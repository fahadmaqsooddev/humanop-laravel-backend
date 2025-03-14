<?php

namespace App\Models\B2B;

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

    public static function getSingleInvite($inviteId)
    {
        
        return self::where('company_id', Helpers::getUser()['id'])
        ->where('invite_link_id',$inviteId)
        ->first();
    }

    public static function createUserInvite($linkId = null)
    {
        return self::create([
            'company_id' => Helpers::getUser()['id'],
            'invite_link_id' => $linkId,
        ]);
    }

    public static function allCandidateInvites()
    {
        return self::where('company_id', Helpers::getUser()['id'])->with(['inviteLinks', 'user'])->get();
    }
}
