<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Http\Requests\B2B\CandidatetoMember;
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
use App\Models\UserInvite\UserInvite;
use App\Models\B2B\UserCandidateInvite;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {

        $this->middleware('auth:api');

        $this->user = $user;
    }


    public function createInviteLinkForMember(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ]);
            
            if ($validator->fails()) {
                return Helpers::validationResponse('Please Send proper Email Address');
            }
            
            
            $email = $request->input('email');

            $checkInviteLink = UserInvite::getSingleInvite($email);
            

            if ($checkInviteLink) {

                $checkCompany = UserCandidateInvite::getSingleInvite($checkInviteLink->id);

                if ($checkCompany && $checkCompany['role'] == Admin::IS_CANDIDATE) {


                    return Helpers::validationResponse("{$email} already has an invite link with your business As a Candidate.");

                } else if ($checkCompany && $checkCompany['role'] == Admin::IS_TEAM_MEMBER) {

                    return Helpers::validationResponse("{$email} already has an invite link with your business As a Member.");

                } else {


                    $userRecord=User::where('email',$email)->first();
                    
                    if($userRecord){

                       $result= B2BBusinessCandidates::where('business_id',Helpers::getUser()['id'])->where('candidate_id',$userRecord['id'])->where('future_consideration',Admin::IN_FUTURE)->first();
                    
                       if($result){

                        return Helpers::validationResponse("{$email} already has an Account with your business in A Future Consideration.");
                       
                    }

                    }

                    UserCandidateInvite::createUserInvite($checkInviteLink->id, 0);

                    $linke = UserInvite::where('email', $email)->first();

                    $url = config('client_url.client_dashboard_url') . '/register?link=' . $linke['link'] . '&company_name=' . Helpers::getUser()['company_name'] . '&prefer=1';

                    $emailData = $this->myprepareEmailData($url);

                    $this->mysendEmailVerification($emailData, $email, 'b2b-signup-link');

                    return Helpers::successResponse("{$email} invite link generated successfully.");
                }
            }


            $newInvite = UserInvite::createInvite($email);

            if ($newInvite) {

                $userRecord=User::where('email',$email)->first();
                    
                    if($userRecord){

                       $result= B2BBusinessCandidates::where('business_id',Helpers::getUser()['id'])->where('candidate_id',$userRecord['id'])->where('future_consideration',Admin::IN_FUTURE)->first();
                      
                       if($result){

                        return Helpers::validationResponse("{$email} already has an Account with your business in A Future Consideration.");
                       
                    }

                    }

                UserCandidateInvite::createUserInvite($newInvite->id, 0);

                $linke = UserInvite::where('email', $email)->first();

                $url = config('client_url.client_dashboard_url') . '/register?link=' . $linke['link'] . '&company_name=' . Helpers::getUser()['company_name'] . '&prefer=1';
               
                $emailData = $this->myprepareEmailData($url);

                $this->mysendEmailVerification($emailData, $email, 'b2b-signup-link');

                return Helpers::successResponse("{$email} invite link generated successfully.");

            }

            return Helpers::serverErrorResponse("Failed to generate invite link for {$email}.");

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }

    }

    public static function allMemberInvites()
    {
        try {

            $invites = UserCandidateInvite::allMemberInvites();

            $memberInvites = [];

            foreach ($invites as $invite) {
                $id = $invite['id'];
                $companyName = $invite['user']['company_name'] ?? 'N/A';
                $inviteLink = $invite['inviteLinks']['link'] ?? 'N/A';
                $email = $invite['inviteLinks']['email'] ?? 'N/A';

                $memberInvites[] = [
                    'id' => $id,
                    'invite_link' => config('client_url.client_dashboard_url') . '/register?link=' . $inviteLink . '&company_name=' . $companyName . '&prefer=1',
                    'email' => $email,
                    'company_name' => $companyName
                ];
            }

            return Helpers::successResponse("All Member invites.", $memberInvites);

        } catch (\Exception $exception) {
            return Helpers::serverErrorResponse($exception->getMessage());
        }
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

            $members = B2BBusinessCandidates::allBusinessMembers(Helpers::getUser()['id']);

            $formattedmembers = [];

            foreach ($members as $member) {

                if (!empty($member['users'])) {

                    $member['users']['gender'] = $member['users']['gender'] == 0 ? 'Male' : 'Female';

                    $member['users']['status'] = $member['users']['last_login'] ? 'on-board' : 'pending';

                    $member['users']['last_login'] = $member['users']['last_login'] ? Carbon::parse($member['users']['last_login'])->format('m/d/Y h:i A') : null;


                    $member['user_created_at'] = $member['created_at'] ? Carbon::parse($member['created_at'])->format('m/d/Y h:i A') : null;

                    unset($member['created_at']);

                    if (isset($member['share_data']) && $member['share_data'] == Admin::NOT_SHARED_DATA) {
                        unset($member['assessments']);
                    }


                    $formattedmembers[] = $member;
                }
            }

            return Helpers::successResponse('All Team members', $formattedmembers);


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

            $member = B2BBusinessCandidates::checkBusinessCandidate($user, $request['member_id']);

            if (empty($member)) {

                return Helpers::validationResponse('You are not authorized to delete this Member.');

            } else {

                B2BBusinessCandidates::DeletedCandidate($request['member_id']);

                return Helpers::successResponse('Member deleted successfully.');

            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function ArchivesingleMember(Request $request)
    {

        try {

            if (!empty($request['member_id'])) {

                $status = B2BBusinessCandidates::getInfo($request['member_id']);

                if ($status) {

                    return Helpers::validationResponse('This Member is already deleted with your business.');

                } else {

                    $archive = B2BBusinessCandidates::checkconsideration($request['member_id']);

                    if ($archive) {

                        return Helpers::validationResponse('This Member is already archived.');

                    } else {

                        $member = B2BBusinessCandidates::ArchivedCandidate($request['member_id']);

                        if ($member) {

                            return Helpers::successResponse('Member archive successfully.');

                        } else {

                            return Helpers::validationResponse('Failed to archive the Member.');
                        }
                    }

                }

            } else {
                return Helpers::validationResponse('Failed to find Member id');
            }


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function checkFutureConsiderationShareData(CandidatetoMember $request)
    {

        try {

            $futureConsideration = B2BBusinessCandidates::checkFutureConsiderationShareData($request['candidate_id']);

            if (!empty($futureConsideration)) {

                $data = [
                    'company_name' => $futureConsideration['busers']['company_name'],
                ];

                return Helpers::successResponse('Future Consideration', $data);
            }

            return Helpers::validationResponse('You are not Future Consideration');

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

    public function futureConsiderationShareData(CandidatetoMember $request)
    {
        try {
            $futureConsideration = B2BBusinessCandidates::where('candidate_id', $request->candidate_id)
                ->where('future_consideration', 1)
                ->where('role', Admin::IS_TEAM_MEMBER)
                ->first();

            if (!$futureConsideration) {
                return Helpers::notFoundResponse('Candidate not found or not marked for future consideration.');
            }

            $futureConsideration->update([
                'future_consideration_share_date' => 1,
            ]);

            return Helpers::successResponse('Future Consideration Share data updated successfully.');

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


    public function DeleteInvite(Request $request)
    {
        try {

            if (!empty($request['invite_id'])) {

                $check = UserCandidateInvite::getMemberInvite($request['invite_id']);

                if (!empty($check)) {

                    $getinvite = UserInvite::getMemberInvite($check['invite_link_id']);

                    if (!empty($getinvite)) {

                        $getmember = User::checkEmail($getinvite['email']);

                        if (!empty($getmember) && $getmember['step'] == 3) {

                            $result = B2BBusinessCandidates::getMemberRecord($check['company_id'], $getmember['id']);

                            if (!empty($result)) {

                                UserCandidateInvite::deleteMemberInvite($request['invite_id']);

                                return Helpers::successResponse('Member Invite deleted successfully.');

                            } else {

                                return Helpers::validationResponse('User Did Not complete his Signup process yet');

                            }

                        } else {
                            return Helpers::validationResponse('User Did Not complete his Signup process yet');
                        }

                    }


                } else {
                    return Helpers::validationResponse('Please enter valid invite id');
                }

            } else {
                return Helpers::validationResponse('Please enter invite id');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());
        }
    }


    public function AllArchiveMembers(Request $request)
    {
        try {


            $archivemembers = B2BBusinessCandidates::AllArchivedCandidates(Helpers::getUser()['id'], false);
            foreach ($archivemembers as $newmembers) {
                $newmembers['users']['gender'] = $newmembers['users']['gender'] == 0 ? 'Male' : 'Female';

            }

            return Helpers::successResponse('Archive Members', $archivemembers);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    public function requestAccessData(Request $request)
    {
        try {
            if (empty($request['member_id'])) {
                return Helpers::validationResponse('Please Enter a Member id');
            } else {
                B2BBusinessCandidates::requestAccess($request['member_id']);
                return Helpers::successResponse('Request For Access Data is Send');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }


    private function myprepareEmailData($url = null,)
    {
        return [
            '{$link}' => $url,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$service}' => url('/term-of-service'),
            '{$privacy}' => url('/privacy-policy'),
        ];
    }

    private function mysendEmailVerification($emailData, $recipientEmail, $name)
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
