@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>

.modal-close-btn {
background: #f2661c;
border: none;
color: white;
font-weight: bold;
font-size: x-large;
float:right;
border-radius: 3px;
padding: 0px 10px 1px 10px;
}
</style>
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card" >
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0">Sub Admins</h5>
                </div>

                @livewire('admin.sub-admin.index',['admins' => $admins])


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
