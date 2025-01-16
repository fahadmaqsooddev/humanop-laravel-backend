<?php

namespace App\Http\Livewire\Admin\User;

use App\Helpers\Helpers;
use App\Models\Client\Plan\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
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


        public function updated($field)
        {
            if (in_array($field, ['name', 'email', 'age'])) {
                $this->resetPage(); 
            }
        }
    

    public function logInAdminAsUser($id = null, $isClientOrPractitioner = null){

        $user = User::whereId($id)->first();

        $admin_id = Helpers::getWebUser()->id;

        Auth::logout();

        Auth::login($user);

        Cache::put('admin_' . $user->id, ['is_admin' => true, 'admin_id' => $admin_id]);

        if ($isClientOrPractitioner == 2)
        {
            return redirect('client/dashboard');
        }
        else
        {
            return redirect('admin/dashboard');
        }

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

    public function hideHaiChatFromAllClients(){

        $exists = User::where('hai_chat', Admin::HAI_CHAT_SHOW)->exists();

        if ($exists){

            User::query()->update(['hai_chat' => Admin::HAI_CHAT_HIDE]);

        }else{

            User::query()->update(['hai_chat' => Admin::HAI_CHAT_SHOW]);
        }

    }



    public function render()
    {
        $users = User::adminClients($this->name, $this->email, $this->age, $this->perPage, [Admin::IS_CUSTOMER,Admin::IS_PRACTITIONER]);
        return view('livewire.admin.user.all-user', [

            'users' => $users
        ]);
    }

}
