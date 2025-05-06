<?php

namespace App\Http\Livewire\B2b\B2binvites;

use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use Livewire\Component;
use App\Models\UserInvite\UserInvite;
use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Enums\Admin\Admin;
use Illuminate\Support\Facades\URL;

class B2bInvite extends Component
{
    use WithFileUploads, WithPagination;

    public $email, $file, $searched_email,  $total_member_limit;
// public $members_limit=10;
    public $invite_id;

    public $selectedItems = [];
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteClientLink', 'bulkDelete','copyLink'];

    public $role = Admin::B2B_INVITE_ROLE;

    protected $rules = [
        'email' => 'nullable|email|max:255|required_without:file|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
        'file' => 'nullable|file|mimes:csv,txt|max:10240|required_without:email',
    ];

    protected $messages = [
        'email.required_without' => 'The email is required when a file is not provided.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'The email should not exceed 255 characters.',
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

                    $data=UserInvite::sendInvite($this->email, $this->file, $this->role);


                    $url = config('client_url.b2b_dashboard_url') . '/check-email?b2b-signup-link=' . $data['link'];

                    $emailData = $this->myprepareEmailData($url);

                    $this->mysendEmailVerification($emailData, $this->email, 'b2b-maestro-signup');

                    session()->flash('success', "{$this->email} invite link generated successfully.");

                }

            

            $this->resetForm();

            $this->emit('closeModal');

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());

        }

    }

    private function myprepareEmailData($url = null)
    {
        return [
            '{$link}' => $url,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$service}' => url('/term-of-service'),
            '{$privacy}' => url('/privacy-policy'),
        ];
    }

    private function mysendEmailVerification($emailData, $recipientEmail, $name)
    {
        $emailTemplate = EmailTemplate::getTemplate($emailData, $name);

        Email::sendEmailVerification(
            ['content' => $emailTemplate],
            $recipientEmail,
            'emails.Email_Template',
            $name
        );
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
        $this->reset(['email', 'invite_id']);
    }

    public function deleteClientLink($id)
    {

        UserInvite::deleteInvite(null, $id);
    }


    public function copyLink($id)
    {

        UserInvite::sendInviteTime($id);
    }


    public function editLimit($id)
    {
        $invite = UserInvite::find($id);

        if ($invite) {
            $this->invite_id = $invite->id;
            $this->total_member_limit = !empty($invite->total_member_limit) ? $invite->total_member_limit : 0;
        }
    }

    public function render()
    {

        $invites = UserInvite::getAllInviteLinks($this->perPage, $this->searched_email, $this->role);

        $invites->withPath(url('/admin/b2b-invites'));

        return view('livewire.b2b.b2b-invites.b2b-invite', ['invites' => $invites]);
    }

}
