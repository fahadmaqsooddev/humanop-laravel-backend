<div wire:ignore.self class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-7 mx-auto">
            <div class="card z-index-0 left-nav-blue-color">
                <div class="card-header pb-0 text-left">
                    <h3 style="color: #0f1535;">Reset Password</h3>
                    <p class="mb-0" style="color: #0f1535;">You will receive an e-mail in maximum 60 seconds</p>
                </div>
                @include('layouts.message')

                <div class="card-body">
                    <form wire:submit.prevent="resetPassword">
                        @csrf
                        <div>
                            <label style="color: #0f1535; font-size: 15px">Email</label>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Enter your e-mail"
                                       aria-label="Email"
                                       style="background-color: #f3deba; color: black; border-radius: 15px;"
                                       aria-describedby="email-addon" wire:model="email"
                                       value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn w-100 mt-4 mb-0 text-white"
                                    style="background-color: #1b3a62 !important;" id="submitButton">
                                Reset
                            </button>
                            <p id="timerDisplay" style="margin-top: 10px; display: none; color: #1b3a62;"></p>
                            <div class="mt-3" style="color: #0f1535;">
                                Already have an account?
                                <a href="{{url('login')}}" style="color: #0f1535; font-weight: bold">Sign in</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('startTimer', function () {
            let timer = 60; // 1 minute
            const button = document.getElementById('submitButton');
            const timerDisplay = document.getElementById('timerDisplay');

            button.disabled = true;
            timerDisplay.style.display = 'block';

            const interval = setInterval(() => {
                timer--;
                timerDisplay.textContent = `Please wait ${timer} seconds before resubmitting.`;

                if (timer <= 0) {
                    clearInterval(interval);
                    timerDisplay.style.display = 'none';
                    button.disabled = false;
                }
            }, 1000);
        });
    });

</script>
