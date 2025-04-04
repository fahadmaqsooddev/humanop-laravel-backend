<?php

namespace App\Models\UserInvite;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Enums\Admin\Admin;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return self::where('email', $email)->first();
    }

    public static function getInviteLink($link = null)
    {
        return self::where('link', $link)->first();
    }

    public static function getAllInviteLinks($per_page = 10, $email = null,$role=null)
    {
        return self::when($email, function ($query, $email){

            $query->where('email', 'LIKE', "$email%");

        })->orderBy('created_at', 'desc')->paginate($per_page);


    }

    public static function sendInvite($email = null, $file = null,$role=Admin::CLIENT_INVITE_ROLE,$members_limit=null)
    {

        if (!empty($file)) {

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {

                while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                    $csvEmail = $data[0];

                    $invite = self::getSingleInvite($csvEmail);

                    if (empty($invite)) {

                        $link = Str::random(16);

                        self::create([
                            'email' => $csvEmail,
                            'link' => $link,
                            'role'=>$role,
                            'members_limit'=>$members_limit,
                            'total_member_limit'=>$members_limit,
                        ]);

                    }

                }

                fclose($handle);
            }

        }

        if (!empty($email)) {

            $invite = self::getSingleInvite($email);

            if (empty($invite)) {

                $link = Str::random(16);

                return self::create([
                    'email' => $email,
                    'link' => $link,
                    'role'=>$role,
                    'members_limit'=>$members_limit,
                    'total_member_limit'=>$members_limit,
                ]);
            }
        }
    }

    public static function deleteInvite($userEmail = null, $id = null)
    {

        $email = Helpers::getWebUser()->email ?? Helpers::getUser()->email;

        if (!empty($userEmail) || !empty($id))
        {
            return self::where('email', $userEmail)->orwhere('id', $id)->delete();

        }

        return self::where('email', $email)->delete();

    }

    public static function getInviteLinkUsingEmail($email = null)
    {
        return self::where('email', $email)->first();
    }

    public static function createInvite($email = null, $role=null)
    {
        // dd($role);
        $link = Str::random(16);

        return self::create([
            'email' => $email,
            'link' => $link,
            'role'=>$role
        ]);
    }




    public static function sendInviteTime($id=null){
       
        $invite = self::find($id);
        if ($invite && empty($invite->send_invite_time)) { 
            $invite->update([
                'send_invite_time' => Carbon::now()
            ]);
        }
       
    }
}
