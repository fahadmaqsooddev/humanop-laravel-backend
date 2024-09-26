<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Client\Plan\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AllUser extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $age = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['logInAdminAsUser','changeUserMemberShip','makePractitioner'];

    public function logInAdminAsUser($id = null){

        $user = User::whereId($id)->first();

        Auth::guard('web')->logout();

        Auth::guard('web')->login($user);

        return redirect('client/dashboard');

    }

    public function changeUserMemberShip($memberShipValue, $user_id){

        $plan = Plan::findPlanFromIntValue($memberShipValue);

        if ($plan){

            Subscription::updateUserSubscriptionFromAdmin($plan->plan_id, $user_id);
        }

    }

    public function makePractitioner($id){

        User::makeUserAsPractitioner($id);
    }

    public function render()
    {

        $users = User::adminClients($this->name, $this->email, $this->age, $this->perPage);

        return view('livewire.admin.user.all-user', [

            'users' => $users
        ]);
    }

}
