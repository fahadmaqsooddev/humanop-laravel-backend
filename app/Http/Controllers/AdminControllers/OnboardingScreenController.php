<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\Signup\SignupScreen;
use App\Models\NetworkTutorial\NetworkTutorial;
use Illuminate\Http\Request;

class OnboardingScreenController extends Controller
{

    protected $onboarding = null;

    public function __construct(SignupScreen $onboarding)
    {
        $this->onboarding = $onboarding;
    }

    public function onboardingScreens()
    {
        try {

            return view('admin-dashboards.onboarding-screen.index');

        } catch (\Exception $exception) {

            return redirect()->back()->with('error', $exception->getMessage());

        }
    }

}
