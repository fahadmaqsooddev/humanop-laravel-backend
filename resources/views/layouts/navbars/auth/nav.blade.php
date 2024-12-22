<!-- Navbar -->
@if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1 || \App\Helpers\Helpers::getWebUser()['is_admin'] === 3)
<nav class="navbar navbar-main navbar-expand-lg left px-1 shadow-none border-radius-xl z-index-sticky"
     style="background-color: #8BB1AB; border-radius: 0px !important; padding: 0px !important;"
     id="navbarBlur" data-scroll="true">

        <div class="navbar-background-color d-flex align-items-end justify-content-end"
    <div class="d-flex "
         style="width: 100%; border-radius: 0px !important;"  data-step="4" >

        <div class="container-fluid py-1 px-3 d-flex" style="justify-content: center; padding: 10px !important;">

            <div class="d-none d-lg-flex flex-2 abc ps-5 mx-auto">
                <div class="col-auto" style="margin: auto">
                    <div class="avatar avatar-xl avatar-icon  ">
                        <img
                            src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                            height="80" alt="profile_image" class="w-100 border-radius-lg shadow-sm  user_profile_image">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="h-100">
                        <a href="javascript:void(0)">
                            <h5 class="mb-1 custom-text-dark {{!empty($traitDescription['public_name']) ? '' : 'my-3'}}">
                                {{Auth::user()['first_name']}} {{Auth::user()['last_name']}}
                            </h5>
                            @php
                                $user = \App\Helpers\Helpers::getWebUser();
                                $optionalTraitDetail = null;

                                if (!empty($user)) {
                                    $assessment = \App\Models\Assessment::getLatestAssessment($user['id']);

                                    if (!empty($assessment)) {
                                        $timezone = $user['timezone'];
                                        $topThreeStyles = \App\Models\Assessment::getAllStyles($assessment);
                                        $topFeatures = \App\Models\Assessment::getFeatures($assessment);
                                        $topTwoFeatures = \App\Models\Assessment::getTopTwoFeatures($topFeatures['top_two_keys'], $assessment);
                                        $optionalTrait = \App\Helpers\Helpers::getOptionalTrait($timezone, $topThreeStyles, $topTwoFeatures);
                                        $optionalTraitDetail = \App\Models\Admin\Code\CodeDetail::getOptionalTraitDetail($optionalTrait);
                                    }
                                }
                            @endphp

                            @if(!empty($optionalTraitDetail))
                                <p class="mb-0 font-weight-bold text-sm">
                                    Optimal Trait To Be In Right Now:
                                </p>
                                <h6 onclick="goToProfileOverviewPage('{{ $optionalTraitDetail[2] }}', 'style_{{ $optionalTraitDetail[0] }}')">
                                    <strong>{{ $optionalTraitDetail[0] }}</strong>
                                </h6>
                            @endif
                        </a>
                    </div>
                </div>

            </div>

            <div class="nav nav-pills  nav-fill bg-transparent position-static  user-pannel-btn"
                 role="tablist">

                <div class="nav-item pt-2">

                    @if(\App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::IS_ADMIN || \App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::SUB_ADMIN)

                        <a href="{{route('assessments')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                           class="btn-sm-1 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results
                        </a>

                    @elseif(\App\Helpers\Helpers::getWebUser()->assessments()->where('page', 0)->count() > 0)

                        @php
                            $userId = \App\Helpers\Helpers::getWebUser()['id'];

                            $assessment = \App\Models\Assessment::where('user_id', $userId)->where('page', 0)->latest()->first();

                        @endphp
                        @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4)
                            <a href="{{route('practitioner_profile_overview', $assessment['id'])}}"
                               style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                               class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results
                            </a>
                        @elseif(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                            <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('practitioner-client-profile-overview', ['id' => $assessment['id'] ]) }}"
                               style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                               class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results
                            </a>
                        @else
                            <a href="{{route('user_profile_overview', $assessment['id'])}}"
                               style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                               class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results
                            </a>
                        @endif

                    @else

                        <button
                            style="padding: 10px 16px 10px 16px; border-radius: 7px; background-color: grey;"
                            data-toggle="tooltip" data-placement="top" title="Take the assessment first"
                            class="text-white btn-sm-2 btn-md-3 btn-lg-5  navButtonResponsive">Access Latest Results
                        </button>

                    @endif
                </div>

                <div class="nav-item pt-2" style="margin-left: 5px">
                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                            class="rainbow-border-user-nav-btn btn-sm-2 btn-md-3 btn-lg-5  navButtonResponsive btnMarginAdd"
                            data-bs-toggle="modal"
                            data-bs-target="#qrCodeModal">Get Free Pro Access!
                    </button>
                </div>
            </div>
        </div>

{{--        <div class="betaTagDiv">--}}
{{--            <p class="betaTag">Beta</p>--}}
{{--        </div>--}}

                <img src="{{ asset('assets/img/beta2.png') }}" class="float-end" height="100" alt="profile_image">

    </div>
</nav>
@endif
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
                                    <button class="btn btn-success" id="downloadButton" style="margin-top: 20px;">Save
                                        QR Code
                                    </button>
                                </div>
                            </div>

                            @if(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                                <div class="d-flex">
                                    <input type="text" class="form-control w-80"
                                           style="background-color: #0f1534;border-radius: 5px 0px 0px 5px;border-right: none"
                                           value="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('register?ref=' . \App\Helpers\Helpers::getWebUser()->referral_code) }}"
                                           readonly="">
                                    <button class="btn mb-0 text-white w-20" id="copy_link"
                                            onclick="copyToClipboard('{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('register?ref=' . \App\Helpers\Helpers::getWebUser()->referral_code) }}')"
                                            style="background-color: #f2661c;border-radius: 0px 5px 5px 0px">Copy Link
                                    </button>
                                </div>
                            @else
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--Qr code End Here--}}


<!-- Wallet Modal -->
<div class="modal fade" id="humanOpWalletModal" tabindex="-1" role="dialog"
     aria-labelledby="humanOpWalletModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content py-3">

            <div class="p-2">
                <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="coins d-flex justify-content-center">
                    <img src="{{ asset('assets/img/coins.gif') }}" alt="Coins falling"
                         style="width: 100px; height: 150px; margin-top: -80px;">
                </div>
                <div class="d-flex justify-content-center">
                    <!-- Points Counter Circle -->
                    <div class="fw-bold display-5 d-flex align-items-center justify-content-center"
                         id="coin-count"
                         style="border-radius: 50%; height: 50px; width: 50px; font-size: 16px; border: 1px solid white; color: white; text-shadow: 0 0 5px #f2661c, 0 0 10px #f2661c; background-color: #f2661c; margin-right: -5px;;z-index:4">
                        <span>{{ Auth::user()['point'] }}</span>
                    </div>
                    <!-- Coins Label - extending from the circle -->
                    <div class="fw-bold display-5 d-flex align-items-center justify-content-center"
                         id="coin-label"
                         style="border-radius: 0px 40% 40% 0px; height: 40px;z-index:2; width: 70px; font-size: 16px; border: 1px solid #f2661c; color: #f2661c; background-color: white; margin-left: -4px;margin-top: 5px">
                        <span style="color: #f2661c;">HP</span>
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

    function goToProfileOverviewPage(src, content_name) {

        window.location.href = "{{url('/client/user-profile-overview') . "?video_url="}}" + src + "&contentName=" + content_name;
    }

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
