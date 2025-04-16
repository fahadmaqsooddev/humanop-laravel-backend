<?php

namespace App\Http\Livewire\Admin\UserFeedback;

use App\Helpers\Points\PointHelper;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\Checkout\Session;

class Feedback extends Component
{
    use WithPagination;
    protected $feedbacks;
public $name='';
        public $approved_feedbacks;

public  $perPage=10;
    protected $paginationTheme = 'bootstrap';
    public function getFeedback($name)
    {
        $this->feedbacks = \App\Models\Client\Feedback\Feedback::userFeedbacks($this->perPage,$name);
        $this->approved_feedbacks = \App\Models\Client\Feedback\Feedback::approvedUserFeedBack();
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

        $this->getFeedback($this->name);

        return view('livewire.admin.user-feedback.feedback',[
            'feedbacks'=>$this->feedbacks,
        ]);
    }
}
