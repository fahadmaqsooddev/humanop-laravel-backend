<?php

namespace App\Models\B2B;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\User;
use App\Models\UserInvite\UserInvite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCandidateInvite extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

//    Relationship
    public function inviteLinks()
    {
        return $this->belongsTo(UserInvite::class, 'invite_link_id', 'id');

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'company_id', 'id');

    }

    public static function getSingleInvite($inviteId = null)
    {

        return self::where('company_id', Helpers::getUser()['id'])->where('invite_link_id', $inviteId)->first();
    }

    public static function getInviteById($inviteId = null)
    {

        return self::where('invite_link_id', $inviteId)->latest()->first();
    }

    public static function createUserInvite($linkId = null, $role = null)
    {
        return self::create([
            'company_id' => Helpers::getUser()['id'],
            'invite_link_id' => $linkId,
            'role' => $role,
        ]);
    }

    public static function allCandidateInvites()
    {

        return self::where('company_id', Helpers::getUser()['id'])->where('role', Admin::IS_CANDIDATE)->whereHas('inviteLinks')->with([
            'inviteLinks',
            'user'
        ])
            ->orderBy('id', 'desc')
            ->get();

    }

    public static function allMemberInvites()
    {

        return self::where('company_id', Helpers::getUser()['id'])->where('role', Admin::IS_TEAM_MEMBER)->whereHas('inviteLinks')->with([
            'inviteLinks',
            'user'
        ])
            ->orderBy('id', 'desc')
            ->get();

    }


    public static function getMemberInvite($id = null)
    {
        return self::where('id', $id)->where('role', Admin::IS_TEAM_MEMBER)->first();
    }

    public static function deleteMemberInvite($id = null)
    {
        return self::where('id', $id)->delete();
    }

    public static function deleteInvite($inviteId = null)
    {
        return self::where('invite_link_id', $inviteId)->delete();
    }
}
