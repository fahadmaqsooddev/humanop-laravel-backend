<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg mt-4 left px-1 shadow-none border-radius-xl z-index-sticky"
    id="navbarBlur" data-scroll="true">

    <div class="navbar-background-color d-flex align-items-end justify-content-end" style="padding: 10px;">

        <div class="container-fluid py-1 px-3 d-flex">

            <div class="d-none d-lg-flex flex-2 abc ps-5 mx-auto">
                <div class="col-auto">
                    <div class="avatar avatar-xl avatar-icon  ">
                        <img src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                             height="80" alt="profile_image" class="w-100 border-radius-lg shadow-sm  ">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="h-100 my-4">
                        <a href="javascript:void(0)">
                            <h5 class="mb-1 custom-text-dark">
                                {{Auth::user()['first_name']}} {{Auth::user()['last_name']}}
                            </h5>
{{--                            <p class="mb-0 font-weight-bold text-sm text-white">--}}
{{--                                Optimal Trait To Be In Right Now:--}}
{{--                            </p>--}}
{{--                            <p class="text-white word-break text-sm col-12"> trait (Thinking) For--}}
{{--                                Strategy and Problem--}}
{{--                                Solving--}}
{{--                                Activities</p>--}}
                        </a>
                    </div>
                </div>

            </div>

            <div class="nav nav-pills  nav-fill bg-transparent position-static  user-pannel-btn mx-auto"
                 role="tablist">

                <div class="nav-item pt-2">

                    @if(\App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::IS_ADMIN || \App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::SUB_ADMIN)

                        <a href="{{route('assessments')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                           class="btn-sm-1 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">Access Your Results
                        </a>

                    @elseif(\App\Helpers\Helpers::getWebUser()?->assessments()?->where('page', 0)?->count() > 0)

                        <a href="{{route('user_profile_overview')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                           class="btn-sm-1 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">Access Your Results
                        </a>

                    @else

                        <a href="javascript:void(0)" style="padding: 10px 16px 10px 16px; border-radius: 7px; background-color: grey"
                           data-toggle="tooltip" data-placement="top" title="Take the assessment first"
                           class="text-white btn-sm-1 btn-md-3 btn-lg-5 ">Access Your Results
                        </a>

                    @endif
                </div>

                <div class="nav-item pt-2">
                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                            class="rainbow-border-user-nav-btn ms-2 btn-sm-2 btn-md-3 btn-lg-5 " data-bs-toggle="modal" data-bs-target="#qrCodeModal"  >Get Free Pro Access!
                    </button>
                </div>
            </div>


        </div>

    </div>
</nav>
<!-- End Navbar -->
{{--QR Code Modal--}}
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog"
     aria-labelledby="qrCodeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-qrcode-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h3 class="text-white text-center">Save and share your custom HumanOP</h3>
                                    <h3 class="text-white text-center">QR code to get Free Pro Access</h3>
                                </div>


                            </div>
                            <div>
                                <div class="text-center" id="qrCodeContainer">
                                    {{ SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/register?ref=' .\App\Helpers\Helpers::getWebUser()->referral_code)) }}
                                </div>


                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success" id="downloadButton" style="margin-top: 20px;">Save
                                        QR Code
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex">
                                <input type="text" class="form-control w-80"
                                       style="background-color: #0f1534;border-radius: 5px 0px 0px 5px;border-right: none"
                                       value="{{url('/register?ref=' .\App\Helpers\Helpers::getWebUser()->referral_code)}}"
                                       readonly="">
                                <button class="btn mb-0 text-white w-20" id="copy_link"
                                        onclick="copyToClipboard('{{ url('/register?ref=' . \App\Helpers\Helpers::getWebUser()->referral_code) }}')"
                                        style="background-color: #f2661c;border-radius: 0px 5px 5px 0px">Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--Qr code End Here--}}

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
                $('#nav-toggle-btn').css('margin-left', '266px');
            }
        } else {
            icon.classList.remove('fa-angle-right');
            icon.classList.add('fa-angle-left');
            if ($(window).width() <= 1200) {
                $('#nav-toggle-btn').css('margin-left', '266px');
            } else {
                $('#nav-toggle-btn').css('margin-left', '96px');

            }
        }
    });

    $(document).ready(function () {
        $('.sidenav').hover(
            function () {
                if (icon.classList.contains('fa-angle-left')) {

                    $('#nav-toggle-btn').css('margin-left', '251px');
                }
            },
            function () {
                if (icon.classList.contains('fa-angle-left')) {
                    if ($(window).width() <= 1200) {
                        $('#nav-toggle-btn').css('margin-left', '0px');
                    } else {
                        $('#nav-toggle-btn').css('margin-left', '96px');
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
                $('#nav-toggle-btn').css('margin-left', '266px');
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


    async function copyToClipboard(text) {

        try {
            // Use the Clipboard API to copy the text
            await navigator.clipboard.writeText(text);

            $('#copy_link').text('Copied!')
            // Hide the tooltip after 2 seconds
            setTimeout(() => {
                setTimeout(() => {
                    $('#copy_link').text('Copy Link')
                }, 300);  // Match the fade-out duration
            }, 2000);

        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
    }


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
