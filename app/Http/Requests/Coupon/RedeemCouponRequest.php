<?php

namespace App\Http\Requests\Coupon;

use App\Domain\Billing\PlanRules;
use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\LifetimeCoupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
class RedeemCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'required|string',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $user = Helpers::getUser();

            if (!$this->code) {
                return;
            }

            $coupon = LifetimeCoupon::where('code', $this->code)->lockForUpdate()->first();

            if (!$coupon) {
                $validator->errors()->add('code', 'Invalid coupon.');
                return;
            }

            if ($coupon->is_redeemed) {
                $validator->errors()->add('code', 'Coupon already redeemed.');
                return;
            }

            // PREMIUM LIFETIME CHECK
            if (
                $coupon->type === PlanRules::PREMIUM_LIFETIME &&
                $user->plan === PlanRules::PREMIUM_LIFETIME
            ) {
                $validator->errors()->add(
                    'code',
                    'You already have a premium plan.'
                );
            }

            // BETA BREAKER CLUB CHECK
            if (
                $coupon->type === PlanRules::BETA_BREAKER_CLUB &&
                $user->beta_breaker_club == Admin::BETA_BREAKER_CLUB
            ) {
                $validator->errors()->add(
                    'code',
                    'You are already a member of the Beta Breaker Club.'
                );
            }
        });
    }
}
