<div>
    <div class="card">
        <div class="card-header table-header-text">
            <h5 class="mb-0">All Pricing Plans</h5>
            <a style="background-color: #1B3A62 ; color: white" class="btn btn-sm float-end mb-0"
               href="{{route('admin_create_pricing_plan')}}">Add Plans</a>

        </div>
        <div class="table-responsive w-100 pt-4 table-orange-color">
            @include('layouts.message')
            <table class="table table-flush" id="datatable-search">
                <thead class="thead-light">
                <tr class="text-color-blue">
                    <th class="text-sm font-weight-normal text-center">Name</th>
                    <th class="text-sm font-weight-normal text-center">Type</th>
                    <th class="text-sm font-weight-normal text-center">Amount</th>
                    <th class="text-sm font-weight-normal text-center">Status</th>
                    <th class="text-sm font-weight-normal text-center">Action</th>
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
                            <a href="{{ route('admin_edit_pricing_plan',['id' => $plan['id'] ]) }}" type="submit" style="background-color: #1B3A62 ; color: white" class="btn text-white">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
