<?php

namespace App\Models\v4\Admin\DailySync;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySyncQuestion extends Model
{
    use HasFactory;

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getQuestions()
    {
        return self::orderBy('created_at', 'desc')->get();
    }

    public static function createQuestion($question = null)
    {

        return self::create([
            'question_text' => $question,
            'is_active' => self::ACTIVE,
        ]);
    }
}
