<?php

namespace App\Http\Controllers\Api\ClientController\UserProfile;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Client\AddUserEmailPhoneRequest;
use App\Models\UserEmailPhoneNumber;

class SettingController extends Controller
{
    public function getUserEmailsPhones()
    {
        $user = Helpers::getUser();
        $emails_phones_no = UserEmailPhoneNumber::getUserEmailsPhones($user->id);

        return Helpers::successResponse('User emails and phone numbers.', $emails_phones_no);
    }

    public function createUserEmailPhone(AddUserEmailPhoneRequest $request)
    {
        $data = $request->all();
        $data['default_email'] = $request->has('email') ? Admin::NORMAL_EMAIL : null;
        $data['default_phone_no'] = $request->has('phone_no') ? Admin::NORMAL_PHONE : null;
        $data['user_id'] = Helpers::getUser()->id;

        $userEmailPhone = UserEmailPhoneNumber::createUserEmail($data);

        return Helpers::successResponse('Record created successfully.', $userEmailPhone);
    }
}
