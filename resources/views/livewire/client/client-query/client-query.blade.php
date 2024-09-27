<div>

    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <label class="form-label fs-4 text-white">Describe your question briefly to
                    us</label>
                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <form wire:submit.prevent="submitForm" >
                    <div class="form-group mt-4">
                        <span style="color: #f2661c;font-size: 26px;font-weight: 800;display: flex;">{{$chat['query'] ?? null}}</span>
                        <div class="text-white mt-2">{!! $chat['answer'] ?? null !!}</div>
                        <textarea rows="4" class="form-control text-white mt-4" style="background-color: #0f1535" wire:model.defer="query"
                                  id="message-text" placeholder="Type your question here..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-9 text-center">
                            @include('layouts.message')
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Send Query</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('javascript')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        window.Livewire.on('hideModal', function () {

            setTimeout(function (){
                $('#exampleModalMessage').modal('hide');
                $('#add_feedback').click();
            }, 1000);
        });

    </script>

@endpush
