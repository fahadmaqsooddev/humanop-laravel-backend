<div>

    <div class="table-responsive table-orange-color">
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>#</th>
                <th>Email</th>
                <th>link</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invites as $index => $invite)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{ $index + 1 }} </td>
                    <td class="text-md font-weight-normal">{{$invite['email']}} </td>
                    <td class="text-md font-weight-normal">{{$invite['link']}} </td>
                </tr>

            @endforeach

            </tbody>
        </table>
    </div>


    <div wire:ignore.self class="modal fade" id="inviteLinkSendModel" tabindex="-1"
         role="dialog"
         aria-labelledby="inviteLinkSendModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Send Client Invite Link For Signup</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="text-white">Email</label>
                                            <input style="background-color: #0f1534;color: lightgrey !important" class="form-control text-white"
                                                   type="email"  wire:model="email" name="email" placeholder="icon name">
                                            @error('email')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"
                                                    style="background-color: #f2661c ">Send Invite
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

</div>
@push('javascript')

    <script type="module">

        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                // Close the modal
                $('#inviteLinkSendModel').modal('hide');
            });
        });
    </script>



@endpush
