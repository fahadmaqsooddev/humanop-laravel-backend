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
    <div class="page-header align-items-start min-vh-50 pb-11 m-3 border-radius-lg">
        <div class="container">
            <div class="row d-flex flex-column justify-content-center">
                <div class="col-lg-5 text-center mx-auto">
                    <img src="<?php echo e(URL::asset('assets/logos/HumanOp Logo.png')); ?>"
                         style="margin-left: 30px; margin-top: 15px;width: 80%; height: 80%;" alt="main_logo">
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
                            <h2 class="mb-2" style="color: #f2661c">Email Verification</h2>
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">Thanks for signing up!</p>
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">Before we get you started,
                                please verify your email by clicking on the link we just emailed you.</p>
                            <p class="d-flex text-justify text-bold" style="color: #0f1534">If you didn't receive the
                                email, and it’s not in your spam, we will gladly send you another by clicking the link
                                below.</p>
                            <a href="{{route('resend_email_verification')}}" style="color: #f2661c"
                               class="float-start text-bold">“Please Resend Verification Email”</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

</body>

</html>
