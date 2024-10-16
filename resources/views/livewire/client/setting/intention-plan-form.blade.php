<div class="card mt-4 setting-box-background" id="intentionPlan">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">90 Days Intention Plan</h5>
    </div>
    @include('layouts.message')
    <form wire:submit.prevent="submitForm" class="mb-4">
        <div class="card-body pt-0">
            <label class="form-label text-color-dark">90 Day Intention</label>
            <div class="form-group text-color-dark">
                <input wire:model.defer="intention"
                       class="form-control text-color-dark setting-box-background" type="text"
                       placeholder="In the next 90 Days I would like to ...">
            </div>
{{--            @foreach($intentionPlans as $option)--}}
{{--                <div class="form-check">--}}
{{--                    <input type="checkbox" name="ninety_day_intention[]"--}}
{{--                           value="{{$option['id']}}" class=" form-check-input">--}}
{{--                    <label for="name"--}}
{{--                           style="color: #0f1535; font-size: 15px">{{$option['description']}}</label>--}}
{{--                </div>--}}
{{--            @endforeach--}}
            <button type="submit" class="btn-sm float-end mt-6 mb-0 rainbow-border-user-nav-btn">
                Update intention
            </button>
        </div>
    </form>
</div>
