<?php

namespace App\Http\Livewire\Admin\UserFeedback;

use App\Helpers\Points\PointHelper;
use Livewire\Component;
use Stripe\Checkout\Session;

class Feedback extends Component
{
    public $feedbacks;

    public function getFeedback()
    {
        $this->feedbacks = \App\Models\Client\Feedback\Feedback::userFeedbacks();

    }

    public function approveFeedback($id)
    {

        $feedback = \App\Models\Client\Feedback\Feedback::getSingleFeedback($id);

        if ($feedback['user'])
        {
            \App\Models\Client\Feedback\Feedback::approveUserFeedBack($id);

            PointHelper::addPointsOnFeedbackSubmission($feedback['user']);

            session()->flash('success', 'feedback approved successfully');

        }


    }

    public function render()
    {

        $this->getFeedback();

        return view('livewire.admin.user-feedback.feedback');
    }
}
