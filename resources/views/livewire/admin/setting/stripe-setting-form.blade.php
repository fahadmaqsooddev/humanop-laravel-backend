<div class="card setting-box-background mt-4" id="accounts">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Stripe Account Setting</h5>
    </div>
    @include('layouts.message')
    <div class="card-body pt-0">
        <form wire:submit.prevent="submitForm" >
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label text-color-dark">Account Name</label>
                        <div class="form-group">
                            <input wire:model.defer="account.account_name" class="form-control text-color-dark setting-box-background"
                                   type="text"
                                   placeholder="account name">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-color-dark">Account Email</label>
                        <div class="form-group">
                            <input class="form-control text-color-dark setting-box-background"
                                   type="email" wire:model.defer="account.account_email"

                                   placeholder="account email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="form-label text-color-dark">API KEY</label>
                        <div class="form-group">
                            <input class="form-control text-color-dark setting-box-background" wire:model.defer="account.api_key"
                                   type="text"
                                   placeholder="api key">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-color-dark">PUBLIC KEY</label>
                        <div class="form-group">
                            <input class="form-control text-color-dark setting-box-background" wire:model.defer="account.public_key"
                                   type="text"
                                   placeholder="public key">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-color-dark">Amount</label>
                        <div class="form-group">
                            <input class="form-control text-color-dark setting-box-background" wire:model.defer="account.amount"
                                   type="text"
                                   placeholder="amount">
                        </div>
                    </div>
                </div>
                <button type="submit" class=" btn-sm float-end mt-6 mb-0" style="background:#1b3a62 !important;color:white;font-weight:bolder;border:none;">Update
                    account
                </button>
            </div>
        </form>
    </div>
</div>
