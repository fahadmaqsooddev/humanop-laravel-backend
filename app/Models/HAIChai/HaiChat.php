<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;
use Illuminate\Support\Carbon;

class HaiChat extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleChat($id = null)
    {
        return self::whereId($id)->first();
    }

    public static function getChat()
    {
        return self::where('user_id', Helpers::getWebUser()->id)->get(['id','query','answer','likedislike']);
    }

    public static function createChat($query = null, $reply = null)
    {
        return self::create([
            'user_id' => Helpers::getWebUser()->id,
            'query' => $query,
            'answer' => $reply[0],
            'likedislike' => $reply[1],
        ]);
    }

    public static function updateChat($id = null, $likedislike = null)
    {
        return self::whereId($id)->update(['likedislike' => $likedislike]);
    }
}
