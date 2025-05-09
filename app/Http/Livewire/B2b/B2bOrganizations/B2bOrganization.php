<?php

namespace App\Http\Livewire\B2b\B2bOrganizations;

use App\Events\B2B\B2BResetPassword;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class B2bOrganization extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    // Search properties
    public $name = '';
    public $email = '';
    
    public $selectedItems = [];
    protected $listeners = ['resetPassword', 'deleteB2BAdminProfile', 'bulkDelete'];

    // This is the key method to fix your pagination issue
    public function updated($field)
    {
        if (in_array($field, ['name', 'email'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->reset(['name', 'email']);
        $this->resetPage();
    }

    public function resetPassword($id, $newPassword)
    {
        if (strlen($newPassword) < 8) {
            $this->dispatchBrowserEvent('swal:error', ['message' => 'Password must be at least 8 characters long.']);
            return;
        }

        $user = User::B2BResetPassword($id, $newPassword);

        if ($user) {
            $this->dispatchBrowserEvent('swal:success', ['message' => 'Password has been reset successfully.']);

            event(new B2BResetPassword($user['id'], "Password has been reset successfully."));

        } else {
            $this->dispatchBrowserEvent('swal:error', ['message' => 'Password Not Reset.']);
            return;
        }
    }

    public function deleteB2BAdminProfile($businessId)
    {
        B2BBusinessCandidates::deleteB2BAdmin($businessId);
    }

    public function bulkDelete()
    {
        User::whereIn('id', $this->selectedItems)->delete();
        $this->selectedItems = [];
    }

    public function render()
    {
        // Get B2B admins with search filters
        $users = User::getB2BAdmin($this->name, $this->email);
        $users->withPath(url('admin/b2b-organizations'));

        return view('livewire.b2b.b2b-organizations.b2b-organization', [
            'users' => $users
        ]);
    }
}