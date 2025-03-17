<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

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

                        return Helpers::successResponse('This candidate successfully linked to your business.');

                    } else {

                        return Helpers::validationResponse('This candidate is already associated with a business');

                    }

                }

                if (empty($checkUser)) {

                    $createMember = User::addB2BMember($dataArray);

                    B2BBusinessCandidates::registerCandidate($user['id'], $createMember['id'],0);

                    User::UpdateMembersLimit($user['email']);

                    return Helpers::successResponse('This candidate Linked successfully With Your Business.', [
                        'authorization' => [
                            'status' => true,
                            'type' => 'bearer',
                        ],

                    ]);
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

                $member->users->gender = $member->users->gender ==  Admin::IS_MALE ? 'Male' : 'Female';
                $member->users->status = $member->users->last_login ? 'on-board' : 'pending';

                $member->users->last_login = $member->users->last_login ? Carbon::parse($member->last_login)->format('m/d/Y h:i A') : null;


                return $member;

            });

            return Helpers::successResponse('All Team members', $members);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
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



    public function ConvertMember(MembertoCandidate $request){
        try {

            $data= $request['member_id'];
            if($data){
                $status = B2BBusinessCandidates::getInfo($request['member_id']);
                if($status){
                    return Helpers::validationResponse('This member is  already deleted');
                }else{
                    $checkrole=B2BBusinessCandidates::checkRole($request['member_id']);

                    if($checkrole){
                        $checklimit=B2BBusinessCandidates::CheckLimit(Helpers::getUser()['email']);

                        if($checklimit['members_limit'] < $checklimit['total_member_limit']){

                            $changerole=B2BBusinessCandidates::newchangeRole($request['member_id']);

                            if($changerole){
    
                                return Helpers::successResponse(' Member  Change To Candidate');
    
                            }else{
    
                                return Helpers::validationResponse('Not Link With Your Business');
    
                            }
                            
                        } else {
                            
                            return Helpers::validationResponse('You have reached the maximum number of members allowed per business.');
                        }
                       
                    }else{
                        return Helpers::validationResponse('Already Converted to member');
                    }
                }
            }else{
                return Helpers::validationResponse('Failed to find member id');
            }

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

}
