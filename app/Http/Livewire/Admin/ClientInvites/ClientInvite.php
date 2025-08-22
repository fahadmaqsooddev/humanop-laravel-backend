<?php

namespace App\Http\Livewire\Admin\ClientInvites;

use App\Models\Email\Email;
use App\Models\Email\EmailTemplate;
use App\Models\UserInvite\UserInvite;
use App\Models\User;
use App\Models\UserInvite\UserInviteLog;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Enums\Admin\Admin;

class ClientInvite extends Component
{
    use WithFileUploads, WithPagination;

    public $email, $file, $searched_email;
    public $role = Admin::CLIENT_INVITE_ROLE;
    public $selectedItems = [];
    protected $invites = [];
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['deleteClientLink', 'bulkDelete', 'copyClipboard'];

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

    protected $updatesQueryString = [

        'email' => ['except' => '']

    ];

    public function submitForm()
    {
        try {

            $this->validate();

            if ($this->email) {

                $uniqueEmail = UserInvite::where('email', $this->email)->first();

                if (empty($uniqueEmail)) {

                    $data = UserInvite::sendInvite($this->email, $this->file);

                    $inviteLink = UserInvite::where('id', $data['invite_id'])->first();

                    $url = config('client_url.client_dashboard_url') . '/register?link=' . $inviteLink['link'];

                    $template = EmailTemplate::getEmailTemplateByTag(Admin::B2C_SIGNUP_LINK);

                    $emailData = $this->myprepareEmailData($url, $template->body, $template->subject);

                    $this->mysendEmailVerification($emailData, $this->email, Admin::B2C_SIGNUP_LINK);

                    session()->flash('success', "{$this->email} invite link generated successfully.");

                }else{

                    $checkB2BInvite = UserInviteLog::checkB2CInvite($uniqueEmail['id']);

                    if (empty($checkB2BInvite)) {

                        $data['id'] = $uniqueEmail['id'];

                        $data['role'] = Admin::CLIENT_INVITE_ROLE;

                        UserInviteLog::createInvite($data);

                        $url = config('client_url.client_dashboard_url') . '/register?link=' . $uniqueEmail['link'];

                        $template = EmailTemplate::getEmailTemplateByTag(Admin::B2C_SIGNUP_LINK);

                        $emailData = $this->myprepareEmailData($url, $template->body, $template->subject);

                        $this->mysendEmailVerification($emailData, $this->email, "HumanOp Sign Up Invite");

                        session()->flash('success', "{$this->email} invite link generated successfully.");

                    }else{

                        session()->flash('success', "{$this->email} Already Have Invite Link Please Create Account.");

                        return;
                    }
                }

            }


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

    public function deleteClientLink($id)
    {

        UserInvite::deleteInvite(null, $id);
    }

    public function copyClipboard($id)
    {

        UserInvite::sendInviteTime($id);
    }

    private function myprepareEmailData($url = null, $body = null, $subject = null)
    {

        return [
            '{$link}' => $url,
            '{$logo}' => URL::asset('assets/logos/HumanOp Logo.png'),
            '{$subject}' => $subject,
            '{$body}' => $body,
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
            "HumanOp Sign Up Invite Link"
        );
    }

    public function searchInvites()
    {

        $this->invites = UserInvite::getAllInviteLinks($this->perPage, $this->searched_email, $this->role);

        if ($this->invites instanceof \Illuminate\Pagination\AbstractPaginator) {

            $this->invites->withPath(url('/admin/client-invites'));

        }

    }

    public function render()
    {

        $this->searchInvites();

        return view('livewire.admin.client-invites.client-invite', ['invites' => $this->invites]);

    }

}
