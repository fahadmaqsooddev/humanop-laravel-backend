<div wire:ignore.self class="modal fade" id="createSubQuestionModal{{ $question['id'] }}" tabindex="-1" role="dialog"
     aria-labelledby="createSubQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <form wire:submit.prevent="createSubQuestion">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Question</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                @include('layouts.message')
                                <div class="form-group mt-4">
                                    <input style="background-color: #0f1534;" class="form-control text-white"
                                           type="text" wire:model.defer="sub_question">
                                </div>
                                <label class="form-label fs-4 text-white">Answers</label>
                                @foreach($question['answers'] as $index => $answer)
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               type="text" wire:model.defer="sub_answer.{{ $index }}"
                                               placeholder="answer">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                            Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
