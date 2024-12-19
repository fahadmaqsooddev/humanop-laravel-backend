<div>
    <div class="card mt-4 setting-box-background" id="two-way-auth">
        <div class="card-header">
            @include('layouts.message')

            <div class="form-switch mb-0 d-flex justify-content-between" style="padding-left: 0px">
                <h5 class="text-color-dark setting-form-heading authorization">2-Factor Authorization</h5>
                <input class="form-check-input"
                       wire:change="updateStatus()"
                       name="status"
                       type="checkbox"
                       @checked($status)>
            </div>
            @if($numberCheck)
            <div class="col-12">
                <label class="form-label mt-4 text-color-dark">Phone Number</label>
                <div class="input-group">
                    <input  id="phone" wire:model.defer="phone" onfocus="focused(this)"
                            maxlength="14" class="form-control text-color-dark setting-box-background" type="tel" placeholder="Enter your phone number">
                </div>
                <button class=" btn-sm float-end mt-2 mb-0 connection-btn" style="font-size: 16px !important;" wire:click="updateNumber()" >
                    Add Phone
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
