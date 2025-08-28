<?php

namespace App\Models;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
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
        return self::select('id','email', 'phone_no', 'default_email', 'default_phone_no')->where('user_id', $user_id)->get();
    }

    public static function getSingleEmailPhone($id = null)
    {
        return self::find($id);
    }

    public static function createUserEmailPhone($data = null)
    {
        $data['default_email'] = $data['email'] ? Admin::NORMAL_EMAIL : null;
        $data['default_phone_no'] = $data['phone_no'] ? Admin::NORMAL_PHONE : null;
        $data['user_id'] = Helpers::getUser()->id;

        return self::create($data);
    }

    public static function removeEmailPhone($id = null)
    {

        $record = self::getSingleEmailPhone($id);

        if ($record) {

            $record->delete();

            return true;

        }else{

            return false;

        }

    }
}
