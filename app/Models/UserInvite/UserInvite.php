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
        return self::where('email', $email)->first();
    }

    public static function getInviteLink($link = null)
    {
        return self::where('link', $link)->first();
    }

    public static function getAllInviteLinks($per_page = 10, $email = null)
    {
        return self::when($email, function ($query, $email){

            $query->where('email', 'LIKE', "$email%");

        })->orderBy('id', 'desc')->paginate($per_page);
    }

    public static function sendInvite($email = null, $file = null)
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
                ]);
            }
        }
    }

    public static function deleteInvite($userEmail = null)
    {

        $email = Helpers::getWebUser()->email ?? Helpers::getUser()->email;

        if (!empty($userEmail))
        {
            return self::where('email', $userEmail)->delete();

        }

        return self::where('email', $email)->delete();

    }
}
