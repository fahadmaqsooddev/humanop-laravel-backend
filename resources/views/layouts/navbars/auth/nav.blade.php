@if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1 || \App\Helpers\Helpers::getWebUser()['is_admin'] === 3)

    <style>
        .nav-link:hover {
            border: none;
        }

        .nav-link:focus{
            border: none;
        }
    </style>

    <nav class="navbar bg-white" style="height: 40px;">

        <div style="padding-right: 30px;" class="w-100">
            <div class="d-flex justify-content-end">

                <ul class="nav nav-tabs gap-3">
                    <li class="nav-item dropdown" style="position: relative; top: -6px; left: 25px;">
                        <button class="nav-link dropdown-toggle p-2" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <i class="fa-solid fa-gear custom-text-dark"></i>
                        </button>
                        <ul class="dropdown-menu p-2">
                            <li class="mt-2">
                                {{\Illuminate\Support\Facades\Auth::user()['first_name'] . ' ' . \Illuminate\Support\Facades\Auth::user()['last_name']}}
                            </li>
                            <li class="mt-2">
                                {{\Illuminate\Support\Facades\Auth::user()['email']}}
                            </li>
                            <li style="border: 1px solid gray;" class="mt-2"></li>
                            <li class="mt-2">
                                <a href="{{route('logout')}}" class="custom-text-dark">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <i class="fa-solid fa-user"></i>
                    </li>
                    <li>
                        <a href="{{route('logout')}}" class="custom-text-dark">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </a>
                    </li>
                </ul>

            </div>

        </div>

    </nav>

    {{--    <div class="container-fluid">--}}

    {{--        <nav class="navbar w-100 "--}}
    {{--             style="background-color: white; border-radius: 0 !important; height: 40px; width: 160%;"--}}
    {{--             id="navbarBlur" data-scroll="true">--}}

    {{--        </nav>--}}

    {{--    </div>--}}

@endif

<script>

    const button = document.getElementById('nav-toggle');

    const icon = document.getElementById('nav-toggle-icon');

    button.addEventListener('click', () => {
        if (icon.classList.contains('fa-angle-left')) {
            icon.classList.remove('fa-angle-left');
            icon.classList.add('fa-angle-right');
            if ($(window).width() <= 1200) {
                $('#nav-toggle-btn').css('margin-left', '0px');
            } else {
                $('#nav-toggle-btn').css('margin-left', '290px');
            }
        } else {
            icon.classList.remove('fa-angle-right');
            icon.classList.add('fa-angle-left');
            if ($(window).width() <= 1200) {
                $('#nav-toggle-btn').css('margin-left', '290px');
            } else {
                $('#nav-toggle-btn').css('margin-left', '125px');

            }
        }
    });

    $(document).ready(function () {
        $('.sidenav').hover(
            function () {
                if (icon.classList.contains('fa-angle-left')) {

                    $('#nav-toggle-btn').css('margin-left', '290px');
                }
            },
            function () {
                if (icon.classList.contains('fa-angle-left')) {
                    if ($(window).width() <= 1200) {
                        $('#nav-toggle-btn').css('margin-left', '0px');
                    } else {
                        $('#nav-toggle-btn').css('margin-left', '125px');
                    }
                }
            }
        );
    });

    $(document).ready(function () {
        let resizeTimeout;

        function checkWidth() {
            if ($(window).width() <= 1200) {
                $('body').removeClass('g-sidenav-pinned').addClass('g-sidenav-hidden');
                $('#nav-toggle-btn').css('margin-left', '0px');
            } else {
                $('body').removeClass('g-sidenav-hidden').addClass('g-sidenav-pinned');
                $('#nav-toggle-btn').css('margin-left', '290px');
            }
        }

        // Initial check when the document is ready
        checkWidth();

        // Add event listener for window resize with debouncing
        $(window).resize(function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(checkWidth, 100); // Adjust the timeout as needed
        });
    });

    document.getElementById('downloadButton').addEventListener('click', function () {
        const svg = document.querySelector('#qrCodeContainer svg');
        const svgData = new XMLSerializer().serializeToString(svg);
        const blob = new Blob([svgData], {type: 'image/svg+xml'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'referral_qr_code.svg';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

</script>
