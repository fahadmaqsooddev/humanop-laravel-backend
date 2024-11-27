<?php

namespace App\Http\Livewire\Admin\ClientInvites;

use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\UserInvite\UserInvite;
use Illuminate\Support\Str;
use Livewire\Component;

class ClientInvite extends Component
{

    public $invites, $email;

    protected $rules = [
        'email' => 'required|email|max:255|unique:user_invites,email,NULL,id,deleted_at,NULL',
    ];

    protected $messages = [
        'email.required' => 'The email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email should not exceed 255 characters.',
        'email.unique' => 'The email address is already registered.',
    ];

    public function getInvites()
    {

        $this->invites = UserInvite::getAllInviteLinks();
    }


    public function submitForm()
    {
        try {

            $this->validate();

            UserInvite::sendInvite($this->email);

            $getInvite = UserInvite::getSingleInvite($this->email);


            $baseUrl = url('/register?link='. $getInvite['link']);

            $data = [
                '{$link}' =>  $baseUrl,
            ];

            $email_template = EmailTemplate::getTemplate($data, 'invite-link');

            dd($this->email);
            
            Email::sendEmailVerification(['content' => $email_template], $this->email,'emails.Email_Template', 'Invite link for Signup');

            session()->flash('success', "{$this->email} send invite link successfully.");

            $this->resetForm();

            $this->emit('closeModal');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    public function resetForm()
    {
        $this->reset(['email']);
    }

    public function render()
    {
        $this->getInvites();
        return view('livewire.admin.client-invites.client-invite');
    }
}
