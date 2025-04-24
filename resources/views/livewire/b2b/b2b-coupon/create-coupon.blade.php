<div wire:ignore.self class="modal fade" id="createB2BCouponModel" tabindex="-1"
     role="dialog"
     aria-labelledby="createB2BCouponModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                @include('layouts.message')
                <div class="card-body pt-0">
                    <label class="form-label fs-4 text-white">Coupon Discount</label>

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form wire:submit.prevent="submitForm">
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <label class="form-label text-white">Discount Percentage</label>
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;" class="form-control text-white" type="text" name="limit" wire:model="limit"
                                               placeholder="limit">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm float-end mt-2 mb-0 text-white"
                                    style="background-color: #f2661c ">Create Coupon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
