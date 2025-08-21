<?php

namespace App\Models\Client\Suggestion;

use App\Models\Admin\SuggestedItem\SuggestedItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionForYou extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function suggestedItem()
    {
        return $this->belongsTo(SuggestedItem::class, 'suggested_item_id', 'id');
    }

    public static function allSuggestion($userId = null)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function checkSuggestion($userId = null)
    {
        return self::where('user_id', $userId)->with('suggestedItem')->latest()->first();
    }

    public static function createSuggestion($userId = null, $suggestedItemId = null)
    {

        return self::create([
            'user_id' => $userId,
            'suggested_item_id' => $suggestedItemId,
        ]);
    }


}
