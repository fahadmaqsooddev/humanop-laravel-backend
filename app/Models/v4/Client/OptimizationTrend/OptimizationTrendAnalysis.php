<?php

namespace App\Models\v4\Client\OptimizationTrend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class OptimizationTrendAnalysis extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable') ?? [];
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden') ?? [];

        parent::__construct($attributes);
    }

    public static function getOptimizationAnalysis($userId = null, $currentAssessmentId = null, $previousAssessmentId = null): Collection
    {
        return self::query()
            ->where('user_id', $userId)
            ->where('current_assessment_id', $currentAssessmentId)
            ->where('previous_assessment_id', $previousAssessmentId)
            ->latest('id')
            ->get();
    }

    public static function storeOptimizationAnalysis(array $payload): self
    {
        return self::query()->updateOrCreate(
            [
                'user_id' => (int)$payload['user_id'],
                'current_assessment_id' => (int)$payload['current_assessment_id'],
                'previous_assessment_id' => (int)$payload['previous_assessment_id'],
                'context' => $payload['context'] ?? null,
            ],
            [
                'ai_response' => $payload['ai_response'] ?? null,
            ]
        );
    }
}
