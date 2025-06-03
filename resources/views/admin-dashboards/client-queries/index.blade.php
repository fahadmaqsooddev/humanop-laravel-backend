@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #1b3a62;
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
        background: #1b3a62 !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #1b3a62 !important;
        color: white !important;
        border-color: #1b3a62 !important;
    }

    .box-grid-size {
        border: 2px solid white;
        padding: 12px 25px;
        text-align: center;
        width: 60px; /* Set fixed width */
        height: 60px; /* Set fixed height */
        display: inline-flex; /* Flexbox for equal sizing */
        justify-content: center; /* Center content horizontally */
        align-items: center; /* Center content vertically */
    }
    .greenBox
    {
        background-color: green !important;
    }
    .redBox
    {
        background-color: red !important;
    }
    .lightGreenBox
    {
        background-color: yellow !important;
        color: black !important;
        font-weight: bold !important;
    }
    .border-green
    {
        border: 2px solid green !important;
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
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Client Queries</h5>
                </div>
                @livewire('admin.client-query.client-query')
            </div>
        </div>
    </div>
@endsection
