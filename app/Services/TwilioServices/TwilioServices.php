<?php

namespace App\Services\TwilioServices;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;
use Exception;
class TwilioServices
{
//    WORKING With twillio




    public static function sendOtp($to, $msg)
    {

        $receiverNumber = $to;
        $message = $msg;
        $account_sid = env("TWILIO_ACCOUNT_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $twilio_number = env("TWILIO_NUMBER"); // Define a Twilio phone number
//
//        $response = $this->verifyPhoneNumber($receiverNumber);
//        $jsonContent = $response->getContent();
//        $validate_data = json_decode($jsonContent, true);
//
//        if ($validate_data['valid'] == true) {
            try {
                if (empty($account_sid) || empty($auth_token) || empty($twilio_number)) {
                    throw new \Exception("Twilio credentials are missing.");
                }

                $client = new Client($account_sid, $auth_token);
                $response = $client->messages->create($receiverNumber, [
                    "from" => $twilio_number,
                    "body" => $message
                ]);
                if ($response->sid) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                return 'error: ' . $e->getMessage();
            }
//        } else {
//            return false;
//        }
    }
    public function verifyPhoneNumber($phoneNumber)
    {
        $account_sid =  env("TWILIO_ACCOUNT_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");

        $client = new Client($account_sid, $auth_token);

        try {
            $number = $client->lookups
                ->phoneNumbers($phoneNumber)
                ->fetch(['type' => 'carrier']);

            // If no exception is thrown, the phone number is valid
            return response()->json(['valid' => true, 'carrier' => $number->carrier['type']]);
        } catch (Exception $e) {
            // Handle exceptions or errors here
            return response()->json(['valid' => false, 'error' => $e->getMessage()]);
        }
    }

    public function sendMsg($data){
        $receiverNumber = $data['number'];
        $message = $data['message'];
        $account_sid = env("TWILIO_AUTH_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $twilio_number = env("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($receiverNumber, [
            'from' => $twilio_number,
            'body' => $message]);
        return 'success';
    }
}
