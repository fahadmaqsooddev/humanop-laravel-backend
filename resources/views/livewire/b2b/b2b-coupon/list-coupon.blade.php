<div>
    <div class="table-responsive table-orange-color">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>Discount Code</th>
                <th>Discount Limit</th>
                <th>Coupon Duration</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                        @foreach($coupons['data'] as $coupon)
                            <tr class="table-text-color">
                                <td class="text-md font-weight-normal">{{$coupon['id']}} </td>
                                <td class="text-md font-weight-normal">{{$coupon['percent_off']}}% </td>
                                <td class="text-md font-weight-normal">{{$coupon['duration']}} </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="confirmB2BBoxForPermanentDelete('{{$coupon['id']}}')">
                                        delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach

            </tbody>
        </table>
    </div>

    @livewire('b2b.b2b-coupon.create-coupon')

</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>


        function confirmB2BBoxForPermanentDelete(coupon_id){

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
                    window.livewire.emit('deleteCoupon', coupon_id)
                }
            })
        }

    </script>

@endpush
