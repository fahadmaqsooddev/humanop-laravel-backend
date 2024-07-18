<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Admin\Coupon\Coupon;

class Payment extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coupons()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function assessments()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');
    }

    public static function createPayment($coupon = null, $userId = null, $discount_ammount = null, $total_amount = null, $assessmentId = null)
    {

        return self::create([
            'user_id' => $userId,
            'coupon_id' => $coupon ? $coupon['id'] : null,
            'assessment_id' => $assessmentId,
            'discount_price' => $discount_ammount,
            'total_price' => $total_amount,
        ]);
    }

    public static function getPaymentHistory()
    {
        $user_id = Auth::user()['id'];

        return self::where('user_id', $user_id)->with('coupons', 'assessments')->orderBy('created_at', 'DESC')->get();

    }
}
