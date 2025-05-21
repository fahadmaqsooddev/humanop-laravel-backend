<?php

namespace App\Models\Client\Feedback;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//use App\Models\Upload\Upload;

class Feedback extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {

        return Helpers::getImage($this->image_id, null);

    }

    // relation

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    // query
    public static function storeClientFeedback($data = null)
    {

        return self::create($data);
    }

    public static function getSingleFeedback($feedbackId = null)
    {
        return self::whereId($feedbackId)->where('approve', 0)->whereHas('user')->with('user')->first();

    }

    public static function userFeedbacks($paginate = null, $name = null)
    {

        $query = self::whereHas('user')->with('user')->orderBy('created_at', 'desc');

        if (!empty($name)) {

            $query->whereHas('user', function ($q) use ($name) {

                $q->where('first_name', 'LIKE', "%$name%")
                    ->orWhere('last_name', 'LIKE', "%$name%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$name%"]);

            });

        }

        return $query->paginate($paginate);

    }

    public static function approveUserFeedBack($feedbackId = null)
    {
        return self::whereId($feedbackId)->update(['approve' => 1]);
    }

    public static function approvedUserFeedBack()
    {
        return self::where('approve', 1)->whereHas('user')->with('user')->orderBy('created_at', 'desc')->get();
    }

}
