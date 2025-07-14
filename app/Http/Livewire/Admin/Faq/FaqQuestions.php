<?php

namespace App\Http\Livewire\Admin\Faq;

use App\Models\Admin\Faq\FaqModel;
use Livewire\Component;

class FaqQuestions extends Component
{

    public $question, $answer, $allQuestions, $question_id;

    protected $listeners = ['deleteQuestion', 'refreshPage' => '$refresh'];

    protected $rules = [
        'question' => 'required',
        'answer' => 'required',
    ];

    protected $messages = [
        'question.required' => 'The Question field is required.',
        'answer.required' => 'The Answer field is required.',

    ];


    public function submitForm()
    {

        try {

            $this->validate();

            FaqModel::storeFaq($this->question, $this->answer);
            session()->flash('success', 'Faq Stored successfully.');

            $this->resetForm();


            $this->emit('refreshPage');


        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());

        }
    }

    public function updateFaqModal($id)
    {
        $faqQuestions = FaqModel::findQuestion($id);
        $this->question_id = $faqQuestions->id;

        $this->question = $faqQuestions->question;
        $this->answer = $faqQuestions->answer;

    }

    public function updateFaqPlan()
    {

        try {
            $this->validate([
                'question' => 'required|string|max:255',
                'answer' => 'required|string',
                'question_id' => 'required|integer',
            ]);

            FaqModel::updateQuestions($this->question_id, $this->question, $this->answer);

            session()->flash('success', 'Faq Update successfully.');

            $this->resetForm();;

            $this->emit('refreshPage');


        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
        }
    }

    public function deleteQuestion($id)
    {
        FaqModel::deleteQuestion($id);
        session()->flash('success', 'Faq Deleted successfully.');

        $this->emit('refreshPage');
    }

    public function resetForm()
    {

        $this->reset('question', 'answer', 'question_id');

    }

    public function render()
    {

        $this->allQuestions = FaqModel::allFaqsQuestions();


        return view('livewire.admin.faq.faq-questions');
    }
}
