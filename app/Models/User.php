<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Admin\Alchemy\AlchemyCode;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Follow\Follow;
use App\Models\Client\Story\Story;
use App\Models\Client\StoryView\StoryView;
use App\Models\IntentionPlan\IntentionPlan;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Client\Point\Point;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles, SoftDeletes;

    protected $appends = ['point', 'photo_url', 'user_picture_url', 'is_follow', 'connection_status', 'feedback_submitted'
        , 'age_group', 'plan_name', 'optional_trait'];

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
        //        if (str_contains(request()->path(), 'api')){
        $this->attributes['password'] = Hash::make($value);
        //        }
    }

    // scope

    public function scopeSelection($query)
    {
        return $query->select(['id', 'first_name', 'last_name', 'gender', 'email', 'phone', 'is_admin', 'is_feedback', 'image_id', 'date_of_birth', 'hai_chat', 'referral_code','timezone','two_way_auth','intro_check']);
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

        $point = Point::where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->select('point')->first();

        if ($point) {

            return $point->point;
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

        return $this->followed()->where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->exists();
    }

    public function getConnectionStatusAttribute()
    {

        if ($this->sentConnectionRequest()->exists()) {

            return 2; // sent connection request

        } elseif ($this->recevivedConnectionRequest()->exists()) {

            return 3; // received connection request

        } elseif ($this->confirmedConnectionRequest()->exists()) {

            return 1; // confirm connection request

        } else {

            return 0;
        }

    }

    public function getFeedbackSubmittedAttribute()
    {
        return $this->feedback()->exists();
    }

    public function getAgeGroupAttribute()
    {
        return 0;//($this->age_min . '-' . $this->age_max);
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

        return $this->hasOne(Connection::class, 'friend_id', 'id')
            ->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('status', 0);
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
        $subAdmins = self::where('is_admin', 3)->get();
        return $subAdmins;
    }

    public static function getSingleUser($id = null)
    {
        $user = self::find($id);
        return $user;
    }

    public static function getUserAge($date_of_birth = null)
    {

        $age = Carbon::parse($date_of_birth)->age;

        switch ($age) {
            case (0 == $age):
                $interval = 'Interval of Surrender';
                break;
            case (0 < $age && $age <= 3):
                $interval = 'Generic Interval';
                break;
            case (3 <= $age && $age <= 7):
                $interval = 'Socialization Interval';
                break;
            case (7 <= $age && $age <= 12):
                $interval = 'Ready to Learn - Energy Centers';
                break;
            case (12 <= $age && $age <= 16):
                $interval = 'Alchemical Revelation';
                break;
            case (16 <= $age && $age <= 21):
                $interval = 'Motivation';
                break;
            case (21 <= $age && $age <= 29):
                $interval = 'Roadworthy';
                break;
            case (30 <= $age && $age <= 33):
                $interval = 'Power Interval';
                break;
            case (34 <= $age && $age <= 43):
                $interval = 'Mid-Life Transformation Interval';
                break;
            case (43 <= $age && $age <= 52):
                $interval = 'Awareness Interval';
                break;
            case (52 <= $age && $age <= 66):
                $interval = 'Pay It Forward Interval';
                break;
            case (66 <= $age && $age <= 70):
                $interval = 'Interval of Liberation';
                break;
            case (70 <= $age && $age <= 75):
                $interval = 'Interval of “Being”';
                break;
            case (75 <= $age && $age <= 84):
                $interval = 'Life Review Interval';
                break;
            default:
                $interval = 'Interval of Surrender';
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
        $user['two_way_auth'] = ($user['two_way_auth'] === Admin::TWO_WAY_AUTH_ACTIVE ? true : false);
        $user['intro_check'] = ($user['intro_check'] === Admin::INTRO_CHECK_UN_READ ? true : false);
        return $user;
    }

    public static function createClient($data = null)
    {

        $data['gender'] = $data['gender'] === 'male' ? 0 : 1;

        $data['is_admin'] = Admin::IS_CUSTOMER; // 2 for client

        $data['status'] = 1;

        $data['hai_chat'] = 2;

        return self::create($data);

    }

    public static function updateUserProfile($request = null)
    {

        $user_id = Helpers::getUser()->id;

        $request['gender'] = $request['gender'] === 'male' ? 0 : 1;

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

            $user->setAppends(['point', 'photo_url', 'user_picture_url', 'is_follow', 'connection_status', 'feedback_submitted'
                , 'age_group', 'plan_name', 'is_viewed_stories']);
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

        $user = self::whereId(Helpers::getWebUser()->id ?? Helpers::getUser()->id)->select(['id', 'is_feedback', 'is_admin'])->first();

        if (!$user->feedback && $user->is_admin === 2) {

            if ($user->is_feedback === 3 || $user->is_feedback === 2) {

                $user->decrement('is_feedback', 1);

            } else if ($user->is_feedback === 1) {

                $user->update(['is_feedback' => 3]);
            }

        }

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

    public static function allPaginatedClients($request = null)
    {

        $users = self::query();

        $users = $users->when($request->input('name'), function ($q, $search_name) {

            $q->where(function ($q) use ($search_name) {

                $q->where('first_name', 'LIKE', "%$search_name%")
                    ->orWhere('last_name', 'LIKE', "%$search_name%")
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

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

        $users = $users->where('is_admin', \App\Enums\Admin\Admin::IS_CUSTOMER);

        return Helpers::pagination($users, $request->input('pagination'), $request->input('per_page'));
    }

    public static function deletedClients()
    {

        return self::whereIn('is_admin', [Admin::IS_CUSTOMER, Admin::IS_PRACTITIONER])
            ->where('is_permanently_deleted', 0)
            ->onlyTrashed()
            ->get();

    }

    public static function adminClients($search_name = null, $email = null, $age = null, $per_page = 10, $isAdmin)
    {
        $userId = Helpers::getWebUser()['id'];

        $isAdminLevel = Helpers::getWebUser()['is_admin'];

        $users = ($isAdminLevel == 4) ? self::where('practitioner_id', $userId) : self::query();

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
            $users->where('email', $email);
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
            ->paginate($per_page)
            ->setPath(route('admin_all_users'));

        return $users;
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

//        $users = $users->when($request->input('email'), function ($query, $email) {
//
//            $query->where('email', $email);
//        });
//
//        $users = $users->when($request->input('google_id'), function ($query, $google_id) {
//
//            $query->where('google_id', $google_id);
//        });
//
//        $users = $users->when($request->input('apple_id'), function ($query, $apple_id) {
//
//            $query->where('apple_id', $apple_id);
//
//        });

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

        self::whereId($id)->delete();
    }

    public static function emailVerified($userId = null)
    {
        return self::whereId($userId)->update(['email_verified_at' => Carbon::now()]);
    }

    public static function checkEmail($userEmail = null)
    {
        return self::where('email', $userEmail)->first();
    }

    public static function updateUserTimezone($timezone = null)
    {
        $userId = Helpers::getWebUser()['id'];

        return self::whereId($userId)->update(['timezone' => $timezone]);

    }


}
