<?php

namespace App\Models\B2B;

use App\Http\Livewire\B2b\B2binvites\B2bInvite;
use App\Models\User;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use App\Models\Assessment;
use App\Models\UserInvite\UserInvite;
use Illuminate\Database\Eloquent\Model;
use App\Events\B2B\SharedDataWithBusiness;
use App\Events\B2B\NotSharedDataWithBusiness;
use App\Events\B2B\RequestAccessData;
use App\Models\Admin\Notification\Notification;
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

    public function busers()
    {
        return $this->belongsTo(User::class, 'business_id', 'id');
    }


    public function companies()
    {
        return $this->belongsTo(User::class, 'business_id', 'id');
    }

    public function assessments()
    {
        return $this->hasOne(Assessment::class, 'user_id', 'candidate_id')->where('page', 0)->latest();
    }

    public static function checkBusinessCandidate($companyId = null, $candidateId = null)
    {

        return self::where('business_id', $companyId)->where('candidate_id', $candidateId)->exists();
    }

    public static function allUser()
    {
        return self::all();
    }

    public static function registerCandidate($businessId = null, $candidateId = null, $role = null, $sharedData = null)
    {

        $checkData = self::where('business_id', $businessId)->where('candidate_id', $candidateId)->first();

        if (empty($checkData)) {
            $data = self::create([

                'business_id' => $businessId,
                'candidate_id' => $candidateId,
                'role' => $role == 1 ? 0 : 1,
                'share_data' => $sharedData,
            ]);
            return $data;
        }


        return $checkData;

//        $user = User::where('id', $candidateId)->first();
//        $getInvite = UserInvite::where('email', $user['email'])->first();
//        UserCandidateInvite::where('company_id', $businessId)->where('invite_link_id', $getInvite['id'])->delete();


    }

    public static function allBusinessMembers($business_id = null, $search_name = null)
    {

        $data = self::when($business_id, function ($query, $business_id) {
            $query->where('business_id', $business_id)
                ->where('is_permanently_deleted', 0)
                ->where('role', Admin::IS_TEAM_MEMBER)
                ->where('future_consideration', Admin::NOT_IN_FUTURE);
        })
            ->when($search_name, function ($query) use ($search_name) {
                $query->whereHas('users', function ($q) use ($search_name) {
                    $q->where('first_name', 'LIKE', "%{$search_name}%")
                        ->orWhere('last_name', 'LIKE', "%{$search_name}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search_name}%"]);
                });
            })
            ->with([
                'users' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'email', 'gender', 'last_login', 'timezone', 'phone', 'date_of_birth', 'company_name', 'step')
                        ->where('step', 3);
                },
                'assessments:id,user_id,page'
            ])
            ->orderBy('id', 'desc')
            ->get();

        return $data;
    }

    public static function allBusinessCandidates($business_id = null)
    {
        $data = self::when($business_id, function ($query, $business_id) {
            $query->where('business_id', $business_id)
                ->where('is_permanently_deleted', 0)
                ->where('role', Admin::IS_CANDIDATE)
                ->where('future_consideration', Admin::NOT_IN_FUTURE);
        })
            ->with([
                'users' => function ($query) {
                    $query->where('step', 3);
                },
                'assessments:id,user_id,page'
            ])
            ->orderBy('id', 'desc')
            ->get();

        return $data;
    }


    public static function getBusinessCandidate()
    {

        $baseQuery = self::where('business_id', Helpers::getUser()['id'])->where('share_data', Admin::SHARED_DATA)->where('role', Admin::IS_TEAM_MEMBER)
            ->whereHas('assessments', function ($query) {
                $query->whereNotNull('page')->where('page', 0);
            });

        $count = $baseQuery->count();

        if ($count === 0) {
            return null;
        }

        $randomOffset = mt_rand(0, $count - 1);

        return $baseQuery->with([
            'users',
            'assessments'
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

    public static function CandidatetoMember($userId)
    {

        return User::where('id', $userId)->update(['is_admin' => Admin::IS_B2U]);
    }

    public static function DeletedCandidate($userId)
    {


        $data = self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userId)->delete();
        $user = User::where('id', $userId)->first();
        $getInvite = UserInvite::where('email', $user['email'])->first();
        UserCandidateInvite::where('company_id', Helpers::getUser()['id'])->where('invite_link_id', $getInvite['id'])->delete();
        return $data;
    }


    public static function ArchivedCandidate($userId)
    {

        // return self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userid)->update([
        //     'future_consideration' => Admin::IN_FUTURE
        // ]);

        $data = self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userId)->update([
            'future_consideration' => Admin::IN_FUTURE
        ]);

        $user = User::where('id', $userId)->first();
        $getInvite = UserInvite::where('email', $user['email'])->first();
        UserCandidateInvite::where('company_id', Helpers::getUser()['id'])->where('invite_link_id', $getInvite['id'])->delete();

        return $data;
    }


    public static function AllArchivedCandidates($business_id, $role = null)
    {

        return self::with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name'])
            ->when($business_id, fn($query) => $query->where('business_id', $business_id)->where('is_permanently_deleted', 0)
                ->where('future_consideration', 1))
            ->where('role', !empty($role) ? Admin::IS_CANDIDATE : Admin::IS_TEAM_MEMBER)
            ->orderBy('id', 'desc')
            ->get();
    }

    // public static function AllArchivedMembers($business_id)
    // {

    //     return self::with(['users:id,first_name,last_name,email,gender,last_login,timezone,phone,date_of_birth,company_name'])
    //         ->when($business_id, fn($query) => $query->where('business_id', $business_id)->where('is_permanently_deleted', 0)
    //             ->where('future_consideration', 1))
    //         ->orderBy('id', 'desc')
    //         ->get();

    // }


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

    public static function shareDataWithBusiness($businessId = null, $candidateId = null)
    {
        $candidate = Helpers::getUser();

        $candidateName = $candidate['first_name'] . ' ' . $candidate['last_name'];

        $checkBusinessCandidate = self::where('business_id', $businessId)->where('candidate_id', $candidateId)->first();

        if ($checkBusinessCandidate) {

            $newShareStatus = $checkBusinessCandidate->share_data == 0 ? 1 : 0;

            $checkBusinessCandidate->update(['share_data' => $newShareStatus]);

            if ($checkBusinessCandidate['share_data'] == 1) {

                event(new SharedDataWithBusiness($businessId, "[ $candidateName ] elected to share their data with your company"));

                Notification::createNotification('Data Share Granted', "[ $candidateName ] elected to share their data with your company", '', $businessId, 0, Admin::B2B_SHARE_DATA_NOTIFICATION, Admin::B2B_NOTIFICATION);
            }

            return $checkBusinessCandidate;
        }

        return null;
    }

    public static function notShareDataWithBusiness($businessId = null, $candidateId = null)
    {

        $candidate = Helpers::getUser();

        $candidateName = $candidate['first_name'] . ' ' . $candidate['last_name'];

        //        $checkBusinessCandidate = self::where('business_id', $businessId)->where('candidate_id', $candidateId)->first();
        //
        //        if ($checkBusinessCandidate) {
        //
        //            $checkBusinessCandidate->update(['share_data' => 2]);

        event(new NotSharedDataWithBusiness($businessId, "[ $candidateName ] elected to  not share their data with your company"));

        Notification::createNotification('Consent Not Granted', " [ $candidateName ] elected to  not share their data with your company", '', $businessId, 0, Admin::B2B_NOT_SHARE_DATA_NOTIFICATION, Admin::B2B_NOTIFICATION);
        //        }
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
        $data = self::where('business_id', Helpers::getUser()['id'])->where('candidate_id', $userid)->with('users')->first();

        if ($data && $data->users) {
            $data->users->gender = $data->users->gender == Admin::IS_MALE ? 'Male' : 'Female';
        }

        return $data;
    }


    public static function checkB2BAdminShare($userId)
    {
        $data = self::where('business_id', $userId)->with('busers')->first();

        if ($data && $data->busers) {
            $data->busers->gender = $data->busers->gender == Admin::IS_MALE ? 'Male' : 'Female';
        }

        return $data;
    }


    public static function CheckLimit($email = null)
    {
        return UserInvite::where('email', $email)->select(['members_limit', 'total_member_limit'])->first();
    }

    public static function checkShareDataDetail($company = null, $candidateid = null)
    {

        return self::where('candidate_id', $candidateid ?? Helpers::getUser()['id'])
            ->whereHas('busers', function ($query) use ($company) {
                $query->where('company_name', $company);
            })
            ->first();
    }

    public static function AllLoginUserCompanies($candidateid = null)
    {

        return self::where('candidate_id', $candidateid ?? Helpers::getUser()['id'])
            ->where('share_data', Admin::NOT_SHARED_DATA)
            ->where('is_permanently_deleted', 0)
            ->with('busers')
            ->get();

    }


    public static function AllCompaniesCheckShareDataDetail($companies = [], $candidateId = null)
    {

        return self::where('candidate_id', $candidateId ?? Helpers::getUser()['id'])->whereHas('busers', function ($query) use ($companies) {
            $query->whereIn('company_name', $companies);
        })
            ->get();
    }

    public static function getCandidatesMembers($userid = null, $prefer = null)
    {
        return self::with('users')->where('business_id', $userid)->where('role', $prefer == 1 ? 0 : 1)->get();
    }

    public static function getMemberRecord($businessId = null, $candidateId = null)
    {
        return self::where('business_id', $businessId)
            ->where('candidate_id', $candidateId)
            ->where('role', Admin::IS_TEAM_MEMBER)
            ->first();
    }

    public static function requestAccess($id = null)
    {
        self::where('id', $id)->update([
            'request_access' => 1
        ]);

        $data = self::find($id);
        $companyName = Helpers::getUser()['company_name'];

        event(new RequestAccessData($data['business_id'], "[ $companyName ] Company Wanted To Access Your Data ", $data['candidate_id']));

        Notification::createNotification('Request Access Data', " [ $companyName ] Company Wanted To Access Your Data", '', $data['candidate_id'], 0, Admin::REQUEST_ACCESS_DATA_NOTIFICATION, Admin::B2C_NOTIFICATION);

        return $data;
    }


    public static function checkFutureConsiderationShareData($candidateId = null)
    {
        return self::with('busers')->where('candidate_id', $candidateId)->where('future_consideration', 1)->where('role', Admin::IS_TEAM_MEMBER)->where('future_consideration_share_date', 0)->first();
    }


    public static function getBusinessUsers($id = null, $prefer = null, $perpage = null)
    {

        $role = ($prefer == 1) ? '0' : '1';

        return self::where('business_id', $id)
            ->where('role', $role)
            ->where('is_permanently_deleted', 0)
            ->where('future_consideration', 0)
            ->whereHas('users', function ($query) {
                $query->where('step', 3);
            })
            ->paginate($perpage);
    }

    public static function deleteUserFromBuisness($businessId = null, $candidateId = null)
    {
        return self::where('business_id', $businessId)->where('candidate_id', $candidateId)->update([
            'is_permanently_deleted' => 1
        ]);
    }

    public static function futureConsiderationUserFromBuisness($businessId = null, $candidateId = null)
    {
        // dd($businessId,$candidateId);
        return self::where('business_id', $businessId)->where('candidate_id', $candidateId)->update([
            'future_consideration' => 1
        ]);
    }

    public static function deleteB2BAdmin($id = null)
    {
        self::where('business_id', $id)->delete();

        $organization = User::getSingleUser($id);

        if ($organization['is_admin'] == Admin::IS_B2B) {
            $organization->forceDelete();
        } else {

            $organization->update(['company_name' => null]);

        }

        return $organization;
    }

    public static function getMembersCount($businessId = null)
    {
        return self::where('business_id', $businessId)
            ->where('role', Admin::IS_TEAM_MEMBER)
            ->where('is_permanently_deleted', 0)
            ->where('future_consideration', 0)
            ->whereHas('users', function ($query) {
                $query->where('step', 3);
            })
            ->count();
    }

    public static function getCandidatesCount($businessId = null)
    {
        return self::where('business_id', $businessId)
            ->where('role', Admin::IS_CANDIDATE)
            ->where('is_permanently_deleted', 0)
            ->where('future_consideration', 0)
            ->whereHas('users', function ($query) {
                $query->where('step', 3);
            })
            ->count();
    }
}
