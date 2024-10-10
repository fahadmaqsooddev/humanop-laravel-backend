<div>
    <div wire:ignore.self class="modal fade" id="updateQuestionModal{{ $question['id'] }}" tabindex="-1" role="dialog"
         aria-labelledby="updateQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="border-radius: 9px">
                    <form wire:submit.prevent="updateQuestion">
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
                                               type="text" wire:model.defer="question.question">
                                    </div>
                                    <label class="form-label fs-4 text-white">Gender</label>
                                    <div class="form-group">
                                        <select style="background-color: #0f1535" class="form-control"
                                                wire:model.defer="question.gender">
                                            <option value="0">Male</option>
                                            <option value="1">Female</option>
                                            <option value="2">Male & Female</option>
                                        </select>
                                    </div>
                                    <label class="form-label fs-4 text-white">Answers</label>
                                    @foreach($question['answers'] as $index => $answer)
                                        <div class="form-group">
                                            <input style="background-color: #0f1534;" class="form-control text-white"
                                                   type="text" wire:model.defer="answers.{{ $index }}.answer"
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

    {{-- Sub Question Add Model --}}
    <div wire:ignore.self class="modal fade" id="createSubQuestionModal{{ $question['id'] }}" tabindex="-1"
         role="dialog"
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

{{--update subquestions--}}
    <div wire:ignore.self class="modal fade" id="updateSubQuestionModal{{ $question['id'] }}" tabindex="-1" role="dialog" aria-labelledby="updateSubQuestionModalLabel{{ $question['id'] }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Question</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                @foreach($subQuestions as $subIndex => $subQuestion)
                                    @if(session('success'.$subQuestion['id']))
                                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('success'.$subQuestion['id']) }}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        @elseif(session('error'.$subQuestion['id']))
                                            <div class="m-3  alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                        <span class="alert-text text-white">
                                            {{session('error'.$subQuestion['id']) }}</span>
                                                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                    <i class="fa fa-close" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        @endif
                                    <form wire:submit.prevent="updateSubQuestion({{ $subQuestion['id'] }})">
                                        <div class="form-group mt-4">
                                            <input wire:model.defer="subQuestions.{{ $subIndex }}.question" style="background-color: #0f1534;" class="form-control text-white" type="text">
                                        </div>
                                        <label class="form-label fs-4 text-white">Answers</label>
                                        @foreach($subQuestion['answers'] as $answerIndex => $subAnswer)
                                            <div class="form-group">
                                                <input wire:model.defer="subQuestions.{{ $subIndex }}.answers.{{ $answerIndex }}.answer" style="background-color: #0f1534;" class="form-control text-white" type="text" placeholder="Answer">
                                            </div>
                                        @endforeach
                                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mb-0" >Update</button>
                                        <br>
                                        <br>
                                        <hr>
                                    </form>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
