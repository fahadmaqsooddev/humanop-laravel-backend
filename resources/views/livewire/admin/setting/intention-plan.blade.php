<div>
    <div class="table-responsive table-orange-color">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
           
                <th>Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($intentionOption as $option)
                <tr class="table-text-color">

                    <td class="text-md font-weight-normal">{{$option['description']}} </td>
                    <td>
                        <button class="btn btn-sm text-white" style="background-color: #f2661c;" data-bs-toggle="modal"
                                data-bs-target="#intentionPlan{{$option['id']}}" >
                            update
                        </button>
                    </td>
                </tr>

                @livewire('admin.setting.intention-plan-update-form', ['description' => $option['description'], 'plan_id' => $option['id']], key($option->id))
            @endforeach

            </tbody>
        </table>
        {{ $intentionOption->links() }}
    </div>

    <!-- Coupon Discount -->
    @livewire('admin.setting.intention-plan-create-form')
</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>


        function confirmBoxForPermanentDelete(coupon_id){

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton:  'btn bg-gradient-primary m-2',
                },
                buttonsStyling: false,
                background : '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to delete coupon!</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if(result.isConfirmed){
                    window.livewire.emit('deleteCoupon', [coupon_id])
                }
            })
        }

    </script>

@endpush
