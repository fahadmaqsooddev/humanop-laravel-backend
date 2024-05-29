@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Manage Codes</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Public Name</th>
                            <th>Code</th>
                            <th>Number</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($codes as $code)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$code['name']}} </td>
                                <td class="text-sm font-weight-normal">{{$code['public_name']}}</td>
                                <td class="text-sm font-weight-normal">{{$code['code']}}</td>
                                <td class="text-sm font-weight-normal">{{$code['number']}}</td>
                                <td class="text-sm font-weight-normal"><a href="{{ route('admin_edit_manage_code',['id' => $code['id'] ]) }}" type="submit" style="background-color: #f2661c; color: white" class="btn btn-sm float-end mt-2 mb-0">Edit</a></td>
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
