<div>

    <div wire:ignore.self class="modal fade" id="viewQueryModal{{ $queryId }}" tabindex="-1" role="dialog"
         aria-labelledby="viewQueryModal{{ $queryId }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <form wire:submit.prevent="">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close" id="close-query-view-modal-{{$queryId}}">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                    <label class="form-label fs-6 text-white">Client Query:</label>
                                    <span style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$question['query'] ?? null}}</span>

                                    <label class="form-label fs-6 text-white">Admin Answer:</label>
                                    <span style="color: white;font-size: 18px;font-weight: 600;display: flex;">{{$answer ?? null}}</span>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
