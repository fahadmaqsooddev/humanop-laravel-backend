<?php

namespace App\Http\Livewire\Admin\ClientInvites;

use App\Models\UserInvite\UserInvite;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Enums\Admin\Admin;

class ClientInvite extends Component
{
    use WithFileUploads, WithPagination;

    public $email, $file, $searched_email;
    public $role=Admin::CLIENT_INVITE_ROLE;
    public $selectedItems = [];

    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteClientLink','bulkDelete'];

    protected $rules = [
        // 'email' => 'nullable|email|max:255|unique:user_invites,email,NULL,id,deleted_at,NULL|required_without:file',
        'email' => 'nullable|email|max:255|required_without:file',
        'file' => 'nullable|file|mimes:csv,txt|max:10240|required_without:email',
    ];

    protected $messages = [
        'email.required_without' => 'The email is required when a file is not provided.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email should not exceed 255 characters.',
        // 'email.unique' => 'The email address is already Have Invite Link.',
       
        'file.required_without' => 'A file is required when an email is not provided.',
        'file.file' => 'The uploaded file must be a valid file.',
        'file.mimes' => 'Only CSV files are allowed.',
        'file.max' => 'The file size should not exceed 10MB.',
    ];

    public function submitForm()
    { 
        try {
           
       
            $this->validate();
           
            
            if ($this->email) {
                $user = User::where('email', $this->email)->first();
            
                if ($user) {
                    if (!empty($user->email_verified_at)) {
                        session()->flash('success', "{$this->email} already has a Registered account.");
                        return; 
                    }
                }
            
                $softDeletedUser = User::withTrashed()->where('email', $this->email)->first();
                if ($softDeletedUser) {
                    session()->flash('success', "{$this->email} already exists. Please restore or delete it permanently.");
                    return; 
                }
                $uniqueEmail = UserInvite::where('email', $this->email)->first();
                if ($uniqueEmail) {
                    session()->flash('success', "{$this->email} Already Have Invite Link Please Create Account.");
                    return;
                }
            
              
                UserInvite::sendInvite($this->email, $this->file,$this->role);
                session()->flash('success', "{$this->email} invite link generated successfully.");
            }
            


           

            $this->resetForm();

            $this->emit('closeModal');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }


    public function bulkDelete()
    {
      
        UserInvite::whereIn('id', $this->selectedItems)->delete();

        
        $this->selectedItems = [];
    }

    public function updatedSearchedEmail()
    {
    $this->resetPage(); 
    }

    public function resetForm()
    {
        $this->reset(['email']);
    }

    public function deleteClientLink($id){

       UserInvite::deleteInvite(null, $id);
    }

    public function render()
    {

        $invites = UserInvite::getAllInviteLinks($this->perPage, $this->searched_email,$this->role);
        $invites->withPath(url('/admin/client-invites'));
        return view('livewire.admin.client-invites.client-invite', ['invites' => $invites]);
    }
}
