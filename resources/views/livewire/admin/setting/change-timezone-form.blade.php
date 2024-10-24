<div class="card mt-4 setting-box-background" id="practitionerTimezone">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">Timezone</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitTimezoneForm" class="mb-4">
        <div class="card-body pt-0">
            <label class="form-label text-color-dark">Timezone</label>
            <div class="form-group">
                <select class="form-control setting-box-background text-color-dark" name="timezone" wire:model="timezone">
                    @foreach($timezones as $timezone)
                        <option value="{{$timezone}}">{{$timezone}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class=" btn-sm float-end mt-2 mb-0 rainbow-border-user-nav-btn">
                set timezone
            </button>
        </div>
    </form>
</div>
