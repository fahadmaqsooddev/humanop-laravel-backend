
<div class="table-responsive">

    <table class="table table-flush" id="datatable-search">
        <thead class="thead-light">
        <tr>
            <th>
                    Questions
            </th>
            <th>
                    Gender
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $q)

            <tr>
                <td class="text-sm font-weight-normal px-4">
                    <h6 class="text-white">{{ $q->question }}</h6>
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
                <td class="text-sm font-weight-normal">
                    {{ $q->gender === '0' ? 'Male & Female' : ($q->gender === '1' ? 'Female' : ($q->gender === '2' ? 'Male' : '')) }}
                </td>
                <td class="text-sm font-weight-normal px-4">
                    <button type="button" data-bs-toggle="modal"
                            data-bs-target="#updateQuestionModal{{ $q->id }}"
                            class="btn btn-sm updateBtn float-end mt-2 mb-0">Edit
                    </button>
                </td>
            </tr>
            @livewire('admin.question.question-update-form', ['question' => $q->toArray(), 'answers' => $q->answers->toArray()], key($q->id))
        @endforeach
        </tbody>
    </table>

    {{ $questions->links() }}

</div>
