<?php

namespace App\Models\UserInvite;

use App\Enums\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInviteLog extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        parent::__construct($attributes);
    }

    public static function checkB2CInvite($inviteId = null)
    {

        return self::where('invite_id', $inviteId)->where('role', Admin::CLIENT_INVITE_ROLE)->first();
    }

    public static function createInvite($invite)
    {
        return self::create([
            'invite_id' => $invite['id'],
            'role' => $invite['role'] ?? Admin::CLIENT_INVITE_ROLE,
        ]);

    }

    public static function deleteInvite($inviteId = null)
    {

        $getInvite = self::where('invite_id', $inviteId)->where('role', Admin::CLIENT_INVITE_ROLE)->first();

        $getInvite->delete();

        return true;

    }

}
