<?php

namespace App\Models\B2B;

use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use App\Models\Assessment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
       
        // return self::with([
        //     'users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name',
        //     'assessments' => function ($query) {
        //         $query->select('id', 'user_id');
        //     }
        // ])


        return self::whereHas('users', function ($query) {
            $query->where('is_admin', Admin::IS_B2U);
        })
        ->with([
            'users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name',
            'assessments:id,user_id'
        ])
        ->when($business_id, fn($query) => $query->where('business_id', $business_id))
        ->get();
    
    }
    
    public static function allBusinessCandidates($business_id = null)
    {
        return self::whereHas('users',function($query){
            $query->where('is_admin',Admin::IS_CUSTOMER)->where('archive_consideration',Admin::NOT_ARCHIVED)
            ->where('is_permanently_deleted',0);
        })
        ->with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name',
        'assessments:id,user_id'
        ])
        ->when($business_id, fn($query) => $query->where('business_id', $business_id))
        ->get();
        
    }

    public static function getBusinessCandidate()
    {

        $count = self::where('business_id', Helpers::getUser()['id'])->whereHas('assessments')->count();

        $randomRecord = null;

        if ($count > 0) {

            $randomOffset = mt_rand(0, $count - 1);

            $randomRecord = self::where('business_id', Helpers::getUser()['id'])
                ->whereHas('assessments')
                ->with([
                    'users',
                    'assessments' => function ($query) {
                        $query->where('page', 0);
                    }
                ])
                ->skip($randomOffset)
                ->take(1)
                ->first();
        }

        return $randomRecord;

    }


    public static function CandidatetoMember($userid){

       return  User::where('id',$userid)->update([
            'is_admin'=>6
        ]);
    }

    public static function DeletedCandidate($userid){

       return  User::where('id',$userid)->update([
            'is_permanently_deleted'=>1
        ]);
    }

    
    public static function ArchivedCandidate($userid){

       return  User::where('id',$userid)->update([
            'future_consideration'=>Admin::IN_FUTURE
        ]);
    }



    public static function AllArchivedCandidates($business_id){
     
        return self::whereHas('users',function($query){
            $query->where('is_admin',Admin::IS_CUSTOMER)->where('future_consideration',Admin::IN_FUTURE);
        })
        ->with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name'])
        ->when($business_id, fn($query) => $query->where('business_id', $business_id))
        ->get();
       
    }


    public static function AlldeletedCandidates($business_id){
     
        return self::whereHas('users',function($query){
            $query->where('is_admin',Admin::IS_CUSTOMER)->where('is_permanently_deleted',1);
        })
        ->with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name'])
        ->when($business_id, fn($query) => $query->where('business_id', $business_id))
        ->get();
       
    }
}
