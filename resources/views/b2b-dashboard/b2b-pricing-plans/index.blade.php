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
            <a data-bs-toggle="modal" data-bs-target="#inviteLinkSendModel" style="background-color: #f2661c; color: white"
                onclick="emptyRoleModal();" class="btn btn-sm float-end">Add Role Template</a>
            <br>
        </div>
    </div>
    <br>

    <div class="row container">
        <div class="card text-center border rounded-4 shadow-sm mx-auto p-4 col-md-5"
            style="max-width: 450px; background-color:#F6BA81">
            <div class="card-header bg-opacity-50 rounded-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Membership Icon"
                    style="width: 54px; object-fit: contain;" />
                <div class="mt-3 px-3 py-1 mx-auto border shadow-sm rounded-pill w-50 text-dark fw-semibold">
                    Premium
                </div>
                <h4 class="mt-3 fw-bold display-6">$29</h4>
                <small class="text-muted">/ per month</small>
            </div>
            <hr class="my-4 border border-secondary" />
            <div class="card-body px-4">
                <h5 class="fw-semibold">Features</h5>
                <p class="text-muted">Everything in Basic Plan</p>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item">Unlimited Access</li>
                </ul>
            </div>
        </div>

        <div class="card text-center border rounded-4 shadow-sm mx-auto p-4 col-md-5"
            style="max-width: 450px;background-color:#bcdec6">
            <div class="card-header bg-opacity-50 rounded-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Membership Icon"
                    style="width: 54px; object-fit: contain;" />
                <div class="mt-3 px-3 py-1 mx-auto border shadow-sm rounded-pill w-50 text-dark fw-semibold">
                    Premium
                </div>
                <h4 class="mt-3 fw-bold display-6">$29</h4>
                <small class="text-muted">/ per month</small>
            </div>
            <hr class="my-4 border border-secondary" />
            <div class="card-body px-4">
                <h5 class="fw-semibold">Features</h5>
                <p class="text-muted">Everything in Basic Plan</p>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item">Unlimited Access</li>
                </ul>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="inviteLinkSendModel" tabindex="-1" role="dialog"
        aria-labelledby="inviteLinkSendModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Create A Plan</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="text-white">Plan Name</label>
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                class="form-control text-white" type="text" wire:model="plan_name"
                                                name="plan_name" placeholder="icon name">
                                            @error('plan_name')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror
                                            <label class="text-white">Plan Type</label>
                                            <select style="background-color: #0f1534; color: lightgrey !important"
                                                class="form-control text-white" wire:model="plan_type" name="plan_type">
                                                <option value="">-- Select Plan --</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                            @error('plan_name')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror

                                            <label class="text-white">Price</label>
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                class="form-control text-white" type="number" wire:model="price"
                                                name="price" placeholder="icon name">

                                            @error('price')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror

                                            <label class="text-white">Description</label>
                                            <textarea style="background-color: #0f1534; color: lightgrey !important" class="form-control text-white"
                                                wire:model="plan_desc" name="plan_desc" placeholder=""></textarea>
                                            @error('plan_desc')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror


                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"
                                                style="background-color: #f2661c ">Generate Invite
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
