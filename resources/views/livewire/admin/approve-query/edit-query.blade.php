<div wire:ignore.self class="modal fade" id="editQueryModal{{ $queryId }}" tabindex="-1" role="dialog"
         aria-labelledby="editQueryModal{{ $queryId }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Query Answer</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-query-edit-modal-{{$queryId}}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                @include('layouts.message')
                    <form wire:submit.prevent="updateAndApproveAnswer">
                        @csrf
                            <div class="form-group mt-2">
                                    <label class="form-label fs-6 text-white">Client Query:</label>
                                    <span
                                        style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$question['query'] ?? null}}</span>
                                    <label class="form-label fs-4 text-white">Answer:</label>
                                    <span class="copy-text float-end" >
                                       <!-- Copy text link -->
                                        <a class="btn-sm text-white px-3"  style="background-color: #f2661c;" onclick="copyToClipboard(`{{$answer}}`,`{{$queryId}}`, this)"><strong id="copy-text{{$queryId}}">Copy</strong></a>

                                  </span>
                                    <br>
                                    <span class="mt-2">{{$answer ?? null}}</span>
                                    <br>
                                    <label class="form-label fs-6 text-white mt-4">Update Answer:</label>
                                    <div class="form-group">
                                        <textarea rows="4" class="form-control text-white mt-2"
                                                  style="background-color: #0f1535"
                                                  wire:model.defer="updatedAnswer"
                                                  placeholder="update answer">
                                        </textarea>
                                    </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                            </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
        </div>
    </div>


@push('javascript')
    <script>

        window.Livewire.on('closeEditQueryModal', function (e) {
            $('#close-query-edit-modal-' + e.id).click();
        });

        async function copyToClipboard(text,id,button) {
            try {
                // Use the Clipboard API to copy the text
                await navigator.clipboard.writeText(text);

                $('#copy-text'+id).text('Copied!')
                // Hide the tooltip after 2 seconds
                setTimeout(() => {
                    setTimeout(() => {
                        $('#copy-text'+id).text('Copy')
                    }, 300);  // Match the fade-out duration
                }, 2000);

            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        }

    </script>
@endpush
