<?php

namespace App\Http\Livewire\Admin\HaiChat;

use App\Models\Admin\FineTuneContent\FineTuneContent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class FineTune extends Component
{

    use WithPagination;

    protected $listeners = ['deleteQuestionAnswer'];

    public $question, $answer, $perPage = 10, $updateQuestion, $updateAnswer, $updateId;

    public $rules = [
        'question' => 'required|max:10000',
        'answer' => 'required|max:10000',
    ];

    public $messages = [
        'question.required' => 'Question is required.',
        'question.max' => 'Question maximum characters length is 10000.',
        'answer.required' => 'Answer is required.',
        'answer.max' => 'Answer maximum characters length is 10000.',
    ];

    public function render()
    {

        $contents = FineTuneContent::allContent($this->perPage);

        return view('livewire.admin.hai-chat.fine-tune', compact('contents'));
    }

    public function addFineTuneContent(){

        $this->validate();

        FineTuneContent::addFineTuneContent($this->question, $this->answer);

        $this->reset();

        session()->flash('success', 'Done! Your content has been saved.');

        $this->emit('closeAlert');

        $this->emit('closeAddModal');

    }

    public function updateQuestionAnswer($id,$question, $answer){

        $this->updateId = $id;

        $this->updateQuestion = $question;

        $this->updateAnswer = $answer;

        $this->emit('openEditModal');
    }

    public function editFineTuneContent(){

        $this->validate([
            'updateQuestion' => 'required|max:10000',
            'updateAnswer' => 'required|max:10000',
        ], [
            'updateQuestion.required' => 'Question is required.',
            'updateQuestion.max' => 'Question maximum characters length is 10000.',
            'updateAnswer.required' => 'Answer is required.',
            'updateAnswer.max' => 'Answer maximum characters length is 10000.',
        ]);

        FineTuneContent::updateFineTunedContent($this->updateId, $this->updateQuestion, $this->updateAnswer);

        $this->emit('closeAlert');

        session()->flash('success', 'Done! Your content has been updated.');

        $this->emit('closeEditModal');
    }

    public function deleteQuestionAnswer($id){

        FineTuneContent::whereId($id)->delete();
    }

    public static function downloadQuestions(){

        $questions = FineTuneContent::retrieveAndUpdateQuestions();

        $fileName = 'fine-tuning-content-' . Carbon::now()->format('Y-m-d') . '.jsonl';

        $data = '';

        foreach ($questions as $question){

            $data .= json_encode(["messages" => [['role' => 'user', 'content' => $question['question']],
                    ['role' => 'assistant','content' => $question['answer']]]]) . "\n";

            $question->update(['is_fine_tuned' => 1]);

        }

        Storage::disk('local')->put($fileName, $data);

        return response()->download(storage_path("app/{$fileName}"))->deleteFileAfterSend(true);
    }
}
