<div class="card mt-4" id="password">
    <div class="card-header">
        <h5 class="text-white">Change Password</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" class="mb-4">
        <div class="card-body pt-0">
            @if(\Illuminate\Support\Facades\Auth::user()['google_id'] == '' && \Illuminate\Support\Facades\Auth::user()['password_set'] == 2)
                <label class="form-label text-white">Current password</label>
                <div class="form-group">
                    <input style="background-color: #0f1534;" wire:model="current_password"
                           class="form-control text-white" type="password"
                           placeholder="Current password">
                </div>
            @endif
            <label class="form-label text-white">New password</label>
            <div class="form-group">
                <input style="background-color: #0f1534;" class="form-control text-white" wire:model="password"
                       type="password"
                       placeholder="New password">
            </div>
            <label class="form-label text-white">Confirm new password</label>
            <div class="form-group">
                <input style="background-color: #0f1534;" class="form-control text-white" wire:model="confirm_password"
                       type="password"
                       placeholder="Confirm password">
            </div>
            <p class="text-muted mb-2">
                Please follow this guide for a strong password:
            </p>
            <ul class="text-muted ps-4 mb-0 float-start">
                <li>
                    <span class="text-sm text-white">One special characters</span>
                </li>
                <li>
                    <span class="text-sm text-white">Min 6 characters</span>
                </li>
                <li>
                    <span class="text-sm text-white">One number (2 are recommended)</span>
                </li>
                <li>
                    <span class="text-sm text-white">Change it often</span>
                </li>
            </ul>
            <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #f2661c ">
                Update password
            </button>
        </div>
    </form>
</div>
