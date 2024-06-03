@include('layouts.message')
<form wire:submit.prevent="updateQuestion" method="post">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <label class="form-label fs-4 text-white">Question</label>
                <div class="form-group">
                    <input
                        style="background-color: #0f1534;"
                        class="form-control text-white"
                        type="text" name="question"
                        wire:model="question.question"
                        placeholder="question">
                </div>
                <label class="form-label fs-4 text-white">Answers</label>
                @foreach($answers as $index => $answer)
                    <div class="form-group">
                        <input
                            style="background-color: #0f1534;"
                            class="form-control text-white"
                            type="text"
                            wire:model="answers.{{ $index }}.answer"
                            placeholder="answer">
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit"
                class="btn updateBtn btn-sm float-end mt-4 mb-0">
            Update Question
        </button>
    </div>
</form>
