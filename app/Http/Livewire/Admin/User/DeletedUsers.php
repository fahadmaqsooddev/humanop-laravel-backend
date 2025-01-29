<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DeletedUsers extends Component
{

    use WithPagination;

    protected $listeners = ['deleteUser' => 'deleteUserPermanently', 'restoreUser' => 'restoreUser','bulkDelete'];

    public $page = 1, $perPage = 10;
    public $selectedItems = [];

    public function render()
    {

        $users = User::deletedClients($this->page, $this->perPage);

        return view('livewire.admin.user.deleted-users', ['users' => $users]);
    }

    public function restoreUser($id)
    {
        User::whereId($id)->restore();
    }

    public function deleteUserPermanently($id)
    {
        User::onlyTrashed()->whereId($id)->forceDelete();
    }

    public function bulkDelete()
    {
        
        User::onlyTrashed()->whereIn('id', $this->selectedItems)->forceDelete();

        
        $this->selectedItems = [];
    }
}
