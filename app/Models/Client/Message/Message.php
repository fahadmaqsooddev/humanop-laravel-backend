<?php

namespace App\Models\Client\Message;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // mutator
    public function getCreatedAtAttribute($value){

        return Carbon::parse($value)->diffForHumans();
    }


    // query
    public static function createMessage($data = null){

       return  self::create($data);
    }

    public static function threadMessages($thread_id = null){

        self::where('message_thread_id', $thread_id)->where('is_read', 0)->update(['is_read' => 1]);

        return self::where('message_thread_id', $thread_id)->get();
    }
}
