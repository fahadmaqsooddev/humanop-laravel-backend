<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2BBusinessCandidates extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function checkBusinessCandidate($businessId = null, $candidateId = null)
    {
        return self::where('business_id', $businessId)->where('candidate_id', $candidateId)->exists();
    }

    public static function registerCandidate($businessId = null, $candidateId = null)
    {
        return self::create([
            'business_id' => $businessId,
            'candidate_id' => $candidateId
        ]);
    }
}
