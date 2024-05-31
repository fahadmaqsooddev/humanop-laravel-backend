@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">All Questions</h5>
                </div>
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
                                            {{--    Question Modal--}}
                                            <div class="modal fade" id="question-{{$question['id']}}" aria-hidden="true"
                                                 aria-labelledby="question-{{$question['id']}}"
                                                 tabindex="-1">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body"
                                                             style="background-color: #0f1535; border-radius: 9px">
                                                            <form action="" method="post">
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
                                                                                    value="{{$question['question']}}"
                                                                                    placeholder="question">
                                                                            </div>
                                                                            <label
                                                                                class="form-label fs-4 text-white">Answer</label>
                                                                            @foreach($question['answers'] as $answer)
                                                                                <div class="form-group">
                                                                                    <input
                                                                                        style="background-color: #0f1534;"
                                                                                        class="form-control text-white"
                                                                                        type="text" name="answer"
                                                                                        value="{{$answer['answer']}}"
                                                                                        placeholder="question">
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });
    </script>
@endpush
