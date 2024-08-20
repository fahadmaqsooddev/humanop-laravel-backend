<div class="card-body">
    <div class="row">
        <div class="col-12">
            <label class="form-label fs-4 text-white">Describe your question briefly to
                us</label>
            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @include('layouts.message')
            <form wire:submit.prevent="submitForm" >
                <div class="form-group mt-4">
                    <textarea rows="4" class="form-control text-white" style="background-color: #0f1535" wire:model.defer="query"
                              id="message-text" placeholder="Type your question here..."></textarea>
                </div>
                <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Send Query</button>
            </form>
        </div>
    </div>
</div>
