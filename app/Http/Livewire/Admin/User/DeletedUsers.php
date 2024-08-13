<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;

class DeletedUsers extends Component
{

    protected $listeners = ['deleteUser' => 'deleteUserPermanently', 'restoreUser' => 'restoreUser'];

    public function render()
    {

        $users = User::deletedClients();

        return view('livewire.admin.user.deleted-users', ['users' => $users]);
    }

    public function restoreUser($id)
    {
        User::whereId($id)->restore();
    }

    public function deleteUserPermanently($id)
    {
        User::onlyTrashed()->whereId($id)->update(['is_permanently_deleted' => 1]);
    }
}
