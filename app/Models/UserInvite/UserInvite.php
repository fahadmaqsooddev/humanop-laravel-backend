<?php

namespace App\Models\UserInvite;

use App\Helpers\Helpers;
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

    public static function deleteInvite(){

        try {
            $email = Helpers::getWebUser()->id ?? Helpers::getUser()->id;
            self::where('email',$email)->delete();
            return response()->json(['data' => 'Successfully logged out'], 200);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }

    }
}
