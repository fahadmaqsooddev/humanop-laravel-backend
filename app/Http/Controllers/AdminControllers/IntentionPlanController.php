<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\IntentionPlan\IntentionOption;
use Illuminate\Http\Request;

class IntentionPlanController extends Controller
{
    protected $coupon = null;

    public function __construct(IntentionOption $option)
    {
        $this->option = $option;
    }

    public function allIntentionPlan()
    {
        try {

            $options = IntentionOption::getOptions();

            return view('admin-dashboards.intention-plan.index', compact('options'));

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }
}
