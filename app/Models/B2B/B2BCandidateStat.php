<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helpers;
use App\Models\User;
use App\Models\Assessment;

class B2BCandidateStat extends Model
{
    use HasFactory;
    // public $businessId;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
        // $this->businessId = Helpers::getUser()['id'];
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id');
    }

    public function assessments()
    {
        return $this->hasOne(Assessment::class, 'user_id', 'candidate_id')->latest();
    }


    public static function getResult(){
        $businessId = Helpers::getUser()['id'];
        // return self::where('business_id', $businessId)->first();
        
        return self::where('business_id', $businessId)
        ->whereHas('assessments')
        ->with(['users', 'assessments']) 
        ->first();
    }

    public static function createRecord($creator_id=null,$action_plan=null){
        $businessId = Helpers::getUser()['id'];
    
        $data['business_id'] = $businessId;
        $data['candidate_id'] = $creator_id;
        $data['action_plan_id'] = $action_plan;
        return self::create($data);
    }

    public static function updateRecord($creator_id=null,$action_plan=null){
        $businessId = Helpers::getUser()['id'];
        return self::update([
            'creator_id' => $creator_id,
            'action_plan_id' => $action_plan,
        ])
        ->where('business_id', $businessId);
    }
}
