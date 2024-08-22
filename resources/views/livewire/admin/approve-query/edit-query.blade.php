<div>

    <div wire:ignore.self class="modal fade" id="editQueryModal{{ $queryId }}" tabindex="-1" role="dialog"
         aria-labelledby="editQueryModal{{ $queryId }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <form wire:submit.prevent="updateAndApproveAnswer">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close" id="close-query-edit-modal-{{$queryId}}">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                    @include('layouts.message')
                                    <label class="form-label fs-6 text-white">Client Query:</label>
                                    <span style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$question['query'] ?? null}}</span>
                                    <label class="form-label fs-4 text-white">Answers</label>
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               type="text" wire:model.defer="answer"
                                               placeholder="update answer">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                                Answer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('javascript')
    <script>

        window.Livewire.on('closeEditQueryModal', function (e){

            $('#close-query-edit-modal-'+ e.id).click();
        });

    </script>
@endpush
