<div wire:ignore.self class="modal fade" id="intentionPlan{{$plan_id}}" tabindex="-1"
     role="dialog"
     aria-labelledby="intentionPlan{{$plan_id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="border-radius: 9px">

                @include('layouts.message')
                <div class="card-body pt-0">
                    <label class="form-label fs-4" style="color: #1b3a62">Update Intention Plan</label>

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form wire:submit.prevent="submitForm">
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label text-white">Plan Description</label>
                                    <div class="form-group">
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style" type="text" name="limit" wire:model="description"
                                               placeholder="limit">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                    style="background-color: #1b3a62 ">Update Intention Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
