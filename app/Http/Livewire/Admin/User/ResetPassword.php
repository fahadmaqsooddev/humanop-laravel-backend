<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\User;
use Livewire\Component;

class ResetPassword extends Component
{
    public $email, $timer = 0;

    protected $rules = [
        'email' => 'required|email',
    ];

    protected $messages = [
        'email.required' => 'Email is required',
    ];

    public function resetPassword()
    {
        $this->validate();

        $checkUserEmail = User::where('email', $this->email)->first();

        if (!empty($checkUserEmail)) {

            $token = User::generateToken($checkUserEmail['email']);

            $baseUrl = url('/reset-password?token=' . $token['reset_password_token']);

            $data = [
                '{$userName}' => $checkUserEmail['first_name'] . ' ' . $checkUserEmail['last_name'],
                '{$link}' => $baseUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'reset-password');

            Email::sendEmailVerification(['content' => $email_template], $checkUserEmail['email'], 'emails.Email_Template', 'Reset Password');

            session()->flash('success', "We have emailed your password reset link!");

            $this->reset();

            $this->emit('startTimer');

        } else {

            session()->flash('error', "Email doesn't exists");


        }
    }

    public function render()
    {
        return view('livewire.admin.user.reset-password');
    }
}
