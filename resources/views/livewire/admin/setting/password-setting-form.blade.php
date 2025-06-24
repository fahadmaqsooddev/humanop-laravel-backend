<div class="card setting-box-background mt-4" id="password">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Change Password</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" class="mb-4">
        <div class="card-body pt-0">
            @if(\Illuminate\Support\Facades\Auth::user()['password_set'] == 1)
                <label class="form-label text-color-dark">Current password</label>
                <div class="form-group">
                    <input wire:model="current_password"
                           class="form-control input-form-style" type="password"
                           placeholder="Current password">
                </div>
            @endif
            <label class="form-label text-color-dark">New password</label>
            <div class="form-group">
                <input class="form-control input-form-style" wire:model="password"
                       type="password"
                       placeholder="New password">
            </div>
            <label class="form-label text-color-dark">Confirm password</label>
            <div class="form-group">
                <input class="form-control input-form-style" wire:model="confirm_password"
                       type="password"
                       placeholder="Confirm password">
            </div>
            <button type="submit" class=" btn-sm float-end mt-2 mb-0" style="background:#1b3a62 !important;color:white;font-weight:bolder;border:none;">
                Update password
            </button>
        </div>
    </form>
</div>
