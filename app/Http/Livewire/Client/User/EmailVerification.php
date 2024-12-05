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
            $logoUrl = URL::asset('assets/logos/HumanOp Logo.png');
            $privacyUrl = url('/privacy-policy');
            $serviceUrl = url('/term-of-service');

            $data = [
                '{$userName}' => $updateUser['first_name'] .' ' . $updateUser['last_name'],
                '{$link}' =>  $baseUrl,
                '{$logo}' => $logoUrl,
                '{$service}' => $serviceUrl,
                '{$privacy}' => $privacyUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'email-verification');

            Email::sendEmailVerification(['content' => $email_template], $updateUser['email'],'emails.Email_Template', 'Email Verification');

            session()->flash('success', "Resend email sent successfully!");

            $this->emit('startTimer');

        }
        else
        {
            session()->flash('success', "You are already verified.");

            return redirect()->to('/login');

        }
    }

    public function render()
    {
        return view('livewire.client.user.email-verification');
    }
}
