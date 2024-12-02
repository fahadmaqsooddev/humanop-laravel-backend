<?php

namespace App\Models\Client\Feedback;

use App\Helpers\Helpers;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // relation
    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    // query
    public static function storeClientFeedback($data = null)
    {

        self::create($data);
    }

    public static function getSingleFeedback($feedbackId = null)
    {
        return self::whereId($feedbackId)->where('approve', 0)->whereHas('user')->with('user')->first();

    }

    public static function userFeedbacks()
    {
        return self::where('approve', 0)->whereHas('user')->with('user')->orderBy('created_at', 'desc')->get();
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
