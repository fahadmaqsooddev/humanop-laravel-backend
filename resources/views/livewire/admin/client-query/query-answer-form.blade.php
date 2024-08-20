<div wire:ignore.self class="modal fade" id="answerQueryModal{{$query['id']}}" tabindex="-1" role="dialog"
     aria-labelledby="answerQueryModal{{$query['id']}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fs-4 text-white">Query Answer</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @include('layouts.message')
                            <form wire:submit.prevent="submitForm">
                                <div class="form-group mt-4">
                                    <label class="form-label fs-6 text-white">Client Query:</label>
                                    <span style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$query['query']}}</span>
                                    <label class="form-label fs-6 text-white mt-4">Answer:</label>
                                    <textarea rows="4" class="form-control text-white mt-2" style="background-color: #0f1535"
                                              wire:model.defer="answer" id="message-text"
                                              placeholder="Type your answer here...">

                    </textarea>
                                </div>
                                <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

