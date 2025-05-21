<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Http\Requests\Api\Client\CheckEmailRequest;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserInvite\UserInvite;
use App\Models\B2B\UserCandidateInvite;
use App\Models\B2B\B2BBusinessCandidates;
use App\Http\Requests\B2B\CandidatetoMember;
use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class CandidateController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {

        $this->middleware('auth:api');

        $this->user = $user;
    }

    public function createInviteLinkForCandidate(CheckEmailRequest $request)
    {
        DB::beginTransaction();
        try {

            $email = $request['email'];

            $currentUser = Helpers::getUser();

            if ($currentUser['email'] === $email) {

                return Helpers::validationResponse('It’s a B2B Admin Account. You can directly login to HumanOP Account.');
            }

            $existingInvite = UserInvite::getSingleInvite($email);

            $user = User::checkEmail($email);

            if ($existingInvite) {

                $existingCandidate = UserCandidateInvite::getSingleInvite($existingInvite['id']);

                if ($existingCandidate) {

                    return $this->inviteAlreadyExistsResponse($email, $existingCandidate['role']);
                }

                if ($user) {

                    $candidateRecord = B2BBusinessCandidates::where([['business_id', $currentUser['id']], ['candidate_id', $user['id']]])->first();

                    if ($candidateRecord) {

                        return $this->inviteAlreadyExistsResponse($email, $candidateRecord['role']);
                    }
                }
                DB::commit();
                return $this->createAndSendCandidateInvite($existingInvite['id'], $email, $currentUser['company_name']);
            }


            $newInvite = UserInvite::createInvite($email);

            if ($newInvite) {

                DB::commit();
                return $this->createAndSendCandidateInvite($newInvite['id'], $email, $currentUser['company_name']);
            }


            return Helpers::serverErrorResponse("Failed to generate invite link for {$email}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return Helpers::serverErrorResponse($e->getMessage());
        }
    }

    private function inviteAlreadyExistsResponse($email, $role)
    {

        $roleText = $role == Admin::IS_CANDIDATE ? 'Candidate' : 'Member';

        return Helpers::validationResponse("{$email} already has an invite/account with your business as a {$roleText}.");
    }

    private function createAndSendCandidateInvite($inviteId, $email, $companyName)
    {
        UserCandidateInvite::createUserInvite($inviteId, Admin::IS_CANDIDATE);

        $invite = UserInvite::getSingleInvite($email);

        $url = config('client_url.client_dashboard_url') . '/register?link=' . $invite['link'] . '&company_name=' . $companyName . '&prefer=2';

        $emailData = $this->prepareEmailData($url);

        $this->sendEmailVerification($emailData, $email, 'b2b-signup-link');

        return Helpers::successResponse("{$email} invite link generated successfully.");
    }

    public static function allCandidates()
    {
        try {
            $invites = UserCandidateInvite::allCandidateInvites();

            $candidateInvites = [];

            foreach ($invites as $invite) {

                $companyName = $invite['user']['company_name'] ?? 'N/A';
                $inviteLink = $invite['inviteLinks']['link'] ?? 'N/A';
                $email = $invite['inviteLinks']['email'] ?? 'N/A';

                $candidateInvites[] = [
                    'invite_link' => config('client_url.client_dashboard_url') . '/register?link=' . $inviteLink . '&company_name=' . $companyName . '&prefer=2',
                    'email' => $email,
                    'company_name' => $companyName
                ];
            }

            return Helpers::successResponse("All candidate invites.", $candidateInvites);
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function getAllCandidates()
    {
        try {
            $candidates = B2BBusinessCandidates::allBusinessCandidates(Helpers::getUser()['id']);

            $formattedCandidates = [];

            foreach ($candidates as $candidate) {

                if (!empty($candidate['users'])) {
                    // dd(1);
                    $candidate['users']['gender'] = $candidate['users']['gender'] == 0 ? 'Male' : 'Female';

                    $candidate['users']['status'] = $candidate['users']['last_login'] ? 'on-board' : 'pending';

                    $candidate['users']['last_login'] = $candidate['users']['last_login'] ? Carbon::parse($candidate['users']['last_login'])->format('m/d/Y h:i A') : null;


                    $candidate['user_created_at'] = $candidate['created_at'] ? Carbon::parse($candidate['created_at'])->format('m/d/Y h:i A') : null;


                    unset($candidate['created_at']);

                    if (isset($candidate['share_data']) && $candidate['share_data'] == Admin::NOT_SHARED_DATA) {
                        unset($candidate['assessments']);
                    }

                    $formattedCandidates[] = $candidate;
                }
            }


            return Helpers::successResponse('All candidates', $formattedCandidates);
        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function ConvertCandidate(CandidatetoMember $request)
    {
        try {

            $data = $request['candidate_id'];

            if ($data) {

                $status = B2BBusinessCandidates::getInfo($request['candidate_id']);

                if ($status) {

                    return Helpers::validationResponse('This candidate is  already deleted');
                } else {

                    $checkRole = B2BBusinessCandidates::checkRole($request['candidate_id']);

                    if ($checkRole) {

                        return Helpers::validationResponse('This candidate is  already converted to member');
                    } else {

                        $changeRole = B2BBusinessCandidates::changeRole($request['candidate_id']);

                        if ($changeRole) {

                            return Helpers::successResponse(' Candidate Change To Member');
                        } else {

                            return Helpers::validationResponse('Not Link With Your Business');
                        }
                    }
                }
            } else {
                return Helpers::validationResponse('Failed to find candidate id');
            }
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function DeletesingleCandidate(Request $request)
    {
        try {

            if (!empty($request['candidate_id'])) {

                $status = B2BBusinessCandidates::getInfo($request['candidate_id']);

                if ($status) {

                    return Helpers::validationResponse('This Candidate is already deleted from your business.');
                } else {

                    $candidate = B2BBusinessCandidates::DeletedCandidate($request['candidate_id']);

                    if ($candidate) {

                        return Helpers::successResponse('Candidate deleted successfully.');
                    } else {

                        return Helpers::validationResponse('Failed to delete the candidate.');
                    }
                }
            } else {

                return Helpers::validationResponse('Failed to find candidate ID.');
            }
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }

    public function ArchivesingleCandidate(Request $request)
    {

        try {

            if (!empty($request['candidate_id'])) {

                $status = B2BBusinessCandidates::getInfo($request['candidate_id']);

                if ($status) {

                    return Helpers::validationResponse('This Candidate is already deleted with your business.');
                } else {

                    $archive = B2BBusinessCandidates::checkConsideration($request['candidate_id']);

                    if ($archive) {

                        return Helpers::validationResponse('This Candidate is already archived.');
                    } else {

                        $candidate = B2BBusinessCandidates::ArchivedCandidate($request['candidate_id']);

                        if ($candidate) {

                            return Helpers::successResponse('Candidate archive successfully.');
                        } else {

                            return Helpers::validationResponse('Failed to archive the candidate.');
                        }
                    }
                }
            } else {
                return Helpers::validationResponse('Failed to find candidate id');
            }
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function AllArchiveCandidates(Request $request)
    {
        try {

            $archiveCandidates = B2BBusinessCandidates::AllArchivedCandidates(Helpers::getUser()['id'], true);

            foreach ($archiveCandidates as $newCandidates) {
                $newCandidates['users']['gender'] = $newCandidates['users']['gender'] == 0 ? 'Male' : 'Female';
            }

            return Helpers::successResponse('Archive Candidates', $archiveCandidates);
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function AllDeletedCandidates(Request $request)
    {
        try {

            $deletedCandidates = B2BBusinessCandidates::AlldeletedCandidates(Helpers::getUser()['id']);

            return Helpers::successResponse('Deleted Candidates', $deletedCandidates);
        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    private function prepareEmailData($url = null)
    {
        return [
            '{$link}' => $url,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$service}' => url('/term-of-service'),
            '{$privacy}' => url('/privacy-policy'),
        ];
    }

    private function sendEmailVerification($emailData = null, $recipientEmail = null, $name = null)
    {
        $emailTemplate = EmailTemplate::getTemplate($emailData, $name);

        Email::sendEmailVerification(['content' => $emailTemplate], $recipientEmail, 'emails.Email_Template', $name);
    }
}
