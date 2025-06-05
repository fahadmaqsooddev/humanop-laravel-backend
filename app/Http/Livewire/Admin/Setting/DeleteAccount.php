<?php

namespace App\Http\Livewire\Admin\Setting;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteAccount extends Component
{

    protected $listeners = ['deleteClientProfile'];

    public function deleteClientProfile($id)
    {

        User::deleteClientProfile($id);

        User::onlyTrashed()->whereId($id)->forceDelete();

        Auth::logout();

        return redirect('/')->with(['success' => 'Your account has been successfully deleted.']);

    }

    public function render()
    {
        return view('livewire.admin.setting.delete-account');
    }
}
