@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .table-text-color {
        color: #1c365e !important;
    }

    .dataTable-table th a {
        color: #1c365e !important;
    }
</style>
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">All Activity Logs</h5>
                </div>
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr class="table-text-color">
                            <th>Title</th>
                            <th>Description</th>
                            <th>Created At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($activityLogs as $log)
                            <tr class="table-text-color">
                                <td class="text-md font-weight-normal">{{ $log->action_title }}</td>
                                <td class="text-md font-weight-normal">{{ $log->action_description }}</td>
                                <td class="text-md font-weight-normal">{{ $log->created_at }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $activityLogs->links() }}
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
