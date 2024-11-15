<div class="card setting-box-background mt-4" id="setting">
    <div class="card-header">
        <div class="card">
            @include('layouts.message')
            <div class="card-body">
                <div class="d-flex ">
                    <div class="col-md-4">
                        <div class="card-title fw-bold" style="color: #0f1535">Personalize</div>
                        <div class="card-text" style="color: #0f1535">Customize your chatbot by giving
                            it a name
                            and avatar
                            and tweaking its personality.
                        </div>
                    </div>
                    <!-- Form -->
                    <form wire:submit.prevent="submitForm">

                        <div class="mb-3">
                            <label for="temperature" style="font-size: small; background-color: #8bb1ab"
                                   class="form-label fw-bold">Temperature
                                (Randomness)</label>
                            <input type="text" class="form-control "
                                   style="font-size: small; background-color: #8bb1ab; color: black !important;"
                                   name="temperature"
                                    wire:model="temperature">
                            <small class="form-text text-muted">
                                Amount of randomness injected into the response. Ranges from
                                0
                                to 1.5. Use closer to 0 for analytical/multiple choice, and
                                closer to 1 for creative and generative tasks.
                            </small>
                        </div>

                        <!-- Max Token Setting -->
                        <div class="mb-3">
                            <label for="temperature" style="font-size: small; background-color: #8bb1ab"
                                   class="form-label fw-bold">Max Token</label>
                            <select style="background-color: #0f1535"
                                    class="form-control text-color-dark setting-box-background"
                                    wire:model.defer="max_token">
                                @for($i = 500; $i <= 5000; $i += 500)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Chunks Setting -->
                        <div class="mb-3">
                            <label for="temperature" style="font-size: small; background-color: #8bb1ab"
                                   class="form-label fw-bold">Chunk</label>
                            <select style="background-color: #0f1535"
                                    class="form-control text-color-dark setting-box-background"
                                    wire:model.defer="chunk">
                                @for($i = 1; $i <= 20; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                save
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
