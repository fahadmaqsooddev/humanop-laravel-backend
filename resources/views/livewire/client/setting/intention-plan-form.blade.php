<div class="card mt-4 setting-box-background" id="intentionPlan">
    <div class="card-header">
        <h5 class="text-color-dark setting-form-heading">90 Days Intention Plan</h5>
    </div>
    @include('layouts.message')

    <form wire:submit.prevent="submitForm" class="mb-4">
        <div class="card-body pt-0">
            <div class="form-group text-color-dark">
                @foreach($intention as $option)
                    <div class="form-check">
                        <input type="checkbox" @checked(true)
{{--                               wire:model.defer="intention"--}}
                               name="intention"
                               class="form-check-input">
                        <label for="name" style="color: #0f1535; font-size: 15px">
                            {{ \App\Enums\Admin\Admin::getIntentionOption($option->ninety_day_intention) }}
                        </label>
                    </div>
                @endforeach
            </div>
{{--            @foreach($intentionPlans as $option)--}}
{{--                <div class="form-check">--}}
{{--                    <input type="checkbox" name="ninety_day_intention[]"--}}
{{--                           value="{{$option['id']}}" class=" form-check-input">--}}
{{--                    <label for="name"--}}
{{--                           style="color: #0f1535; font-size: 15px">{{$option['description']}}</label>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--            <button type="submit" class="btn-sm float-end mt-6 mb-0 rainbow-border-user-nav-btn">--}}
{{--                Update intention--}}
{{--            </button>--}}
        </div>
    </form>
</div>
