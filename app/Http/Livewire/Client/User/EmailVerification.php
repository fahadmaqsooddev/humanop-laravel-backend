<?php

namespace App\Http\Livewire\Client\User;

use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\User;
use Livewire\Component;

class EmailVerification extends Component
{
    public $token;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function emailVerification()
    {

        $user = User::where('email_verify_token', $this->token)->first();

        if (!empty($user) && $user['email_verified_at'] == null)
        {
            $updateUser = User::generateEmailVerificationToken($user['email']);

            $baseUrl = url('/check-email?token='. $updateUser['email_verify_token']);

            $data = [
                '{$userName}' => $updateUser['first_name'] .' ' . $updateUser['last_name'],
                '{$link}' =>  $baseUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'email-verification');

            Email::sendEmailVerification(['content' => $email_template], $updateUser['email'],'emails.Email_Template', 'Email Verification');

            session()->flash('success', "Resend email sent successfully!");

            $this->emit('startTimer');

        }
        else
        {
            session()->flash('error', "Email doesn't exists");

        }
    }

    public function render()
    {
        return view('livewire.client.user.email-verification');
    }
}
