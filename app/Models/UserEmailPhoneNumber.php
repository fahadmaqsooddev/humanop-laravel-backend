<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEmailPhoneNumber extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function changeEmailsPhonesConditional($whereNull = null, $where_clause = [], $data = [])
    {
        return self::where([$where_clause])->whereNull($whereNull)->update($data);
    }

    public static function getUserEmailsPhones($user_id = null)
    {
        return self::select('email', 'phone_no', 'default_email', 'default_phone_no')->where('user_id', $user_id)->get();
    }

    public static function getSingleEmailPhone($id = null)
    {
        return self::find($id);
    }

    public static function createUserEmailPhone($data = [])
    {
        return self::create($data);
    }

    public static function removeEmailPhone($data = null)
    {
        return self::find($data)->delete();
    }
}
