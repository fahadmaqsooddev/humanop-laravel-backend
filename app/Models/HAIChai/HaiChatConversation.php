<?php

namespace App\Models\HAIChai;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function Aws\flatmap;

class HaiChatConversation extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }


    public static function createUserFetchChatConversation($userId = null, $conversations = null)
    {

        foreach ($conversations as $conversation){

            $conversation['user_id'] = $userId;

            self::create($conversation);

        }

        return true;

    }
}
