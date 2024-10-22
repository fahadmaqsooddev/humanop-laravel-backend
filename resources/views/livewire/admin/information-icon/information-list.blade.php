<div>
    <div class="table-responsive table-orange-color">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>name</th>
                <th>Information</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($iconInformations as $info)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$info['name'] }} </td>
                    <td class="text-md font-weight-normal">{{$info['information']}} </td>
                    <td>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#editInformationIconModel-{{$info['id']}}">
                            update
                        </button>
                    </td>
                </tr>

                <div wire:ignore.self class="modal fade" id="editInformationIconModel-{{$info['id']}}" tabindex="-1"
                     role="dialog"
                     aria-labelledby="editInformationIconModel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style=" border-radius: 9px">
                                <div class="card-body pt-0">
                                    <label class="form-label fs-4 text-white">Edit Information Icon</label>

                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close" id="close-info-modal-button">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <form wire:submit.prevent="editSubmitForm">
                                        <div class="card-body pt-0">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <label class="form-label text-white">Name</label>
                                                    <div class="form-group">
                                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                                               type="text" name="name" value="{{$info['name']}}"
                                                               placeholder="icon name">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label text-white">Information</label>
                                                    <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="10" cols="10"
                                                      name="information">{!! $info['information'] !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-sm float-end mt-3 mb-0 text-white"
                                                    style="background-color: #f2661c ">Update Information
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

            </tbody>
        </table>
    </div>


    <div wire:ignore.self class="modal fade" id="informationIconModel" tabindex="-1"
         role="dialog"
         aria-labelledby="informationIconModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Create Information Icon</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-info-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body pt-0">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label class="form-label text-white">Name</label>
                                        <div class="form-group">
                                            <input style="background-color: #0f1534;" class="form-control text-white"
                                                   type="text" name="limit"
                                                   wire:model="name" placeholder="icon name">
                                            @error('name')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label text-white">Information</label>
                                        <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="5" cols="5"
                                                      name="information"
                                                      wire:model="information"></textarea>
                                            @error('information')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #f2661c ">Create Information
                                </button>
                            </div>
                        </form>
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
    <script>
        window.Livewire.on('closeInfoModal', function (e) {

            $('#close-info-modal-button').click();
        })
    </script>
@endpush
