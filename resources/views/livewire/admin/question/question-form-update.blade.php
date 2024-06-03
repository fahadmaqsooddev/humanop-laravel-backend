<div wire:ignore.self class="modal fade" id="updateQuestion" aria-hidden="true"
     aria-labelledby="updateQuestion"
     tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body"
                 style="background-color: #0f1535; border-radius: 9px">
                <form wire:submit.prevent="updateQuestion" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Question</label>
                                <div class="form-group">
                                    <input
                                        style="background-color: #0f1534;"
                                        class="form-control text-white"
                                        type="text" name="question"
                                        wire:model="name"
                                        placeholder="question">
                                </div>
                                <label class="form-label fs-4 text-white">Answers</label>
{{--                                @foreach($answers as $index => $answer)--}}
                                    <div class="form-group">
                                        <input
                                            style="background-color: #0f1534;"
                                            class="form-control text-white"
                                            type="text"
                                            placeholder="answer">
                                    </div>
{{--                                @endforeach--}}
                            </div>
                        </div>
                        <button type="submit"
                                class="btn updateBtn btn-sm float-end mt-4 mb-0">
                            Update Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

