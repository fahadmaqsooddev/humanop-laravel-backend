<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Admin\Alchemy\AlchemyCode;
use App\Models\Client\Connection\Connection;
use App\Models\Client\Follow\Follow;
use App\Models\Client\Story\Story;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, Billable,HasRoles, SoftDeletes;

    protected $appends = ['photo_url','user_picture_url', 'is_follow','connection_status','feedback_submitted'
        ,'age_group', 'plan_name'];

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
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

    public function scopeSelection($query){
        return $query->select(['id','first_name','last_name','gender','email','phone','is_admin','is_feedback','image_id','age_min','age_max']);
    }

    // appends

    public function getUserPictureUrlAttribute(){
        return (request()->getSchemeAndHttpHost() . "/assets/img/bruce-mars.jpg");
    }

    public function getPhotoUrlAttribute(){
        return Helpers::getImage($this->image_id,'profile_pic.png');
    }

    public function getIsFollowAttribute(){

        return $this->followed()->where('user_id', Helpers::getWebUser()->id ?? Helpers::getUser()->id)->exists();
    }

    public function getConnectionStatusAttribute(){

        if ($this->sentConnectionRequest()->exists()){

            return 2; // sent connection request

        }elseif ($this->recevivedConnectionRequest()->exists()){

            return 3; // received connection request

        }elseif($this->confirmedConnectionRequest()->exists()){

            return 1; // confirm connection request

        }else{

            return 0;
        }

    }

    public function getFeedbackSubmittedAttribute(){
        return $this->feedback()->exists();
    }

    public function getAgeGroupAttribute(){
        return ($this->age_min . '-' . $this->age_max);
    }

    public function getPlanNameAttribute(){

        return $this->userSubscription->plan->name ?? "Freemium";
    }

    // relations
    public function stories(){

        return $this->hasMany(Story::class, 'user_id','id');
    }

    public function followed(){

        return $this->HasMany(Follow::class,'follow_id', 'id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'user_id', 'id');
    }

    public function feedback(){

        return $this->hasOne(Client\Feedback\Feedback::class,'user_id','id');
    }

    public function sentConnectionRequest(){

        return $this->hasOne(Connection::class,'friend_id','id')

            ->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('status', 0);
    }

    public function recevivedConnectionRequest(){

        return $this->hasOne(Connection::class,'user_id','id')

            ->where('friend_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('status', 0);
    }

    public function confirmedConnectionRequest(){

        return $this->hasOne(Connection::class,'friend_id','id')

            ->where('user_id', (Helpers::getWebUser()->id ?? Helpers::getUser()->id))->where('status', 1);
    }

    public function colorCodes(){

        return $this->hasManyThrough(AssessmentColorCode::class,Assessment::class,'user_id','assessment_id','id','id');
    }

    public function payments(){

        return $this->hasMany(Payment::class,'user_id','id');
    }

    public function userSubscription(){

        return $this->hasOne(Subscription::class,'user_id','id')->latest();
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

    public static function allSubAdmin(){
        $subAdmins = self::where('is_admin',3)->get();
        return $subAdmins;
    }

    public static function getSingleUser($id = null){
        $user = self::find($id);
        return $user;
    }

    public static function getUserAge($user_age = null)
    {

        switch ($user_age) {
            case '0-3':
                $interval = 'Generic Interval';
                break;
            case '3-7':
                $interval = 'Socialization Interval';
                break;
            case '7-12':
                $interval = 'Ready to Learn - Energy Centers';
                break;
            case '12-16':
                $interval = 'Alchemical Revelation';
                break;
            case '16-21':
                $interval = 'Motivation';
                break;
            case '21-29':
                $interval = 'Roadworthy';
                break;
            case '30-33':
                $interval = 'Power Interval';
                break;
            case '34-43':
                $interval = 'Mid-Life Transformation Interval';
                break;
            case '43-52':
                $interval = 'Awareness Interval';
                break;
            case '52-66':
                $interval = 'Pay It Forward Interval';
                break;
            case '66-70':
                $interval = 'Interval of Liberation';
                break;
            case '70-75':
                $interval = 'Interval of “Being”';
                break;
            case '75-84':
                $interval = 'Life Review Interval';
                break;
            default:
                $interval = 'Interval of Surrender';
                break;
        }

        return $interval;
    }

    public static function updateUser($data = null, $id = null){

        return self::find($id)->update($data);

    }

    public static function createUser($data = null){

        $data['is_admin'] = 2;
        $age = explode('-', $data['age_range']);
        $data['age_min'] = $age[0];
        $data['age_max'] = $age[1];

        $user = self::create($data);

        return $user;

    }
    public static function createSubAdmin($data = null){
        $data['is_admin'] = 3;
        $age = explode('-', $data['age_range']);
        $data['age_min'] = $age[0];
        $data['age_max'] = $age[1];
        $data['status'] = 1;

        $user = self::create($data);

        return $user;

    }
    public static function createCustomerOnStripe($user = null, $stripe_keys = null){

        $stripe = new \Stripe\StripeClient($stripe_keys['api_key']);

        $stripe_customer = $stripe->customers->create([
            'name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
        ]);

        $user->stripe_id = $stripe_customer->id;

        $user->save();

    }

    public static function checkPassword($password,$id){
        $user = self::find($id);
        if ($user && Hash::check($password, $user->password)) {
            return true;
        } else {
            return false;
        }
    }

    public static function user($id = null){
        $user = self::whereId($id)->selection()->first();
        $user['gender'] = ($user['gender'] === 2 || $user['gender'] === '2' ? "male" : "female");
        return $user;
    }

    public static function createClient($data = null){

        $data['gender'] = $data['gender'] === 'male' ? 2 : 1;

        $data['is_admin'] = 2; // 2 for client

        return self::create($data);

    }

    public static function updateUserProfile($request = null){

        $user_id = Helpers::getUser()->id;

        $request['gender'] = $request['gender'] === 'male' ? 2 : 1;

        self::whereId($user_id)->update($request);

        return self::user($user_id);
    }

    public static function updateUserPassword($password = null){

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
            'pm_exp_month' => '0'.$paymentMethod['card']['exp_month'],
            'pm_exp_year' => $paymentMethod['card']['exp_year'],
        ]);

    }

    public static function storyUsers(){

        $logged_in_user_id = Helpers::getWebUser()->id ?? Helpers::getUser()->id;

        $users = self::whereHas('stories', function ($q){

            return $q->where('created_at', ">", Carbon::now()->subDay());
        })

            ->whereNot('id', $logged_in_user_id)

            ->select(['id','first_name', 'last_name'])

            ->get();

        return $users;
    }

    public static function updateUserPaymentMethodFromApi($paymentMethod = null, $request = null)
    {

        self::whereId(Helpers::getUser()->id)->update([

            'payment_method' => $paymentMethod['id'],
            'pm_type' => $paymentMethod['card']['brand'],
            'pm_last_four' => $paymentMethod['card']['last4'],
            'pm_exp_month' => '0'.$paymentMethod['card']['exp_month'],
            'pm_exp_year' => $paymentMethod['card']['exp_year'],
            'card_name' => $request->input('card_name')

        ]);

    }

    public static function userStories($id = null){

        return self::whereId($id)

            ->with(['stories' => function($q){

            $q->where('created_at', ">", Carbon::now()->subDay());

        }])

            ->select(['id','first_name','last_name'])

            ->first();
    }

    public static function updateUserIsFeedback(){

        $user = self::whereId(Helpers::getWebUser()->id ?? Helpers::getUser()->id)->select(['id','is_feedback','is_admin'])->first();

        if (!$user->feedback && $user->is_admin === 2){

            if ($user->is_feedback === 3 || $user->is_feedback === 2){

                $user->decrement('is_feedback', 1);

            }else if ($user->is_feedback === 1){

                $user->update(['is_feedback' => 3]);
            }

        }

    }

    public static function allClients($search_name = null, $per_page = 12, $style_feature_code = null, $alchemy_codes_array = []){

        $users = self::query();

        if (!empty($search_name)){

            $users = $users->where(function ($q) use ($search_name){

                $q->where('first_name', 'LIKE', "%$search_name%")

                    ->orWhere('last_name', 'LIKE', "%$search_name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

            });

        }

        if (!empty($style_feature_code)){

            $users = $users->whereHas('colorCodes', function ($q) use ($style_feature_code){

                $q->where('code', $style_feature_code)->where('code_color', 'green');

            });

        }

        if (!empty($alchemy_codes_array)){

            $sqlArray = '(' . join(',', $alchemy_codes_array) . ')';

            $users = $users->whereHas('assessments', function ($q) use ($sqlArray){

                $q->whereRaw("concat(g, '', s, '', c) IN $sqlArray");

            });

        }

        $users = $users->where('is_admin', \App\Enums\Admin\Admin::IS_CUSTOMER)

            ->paginate($per_page);

        return $users;
    }

    public static function allPaginatedClients($request = null){

        $users = self::query();

        $users = $users->when($request->input('name'), function ($q, $search_name){

            $q->where(function ($q) use ($search_name){

                $q->where('first_name', 'LIKE', "%$search_name%")

                    ->orWhere('last_name', 'LIKE', "%$search_name%")

                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%$search_name%' ");

            });

        });

        $users = $users->when($request->input('style_feature_code'), function ($q, $style_feature_code){

            $q->whereHas('colorCodes', function ($q) use ($style_feature_code){

                $q->where('code', $style_feature_code)->where('code_color', 'green');

            });

        });

        $users = $users->when($request->input('alchemy_code'), function ($q, $alchemy_code){

            $alchemy_codes_array = AlchemyCode::getNumbersFromCode($alchemy_code);

            if (isset($alchemy_codes_array[0])){

                $sqlArray = '(' . join(',', $alchemy_codes_array) . ')';

                $q->whereHas('assessments', function ($q) use ($sqlArray){

                    $q->whereRaw("concat(g, '', s, '', c) IN $sqlArray");

                });

            }

        });

        $users = $users->where('is_admin', \App\Enums\Admin\Admin::IS_CUSTOMER);

        return Helpers::pagination($users, $request->input('pagination'),$request->input('per_page'));
    }

    public static function deletedClients(){

        return self::where('is_admin', Admin::IS_CUSTOMER)

            ->where('is_permanently_deleted', 0)

            ->onlyTrashed()

            ->get();

    }

}
