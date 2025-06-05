<div>
    <div class="card">
        <div class="card-header table-header-text">
            <h5 class="mb-0">All Pricing Plans</h5>
            <a data-bs-toggle="modal"
               data-bs-target="#createPlanModel"
               style="background-color: #1B3A62 ; color: white" class="btn btn-sm float-end mb-0">Add Plans</a>

        </div>
        <div class="table-responsive w-100 pt-4 table-orange-color">
            @include('layouts.message')
            <table class="table table-flush" id="datatable-search">
                <thead class="thead-light">
                <tr class="text-color-blue">
                    <th>Name</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($plans as $plan)
                    <tr class="text-color-blue">
                        <td class="text-sm font-weight-normal text-center">{{$plan['name']}}</td>
                        <td class="text-sm font-weight-normal">{{$plan['billing_method']}}</td>
                        <td class="text-sm font-weight-normal text-center">{{$plan['price']}}</td>
                        <td class="text-sm font-weight-normal text-center">
                            <button class="btn text-white" style="background-color: #1b3a62"
                                    onclick="confirmBoxForActiveInactivePlan('{{$plan['id']}}')">{{$plan['status'] == 0 ? 'Inactive' : 'Active'}}</button>
                        </td>
                        <td>
                            <button class="btn btn-sm text-white" style="background-color: #1b3a62"
                                    data-bs-toggle="modal"
                                    wire:click="updatePlanModal({{ $plan['id']}},`{{$plan['name']}}`,`{{$plan['price']}}`,`{{$plan['no_of_team_members']}}`,`{{$plan['billing_method']}}`)"
                                    data-bs-target="#editPlan">edit
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div wire:ignore.self class="modal fade" id="createPlanModel" tabindex="-1"
             role="dialog" data-bs-focus="false"
             aria-labelledby="createPlanModel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4" style="color: #1b3a62">Create Plan</label>

                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-optimization-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <form wire:submit.prevent="submitForm">
                                <div class="row mt-4">
                                    <div class="col-12">

                                        <label class="form-label" style="color: #1b3a62">Plan Name</label>
                                        <div class="input-group">
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   class="form-control" type="text"
                                                   wire:model="plan_name"
                                                   placeholder="plan name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <select class="form-control bg-transparent" style="color: #1b3a62"
                                                wire:model="plan_type">
                                            <option value="" style="color: black">Select Plan Type</option>
                                            <option value="month" style="color: black">Month</option>
                                            <option value="year" style="color: black">Year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Plan Amount</label>
                                        <div class="input-group">
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   wire:model="price"
                                                   class="form-control table-header-text" type="text">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62 ">create plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editPlan" tabindex="-1"
             role="dialog" data-bs-focus="false"
             aria-labelledby="editPlan" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4" style="color: #1b3a62">Edit Plan</label>

                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-optimization-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <form wire:submit.prevent="updateB2bPlan">
                                <div class="row mt-4">
                                    <div class="col-12">

                                        <label class="form-label" style="color: #1b3a62">Plan Name</label>
                                        <div class="input-group">
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   class="form-control" type="text"
                                                   wire:model="plan_name"
                                                   placeholder="plan name">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <select class="form-control bg-transparent" style="color: #1b3a62"
                                                wire:model="plan_type" disabled>
                                            <option value="" style="color: black">Select Plan Type</option>
                                            <option value="month" style="color: black">Month</option>
                                            <option value="year" style="color: black">Year</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Plan Amount</label>
                                        <div class="input-group">
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   disabled
                                                   wire:model="price"
                                                   class="form-control table-header-text" type="text">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62 ">Update plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <script>
        window.Livewire.on('closeInfoModal', function (e) {
            $('#close-optimization-modal-button').click();
        })
    </script>
    <script>

        function confirmBoxForActiveInactivePlan(plan_id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-success m-2',
                    cancelButton: 'btn bg-gradient-primary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to Active / Inactive Plan!</span>",
                showCancelButton: true,
                confirmButtonText: 'Active / Inactive',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('activeInactivePlanModal', plan_id)
                }
            })
        }

    </script>
@endpush
