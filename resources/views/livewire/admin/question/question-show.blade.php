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
                @forelse($questions as $question)
                    <tr>
                        <td class="text-sm font-weight-normal">
                            <h6 class="text-white">{{$question['question']}}</h6>
                            <div class="">
                                @foreach($question['answers'] as $answer)
                                    <div class="d-flex">
                                        <p>{{$answer['answer']}}</p>
                                        @foreach($answer['answerCodes'] as $code)
                                            <p class="px-2" style="color: #f2661c"> {{$code['code']}}
                                                + {{$code['number']}}</p>
                                        @endforeach
                                    </div>
                                    @include('livewire.admin.question.question-form-update')
                                @endforeach
                            </div>
                        </td>
                        <td class="text-sm font-weight-normal">{{$question['gender'] === '0' ? 'Male & Female' : ($question['gender'] === '1' ? 'Female' : ($question['gender'] === '2' ? 'Male' : ''))}}</td>
                        <td class="text-sm font-weight-normal">
                            <button wire:click="editQuestion({{$question['id']}})" data-bs-toggle="modal"
                                    href="#updateQuestion"
                                    class="btn btn-sm updateBtn float-end mt-2 mb-0">Edit
                            </button>
                        </td>
                    </tr>
                @empty
                @endforelse
        </tbody>
    </table>
</div>
