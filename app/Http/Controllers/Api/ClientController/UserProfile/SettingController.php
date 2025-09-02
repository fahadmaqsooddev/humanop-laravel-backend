<?php

namespace App\Http\Controllers\Api\ClientController\UserProfile;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\AddUserEmailPhoneRequest;
use App\Models\User;
use App\Models\UserEmailPhoneNumber;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    protected $userRecord;

    public function __construct(UserEmailPhoneNumber $userRecord)
    {
        $this->middleware('auth:api');

        $this->userRecord = $userRecord;

    }

    public function getUserEmailsPhones()
    {
        $user = Helpers::getUser();

        $emails_phones_no = UserEmailPhoneNumber::getUserEmailsPhones($user->id);

        return Helpers::successResponse('User emails and phone numbers.', $emails_phones_no);
    }

    public function createUserEmailPhone(AddUserEmailPhoneRequest $request)
    {

        $dataArray = $request->only($this->userRecord->getFillable());
//        dd($dataArray);

        $userEmailPhone = UserEmailPhoneNumber::createUserEmailPhone($dataArray);

        return Helpers::successResponse('Record created successfully.', $userEmailPhone);
    }

    public function removeEmailPhone(Request $request)
    {
        $checkRecordDelete = UserEmailPhoneNumber::removeEmailPhone($request['id']);

        if ($checkRecordDelete){

            return Helpers::successResponse('Record deleted successfully.');

        }else{

            return Helpers::validationResponse('Record not found');
        }
    }

    public function setDefaultEmailPhone(Request $request)
    {
        $emailPhone = UserEmailPhoneNumber::getSingleEmailPhone($request['id']);

        dd($request->all());
        if (!$emailPhone) {
            return Helpers::notFoundResponse('Record Not found.');
        }
        if ($emailPhone->email) {
            UserEmailPhoneNumber::changeEmailsPhonesConditional('phone_no', ['id', '!=', $request['id'],], ['default_email' => Admin::NORMAL_EMAIL]);
            $emailPhone->update(['default_email' => Admin::DEFAULT_EMAIL]);
        } else {
            UserEmailPhoneNumber::changeEmailsPhonesConditional('email', ['id', '!=', $request['id']], ['default_phone_no' => Admin::NORMAL_PHONE]);
            $emailPhone->update(['phone_no' => Admin::DEFAULT_PHONE]);
        }

        return Helpers::successResponse('Default set.', $emailPhone);
    }
}
