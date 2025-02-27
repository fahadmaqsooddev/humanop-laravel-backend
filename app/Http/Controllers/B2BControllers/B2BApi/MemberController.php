<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\B2B\AddMemberRequest;
use App\Http\Requests\B2B\EditMemberRequest;
use App\Models\User;
use Carbon\Carbon;

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

            $limit=$this->user->MembersLimit($user['email']);
          
           
            if(empty($limit)){

                return Helpers::validationResponse('You have reached the maximum number of members allowed per business.');  

            }

            else

            {

                  
            $dataArray = $request->only($this->user->getFillable());
      
            $checkDeletedUser = User::checkDeleteEmail($dataArray['email']);
             
            
            if (!empty($checkDeletedUser)) {
                
                return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
            }

            $checkUser = User::checkEmail($dataArray['email']);

            if ($checkUser && !empty($checkUser['business_id'])) {

                return Helpers::validationResponse('This member is already 
                associated with a busines');

            }

            if ($checkUser && empty($checkUser['business_id'])) {
             
                $checkUser->update(['business_id' => $user['id']]);

                User::UpdateMembersLimit($user['email']);
                
                return Helpers::successResponse('This member successfully linked to your business.');

            }

            if (empty($checkUser)) {
             
                $createMember = User::addB2BMember($dataArray);

                Helpers::createClientsOnOneSignal($createMember['id']);

                return Helpers::successResponse('This member Linked successfully With Your Business.', [
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

    public function AllMembers()
    {
        try {

            $members = User::allBusinessMembers(Helpers::getUser()['id'])
           ->map(function ($member) {
            $member->status = $member->last_login ? 'on-board' : 'pending';
            $member->last_login = $member->last_login 
            ? Carbon::parse($member->last_login)->format('m/d/Y h:i A') 
            : null;

            return $member;
            });

            return Helpers::successResponse('All members', $members);


        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }



    public function EditMember(EditMemberRequest $request){
    
       try {
        //code...
       } catch (\Throwable $th) {
        //throw $th;
       }

    }

}
