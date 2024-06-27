@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Users</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assessment_details as $index => $assessment)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$index + 1}} - {{$assessment['question']}}</td>
                                <td class="text-sm font-weight-normal">{{$assessment['answer']}}</td>
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
