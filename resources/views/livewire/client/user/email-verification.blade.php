<div wire:ignore.self class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-7 mx-auto">
            <div class="card z-index-0" style="background-color: #F3DEBA">
                <div class="card-header text-center pt-4">
                    @include('layouts.message')

                    <div class="card-body">
                        <form wire:submit.prevent="emailVerification">
                            @csrf

                            <h2 class="mb-2" style="color: #f2661c">Email Verification</h2>
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">Thanks for signing up!</p>
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">Before we get you started,
                                please verify your email by clicking on the link we just emailed you.</p>
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">If you didn't receive the
                                email, and it’s not in your spam, we will gladly send you another by clicking the link
                                below.</p>
                            <input type="text" wire:model="token" hidden class="text-black-50">
                            <button type="submit" class="btn w-100 mt-4 mb-0 text-white"
                                    style="background-color: #f2661c !important;" id="submitButton">
                                Please Resend Verification Email
                            </button>
                            <p id="timerDisplay" style="margin-top: 10px; display: none; color: #f2661c;"></p>
                        </form>
                    </div>
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
