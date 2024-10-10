<?php

namespace App\Http\Livewire\Admin\User;

use App\Helpers\Helpers;
use App\Models\Client\Plan\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\Admin\Admin;


class AllUser extends Component
{
    use WithPagination;

    public $name = '';
    public $email = '';
    public $age = '';

    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['logInAdminAsUser','changeUserMemberShip','makePractitioner'
        ,'updateHaiChatVisibility','deleteClientProfile'];

    public function logInAdminAsUser($id = null){

        $user = User::whereId($id)->first();

        $admin_id = Helpers::getWebUser()->id;

        Auth::logout();

        Auth::login($user);

        Cache::put('admin', ['is_admin' => true, 'admin_id' => $admin_id]);

//        Session::put('admin', ['is_admin' => true, 'admin_id' => $admin_id]);

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
            if($user->hai_chat == Admin::HAI_CHAT_SHOW){
                User::updateUser(['hai_chat' => Admin::HAI_CHAT_HIDE],$id);
            }else{
                User::updateUser(['hai_chat' => Admin::HAI_CHAT_SHOW],$id);
            }
        }
    }

    public function deleteClientProfile($id){

        User::deleteClientProfile($id);
    }



    public function render()
    {
        $users = User::adminClients($this->name, $this->email, $this->age, $this->perPage, [Admin::IS_CUSTOMER,Admin::IS_PRACTITIONER]);

        return view('livewire.admin.user.all-user', [

            'users' => $users
        ]);
    }

}
