<?php

namespace App\Http\Livewire\Admin\User;

use App\Helpers\Helpers;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\Client\Plan\Plan;
use App\Models\HAIChai\Chatbot;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\Admin\Admin;
use Carbon\Carbon;
use Termwind\Components\Dd;


class AllUser extends Component
{
    use WithPagination;

    public $name = '', $email = '', $age = '', $selectedItems = [], $is_chatBot_published;

    protected $users = [];
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['logInAdminAsUser', 'changeUserMemberShip', 'makePractitioner', 'updateHaiChatVisibility', 'deleteClientProfile', 'updateEmailVerified', 'bulkDelete', 'userPlanChange','updateEmail'];

    protected $updatesQueryString = [
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'age' => ['except' => ''],

    ];

    public function updated($field)
    {
        if (in_array($field, ['name', 'email', 'age'])) {
            $this->resetPage();
        }
    }


    public function logInAdminAsUser($id = null, $isClientOrPractitioner = null)
    {

        $user = User::whereId($id)->first();

        $admin_id = Helpers::getWebUser()->id;

        Auth::logout();

        Auth::login($user);

        Cache::put('admin_' . $user->id, ['is_admin' => true, 'admin_id' => $admin_id]);

        if ($isClientOrPractitioner == 2) {
            return redirect('client/dashboard');
        } else {
            return redirect('admin/dashboard');
        }

    }

    public function userPlanChange($id = null, $planName = null)
    {
        $user = User::getSingleUser($id);

        if ($user->plan_name === $planName) {

            session()->flash('error', "User already has this plan.");

        }

        if ($planName === "premium_lifetime") {

            $user->update([
                'is_lifetime' => 1,
                'has_bb_onetime' => 0,
                'plan' => 'premium_lifetime',
                'billing_context' =>'b2c',
                'premium_lifetime_welcome' => 1
            ]);

            session()->flash('success', "User downgraded to Premium Lifetime successfully.");

        }

        if ($planName === "bb_onetime") {

            $user->update([
                'is_lifetime' => 0,
                'has_bb_onetime' => 1,
                'plan' => 'bb_onetime',
                'billing_context' =>'b2c'
            ]);

            session()->flash('success', "User downgraded to Beta Breaker Lifetime successfully.");

        }

        return Helpers::validationResponse('Invalid plan name.');
    }

    public function changeUserMemberShip($memberShipValue, $user_id)
    {

        $plan = Plan::findPlanFromIntValue($memberShipValue);

        if ($plan) {

            Subscription::updateUserSubscriptionFromAdmin($plan->plan_id, $user_id);
        }

    }

    public function makePractitioner($id)
    {
        User::makeUserAsPractitioner($id);
    }

    public function updateHaiChatVisibility($id)
    {
        $user = User::find($id);
        if ($user) {
            if ($user->hai_chat == Admin::HAI_CHAT_SHOW) {
                User::updateUser(['hai_chat' => Admin::HAI_CHAT_HIDE], $id);
            } else {
                User::updateUser(['hai_chat' => Admin::HAI_CHAT_SHOW], $id);
            }
        }
    }

    public function updateEmailVerified($id)
    {
        $user = User::find($id);

        if ($user) {

            if (empty($user->email_verified_at)) {
                User::updateUser(['email_verified_at' => Carbon::now()->format('Y-m-d H:i:s')], $id);
            } else {
            }
        }
    }

    public function deleteClientProfile($id)
    {

        $checkAssociatedCompanies = B2BBusinessCandidates::where('candidate_id', $id)->where('future_consideration', Admin::NOT_IN_FUTURE)->get();

        if ($checkAssociatedCompanies) {

            foreach ($checkAssociatedCompanies as $associatedCompany) {

                B2BBusinessCandidates::futureConsiderationUser($associatedCompany);

            }
        }

        User::deleteClientProfile($id);

    }

    public function bulkDelete()
    {

        User::whereIn('id', $this->selectedItems)->delete();


        $this->selectedItems = [];
    }

    public function hideHaiChatFromAllClients()
    {

        $exists = User::where('hai_chat', Admin::HAI_CHAT_SHOW)->exists();

        if ($exists) {

            User::query()->update(['hai_chat' => Admin::HAI_CHAT_HIDE]);

        } else {

            User::query()->update(['hai_chat' => Admin::HAI_CHAT_SHOW]);
        }

    }

    public function searchFilter()
    {

        $this->users = User::adminClients($this->name, $this->email, $this->age, $this->perPage, [Admin::IS_CUSTOMER, Admin::IS_PRACTITIONER]);
    }

    public function updateEmail($id, $newEmail)
    {
        $user = User::find($id);
        if ($user) {
            $user->email = $newEmail;
            $user->save();

            session()->flash('success', "Email updated successfully!");

        }
    }

    public function render()
    {

        $this->searchFilter();

        $this->is_chatBot_published = Chatbot::where('is_connected', 1)->exists();

        return view('livewire.admin.user.all-user', ['users' => $this->users]);
    }

}
