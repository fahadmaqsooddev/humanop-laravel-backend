<?php

namespace App\Http\Livewire\Admin\User;

use App\Helpers\Helpers;
use App\Models\Client\Plan\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
    protected $listeners = ['logInAdminAsUser','changeUserMemberShip','makePractitioner','updateHaiChatVisibility'];

    public function logInAdminAsUser($id = null){

        $user = User::whereId($id)->first();

        $admin_id = Helpers::getWebUser()->id;

        Auth::guard('web')->logout();

        Auth::guard('web')->login($user);

        Session::put('admin', ['is_admin' => true, 'admin_id' => $admin_id]);

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

    public function updateHaiChatVisibility($id)
    {
        
        $user = User::find($id);
        if ($user) {
            if($user->hai_chat == 1){
                User::updateUser(['hai_chat' => 2],$id);
            }else{
                User::updateUser(['hai_chat' => 1],$id);
            }
        }
    }



    public function render()
    {

        $users = User::adminClients($this->name, $this->email, $this->age, $this->perPage, \App\Enums\Admin\Admin::IS_CUSTOMER);

        return view('livewire.admin.user.all-user', [

            'users' => $users
        ]);
    }

}
