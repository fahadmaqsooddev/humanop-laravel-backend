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
            background-color: #eaf3ff !important;
            color: #1b3a62 !important;
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
<aside style="z-index: 1024 !important; background: #1B3A62 !important"
       class=" {{\App\Helpers\Helpers::getWebUser()['is_admin'] == 2 ? "mt-4 mb-4" : ''}}  sidenav sidenavHideClass myspecial navbar navbar-vertical navbar-expand-xs border-0   {{ (\Request::is('pages-rtl') ? 'fixed-end me-3 rotate-caret' : 'fixed-start' ) }} "
       id="sidenav-main">
    <div class="d-flex">
        <div class="sidenav-header mb-3">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
               aria-hidden="true" id="iconSidenav"></i>
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
        </div>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @if(Auth::user()->hasAnyRole(['super admin', 'sub admin', 'practitioner']) )
                <li class="nav-item">
                    <div class="collapse show" id="dashboardsExamples">
                        <ul class="nav ps-2">
                            @if(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('sub admin'))
                                <li class="nav-item mylink">
                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                       data-bs-toggle="collapse" aria-expanded="false" href="#adminExamples">
                                        <span class="sidenav-mini-icon"><img style="width: 25px; margin-right: 10px"
                                                                             src="{{ asset('assets/new-white-icons/main-dashboard.png') }}"></span>
                                        <span class="sidenav-normal"><img style="width: 25px; margin-right: 10px"
                                                                          src="{{ asset('assets/new-white-icons/main-dashboard.png') }}"></span>
                                        <span class="sidenav-normal"> Admin Dashboard <b class="caret"></b></span>
                                    </a>
                                    <div class="collapse show {{ ($childFolder == 'virtual' ? 'show' : '') }}"
                                         id="adminExamples">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item {{ (Request::is('admin/dashboard') ? 'active-itt-all' : '') }}">
                                                <a class="nav-link {{ (Request::is('admin/dashboard') ? 'active-itt-all' : '') }}"
                                                   href="{{ route('admin_dashboard') }}"><span
                                                        class="sidenav-mini-icon"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{ Request::is('admin/dashboard') ? asset('assets/new-blue-icons/dashboard.png') : asset('assets/new-white-icons/dashboard.png') }}"></span>
                                                    <span class="sidenav-normal"><img
                                                            style="width: 18px; margin-right: 10px"
                                                            src="{{ Request::is('admin/dashboard') ? asset('assets/new-blue-icons/dashboard.png') : asset('assets/new-white-icons/dashboard.png') }}"></span>
                                                    <span class="sidenav-normal"> Dashboard </span>
                                                </a>
                                            </li>
                                            @can('user_management')
                                                <li class="nav-item ">
                                                    <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}"
                                                       data-bs-toggle="collapse" aria-expanded="false"
                                                       href="#usermanagementExamples">
                                                        <span class="sidenav-mini-icon"><img
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/user-management.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/user-management.png') }}"></span>
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
                                                                            src="{{ Request::is('admin/users') ? asset('assets/new-blue-icons/user-database.png') : asset('assets/new-white-icons/user-database.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/users') ? asset('assets/new-blue-icons/user-database.png') : asset('assets/new-white-icons/user-database.png') }}"></span>
                                                                    <span class="sidenav-normal"> User Database </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/deleted-clients') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/deleted-clients') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('deleted_clients') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/deleted-clients') ? asset('assets/new-blue-icons/delete-users.png') : asset('assets/new-white-icons/delete-users.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/deleted-clients') ? asset('assets/new-blue-icons/delete-users.png') : asset('assets/new-white-icons/delete-users.png') }}"></span>
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
                                                            style="width: 25px; margin-right: 10px"
                                                            src="{{ asset('assets/new-white-icons/assessment-management.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/assessment-management.png') }}"></span>
                                                        <span class="sidenav-normal"> Assessment Management <b
                                                                class="caret"></b></span>
                                                    </a>
                                                    <div
                                                        class="collapse {{ Request::is('admin/assessments','admin/user-answers/*','admin/abandoned-assessment','admin/user-grid/*','admin/user-profile-overview/*') ? 'show' : '' }}"
                                                        id="assementexample">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/assessments','admin/user-answers/*','admin/user-grid/*','admin/user-profile-overview/*') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/assessments','admin/user-answers/*','admin/user-grid/*','admin/user-profile-overview/*') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('assessments') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/assessments','admin/user-answers/*','admin/user-grid/*','admin/user-profile-overview/*') ? asset('assets/new-blue-icons/assessment-database.png') : asset('assets/new-white-icons/assessment-database.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/assessments','admin/user-answers/*','admin/user-grid/*','admin/user-profile-overview/*') ? asset('assets/new-blue-icons/assessment-database.png') : asset('assets/new-white-icons/assessment-database.png') }}"></span>
                                                                    <span class="sidenav-normal"> {{\App\Helpers\Helpers::getWebUser()['is_admin'] == 4 ? 'Client' : ''}} Assessment Database </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/abandoned-assessment') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/abandoned-assessment') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_abandoned_assessment') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/abandoned-assessment') ? asset('assets/new-blue-icons/unfinished-assessment.png') : asset('assets/new-white-icons/unfinished-assessment.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/abandoned-assessment') ? asset('assets/new-blue-icons/unfinished-assessment.png') : asset('assets/new-white-icons/unfinished-assessment.png') }}"></span>
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
                                                            style="width: 25px; margin-right: 10px"
                                                            src="{{ asset('assets/new-white-icons/tech-management.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/tech-management.png') }}"></span>
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
                                                                            src="{{ Request::is('admin/questions') ? asset('assets/new-blue-icons/questions.png') : asset('assets/new-white-icons/questions.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/questions') ? asset('assets/new-blue-icons/questions.png') : asset('assets/new-white-icons/questions.png') }}"></span>
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
                                                            style="width: 25px; margin-right: 10px"
                                                            src="{{ asset('assets/new-white-icons/team-management.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/team-management.png') }}"></span>
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
                                                                        src="{{ Request::is('admin/sub-admins') ? asset('assets/new-blue-icons/sub-admin.png') : asset('assets/new-white-icons/sub-admin.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/sub-admins') ? asset('assets/new-blue-icons/sub-admin.png') : asset('assets/new-white-icons/sub-admin.png') }}"></span>
                                                                    <span class="sidenav-normal"> Sub Admins </span>
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
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/cms.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 25px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/cms.png') }}"></span>
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
                                                        'admin/information-icon','admin/payment-history',
                                                        'admin/edit-version-control*','admin/create-version-control',
                                                        'admin/summary-report',
                                                        'admin/assessment-introduction',
                                                        'admin/edit-assessment-intro/*',
                                                        'admin/edit-summary-report/*',
                                                        'admin/create-version-control',
                                                        'admin/edit-version-control/*',
                                                        'admin/edit-code/*',
                                                       'admin/admin_get_client_invite',
                                                       'admin/pricing-plans') ? 'show' : '' }}"
                                                        id="vrExamples">
                                                        <ul class="nav nav-sm flex-column">
                                                            <li class="nav-item {{ (Request::is('admin/assessment-walkthrough') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/assessment-walkthrough') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_get_assessment_walkthrough') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/assessment-walkthrough') ? asset('assets/new-blue-icons/post-assessment-walkthrough.png') : asset('assets/new-white-icons/post-assessment-walkthrough.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/assessment-walkthrough') ? asset('assets/new-blue-icons/post-assessment-walkthrough.png') : asset('assets/new-white-icons/post-assessment-walkthrough.png') }}"></span>
                                                                    <span class="sidenav-normal"> Post Assessment WalkThrough </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/assessment-introduction', 'admin/edit-assessment-intro/*') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/assessment-introduction', 'admin/edit-assessment-intro/*') ? 'active-itt-all' : '') }}"
                                                                   href="{{route('admin_manage_assessment_intro')}}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/assessment-introduction','admin/edit-assessment-intro/*') ? asset('assets/new-blue-icons/assessment-intro-page.png') : asset('assets/new-white-icons/assessment-intro-page.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/assessment-introduction','admin/edit-assessment-intro/*') ? asset('assets/new-blue-icons/assessment-intro-page.png') : asset('assets/new-white-icons/assessment-intro-page.png') }}"></span>
                                                                    <span class="sidenav-normal"> Assessment Introduction Page </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/summary-report','admin/edit-summary-report/*') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/summary-report','admin/edit-summary-report/*') ? 'active-itt-all' : '') }}"
                                                                   href="{{route('admin_manage_summary_report')}}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/summary-report','admin/edit-summary-report/*') ? asset('assets/new-blue-icons/summary-report-content.png') : asset('assets/new-white-icons/summary-report-content.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/summary-report','admin/edit-summary-report/*') ? asset('assets/new-blue-icons/summary-report-content.png') : asset('assets/new-white-icons/summary-report-content.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Summary Report Content </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/resources') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/resources') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_resources') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/resources') ? asset('assets/new-blue-icons/resource-training.png') : asset('assets/new-white-icons/resource-training.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/resources') ? asset('assets/new-blue-icons/resource-training.png') : asset('assets/new-white-icons/resource-training.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Resources & Trainings </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/all-daily-tips') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/all-daily-tips') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_daily_tip') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/all-daily-tips') ? asset('assets/new-blue-icons/daily-tip.png') : asset('assets/new-white-icons/daily-tip.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/all-daily-tips') ? asset('assets/new-blue-icons/daily-tip.png') : asset('assets/new-white-icons/daily-tip.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Daily Tip Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/all-optimization-plan') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/all-optimization-plan') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_optimization_plan') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/all-optimization-plan') ? asset('assets/new-blue-icons/optimization-plan.png') : asset('assets/new-white-icons/optimization-plan.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/all-optimization-plan') ? asset('assets/new-blue-icons/optimization-plan.png') : asset('assets/new-white-icons/optimization-plan.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Optimization Plan Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/all-intention-plans') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/all-intention-plans') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_all_intention_plan') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/all-intention-plans') ? asset('assets/new-blue-icons/intention-management.png') : asset('assets/new-white-icons/intention-management.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/all-intention-plans') ? asset('assets/new-blue-icons/intention-management.png') : asset('assets/new-white-icons/intention-management.png') }}"></span>
                                                                    <span class="sidenav-normal"> Intention of Use Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/information-icon') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/information-icon') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_get_info') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/information-icon') ? asset('assets/new-blue-icons/info-icon-management.png') : asset('assets/new-white-icons/info-icon-management.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/information-icon') ? asset('assets/new-blue-icons/info-icon-management.png') : asset('assets/new-white-icons/info-icon-management.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Information Icon Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                                                <a class="nav-link {{ (Request::is('') ? 'active' : '') }}"
                                                                   href="{{ url('#') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/videos') ? asset('assets/new-blue-icons/video-management.png') : asset('assets/new-white-icons/video-management.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/videos') ? asset('assets/new-blue-icons/video-management.png') : asset('assets/new-white-icons/video-management.png') }}"></span>
                                                                    <span class="sidenav-normal"> Video Results Management </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/podcast') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/podcast') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('podcast') }}">
                                                                   <span class="sidenav-mini-icon"><img
                                                                           style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                           src="{{ Request::is('admin/podcast') ? asset('assets/new-blue-icons/podcast-management.png') : asset('assets/new-white-icons/podcast-management.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/podcast') ? asset('assets/new-blue-icons/podcast-management.png') : asset('assets/new-white-icons/podcast-management.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Podcast </span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item {{ (Request::is('admin/version-control','admin/edit-version-control/*') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/version-control') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_get_version') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/version-control','admin/edit-version-control/*') ? asset('assets/new-blue-icons/version-management.png') : asset('assets/new-white-icons/version-management.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/version-control','admin/edit-version-control/*') ? asset('assets/new-blue-icons/version-management.png') : asset('assets/new-white-icons/version-management.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Version Pop-Up Management </span>
                                                                </a>
                                                            </li>


                                                            <li class="nav-item {{ (Request::is('admin/codes','admin/edit-code/*') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/codes','admin/edit-code/*') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_manage_code') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/codes','admin/edit-code/*') ? asset('assets/new-blue-icons/code-management.png') : asset('assets/new-white-icons/code-management.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/codes','admin/edit-code/*') ? asset('assets/new-blue-icons/code-management.png') : asset('assets/new-white-icons/code-management.png') }}"></span>
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
                                                                            src="{{ Request::is('admin/client-invites') ? asset('assets/new-blue-icons/client-invites.png') : asset('assets/new-white-icons/client-invites.png') }}"></span>
                                                                        <span class="sidenav-normal"><img
                                                                                style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                                src="{{ Request::is('admin/client-invites') ? asset('assets/new-blue-icons/client-invites.png') : asset('assets/new-white-icons/client-invites.png') }}"></span>
                                                                        <span
                                                                            class="sidenav-normal"> Client Invites </span>
                                                                    </a>
                                                                </li>
                                                            @endif

                                                            <li class="nav-item {{ (Request::is('admin/pricing-plans') ? 'active-itt-all' : '') }}">
                                                                <a class="nav-link {{ (Request::is('admin/pricing-plans') ? 'active-itt-all' : '') }}"
                                                                   href="{{ route('admin_pricing_plan') }}">
                                                                    <span class="sidenav-mini-icon"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/pricing-plans') ? asset('assets/new-blue-icons/pricing-plan.png') : asset('assets/new-white-icons/pricing-plan.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/pricing-plans') ? asset('assets/new-blue-icons/pricing-plan.png') : asset('assets/new-white-icons/pricing-plan.png') }}"></span>
                                                                    <span
                                                                        class="sidenav-normal"> Pricing Plan </span>
                                                                </a>
                                                            </li>
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
                                                            src="{{ asset('assets/new-white-icons/support-admin.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{ asset('assets/new-white-icons/support-admin.png') }}"></span>
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
                                                                        src="{{ Request::is('admin/feedback') ? asset('assets/new-blue-icons/feedback.png') : asset('assets/new-white-icons/feedback.png') }}"></span>
                                                                    <span class="sidenav-normal"><img
                                                                            style="width: 18px; margin-left: 28px; margin-right: 10px"
                                                                            src="{{ Request::is('admin/feedback') ? asset('assets/new-blue-icons/feedback.png') : asset('assets/new-white-icons/feedback.png') }}"></span>
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
                                                                src="{{ Request::is('admin/client-queries') ? asset('assets/new-blue-icons/client-queries.png') : asset('assets/new-white-icons/client-queries.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{ Request::is('admin/client-queries') ? asset('assets/new-blue-icons/client-queries.png') : asset('assets/new-white-icons/client-queries.png') }}"></span>
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
                                                                src="{{ Request::is('admin/approve-queries') ? asset('assets/new-blue-icons/approve-queries.png') : asset('assets/new-white-icons/approve-queries.png') }}"></span>
                                                        <span class="sidenav-normal"><img
                                                                style="width: 18px; margin-right: 10px"
                                                                src="{{ Request::is('admin/approve-queries') ? asset('assets/new-blue-icons/approve-queries.png') : asset('assets/new-white-icons/approve-queries.png') }}"></span>
                                                        <span class="sidenav-normal"> Approve Queries </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>

                            @endif

                            <li class="nav-item {{ (Request::is('admin/settings') ? 'active-itt-all' : '') }}">
                                <a class="nav-link {{ (Request::is('admin/settings') ? 'active-itt-all' : '') }}"
                                   href="{{ route('admin_setting') }}">
                                                                <span class="sidenav-mini-icon"><img
                                                                        style="width: 18px; margin-right: 10px"
                                                                        src="{{ Request::is('admin/settings') ? asset('assets/new-blue-icons/setting.png') : asset('assets/new-white-icons/setting.png') }}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{ Request::is('admin/settings') ? asset('assets/new-blue-icons/setting.png') : asset('assets/new-white-icons/setting.png') }}"></span>
                                    <span class="sidenav-normal"> Settings </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link"
                                   href="{{ url('/logout')}}"><span class="sidenav-mini-icon"><img
                                            style="width: 18px; margin-right: 10px"
                                            src="{{URL::asset('assets/icons/signoutt.png')}}"></span>
                                    <span class="sidenav-normal"><img style="width: 18px; margin-right: 10px"
                                                                      src="{{ asset('assets/new-white-icons/logout.png') }}"></span>
                                    <span class="sidenav-normal text-bold"> Log Out </span>
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
