<div class="container">

    <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
        <div class="col-xl-6 col-lg-6 col-md-7 mx-auto">
            <div class="card z-index-0" style="background-color: #F3DEBA">
                <div class="card-header text-center">
                    @include('layouts.message')
                    <div class="card-body">
                        <h2 class="mb-2" style="color: #f2661c">Phone Verification</h2>
                        <p class="d-flex text-justify text-bold" style="color: #0f1534">Thanks for Login!</p>
                        <p class="d-flex text-justify text-bold" style="color: #0f1534">Before we get you started,
                            please verify your phone by Entering the verification code.</p>
                        <div class="d-flex justify-content-between">
                            <div class="input-group">
                                <input id="phone" wire:model="otp"
                                       maxlength="14" class="form-control text-color-dark setting-box-background"
                                       type="tel" placeholder="Enter code">
                            </div>
                            <button class="btn-sm float-end mb-0 rainbow-border-user-nav-btn" wire:click="verifyOtp()"
                                    style="margin-left: 5px">
                                Verify
                            </button>
                        </div>
                        <p class="d-flex text-justify text-bold mt-2" style="color: #0f1534">If you didn't receive the
                            Phone Verification Code,click the link
                            below.</p>

                        <a href="javascript:void(0);" wire:click="resendOtp()" style="color: #f2661c"
                           class="float-start text-bold">“Please Resend Phone Verification Code”</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
