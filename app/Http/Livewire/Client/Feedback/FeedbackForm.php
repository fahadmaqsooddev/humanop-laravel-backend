<?php

namespace App\Http\Livewire\Client\Feedback;

use App\Models\Client\Feedback\Feedback;
use Livewire\Component;

class FeedbackForm extends Component
{
    public $comment;

    public function submitForm()
    {
        Feedback::create(['comment' => $this->comment]);

        session()->flash('success', 'Thank you for submitting your feedback! We will credit you a point after we verify your feedback as a token of our appreciation!');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.client.feedback.feedback-form');
    }
}
