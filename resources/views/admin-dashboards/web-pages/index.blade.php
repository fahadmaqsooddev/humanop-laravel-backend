@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Web Pages</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-search">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($web_pages as $page)
                            <tr>
                                <td class="text-sm font-weight-normal">{{$page['name']}} </td>
                                <td class="text-sm font-weight-normal">{{$page['title']}} </td>
                                <td class="text-sm font-weight-normal">
                                    <button data-bs-toggle="modal"
                                            href="#page-{{$page['id']}}"
                                            class="btn btn-sm updateBtn float-end mt-2 mb-0">Edit
                                    </button>
                                </td>
                            </tr>
                            @livewire('admin.web-page.web-page-form', ['page' => $page])
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

