<div wire:ignore.self class="modal fade" id="updateQuestionModal{{ $question['id'] }}" tabindex="-1" role="dialog"
     aria-labelledby="updateQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                <form wire:submit.prevent="updateQuestion">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Question</label>
                                <button type="button" class="close modal-close-btn"  data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                @include('layouts.message')
                                <div class="form-group mt-4">
                                    <input style="background-color: #0f1534;" class="form-control text-white" type="text" wire:model.defer="question.question">
                                </div>

                                <label class="form-label fs-4 text-white">Answers</label>
                                @foreach($answers as $index => $answer)
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;" class="form-control text-white" type="text" wire:model.defer="answers.{{ $index }}.answer" placeholder="answer">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update Question</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
