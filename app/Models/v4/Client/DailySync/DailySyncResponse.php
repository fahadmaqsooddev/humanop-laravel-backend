<?php

namespace App\Models\v4\Client\DailySync;

use App\Models\v4\Admin\DailySync\DailySyncQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySyncResponse extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function session()
    {
        return $this->belongsTo(DailySyncSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(DailySyncQuestion::class, 'question_id');
    }

    public static function getSingleSession($sessionId = null, $questionId = null)
    {
        return self::where('session_id', $sessionId)
            ->where('question_id', $questionId)
            ->first();
    }

    public static function createResponse($session = null, $question = null)
    {
        return self::create([
            'session_id' => $session->id,
            'question_id' => $question->id,
            'question_text' => $question->question_text,
        ]);
    }

    public static function submitQuestionCount($latestSessionId = null)
    {
        return self::where('session_id', $latestSessionId)
            ->whereNotNull('response_text')
            ->where('response_text', '!=', '')
            ->count();
    }
}
