<?php

namespace App\Models\Client\Follow;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function addFollow($follow_id = null){

        $data['follow_id'] = $follow_id;

        $data['user_id'] = Helpers::getWebUser()->id;

        $follow = self::where('follow_id', $follow_id)->where('user_id', $data['user_id'])->first();

        if ($follow){

            $follow->delete();

        }else{

            self::create($data);
        }

    }
}
