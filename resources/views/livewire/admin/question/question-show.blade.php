<div class="table-responsive">
    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr>
            <th>Questions</th>
            <th>Gender</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $question)
            @include('layouts.message')
            <tr>
                <td class="text-sm font-weight-normal">
                    <h6 class="text-white">{{$question['question']}}</h6>
                    <div class="">
                        @foreach($question['answers'] as $answer)
                            <div class="d-flex">
                                <p>{{$answer['answer']}}</p>
                                @foreach($answer['answer_codes'] as $code)
                                    <p class="px-2" style="color: #f2661c"> {{$code['code']}}
                                        + {{$code['number']}}</p>
                                @endforeach
                            </div>
                        @endforeach
                        @livewire('admin.question.question-update-form', ['question' => $question, 'answers' =>
                        $question['answers']])
                    </div>
                </td>
                <td class="text-sm font-weight-normal">{{$question['gender'] === '0' ? 'Male & Female' : ($question['gender'] === '1' ? 'Female' : ($question['gender'] === '2' ? 'Male' : ''))}}</td>
                <td class="text-sm font-weight-normal">
                    <button data-bs-toggle="modal"
                            href="#question-{{$question['id']}}"
                            class="btn btn-sm updateBtn float-end mt-2 mb-0">Edit
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

