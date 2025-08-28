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

        $userEmailPhone = UserEmailPhoneNumber::createUserEmailPhone($data);

        return Helpers::successResponse('Record created successfully.', $userEmailPhone);
    }

    public static function changeEmailsPhonesConditional($where_clause = [], $data = [])
    {
        return self::where($where_clause)->update($data);
    }

    public function removeEmailPhone($id)
    {
        $emailPhone = UserEmailPhoneNumber::getSingleEmailPhone($id);

        if (!$emailPhone) {
            return Helpers::notFoundResponse('Record Not found.');
        }
        UserEmailPhoneNumber::removeEmailPhone($id);

        return Helpers::successResponse('Record deleted successfully.');
    }

    public function setDefaultEmailPhone($id)
    {
        $emailPhone = UserEmailPhoneNumber::getSingleEmailPhone($id);

        if (!$emailPhone) {
            return Helpers::notFoundResponse('Record Not found.');
        }
        if ($emailPhone->email) {
            UserEmailPhoneNumber::changeEmailsPhonesConditional('phone_no', ['id', '!=', $id,], ['default_email' => Admin::NORMAL_EMAIL]);
            $emailPhone->update(['default_email' => Admin::DEFAULT_EMAIL]);
        } else {
            UserEmailPhoneNumber::changeEmailsPhonesConditional('email', ['id', '!=', $id], ['default_phone_no' => Admin::NORMAL_PHONE]);
            $emailPhone->update(['phone_no' => Admin::DEFAULT_PHONE]);
        }

        return Helpers::successResponse('Default set.', $emailPhone);
    }
}
