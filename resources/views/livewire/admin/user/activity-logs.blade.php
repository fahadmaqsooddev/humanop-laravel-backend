@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .pagination {
            float: right;
            margin-right: 24px;
        }

        .page-link {
            background: none !important;
        }

        .page-link:hover {
            background: #1B3A62 !important;
            color: white !important;
        }

        .page-item.active .page-link {
            background: #1B3A62 !important;
            color: white !important;
            border-color: #1B3A62 !important;
        }

    </style>
@endpush
<div class="row mt-4 container-fluid">
    <div class="col-12">
        <div class="card">
            <!-- Card header -->
            <div class="card-header table-header-text">
                <h5 class="mb-0">All Activity Logs</h5>
            </div>
            <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
                <table class="table table-flush">
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

            </div>

            {{-- Pagination --}}
            {{ $activityLogs->links() }}
        </div>
    </div>
</div>
@push('js')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script>
        const dataTableSearch = new simpleDatatables.DataTable("#datatable-search", {
            searchable: true,
            fixedHeight: true
        });
    </script>
@endpush
