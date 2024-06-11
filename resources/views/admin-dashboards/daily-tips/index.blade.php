@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #f2661c;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float: right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }

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

</style>
@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">All Daily Tips</h5>
                    <a href="{{route('admin_create_daily_tip')}}" type="submit"
                       style="background-color: #f2661c; color: white" class="btn btn-sm float-end mb-0">Add Tip</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tips as $tip)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$tip['code']}} </td>
                                <td class="text-sm font-weight-normal">{{$tip['title']}}</td>
                                <td class="text-sm font-weight-normal">{{$tip['description']}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
