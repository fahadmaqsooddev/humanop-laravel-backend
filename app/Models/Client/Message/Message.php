<?php

namespace App\Models\Client\Message;

use App\Helpers\Helpers;
use App\Models\Client\MessageRead\MassageRead;
use App\Models\Client\MessageThread\MessageThread;
use App\Models\User;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $appends = ['upload_url'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function getUploadUrlAttribute()
    {
        if (!empty($this->upload_id)) {

            return Helpers::getImage($this->upload_id, 'humanop_default_image.png')['url'];

        } else {

            return null;
        }

    }

    //relation
    public function thread()
    {
        return $this->belongsTo(MessageThread::class, 'message_thread_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reads()
    {
        return $this->hasMany(MassageRead::class, 'message_id');
    }

    // mutator
    public function getCreatedAtAttribute($value)
    {

        return Carbon::parse($value)->diffForHumans();
    }


    // query
    public static function createMessage($request = null, $messageThread = null)
    {

        $msg = Message::create([
            'message_thread_id' => $messageThread->id,
            'sender_id' => $request->user()->id,
            'message' => $request->input('message'),
            'is_read' => false,
            'upload_id' => $request['upload_id'],
        ]);

        $messageThread->touch();

        return $msg->load('sender:id,first_name,last_name');

    }

    public static function threadMessages($thread_id = null)
    {

        self::where('message_thread_id', $thread_id)->where('is_read', 0)->update(['is_read' => 1]);

        return self::where('message_thread_id', $thread_id)->get();
    }
}
