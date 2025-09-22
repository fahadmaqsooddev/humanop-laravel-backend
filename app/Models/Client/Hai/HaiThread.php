<?php

namespace App\Models\Client\Hai;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiThread extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getUserChats()
    {

        return self::where('user_id', Helpers::getUser()['id'])->get();

    }

    public static function createThreadIds($thread = null)
    {

        return self::create([
            'title' => $thread['thread_title'],
            'user_id' => $thread['user_id'],
            'thread_id' => $thread['thread_id'],
        ]);

    }
}
