@push('css')
    <style>
        .sidenav > .ps__rail-y {
            display: none !important;
        }

        .icon-size {
            width: 40px !important;
            height: 40px !important;

        }

        .active-itt-all {
            color: #f2661c !important;
        }

        .nav-active {
            border-radius: 40px 0px 0px 40px;
            background: #F4ECE0;
        }

        .sticky_header {
            display: none; /* Hidden by default */
        }

        .caret {
            margin-left: 10px !important;
        }

        @media screen and (max-width: 1200px) {
            .sticky_header {
                display: block; /* Visible on smaller screens */
            }
        }

    </style>

@endpush
@if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1 || \App\Helpers\Helpers::getWebUser()['is_admin'] === 3)

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
    <div class="position-sticky w-100 sticky_header" style="top: 28;z-index: 9999999;">
        <div class="d-flex justify-content-between px-5">
            <div
                style="border-radius: 50%;background: #F4E3C7;box-shadow: 0 0.3125rem 0.625rem 0 rgba(0, 0, 0, 0.12) !important;cursor: pointer"
                id="nav-show-btn" onclick="showNavbar()">
                <img src="{{asset('assets/new-design/icon/dashboard/menu-icon.svg')}}" id="menu_back_arrow"
                     alt="notification"
                     width="50" height="50">
            </div>
            <div
                style="border-radius: 50%;background: #F4E3C7;box-shadow:0 0.3125rem 0.625rem 0 rgba(0, 0, 0, 0.12) !important;cursor: pointer"
                data-toggle="modal" data-target="#humanOpWalletModal">
                <img src="{{asset('assets/new-design/icon/dashboard/orange_crown.svg')}}" alt="notification"
                     width="50" height="50">
            </div>
        </div>
    </div>
@endif
<aside
    style="z-index: 1024; !important;{{\App\Helpers\Helpers::getWebUser()['is_admin'] == 2  ? 'width: 155px; height: auto;border-radius: 40px !important;margin-left: 30px;' : ''}}background: #1C365E !important"
    class=" {{\App\Helpers\Helpers::getWebUser()['is_admin'] == 2 ? "mt-4 mb-4" : ''}}  sidenav sidenavHideClass myspecial navbar navbar-vertical navbar-expand-xs border-0   {{ (\Request::is('pages-rtl') ? 'fixed-end me-3 rotate-caret' : 'fixed-start' ) }} "
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
            @endif
            @if(\App\Helpers\Helpers::getWebUser()['is_admin'] === 1 || \App\Helpers\Helpers::getWebUser()['is_admin'] === 3)

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
            @endif
        </div>
    </div>


    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        @php($is_admin = \Illuminate\Support\Facades\Cache::get('admin_' . auth()->id())['is_admin'] ?? false)
        @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4 && $is_admin == true)
            {{\Illuminate\Support\Facades\Log::info(['333' => $is_admin, 'id' => auth()->id()])}}
            <div class="d-flex justify-content-center">
                <a onclick="resetAdminValueFromLocalStorage()" href="{{url('/admin/login-back-to-admin')}}"
                   class="btn btn-sm"
                   style="background-color: #f2661c; color: white;" id="logInBackToAdmin_1" hidden>Back to admin</a>
            </div>
        @endif
        <ul class="navbar-nav">
            @if(Auth::user()->hasAnyRole(['super admin', 'sub admin', 'practitioner']) )
                <li class="nav-item">
                    <div class="collapse show" id="dashboardsExamples">
                        <ul class="nav ps-2">

                            @if(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('sub admin'))
                                <li class="nav-item mylink">
                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                       data-bs-toggle="collapse" aria-expanded="false" href="#adminExamples">
                                        <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                             src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                        <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                          src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                        <span class="sidenav-normal"> Admin Dashboard <b class="caret"></b></span>
                                    </a>
                                    <div class="collapse show {{ ($childFolder == 'virtual' ? 'show' : '') }}"
                                         id="adminExamples">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::is('admin/admin-dashboard') ? 'active-itt-all' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/admin-dashboard') ? 'active-itt-all' : '') }}"
                                                   href="{{ route('admin_dashboard') }}">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>
                                                    <span class="sidenav-normal"> Dashboard </span>
                                                </a>
                                            </li>
                                            @can('user_management')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#usermanagementExamples">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> User Management <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse {{ Request::is('admin/users',  'admin/deleted-clients') ? 'show' : '' }}"
                                                        id="usermanagementExamples">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/users') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/users') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_users') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Client.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Client.png')}}"></span>
                                                                    <span class="sidenav-normal"> User Database </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/deleted-clients') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/deleted-clients') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('deleted_clients') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Delete Client.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Delete Client.png')}}"></span>
                                                                    <span class="sidenav-normal"> Deleted User </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('assessment_management')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#assementexample">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> Assessment Management <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse {{ Request::is('admin/assessments', 'admin/abandoned-assessment') ? 'show' : '' }}"
                                                        id="assementexample">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/assessments') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/assessments') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('assessments') }}">
                                                                           <span class="sidenav-mini-icon"><img
                                                                                   style="width: 18px; margin-right: 10px"
                                                                                   src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                                                    <span class="sidenav-normal"> {{\App\Helpers\Helpers::getWebUser()['is_admin'] == 4 ? 'Client' : ''}} Assessment Database </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/abandoned-assessment') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/abandoned-assessment') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_abandoned_assessment') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Abandoned Assessment.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Unfinished Assessments </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('technology_management')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#technologyexample">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> Technology Management <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse {{ Request::is('admin/questions') ? 'show' : '' }}"
                                                        id="technologyexample">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/questions') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/questions') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_questions') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Question.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Question.png')}}"></span>
                                                                    <span class="sidenav-normal"> Questions </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('team_management')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#teamexample">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> Team Management <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse  {{ Request::is('admin/sub-admins') ? 'show' : '' }}"
                                                        id="teamexample">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/sub-admins') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/sub-admins') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_sub_admins') }}">
                                                                <span class="sidenav-mini-icon"><img
                                                                        style="width: 18px; margin-right: 10px"
                                                                        src="{{URL::asset('assets/icons/Sub Admin.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Sub Admin.png')}}"></span>
                                                                    <span class="sidenav-normal"> Sub Admins </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('hai_admin')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false" href="#chat">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> HAI Admin <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse {{ Request::is('admin/hai-chat-persona', 'admin/clusters', 'admin/hai-chat', 'admin/hai-chat-comparison', 'admin/fine-tune') ? 'show' : '' }}"
                                                        id="chat">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/hai-chat-persona') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/hai-chat-persona') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_hai_chat_persona') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"> Persona </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/hai-chat') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/hai-chat') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_hai_chat') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"> Brains </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/clusters') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/clusters') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_embedding_groups') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                                    <span class="sidenav-normal"> Knowledge </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/fine-tune') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/fine-tune') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('fine_tune') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                                    <span class="sidenav-normal"> Advanced </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/hai-chat-comparison') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/hai-chat-comparison') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_hai_chat_comparison') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Video Bucket.png')}}"></span>
                                                                    <span class="sidenav-normal"> Comparison </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('cms_admin')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#vrExamples">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> CMS Admin<b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse  {{ Request::is('admin/codes','admin/client-invites',
                                                        'admin/assessment-walkthrough','admin/all-daily-tips',
                                                        'admin/resources',
                                                        'admin/dashboard-cms',
                                                        'admin/all-coupons','admin/cms','admin/all-optimization-plan',
                                                        'admin/all-intention-plans',
                                                        'admin/admin_resources','admin/podcast','admin/version-control',
                                                        'admin/b2b-support','admin/information-icon','admin/payment-history',
                                                        'admin/edit-version-control*','admin/create-version-control',

                                                        'admin/admin_get_client_invite') ? 'show' : '' }}"
                                                        id="vrExamples">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/assessment-walkthrough') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/assessment-walkthrough') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_get_assessment_walkthrough') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"> Post Assessment WalkThrough </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/assessment-introduction') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/assessment-introduction') ? 'active-itt-all' : '') }}"
                                                                   href="{{route('admin_manage_assessment_intro')}}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"> Assessment Introduction Page </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/summary-report') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/summary-report') ? 'active-itt-all' : '') }}"
                                                                   href="{{route('admin_manage_summary_report')}}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Summary Report Content </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/resources') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/resources') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_resources') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/resourcee.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/resourcee.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Resources & Trainings </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/all-daily-tips') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/all-daily-tips') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_daily_tip') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Daily Tip Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/all-optimization-plan') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/all-optimization-plan') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_optimization_plan') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Optimization Plan Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/all-intention-plans') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/all-intention-plans') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_intention_plan') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>
                                                                    <span class="sidenav-normal"> Intention of Use Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/information-icon') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/information-icon') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_get_info') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Information Icon Management </span>
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
                                                                    <span class="sidenav-normal"> Video Results Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/podcast') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/podcast') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('podcast') }}">
                                                                   <span class="sidenav-normal"><img
                                                                           style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                           src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Podcast Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/version-control') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/version-control') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_get_version') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Version Pop-Up Management </span>
                                                                </a>
                                                            </li>


                                                            <li class="nav-item {{ (Request::is('admin/codes') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/codes') ? 'active-itt-all' : '') }}"
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
                                                            @if(env("APP_ENV") === 'production')
                                                            @else
                                                                <li class="nav-item {{ (Request::is('admin/client-invites') ? 'active-itt-all' : '') }}">
                                                                    <a class="nav-link {{ (Request::is('admin/client-invites') ? 'active-itt-all' : '') }}"
                                                                       href="{{ route('admin_get_client_invite') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                        <span class="sidenav-normal"><img
                                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                                        <span
                                                                            class="sidenav-normal"> Client Invites </span>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            <li class="nav-item {{ (Request::is('admin/b2b-support') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/b2b-support') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_b2b_support') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> B2B Support </span>
                                                                </a>
                                                            </li>

                                                            {{--                                                            <li class="nav-item {{ (Request::is('admin/cms') ? 'active-itt-all':'') }}">--}}
                                                            {{--                                                                <a class="nav-link {{ (Request::is('admin/cms') ?  'active-itt-all':'') }}"--}}
                                                            {{--                                                                   href="{{ route('admin_web_pages') }}">--}}
                                                            {{--                                                                    <span class="sidenav-mini-icon"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Website page.png')}}"></span>--}}
                                                            {{--                                                                    <span class="sidenav-normal"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Website page.png')}}"></span>--}}
                                                            {{--                                                                    <span class="sidenav-normal"> Web Pages </span>--}}
                                                            {{--                                                                </a>--}}
                                                            {{--                                                            </li>--}}
                                                            {{--                                                            <li class="nav-item {{ (Request::is('admin/dashboard-cms') ? 'active-itt-all' : '') }}">--}}
                                                            {{--                                                                <a class="nav-link {{ (Request::is('admin/dashboard-cms') ? 'active-itt-all' : '') }}"--}}
                                                            {{--                                                                   href="{{ route('admin_cms') }}">--}}
                                                            {{--                                                                    <span class="sidenav-mini-icon"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Asset Management.png')}}"></span>--}}
                                                            {{--                                                                    <span class="sidenav-normal"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Asset Management.png')}}"></span>--}}
                                                            {{--                                                                    <span--}}
                                                            {{--                                                                        class="sidenav-normal"> Assets Management </span>--}}
                                                            {{--                                                                </a>--}}
                                                            {{--                                                            </li>--}}
                                                            {{--                                                            <li class="nav-item {{ (Request::is('admin/all-coupons') ? 'active-itt-all' : '') }}">--}}
                                                            {{--                                                                <a class="nav-link {{ (Request::is('admin/all-coupons') ? 'active-itt-all' : '') }}"--}}
                                                            {{--                                                                   href="{{ route('admin_all_coupon') }}">--}}
                                                            {{--                                                                    <span class="sidenav-mini-icon"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>--}}
                                                            {{--                                                                    <span class="sidenav-normal"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Coupom.png')}}"></span>--}}
                                                            {{--                                                                    <span class="sidenav-normal"> Coupons </span>--}}
                                                            {{--                                                                </a>--}}
                                                            {{--                                                            </li>--}}
                                                            {{--                                                            <li class="nav-item {{ (Request::is('admin/payment-history') ? 'active-itt-all' : '') }}">--}}
                                                            {{--                                                                <a class="nav-link {{ (Request::is('admin/payment-history') ? 'active-itt-all' : '') }}"--}}
                                                            {{--                                                                   href="{{ route('admin_payment_history') }}">--}}
                                                            {{--                                                                    <span class="sidenav-mini-icon"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Payment.png')}}"></span>--}}
                                                            {{--                                                                    <span class="sidenav-normal"><img--}}
                                                            {{--                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
                                                            {{--                                                                            src="{{URL::asset('assets/icons/Payment.png')}}"></span>--}}
                                                            {{--                                                                    <span--}}
                                                            {{--                                                                        class="sidenav-normal"> Payment History </span>--}}
                                                            {{--                                                                </a>--}}
                                                            {{--                                                            </li>--}}
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('support_admin')
                                                <li class="nav-item mylink">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#supportadminExamples">
                                                    <span class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                                        <span class="sidenav-normal"> Support Admin <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse {{ (Request::is('admin/feedback') ?'show':'') }}"
                                                        id="supportadminExamples">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/feedback') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/feedback') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('feedback') }}">
                                                                <span class="sidenav-mini-icon"><img
                                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                        src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{URL::asset('assets/icons/User feedback.png')}}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> User Feedback Management</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            @can('client_queries')
                                                <li class="nav-item {{ (Request::is('admin/client-queries') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/client-queries') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('admin_client_queries') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Client Queries.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Client Queries.png')}}"></span>
                                                        <span class="sidenav-normal"> Client Queries </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('approve_queries')
                                                <li class="nav-item {{ (Request::is('admin/approve-queries') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/approve-queries') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('admin_approve_queries') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Approved queries.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Approved queries.png')}}"></span>
                                                        <span class="sidenav-normal"> Approve Queries </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                @if(Auth::user()->hasRole('super admin'))
                                    <li class="nav-item ">
                                        <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                           data-bs-toggle="collapse" aria-expanded="false" href="#b2bExamples">
                                            <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"
                                                                                 src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                            <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                              src="{{URL::asset('assets/icons/CMS.png')}}"></span>
                                            <span class="sidenav-normal"> B2B Dashboard <b class="caret"></b></span>
                                        </a>
                                        <div
                                            class="collapse {{ ( Request::is('admin/b2b-organizations','admin/role-template','admin/role-template','admin/b2b-deleted-clients','admin/b2b-invites','admin/b2b-pricing-plans','admin/b2b-coupon') ? 'show' : '') }}"
                                            id="b2bExamples">
                                            <ul class="nav nav-sm flex-column">
                                                <li class="nav-item {{ (Request::is('admin/b2b-organizations') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/b2b-organizations') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('all_b2b_organizations') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"> Organizations </span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ (Request::is('admin/role-template') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/role-template') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('admin_role_template') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"> Role Template Manage </span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ (Request::is('admin/b2b-invites') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/b2b-invites') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('admin_b2b_invites') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"> B2B Invites </span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ (Request::is('admin/b2b-pricing-plans') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/b2b-pricing-plans') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('admin_b2b_pricing_plan') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"> B2B Pricing Plans </span>
                                                    </a>
                                                </li>
                                                <li class="nav-item {{ (Request::is('admin/b2b-coupon') ? 'active-itt-all' : '') }}">
                                                    <a class="nav-link {{ (Request::is('admin/b2b-coupon') ? 'active-itt-all' : '') }}"
                                                       href="{{ route('admin_b2b_coupon') }}">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                src="{{URL::asset('assets/icons/Codee.png')}}"></span>
                                                        <span class="sidenav-normal"> B2B Coupon </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                @endif
                            @endif

                            <li class="nav-item {{ (Request::is('admin/settings') ? 'active-itt-all' : '') }}">
                                <a class="nav-link {{ (Request::is('admin/settings') ? 'active-itt-all' : '') }}"
                                   href="{{ route('admin_setting') }}">
                                                                <span class="sidenav-mini-icon"><img
                                                                        style="width: 18px; margin-right: 10px"
                                                                        src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/Settings.png')}}"></span>
                                    <span class="sidenav-normal"> Settings </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link"
                                   href="{{ url('/logout')}}">
                                                                <span class="sidenav-mini-icon"><img
                                                                        style="width: 18px; margin-right: 10px"
                                                                        src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal text-bold"> Log Out </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
{{--            @elseif((\Illuminate\Support\Facades\Auth::user()->is_admin == 2) && (\Illuminate\Support\Facades\Auth::user()->practitioner_id != null))--}}
{{--                <li class="nav-item">--}}
{{--                    @php($is_admin = \Illuminate\Support\Facades\Cache::get('admin_' . auth()->id())['is_admin'] ?? false)--}}
{{--                    @if($is_admin == true)--}}
{{--                        {{\Illuminate\Support\Facades\Log::info(['2222' => $is_admin, 'id' => auth()->id()])}}--}}
{{--                        <div class="d-flex justify-content-center">--}}
{{--                            <a onclick="resetAdminValueFromLocalStorage()" href="{{url('/client/login-back-to-admin')}}"--}}
{{--                               class="btn btn-sm"--}}
{{--                               style="background-color: #f2661c; color: white;" id="logInBackToAdmin_3" hidden>Back to--}}
{{--                                admin</a>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <div class="collapse {{ ($parentFolder == 'client-dashboard' ? ' show' : '') }}"--}}
{{--                         id="clientdashboardids">--}}
{{--                        <ul class="nav ms-3">--}}
{{--                            <li class="nav-item {{ (Request::is('client/dashboard')  ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('client/dashboard')  ? 'active' : '') }}"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('dashboard') }}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/Dashboard.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"> Dashboard </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('client/stripe-checkout')  ? 'active' : '') }}"--}}
{{--                                data-step="1">--}}
{{--                                <a class="nav-link {{ (Request::is('client/stripe-checkout')  ? 'active' : '') }}"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('intro-assessment') }}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/assessment.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/assessment.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"> Assessment </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('client/all-assessments')  ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('client/all-assessments')  ? 'active' : '') }}"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('all-assessments') }}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/Results.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/Results.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"> Your Assessment Results </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item {{ (Request::is('client/resource') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('client/resource') ? 'active' : '') }}"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('resource') }}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/Library Resources.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"> Resources <br> & Trainings </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item ">--}}
{{--                                <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"--}}
{{--                                   data-bs-toggle="collapse" aria-expanded="false" href="#vrExamples">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/Human Network.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/Human Network.png')}}"><b--}}
{{--                                            class="caret"></b></span>--}}
{{--                                    <span class="sidenav-normal"> Human Network <b class="caret"></b></span>--}}
{{--                                </a>--}}
{{--                                <div class="collapse {{ ($childFolder == 'virtual' ? 'show' : '') }}"--}}
{{--                                     id="vrExamples">--}}
{{--                                    <ul class="nav nav-sm flex-column">--}}
{{--                                        <li class="nav-item {{ (Request::is('connections') ? 'active' : '') }}">--}}
{{--                                            <a class="nav-link {{ (Request::is('connections') ? 'active' : '') }}"--}}
{{--                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('connections?type=connection') }}">--}}
{{--                                                <span class="sidenav-mini-icon"><img--}}
{{--                                                        style="width: 18px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Find and Connect.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"><img--}}
{{--                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Find and Connect.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal">Find Connects</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item {{ (Request::is('client/messages') ? 'active' : '') }}">--}}
{{--                                            <a class="nav-link {{ (Request::is('client/messages') ? 'active' : '') }}"--}}
{{--                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('messages') }}">--}}
{{--                                                <span class="sidenav-mini-icon"><img--}}
{{--                                                        style="width: 18px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Messages.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"><img--}}
{{--                                                        style="width: 18px;margin-left: 28px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Messages.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"> Messages </span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item {{ (Request::is('client/connections') ? 'active' : '') }}">--}}
{{--                                            <a class="nav-link {{ (Request::is('client/connections') ? 'active' : '') }}"--}}
{{--                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('connections?type=request') }}">--}}
{{--                                                <span class="sidenav-mini-icon text-xs"><img--}}
{{--                                                        style="width: 18px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Connection Request.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"><img--}}
{{--                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Connection Request.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"> Connection Request </span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item {{ (Request::is('client/follow') ? 'active' : '') }}">--}}
{{--                                            <a class="nav-link {{ (Request::is('client/follow') ? 'active' : '') }}"--}}
{{--                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('follow?type=follower') }}">--}}
{{--                                                <span class="sidenav-mini-icon"><img--}}
{{--                                                        style="width: 18px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Followers.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"><img--}}
{{--                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Followers.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"> Followers </span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item {{ (Request::is('client/follow') ? 'active' : '') }}">--}}
{{--                                            <a class="nav-link {{ (Request::is('client/follow') ? 'active' : '') }}"--}}
{{--                                               href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('follow?type=following') }}">--}}
{{--                                                <span class="sidenav-mini-icon text-xs"><img--}}
{{--                                                        style="width: 18px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Following.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"><img--}}
{{--                                                        style="width: 18px; margin-left: 28px; margin-right: 10px"--}}
{{--                                                        src="{{URL::asset('assets/icons/Following.png')}}"></span>--}}
{{--                                                <span class="sidenav-normal"> Following </span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('client/pricing') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('client/pricing') ? 'active' : '') }}"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('pricing') }}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/Pricing.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/Pricing.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"> Pricing </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('client/setting') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('client/setting') ? 'active' : '') }}"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('setting') }}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/Settings.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/Settings.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"> Settings </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link"--}}
{{--                                   href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('logout')}}">--}}
{{--                                    <span class="sidenav-mini-icon"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                         src="{{URL::asset('assets/icons/signoutt.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"--}}
{{--                                                                      src="{{URL::asset('assets/icons/signoutt.png')}}"></span>--}}
{{--                                    <span class="sidenav-normal text-bold  "> Log Out </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item mt-2 d-flex justify-content-center">--}}
{{--                                <div class="d-flex justify-content-between">--}}
{{--                                <span class="humanopMiniLogo d-none">--}}
{{--                                    <img src="{{asset('assets/icons/apple_mobile_logo.png')}}"--}}
{{--                                         alt="apple icon"--}}
{{--                                         style="width: 40px; height: 40px; color: white;"/>--}}
{{--                                  </span>--}}
{{--                                    <span class="humanopLogo ">--}}
{{--                                 <img src="{{asset('assets/icons/downloadapple.svg')}}"--}}
{{--                                      alt="apple icon"--}}
{{--                                      style="width: 100px; height: 40px; color: white;"/>--}}
{{--                                </span>--}}
{{--                                    <span class="humanopMiniLogo d-none " style="margin-left: 5px">--}}
{{--                                  <img src="{{asset('assets/icons/android_mobile_logo.png')}}"--}}
{{--                                       alt="android icon"--}}
{{--                                       style="width: 40px; height: 40px;"/>--}}
{{--                                  </span>--}}
{{--                                    <span class="humanopLogo" style="margin-left: 5px">--}}
{{--                                   <img src="{{asset('assets/icons/downloadandroid.png')}}"--}}
{{--                                        alt="android icon"--}}
{{--                                        style="width: 100px;height: 35px;margin-top: 2px;"/>--}}
{{--                                  </span>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <div class="abc mb-3" style="text-align: center">--}}
{{--                                    @if(Auth::user()['is_admin'] == \App\Enums\Admin\Admin::IS_CUSTOMER)--}}
{{--                                        <div class="d-flex justify-content-center mt-3">--}}
{{--                                            <div class="bg-white py-1"--}}
{{--                                                 style="cursor: pointer; width: 60px; height: 60px; border-radius: 50%;"--}}
{{--                                                 data-toggle="modal" data-target="#humanOpWalletModal">--}}
{{--                                                <img src="{{asset('assets/icons/wallet-humanop.svg')}}"--}}
{{--                                                     alt="wallet icon"--}}
{{--                                                     style="width: 50px; height: 50px; color: white;"/>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--            @else--}}
{{--                <li class="nav-item">--}}
{{--                    <a data-bs-toggle="collapse" href="#practitionerdashboardids"--}}
{{--                       class="nav-link {{ ($parentFolder == 'practitioner-dashboard' ? ' active' : '') }}"--}}
{{--                       aria-controls="practitionerdashboardids" role="button" aria-expanded="false">--}}
{{--                        <div--}}
{{--                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">--}}
{{--                            <svg width="12px" height="12px" viewBox="0 0 42 42" version="1.1"--}}
{{--                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">--}}
{{--                                <title>office</title>--}}
{{--                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                                    <g transform="translate(-1869.000000, -293.000000)" fill="#FFFFFF"--}}
{{--                                       fill-rule="nonzero">--}}
{{--                                        <g transform="translate(1716.000000, 291.000000)">--}}
{{--                                            <g id="office" transform="translate(153.000000, 2.000000)">--}}
{{--                                                <path class="color-background"--}}
{{--                                                      d="M12.25,17.5 L8.75,17.5 L8.75,1.75 C8.75,0.78225 9.53225,0 10.5,0 L31.5,0 C32.46775,0 33.25,0.78225 33.25,1.75 L33.25,12.25 L29.75,12.25 L29.75,3.5 L12.25,3.5 L12.25,17.5 Z"--}}
{{--                                                      opacity="0.6"></path>--}}
{{--                                                <path class="color-background"--}}
{{--                                                      d="M40.25,14 L24.5,14 C23.53225,14 22.75,14.78225 22.75,15.75 L22.75,38.5 L19.25,38.5 L19.25,22.75 C19.25,21.78225 18.46775,21 17.5,21 L1.75,21 C0.78225,21 0,21.78225 0,22.75 L0,40.25 C0,41.21775 0.78225,42 1.75,42 L40.25,42 C41.21775,42 42,41.21775 42,40.25 L42,15.75 C42,14.78225 41.21775,14 40.25,14 Z M12.25,36.75 L7,36.75 L7,33.25 L12.25,33.25 L12.25,36.75 Z M12.25,29.75 L7,29.75 L7,26.25 L12.25,26.25 L12.25,29.75 Z M35,36.75 L29.75,36.75 L29.75,33.25 L35,33.25 L35,36.75 Z M35,29.75 L29.75,29.75 L29.75,26.25 L35,26.25 L35,29.75 Z M35,22.75 L29.75,22.75 L29.75,19.25 L35,19.25 L35,22.75 Z"></path>--}}
{{--                                            </g>--}}
{{--                                        </g>--}}
{{--                                    </g>--}}
{{--                                </g>--}}
{{--                            </svg>--}}
{{--                        </div>--}}
{{--                        <span class="nav-link-text ms-1">Practitioner</span>--}}
{{--                    </a>--}}
{{--                    <div class="collapse  {{ ($parentFolder == 'practitioner-dashboard' ? 'show' : '') }}"--}}
{{--                         id="practitionerdashboardids">--}}
{{--                        <ul class="nav ms-4 ps-2">--}}
{{--                            <li class="nav-item {{ (Request::is('practitioner-database')  ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('practitioner-database')  ? 'active' : '') }}"--}}
{{--                                   href="{{ url('practitioner-dashboard') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> D </span>--}}
{{--                                    <span class="sidenav-normal"> Dashboard </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> L </span>--}}
{{--                                    <span class="sidenav-normal"> License Status </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('practitioner-projects') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('practitioner-projects') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('practitioner-projects') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> P </span>--}}
{{--                                    <span class="sidenav-normal"> Projects </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('practitioner-new-user') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('practitioner-new-user') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('practitioner-new-user') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> N </span>--}}
{{--                                    <span class="sidenav-normal"> New Users </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('practitioner-total-sales') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('practitioner-total-sales') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('practitioner-total-sales') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> T </span>--}}
{{--                                    <span class="sidenav-normal"> Total Sales </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> H </span>--}}
{{--                                    <span class="sidenav-normal"> HAI Interface </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('practitioner-billing') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('practitioner-billing') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('practitioner-billing') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> B </span>--}}
{{--                                    <span class="sidenav-normal"> Billing </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('practitioner-pages-account-settings') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('practitioner-pages-account-settings') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('practitioner-pages-account-settings') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> S </span>--}}
{{--                                    <span class="sidenav-normal"> Settings </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a data-bs-toggle="collapse" href="#enterprisedashboardids"--}}
{{--                       class="nav-link {{ ($parentFolder == 'enterprise-dashboard' ? ' active' : '') }}"--}}
{{--                       aria-controls="enterprisedashboardids" role="button" aria-expanded="false">--}}
{{--                        <div--}}
{{--                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">--}}
{{--                            <svg class="text-dark" width="12px" height="12px" viewBox="0 0 42 44" version="1.1"--}}
{{--                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">--}}
{{--                                <title>basket</title>--}}
{{--                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--                                    <g transform="translate(-1869.000000, -741.000000)" fill="#FFFFFF"--}}
{{--                                       fill-rule="nonzero">--}}
{{--                                        <g transform="translate(1716.000000, 291.000000)">--}}
{{--                                            <g id="basket" transform="translate(153.000000, 450.000000)">--}}
{{--                                                <path class="color-background"--}}
{{--                                                      d="M34.080375,13.125 L27.3748125,1.9490625 C27.1377583,1.53795093 26.6972449,1.28682264 26.222716,1.29218729 C25.748187,1.29772591 25.3135593,1.55890827 25.0860125,1.97535742 C24.8584658,2.39180657 24.8734447,2.89865282 25.1251875,3.3009375 L31.019625,13.125 L10.980375,13.125 L16.8748125,3.3009375 C17.1265553,2.89865282 17.1415342,2.39180657 16.9139875,1.97535742 C16.6864407,1.55890827 16.251813,1.29772591 15.777284,1.29218729 C15.3027551,1.28682264 14.8622417,1.53795093 14.6251875,1.9490625 L7.919625,13.125 L0,13.125 L0,18.375 L42,18.375 L42,13.125 L34.080375,13.125 Z"--}}
{{--                                                      opacity="0.595377604"></path>--}}
{{--                                                <path class="color-background"--}}
{{--                                                      d="M3.9375,21 L3.9375,38.0625 C3.9375,40.9619949 6.28800506,43.3125 9.1875,43.3125 L32.8125,43.3125 C35.7119949,43.3125 38.0625,40.9619949 38.0625,38.0625 L38.0625,21 L3.9375,21 Z M14.4375,36.75 L11.8125,36.75 L11.8125,26.25 L14.4375,26.25 L14.4375,36.75 Z M22.3125,36.75 L19.6875,36.75 L19.6875,26.25 L22.3125,26.25 L22.3125,36.75 Z M30.1875,36.75 L27.5625,36.75 L27.5625,26.25 L30.1875,26.25 L30.1875,36.75 Z"></path>--}}
{{--                                            </g>--}}
{{--                                        </g>--}}
{{--                                    </g>--}}
{{--                                </g>--}}
{{--                            </svg>--}}
{{--                        </div>--}}
{{--                        <span class="nav-link-text ms-1">Enterprise</span>--}}
{{--                    </a>--}}
{{--                    <div class="collapse {{ ($parentFolder == 'enterprise-dashboard' ? ' show' : '') }}"--}}
{{--                         id="enterprisedashboardids">--}}
{{--                        <ul class="nav ms-4 ps-2">--}}
{{--                            <li class="nav-item {{ (Request::is('')  ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('')  ? 'active' : '') }}"--}}
{{--                                   href="{{ url('enterprise-dashboard') }}">--}}
{{--                                    <span class="sidenav-mini-icon"></span>--}}
{{--                                    <span class="sidenav-normal"> Dashboard </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('')  ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('')  ? 'active' : '') }}"--}}
{{--                                   href="{{ url('enterprise-team-dashboard') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> T </span>--}}
{{--                                    <span class="sidenav-normal"> Team Dashboard </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('enterprise-roles-management')  ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('enterprise-roles-management')  ? 'active' : '') }}"--}}
{{--                                   href="{{ url('enterprise-roles-management') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> R </span>--}}
{{--                                    <span class="sidenav-normal"> Role Management </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item {{ (Request::is('enterprise-tags-management') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('enterprise-tags-management') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('#') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> T </span>--}}
{{--                                    <span class="sidenav-normal"> Team Management</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item {{ (Request::is('enterprise-team-stats') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('enterprise-team-stats') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('enterprise-team-stats') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> T </span>--}}
{{--                                    <span class="sidenav-normal"> Team Stats </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item {{ (Request::is('enterprise-strategies-development') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('enterprise-strategies-development') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('/enterprise-strategies-development') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> S </span>--}}
{{--                                    <span class="sidenav-normal"> Strategies Development </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('enterprise-billing') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('enterprise-billing') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('enterprise-billing') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> B </span>--}}
{{--                                    <span class="sidenav-normal"> Billing </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item {{ (Request::is('enterprise-pages-account-settings') ? 'active' : '') }}">--}}
{{--                                <a class="nav-link {{ (Request::is('enterprise-pages-account-settings') ? 'active' : '') }}"--}}
{{--                                   href="{{ url('enterprise-pages-account-settings') }}">--}}
{{--                                    <span class="sidenav-mini-icon"> S </span>--}}
{{--                                    <span class="sidenav-normal"> Settings </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}
            @endif

        </ul>
    </div>
</aside>
@push('js')
    <script>

        is_admin = localStorage.getItem('is_admin');

        if (is_admin) {

            const id_arrays = ['logInBackToAdmin_1', 'logInBackToAdmin_2', 'logInBackToAdmin_3'];

            id_arrays.forEach(function (value, key) {

                if (document.getElementById(value)) {

                    console.log('1');

                    document.getElementById(value).removeAttribute("hidden");
                }

            });
        }

        function resetAdminValueFromLocalStorage() {

            localStorage.removeItem('is_admin');
        }

        function showNavbar() {
            $('body').removeClass('g-sidenav-hidden').addClass('g-sidenav-pinned');
            $('#nav-show-btn').attr('onclick', 'hideNavbar()'); // Set onclick to hideNavbar
            $('#menu_back_arrow').attr('src', back_arrow_src);
        }

        function hideNavbar() {
            $('body').addClass('g-sidenav-hidden').removeClass('g-sidenav-pinned');
            $('#nav-show-btn').attr('onclick', 'showNavbar()'); // Set onclick to showNavbar
            $('#menu_back_arrow').attr('src', menu_src);
        }

        $(window).on('resize', function () {
            if ($(window).width() > 1200) {
                $('body').removeClass('g-sidenav-hidden').addClass('g-sidenav-pinned');
            } else {
                $('body').addClass('g-sidenav-hidden').removeClass('g-sidenav-pinned');
            }
        });
    </script>

@endpush
