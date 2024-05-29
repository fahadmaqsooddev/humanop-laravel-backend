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
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-sm font-weight-normal">{{$question['gender'] === '0' ? 'Male & Female' : ($question['gender'] === '1' ? 'Female' : ($question['gender'] === '2' ? 'Male' : ''))}}</td>
                                <td class="text-sm font-weight-normal"><a href="{{ route('admin_user_detail') }}"
                                                                          type="submit"
                                                                          style="background-color: #f2661c; color: white"
                                                                          class="btn btn-sm float-end mt-2 mb-0">Edit</a>
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
