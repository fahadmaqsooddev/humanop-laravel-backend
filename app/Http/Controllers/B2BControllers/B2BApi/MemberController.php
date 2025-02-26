<?php

namespace App\Http\Controllers\B2BControllers\B2BApi;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\B2B\AddMemberRequest;
use App\Models\User;

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

            $dataArray = $request->only($this->user->getFillable());

            $checkDeletedUser = User::checkDeleteEmail($dataArray['email']);

            $checkUser = User::checkEmail($dataArray['email']);
            
            if($limit==0){
                return Helpers::validationResponse('You have reached the maximum number of members allowed per business.');  // Maximum limit reached. Please contact your business admin.
            }else{

            
            if (!empty($checkDeletedUser)) {

                return Helpers::validationResponse('Your account associated with this email has been frozen. Please contact our technical support team for assistance.');
            }

            if ($checkUser && !empty($checkUser['business_id'])) {

                return Helpers::validationResponse('This member is already associated with a business.');
            }

            if ($checkUser && empty($checkUser['business_id'])) {

                $checkUser->update(['business_id' => Helpers::getUser()['id']]);
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

            $members = User::allBusinessMembers(Helpers::getUser()['id']);

            return Helpers::successResponse('All members', $members);

        } catch (\Exception $exception) {

            return Helpers::serverErrorResponse($exception->getMessage());

        }
    }

}
