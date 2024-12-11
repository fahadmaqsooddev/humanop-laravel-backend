<div class="card card-bg-white-orange-border mt-4" id="setting">
    <div class="card-header">
        <div>
            @include('layouts.message')
            <div class="card-body">
                <div class="d-flex ">
                    <div class="col-md-4">
                        <div class="card-title fw-bold text-orange">Personalize</div>
                        <div class="card-text" style="color: #0f1535">Customize your chatbot by giving
                            it a name
                            and avatar
                            and tweaking its personality.
                        </div>
                    </div>
                    <!-- Form -->
                    <form wire:submit.prevent="submitForm">

                        <div class="mb-3">
                            <label for="temperature" style="font-size: small;"
                                   class="form-label fw-bold text-orange">Temperature
                                (Randomness)</label>
                            <select
                                    class="form-control input-bg"
                                    wire:model.defer="temperature">
                                @for($i = 0.1; $i <= 1; $i += 0.1)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
{{--                            <input type="text" class="form-control "--}}
{{--                                   style="font-size: small; background-color: #8bb1ab; color: black !important;"--}}
{{--                                   name="temperature"--}}
{{--                                    wire:model.defer="temperature">--}}
                            <small class="form-text text-muted">
                                Amount of randomness injected into the response. Ranges from
                                0
                                to 1.5. Use closer to 0 for analytical/multiple choice, and
                                closer to 1 for creative and generative tasks.
                            </small>
                        </div>

                        <!-- Max Token Setting -->
                        <div class="mb-3">
                            <label for="temperature" style="font-size: small;"
                                   class="form-label fw-bold text-orange">Max Token</label>
                            <select
                                    class="form-control input-bg"
                                    wire:model.defer="max_token">
                                @for($i = 500; $i <= 5000; $i += 500)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Chunks Setting -->
                        <div class="mb-3">
                            <label for="temperature" style="font-size: small;"
                                   class="form-label fw-bold text-orange">Chunk</label>
                            <select class="form-control input-bg"
                                    wire:model.defer="chunk">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="temperature" style="font-size: small;"
                                   class="form-label fw-bold text-orange">LLM Models</label>
                            <select class="form-control input-bg"
                                    wire:model.defer="model_type">
                                <option value="1">gpt-4o-mini</option>
                                <option value="2">gpt-4o</option>
                                <option value="3">Claude 3.5 Sonnet</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="temperature" style="font-size: small;"
                                   class="form-label fw-bold text-orange">Plan</label>
                            <select class="form-control input-bg"
                                    wire:model.defer="plan_id">
                                    <option value="">Select plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                save
                                <span wire:loading wire:target="submitForm" class="swal2-loader" style="font-size: 8px;">
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
