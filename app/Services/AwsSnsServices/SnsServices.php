<?php

namespace App\Services\AwsSnsServices;

use App\Models\Notification\SmsNotification;
use Aws\Sns\SnsClient;
use Illuminate\Support\Facades\Crypt;

class SnsServices
{

    protected $sns;

    public function __construct()
    {
        $credential = SmsNotification::getCredential();

        $this->sns = new SnsClient([
//            'region' => env('AWS_DEFAULT_REGION'),
            'region'  => trim(Crypt::decryptString($credential['region']), '"'),
            'version' => '2010-03-31',
            'credentials' => [
//                'key' => env('AWS_ACCESS_KEY_ID'),
//                'secret' => env('AWS_SECRET_ACCESS_KEY'),
                'key'    => trim(Crypt::decryptString($credential['public_key']), '"'),
                'secret' => trim(Crypt::decryptString($credential['secret_key']), '"'),
            ],
        ]);
    }

    public function sendSms($phoneNumber, $message)
    {
        return $this->sns->publish([
            'Message'     => $message,
            'PhoneNumber' => $phoneNumber,
        ]);
    }

}
