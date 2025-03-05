<?php

namespace App\Models\B2B;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assessment;
use App\Enums\Admin\Admin;

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
    

    public function users(){
        return $this->belongsTo(User::class, 'candidate_id', 'id');
    }
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'user_id', 'candidate_id');
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

    // public static function allBusinessMembers($business_id = null) {
        
    // $query = self::with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone']) ; 

    // if (!is_null($business_id)) {
    //     $query->where('business_id', $business_id);
    // }

    // $data = $query->get();
  
    
    // return $data;
    

    
    // }

    public static function allBusinessMembers($business_id = null) {
        
        return self::with([
            'users:id,first_name,last_name,email,gender,last_login,timezone,phone',
            'assessments'=>function($query){
                $query->select('id', 'user_id');
            } 
        ])
        ->when($business_id, fn($query) => $query->where('business_id', $business_id))
        ->get();
    }
    
    
    
}
