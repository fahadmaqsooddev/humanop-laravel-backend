<div class="table-responsive table-orange-color">

    <div class="card-header table-header-text d-flex justify-content-between">
        <div class="col-8">
            <h5 class="mb-0 table-text-color">All Questions</h5>
        </div>
        <div class="col-4">
            <div class="input-group ms-md-4 pe-md-4">
                <select class="form-control table-orange-color search-bar custom-text-dark" name="age"
                        wire:model.debounce="gender">
                    <option value="">Select Gender</option>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Male & Female</option>
                </select>
            </div>
        </div>
    </div>

    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr class="table-text-color">
            <th>Questions</th>
            <th></th>
            <th>Gender</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $q)
            <tr class="table-text-color">
                <td class="text-md font-weight-normal px-4">
                    <h6 class="table-text-color">{{ $q->question }}</h6>
                    <div>
                        @foreach($q->answers as $answer)

                            <div class="d-flex">
                                <p>{{ $answer->answer }}</p>


                                @foreach($answer->answerCodes as $code)
                                    <p class="px-2" style="color: #f2661c">{{ $code->code }} + {{ $code->number }}</p>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </td>
                <td class="text-md font-weight-normal">
                    <div>
                    <button type="button" data-bs-toggle="modal"
                            data-bs-target="#createSubQuestionModal{{ $q->id }}"
                            class="btn btn-sm updateBtn mt-2 mb-0">Add
                    </button>
                    </div>
                    @if($q->subQuestions && count($q->subQuestions) > 0)
                        <div>
                            <button data-bs-toggle="modal"
                                    data-bs-target="#updateSubQuestionModal{{ $q->id }}"
                                    class="btn btn-sm updateBtn mt-2 mb-0" style="padding-left:16px;padding-right: 28px">
                                ({{ count($q->subQuestions) }}) View
                            </button>
                        </div>
                    @endif
                </td>
                <td class="text-md font-weight-normal">
                    <p class="mt-2">{{ $q->gender === '2' ? 'Male & Female' : ($q->gender === '1' ? 'Female' : ($q->gender === '0' ? 'Male' : '')) }}</p>
                </td>
                <td class="text-md font-weight-normal px-4">
                    <button type="button" data-bs-toggle="modal"
                            data-bs-target="#updateQuestionModal{{ $q->id }}"
                            class="btn btn-sm updateBtn float-end mt-2 mb-0">Edit
                    </button>
                </td>
            </tr>

            @livewire('admin.question.question-update-form', ['subQuestions' => $q->subQuestions->toArray(),'question' => $q->toArray(), 'answers' =>
            $q->answers->toArray()], key($q->id))

{{--            @livewire('admin.question.sub-question-create-form', ['question' => $q->toArray(), 'answers' =>--}}
{{--            $q->answers->toArray()], key($q->id))--}}
        @endforeach
        </tbody>
    </table>

    {{ $questions->links() }}

</div>

