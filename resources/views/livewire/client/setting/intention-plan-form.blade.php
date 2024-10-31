<div class="card mt-4 setting-box-background" id="intentionPlan">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Your Intention for using the HumanOp Technology</h5>
    </div>
    @include('layouts.message')

    <form wire:submit.prevent="submitForm" class="mb-4">
        <div class="card-body pt-0">
            <div class="form-group text-color-dark">
                @foreach($intentionOptions as $option)
                    <div class="form-check">
                        <input type="checkbox"
                               value="{{ $option['id'] }}"
                               wire:model="selectedIntention"
                               class="form-check-input">
                        <label for="name" style="color: #0f1535; font-size: 15px">
                            {{ $option['description'] }}
                        </label>
                    </div>
                @endforeach
                <button type="submit" class="btn-sm float-end mt-4 mb-3 rainbow-border-user-nav-btn">Update Intention</button>
            </div>
        </div>
    </form>
</div>
