<?php

namespace App\Models\B2B;

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

    public function businessUsers()
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
                'role' => $role == 1 ? Admin::IS_TEAM_MEMBER : Admin::IS_CANDIDATE,
                'share_data' => $sharedData,
            ]);

            return $data;
        }

        return $checkData;

    }

    public static function CandidatetoMember($userId)
    {

        return User::where('id', $userId)->update(['is_admin' => Admin::IS_B2U]);
    }



    public static function getInfo($userId)
    {
        return self::where('business_id', Helpers::getUser()['id'])
            ->where('candidate_id', $userId)
            ->where('is_permanently_deleted', 1)
            ->first();
    }


    public static function notShareDataWithBusiness($businessId = null, $candidateId = null)
    {

        $candidate = Helpers::getUser();

        $candidateName = $candidate['first_name'] . ' ' . $candidate['last_name'];


        event(new NotSharedDataWithBusiness($businessId, "[ $candidateName ] elected to  not share their data with your company"));

        Notification::createNotification('Consent Not Granted', " [ $candidateName ] elected to  not share their data with your company", '', $businessId, 0, Admin::B2B_NOT_SHARE_DATA_NOTIFICATION, Admin::B2B_NOTIFICATION);

    }

    public static function allCompaniesInfo()
    {
        return self::all();
    }



    public static function checkShareDataDetail($company = null, $candidateId = null)
    {

        return self::where('candidate_id', $candidateId ?? Helpers::getUser()['id'])
            ->whereHas('businessUsers', function ($query) use ($company) {
                $query->where('company_name', $company);
            })
            ->first();
    }

    public static function AllLoginUserCompanies($candidateId = null)
    {

        return self::where('candidate_id', $candidateId ?? Helpers::getUser()['id'])
            ->where('share_data', Admin::NOT_SHARED_DATA)
            ->where('is_permanently_deleted', 0)
            ->with('businessUsers')
            ->get();

    }


    public static function AllCompaniesCheckShareDataDetail($companies = [], $candidateId = null)
    {

        return self::where('candidate_id', $candidateId ?? Helpers::getUser()['id'])->whereHas('businessUsers', function ($query) use ($companies) {
            $query->whereIn('company_name', $companies);
        })
            ->get();
    }





    public static function checkFutureConsiderationShareData($candidateId = null)
    {
        return self::with('businessUsers')->where('candidate_id', $candidateId)->where('future_consideration', 1)->where('role', Admin::IS_TEAM_MEMBER)->where('future_consideration_share_date', 0)->first();
    }






    public static function getMembersCount($businessId = null)
    {

        return self::where('business_id', $businessId)->where('role', Admin::IS_TEAM_MEMBER)->where('is_permanently_deleted', 0)->where('future_consideration', 0)->whereHas('users', function ($query) {

            $query->where('step', 3);

        })
            ->count();

    }

    public static function getCandidatesCount($businessId = null)
    {
        return self::where('business_id', $businessId)->where('role', Admin::IS_CANDIDATE)->where('is_permanently_deleted', 0)->where('future_consideration', 0)->whereHas('users', function ($query) {

            $query->where('step', 3);

        })

            ->count();

    }
}
