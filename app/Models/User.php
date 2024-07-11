<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Billable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, Billable,HasRoles;

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
        $this->attributes['password'] = Hash::make($value);
    }

    // scope

    public function scopeSelection($query){

        return $query->select(['id','first_name','last_name','gender','email','phone','is_admin']);
    }


    // relations

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
//        $data['password'] = Hash::make($data['password']);

        $user = self::create($data);

        return $user;

    }
    public static function createSubAdmin($data = null){
        $data['is_admin'] = 3;
        $age = explode('-', $data['age_range']);
        $data['age_min'] = $age[0];
        $data['age_max'] = $age[1];
        $data['status'] = 1;
//        $data['password'] = Hash::make($data['password']);

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

        $user['gender'] = ($user['gender'] === 2 || $user['gender'] === '2' ? "Male" : "Female");

        return $user;
    }

    public static function createClient($data = null){

        $data['is_admin'] = 2; // 2 for client

        return self::create($data);

    }
}
