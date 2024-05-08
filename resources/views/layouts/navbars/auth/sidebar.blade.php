<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 {{ (\Request::is('pages-rtl') ? 'fixed-end me-3 rotate-caret' : 'fixed-start ms-3' ) }}" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ url('dashboard-default') }}">
            <img src="{{ URL::asset('assets/img/logo-ct.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-3 font-weight-bold">UI Dashboard PRO Laravel</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link {{ ($parentFolder == 'dashboards' ? ' active' : '') }}" aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>shop </title>
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                            <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(0.000000, 148.000000)">
                                <path class="color-background" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z" opacity="0.598981585"></path>
                                <path class="color-background" d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z"></path>
                                </g>
                            </g>
                            </g>
                        </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Admin Dashboard</span>
                </a>
                <div class="collapse {{ ($parentFolder == 'dashboards' ? ' show' : '') }}" id="dashboardsExamples">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{ (Request::is('dashboard-default') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('dashboard-default') ? 'active' : '') }}" href="{{ url('dashboard-default') }}">
                            <span class="sidenav-mini-icon"> D </span>
                            <span class="sidenav-normal"> Dashboard </span>
                        </a>
                        </li>
                        <li class="nav-item {{ (Request::is('users') ? 'active' : '') }}">
                            <a class="nav-link {{ (Request::is('users') ? 'active' : '') }}" href="{{ url('users') }}">
                                <span class="sidenav-mini-icon"> U </span>
                                <span class="sidenav-normal"> Users </span>
                            </a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link {{ ($childFolder == 'virtual' ? 'active' : '') }}" data-bs-toggle="collapse" aria-expanded="false" href="#vrExamples">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal"> CMS <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ ($childFolder == 'virtual' ? 'show' : '') }}" id="vrExamples">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                        <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">
                                            <span class="sidenav-mini-icon text-xs"> V </span>
                                            <span class="sidenav-normal"> Video Buckets </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ (Request::is('dashboard-cms') ? 'active' : '') }}">
                                        <a class="nav-link {{ (Request::is('dashboard-cms') ? 'active' : '') }}" href="{{ url('dashboard-cms') }}">
                                            <span class="sidenav-mini-icon"> A </span>
                                            <span class="sidenav-normal"> Assets Management </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                        <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">
                                            <span class="sidenav-mini-icon text-xs"> V </span>
                                            <span class="sidenav-normal"> Resources Content </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{ (Request::is('') ? 'active' : '') }}">
                                        <a class="nav-link {{ (Request::is('') ? 'active' : '') }}" href="{{ url('#') }}">
                                            <span class="sidenav-mini-icon text-xs"> V </span>
                                            <span class="sidenav-normal"> Daily Tips </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item {{ (Request::is('dashboard-hai-chat') ? 'active' : '') }}">
                        <a class="nav-link {{ (Request::is('dashboard-hai-chat') ? 'active' : '') }}" href="{{ url('dashboard-hai-chat') }}">
                            <span class="sidenav-mini-icon"> H </span>
                            <span class="sidenav-normal"> H.A.I. Chat </span>
                        </a>
                        </li>
                    </ul>
                </div>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#laravelExamples" class="nav-link {{ ($parentFolder == 'laravel' ? ' active' : '') }}" aria-controls="applicationsExamples" role="button" aria-expanded="true">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="1rem" height="1rem" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>laravel </title>
                            <g id="Basic-Elements" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Rounded-Icons" transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g id="Icons-with-opacity" transform="translate(1716.000000, 291.000000)">
                                        <g id="shop-" transform="translate(0.000000, 148.000000)">
                                            <path class="color-background" d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z" id="Path" opacity="0.598981585"></path>
                                            <path class="color-background" d="M505.57 234.62c-3.28-3.53-26.82-32.29-39.51-47.79c-6.75-8.24-12.08-14.75-14.32-17.45l-.18-.22l-.2-.21c-5.22-5.83-12.64-12.51-23.78-12.51a39.78 39.78 0 0 0-5.41.44c-.37.05-.75.11-1.15.15c-2.45.27-10.06 1.5-28.14 4.48c-14 2.29-35.11 5.77-38.31 6.07l-.71.06l-.69.13c-10 1.78-16.62 6.22-19.56 13.19c-1.55 3.68-3.22 11.15 2.94 19.86c1.53 2.22 6.83 9.56 15.94 22.17c6.06 8.4 12.87 17.82 18.75 26L259.9 275L150.66 96.05l-.2-.34l-.23-.33l-.44-.65C145.32 88.17 139.76 80 123.7 80c-1.13 0-2.31 0-3.63.11c-4.6.25-21.42 1.57-40.89 3.11c-21.49 1.69-50.9 4-54.72 4.1h-.73l-.79.08c-9.14.89-15.77 4.6-19.7 11c-6.55 10.69-1.42 22.69.26 26.63C6.87 133 37.56 197.7 64.63 254.81c18 37.94 36.58 77.17 38.1 80.65a34.85 34.85 0 0 0 32.94 21.59a46.62 46.62 0 0 0 9.86-1.1h.21l.2-.05c13.86-3.38 57.83-14.54 89.2-22.59c1.9 3.32 3.9 6.83 6 10.44c21.93 38.5 37.9 66.35 43.16 73.46C287 421 295 432 310.06 432c5.46 0 10.46-1.4 15.74-2.89l1.53-.43h.12c10.53-3 150.69-52.16 157.87-55.35l.22-.1c5.44-2.41 13.66-6.05 16.18-15.4c1.65-6.12.18-12.33-4.38-18.46l-.07-.09l-.07-.09c-.85-1.1-4-5.21-8.27-10.9c-9.13-12.07-23.88-31.57-36.84-48.54c17.37-4.5 38.8-10.11 43.38-11.55c11.47-3.43 14.94-10.69 16-14.73c.79-3.15 1.82-11.2-5.9-18.85zm-320 58.19c-17.81 4.17-30.22 7.08-37.89 8.9c-6.67-13.34-19.74-39.65-32.5-65.33c-29.74-59.92-45.1-90.77-53.18-106.9l8.15-.7c13.34-1.15 31.61-2.72 41.78-3.57c16.76 28.26 74.32 125.3 96.3 162.3zM427.58 172zM310.06 416.4zm53.67-56.95c-24.21 8-37.33 12.37-44.42 14.74c-6.3-10.34-20.16-33.52-32.47-54.19l115.7-29.48c5 6.81 14.57 19.72 33.46 44.93c-18.07 6.04-48.2 16.02-72.27 24zm55.87-121.63l-23.76-31.53c13.67-2.39 21.54-3.77 26.15-4.6l12 14.88l11.94 14.82c-8.2 1.99-17.74 4.32-26.33 6.43z"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <span class="nav-link-text ms-1">Client Dashboard</span>
                </a>
                <div class="collapse show" id="laravelExamples">
                    <ul class="nav ms-4 ps-3">
                        <li class="nav-item {{ (Request::is('client-dashboard')  ? 'active' : '') }}">
                            <a class="nav-link {{ (Request::is('client-dashboard')  ? 'active' : '') }}" href="{{ url('client-dashboard') }}">
                                <span class="sidenav-mini-icon"> R </span>
                                <span class="sidenav-normal"> Results </span>
                            </a>
                        </li>
                        @if(auth()->user()->role_id == 1)

                        @endif
                    </ul>
                </div>
            </li>
            </li>
        </ul>
    </div>
</aside>
