<?php

namespace App\Models\B2B;

use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use App\Models\Assessment;
use App\Models\UserInvite\UserInvite;
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

  

    public function companies()
    {
        return $this->belongsTo(User::class, 'business_id', 'id');
    }

    public function assessments()
    {
        return $this->hasOne(Assessment::class, 'user_id', 'candidate_id')->latest();
    }

    public static function checkBusinessCandidate($businessId = null, $candidateId = null)
    {
        return self::where('business_id', $businessId)->where('candidate_id', $candidateId)->exists();
    }

    public static function registerCandidate($businessId = null, $candidateId = null, $role = null, $sharedData = null)
    {

        $checkData = self::where('business_id', $businessId)->where('candidate_id', $candidateId)->first();

        if (empty($checkData)) {
            return self::create([

                'business_id' => $businessId,
                'candidate_id' => $candidateId,
                'role' => $role == '0' ? Admin::IS_TEAM_MEMBER : Admin::IS_CANDIDATE,
                'share_data' => $sharedData,
            ]);
        }
    }

    public static function allBusinessMembers($business_id = null, $search_name = null)
    {
        // return self::with([
        //     'users' => function ($query) {
        //         $query->select('id', 'first_name', 'last_name', 'email', 'gender', 'last_login', 'timezone', 'phone', 'date_of_birth', 'company_name', 'created_at')
        //             ->with(['invites' => function ($inviteQuery) {
        //                 $inviteQuery->select('id', 'email', 'send_invite_time'); // Add required fields from user_invites
        //             }]);
        //     },
        //     'assessments:id,user_id'
        // ])


        return self::with([
            'users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name',
            'assessments:id,user_id'
        ])
            ->when($search_name, function ($query) use ($search_name) {
                $query->whereHas('users', function ($q) use ($search_name) {
                    $q->where('first_name', 'LIKE', "%{$search_name}%")
                        ->orWhere('last_name', 'LIKE', "%{$search_name}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search_name}%"]);
                });
            })
            ->when($business_id, function ($query) use ($business_id) {
                $query->where('business_id', $business_id)
                    ->where('role', Admin::IS_TEAM_MEMBER)
                    ->where('future_consideration', Admin::NOT_IN_FUTURE)
                    ->where('is_permanently_deleted', 0);
            })
            ->get();
    }

    public static function allBusinessCandidates($business_id = null)
    {
        $data= self::with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name,created_at',
            'assessments:id,user_id'
        ])
            ->when($business_id, function ($query, $business_id){
                $query->where('business_id', $business_id)
                    ->where('is_permanently_deleted', 0)
                    ->where('role', Admin::IS_CANDIDATE)
                    ->where('future_consideration', Admin::NOT_IN_FUTURE);
            })
            ->get();

        // $data = self::with([
        //     'users' => function ($query) {
        //         $query->select('id', 'first_name', 'last_name', 'email', 'gender', 'last_login', 'timezone', 'phone', 'date_of_birth', 'company_name', 'created_at')
        //             ->with(['invites' => function ($inviteQuery) {
        //                 $inviteQuery->select('id', 'email', 'send_invite_time'); // Add required fields from user_invites
        //             }]);
        //     },
        //     'assessments:id,user_id'
        // ])
        // ->when($business_id, function ($query, $business_id) {
        //     $query->where('business_id', $business_id)
        //         ->where('is_permanently_deleted', 0)
        //         ->where('role', Admin::IS_CANDIDATE)
        //         ->where('future_consideration', Admin::NOT_IN_FUTURE);
        // })
        // ->get();
    
            
        return $data;

    }

    public static function getBusinessCandidate()
    {

        $baseQuery = self::where('business_id', Helpers::getUser()['id'])->where('share_data', Admin::SHARED_DATA)->where('role',Admin::IS_TEAM_MEMBER)->whereHas('assessments');

        $count = $baseQuery->count();

        if ($count === 0) {
            return null;
        }

        $randomOffset = mt_rand(0, $count - 1);

        return $baseQuery->with([
            'users',
            'assessments' => function ($query) {
                $query->where('page', 0);
            }
        ])
            ->skip($randomOffset)
            ->take(1)
            ->first();
    }

    public static function getCandidateBusiness()
    {

        return self::where('candidate_id', Helpers::getUser()['id'])->with('companies', function ($query) {
            $query->select('id', 'company_name');
        })->get();

    }

    public static function CandidatetoMember($userid)
    {

        return User::where('id', $userid)->update(['is_admin' => Admin::IS_B2U]);
    }

    public static function DeletedCandidate($userid)
    {

        return self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userid)->update([
            'is_permanently_deleted' => 1
        ]);
    }


    public static function ArchivedCandidate($userid)
    {

        return self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userid)->update([
            'future_consideration' => Admin::IN_FUTURE
        ]);
    }


    public static function AllArchivedCandidates($business_id)
    {

        return self::with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name'])
            ->when($business_id, fn($query) => $query->where('business_id', $business_id)->where('is_permanently_deleted', 0)
                ->where('future_consideration', 1))
            ->get();

    }


    public static function AlldeletedCandidates($business_id)
    {

        return self::with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name'])
            ->when($business_id, fn($query) => $query->where('business_id', $business_id)->where('is_permanently_deleted', 1))
            ->get();

    }


    public static function getInfo($userid)
    {
        return self::where('business_id', Helpers::getUser()['id'])
            ->where('candidate_id', $userid)
            ->where('is_permanently_deleted', 1)
            ->first();
    }

    public static function checkconsideration($userid)
    {
        return self::where('business_id', Helpers::getUser()['id'])
            ->where('candidate_id', $userid)
            ->where('future_consideration', Admin::IN_FUTURE)
            ->first();
    }

    public static function checkRole($userid)
    {
        return self::where('business_id', Helpers::getUser()['id'])
            ->where('candidate_id', $userid)
            ->where('role', Admin::IS_TEAM_MEMBER)
            ->first();
    }

    public static function changeRole($userid)
    {
        $data = self::where('business_id', Helpers::getUser()['id'])
            ->where('candidate_id', $userid)->update([
                'role' => Admin::IS_TEAM_MEMBER
            ]);
        UserInvite::where('email', Helpers::getUser()['email'])->decrement('members_limit', 1);
        return $data;
    }

    public static function checkCandidateCompany($candidateId = null)
    {
        return self::where('candidate_id', $candidateId)->where('share_data', Admin::NOT_SHARED_DATA)->first();
    }

    public static function ShareDataWithBusiness($businessId = null, $candidateId = null)
    {

        $checkBusinessCandidate = self::where('business_id', $businessId)->where('candidate_id', $candidateId)->first();

        if (!empty($checkBusinessCandidate)) {

            if ($checkBusinessCandidate['share_data'] == 0) {
                return $checkBusinessCandidate->update(['share_data' => 1]);
            } else {
                return $checkBusinessCandidate->update(['share_data' => 0]);
            }

            return $checkBusinessCandidate;

        }

    }

    public static function allCompaniesInfo()
    {
        return self::all();
    }


    public static function newchangeRole($userid)
    {

        $data = self::where('business_id', Helpers::getUser()['id'])
            ->where('candidate_id', $userid)->update([
                'role' => Admin::IS_CANDIDATE
            ]);

        UserInvite::where('email', Helpers::getUser()['email'])->increment('members_limit', 1);
        return $data;
    }


    public static function checkShare($userid)
    {
        $data= self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userid)->where('share_data', Admin::SHARED_DATA)->with('users')->first();
        
        if ($data && $data->users) {
            $data->users->gender = $data->users->gender == Admin::IS_MALE ? 'Male' : 'Female';
        }
        
        return $data;
    }


    public static function CheckLimit($email = null)
    {
        return UserInvite::where('email', $email)->select(['members_limit', 'total_member_limit'])->first();


    }


}
