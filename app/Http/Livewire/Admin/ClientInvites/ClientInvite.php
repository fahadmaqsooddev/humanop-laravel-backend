<?php

namespace App\Http\Livewire\Admin\ClientInvites;

use App\Models\UserInvite\UserInvite;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientInvite extends Component
{
    use WithFileUploads;

    public $invites, $email, $file;

    protected $rules = [
        'email' => 'nullable|email|max:255|unique:user_invites,email,NULL,id,deleted_at,NULL|required_without:file',
        'file' => 'nullable|file|mimes:csv,txt|max:10240|required_without:email',
    ];

    protected $messages = [
        'email.required_without' => 'The email is required when a file is not provided.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email should not exceed 255 characters.',
        'email.unique' => 'The email address is already registered.',
        'file.required_without' => 'A file is required when an email is not provided.',
        'file.file' => 'The uploaded file must be a valid file.',
        'file.mimes' => 'Only CSV files are allowed.',
        'file.max' => 'The file size should not exceed 10MB.',
    ];


    public function getInvites()
    {

        $this->invites = UserInvite::getAllInviteLinks();
    }


    public function submitForm()
    {
        try {

            $this->validate();

            UserInvite::sendInvite($this->email, $this->file);

            session()->flash('success', "{$this->email} invite link generated successfully.");

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
