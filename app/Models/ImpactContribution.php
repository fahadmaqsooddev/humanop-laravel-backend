<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ImpactContribution extends Model
{
    use HasFactory;


    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

     /**
     * Create a new impact contribution
     *
     * @param int $userId
     * @param int $projectId
     * @param int $hpContributed
     * @return ImpactContribution
     */
    public static function createContribution($userId, $projectId, $hpContributed)
    {
        return self::create([
            'user_id' => $userId,
            'impact_project_id' => $projectId,
            'hp_contributed' => $hpContributed,
        ]);
    }

    public function project()
    {
        return $this->belongsTo(ImpactProject::class,'impact_project_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


     
}
