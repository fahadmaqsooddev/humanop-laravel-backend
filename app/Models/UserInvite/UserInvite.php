<?php

namespace App\Models\UserInvite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserInvite extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getSingleInvite($email = null)
    {
        return self::where('email',$email)->first();
    }

    public static function getInviteLink($link = null)
    {
        return self::where('link',$link)->first();
    }

    public static function getAllInviteLinks()
    {
        return self::orderBy('id', 'desc')->get();
    }

    public static function sendInvite($email = null)
    {
        $invite = self::getSingleInvite($email);

        if (empty($invite))
        {
            $link = Str::random(16);

            return self::create([
                'email' => $email,
                'link' => $link
            ]);
        }
    }
}
