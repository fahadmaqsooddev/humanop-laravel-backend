<div class="card mt-4 setting-box-background" id="password">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Change Password</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" class="mb-4">
        <div class="card-body pt-0">
            @if(\Illuminate\Support\Facades\Auth::user()['password_set'] == 1)
                <label class="form-label text-color-dark">Current password</label>
                <div class="form-group position-relative">
                    <input wire:model="current_password" id="currentPassword"
                           class="form-control text-color-dark setting-box-background" type="password"
                           placeholder="Current password">
                </div>
                <span class="position-absolute" id="toggleCurrentPassword"
                      style="right: 32px; top: 133px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;"><i
                        class="fas fa-eye-slash pt-1 " id="current-password-eye"
                        style="    color: #f2661c !important;"></i></span>
            @endif
            <label class="form-label text-color-dark">New password</label>
            <div class="form-group position-relative">
                <input class="form-control text-color-dark setting-box-background" wire:model="password"
                       type="password" maxlength="30" id="new-password"
                       placeholder="New password">
                <span class="position-absolute " id="toggleNewPassword"
                      style="right: 9px; top: 19px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;">
                    <i class="fas fa-eye-slash pt-1" id="new-password-eye"
                       style="    color: #f2661c !important;"></i></span>
            </div>
            <label class="form-label text-color-dark">Confirm password</label>
            <div class="form-group position-relative">
                <input class="form-control text-color-dark setting-box-background" wire:model="confirm_password"
                       type="password" id="confirmPassword" maxlength="30" placeholder="Confirm password">
                <span class="position-absolute" id="toggleConfirmPassword"
                      style="right: 9px; top: 19px; transform: translateY(-50%); cursor: pointer; color: white; z-index: 10;"><i
                        class="fas fa-eye-slash pt-1 confirm-password-eye" id="confirm-password-eye"
                        style="    color: #f2661c !important;"></i></span>
            </div>
            <button type="submit" class=" btn-sm float-end mt-2 mb-0 rainbow-border-user-nav-btn">
                Update password
            </button>
        </div>
    </form>
</div>
<script>

    // current password eye toggler
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    const currentPasswordInput = document.getElementById('currentPassword');
    const currentIcon = document.getElementById('current-password-eye');

    // Add click event listener to the eye icon
    if (toggleCurrentPassword && currentPasswordInput) {
        toggleCurrentPassword.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default behavior (especially for anchors)

            // Toggle the password visibility and the eye icon
            if (currentPasswordInput.type === 'password') {
                currentPasswordInput.type = 'text';
                currentIcon.classList.remove('fa-eye-slash');
                currentIcon.classList.add('fa-eye'); // Change to the eye-slash icon when password is visible
            } else {
                currentPasswordInput.type = 'password';
                currentIcon.classList.remove('fa-eye');
                currentIcon.classList.add('fa-eye-slash'); // Change back to the eye icon when password is hidden
            }
        });
    }


    // new password eye toggler
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('toggleNewPassword');
        const passwordInput = document.getElementById('new-password');
        const icon = document.getElementById('new-password-eye');

        // Add click event listener to the eye icon
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior (especially for anchors)

                // Toggle the password visibility and the eye icon
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye'); // Change to the eye-slash icon when password is visible
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash'); // Change back to the eye icon when password is hidden
                }
            });
        }

        // confirm password eye toggler
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const confirmIcon = document.getElementById('confirm-password-eye');

        // Add click event listener to the eye icon
        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior (especially for anchors)

                // Toggle the password visibility and the eye icon
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    confirmIcon.classList.remove('fa-eye-slash');
                    confirmIcon.classList.add('fa-eye'); // Change to the eye-slash icon when password is visible
                } else {
                    confirmPasswordInput.type = 'password';
                    confirmIcon.classList.remove('fa-eye');
                    confirmIcon.classList.add('fa-eye-slash'); // Change back to the eye icon when password is hidden
                }
            });
        }

        $('#phone').on('input', function () {

            let input = $(this).val();
            // 1. Remove all characters except numbers and `+`
            input = input.replace(/[^+\d]/g, '');

            // 2. Ensure the `+` appears only at the start
            if (input.indexOf('+') > 0) {
                input = input.replace(/\+/g, ''); // Remove additional `+` symbols
            }

            // 3. Limit to 14 characters
            input = input.slice(0, 14);

            // 4. Set sanitized value back to the input field
            $(this).val(input);
        });

        // Optional: Prevent invalid key presses
        $('#phone').on('keypress', function (e) {
            const char = String.fromCharCode(e.which);

            // Allow only digits or `+` at the start
            if (!/[\d+]/.test(char) || (char === '+' && $(this).val().indexOf('+') !== -1)) {
                e.preventDefault();
            }
        });
    });

</script>
