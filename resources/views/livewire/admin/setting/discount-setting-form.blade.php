<div wire:ignore.self class="modal fade" id="couponModel" tabindex="-1"
     role="dialog"
     aria-labelledby="couponModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
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
                                <div class="col-12">
                                    <label class="form-label text-white">Discount</label>
                                    <div class="form-group">
                                        <select style="background-color: #0f1535" class="form-control" name="discount" wire:model="discount">
                                            <option value="0">0%</option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                            <option value="15">15%</option>
                                            <option value="20">20%</option>
                                            <option value="25">25%</option>
                                            <option value="30">30%</option>
                                            <option value="35">35%</option>
                                            <option value="40">40%</option>
                                            <option value="45">45%</option>
                                            <option value="50">50%</option>
                                            <option value="55">55%</option>
                                            <option value="60">60%</option>
                                            <option value="65">65%</option>
                                            <option value="70">70%</option>
                                            <option value="75">75%</option>
                                            <option value="80">80%</option>
                                            <option value="85">85%</option>
                                            <option value="90">90%</option>
                                            <option value="95">95%</option>
                                            <option value="100">100%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white">Discount Limit</label>
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;" class="form-control text-white" type="text" name="limit" wire:model="limit"
                                               placeholder="limit">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                    style="background-color: #f2661c ">Create Coupon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
