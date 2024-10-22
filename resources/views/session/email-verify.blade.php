<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <title>HumanOp Tech</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          crossorigin="anonymous">
    <link id="pagestyle" href="{{ URL::asset('assets/css/soft-ui-dashboard.css?v=1.0.4') }}" rel="stylesheet"/>

    @livewireStyles
</head>

<body style="background-color: #2C4C7E">

<main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg">
        <div class="container">
            <div class="row mt-5 d-flex flex-column justify-content-center">
                <div class="col-lg-5 text-center mx-auto">
                    <h1 class="text-white mb-2 mt-5">Email Verification</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-7 mx-auto">
                <div class="card z-index-0" style="background-color: #F3DEBA">
                    <div class="card-header text-center pt-4">
                        <div class="card-body">
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">Thanks for signing
                                up! Before getting started, could you verify your email address by
                                clicking on the link we just emailed to you? if you didn't receive the email, we
                                will gladly send you another.</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{route('resend_email_verification')}}"
                                   class="btn my-4 mb-2 text-white" style="background-color: #f2661c;">RESEND
                                    VERIFICATION EMAIL</a>
                                <a href="{{route('call_back_registration')}}" class="btn my-4 mb-2 text-white"
                                   style="background-color: #f2661c;"><i class="fa-solid fa-arrow-left"></i>
                                    Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

</body>

</html>
