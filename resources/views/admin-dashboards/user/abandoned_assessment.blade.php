@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Users</h5>
                </div>
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Date & Time</th>
                            <th>Practitioner</th>
                            <th>Project</th>
                            <th>Email</th>
                            <th>Assessment Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assessments as $assessment)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$assessment['users'] ? $assessment['users']['first_name'].' '.$assessment['users']['last_name'] : ""}} </td>
                                <td class="text-sm font-weight-normal">{{\Carbon\Carbon::parse($assessment['users']['signup_date'] ?? null)->format('Y/m/d')}}</td>
                                <td class="text-sm font-weight-normal">Null</td>
                                <td class="text-sm font-weight-normal">Null</td>
                                <td class="text-sm font-weight-normal">{{$assessment['users']['email'] ?? ""}}</td>
                                <td class="text-sm font-weight-normal">Incomplete</td>
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
