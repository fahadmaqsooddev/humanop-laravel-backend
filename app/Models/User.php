<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Admin\Alchemy\AlchemyCode;
use App\Models\Admin\Code\CodeDetail;
use App\Models\B2B\B2BBusinessCandidates;
use App\Models\B2B\B2BIntentionOption;
use App\Models\B2B\SelectIntentionOption;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Follow\Follow;
use App\Models\Client\Story\Story;
use App\Models\Client\StoryView\StoryView;
use App\Models\IntentionPlan\IntentionOption;
use App\Models\IntentionPlan\IntentionPlan;
use App\Models\UserInvite\UserInvite;
use Aws\ElasticsearchService\Exception\ElasticsearchServiceException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Client\Point\Point;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles, SoftDeletes;

    protected $appends = [
        'point',
        'photo_url',
        'user_picture_url',
        'is_follow',
        'connection_status',
        'feedback_submitted',
        'age_group',
        'plan_name',
        'optional_trait'
    ];

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->referral_code = Str::random(5) . $user->id . Str::random(5);
            $user->save();
        });
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // mutator
    public function setPasswordAttribute($value)
    {
        Session::put('user_password', $value);

        $this->attributes['password'] = Hash::make($value);
    }

    // scope

    public function scopeSelection($query)
    {
        return $query->select(['id', 'first_name', 'last_name', 'gender', 'email', 'phone', 'is_admin', 'is_feedback', 'image_id', 'date_of_birth', 'hai_chat', 'referral_code', 'timezone', 'two_way_auth', 'intro_check', 'app_intro_check', 'step', 'register_from_app', 'email_verified_at', 'company_name', 'apple_id', 'google_id', 'b2b_step', 'prompt_notification', 'version_update', 'complete_assessment_walkthrough', 'complete_tutorial', 'profile_status', 'hai_status']);
    }

    // appends

    public function getUserPictureUrlAttribute()
    {
        return (request()->getSchemeAndHttpHost() . "/assets/img/bruce-mars.jpg");
    }

    public function getOptionalTraitAttribute()
    {
        $timezone = $this->timezone;

        $assessment = Assessment::getLatestAssessment($this->id);

        if (!empty($assessment)) {

            $topThreeStyles = Assessment::getAllStyles($assessment);

            $topFeatures = Assessment::getFeatures($assessment);

            $topTwoFeatures = Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment);

            $optionalTrait = Helpers::getOptionalTrait($timezone, $topThreeStyles, $topTwoFeatures);

            $optionalTraitDetail = CodeDetail::getOptionalTraitDetail($optionalTrait);

            return $optionalTraitDetail;
        }

        return '';
    }

    public function getPointAttribute()
    {
        $user = Helpers::getWebUser() ?? Helpers::getUser();

        $userId = $user ? $user['id'] : null;

        if ($userId !== null) {
            $point = Point::where('user_id', $userId)->select('point')->first();

            if ($point) {
                return $point->point;
            }
        }

        return 0;
    }


    public function getPhotoUrlAttribute()
    {

        if ($this->gender == '1') {
            $profilePic = 'female_profile_pic.png';
        } else {
            $profilePic = 'profile_pic.png';
        }

        return Helpers::getImage($this->image_id, $profilePic);
    }

    public function getIsFollowAttribute()
    {
        $user = Helpers::getWebUser() ?? Helpers::getUser();

        $userId = $user ? $user['id'] : null;

        if ($userId !== null) {

            return $this->followed()->where('user_id', $userId)->exists();

        }
        else
        {
            return false;

        }
    }

    public function getConnectionStatusAttribute()
    {

//        if ($this->sentConnectionRequest()->exists() != null)
//        {
//            if ($this->sentConnectionRequest()->exists()) {
//
//                return 2; // sent connection request
//
//            }
//            elseif ($this->recevivedConnectionRequest()->exists()) {
//
//                return 3; // received connection request
//
//            }
//            elseif ($this->confirmedConnectionRequest()->exists()) {
//
//                return 1; // confirm connection request
//
//            }
//            else {
//
//                return 0;
//            }
//        }
//        else
//        {
            return 0;

//        }

    }

    public function getFeedbackSubmittedAttribute()
    {
        return $this->feedback()->exists();
    }

    public function getAgeGroupAttribute()
    {
        return 0; //($this->age_min . '-' . $this->age_max);
    }

    public function getPlanNameAttribute()
    {

        return $this->userSubscription->plan->name ?? "Freemium";
    }

    public function getIsViewedStoriesAttribute()
    {

        return ($this->storyViews()->count() === $this->userStory()->count() ? 1 : 0);
    }

    // relations

    public function invites()
    {
        return $this->hasOne(UserInvite::class, 'email', 'email');
    }

    public function getSubscription()
    {

        return $this->hasOne(Subscription::class, 'user_id', 'id');
    }

    public function stories()
    {

        return $this->hasMany(Story::class, 'user_id', 'id');
    }

    public function followed()
    {

        return $this->HasMany(Follow::class, 'follow_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'user_id', 'id');
    }

    public function haiAssessments()
    {
        return $this->hasOne(Assessment::class, 'user_id', 'id')->latest();
    }

    public function hasRoles()
    {
        return $this->hasOne(ModelHasRole::class, 'model_id', 'id');
    }

    public function feedback()
    {

        return $this->hasOne(Client\Feedback\Feedback::class, 'user_id', 'id');
    }

    public function sentConnectionRequest()
    {
        $user = Helpers::getWebUser() ?? Helpers::getUser();

        $userId = $user ? $user['id'] : null;

        if ($userId !== null) {

            return $this->hasOne(Connection::class, 'friend_id', 'id')->where('user_id', $userId)->where('status', 0);
        }
        else
        {
            return null;
        }

    }

    public function recevivedConnectionRequest()
    {

        return $this->hasOne(Connection::class, 'user_id', 'id')
            ->where('friend_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('status', 0);
    }

    public function confirmedConnectionRequest()
    {

        return $this->hasOne(Connection::class, 'friend_id', 'id')
            ->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('status', 1);
    }

    public function colorCodes()
    {

        return $this->hasManyThrough(AssessmentColorCode::class, Assessment::class, 'user_id', 'assessment_id', 'id', 'id');
    }

    public function payments()
    {

        return $this->hasMany(Payment::class, 'user_id', 'id');
    }

    public function userSubscription()
    {

        return $this->hasOne(Subscription::class, 'user_id', 'id')->latest();
    }

    public function storyViews()
    {

        return $this->hasManyThrough(StoryView::class, Story::class, 'user_id', 'story_id', 'id', 'id')->where('stories.created_at', ">", Carbon::now()->subDay())->where('story_views.user_id', (Helpers::getUser()->id ?? Helpers::getWebUser()->id));
    }

    public function userStory()
    {

        return $this->hasMany(Story::class, 'user_id', 'id')->where('created_at', ">", Carbon::now()->subDay());
    }

    public function userIntensionPlan()
    {

        return $this->hasMany(IntentionPlan::class, 'user_id', 'id');
    }

    public function businessCandidate()
    {
        return $this->hasOne(B2BBusinessCandidates::class, 'business_id', 'id');
    }

    public function candidate()
    {

        return $this->hasOne(B2BBusinessCandidates::class, 'candidate_id', 'id');
    }

    public function userIntentions()
    {

        return $this->hasManyThrough(IntentionOption::class, IntentionPlan::class, 'user_id', 'id', 'id', 'intention_option_id');
    }

    public function businessIntentions()
    {

        return $this->hasManyThrough(B2BIntentionOption::class, SelectIntentionOption::class, 'business_id', 'id', 'id', 'intention_option_id');
    }

    // query
    public function isAdmin()
    {
        return $this->role_id == 1;
    }

    public function isCreator()
    {
        return $this->role_id == 2;
    }

    public function isMember()
    {
        return $this->role_id == 3;
    }

    public static function allUser()
    {
        return self::where('is_admin', \App\Enums\Admin\Admin::IS_CUSTOMER)->get();
    }

    public static function allPractitioner()
    {
        return self::where('is_admin', \App\Enums\Admin\Admin::IS_PRACTITIONER)->get();
    }

    public static function allSubAdmin()
    {
        $subAdmins = self::where('is_admin', 3)->orderBy('id', 'desc')->get();
        return $subAdmins;
    }

    public static function deleteSubAdmin($id = null)
    {
        return self::where('id', $id)->forceDelete();
    }

    public static function getSingleUser($id = null)
    {
        $user = self::find($id);
        return $user;
    }

    public static function getSingleUserFromCompanyName($companyName = null)
    {
        return self::where('company_name', $companyName)->first();
    }

    public static function getReferralByUser($referralCode = null)
    {
        return self::where('referral_code', $referralCode)->first();
    }

    public static function allReferralUsers($userId = null)
    {
        $users = self::where('referred_by', $userId)->where('step', 3)->select(['id', 'first_name', 'last_name', 'email', 'gender', 'last_login', 'date_of_birth'])->get();

        foreach ($users as $user) {
            $user['gender'] = $user->gender == Admin::IS_MALE ? 'Male' : 'Female';
            $user['last_login'] = Carbon::parse($user['last_login'])->format('m/d/Y h:i A');
            $user->setAppends([]);
        }

        return $users;
    }

    public static function updateWorkEmail($id = null, $email = null)
    {
        return self::where('id', $id)->update([
            'work_email' => $email,
            'b2b_step' => 1,
        ]);
    }

    public static function updateCompany($id = null, $company = null, $businesStrategyid = null)
    {
        return self::where('id', $id)->update([
            'company_name' => $company,
            'business_sub_stratergy_id' => $businesStrategyid,
            'b2b_step' => 2,
        ]);
    }

    public static function updateTeam($id = null, $team = null)
    {
        return self::where('id', $id)->update([
            'team_department' => $team,
            'b2b_step' => 3,
        ]);
    }

    public static function getUserAge($date_of_birth = null)
    {

        $age = Carbon::parse($date_of_birth)->age;

        switch ($age) {
            case (7 <= $age && $age <= 11):
                $interval = [
                    'interval' => 'Connecting & Communicating',
                    'public_name' => 'Cycle of Life - Connecting & Communicating (7-11)',
                    'video' => asset('assets/video/Cycle of Life - Motivation 16-20.mp4'),
                    'description' => config('intervalLifeCycle.connecting_Communicating_(7-11)')
                ];
                break;
            case (12 <= $age && $age <= 15):
                $interval = [
                    'interval' => 'Alchemical Revelation',
                    'public_name' => 'Cycle of Life - Alchemical Revelation (12-15)',
                    'video' => asset('assets/video/Cycle of Life - Motivation 16-20.mp4'),
                    'description' => config('intervalLifeCycle.alchemical_revelation_(12-15)')
                ];
                break;
            case (16 <= $age && $age <= 20):
                $interval = [
                    'interval' => 'Motivation',
                    'public_name' => 'Cycle of Life - Motivation (16-20)',
                    'video' => asset('assets/video/Cycle of Life - Motivation 16-20.mp4'),
                    'description' => config('intervalLifeCycle.motivation_(16-20)')

                ];
                break;
            case (21 <= $age && $age <= 29):
                $interval = [
                    'interval' => 'Roadworthy ',
                    'public_name' => 'Cycle of Life - Roadworthy (21-29)',
                    'video' => asset('assets/video/Cycle of Life - Roadworthy 21-29.mp4'),
                    'description' => config('intervalLifeCycle.roadworthy_(21-29)')

                ];
                break;
            case (30 <= $age && $age <= 33):
                $interval = [
                    'interval' => 'Power',
                    'public_name' => 'Cycle of Life - The Power Interval (30-33)',
                    'video' => asset('assets/video/The Cycle of Life - Power Interval 30-33.mp4'),
                    'description' => config('intervalLifeCycle.the_power_interval_(30-33)')

                ];
                break;
            case (34 <= $age && $age <= 42):
                $interval = [
                    'interval' => 'MidLife Transformation',
                    'public_name' => 'Cycle of Life - Mid-Life Transformation (34-42)',
                    'video' => asset('assets/video/The Cycle of Life - Mid-Life Transformation 34-43.mp4'),
                    'description' => config('intervalLifeCycle.mid_life_transformation_(34-42)')

                ];
                break;
            case (43 <= $age && $age <= 51):
                $interval = [
                    'interval' => 'Awareness',
                    'public_name' => 'Cycle of Life - Awareness (43-51)',
                    'video' => asset('assets/video/Cycle of Life - Awareness Interval 43-52.mp4'),
                    'description' => config('intervalLifeCycle.awareness_(43-51)')

                ];
                break;
            case (52 <= $age && $age <= 65):
                $interval = [
                    'interval' => 'Payit Forward',
                    'public_name' => 'Cycle of Life - Pay It Forward (52-65)',
                    'video' => asset('assets/video/Cycle of Life - Pay It Forward 52-66.mp4'),
                    'description' => config('intervalLifeCycle.pay_it_forward_(52-65)')

                ];
                break;
            case (66 <= $age && $age <= 69):
                $interval = [
                    'interval' => 'Liberated',
                    'public_name' => 'Cycle of Life - Liberated (66-69)',
                    'video' => asset('assets/video/Cycle of Life - Liberated 66-70.mp4'),
                    'description' => config('intervalLifeCycle.liberated_(66-69)')

                ];
                break;
            case (70 <= $age && $age <= 74):
                $interval = [
                    'interval' => 'Being',
                    'public_name' => 'Cycle of Life - Being (70-74)',
                    'video' => asset('assets/video/The Cycle of Life - Being 70-75.mp4'),
                    'description' => config('intervalLifeCycle.being_(70-74)')

                ];
                break;
            case (75 <= $age && $age <= 83):
                $interval = [
                    'interval' => 'Life Review',
                    'public_name' => 'Cycle of Life - Life Review (75-83)',
                    'video' => asset('assets/video/The Cycle of Life - Being 70-75.mp4'),
                    'description' => config('intervalLifeCycle.life_review_(75-83)')

                ];
                break;
            default:
                $interval = [
                    'interval' => 'Surrender',
                    'public_name' => 'Cycle of Life - Surrender (84+)',
                    'video' => asset('assets/video/The Cycle of Life - Life Review Interval Ages 75-84.mp4'),
                    'description' => config('intervalLifeCycle.surrender_(84+)')

                ];
                break;
        }

        return $interval;
    }

    public static function updateUser($data = null, $id = null)
    {

        return self::find($id)->update($data);
    }

    public static function createUser($data = null)
    {

        $data['is_admin'] = Admin::IS_CUSTOMER;
        $data['status'] = 1;
        $data['hai_chat'] = 2;
        $data['email_verify_token'] = Str::random(16);

        $user = self::create($data);

        return $user;
    }

    public static function createPractitionerUser($data = null, $practitionerId = null)
    {

        $data['is_admin'] = Admin::IS_CUSTOMER;
        $data['status'] = 1;
        $data['practitioner_id'] = $practitionerId;

        $user = self::create($data);

        return $user;
    }

    public static function createSubAdmin($data = null)
    {
        $data['is_admin'] = 3;
        $data['status'] = 1;
        $data['email_verified_at'] = Carbon::now();

        $user = self::create($data);

        return $user;
    }

    public static function createCustomerOnStripe($user = null, $stripe_keys = null)
    {

        $stripe = new \Stripe\StripeClient($stripe_keys['api_key']);

        $stripe_customer = $stripe->customers->create([
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
        ]);

        $user->stripe_id = $stripe_customer->id;

        $user->save();
    }

    public static function checkPassword($password, $id)
    {
        $user = self::find($id);
        if ($user && Hash::check($password, $user->password)) {
            return true;
        } else {
            return false;
        }
    }

    public static function user($id = null)
    {
        $user = self::whereId($id)->with('userIntensionPlan')->selection()->first();
        $user['gender'] = ($user['gender'] === 0 || $user['gender'] === '0' ? "male" : "female");
        $user['hai_chat'] = ($user['hai_chat'] === Admin::HAI_CHAT_SHOW ? true : false);
        $user['is_feedback'] = $user['is_feedback'];
        //        $user['is_feedback'] = ($user['is_feedback'] === Admin::Is_Feed_Back_Show ? true : false);
        $user['two_way_auth'] = ($user['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);
        $user['intro_check'] = ($user['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);
        return $user;
    }


    public static function checkHaiStatus($id = null)
    {
        $user = self::whereId($id)->select(['hai_chat'])->first();
        return ($user['hai_chat'] === Admin::HAI_CHAT_SHOW ? true : false);
    }

    public static function createClient($data = null)
    {

        $data['gender'] = $data['gender'] === 'male' ? 0 : 1;

        $data['is_admin'] = Admin::IS_CUSTOMER; // 2 for client

        $data['status'] = 1;

        $data['hai_chat'] = 2;

        $data['email_verify_token'] = Str::random(16);

        return self::create($data);
    }

    public static function createFirstStep($data = null, $googleId = null, $appleId = null, $is_admin = null, $referralCode = null)
    {

        $data['step'] = 1;

        $data['is_admin'] = !empty($is_admin) ? Admin::IS_B2B : Admin::IS_CUSTOMER;

        $data['company_name'] = null;

        $data['status'] = 1;

        $data['hai_chat'] = 2;

        $data['email_verify_token'] = Str::random(16);

        if (!empty($referralCode)) {

            $referralBy = self::getReferralByUser($referralCode);

            $data['referred_by'] = $referralBy['id'];
        }

        $user = self::create($data);

        if (!empty($googleId) || !empty($appleId)) {

            User::emailVerified($user['id']);
        }

        return $user;
    }

    public static function updateUserProfile($request = null)
    {

        $user_id = Helpers::getUser()->id;


        $request['gender'] = $request['gender'] === 'male' ? 0 : 1;

        if (isset($request['password']) && !empty($request['password'])) {
            $request['password'] = Hash::make($request['password']);
        } else {
            $request['password'] = Helpers::getUser()->password;
        }

        self::whereId($user_id)->update($request);


        return self::user($user_id);
    }

    public static function updateUserPassword($password = null)
    {

        $user = self::whereId(Helpers::getUser()->id)->first();

        $user->update(['password' => $password]);
    }

    public static function updateUserPaymentMethod($paymentMethod = null)
    {
        $user_id = Auth::user()['id'];

        $user = self::whereId($user_id)->first();

        return $user->update([
            'payment_method' => $paymentMethod['id'],
            'pm_type' => $paymentMethod['card']['brand'],
            'pm_last_four' => $paymentMethod['card']['last4'],
            'pm_exp_month' => '0' . $paymentMethod['card']['exp_month'],
            'pm_exp_year' => $paymentMethod['card']['exp_year'],
        ]);
    }

    public static function storyUsers()
    {

        $users = self::whereHas('stories', function ($q) {

            return $q->where('created_at', ">", Carbon::now()->subDay());
        })
            ->select(['id', 'first_name', 'last_name', 'image_id'])
            ->get();

        foreach ($users as $user) {

            $user->setAppends([
                'point',
                'photo_url',
                'user_picture_url',
                'is_follow',
                'connection_status',
                'feedback_submitted',
                'age_group',
                'plan_name',
                'is_viewed_stories'
            ]);
        }

        return $users;
    }

    public static function updateUserPaymentMethodFromApi($paymentMethod = null, $request = null)
    {

        self::whereId(Helpers::getUser()->id)->update([

            'payment_method' => $paymentMethod['id'],
            'pm_type' => $paymentMethod['card']['brand'],
            'pm_last_four' => $paymentMethod['card']['last4'],
            'pm_exp_month' => '0' . $paymentMethod['card']['exp_month'],
            'pm_exp_year' => $paymentMethod['card']['exp_year'],
            'card_name' => $request->input('card_name')

        ]);
    }

    public static function userStories($id = null)
    {

        return self::whereId($id)
            ->with(['stories' => function ($q) {

                $q->where('created_at', ">", Carbon::now()->subDay());
            }])
            ->select(['id', 'first_name', 'last_name', 'image_id'])
            ->first();
    }

    public static function updateUserIsFeedback()
    {

        $user = self::whereId(Helpers::getWebUser()->id ?? Helpers::getUser()->id)->first();

        if (!$user->feedback && $user->is_admin === 2) {

            if ($user->is_feedback === 3 || $user->is_feedback === 2) {

                tap($user->decrement('is_feedback', 1));
            } else if ($user->is_feedback === 1) {

                tap($user->update(['is_feedback' => 3]));
            }
        }

        return $user;
    }

    public static function allClients($search_name = null, $per_page = 12, $style_feature_code = null, $alchemy_codes_array = [])
    {

        $users = self::query();

        if (!empty($search_name)) {

            $users = $users->where(function ($q) use ($search_name) {

                $q->where('first_name', 'LIKE', "%$search_name%")
                    ->orWhere('last_name', 'LIKE', "%$search_name%")
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");
            });
        }

        if (!empty($style_feature_code)) {

            $users = $users->whereHas('colorCodes', function ($q) use ($style_feature_code) {

                $q->where('code', $style_feature_code)->where('code_color', 'green');
            });
        }

        if (!empty($alchemy_codes_array)) {

            $sqlArray = '(' . join(',', $alchemy_codes_array) . ')';

            $users = $users->whereHas('assessments', function ($q) use ($sqlArray) {

                $q->whereRaw("concat(g, '', s, '', c) IN $sqlArray");
            });
        }

        $users = $users->where('is_admin', \App\Enums\Admin\Admin::IS_CUSTOMER)
            ->paginate($per_page);

        return $users;
    }

    // public static function getB2BAdmin($search_name = null, $search_email = null, $per_page = 10)
    // {
    //     $organizations = self::query();

    //     if (!empty($search_name)) {

    //         $organizations->where(function ($q) use ($search_name) {

    //             $q->where('first_name', 'LIKE', "%{$search_name}%")
    //                 ->orWhere('last_name', 'LIKE', "%{$search_name}%")
    //                 ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search_name}%"]);

    //         });

    //     }

    //     if (!empty($search_email)) {

    //         $organizations->where(function ($q) use ($search_email) {

    //             $q->where('email', 'LIKE', "%{$search_email}%");

    //         });


    //     }

    //     $organizations = $organizations->whereNotNull('company_name')
    //         ->orderBy('created_at', 'desc')
    //         ->paginate($per_page);
    //         // dd($organizations);

    //     foreach ($organizations as $organization) {

    //         $organization->member_count = B2BBusinessCandidates::getMembersCount($organization['id']);

    //         $organization->candidate_count = B2BBusinessCandidates::getCandidatesCount($organization['id']);
    //     }

    //     // $data= $organizations->paginate($per_page);
    //     return $organizations;
    // }

    public static function getB2BAdmin($search_name = null, $search_email = null, $per_page = 10)
    {
        $query = self::query();

        if (!empty($search_name)) {
            $query->where(function ($q) use ($search_name) {
                $q->where('first_name', 'LIKE', "%{$search_name}%")
                    ->orWhere('last_name', 'LIKE', "%{$search_name}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search_name}%"]);
            });
        }

        if (!empty($search_email)) {
            $query->where(function ($q) use ($search_email) {
                $q->where('email', 'LIKE', "%{$search_email}%");
            });
        }

        // Apply main filters but don't paginate yet
        $query = $query->whereNotNull('company_name')
            ->orderBy('created_at', 'desc');

        // Get the paginated results
        $organizations = $query->paginate($per_page);

        // Now add the additional counts
        foreach ($organizations as $organization) {
            $organization->member_count = B2BBusinessCandidates::getMembersCount($organization['id']);
            $organization->candidate_count = B2BBusinessCandidates::getCandidatesCount($organization['id']);
        }

        return $organizations;
    }

    public static function allPaginatedClients($request = null)
    {

        $users = self::query();

        $users = $users->when($request->input('name'), function ($q, $search_name) {

            $q->where(function ($q) use ($search_name) {

                $q->where('first_name', 'LIKE', "%$search_name%")
                    ->orWhere('last_name', 'LIKE', "%$search_name%")
//                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search_name}%"]);

            });
        });

        $users = $users->when($request->input('style_feature_code'), function ($q, $style_feature_code) {

            $q->whereHas('colorCodes', function ($q) use ($style_feature_code) {

                $q->where('code', $style_feature_code)->where('code_color', 'green');
            });
        });

        $users = $users->when($request->input('alchemy_code'), function ($q, $alchemy_code) {

            $alchemy_codes_array = AlchemyCode::getNumbersFromCode($alchemy_code);

            if (isset($alchemy_codes_array[0])) {

                $sqlArray = '(' . join(',', $alchemy_codes_array) . ')';

                $q->whereHas('assessments', function ($q) use ($sqlArray) {

                    $q->whereRaw("concat(g, '', s, '', c) IN $sqlArray");
                });
            }
        });

        $users = $users->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B]);

        return Helpers::pagination($users, $request->input('pagination'), $request->input('per_page'));
    }

    // public static function deletedClients($page, $per_page)
    // {

    //     return self::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_PRACTITIONER])
    //         ->where('is_permanently_deleted', 0)
    //         ->onlyTrashed()
    //         ->orderBy('deleted_at','desc')
    //         ->paginate($per_page)->setPath(route('deleted_clients'));

    // }


    public static function deletedClients($page, $per_page, $search_name = null, $email = null, $age = null)
    {
        $userId = Helpers::getWebUser()['id'];

        $isAdminLevel = Helpers::getWebUser()['is_admin'];

        $users = ($isAdminLevel == 4) ? self::where('practitioner_id', $userId)->orderBy('created_at', 'desc') : self::query()->orderBy('created_at', 'desc');

        if (!empty($search_name)) {
            $users->where(function ($query) use ($search_name) {
                $query->where('first_name', 'LIKE', "%$search_name%")
                    ->orWhere('last_name', 'LIKE', "%$search_name%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search_name%"]);
            });
        }

        // Filter by email
        if (!empty($email)) {
            //    $users->where('email', $email);
            $users->where('email', 'LIKE', "%$email%");
        }

        if (!empty($age)) {
            $data['age_range'] = $age;
            $ageData = Helpers::explodeAgeRangeIntoAge($data);

            $min_date = Carbon::now()->subYears((int)($ageData['age_max'] ?? 0))->toDateString();
            $max_date = Carbon::now()->subYears((int)($ageData['age_min'] ?? 0))->toDateString();

            $users->whereBetween('date_of_birth', [$min_date, $max_date]);
        }

        $users = $users->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_PRACTITIONER])
            ->where('is_permanently_deleted', 0)
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate($per_page)->setPath(route('deleted_clients'));

        return $users;
    }

    public static function adminClients($search_name = null, $email = null, $age = null, $per_page = 10, $isAdmin)
    {

        $userId = Helpers::getWebUser()['id'];

        $isAdminLevel = Helpers::getWebUser()['is_admin'];

        $users = ($isAdminLevel == 4) ? self::where('practitioner_id', $userId)->orderBy('created_at', 'desc') : self::query()->orderBy('created_at', 'desc');

        // Search by name
        if (!empty($search_name)) {
            $users->where(function ($query) use ($search_name) {
                $query->where('first_name', 'LIKE', "%$search_name%")
                    ->orWhere('last_name', 'LIKE', "%$search_name%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$search_name%"]);
            });
        }

        // Filter by email
        if (!empty($email)) {

            //    $users->where('email', $email);
            $users->where('email', 'LIKE', "%$email%");
        }

        // Filter by age
        if (!empty($age)) {
            $data['age_range'] = $age;
            $ageData = Helpers::explodeAgeRangeIntoAge($data);

            $min_date = Carbon::now()->subYears((int)($ageData['age_max'] ?? 0))->toDateString();
            $max_date = Carbon::now()->subYears((int)($ageData['age_min'] ?? 0))->toDateString();

            $users->whereBetween('date_of_birth', [$min_date, $max_date]);
        }

        // Filter by admin status and paginate
        $users = $users->whereIn('is_admin', $isAdmin)
            // ->whereNotNull('email_verified_at')
            ->paginate($per_page)
            ->setPath(route('admin_all_users', [
                'name' => $search_name,
                'email' => $email,
                'age' => $age
            ]));


        return $users;
    }

    public static function getSuperAdmins()
    {
        return self::where('is_admin', Admin::IS_ADMIN)->get();
    }

    public static function makeUserAsPractitioner($user_id = null)
    {
        $user = self::find($user_id);
        if ($user) {
            if ($user->is_admin == Admin::IS_PRACTITIONER) {
                User::whereId($user_id)->update(['is_admin' => Admin::IS_CUSTOMER]);

                DB::table('model_has_roles')->where('model_id', $user['id'])->delete();

                DB::table('model_has_permissions')->where('model_id', $user['id'])->delete();
            } else {
                User::whereId($user_id)->update(['is_admin' => Admin::IS_PRACTITIONER]);

                DB::table('model_has_roles')->insert([
                    ['role_id' => Admin::PRACTITIONER_ROLE ?? null, 'model_type' => 'App\Models\User', 'model_id' => $user['id']],
                ]);

                $permissions = Permission::all();

                foreach ($permissions as $permission) {

                    if ($user->hasRoles->role_id === Admin::PRACTITIONER_ROLE) {

                        if ($permission->name == 'users') {

                            DB::table('model_has_permissions')->insert([
                                ['permission_id' => $permission->id ?? null, 'model_type' => 'App\Models\User', 'model_id' => $user['id']],
                            ]);
                        } elseif ($permission->name == 'abandonedAssessment') {
                            DB::table('model_has_permissions')->insert([
                                ['permission_id' => $permission->id ?? null, 'model_type' => 'App\Models\User', 'model_id' => $user['id']],
                            ]);
                        }
                    }
                }
            }
        }
    }

    public static function userLoggedInData()
    {

        $user = Helpers::getUser() ?? Helpers::getWebUser();

        return self::whereId($user->id)->with('userIntensionPlan')->first();
    }

    public static function checkUserFromEmailOrSocialId($request)
    {

        $user = self::where('email', $request->input('email'))->first();

        if ($user) {

            if ($request->has('apple_id') && !empty($request->input('apple_id')) && empty($user['apple_id'])) {

                $user->update(['apple_id' => $request->input('apple_id')]);
            }

            if ($request->has('google_id') && !empty($request->input('google_id')) && empty($user['google_id'])) {

                $user->update(['google_id' => $request->input('google_id')]);
            }

            $user = $user->where('email', $request->input('email'))
                ->with('userIntensionPlan')->selection()->first();

            $user ? $user['gender'] = ($user['gender'] === 0 || $user['gender'] === '0' ? "male" : "female") : "";

            if ($user) {
                $user['intro_check'] = ($user['app_intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);
                $user['is_feedback'] = ($user['is_feedback'] === Admin::Is_Feed_Back_Show ? true : false);
            }

            return $user;
        }

        return false;
    }

    public static function verifyUserExistsWithPractitionerSlugs($email, $slug1, $slug2)
    {

        $practitioner = self::where('first_name', $slug1)->where('last_name', $slug2)->first();

        User::where('practitioner_id', $practitioner->id)->where('email', $email)->exists();
    }

    public static function deleteClientProfile($id)
    {

        self::whereId($id)->first();

        self::whereId($id)->delete();
    }

    public static function emailVerified($userId = null)
    {
        return self::whereId($userId)->update(['email_verified_at' => Carbon::now(), 'step' => 2]);
    }

    public static function checkEmailVerified($userEmail = null)
    {
        return self::where('email', $userEmail)->whereNotNull('email_verified_at')->first();
    }

    public static function checkLastStep($userEmail = null)
    {
        return self::where('email', $userEmail)->first();
    }


    public static function checkEmail($userEmail = null)
    {
        return self::where('email', $userEmail)->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B, Admin::IS_B2U])->first();
    }

    public static function checkB2BEmail($userEmail = null)
    {
        return self::where('email', $userEmail)->whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B])->first();
    }

    public static function checkDeleteEmail($userEmail = null)
    {
        return self::where('email', $userEmail)->onlyTrashed()->first();
    }

    public static function updateUserTimezone($timezone = null)
    {
        $userId = Helpers::getWebUser()['id'];

        return self::whereId($userId)->update(['timezone' => $timezone]);
    }

    public static function resetPassword($userId = null)
    {

        return self::whereId($userId)->update(['reset_password' => 0]);
    }


    public static function profileUpload($userId = null, $uploadId = null)
    {

        self::whereId($userId)->update(['image_id' => $uploadId]);

        return self::whereId($userId)->first();
    }

    public static function generateToken($email)
    {

        if ($email) {
            $token = Str::random(16);

            self::where('email', $email)->update(['reset_password_token' => $token]);

            return self::where('email', $email)->first();
        }
    }

    public static function generateEmailVerificationToken($email)
    {

        if ($email) {
            $token = Str::random(16);

            self::where('email', $email)->update(['email_verify_token' => $token]);

            return self::where('email', $email)->first();
        }
    }

    public static function getUserDetailByIds()
    {

        $users = self::whereHas('assessments', function ($query) {

            $query->where('page', 0)
                ->orderBy('updated_at', 'desc');
        })->select(['id', 'first_name', 'last_name'])->orderBy('first_name')->get();

        foreach ($users as $user) {

            $user->setAppends([]);
        }

        return $users;
    }

    public static function updateUserLastStep($data = null, $userId = null)
    {
        $user = self::whereId($userId)->update($data);

        return $user;
    }

    public static function getAllClientUser()
    {
        return self::where('is_admin', 2)->get();
    }


    public static function createB2BSignup($data = null)
    {

        $data['step'] = 3;
        $data['email_verified_at'] = Carbon::now();
        $data['status'] = 1;
        $data['is_admin'] = Admin::IS_B2B;

        $user = self::create($data);

        return $user;
    }


    public static function addB2BMember($data = null)
    {
        $data['step'] = 3;
        $data['email_verified_at'] = Carbon::now();
        $data['status'] = 1;
        $data['is_admin'] = Admin::IS_B2U;
        $data['gender'] = $data['gender'] === 'male' ? 0 : 1;

        return self::create($data);
    }

    public static function allBusinessMembers($business_id = null)
    {

        $users = self::where('business_id', $business_id)
            ->with(['assessments' => function ($query) {
                $query->select('id', 'user_id');
            }])
            ->select(['id', 'first_name', 'last_name', 'email', 'gender', 'last_login', 'timezone', 'phone'])
            ->get();
        foreach ($users as $user) {
            $user->gender = $user->gender == Admin::IS_MALE ? 'Male' : 'Female';
            $user->setAppends([]);
        }

        return $users;
    }

    public static function MembersLimit($email = null)
    {
        return UserInvite::where('email', $email)->value('members_limit');
    }


    public static function UpdateMembersLimit($email = null)
    {

        UserInvite::where('email', $email)->decrement('members_limit', 1);
    }


    public static function UpdateMember($data = null, $memberId = null)
    {

        $data['gender'] = $data['gender'] === 'male' ? 0 : 1;
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            return self::where('id', $memberId)->update($data);
        } else {

            $userinfo = User::where('id', $memberId)->first();
            $data['password'] = $userinfo['password'];
            self::where('id', $memberId)->update($data);
        }
    }

    public static function deleteMember($id = null)
    {


        $updated = self::where('id', $id)->update(['business_id' => null]);

        $email = Helpers::getUser()->email;

        UserInvite::where('email', $email)->increment('members_limit', 1);

        return $updated;
    }

    public static function allCompanies()
    {
        return self::where('is_admin', Admin::IS_B2B)->get(['id', 'company_name']);
    }

    public static function addB2BCompanyName($B2BId = null, $companyName = null)
    {
        return self::whereId($B2BId)->update(['company_name' => $companyName]);
    }

    public static function B2BResetPassword($id, $password)
    {
        $user = self::find($id);
        if ($user) {

            $user->password = $password;
            $user->save();
            return $user;
        }


        return null;
    }


    public static function userDataForHAi($user_id = null)
    {
        $user = self::with('userIntentions')->whereId($user_id)->select(['id', 'first_name', 'last_name', 'date_of_birth'])->first()->setAppends([]);

        return $user;
    }

    public static function checkUserEmailInB2B($email)
    {
        return self::where('email', $email)->where('business_id', Helpers::getUser()->id)->whereHas('candidate', function ($query) {

            $query->where('share_data', 1);
        })->select(['id', 'email'])->first()?->id;
    }

    public static function updateVersion()
    {
        $admins = self::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_B2B, Admin::IS_B2U])->get();
        foreach ($admins as $admin) {
            $admin->version_update = 0;
            $admin->save();
        }
    }


    public static function updateSingleUserVersion($id = null)
    {

        return self::where('id', $id)->update(['version_update' => 1]);

    }

    public static function completeAssessmentWalkthrought()
    {
        $user = Helpers::getUser();

        if ($user['complete_assessment_walkthrough'] == 0) {
            $user->update(['complete_assessment_walkthrough' => 1]);
        }

        return $user;
    }

    public static function completeTutorial()
    {
        $user = Helpers::getUser();

        if ($user['complete_tutorial'] == 0) {
            $user->update(['complete_tutorial' => 1]);
        }

        return $user;
    }

    public static function getB2BDeletedAdmins($name = null, $email = null, $age = null, $perPage = null)
    {
        $users = self::where('is_admin', Admin::IS_B2B);

        if (!empty($name)) {
            $users->where(function ($query) use ($name) {
                $query->where('first_name', 'LIKE', "%$name%")
                    ->orWhere('last_name', 'LIKE', "%$name%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$name%"]);
            });
        }

        // Filter by email
        if (!empty($email)) {

            //    $users->where('email', $email);
            $users->where('email', 'LIKE', "%$email%");
        }

        // Filter by age
        if (!empty($age)) {
            $data['age_range'] = $age;
            $ageData = Helpers::explodeAgeRangeIntoAge($data);

            $min_date = Carbon::now()->subYears((int)($ageData['age_max'] ?? 0))->toDateString();
            $max_date = Carbon::now()->subYears((int)($ageData['age_min'] ?? 0))->toDateString();

            $users->whereBetween('date_of_birth', [$min_date, $max_date]);
        }
        return $users->where('is_permanently_deleted', 0)
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate($perPage);
    }

    public static function b2bDetailForHai($user_id)
    {

        $user = self::with('businessIntentions')->whereId($user_id)->select(['id', 'first_name', 'last_name', 'date_of_birth'])->first()->setAppends([]);

        return $user;
    }

    public static function userIntervalOfLife($date_of_birth = null)
    {

        $age = Carbon::parse($date_of_birth)->age;

        switch ($age) {
            case (7 <= $age && $age <= 11):
                $interval = [
                    'interval' => 'Connecting & Communicating',
                    'public_name' => 'Cycle of Life - Connecting & Communicating (7-11)'
                ];
                break;
            case (12 <= $age && $age <= 15):
                $interval = [
                    'interval' => 'Alchemical Revelation',
                    'public_name' => 'Cycle of Life - Alchemical Revelation (12-15)'
                ];
                break;
            case (16 <= $age && $age <= 20):
                $interval = [
                    'interval' => 'Motivation',
                    'public_name' => 'Cycle of Life - Motivation (16-20)'
                ];
                break;
            case (21 <= $age && $age <= 29):
                $interval = [
                    'interval' => 'Roadworthy ',
                    'public_name' => 'Cycle of Life - Roadworthy (21-29)'
                ];
                break;
            case (30 <= $age && $age <= 33):
                $interval = [
                    'interval' => 'Power',
                    'public_name' => 'Cycle of Life - The Power Interval (30-33)'
                ];
                break;
            case (34 <= $age && $age <= 42):
                $interval = [
                    'interval' => 'MidLife Transformation',
                    'public_name' => 'Cycle of Life - Mid-Life Transformation (34-42)'
                ];
                break;
            case (43 <= $age && $age <= 51):
                $interval = [
                    'interval' => 'Awareness',
                    'public_name' => 'Cycle of Life - Awareness (43-51)'
                ];
                break;
            case (52 <= $age && $age <= 65):
                $interval = [
                    'interval' => 'Payit Forward',
                    'public_name' => 'Cycle of Life - Pay It Forward (52-65)'
                ];
                break;
            case (66 <= $age && $age <= 69):
                $interval = [
                    'interval' => 'Liberated',
                    'public_name' => 'Cycle of Life - Liberated (66-69)'
                ];
                break;
            case (70 <= $age && $age <= 74):
                $interval = [
                    'interval' => 'Being',
                    'public_name' => 'Cycle of Life - Being (70-74)'
                ];
                break;
            case (75 <= $age && $age <= 83):
                $interval = [
                    'interval' => 'Life Review',
                    'public_name' => 'Cycle of Life - Life Review (75-83)'
                ];
                break;
            default:
                $interval = [
                    'interval' => 'Surrender',
                    'public_name' => 'Cycle of Life - Surrender (84+)'
                ];
                break;
        }

        return $interval;
    }

    public static function changeProfileAccess($profileAccess = null)
    {

        return Helpers::getUser()->update(['profile_status' => (int)$profileAccess]);

    }

    public static function changeHaiAccess($haiAccess = null)
    {

        return Helpers::getUser()->update(['hai_status' => (int)$haiAccess]);

    }


    public static function getUserDataForHai()
    {
        $users = self::where('is_admin', 2)->whereHas('haiAssessments', function ($query) {
            $query->where('page', 0);

        })->limit(10)->get();

        foreach ($users as $user) {

            $user->setAppends([]); 

            $user['gender'] = $user->gender == Admin::IS_MALE ? 'Male' : 'Female';

            $user['last_login'] = Carbon::parse($user['last_login'])->format('m/d/Y h:i A');
//            $user->setAppends(['point','connection_status','is_follow']);
        }


        return $users;
    }

}
