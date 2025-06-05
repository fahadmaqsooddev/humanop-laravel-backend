<div>
    <div class="card setting-box-background mt-4" id="delete">
        <div class="card-header">
            <h5 class="text-color-dark setting-form-heading">Delete Account</h5>
            <p class="text-sm mb-0 text-color-dark">Once you delete your account, there is no going
                back. Please be certain.</p>
        </div>
        <div class="card-body d-sm-flex pt-0">
            <button class="btn btn-outline-secondary mb-0 ms-auto invisible" type="button"
                    name="button">Deactivate
            </button>
            <button class="btn bg-gradient-danger mb-0 ms-2" type="button"
                    onclick="deleteClientProfile({{Auth::user()['id'] ?? null}}, '{{Auth::user()['first_name'] ?? null}}')"
                    name="button">Delete
                Account
            </button>
        </div>
    </div>
</div>
