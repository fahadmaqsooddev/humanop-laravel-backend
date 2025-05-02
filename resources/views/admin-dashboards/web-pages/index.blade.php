@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }
</style>
@section('content')
    <div class="row mt-4 container-fluid
    ">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Web Pages</h5>
                </div>
                <div class="table-responsive table-orange-color">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr class="table-text-color">
                            <th>Name</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($web_pages as $page)
                            <tr class="table-text-color">
                                <td class="text-md font-weight-normal">{{$page['name']}} </td>
                                <td class="text-md font-weight-normal">{{$page['title']}}</td>
                                <td class="text-md font-weight-normal"><a href="{{ route('admin_edit_web_pages',['id' => $page['id'] ]) }}" type="submit" style="background-color: #f2661c; color: white" class="btn btn-sm float-end mt-2 mb-0">Edit</a></td>
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
