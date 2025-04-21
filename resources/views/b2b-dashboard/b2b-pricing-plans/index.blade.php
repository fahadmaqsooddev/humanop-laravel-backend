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

    .table-text-color {
        color: #1c365e !important;
    }

    .dataTable-table th a {
        color: #1c365e !important;
    }

</style>
@section('content')
    <div class="container">
        <div style="margin-top: 80px; margin-left: 50px">
            <a data-bs-toggle="modal" data-bs-target="#roleModel" style="background-color: #f2661c; color: white" onclick="emptyRoleModal();" class="btn btn-sm float-end">Add Role Template</a>
        </div>
    </div>

        <div class="card text-center border rounded-4 shadow-sm mx-auto p-4" style="max-width: 450px;">
            <div class="card-header bg-opacity-50 rounded-4">
                <img
                    src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png"
                    alt="Membership Icon"
                    style="width: 54px; object-fit: contain;"
                />
                <div class="mt-3 px-3 py-1 mx-auto border shadow-sm rounded-pill w-50 text-dark fw-semibold">
                    Premium
                </div>
                <h4 class="mt-3 fw-bold display-6">$29</h4>
                <small class="text-muted">/ per month</small>
            </div>
            <hr class="my-4 border border-secondary"/>
            <div class="card-body px-4">
                <h5 class="fw-semibold">Features</h5>
                <p class="text-muted">Everything in Basic Plan</p>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item">Unlimited Access</li>
                </ul>
            </div>
        </div>
@endsection
