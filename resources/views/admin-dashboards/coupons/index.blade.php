@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #1b3a62;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float:right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }
    .pagination{
        float:right;
        margin-right:24px ;
    }
    .page-link {
        background: none !important;
    }
    .page-link:hover{
        background: #1b3a62 !important;
        color:white !important;
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
            <div class="card" >
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">All Coupon</h5>
                    <a data-bs-toggle="modal"
                       data-bs-target="#couponModel"
                       style="background-color: #1B3A62 ; color: white" class="btn btn-sm float-end mb-0">Add Coupon</a>
                </div>
                @livewire('admin.setting.discount-list')
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
