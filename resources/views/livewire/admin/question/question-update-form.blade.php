<div>
    <div wire:ignore.self class="modal fade" id="updateQuestionModal{{ $question['id'] }}" tabindex="-1" role="dialog"
         aria-labelledby="updateQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="border-radius: 9px">
                                          @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Question</label>

                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <input  class="form-control input-form-style"
                                               type="text" wire:model.defer="question.question">
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">Gender</label>
                                    <div class="form-group">
                                        <select style="background-color: #eaf3ff" class="form-control input-form-style"
                                                wire:model.defer="question.gender">
                                            <option value="0">Male</option>
                                            <option value="1">Female</option>
                                            <option value="2">Male & Female</option>
                                        </select>
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">Answers</label>
                                    @foreach($question['answers'] as $index => $answer)
                                        <div class="form-group">
                                            <input  class="form-control input-form-style"
                                                   type="text" wire:model.defer="answers.{{ $index }}.answer"
                                                   placeholder="answer">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                           
                            <button type="button" wire:click="updateQuestion" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">
                                Update Question
                            </button>
                        </div>
                  
                </div>
            </div>
        </div>
    </div>

    {{-- Sub Question Add Model --}}
    <div wire:ignore.self class="modal fade" id="createSubQuestionModal{{ $question['id'] }}" tabindex="-1"
         role="dialog"
         aria-labelledby="createSubQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Add Question</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <input  class="form-control input-form-style"
                                               type="text" wire:model.defer="sub_question">
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">Answers</label>
                                    @foreach($question['answers'] as $index => $answer)
                                        <div class="form-group">
                                            <input  class="form-control input-form-style"
                                                   type="text" wire:model.defer="sub_answer.{{ $index }}"
                                                   placeholder="answer">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button type="button" wire:click="createSubQuestion" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">
                                Submit
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    {{--update subquestions--}}
    <div wire:ignore.self class="modal fade" id="updateSubQuestionModal{{ $question['id'] }}" tabindex="-1"
         role="dialog" aria-labelledby="updateSubQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4" style="color: #1b3a62">Question</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                @foreach($subQuestions as $subIndex => $subQuestion)
                                    @if(session('success'.$subQuestion['id']))
                                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert"
                                             role="alert">
                        <span class="alert-text text-white">
                            {{session('success'.$subQuestion['id']) }}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @elseif(session('error'.$subQuestion['id']))
                                        <div class="m-3  alert alert-danger alert-dismissible fade show" id="alert"
                                             role="alert">
                                        <span class="alert-text text-white">
                                            {{session('error'.$subQuestion['id']) }}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif
                                    
                                        <div class="form-group mt-4">
                                            <input wire:model.defer="subQuestions.{{ $subIndex }}.question"
                                                    class="form-control input-form-style" type="text">
                                        </div>
                                        <label class="form-label fs-4" style="color: #1b3a62">Answers</label>
                                        @foreach($subQuestion['answers'] as $answerIndex => $subAnswer)
                                            <div class="form-group">
                                                <input
                                                    wire:model.defer="subQuestions.{{ $subIndex }}.answers.{{ $answerIndex }}.answer"
                                                     class="form-control input-form-style" type="text"
                                                    placeholder="Answer">
                                            </div>
                                        @endforeach
                                        <button type="button" wire:click="updateSubQuestion({{ $subQuestion['id'] }})"
                                                class="btn updateBtn btn-sm float-end text-white mb-0">
                                            Update
                                        </button>
                                        <br>
                                        <br>
                                        <hr>
                                    
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


<script>
    if (!window.modalListenerAdded) {
        window.modalListenerAdded = true;

        window.addEventListener('close-modal', event => {
            let modalId = event.detail.id;

            setTimeout(() => {
                let modalEl = document.getElementById(modalId);
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }, 1000);
        });
    }
</script>


