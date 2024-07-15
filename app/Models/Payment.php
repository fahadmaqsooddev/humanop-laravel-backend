<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function createPayment($couponId = null, $userId = null, $discount_ammount = null, $total_amount = null, $assessmentId = null)
    {
        return self::create([
            'user_id' => $userId,
            'coupon_id' => $couponId,
            'assessment_id' => $assessmentId,
            'discount_price' => $discount_ammount,
            'total_price' => $total_amount,
        ]);
    }
}
