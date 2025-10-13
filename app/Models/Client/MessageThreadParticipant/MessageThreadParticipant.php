<?php

namespace App\Models\Client\MessageThreadParticipant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreadParticipant extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function getSingleUser($loginUserId = null, $threadId = null)
    {
        return self::where('user_id', $loginUserId)->where('message_thread_id', $threadId)->first();
    }
}
