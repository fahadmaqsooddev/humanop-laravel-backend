<div class="card mt-4" id="accounts">
    <div class="card-header">
        <h5>Stripe Account Setting</h5>
    </div>
    @include('layouts.message')
    <div class="card-body pt-0">
        <form wire:submit.prevent="submitForm" >
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-6">
                        <label class="form-label text-white">Account Name</label>
                        <div class="form-group">
                            <input style="background-color: #0f1534;" wire:model.defer="account.account_name" class="form-control text-white"
                                   type="text"
                                   placeholder="account name">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-white">Account Email</label>
                        <div class="form-group">
                            <input style="background-color: #0f1534;" class="form-control text-white"
                                   type="email" wire:model.defer="account.account_email"

                                   placeholder="account email">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label class="form-label text-white">API KEY</label>
                        <div class="form-group">
                            <input style="background-color: #0f1534;" class="form-control text-white" wire:model.defer="account.api_key"
                                   type="text"
                                   placeholder="api key">
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-white">PUBLIC KEY</label>
                        <div class="form-group">
                            <input style="background-color: #0f1534;" class="form-control text-white" wire:model.defer="account.public_key"
                                   type="text"
                                   placeholder="public key">
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-white">Amount</label>
                        <div class="form-group">
                            <input style="background-color: #0f1534;" class="form-control text-white" wire:model.defer="account.amount"
                                   type="text"
                                   placeholder="amount">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #f2661c ">Update
                    account
                </button>
            </div>
        </form>
    </div>
</div>
