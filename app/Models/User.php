<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
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

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
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
    public static function allUser(){
        $users = self::all();
        return $users;
    }
    public static function getSingleUser($id = null){
        $user = self::find($id);
        return $user;
    }
    public static function updateUser($data = null, $id = null){

        $user = self::find($id)->update($data);

        return $user;

    }

    public static function checkPassword($password,$id){
        $user = self::find($id);
        if ($user && Hash::check($password, $user->password)) {
            return true;
        } else {
            return false;
        }
    }
}
