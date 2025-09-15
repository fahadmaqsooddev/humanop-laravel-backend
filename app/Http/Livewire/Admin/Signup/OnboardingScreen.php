<?php

namespace App\Http\Livewire\Admin\Signup;

use App\Models\Admin\Signup\SignupScreen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OnboardingScreen extends Component
{

    public $screenId, $title, $description;

    public function allScreens()
    {
        return SignupScreen::allScreens();
    }

    public function editOnboardingScreenModal($screen_id, $screenName, $screenText)
    {
        $this->screenId = $screen_id;
        $this->title = $screenName;
        $this->description = $screenText;

        // Send description to JS so Summernote can display it
        $this->dispatchBrowserEvent('loadDescription', [
            'description' => $screenText,
        ]);
    }

    public function updateForm(Request $request)
    {
        DB::beginTransaction();

        try {

            SignupScreen::updateOnboarding($this->screenId, $this->title, $this->description);

            session()->flash('success', 'Onboarding Screen Updated successfully.');

            $this->render();

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }


    public function render()
    {

        $onboardingScreen = $this->allScreens();

        return view('livewire.admin.signup.onboarding-screen',['onboardingScreens' => $onboardingScreen]);
    }
}
