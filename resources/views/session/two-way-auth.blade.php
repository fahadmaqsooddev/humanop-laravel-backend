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
<style>
    .rainbow-border-user-nav-btn {
        display: inline-block;
        padding: 10px 30px;
        font-size: 16px;
        font-weight: bold;
        color: black !important;
        background-color: transparent;
        border: 2px solid transparent;
        border-radius: 8px;
        background-image: linear-gradient(#F3DEBA, #F3DEBA), linear-gradient(90deg, rgb(146, 11, 11), orange, yellow, rgb(22, 200, 22), rgb(0, 238, 255), rgb(26, 58, 222), rgb(4, 19, 113));
        background-clip: padding-box, border-box;
        background-origin: border-box;
        cursor: pointer;
        transition-property: background-image;
        transition-duration: 2s;
        transition-timing-function: linear;
        transition-delay: 0;
    }
    .rainbow-border-user-nav-btn:hover {
        animation: changeUserNavGradient 1s linear infinite alternate;
        color: black !important;
    }

    @keyframes changeUserNavGradient {
        from {
            background-image: linear-gradient(#F3DEBA, #F3DEBA), linear-gradient(90deg, rgb(146, 11, 11), orange, yellow, rgb(22, 200, 22), rgb(0, 238, 255), rgb(26, 58, 222), rgb(4, 19, 113));
        }

        to {
            background-image: linear-gradient(#F3DEBA, #F3DEBA), linear-gradient(135deg, rgb(146, 11, 11), orange, yellow, rgb(22, 200, 22), rgb(0, 238, 255), rgb(26, 58, 222), rgb(4, 19, 113));
        }
    }
</style>
<body style="background-color: #2C4C7E">

<main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-50 pb-11 border-radius-lg">
        <div class="container">
            <div class="row d-flex flex-column justify-content-center">
                <div class="col-lg-5 text-center mx-auto">
                    <img src="<?php echo e(URL::asset('assets/logos/HumanOp Logo.png')); ?>"
                         style="margin-left: 30px; margin-top: 15px;width: 80%; height: 80%;" alt="main_logo">
                </div>
            </div>
        </div>
    </div>
    @livewire('client.verification.phone-verification')

</main>

</body>
<script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
<livewire:scripts />
</html>
