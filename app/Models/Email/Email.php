<?php

namespace App\Models\Email;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Email extends Model
{
    use HasFactory;

    public static function sendEmailVerification($data = null, $email =  null, $viewName = null, $subject = null){

        Mail::send($viewName, $data, function ($message) use ($email,$subject){

            $message->to($email)
                ->subject($subject);

            if (!empty(config('mail.from.address')) && !empty(config('mail.from.name'))){

                $message->from(
                    config('mail.from.address'),
                    config('mail.from.name')
                );
            }

        });

    }

}
