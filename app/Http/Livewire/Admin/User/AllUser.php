<?php

namespace App\Http\Livewire\Admin\User;

use App\Events\UserLogout;
use App\Helpers\Helpers;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\Client\Plan\Plan;
use App\Models\Client\Point\Point;
use App\Models\HAIChai\Chatbot;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\Admin\Admin;
use Carbon\Carbon;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class AllUser extends Component
{
    use WithPagination;

    public $name = '', $email = '', $age = '', $selectedItems = [], $is_chatBot_published, $is_beta_breaker_club;

    protected $users = [];
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['logInAdminAsUser', 'changeUserMemberShip', 'makePractitioner', 'updateHaiChatVisibility', 'deleteClientProfile', 'updateEmailVerified', 'bulkDelete', 'userPlanChange', 'updateEmail', 'updateUserBetaBreaker'];

    protected $updatesQueryString = [
        'name' => ['except' => ''],
        'email' => ['except' => ''],
        'age' => ['except' => ''],

    ];

    protected function HAiCreditsAdd($user = null)
    {

        if ($user->beta_breaker_club == Admin::BETA_BREAKER_CLUB) {

            $credits = Admin::PREMIUM_LIFETIME_CREDITS + Admin::BREAKER_CREDITS;

        } else {

            $credits = Admin::PREMIUM_LIFETIME_CREDITS;

        }

        return $credits;
    }

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

        $previousPlan = $user->plan == null ? 'freemium' : $user->plan;
        $newPlan = $planName;

        $planRank = [
            'freemium' => 1,
            'premium_monthly' => 2,
            'premium_yearly' => 3,
            'premium_lifetime' => 4,
        ];

        if ($previousPlan === $newPlan) {
            session()->flash('error', "User already has this plan.");
            return back();
        }

        $movement = ($planRank[$newPlan] > $planRank[$previousPlan]) ? 'upgraded' : 'downgraded';

        if ($newPlan === "premium_lifetime") {

            $user->update([
                'is_lifetime' => Admin::PREMIUM_LIFETIME,
                'plan' => 'premium_lifetime',
                'billing_context' => 'b2c',
                'premium_lifetime_welcome' => 1,
            ]);

        } elseif ($newPlan === "premium_monthly") {

            $user->update([
                'is_lifetime' => Admin::PREMIUM_LIFETIME_NOT,
                'plan' => 'premium_monthly',
                'billing_context' => 'b2c',
                'premium_lifetime_welcome' => 0,
            ]);

        } elseif ($newPlan === "premium_yearly") {

            $user->update([
                'is_lifetime' => Admin::PREMIUM_LIFETIME_NOT,
                'plan' => 'premium_yearly',
                'billing_context' => 'b2c',
            ]);

        } else {

            $user->update([
                'is_lifetime' => Admin::PREMIUM_LIFETIME_NOT,
                'plan' => 'freemium',
                'billing_context' => 'b2c',
                'premium_lifetime_welcome' => 0,
            ]);
        }

        if ($newPlan === "freemium") {

            $credits = $user->beta_breaker_club == Admin::BETA_BREAKER_CLUB ? Admin::BREAKER_CREDITS : Admin::FREEMIUM_CREDITS;

        } else {

            $credits = self::HAiCreditsAdd($user);

        }

        $this->HAiCreditsUpdated($credits, $user);

        session()->flash('success', "User {$movement} to " . ucfirst(str_replace('_', ' ', $newPlan)) . " successfully.");

        return back();
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

    public function updateUserBetaBreaker($id)
    {
        $user = User::find($id);

        if ($user) {

            if ($user->has_bb_onetime == Admin::BB_ONETIME || $user->beta_breaker_club == Admin::BETA_BREAKER_CLUB) {

                $user->has_bb_onetime = Admin::BB_ONETIME_NOT;
//                $user->plan = 'freemium';
                $user->beta_breaker_club = Admin::BETA_BREAKER_CLUB_NOT;

                $user->save();

                $credits = $user->plan_name == "Premium" ? Admin::PREMIUM_LIFETIME_CREDITS : Admin::FREEMIUM_CREDITS;

                $this->HAiCreditsUpdated($credits, $user);

                event(new UserLogout($user->id));

                session()->flash('success', "Congratulations, {$user->first_name} {$user->last_name}! You have been successfully removed from the Beta Breaker Club.");

            } else {

                $user->is_lifetime = Admin::PREMIUM_LIFETIME_NOT;
//                $user->has_bb_onetime = Admin::BB_ONETIME;
//                $user->plan = 'bb_onetime';
                $user->beta_breaker_club = Admin::BETA_BREAKER_CLUB;
                $user->premium_lifetime_welcome = Admin::PREMIUM_LIFETIME;
                $user->save();

                if ($user->plan_name == "Premium") {

                    $credits = Admin::PREMIUM_LIFETIME_CREDITS + Admin::BREAKER_CREDITS;

                } elseif ($user->plan_name == "Freemium") {

                    $credits = Admin::FREEMIUM_CREDITS + Admin::BREAKER_CREDITS;

                } else {
                    $credits = Admin::BREAKER_CREDITS;

                }

                $this->HAiCreditsUpdated($credits, $user);

                session()->flash('success', "Congratulations, {$user->first_name} {$user->last_name}! You have been successfully added to the Beta Breaker Club.");
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

        if (!$user) {
            session()->flash('error', "User not found!");
            return;
        }

        // Basic email format validation
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            session()->flash('error', "Please enter a valid email address!");
            return;
        }

        // Only allow letters, numbers, dots, hyphens, underscores, and @
        if (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $newEmail)) {
            session()->flash('error', "Email contains invalid characters!");
            return;
        }

        // Check if email already exists for another user
        $checkEmail = User::where('email', $newEmail)
            ->where('id', '!=', $id)
            ->first();

        if ($checkEmail) {
            session()->flash('error', "This email already exists!");
            return;
        }

        // Update the email
        $user->email = $newEmail;
        $user->save();

        session()->flash('success', "Email updated successfully!");
    }

    private function HAiCreditsUpdated($points, $user)
    {
        $existingPoints = Point::userExists($user->id);

        $existingPoints->point = $points;

        $existingPoints->save();

    }

    public function render()
    {

        $this->searchFilter();

        $this->is_chatBot_published = Chatbot::where('is_connected', 1)->exists();

        return view('livewire.admin.user.all-user', ['users' => $this->users]);
    }

}
