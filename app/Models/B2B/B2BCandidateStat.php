<?php

namespace App\Models\B2B;

use App\Helpers\Helpers;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class B2BCandidateStat extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id');
    }

    public function assessments()
    {
        return $this->hasOne(Assessment::class, 'user_id', 'candidate_id')->latest();
    }

    public static function getResult($userId = null)
    {

        return self::where('business_id', $userId)->whereHas('assessments')->with(['users', 'assessments' => function ($query) {
            $query->where('page', 0);
        }])->first();
    }

    public static function createRecord($candidateId = null, $actionPlanId = null)
    {

        $data['business_id'] = Helpers::getUser()['id'];

        $data['candidate_id'] = $candidateId;

        $data['action_plan_id'] = $actionPlanId;

        return self::create($data);
    }

    public static function updateRecord($candidateId = null, $actionPlanId = null)
    {

        return self::where('business_id', Helpers::getUser()['id'])->update([
            'candidate_id' => $candidateId,
            'action_plan_id' => $actionPlanId,
        ]);
    }
}
