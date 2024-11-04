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
        background: #f2661c !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
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
            <div class="card">
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">User Feedback's</h5>
                </div>
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr class="table-text-color">
                            <th>#</th>
                            <th>Username</th>
                            <th>Feedback</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!isset($feedbacks[0]))
                            <tr class="table-text-color">
                                <td>No any feedback...</td>
                            </tr>
                        @endif
                        @foreach($feedbacks as $key => $feedback)
                            <tr class="table-text-color">
                                <td class="text-md font-weight-normal">{{$key + 1}}</td>
                                <td class="text-md font-weight-normal">{{$feedback['user'] ? $feedback['user']['first_name'] . ' ' . $feedback['user']['last_name'] : ""}}</td>
                                <td class="text-md font-weight-normal">{{$feedback['comment']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
