@if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1 || \App\Helpers\Helpers::getWebUser()['is_admin'] === 3)
    <nav class="navbar navbar-main navbar-expand-lg left px-1 shadow-none border-radius-xl z-index-sticky"
         style="background-color: white; border-radius: 0px !important; padding: 0px !important;"
         id="navbarBlur" data-scroll="true">
        <div class="navbar-background-color d-flex"
             style="justify-content: center; align-items: center; margin: auto; width: 100%;">
            <div class="d-flex "
                 style="width: 100%; border-radius: 0px !important;" data-step="4">
                <div class="container-fluid py-1 px-3 d-flex"
                     style="justify-content: center; padding: 10px !important;">
                    <div class="d-none d-lg-flex flex-2 abc ps-5 mx-auto">
                        <div class="col-auto" style="margin: auto">
                            <div class="avatar avatar-xl avatar-icon  ">
                                <img
                                    src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                                    height="80" alt="profile_image"
                                    class="w-100 border-radius-lg shadow-sm  user_profile_image">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="h-100">
                                <a href="javascript:void(0)">
                                    <h3 class="mb-1 custom-text-dark {{!empty($traitDescription['public_name']) ? '' : 'my-3'}}">
                                        {{Auth::user()['first_name']}} {{Auth::user()['last_name']}}
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{--                    <div class="nav nav-pills  nav-fill bg-transparent position-static  user-pannel-btn"--}}
                    {{--                         role="tablist">--}}
                    {{--                        <div class="nav-item pt-2">--}}
                    {{--                            <a href="{{route('assessments')}}"--}}
                    {{--                               style="padding: 10px 16px 10px 16px; border-radius: 7px;background:#1B3A62 !important;color:white;font-weight:bolder;border:none;"--}}
                    {{--                               class="btn-sm-1 btn-md-3 btn-lg-5  navButtonResponsive">Access Latest Results--}}
                    {{--                            </a>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="nav-item pt-2" style="margin-left: 5px">--}}
                    {{--                            <button--}}
                    {{--                                style="padding: 10px 16px 10px 16px; border-radius: 7px;background:#1B3A62;color:white;font-weight:bolder;border:none;"--}}
                    {{--                                class=" btn-sm-2 btn-md-3 btn-lg-5  navButtonResponsive btnMarginAdd"--}}
                    {{--                                data-bs-toggle="modal"--}}
                    {{--                                data-bs-target="#qrCodeModal">Get Free Pro Access!--}}
                    {{--                            </button>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
                <img src="{{ asset('assets/img/beta2.png') }}" class="float-end" height="100" alt="profile_image">
            </div>
        </div>
    </nav>
@endif
<div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog"
     aria-labelledby="qrCodeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px; border: 2px solid #1B3A62">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-qrcode-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="d-flex justify-content-center">
                                <div>
                                    <h3 class="text-center" style="color: #1B3A62">Save and share your custom
                                        HumanOP</h3>
                                    <h3 class="text-center" style="color: #1B3A62">QR code to get Free Pro Access</h3>
                                </div>
                            </div>
                            <div>
                                @if(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                                    <div class="text-center" id="qrCodeContainer">
                                        {{ SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate( \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('register?ref=' . \App\Helpers\Helpers::getWebUser()->referral_code) ) }}
                                    </div>
                                @else
                                    <div class="text-center" id="qrCodeContainer">
                                        {{ SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/register?ref=' .\App\Helpers\Helpers::getWebUser()->referral_code)) }}
                                    </div>
                                @endif
                                <div class="d-flex justify-content-center">
                                    <button class="btn" id="downloadButton"
                                            style="margin-top: 20px; background-color: #1B3A62; color: white">Save QR
                                        Code
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex">
                                <input type="text" class="form-control w-80"
                                       style="background-color: white;border-radius: 5px 0px 0px 5px;border-right: none"
                                       value="{{url('/register?ref=' .\App\Helpers\Helpers::getWebUser()->referral_code)}}"
                                       readonly="">
                                <button class="btn mb-0 text-white w-20" id="copy_link"
                                        onclick="copyToClipboard('{{ url('/register?ref=' . \App\Helpers\Helpers::getWebUser()->referral_code) }}')"
                                        style="background-color: #1B3A62;border-radius: 0px 5px 5px 0px">Copy Link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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

    function goToProfileOverviewPage(src, content_name) {

        window.location.href = "{{url('/client/user-profile-overview') . "?video_url="}}" + src + "&contentName=" + content_name;
    }

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
