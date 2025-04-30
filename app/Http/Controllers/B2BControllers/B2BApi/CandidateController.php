<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

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
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {

        $this->middleware('auth:api');

        $this->user = $user;
    }

    public function createInviteLinkForCandidate(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Validation rules for email
            ], [
                'email.required' => 'The email field is required.',
                'email.email' => 'Please provide a valid email address.',
            ]);
            
            if ($validator->fails()) {
                return Helpers::validationResponse('Please Send proper Email Address');
            }
            

            $email = $request->input('email');

            $checkInviteLink = UserInvite::getSingleInvite($email);


            if ($checkInviteLink) {


                $checkCompany = UserCandidateInvite::getSingleInvite($checkInviteLink->id);

                if ($checkCompany && $checkCompany['role'] == Admin::IS_CANDIDATE) {

//                    return Helpers::successResponse("{$email} already has an invite link with your business As a Candidate.");
                    return Helpers::validationResponse("{$email} already has an invite link with your business As a Candidate.");
                } else if ($checkCompany && $checkCompany['role'] == Admin::IS_TEAM_MEMBER) {
//                    return Helpers::successResponse("{$email} already has an invite link with your business As a Member.");
                    return Helpers::validationResponse("{$email} already has an invite link with your business As a Member.");

                } else {

                    UserCandidateInvite::createUserInvite($checkInviteLink->id, 1);

                    $linke = UserInvite::where('email', $email)->first();

                    $url = config('client_url.client_dashboard_url') . '/register?link=' . $linke['link'] . '&company_name=' . Helpers::getUser()['company_name'] . '&prefer=2';

                    $emailData = $this->prepareEmailData($url);

                    $this->sendEmailVerification($emailData, $email, 'b2b-signup-link');

                    return Helpers::successResponse("{$email} invite link generated successfully.");
                }
            }


            $newInvite = UserInvite::createInvite($email);

            if ($newInvite) {

                UserCandidateInvite::createUserInvite($newInvite->id, 1);
                $linke = UserInvite::where('email', $email)->first();
                $url = config('client_url.client_dashboard_url') . '/register?link=' . $linke['link'] . '&company_name=' . Helpers::getUser()['company_name'] . '&prefer=2';
                $emailData = $this->prepareEmailData($url);

                $this->sendEmailVerification($emailData, $email, 'b2b-signup-link');

                return Helpers::successResponse("{$email} invite link generated successfully.");

            }

            return Helpers::serverErrorResponse("Failed to generate invite link for {$email}.");

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

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
                    $checkrole = B2BBusinessCandidates::checkRole($request['candidate_id']);
                    if ($checkrole) {
                        return Helpers::validationResponse('This candidate is  already converted to member');
                    } else {

                        $changerole = B2BBusinessCandidates::changeRole($request['candidate_id']);
                        if ($changerole) {
                            return Helpers::successResponse(' Candidate Change To Member');
                        } else {
                            return Helpers::validationResponse('Not Link With Your Business');
                        }
                        // // $checklimit = B2BBusinessCandidates::CheckLimit(Helpers::getUser()['email']);

                        // if ($checklimit['members_limit'] > 0 && $checklimit['members_limit'] <= $checklimit['total_member_limit']) {
                            
                        // } else {
                        //     return Helpers::validationResponse('Your Business has reached the maximum number of members');
                        // }

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

                    $archive = B2BBusinessCandidates::checkconsideration($request['candidate_id']);

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


            $archivecandidates = B2BBusinessCandidates::AllArchivedCandidates(Helpers::getUser()['id'],true);
            
            foreach($archivecandidates as $newcandidates){
                $newcandidates['users']['gender'] = $newcandidates['users']['gender'] == 0 ? 'Male' : 'Female';
            }

            return Helpers::successResponse('Archive Candidates', $archivecandidates);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function AllDeletedCandidates(Request $request)
    {
        try {


            $deletedcandidates = B2BBusinessCandidates::AlldeletedCandidates(Helpers::getUser()['id']);

            return Helpers::successResponse('Deleted Candidates', $deletedcandidates);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    private function prepareEmailData($url = null,)
    {
        return [
            '{$link}' => $url,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$service}' => url('/term-of-service'),
            '{$privacy}' => url('/privacy-policy'),
        ];
    }

    private function sendEmailVerification($emailData, $recipientEmail, $name)
    {
        $emailTemplate = EmailTemplate::getTemplate($emailData, $name);

        Email::sendEmailVerification(
            ['content' => $emailTemplate],
            $recipientEmail,
            'emails.Email_Template',
            $name
        );
    }

}
