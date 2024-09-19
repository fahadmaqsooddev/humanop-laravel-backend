<aside id="#remove-scrollbar-nav" style="z-index: 999; !important;"
       class="sidenav sidenavHideClass navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3  {{ (\Request::is('pages-rtl') ? 'fixed-end me-3 rotate-caret' : 'fixed-start ms-3' ) }}"
       id="sidenav-main">
    <div class="d-flex">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
               aria-hidden="true" id="iconSidenav"></i>
            <a class="align-items-center d-flex m-0 text-wrap" href="{{ route('admin_dashboard') }}">
            <span class="humanopLogo">
    <img src="{{ URL::asset('assets/img/logo.png') }}" class="h-100" style="margin-left: 33px" alt="main_logo">
</span>
                <span class="humanopMiniLogo d-none">
    <img src="{{ URL::asset('assets/img/Human_OP.png') }}" class="h-100" style="margin-left: 10px; width: 77px"
         alt="main_logo">
</span>
            </a>
        </div>


    </div>


    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if(Auth::user()->hasAnyRole(['super admin', 'sub admin']) )
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#dashboardsExamples"
                       class="nav-link {{ ($parentFolder == 'dashboards' ? ' active' : '') }}"
                       aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>shop </title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF"
                                       fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(0.000000, 148.000000)">
                                                <path class="color-background"
                                                      d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z"
                                                      opacity="0.598981585"></path>
                                                <path class="color-background"
                                                      d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>

                        <span class="nav-link-text ms-1">Admin</span>
                    </a>
                    <div class="collapse {{ ($parentFolder == 'dashboards' ? ' show' : '') }}" id="dashboardsExamples">
                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item {{ (Request::is('admin-dashboard') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('admin-dashboard') ? 'active' : '') }}"
                                   href="{{ route('admin_dashboard') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                    <span class="sidenav-normal"> Dashboard </span>
                                </a>
                            </li>
                            @if(Auth::user()->hasRole('super admin'))
                                <li class="nav-item {{ (Request::is('sub-admins') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('sub-admins') ? 'active' : '') }}"
                                       href="{{ route('admin_all_sub_admins') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Sub Admin.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Sub Admin.png')}}"></span>
                                        <span class="sidenav-normal"> Sub Admins </span>
                                    </a>
                                </li>
                            @endif
                            @can('users')
                                <li class="nav-item {{ (Request::is('users') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('users') ? 'active' : '') }}"
                                       href="{{ route('admin_all_users') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Client.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Client.png')}}"></span>
                                        <span class="sidenav-normal"> Client </span>
                                    </a>
                                </li>
                            @endcan
                            @can('abandonedAssessment')
                                <li class="nav-item {{ (Request::is('abandoned-assessment') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('abandoned-assessment') ? 'active' : '') }}"
                                       href="{{ route('admin_abandoned_assessment') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                        <span class="sidenav-normal"> Abandoned Assessment </span>
                                    </a>
                                </li>
                            @endcan
                            @can('clientQueries')
                                <li class="nav-item {{ (Request::is('client-queries') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('client-queries') ? 'active' : '') }}"
                                       href="{{ route('admin_client_queries') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Client Queries.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Client Queries.png')}}"></span>
                                        <span class="sidenav-normal"> Client Queries </span>
                                    </a>
                                </li>
                            @endcan
                            @can('approveQueries')
                                <li class="nav-item {{ (Request::is('approve-queries') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('approve-queries') ? 'active' : '') }}"
                                       href="{{ route('admin_approve_queries') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Approved queries.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Approved queries.png')}}"></span>
                                        <span class="sidenav-normal"> Approve Queries </span>
                                    </a>
                                </li>
                            @endcan
                            @can('deletedClient')
                                <li class="nav-item {{ (Request::is('deleted-clients') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('deleted-clients') ? 'active' : '') }}"
                                       href="{{ route('deleted_clients') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Delete Client.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Delete Client.png')}}"></span>
                                        <span class="sidenav-normal"> Deleted Clients </span>
                                    </a>
                                </li>
                            @endcan
                            @can('questions')
                                <li class="nav-item {{ (Request::is('questions') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('questions') ? 'active' : '') }}"
                                       href="{{ route('admin_all_questions') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Question.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Question.png')}}"></span>
                                        <span class="sidenav-normal"> Questions </span>
                                    </a>
                                </li>
                            @endcan
                            @can('resources')
                                <li class="nav-item {{ (Request::is('admin_resources') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('admin_resources') ? 'active' : '') }}"
                                       href="{{ route('admin_resources') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/resourcee.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/resourcee.png')}}"></span>
                                        <span class="sidenav-normal"> Resources </span>
                                    </a>
                                </li>
                            @endcan
                            @can('cms')
                                <li class="nav-item ">
                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                       data-bs-toggle="collapse" aria-expanded="false" href="#vrExamples">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                        <span class="sidenav-normal"> CMS <b class="caret"></b></span>
                                    </a>
                                    <div class="collapse {{ ($childFolder == 'virtual' ? 'show' : '') }}"
                                         id="vrExamples">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::is('admin_manage_code') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin_manage_code') ? 'active' : '') }}"
                                                   href="{{ route('admin_manage_code') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                    <span class="sidenav-normal"> Codes Manage </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                                   href="{{ url('#') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                    <span class="sidenav-normal"> Video Buckets </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('cms') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('cms') ? 'active' : '') }}"
                                                   href="{{ route('admin_web_pages') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Website page.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Website page.png')}}"></span>
                                                    <span class="sidenav-normal"> Web Pages </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('dashboard-cms') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('dashboard-cms') ? 'active' : '') }}"
                                                   href="{{ route('admin_cms') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Asset Management.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Asset Management.png')}}"></span>
                                                    <span class="sidenav-normal"> Assets Management </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                                   href="{{ url('#') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>
                                                    <span class="sidenav-normal"> Resources Content </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/all-coupons') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/all-coupons') ? 'active' : '') }}"
                                                   href="{{ route('admin_all_coupon') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"> Coupons </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/payment-history') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/payment-history') ? 'active' : '') }}"
                                                   href="{{ route('admin_payment_history') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Payment.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Payment.png')}}"></span>
                                                    <span class="sidenav-normal"> Payment History </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/feedback') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/feedback') ? 'active' : '') }}"
                                                   href="{{ route('feedback') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                    <span class="sidenav-normal"> User Feedback </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/podcast') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/podcast') ? 'active' : '') }}"
                                                   href="{{ route('podcast') }}">
                                                    <span class="sidenav-mini-icon"> P </span>
                                                    <span class="sidenav-normal"> Podcast </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endcan
                            @can('chat')
                                <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                       href="{{ route('admin_hai_chat') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Chat.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Chat.png')}}"></span>
                                        <span class="sidenav-normal"> H.A.I. Chat </span>
                                    </a>
                                </li>
                            @endcan
                            @can('projects')
                                <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                       href="{{ route('admin_projects') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Project.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Project.png')}}"></span>
                                        <span class="sidenav-normal">Projects</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item {{ (Request::is('settings') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('settings') ? 'active' : '') }}"
                                   href="{{ route('admin_setting') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"> Setting </span>
                                </a>
                            </li>
                            <li class="nav-item rounded sign-out-btn me-3 mt-3">
                                <a class="nav-link"
                                   href="{{ url('/logout')}}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal text-bold  "> Sign Out </span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @elseif(\Illuminate\Support\Facades\Auth::user()->is_admin == 2 )
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#clientdashboardids"
                       class="nav-link {{ ($parentFolder == 'client-dashboard' ? ' active' : '') }}"
                       aria-controls="clientdashboardids" role="button" aria-expanded="false">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <svg width="12px" height="12px" viewBox="0 0 40 44" version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>document</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF"
                                       fill-rule="nonzero">
                                        <g transform="translate(1716.000000, 291.000000)">
                                            <g transform="translate(154.000000, 300.000000)">
                                                <path class="color-background"
                                                      d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"
                                                      opacity="0.603585379"></path>
                                                <path class="color-background"
                                                      d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="nav-link-text ms-1">Client</span>
                    </a>
                    <div class="collapse {{ ($parentFolder == 'client-dashboard' ? ' show' : '') }}"
                         id="clientdashboardids">
                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item {{ (Request::is('client/dashboard')  ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/dashboard')  ? 'active' : '') }}"
                                   href="{{ route('client_dashboard') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                    <span class="sidenav-normal"> Dashboard </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/stripe-checkout')  ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/stripe-checkout')  ? 'active' : '') }}"
                                   href="{{ route('stripe_checkout') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/assessment.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/assessment.png')}}"></span>
                                    <span class="sidenav-normal"> Assessment </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/all-assessments')  ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/all-assessments')  ? 'active' : '') }}"
                                   href="{{ route('all_assessment') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Results.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Results.png')}}"></span>
                                    <span class="sidenav-normal"> Results </span>
                                </a>
                            </li>

                            <li class="nav-item {{ (Request::is('client/resource') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/resource') ? 'active' : '') }}"
                                   href="{{ route('resource') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>
                                    <span class="sidenav-normal"> Library Resources </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                   data-bs-toggle="collapse" aria-expanded="false" href="#vrExamples">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Human Network.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Human Network.png')}}"><b
                                            class="caret"></b></span>
                                    <span class="sidenav-normal"> Human Network <b class="caret"></b></span>
                                </a>
                                <div class="collapse {{ ($childFolder == 'virtual' ? 'show' : '') }}"
                                     id="vrExamples">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item {{ (Request::is('connections') ? 'active' : '') }}">
                                            <a class="nav-link {{ (Request::is('connections') ? 'active' : '') }}"
                                               href="{{ route('connections', ['type' => 'connection']) }}">
                                                <span class="sidenav-mini-icon"><img
                                                        style="width: 18px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Find and Connect.png')}}"></span>
                                                <span class="sidenav-normal"><img
                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Find and Connect.png')}}"></span>
                                                <span class="sidenav-normal">Find Connects</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ (Request::is('client/messages') ? 'active' : '') }}">
                                            <a class="nav-link {{ (Request::is('client/messages') ? 'active' : '') }}"
                                               href="{{ route('messages') }}">
                                                <span class="sidenav-mini-icon"><img
                                                        style="width: 18px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Messages.png')}}"></span>
                                                <span class="sidenav-normal"><img
                                                        style="width: 18px;margin-left: 28px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Messages.png')}}"></span>
                                                <span class="sidenav-normal"> Messages </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ (Request::is('client/connections') ? 'active' : '') }}">
                                            <a class="nav-link {{ (Request::is('client/connections') ? 'active' : '') }}"
                                               href="{{ route('connections', ['type' => 'request']) }}">
                                                <span class="sidenav-mini-icon text-xs"><img
                                                        style="width: 18px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Connection Request.png')}}"></span>
                                                <span class="sidenav-normal"><img
                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Connection Request.png')}}"></span>
                                                <span class="sidenav-normal"> Connection Request </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ (Request::is('client/follow') ? 'active' : '') }}">
                                            <a class="nav-link {{ (Request::is('client/follow') ? 'active' : '') }}"
                                               href="{{ route('follow', ['type' => 'follower']) }}">
                                                <span class="sidenav-mini-icon"><img
                                                        style="width: 18px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Followers.png')}}"></span>
                                                <span class="sidenav-normal"><img
                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Followers.png')}}"></span>
                                                <span class="sidenav-normal"> Followers </span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ (Request::is('client/follow') ? 'active' : '') }}">
                                            <a class="nav-link {{ (Request::is('client/follow') ? 'active' : '') }}"
                                               href="{{ route('follow', ['type' => 'following']) }}">
                                                <span class="sidenav-mini-icon text-xs"><img
                                                        style="width: 18px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Following.png')}}"></span>
                                                <span class="sidenav-normal"><img
                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                        src="{{URL::asset('assets/icons/Following.png')}}"></span>
                                                <span class="sidenav-normal"> Following </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="nav-item {{ (Request::is('client/newsfeed') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/newsfeed') ? 'active' : '') }}"
                                   href="{{ route('newsfeed') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/NewsFeed.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/NewsFeed.png')}}"></span>
                                    <span class="sidenav-normal"> Newsfeed </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/pricing') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/pricing') ? 'active' : '') }}"
                                   href="{{ route('client_pricing') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Pricing.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Pricing.png')}}"></span>
                                    <span class="sidenav-normal"> Pricing </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/billing') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/billing') ? 'active' : '') }}"
                                   href="{{ route('billing') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/billing.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/billing.png')}}"></span>
                                    <span class="sidenav-normal"> Billing </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/payment-history') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/payment-history') ? 'active' : '') }}"
                                   href="{{ route('payment_history') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Payment.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Payment.png')}}"></span>
                                    <span class="sidenav-normal"> Payment History </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/setting') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/setting') ? 'active' : '') }}"
                                   href="{{ route('setting') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"> Setting </span>
                                </a>
                            </li>

                            <li class="nav-item rounded sign-out-btn me-3 mt-3">
                                <a class="nav-link"
                                   href="{{ url('/logout')}}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal text-bold  "> Sign Out </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <div class="abc" style="text-align: center">
                                @if(Auth::user()['is_admin'] == 2)
                                    <!-- Falling Coins GIF -->
                                        <div class="coins">
                                            <img src="{{ asset('assets/img/coins.gif') }}" alt="Coins falling"
                                                 style="width: 100px; height: 100px; margin-top: -15px;">
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <!-- Points Counter Circle -->
                                            <div class="fw-bold display-5 d-flex align-items-center justify-content-center" id="coin-count"
                                                 style="border-radius: 50%; height: 50px; width: 50px; font-size: 16px; border: 1px solid white; color: white; text-shadow: 0 0 5px #f2661c, 0 0 10px #f2661c; background-color: #f2661c; margin-right: -5px;">
                                                <span>{{ Auth::user()['point'] }}</span>
                                            </div>
                                            <!-- Coins Label - extending from the circle -->
                                            <div class="fw-bold display-5 d-flex align-items-center justify-content-center" id="coin-label"
                                                 style="border-radius: 0px 40% 40% 0px; height: 40px;z-index:-1; width: 70px; font-size: 16px; border: 1px solid #f2661c; color: #f2661c; background-color: white; margin-left: -4px;margin-top: 5px">
                                                <span style="color: #f2661c;">coins</span>
                                            </div>
                                        </div>

                                    @endif
                                </div>
                            </li>


                        </ul>
                    </div>
                </li>
        </ul>
    </div>

    </li>
    @else
        <li class="nav-item">
            <a data-bs-toggle="collapse" href="#practitionerdashboardids"
               class="nav-link {{ ($parentFolder == 'practitioner-dashboard' ? ' active' : '') }}"
               aria-controls="practitionerdashboardids" role="button" aria-expanded="false">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                    <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>office</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF"
                               fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g id="office" transform="translate(153.000000, 2.000000)">
                                        <path class="color-background"
                                              d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"
                                              opacity="0.6"></path>
                                        <path class="color-background"
                                              d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Practitioner</span>
            </a>
            <div class="collapse  {{ ($parentFolder == 'practitioner-dashboard' ? 'show' : '') }}"
                 id="practitionerdashboardids">
                <ul class="nav ms-4 ps-3">
                    <li class="nav-item {{ (Request::is('practitioner-database')  ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('practitioner-database')  ? 'active' : '') }}"
                           href="{{ url('practitioner-dashboard') }}">
                            <span class="sidenav-mini-icon"> D </span>
                            <span class="sidenav-normal"> Dashboard </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">
                            <span class="sidenav-mini-icon"> L </span>
                            <span class="sidenav-normal"> License Status </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('practitioner-projects') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('practitioner-projects') ? 'active' : '') }}"
                           href="{{ url('practitioner-projects') }}">
                            <span class="sidenav-mini-icon"> P </span>
                            <span class="sidenav-normal"> Projects </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('practitioner-new-user') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('practitioner-new-user') ? 'active' : '') }}"
                           href="{{ url('practitioner-new-user') }}">
                            <span class="sidenav-mini-icon"> N </span>
                            <span class="sidenav-normal"> New Users </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('practitioner-total-sales') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('practitioner-total-sales') ? 'active' : '') }}"
                           href="{{ url('practitioner-total-sales') }}">
                            <span class="sidenav-mini-icon"> T </span>
                            <span class="sidenav-normal"> Total Sales </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">
                            <span class="sidenav-mini-icon"> H </span>
                            <span class="sidenav-normal"> HAI Interface </span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::is('practitioner-billing') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('practitioner-billing') ? 'active' : '') }}"
                           href="{{ url('practitioner-billing') }}">
                            <span class="sidenav-mini-icon"> B </span>
                            <span class="sidenav-normal"> Billing </span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::is('practitioner-pages-account-settings') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('practitioner-pages-account-settings') ? 'active' : '') }}"
                           href="{{ url('practitioner-pages-account-settings') }}">
                            <span class="sidenav-mini-icon"> S </span>
                            <span class="sidenav-normal"> Setting </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a data-bs-toggle="collapse" href="#enterprisedashboardids"
               class="nav-link {{ ($parentFolder == 'enterprise-dashboard' ? ' active' : '') }}"
               aria-controls="enterprisedashboardids" role="button" aria-expanded="false">
                <div
                    class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                    <svg class="text-dark" width="12px" height="12px" viewBox="0 0 42 44" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>basket</title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1869.000000, -741.000000)" fill="#FFFFFF"
                               fill-rule="nonzero">
                                <g transform="translate(1716.000000, 291.000000)">
                                    <g id="basket" transform="translate(153.000000, 450.000000)">
                                        <path class="color-background"
                                              d="M34.080375,13.125 L27.3748125,1.9490625 C27.1377583,1.53795093 26.6972449,1.28682264 26.222716,1.29218729 C25.748187,1.29772591 25.3135593,1.55890827 25.0860125,1.97535742 C24.8584658,2.39180657 24.8734447,2.89865282 25.1251875,3.3009375 L31.019625,13.125 L10.980375,13.125 L16.8748125,3.3009375 C17.1265553,2.89865282 17.1415342,2.39180657 16.9139875,1.97535742 C16.6864407,1.55890827 16.251813,1.29772591 15.777284,1.29218729 C15.3027551,1.28682264 14.8622417,1.53795093 14.6251875,1.9490625 L7.919625,13.125 L0,13.125 L0,18.375 L42,18.375 L42,13.125 L34.080375,13.125 Z"
                                              opacity="0.595377604"></path>
                                        <path class="color-background"
                                              d="M3.9375,21 L3.9375,38.0625 C3.9375,40.9619949 6.28800506,43.3125 9.1875,43.3125 L32.8125,43.3125 C35.7119949,43.3125 38.0625,40.9619949 38.0625,38.0625 L38.0625,21 L3.9375,21 Z M14.4375,36.75 L11.8125,36.75 L11.8125,26.25 L14.4375,26.25 L14.4375,36.75 Z M22.3125,36.75 L19.6875,36.75 L19.6875,26.25 L22.3125,26.25 L22.3125,36.75 Z M30.1875,36.75 L27.5625,36.75 L27.5625,26.25 L30.1875,26.25 L30.1875,36.75 Z"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <span class="nav-link-text ms-1">Enterprise</span>
            </a>
            <div class="collapse {{ ($parentFolder == 'enterprise-dashboard' ? ' show' : '') }}"
                 id="enterprisedashboardids">
                <ul class="nav ms-4 ps-3">
                    <li class="nav-item {{ (Request::is('')  ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('')  ? 'active' : '') }}"
                           href="{{ url('enterprise-dashboard') }}">
                            <span class="sidenav-mini-icon"></span>
                            <span class="sidenav-normal"> Dashboard </span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::is('')  ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('')  ? 'active' : '') }}"
                           href="{{ url('enterprise-team-dashboard') }}">
                            <span class="sidenav-mini-icon"> T </span>
                            <span class="sidenav-normal"> Team Dashboard </span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::is('enterprise-roles-management')  ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('enterprise-roles-management')  ? 'active' : '') }}"
                           href="{{ url('enterprise-roles-management') }}">
                            <span class="sidenav-mini-icon"> R </span>
                            <span class="sidenav-normal"> Role Management </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('enterprise-tags-management') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('enterprise-tags-management') ? 'active' : '') }}"
                           href="{{ url('#') }}">
                            <span class="sidenav-mini-icon"> T </span>
                            <span class="sidenav-normal"> Team Management</span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('enterprise-team-stats') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('enterprise-team-stats') ? 'active' : '') }}"
                           href="{{ url('enterprise-team-stats') }}">
                            <span class="sidenav-mini-icon"> T </span>
                            <span class="sidenav-normal"> Team Stats </span>
                        </a>
                    </li>

                    <li class="nav-item {{ (Request::is('enterprise-strategies-development') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('enterprise-strategies-development') ? 'active' : '') }}"
                           href="{{ url('/enterprise-strategies-development') }}">
                            <span class="sidenav-mini-icon"> S </span>
                            <span class="sidenav-normal"> Strategies Development </span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::is('enterprise-billing') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('enterprise-billing') ? 'active' : '') }}"
                           href="{{ url('enterprise-billing') }}">
                            <span class="sidenav-mini-icon"> B </span>
                            <span class="sidenav-normal"> Billing </span>
                        </a>
                    </li>
                    <li class="nav-item {{ (Request::is('enterprise-pages-account-settings') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('enterprise-pages-account-settings') ? 'active' : '') }}"
                           href="{{ url('enterprise-pages-account-settings') }}">
                            <span class="sidenav-mini-icon"> S </span>
                            <span class="sidenav-normal"> Setting </span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>
        @endif
        </ul>
        </div>
</aside>
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var addPoint = `{{Session::has('add_point') ? '+' . Session::pull('add_point') : '' }}`;

        document.querySelector('.sidenav-toggler-inner').addEventListener('click', function () {
            // Toggle visibility of logos
            $('.humanopLogo').toggleClass('d-none');
            $('.humanopMiniLogo').toggleClass('d-none');
        });

        function animateNumber(addPoint) {
            const navContainer = document.querySelector(".abc");
            const animationEffect = document.createElement('span');

            animationEffect.classList.add('animated-number');
            animationEffect.textContent = addPoint;
            animationEffect.style.color = 'orange';
            animationEffect.style.fontWeight = '900';
            animationEffect.style.fontSize = '2rem';
            animationEffect.style.textShadow = '0 0 5px orange, 0 0 10px orange';
            navContainer.appendChild(animationEffect);

            // Add a slight delay before starting the animation
            setTimeout(() => {
                animationEffect.classList.add('fade-in');
            }, 100); // Slightly longer delay to allow the element to render

            setTimeout(() => {
                animationEffect.classList.add('disappear');
            }, 8000);

            setTimeout(() => {
                animationEffect.remove();
            }, 9000);
        }

        animateNumber(addPoint);

    </script>
@endpush
