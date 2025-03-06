<?php

namespace App\Models\B2B;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assessment;

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

    //    RelationShip
    public function users()
    {
        return $this->belongsTo(User::class, 'candidate_id', 'id');
    }

    public function assessments()
    {
        return $this->hasOne(Assessment::class, 'user_id', 'candidate_id')->latest();
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

    public static function allBusinessMembers($business_id = null)
    {
        return self::with([
            'users:id,first_name,last_name,email,gender,last_login,timezone,phone',
            'assessments' => function ($query) {
                $query->select('id', 'user_id');
            }
        ])
            ->when($business_id, fn($query) => $query->where('business_id', $business_id))
            ->get();
    }

    public static function getBusinessCandidate()
    {
        $businessId = Helpers::getUser()['id'];

        // return self::where('business_id', $businessId)->whereHas('assessments')
        // ->with(['users', 'assessments'=>function($query){
        //     $query->where('page',0);
        // }])->first();
        return self::where('business_id', $businessId)
    ->whereHas('assessments')
    ->with(['users', 'assessments' => function ($query) {
        $query->where('page', 0);
    }])
    ->inRandomOrder() // Randomly orders the results
    ->first(); // Picks the first random candidate


    }

}
