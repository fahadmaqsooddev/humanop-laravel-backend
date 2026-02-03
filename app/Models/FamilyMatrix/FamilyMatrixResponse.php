<?php

namespace App\Models\FamilyMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMatrixResponse extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getFamilyMatrix($userId = null, $targetId = null)
    {
        return self::where('user_id', $userId)->where('target_id', $targetId)->first();
    }

    public static function createFamilyMatrixResponse($userId = null, $targetId = null, $response = null)
    {

        return self::create([
            'user_id' => $userId,
            'target_id' => $targetId,
            'vide_check_text' => $response['content']['vibe_check']['text'],
            'physics_friction_analysis' => $response['content']['the_physics']['friction_analysis'],
            'physics_flow_analysis' => $response['content']['the_physics']['flow_analysis'],
            'system_hack_title' => $response['content']['system_hack']['title'],
            'system_hack_actionable_step' => $response['content']['system_hack']['actionable_step']
        ]);

    }

}
