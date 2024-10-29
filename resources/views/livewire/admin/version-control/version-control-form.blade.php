<div>
    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>#</th>
                <th>Version</th>
                <th>Release Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($versions as $version)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$version['id'] }} </td>
                    <td class="text-md font-weight-normal">{{$version['version']}} </td>
                    <td class="text-md font-weight-normal">{{\Illuminate\Support\Carbon::parse($version['updated_at'])->format('m/d/Y')}} </td>
                    <td>
                        <button type="submit"
                                wire:click="updateEditModal({{$version['id']}},`{{$version['version']}}`,`{{$version['details']}}`)"
                                data-bs-toggle="modal"
                                data-bs-target="#versionControlModel"
                                class="rainbow-border-user-nav-btn btn-sm mt-2 mb-0">update
                        </button>
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>

    <div wire:ignore.self class="modal fade" id="createVersionModel" tabindex="-1"
         role="dialog"
         aria-labelledby="informationIconModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Create Information Icon</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-version-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form wire:submit.prevent="createVersion">
                            <div class="card-body pt-0">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label class="form-label text-white">Version</label>
                                        <div class="form-group">
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                   class="form-control text-white"
                                                   type="text" name="limit"
                                                   wire:model="version" placeholder="version name">
                                            @error('version')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label text-white">Version Detail</label>
                                        <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="10" cols="10"
                                                      name="information"
                                                      wire:model="details"></textarea>
                                            @error('details')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mb-0 text-white"
                                        style="background-color: #f2661c ">Update version
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="versionControlModel" tabindex="-1"
         role="dialog"
         aria-labelledby="informationIconModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Create Information Icon</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-version-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form wire:submit.prevent="updateVersion">
                            <div class="card-body pt-0">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label class="form-label text-white">Version</label>
                                        <div class="form-group">
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                   class="form-control text-white"
                                                   type="text" name="limit"
                                                   wire:model="version" placeholder="version name">
                                            @error('version')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label text-white">Version Detail</label>
                                        <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="10" cols="10"
                                                      name="information"
                                                      wire:model="details"></textarea>
                                            @error('details')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mb-0 text-white"
                                        style="background-color: #f2661c ">Update version
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
        document.addEventListener('livewire:load', function () {
            Livewire.on('closeUpdateModal', () => {
                $('#versionControlModel').modal('hide');
                $('#createVersionModel').modal('hide');
            });
        });

        function confirmBoxForPermanentDelete(coupon_id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-primary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to delete coupon!</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteCoupon', [coupon_id])
                }
            })
        }

    </script>
    <script>
        window.Livewire.on('closeInfoModal', function (e) {
            $('#close-version-modal-button').click();
        })

    </script>

@endpush
