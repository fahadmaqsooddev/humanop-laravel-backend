<?php

namespace App\Http\Livewire\V4\Admin\DailySyncQuestions;

use App\Models\v4\Admin\DailySync\DailySyncQuestion;
use Livewire\Component;

class DailySyncQuestions extends Component
{
    public array $questions = [''];

    public ?string $fullQuestionText = null;

    /** Edit form */
    public ?int $editQuestionId = null;
    public string $editQuestionText = '';
    public bool $editIsActive = true;

    protected const QUESTION_TRUNCATE_LENGTH = 80;

    protected $listeners = ['deleteDailySyncQuestion' => 'deleteQuestion'];

    public function addQuestion(): void
    {
        $this->questions[] = '';
    }

    public function removeQuestion(): void
    {

        if (count($this->questions) > 1) {

            array_pop($this->questions);

        }

    }

    public function submitForm(): void
    {

        $this->validate([
            'questions' => ['required', 'array', 'min:1'],
            'questions.*' => ['required', 'string', 'max:65535'],
        ], [
            'questions.*.required' => 'Each question field is required.',
        ]);

        $created = 0;

        foreach ($this->questions as $questionText) {

            $question = trim($questionText);

            if ($question !== '') {

                DailySyncQuestion::createQuestion($question);

                $created++;

            }

        }

        $this->questions = [''];

        $this->dispatchBrowserEvent('close-add-daily-sync-question-modal');

        session()->flash('message', $created > 0 ? "{$created} question(s) created successfully." : 'No questions to create.');

    }


    public function openAddModal(): void
    {
        $this->questions = [''];
        $this->resetValidation();
    }

    public function showFullQuestion(int $id): void
    {
        $question = DailySyncQuestion::find($id);
        $this->fullQuestionText = $question ? $question->question_text : '';
        $this->dispatchBrowserEvent('show-view-question-modal');
    }

    public function openEditModal(int $id): void
    {
        $question = DailySyncQuestion::find($id);
        if ($question) {
            $this->editQuestionId = $question->id;
            $this->editQuestionText = $question->question_text;
            $this->editIsActive = (bool) $question->is_active;
            $this->resetValidation();
            $this->dispatchBrowserEvent('show-edit-daily-sync-question-modal');
        }
    }

    public function submitEditForm(): void
    {
        $this->validate([
            'editQuestionText' => ['required', 'string', 'max:65535'],
            'editIsActive' => ['boolean'],
        ], [
            'editQuestionText.required' => 'Question is required.',
        ]);

        $question = DailySyncQuestion::find($this->editQuestionId);
        if ($question) {
            $question->update([
                'question_text' => trim($this->editQuestionText),
                'is_active' => $this->editIsActive,
            ]);
            session()->flash('message', 'Question updated successfully.');
        }

        $this->editQuestionId = null;
        $this->editQuestionText = '';
        $this->editIsActive = true;
        $this->dispatchBrowserEvent('close-edit-daily-sync-question-modal');
    }

    public function deleteQuestion(int $id): void
    {
        $question = DailySyncQuestion::find($id);
        if ($question) {
            $question->delete();
            session()->flash('message', 'Question deleted successfully.');
        }
    }

    public function render()
    {
        $dailySyncQuestions = DailySyncQuestion::getQuestions();

        return view('livewire.v4.admin.daily-sync-questions.daily-sync-questions', [
            'dailySyncQuestions' => $dailySyncQuestions,
            'questionTruncateLength' => self::QUESTION_TRUNCATE_LENGTH,
        ]);
    }

}
