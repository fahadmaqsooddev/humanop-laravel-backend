<?php

namespace App\Http\Livewire\Client\Feedback;

use App\Helpers\Helpers;
use App\Models\Client\Feedback\Feedback;
use Livewire\Component;

class FeedbackForm extends Component
{
    public $comment;
    protected $rules = [
        'comment' => 'required|max:1000',
    ];

    protected $messages = [
        'comment.required' => 'Please Fill out Feedback Section',
        'comment.max' => 'Feedback must not exceed 1000 character Limit',
    ];

    public function submitForm()
    {
        $validatedData = $this->validate();
        Feedback::create(['comment' => $validatedData['comment'], 'user_id' => Helpers::getWebUser()['id']]);

        session()->flash('success', 'Thank you for submitting your feedback! We will credit you a point after we verify your feedback as a token of our appreciation!');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.client.feedback.feedback-form');
    }
}
