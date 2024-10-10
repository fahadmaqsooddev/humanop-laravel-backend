@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4 container-fluid">
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
                            <th>Assessment</th>
                            <th>Assessment Status</th>
                            <th>Date & Time</th>
                            <th>Practitioner</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assessments as $assessment)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$assessment['id']}} </td>
                                <td class="text-sm font-weight-normal">{{$assessment['page'] === 0 ? 'Complete' : 'Incomplete'}} </td>
                                <td class="text-sm font-weight-normal">{{\Carbon\Carbon::parse($assessment['created_at'])->format('Y/m/d')}}</td>
                                <td class="text-sm font-weight-normal">Null</td>
                                <td class="text-sm font-weight-normal"><a href="{{ route('user_profile_overview',['id' => $assessment['id'] ]) }}" type="submit" style="background-color: #f2661c; color: white" class="rainbow-border-user-nav-btn btn-sm float-end mt-2 mb-0">View</a></td>
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
