<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\B2B\B2BBusinessCandidates;
use App\Http\Requests\B2B\AddMemberRequest;
use App\Http\Requests\B2B\EditMemberRequest;
use App\Http\Requests\B2B\MembertoCandidate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class MemberController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {

        $this->middleware('auth:api');

        $this->user = $user;
    }

    public function addMember(AddMemberRequest $request)
    {

        try {

            $user = Helpers::getUser();

            $limit = $this->user->MembersLimit($user['email']);

            if (empty($limit)) {

                return Helpers::validationResponse('You have reached the maximum number of candidates allowed per business.');

            } else {

                $dataArray = $request->only($this->user->getFillable());

                $checkDeletedUser = User::checkDeleteEmail($dataArray['email']);

                if (!empty($checkDeletedUser)) {

                    return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
                }

                $checkUser = User::checkEmail($dataArray['email']);

                if (!empty($checkUser)) {

                    $checkCandidate = B2BBusinessCandidates::checkBusinessCandidate($user['id'], $checkUser['id']);

                    if ($checkCandidate == false) {

                        B2BBusinessCandidates::registerCandidate($user['id'], $checkUser['id'], 0, Admin::NOT_SHARED_DATA);

                        User::UpdateMembersLimit($user['email']);

                        $url = config('client_url.client_dashboard_url') . '/login' . '?company_name=' . Helpers::getUser()['company_name'];

                        $emailData = $this->prepareEmailData($checkUser, $url);

                        $this->sendEmailVerification($emailData, $checkUser['email'], 'b2b-login-link');

                        return Helpers::successResponse('This candidate has been successfully linked to your business. Check your email and continue with login.');

                    } else {

                        $url = config('client_url.client_dashboard_url') . '/login' . '?company_name=' . Helpers::getUser()['company_name'];

                        $emailData = $this->prepareEmailData($checkUser, $url);

                        $this->sendEmailVerification($emailData, $checkUser['email'], 'b2b-login-link');

                        return Helpers::successResponse('This candidate is already associated with a business. Check your email and continue with login.');

                    }

                }

                if (empty($checkUser)) {

                    $createMember = User::addB2BMember($dataArray);

                    B2BBusinessCandidates::registerCandidate($user['id'], $createMember['id'], 0, Admin::NOT_SHARED_DATA);

                    User::UpdateMembersLimit($user['email']);

                    $url = config('client_url.client_dashboard_url') . '/login' . '?company_name=' . Helpers::getUser()['company_name'];

                    $emailData = $this->prepareEmailData($createMember, $url);

                    $this->sendEmailVerification($emailData, $createMember['email'], 'b2b-login-link');

                    return Helpers::successResponse('This candidate has been successfully linked to your business. Check your email and continue with login.');
                }
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public function AllMembers(Request $request)
    {
        try {

            $members = B2BBusinessCandidates::allBusinessMembers(Helpers::getUser()['id'], $request['search_name'])->map(function ($member) {

                $member->users->gender = $member->users->gender == Admin::IS_MALE ? 'Male' : 'Female';
                $member->users->status = $member->users->last_login ? 'on-board' : 'pending';

                $member->users->last_login = $member->users->last_login ? Carbon::parse($member->last_login)->format('m/d/Y h:i A') : null;
                $member->user_created_at = $member->created_at ? Carbon::parse($member->created_at)->format('m/d/Y h:i A') : null;
                unset($member->created_at);

                return $member;

            });

            return Helpers::successResponse('All Team members', $members);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    private function prepareEmailData($user = null, $url = null, $codeNumber = null)
    {
        return [
            '{$userName}' => $user['first_name'] . ' ' . $user['last_name'],
            '{$link}' => $url,
            '{$email}' => $user['email'],
            '{$password}' => Session::get('user_password'),
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

    public function EditMember(EditMemberRequest $request)
    {

        try {

            $dataArray = $request->only($this->user->getFillable());

            $user = $this->user->UpdateMember($dataArray, $request['member_id']);

            return Helpers::successResponse('candidate updated successfully.');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }

    }

    public function DeleteMember(Request $request)
    {
        try {

            $user = Helpers::getUser()->id;
            $data = User::where('id', $request['member_id'])->first();

            if ($data->business_id != $user) {
                return Helpers::validationResponse('You are not authorized to delete this candidate.');
            } else {
                $this->user->deleteMember($request['member_id']);
                return Helpers::successResponse('candidate deleted successfully.');

            }


        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function ConvertMember(MembertoCandidate $request)
    {
        try {

            $data = $request['member_id'];
            if ($data) {
                $status = B2BBusinessCandidates::getInfo($request['member_id']);
                if ($status) {
                    return Helpers::validationResponse('This member is  already deleted');
                } else {
                    $checkrole = B2BBusinessCandidates::checkRole($request['member_id']);

                    if ($checkrole) {
                        $checklimit = B2BBusinessCandidates::CheckLimit(Helpers::getUser()['email']);

                        if ($checklimit['members_limit'] < $checklimit['total_member_limit']) {

                            $changerole = B2BBusinessCandidates::newchangeRole($request['member_id']);

                            if ($changerole) {

                                return Helpers::successResponse(' Member  Change To Candidate');

                            } else {

                                return Helpers::validationResponse('Not Link With Your Business');

                            }

                        } else {

                            return Helpers::validationResponse('You have reached the maximum number of members allowed per business.');
                        }


                    } else {
                        return Helpers::validationResponse('Already Converted to member');
                    }
                }
            } else {
                return Helpers::validationResponse('Failed to find member id');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

}
