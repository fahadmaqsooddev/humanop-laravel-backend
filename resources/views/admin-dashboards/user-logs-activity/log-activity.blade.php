@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>

    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #1b3a62 !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #1b3a62 !important;
        color: white !important;
        border-color: #1b3a62 !important;
    }

    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }

</style>
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card table-orange-color">
                <div class="container mt-4">
                    <h3>User Activity Logs</h3>
                    <table class="table table-bordered">
                        <thead style="color: black;">
                        <tr>
                            <th>#</th>
                            <th>Action perfomed</th>
                            <th>Description</th>
                            {{--                            <th>Properties</th>--}}
                            <th>Causer name</th>
                            <th>Time</th>

                        </tr>
                        </thead>
                        <tbody style="color:black;">
                        @foreach($logs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $log->causer_user ? $log->causer_user->first_name . ' ' . $log->causer_user->last_name : 'System' }}</td>
                                <td>{{ $log->description }}</td>

                                {{--                                <td>--}}
                                {{--                                    <pre>{{ json_encode(json_decode($log->properties, true), JSON_PRETTY_PRINT) }}</pre>--}}
                                {{--                                </td>--}}
                                <td>{{ $log->subject_user ? $log->subject_user->first_name . ' ' . $log->subject_user->last_name : 'N/A' }}</td>
                                <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>



            </div>
        </div>
    </div>
@endsection
