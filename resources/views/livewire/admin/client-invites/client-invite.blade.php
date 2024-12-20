<div>

    <div class="p-2">

        <div class="input-group ms-md-4 pe-md-4 d-flex justify-content-end">
            <input type="email" wire:model="searched_email"
                   style="border-radius: 5px; width: 30%; padding: 5px;"
                   class="table-orange-color search-bar" placeholder="Search Email">
        </div>

    </div>

    <div class="table-responsive table-orange-color">

        @if(count($invites) > 0)

        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>Email</th>
                <th>link</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invites as $index => $invite)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$invite['email']}} </td>
                    <td class="text-md font-weight-normal">{{ url('/register?link=' . $invite['link']) }} </td>
                    <td>
                        <button class="btn mb-0 text-white" id="copy_link_{{$index+1}}"
                                    onclick="copyToClipboard('{{ url('/register?link=' . $invite['link']) }}','{{$index +1}}')"
                                    style="background-color: #f2661c;border-radius: 0px 5px 5px 0px">Copy Link
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        @else
            <div class="text-center p-5">

                <span class="custom-text-dark">No invites found</span>

            </div>
        @endif

        {{$invites->links('pagination.table-pagination')}}

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

                                            <label class="text-white mt-4">Upload csv file</label>
                                            <input
                                                style="background-color: #0f1534; color: lightgrey !important;"
                                                class="form-control text-white"
                                                type="file"
                                                wire:model="file"
                                                name="file"
                                                accept=".csv,.xlsx,.xls"
                                                placeholder="Choose a file">
                                            @error('file')
                                                <span class="text-sm text-danger">{{$message}}</span>
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

   <script>
       async function copyToClipboard(text,id) {
           try {
               // Use the Clipboard API to copy the text
               await navigator.clipboard.writeText(text);
               $('#copy_link_'+id).text('Copied!')
               // Hide the tooltip after 2 seconds
               setTimeout(() => {
                   setTimeout(() => {
                       $('#copy_link_'+id).text('Copy Link')
                   }, 300);  // Match the fade-out duration
               }, 2000);
           } catch (err) {
               console.error('Failed to copy text: ', err);
           }
       }

   </script>

@endpush
