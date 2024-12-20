@push('css')
    <style>
        .sidenav > .ps__rail-y {
            display: none !important;
        }
        .icon-size{
            width: 40px !important;
            height: 40px !important;

        }
        .nav-active{
            border-radius: 40px 0px 0px 40px;background: #F4ECE0;
        }
        .sticky_header {
            display: none; /* Hidden by default */
        }

        @media screen and (max-width: 1200px) {
            .sticky_header {
                display: block; /* Visible on smaller screens */
            }
        }
    </style>

@endpush

@if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1)

<div class="sidenav-toggler sidenav-toggler-inner d-flex flex-1" id="nav-toggle-btn"
     style="margin-left: 282px;margin-top:54px;position: absolute;z-index: 1024">
    <a href="javascript:void(0);" class="nav-link text-body p-0">
        <div class="sidenav-toggler-inner">
            <button id="nav-toggle" class="btn rounded-0" style="padding-left: 20px;padding-right: 20px">
                <i class="fa fa-angle-right" id="nav-toggle-icon"></i>
            </button>
        </div>
    </a>
</div>
@endif


@if(\App\Helpers\Helpers::getWebUser()['is_admin'] != 1)
    <div class="position-sticky w-100 sticky_header"  style="top: 28;z-index: 9999999;">
        <div class="d-flex justify-content-between px-5">
            <div style="border-radius: 50%;background: #F4E3C7;box-shadow: 0 0.3125rem 0.625rem 0 rgba(0, 0, 0, 0.12) !important;cursor: pointer" id="nav-show-btn" onclick="showNavbar()">
                <img src="{{asset('assets/new-design/icon/dashboard/menu-icon.svg')}}" id="menu_back_arrow" alt="notification"
                     width="50" height="50">
            </div>
            <div style="border-radius: 50%;background: #F4E3C7;box-shadow:0 0.3125rem 0.625rem 0 rgba(0, 0, 0, 0.12) !important;cursor: pointer" data-toggle="modal" data-target="#humanOpWalletModal">
                <img src="{{asset('assets/new-design/icon/dashboard/orange_crown.svg')}}" alt="notification"
                     width="50" height="50">
            </div>
        </div>
    </div>
@endif
<aside id="" style="z-index: 1024; !important;{{\App\Helpers\Helpers::getWebUser()['is_admin'] != 1 ? 'width: 155px; height: auto;border-radius: 40px !important;margin-left: 30px;' : ''}}background: #1C365E !important"
       class=" {{\App\Helpers\Helpers::getWebUser()['is_admin'] != 1 ? "mt-4 mb-4" : ''}}  sidenav sidenavHideClass navbar navbar-vertical navbar-expand-xs border-0   {{ (\Request::is('pages-rtl') ? 'fixed-end me-3 rotate-caret' : 'fixed-start' ) }} "
       id="sidenav-main">
    <div class="d-flex ">
        <div class="sidenav-header mb-3">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
               aria-hidden="true" id="iconSidenav"></i>
            @if(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                <a class="align-items-center d-flex m-0 text-wrap"
                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard') }}">
                <span class="humanopLogo">
                <img src="{{ URL::asset('assets/logos/HumanOp Logo.png') }}"
                     style="margin-left: 30px; margin-top: 15px;width: 80%; height: 80%;" alt="main_logo">
                </span>
                    <span class="humanopMiniLogo d-none mt-3">
                    <img src="{{ URL::asset('assets/img/Human_OP.png') }}" class="h-100"
                         style="margin-left: 10px; width: 77px"
                         alt="main_logo">
                </span>
                </a>
            @else

                @if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1)

                    <a class="align-items-center d-flex m-0 text-wrap" href="{{ route('admin_dashboard') }}">
                <span class="humanopLogo">
                <img src="{{ URL::asset('assets/logos/HumanOp Logo.png') }}"
                     style="margin-left: 30px; margin-top: 15px;width: 80%; height: 80%;" alt="main_logo">
                </span>
                        <span class="humanopMiniLogo d-none mt-3">
                    <img src="{{ URL::asset('assets/img/Human_OP.png') }}" class="h-100"
                         style="margin-left: 10px; width: 77px"
                         alt="main_logo">
                </span>
                    </a>

                @else

                    <a class="align-items-center d-flex m-0 text-wrap" href="{{ route('client_dashboard') }}">
                <span class="humanopLogo text-center mt-5 " >
                <img src="{{ URL::asset('assets/logos/HumanOp Logo.png') }}"
                     style="margin-left: 5px;width: 100%; height: 100%;" alt="main_logo">
                </span>
                        <span class="humanopMiniLogo d-none mt-3">
                    <img src="{{ URL::asset('assets/img/Human_OP.png') }}" class="h-100"
                         style="margin-left: 10px; width: 77px"
                         alt="main_logo">
                </span>
                    </a>

                @endif
            @endif
        </div>
    </div>


    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        @php($is_admin = \Illuminate\Support\Facades\Cache::get('admin_' . auth()->id())['is_admin'] ?? false)
        @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4 && $is_admin == true)
            {{\Illuminate\Support\Facades\Log::info(['333' => $is_admin, 'id' => auth()->id()])}}
            <div class="d-flex justify-content-center">
                <a onclick="resetAdminValueFromLocalStorage()" href="{{url('/admin/login-back-to-admin')}}" class="btn btn-sm"
                   style="background-color: #f2661c; color: white;" id="logInBackToAdmin_1" hidden>Back to admin</a>
            </div>
        @endif
        <ul class="navbar-nav">
            @if(Auth::user()->hasAnyRole(['super admin', 'sub admin', 'practitioner']) )
                <li class="nav-item">
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
                                        <span class="sidenav-normal"> Clients </span>
                                    </a>
                                </li>
                            @endcan
                            @can('practitioner')
                                <li class="nav-item {{ (Request::is('practitioners') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('practitioners') ? 'active' : '') }}"
                                       href="{{ route('admin_all_practitioners') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Client.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Client.png')}}"></span>
                                        <span class="sidenav-normal"> Practitioners </span>
                                    </a>
                                </li>
                            @endcan
                            @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4)
                                <li class="nav-item {{ (Request::is('intro-assessment') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('intro-assessment') ? 'active' : '') }}"
                                       href="{{ route('practitioner_intro_assessment') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                        <span class="sidenav-normal"> Take Assessments </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ (Request::is('all-practitioner-assessment') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('all-practitioner-assessment') ? 'active' : '') }}"
                                       href="{{ route('admin_all_assessment') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Results.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Results.png')}}"></span>
                                        <span class="sidenav-normal"> Results </span>
                                    </a>
                                </li>
                            @endif
                            @can('abandonedAssessment')
                                <li class="nav-item {{ (Request::is('assessments') ? 'active' : '') }}">
                                    <a class="nav-link {{ (Request::is('assessments') ? 'active' : '') }}"
                                       href="{{ route('assessments') }}">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                        <span class="sidenav-normal"> {{\App\Helpers\Helpers::getWebUser()['is_admin'] == 4 ? 'Client' : ''}} Assessments </span>
                                    </a>
                                </li>

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
                                        <span class="sidenav-normal"> Resources <br>& Trainings </span>
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
                                            <li class="nav-item {{ (Request::is('admin_get_client_invite') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin_get_client_invite') ? 'active' : '') }}"
                                                   href="{{ route('admin_get_client_invite') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                    <span class="sidenav-normal"> Client Invites </span>
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
                                            <li class="nav-item {{ (Request::is('admin/all-daily-tips') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/all-daily-tips') ? 'active' : '') }}"
                                                   href="{{ route('admin_all_daily_tip') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"> Daily Tip </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/all-optimization-plan') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/all-optimization-plan') ? 'active' : '') }}"
                                                   href="{{ route('admin_all_optimization_plan') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"> Optimization Plan </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/all-intention-plan') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/all-intention-plan') ? 'active' : '') }}"
                                                   href="{{ route('admin_all_intention_plan') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                    <span class="sidenav-normal"> Intention Plan </span>
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
                                            <li class="nav-item {{ (Request::is('admin/information-icon') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/information-icon') ? 'active' : '') }}"
                                                   href="{{ route('admin_get_info') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                    <span class="sidenav-normal"> Information Icon & <br> Tutorials </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('admin/version-control') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/version-control') ? 'active' : '') }}"
                                                   href="{{ route('admin_get_version') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                    <span class="sidenav-normal"> Version Control </span>
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
                                <li class="nav-item ">
                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                       data-bs-toggle="collapse" aria-expanded="false" href="#chat">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                        <span class="sidenav-normal"> HAI Chat <b class="caret"></b></span>
                                    </a>
                                    <div class="collapse {{ ($childFolder == 'virtual' ? 'show' : '') }}"
                                         id="chat">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                                   href="{{ route('admin_hai_chat') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                    <span class="sidenav-normal"> Chatbots </span>
                                                </a>
                                            </li>
                                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                                   href="{{ route('admin_embedding_groups') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                    <span class="sidenav-normal"> Embeddings </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endcan
                            {{--                            @can('chat')--}}
                            {{--                                <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">--}}
                            {{--                                    <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"--}}
                            {{--                                       href="{{ route('admin_hai_chat') }}">--}}
                            {{--                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
                            {{--                                                                             src="{{URL::asset('assets/icons/Chat.png')}}"></span>--}}
                            {{--                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
                            {{--                                                                          src="{{URL::asset('assets/icons/Chat.png')}}"></span>--}}
                            {{--                                        <span class="sidenav-normal"> HAI Chat </span>--}}
                            {{--                                    </a>--}}
                            {{--                                </li>--}}
                            {{--                            @endcan--}}
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
                            <li class="nav-item ">
                                <a class="nav-link"
                                   href="{{ url('/logout')}}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal text-bold"> Sign Out </span>
                                </a>
                            </li>

                            @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4)
                                <li class="nav-item mt-2 d-flex justify-content-center">
                                    <div class="d-flex justify-content-between">
                                <span class="humanopMiniLogo d-none">
                                    <img src="{{asset('assets/icons/apple_mobile_logo.png')}}"
                                         alt="apple icon"
                                         style="width: 40px; height: 40px; color: white;"/>
                                  </span>
                                        <span class="humanopLogo ">
                                 <img src="{{asset('assets/icons/downloadapple.svg')}}"
                                      alt="apple icon"
                                      style="width: 100px; height: 40px; color: white;"/>
                                </span>
                                        <span class="humanopMiniLogo d-none " style="margin-left: 5px">
                                  <img src="{{asset('assets/icons/android_mobile_logo.png')}}"
                                       alt="android icon"
                                       style="width: 40px; height: 40px;"/>
                                  </span>
                                        <span class="humanopLogo" style="margin-left: 5px">
                                   <img src="{{asset('assets/icons/downloadandroid.png')}}"
                                        alt="android icon"
                                        style="width: 100px;height: 35px;margin-top: 2px;"/>
                                  </span>
                                    </div>
                                </li>
                            @endif
                            <li class="nav-item">
                                <div class="abc mb-3" style="text-align: center">
                                    @if(Auth::user()['is_admin'] == \App\Enums\Admin\Admin::IS_PRACTITIONER)

                                        <div class="d-flex justify-content-center mt-3">

                                            <div class="bg-white py-1"
                                                 style="cursor: pointer; width: 60px; height: 60px; border-radius: 50%;"
                                                 data-toggle="modal" data-target="#humanOpWalletModal">

                                                <img src="{{asset('assets/icons/wallet-humanop.svg')}}"
                                                     alt="wallet icon"
                                                     style="width: 50px; height: 50px; color: white;"/>

                                            </div>

                                        </div>

                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>

            @elseif((\Illuminate\Support\Facades\Auth::user()->is_admin == 2) && (\Illuminate\Support\Facades\Auth::user()->practitioner_id == null))
                <li class="nav-item">

                    @php($is_admin = \Illuminate\Support\Facades\Cache::get('admin_' . auth()->id())['is_admin'] ?? false)
                    {{\Illuminate\Support\Facades\Log::info(['admin' => \Illuminate\Support\Facades\Cache::get('admin_' . auth()->id())])}}
                    @if($is_admin == true)
                        {{\Illuminate\Support\Facades\Log::info(['111' => $is_admin, 'id' => auth()->id()])}}
                        <div class="d-flex justify-content-center">
                            <a onclick="resetAdminValueFromLocalStorage()" href="{{url('/client/login-back-to-admin')}}" class="btn btn-sm"
                               style="background-color: #f2661c; color: white;" id="logInBackToAdmin_2" hidden>Back to admin</a>
                        </div>
                    @endif

                    {{--                    <a data-bs-toggle="collapse" href="#clientdashboardids"--}}
                    {{--                       class="nav-link {{ ($parentFolder == 'client-dashboard' ? ' active' : '') }}"--}}
                    {{--                       aria-controls="clientdashboardids" role="button" aria-expanded="false">--}}
                    {{--                        <div--}}
                    {{--                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">--}}
                    {{--                            <svg width="12px" height="12px" viewBox="0 0 40 44" version="1.1"--}}
                    {{--                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">--}}
                    {{--                                <title>document</title>--}}
                    {{--                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
                    {{--                                    <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF"--}}
                    {{--                                       fill-rule="nonzero">--}}
                    {{--                                        <g transform="translate(1716.000000, 291.000000)">--}}
                    {{--                                            <g transform="translate(154.000000, 300.000000)">--}}
                    {{--                                                <path class="color-background"--}}
                    {{--                                                      d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"--}}
                    {{--                                                      opacity="0.603585379"></path>--}}
                    {{--                                                <path class="color-background"--}}
                    {{--                                                      d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>--}}
                    {{--                                            </g>--}}
                    {{--                                        </g>--}}
                    {{--                                    </g>--}}
                    {{--                                </g>--}}
                    {{--                            </svg>--}}
                    {{--                        </div>--}}
                    {{--                        <span class="nav-link-text ms-1">Client</span>--}}
                    {{--                    </a>--}}
                    <div class="collapse {{ ($parentFolder == 'client-dashboard' ? ' show' : '') }}"
                         id="clientdashboardids">
                        <ul class="nav">
                            <li class="nav-item text-center mt-2 {{ (Request::is('client/dashboard')  ? 'nav-active' : '') }}" style="margin-left: 10px">
                                <a class="pb-1 "
                                   href="{{ route('client_dashboard') }}">
                                    <span >
                                        @if(Request::is('client/dashboard'))
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/home_active.svg')}}" style="margin-top:5px">
                                        @else
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/home.svg')}}" style="margin-top:5px">
                                        @endif
                                    </span>
                                </a>
                                @if(Request::is('client/dashboard'))
                                    <p class="sidenav-normal mb-0" style="color: #1C365E !important;font-size: 14px"> Dashboard </p>
                                @else
                                    <p class="sidenav-normal mb-0" style="color: #F4E3C7 !important;font-size: 14px"> Dashboard </p>
                                @endif
                            </li>
                            <li class="nav-item text-center mt-2 {{ (Request::is('client/stripe-checkout')  ? 'nav-active' : '') }}"   data-step="1" style="margin-left: 10px">
                                <a class=" pb-1 "
                                   href="{{ url('client/intro-assessment') }}">
                                    <span>
                                        @if(Request::is('client/stripe-checkout'))
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_graph.svg')}}" style="margin-top:5px">
                                        @else
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/graph.svg')}}" style="margin-top:5px">
                                        @endif
                                    </span>
                                    {{--                                    <span class="sidenav-normal"> Assessment </span>--}}
                                </a>
                                @if(Request::is('client/stripe-checkout'))
                                    <p class="sidenav-normal mb-0" style="color: #1C365E !important;font-size: 14px"> Assessment </p>
                                @else
                                    <p class="sidenav-normal mb-0" style="color: #F4E3C7 !important;font-size: 14px"> Assessment </p>
                                @endif
                            </li>
                            <li class="nav-item text-center mt-2 {{ (Request::is('client/all-assessments')  ? 'nav-active' : '') }}" style="margin-left: 10px">
                                <a class=" pb-1 "
                                   href="{{ route('all_assessment') }}">
                                    <span >
                                          @if(Request::is('client/all-assessments'))
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_notepad.svg')}}" style="margin-top:5px">
                                        @else
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/notepad.svg')}}" style="margin-top:5px">
                                        @endif
                                    </span>
                                    {{--                                    <span class="sidenav-normal"> Results </span>--}}
                                </a>
                                @if(Request::is('client/all-assessments'))
                                    <p class="sidenav-normal mb-0" style="color: #1C365E !important;font-size: 14px"> Results </p>
                                @else
                                    <p class="sidenav-normal mb-0" style="color: #F4E3C7 !important;font-size: 14px"> Results </p>
                                @endif
                            </li>

                            <li class="nav-item text-center mt-2 {{ (Request::is('client/resource') ? 'nav-active' : '') }}" style="margin-left: 10px">
                                <a class=" pb-1 "
                                   href="{{ route('resource') }}">

                                    <span >
                                            @if(Request::is('client/resource'))
                                            <img class="icon-size mx-auto"   src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active-resource.png')}}" style="margin-top:5px">
                                        @else
                                            <img class="icon-size mx-auto"   src="{{URL::asset('assets/new-design/icon/dashboard/navbar/resource.png')}}" style="margin-top:5px">
                                        @endif
                                    </span>
                                    {{--                                    <span class="sidenav-normal"> Resources & Trainings </span>--}}
                                </a>
                                @if(Request::is('client/resource'))
                                    <p class="sidenav-normal mb-0" style="color: #1C365E !important;font-size: 14px"> Resources <br>& Trainings </p>
                                @else
                                    <p class="sidenav-normal mb-0" style="color: #F4E3C7 !important;font-size: 14px"> Resources <br>& Trainings </p>
                                @endif
                            </li>
                            <li class="nav-item mt-2">
                                <div class="text-center {{ ((Request::is('client/human-network') ||  Request::is('client/connections') || Request::is('client/connections') || Request::is('client/messages') || Request::is('client/follow')) ? 'nav-active' : '') }}"   style="margin-left: 10px">
                                    <a class=" pb-2 ">
                                        <span >
                                               @if(Request::is('client/human-network') ||  Request::is('client/connections') || Request::is('client/connections') || Request::is('client/messages') || Request::is('client/follow'))
                                                <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_network.svg')}}" style="margin-top:5px">
                                            @else
                                                <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/network.svg')}}" style="margin-top:5px">
                                            @endif
                                        </span>
                                        {{--                                    <span class="sidenav-normal"> HumanOp Network &nbsp;&nbsp; <b--}}
                                        {{--                                            class="caret"></b></span>--}}
                                    </a>
                                    @if(Request::is('client/human-network') ||  Request::is('client/connections') || Request::is('client/connections') || Request::is('client/messages') || Request::is('client/follow'))
                                        <a class="sidenav-normal d-block" data-bs-toggle="collapse" aria-expanded="false" href="#vrExamples" style="color: #1C365E  !important;font-size: 14px"> Network
                                            <img
                                                src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_downarrow.svg')}}" style="margin-left: 10px" width="12" height="12"></a>
                                    @else
                                        <a class="sidenav-normal d-block" data-bs-toggle="collapse" aria-expanded="false" href="#vrExamples" style="color: #F4E3C7 !important;font-size: 14px"> Network
                                            <img
                                                src="{{URL::asset('assets/new-design/icon/dashboard/navbar/down_arrow.svg')}}" style="margin-left: 10px" width="12" height="12"></a>
                                    @endif
                                </div>
                                <div class="collapse "
                                     id="vrExamples">
                                    <ul class="nav nav-sm flex-column" style="margin-left: 15px">
                                        <li class="nav-item">

                                            <a class="nav-link px-0 mx-0"
                                               href="{{ route('connections', ['type' => 'connection']) }}">

                                                <span ><img
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/search.svg')}}" width="25" height="25"></span>
                                                <span class="sidenav-normal" style="color: #F4E3C7 !important;font-size: 16px;margin-left: 5px"> Find <br> Connections</span>
                                            </a>

                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-link px-0 mx-0 "
                                               href="{{ route('messages') }}">
                                                <span><img
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/message.svg')}}" width="25" height="25"></span>
                                                <span class="sidenav-normal" style="color: #F4E3C7 !important;font-size: 16px;margin-left: 5px"> Messages </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-link px-0 mx-0 "
                                               href="{{ route('connections', ['type' => 'request']) }}">
                                                <span >
                                                    <img
                                                        width="25" height="25"
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/add_people.svg')}}"></span>
                                                <span class="sidenav-normal" style="color: #F4E3C7 !important;font-size: 16px;margin-left: 5px"> Connection<br> Request </span>
                                            </a>
                                        </li>
                                        <li class="nav-item ">
                                            <a class="nav-link  px-0 mx-0 "
                                               href="{{ route('follow', ['type' => 'follower']) }}">
                                                <span><img
                                                        width="25" height="25"
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/people.svg')}}"></span>
                                                <span class="sidenav-normal" style="color: #F4E3C7 !important;font-size: 16px;margin-left: 5px"> Followers </span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link px-0 mx-0 "
                                               href="{{ route('follow', ['type' => 'following']) }}">
                                                <span ><img
                                                        width="25" height="25"
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/people_tick.svg')}}"></span>
                                                <span class="sidenav-normal" style="color: #F4E3C7 !important;font-size: 16px;margin-left: 5px"> Following </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item mt-4">
                                <div class="text-center  {{ ((Request::is('client/beta-feedback') ||  Request::is('client/tutorials')) ? 'nav-active' : '') }}" style="margin-left: 10px">
                                    <a class="pb-2  "
                                    >
                                    <span >
                                        @if(Request::is('client/beta-feedback') ||  Request::is('client/tutorials'))
                                            <img class="icon-size mx-auto"
                                                 src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_tool.svg')}}" style="margin-top:5px"></span>
                                        @else
                                            <img class="icon-size mx-auto"
                                                 src="{{URL::asset('assets/new-design/icon/dashboard/navbar/tool.svg')}}" style="margin-top:5px"></span>
                                        @endif
                                        {{--                                    <span class="sidenav-normal"> Support &nbsp;&nbsp; <b--}}
                                        {{--                                            class="caret"></b></span>--}}
                                    </a>
                                    @if(Request::is('client/beta-feedback') ||  Request::is('client/tutorials'))
                                        <a class="sidenav-normal d-block"  data-bs-toggle="collapse" aria-expanded="false" href="#support" style="color: #1C365E !important;font-size: 14px"> Support       <img
                                                style="margin-left: 10px"    src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_downarrow.svg')}}" width="12" height="12"> </a>
                                    @else
                                        <a class="sidenav-normal d-block"  data-bs-toggle="collapse" aria-expanded="false" href="#support" style="color: #F4E3C7 !important;font-size: 14px"> Support       <img
                                                style="margin-left: 10px"    src="{{URL::asset('assets/new-design/icon/dashboard/navbar/down_arrow.svg')}}" width="12" height="12"> </a>
                                    @endif
                                </div>
                                <div class="collapse {{ ($childFolder == 'support' ? 'show' : '') }}"
                                     id="support">
                                    <ul class="nav nav-sm flex-column" style="margin-left: 15px">
                                        <li class="nav-item  {{ (Request::is('client/beta-feedback') ? 'active' : '') }}">
                                            <a class="nav-link px-0 mx-0 {{ (Request::is('client/beta-feedback') ? 'active' : '') }}"
                                               href="{{ route('user_beta_feedback') }}">
                                                <span ><img
                                                        width="25" height="25"
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/comment.svg')}}"></span>
                                                <span class="sidenav-normal"  style="font-size:16px;color: #F4E3C7 !important;margin-left: 5px">Beta <br> Feedback</span>
                                            </a>
                                        </li>
                                        <li class="nav-item px-0 mx-0 {{ (Request::is('client/tutorials') ? 'active' : '') }}">
                                            <a class="nav-link px-0 mx-0 {{ (Request::is('client/tutorials') ? 'active' : '') }}"
                                               href="{{ route('user_tutorial') }}">

                                                <span class="sidenav-normal"><img
                                                        width="25" height="25"
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/tutorial.svg')}}"></span>
                                                <span class="sidenav-normal" style="font-size:16px;color: #F4E3C7 !important;margin-left: 5px">Tutorials</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link start-tour px-0 mx-0"
                                               href="javascript:void(0);">

                                                <span ><img
                                                        width="25" height="25"
                                                        src="{{URL::asset('assets/new-design/icon/dashboard/navbar/flag.svg')}}"></span>
                                                <span class="sidenav-normal" style="font-size:16px;color: #F4E3C7 !important;margin-left: 5px">Tour Guide</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item text-center mt-2 {{ (Request::is('client/pricing') ? 'nav-active' : '') }} " style="margin-left: 10px">
                                <a class=" pb-1 {{ (Request::is('client/pricing') ? 'active' : '') }}"
                                   href="{{ route('client_pricing') }}">
                                    <span >
                                     @if(Request::is('client/pricing'))
                                            <img class="icon-size mx-auto"
                                                 src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_crown.svg')}}" style="margin-top:5px">
                                        @else
                                            <img class="icon-size mx-auto" src="{{URL::asset('assets/new-design/icon/dashboard/navbar/crown.svg')}}" style="margin-top:5px">
                                        @endif
                                    </span>
                                    {{--                                    <span class="sidenav-normal"> Pricing </span>--}}
                                </a>
                                @if(Request::is('client/pricing'))
                                    <p class="sidenav-normal mb-0" style="color: #1C365E !important;font-size: 14px"> Pricing </p>
                                @else
                                    <p class="sidenav-normal mb-0" style="color: #F4E3C7 !important;font-size: 14px"> Pricing </p>
                                @endif
                            </li>








                            <li class="nav-item text-center mt-2 {{ (Request::is('client/setting') ? 'nav-active' : '') }}" style="margin-left: 10px">
                                <a class="pb-1 {{ (Request::is('client/setting') ? 'active' : '') }}"
                                   href="{{ route('setting') }}">
                                    <span >
                                        @if(Request::is('client/setting'))
                                            <img class="icon-size mx-auto"
                                                 src="{{URL::asset('assets/new-design/icon/dashboard/navbar/active_setting.svg')}} " style="margin-top:5px">
                                        @else
                                            <img class="icon-size mx-auto"
                                                 src="{{URL::asset('assets/new-design/icon/dashboard/navbar/setting.svg')}}" style="margin-top:5px">
                                        @endif
                                    </span>
                                    {{--                                    <span class="sidenav-normal"> Setting </span>--}}
                                </a>
                                @if(Request::is('client/setting'))
                                    <p class="sidenav-normal mb-0" style="color: #1C365E !important;font-size: 14px"> Setting </p>
                                @else
                                    <p class="sidenav-normal mb-0" style="color: #F4E3C7 !important;font-size: 14px"> Setting </p>
                                @endif
                            </li>

                            <li class="nav-item text-center mt-2" style="margin-left: 10px">
                                <a class=" pb-1"
                                   href="{{ url('/logout')}}">
                                    <span><img class="icon-size mx-auto"
                                               src="{{URL::asset('assets/new-design/icon/dashboard/navbar/logout.svg')}}" style="margin-top:5px"></span>
                                    {{--                                    <span class="sidenav-normal text-bold"> Sign Out </span>--}}
                                </a>
                                <p class="sidenav-normal" style="color: #F4E3C7 !important;font-size: 14px"> Signout </p>
                            </li>
                            {{--                            download on apple--}}
                            <li class="nav-item mt-2 d-flex justify-content-center">
                                <div class="d-flex justify-content-between">
                                         <span class="humanopMiniLogo" >
                                  <img src="{{asset('assets/icons/android_mobile_logo.png')}}"
                                       alt="android icon"
                                       style="width: 40px; height: 40px;"/>
                                  </span>
                                    <span class="humanopMiniLogo " style="margin-left: 5px">
                                    <img src="{{asset('assets/icons/apple_mobile_logo.png')}}"
                                         alt="apple icon"
                                         style="width: 40px; height: 40px; color: white;"/>
                                  </span>
                                </div>
                            </li>
                            {{--                            end download here--}}
                            <li class="nav-item mt-3 {{ (Request::is('client/version') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/prversionicing') ? 'active' : '') }}"
                                   href="{{ route('get_latest_version') }}">
                                    <span style="margin-left: -30px !important;" class="humanop-version sidenav-normal"> HAI OS {{\App\Models\Admin\VersionControl\Version::getLatestVersion() ? \App\Models\Admin\VersionControl\Version::getLatestVersion()['version'] : '0.0.0'}} </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <div class="abc mb-3" style="text-align: center">
                                    @if(Auth::user()['is_admin'] == \App\Enums\Admin\Admin::IS_CUSTOMER)

                                        <div class="d-flex justify-content-center mt-3">

                                            <div class="bg-white py-1"
                                                 style="cursor: pointer; width: 60px; height: 60px; border-radius: 50%;"
                                                 data-toggle="modal" data-target="#humanOpWalletModal">

                                                <img src="{{asset('assets/icons/wallet-humanop.svg')}}"
                                                     alt="wallet icon"
                                                     style="width: 50px; height: 50px; color: white;"/>

                                            </div>

                                        </div>

                                    @endif
                                </div>
                            </li>

                        </ul>
                    </div>
                </li>
            @elseif((\Illuminate\Support\Facades\Auth::user()->is_admin == 2) && (\Illuminate\Support\Facades\Auth::user()->practitioner_id != null))
                <li class="nav-item">

                    @php($is_admin = \Illuminate\Support\Facades\Cache::get('admin_' . auth()->id())['is_admin'] ?? false)
                    @if($is_admin == true)
                        {{\Illuminate\Support\Facades\Log::info(['2222' => $is_admin, 'id' => auth()->id()])}}
                        <div class="d-flex justify-content-center">
                            <a onclick="resetAdminValueFromLocalStorage()" href="{{url('/client/login-back-to-admin')}}" class="btn btn-sm"
                               style="background-color: #f2661c; color: white;" id="logInBackToAdmin_3" hidden>Back to admin</a>
                        </div>
                    @endif

                    {{--                    <a data-bs-toggle="collapse" href="#clientdashboardids"--}}
                    {{--                       class="nav-link {{ ($parentFolder == 'client-dashboard' ? ' active' : '') }}"--}}
                    {{--                       aria-controls="clientdashboardids" role="button" aria-expanded="false">--}}
                    {{--                        <div--}}
                    {{--                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">--}}
                    {{--                            <svg width="12px" height="12px" viewBox="0 0 40 44" version="1.1"--}}
                    {{--                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">--}}
                    {{--                                <title>document</title>--}}
                    {{--                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
                    {{--                                    <g transform="translate(-1870.000000, -591.000000)" fill="#FFFFFF"--}}
                    {{--                                       fill-rule="nonzero">--}}
                    {{--                                        <g transform="translate(1716.000000, 291.000000)">--}}
                    {{--                                            <g transform="translate(154.000000, 300.000000)">--}}
                    {{--                                                <path class="color-background"--}}
                    {{--                                                      d="M40,40 L36.3636364,40 L36.3636364,3.63636364 L5.45454545,3.63636364 L5.45454545,0 L38.1818182,0 C39.1854545,0 40,0.814545455 40,1.81818182 L40,40 Z"--}}
                    {{--                                                      opacity="0.603585379"></path>--}}
                    {{--                                                <path class="color-background"--}}
                    {{--                                                      d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z"></path>--}}
                    {{--                                            </g>--}}
                    {{--                                        </g>--}}
                    {{--                                    </g>--}}
                    {{--                                </g>--}}
                    {{--                            </svg>--}}
                    {{--                        </div>--}}
                    {{--                        <span class="nav-link-text ms-1">Client</span>--}}
                    {{--                    </a>--}}
                    <div class="collapse {{ ($parentFolder == 'client-dashboard' ? ' show' : '') }}"
                         id="clientdashboardids">
                        <ul class="nav  ps-3">
                            <li class="nav-item {{ (Request::is('client/dashboard')  ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/dashboard')  ? 'active' : '') }}"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                    <span class="sidenav-normal"> Dashboard </span>
                                </a>
                            </li>
                            <li  class="nav-item {{ (Request::is('client/stripe-checkout')  ? 'active' : '') }}"   data-step="1"      >
                                <a class="nav-link {{ (Request::is('client/stripe-checkout')  ? 'active' : '') }}"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('intro-assessment') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/assessment.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/assessment.png')}}"></span>
                                    <span class="sidenav-normal"> Assessment </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/all-assessments')  ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/all-assessments')  ? 'active' : '') }}"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('all-assessments') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Results.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Results.png')}}"></span>
                                    <span class="sidenav-normal"> Your Assessment Results </span>
                                </a>
                            </li>

                            <li class="nav-item {{ (Request::is('client/resource') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/resource') ? 'active' : '') }}"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('resource') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>
                                    <span class="sidenav-normal"> Resources <br> & Trainings </span>
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
                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('connections?type=connection') }}">
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
                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('messages') }}">
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
                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('connections?type=request') }}">
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
                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('follow?type=follower') }}">
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
                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('follow?type=following') }}">
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
                            <li class="nav-item {{ (Request::is('client/pricing') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/pricing') ? 'active' : '') }}"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('pricing') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Pricing.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Pricing.png')}}"></span>
                                    <span class="sidenav-normal"> Pricing </span>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::is('client/setting') ? 'active' : '') }}">
                                <a class="nav-link {{ (Request::is('client/setting') ? 'active' : '') }}"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('setting') }}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"> Setting </span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('logout')}}">
                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                         src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal text-bold  "> Sign Out </span>
                                </a>
                            </li>
                            <li class="nav-item mt-2 d-flex justify-content-center">
                                <div class="d-flex justify-content-between">
                                <span class="humanopMiniLogo d-none">
                                    <img src="{{asset('assets/icons/apple_mobile_logo.png')}}"
                                         alt="apple icon"
                                         style="width: 40px; height: 40px; color: white;"/>
                                  </span>
                                    <span class="humanopLogo ">
                                 <img src="{{asset('assets/icons/downloadapple.svg')}}"
                                      alt="apple icon"
                                      style="width: 100px; height: 40px; color: white;"/>
                                </span>
                                    <span class="humanopMiniLogo d-none " style="margin-left: 5px">
                                  <img src="{{asset('assets/icons/android_mobile_logo.png')}}"
                                       alt="android icon"
                                       style="width: 40px; height: 40px;"/>
                                  </span>
                                    <span class="humanopLogo" style="margin-left: 5px">
                                   <img src="{{asset('assets/icons/downloadandroid.png')}}"
                                        alt="android icon"
                                        style="width: 100px;height: 35px;margin-top: 2px;"/>
                                  </span>
                                </div>
                            </li>

                            <li class="nav-item">
                                <div class="abc mb-3" style="text-align: center">
                                    @if(Auth::user()['is_admin'] == \App\Enums\Admin\Admin::IS_CUSTOMER)

                                        <div class="d-flex justify-content-center mt-3">

                                            <div class="bg-white py-1"
                                                 style="cursor: pointer; width: 60px; height: 60px; border-radius: 50%;"
                                                 data-toggle="modal" data-target="#humanOpWalletModal">

                                                <img src="{{asset('assets/icons/wallet-humanop.svg')}}"
                                                     alt="wallet icon"
                                                     style="width: 50px; height: 50px; color: white;"/>

                                            </div>

                                        </div>

                                    @endif
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

@push('js')

    <script>

        is_admin = localStorage.getItem('is_admin');

        if (is_admin){

            const id_arrays = ['logInBackToAdmin_1','logInBackToAdmin_2','logInBackToAdmin_3'];

            id_arrays.forEach(function (value, key){

                if(document.getElementById(value)){

                    console.log('1');

                    document.getElementById(value).removeAttribute("hidden");
                }

            });
        }

        function resetAdminValueFromLocalStorage(){

            localStorage.removeItem('is_admin');
        }

        function showNavbar() {
            $('body').removeClass('g-sidenav-hidden').addClass('g-sidenav-pinned');
            $('#nav-show-btn').attr('onclick', 'hideNavbar()'); // Set onclick to hideNavbar
            $('#menu_back_arrow').attr('src',back_arrow_src);
        }

        function hideNavbar() {
            $('body').addClass('g-sidenav-hidden').removeClass('g-sidenav-pinned');
            $('#nav-show-btn').attr('onclick', 'showNavbar()'); // Set onclick to showNavbar
            $('#menu_back_arrow').attr('src',menu_src);
        }

        $(window).on('resize', function () {
            if ($(window).width() > 1200) {
                $('body').removeClass('g-sidenav-hidden').addClass('g-sidenav-pinned');
            }else{
                $('body').addClass('g-sidenav-hidden').removeClass('g-sidenav-pinned');
            }
        });
    </script>

@endpush
