<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Models\Client\Follow\Follow;
use App\Models\Client\Story\Story;
use Carbon\Carbon;
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

    protected $appends = ['user_picture_url', 'is_follow'];

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

        return $query->select(['id','first_name','last_name','gender','email','phone','is_admin']);
    }

    // appends
    public function getUserPictureUrlAttribute(){

        return (request()->getSchemeAndHttpHost() . "/assets/img/bruce-mars.jpg");
    }

    public function getIsFollowAttribute(){

        return $this->followed()->where('user_id', Helpers::getWebUser()->id)->exists();
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

    public static function updateUser($data = null, $id = null){

        $user = self::find($id)->update($data);

        return $user;

    }

    public static function createUser($data = null){

        $data['is_admin'] = 2;
        $age = explode('-', $data['age_range']);
        $data['age_min'] = $age[0];
        $data['age_max'] = $age[1];
        $data['password'] = Hash::make($data['password']);

        $user = self::create($data);

        return $user;

    }
    public static function createSubAdmin($data = null){
        $data['is_admin'] = 3;
        $age = explode('-', $data['age_range']);
        $data['age_min'] = $age[0];
        $data['age_max'] = $age[1];
        $data['status'] = 1;
        $data['password'] = Hash::make($data['password']);

        $user = self::create($data);

        return $user;

    }
    public static function createCustomerAndSubscriptionOnStripe($user = null){

        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));

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

        $users = self::whereHas('stories', function ($q){

            return $q->where('created_at', ">", Carbon::now()->subDay());
        })

            ->with('stories', function($q){

                $q->select(['id','user_id']);

            })

            ->whereNot('id', Helpers::getWebUser()->id)

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
}
