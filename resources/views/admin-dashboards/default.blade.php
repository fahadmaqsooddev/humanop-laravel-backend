@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row">
        <div class="col-lg-7 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h2 class="font-weight-bolder mb-0">Statistics</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 col-sm-5">
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Daily Assessments</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                9
                                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Weekly Assessments
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                45
                                                <span class="text-success text-sm font-weight-bolder">+3%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Monthly Assessments
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                130
                                                <span class="text-success text-sm font-weight-bolder">+2%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">Yearly Assessments
                                            </p>
                                            <h5 class="font-weight-bolder mb-0">
                                                1560
                                                <span class="text-success text-sm font-weight-bolder">+5%</span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-5 col-sm-5">
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Practitioner Activity Stats</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Revenue Stats</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-5 col-sm-5">
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Project Stats</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('pages-users-reports') }}">
                        <div class="card mt-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                Enterprise stats</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                <span class="text-success text-sm font-weight-bolder"></span>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <div class="row mt-4">
                <div class="col-12 col-md-10">
                    <div class="card ">
                        <div class="card-header pb-0 p-3">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-2">Sales by Country</h6>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center ">
                                <tbody>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ URL::asset('assets/img/icons/flags/US.png') }}"
                                                     alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">United States</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">2500</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$230,900</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">29.9%</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ URL::asset('assets/img/icons/flags/DE.png') }}"
                                                     alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">Germany</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">3.900</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$440,000</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">40.22%</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ URL::asset('assets/img/icons/flags/GB.png') }}"
                                                     alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">Great Britain</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">1.400</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$190,700</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">23.44%</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <img src="{{ URL::asset('assets/img/icons/flags/BR.png') }}"
                                                     alt="Country flag">
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                <h6 class="text-sm mb-0">Brasil</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                            <h6 class="text-sm mb-0">562</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">Value:</p>
                                            <h6 class="text-sm mb-0">$143,960</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-sm">
                                        <div class="col text-center">
                                            <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                            <h6 class="text-sm mb-0">32.14%</h6>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-5 col-sm-5">
                <a href="{{ url('pages-users-reports') }}">
                    <div class="card mb-4"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Practitioner Activity Stats</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{ url('pages-users-reports') }}">
                    <div class="card"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Revenue Stats</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-5 col-sm-5">
                <a href="{{ url('pages-users-reports') }}">
                    <div class="card"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Project Stats</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            <span class="text-success text-sm font-weight-bolder"></span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row mt-4">
            <div class=" card z-index-2 MuiGrid-root MuiGrid-item MuiGrid-grid-xs-12 MuiGrid-grid-md-6 css-ebtddw">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Sales by Country</h6>
                    </div>
                </div>

                <div
                    class="MuiPaper-root MuiPaper-elevation MuiPaper-rounded MuiPaper-elevation1 MuiCard-root css-1bdukto">
                    <div class="MuiBox-root css-7wmvu8">
                        <div class="MuiBox-root css-msefqn">
                            <div class="MuiBox-root css-vz2i8e">
                                <div class="MuiTypography-root MuiTypography-button css-1j3s1sb"></div>
                            </div>
                        </div>
                        <div class="MuiBox-root css-1vsk1q1">
                            <div>
                                <div id="myChart" class="apexcharts-canvas apexcharts71k78jxej apexcharts-theme-light"
                                     style="width: 635px; height: 300px;">
                                    <svg id="SvgjsSvg10348" width="560" height="300"
                                         xmlns="http://www.w3.org/2000/svg" version="1.1"
                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                         xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable"
                                         xmlns:data="ApexChartsNS"
                                         transform="translate(0, 0)" style="background: transparent;">
                                        <g id="SvgjsG10350" class="apexcharts-inner apexcharts-graphical"
                                           transform="translate(57.54877604166667, 30)">
                                            <defs id="SvgjsDefs10349">
                                                <clipPath id="gridRectMask71k78jxej">
                                                    <rect id="SvgjsRect10356" width="593.0254947916667" height="242.202"
                                                          x="-16.5" y="-2.5" rx="0"
                                                          ry="0" opacity="1" stroke-width="0" stroke="none"
                                                          stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                                <clipPath id="forecastMask71k78jxej"></clipPath>
                                                <clipPath id="nonForecastMask71k78jxej"></clipPath>
                                                <clipPath id="gridRectMarkerMask71k78jxej">
                                                    <rect id="SvgjsRect10357" width="566.0254947916667" height="241.202"
                                                          x="-2" y="-2" rx="0"
                                                          ry="0" opacity="1" stroke-width="0" stroke="none"
                                                          stroke-dasharray="0" fill="#fff"></rect>
                                                </clipPath>
                                            </defs>
                                            <line id="SvgjsLine10355" x1="0" y1="0" x2="0" y2="237.202" stroke="#b6b6b6"
                                                  stroke-dasharray="3"
                                                  stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0"
                                                  width="1" height="237.202"
                                                  fill="#b1b9c4" filter="none" fill-opacity="0.9"
                                                  stroke-width="1"></line>
                                            <g id="SvgjsG10383" class="apexcharts-xaxis" transform="translate(0, 0)">
                                                <g id="SvgjsG10384" class="apexcharts-xaxis-texts-g"
                                                   transform="translate(0, -4)">
                                                    <text
                                                        id="SvgjsText10386" font-family="Helvetica, Arial, sans-serif"
                                                        x="0" y="266.202"
                                                        text-anchor="middle" dominant-baseline="auto" font-size="10px"
                                                        font-weight="400"
                                                        fill="#a0aec0" class="apexcharts-text apexcharts-xaxis-label "
                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10387">Jan</tspan>
                                                        <title>Jan</title>
                                                    </text>
                                                    <text id="SvgjsText10389" font-family="Helvetica, Arial, sans-serif"
                                                          x="80.28935639880953" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10390">Feb</tspan>
                                                        <title>Feb</title>
                                                    </text>
                                                    <text id="SvgjsText10392" font-family="Helvetica, Arial, sans-serif"
                                                          x="160.57871279761906" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10393">Mar</tspan>
                                                        <title>Mar</title>
                                                    </text>
                                                    <text id="SvgjsText10395" font-family="Helvetica, Arial, sans-serif"
                                                          x="240.86806919642856" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10396">Apr</tspan>
                                                        <title>Apr</title>
                                                    </text>
                                                    <text id="SvgjsText10398" font-family="Helvetica, Arial, sans-serif"
                                                          x="321.1574255952381" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10399">May</tspan>
                                                        <title>May</title>
                                                    </text>
                                                    <text id="SvgjsText10401" font-family="Helvetica, Arial, sans-serif"
                                                          x="401.4467819940477" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10402">Jun</tspan>
                                                        <title>Jun</title>
                                                    </text>
                                                    <text id="SvgjsText10404" font-family="Helvetica, Arial, sans-serif"
                                                          x="481.73613839285724" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10405">Jul</tspan>
                                                        <title>Jul</title>
                                                    </text>
                                                    <text id="SvgjsText10407" font-family="Helvetica, Arial, sans-serif"
                                                          x="562.0254947916668" y="266.202" text-anchor="middle"
                                                          dominant-baseline="auto"
                                                          font-size="10px" font-weight="400" fill="#a0aec0"
                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                        <tspan id="SvgjsTspan10408">Aug</tspan>
                                                        <title>Aug</title>
                                                    </text>
                                                </g>
                                            </g>
                                            <g id="SvgjsG10432" class="apexcharts-grid">
                                                <g id="SvgjsG10433" class="apexcharts-gridlines-horizontal">
                                                    <line id="SvgjsLine10443" x1="-9.425729166666667" y1="0"
                                                          x2="571.4512239583333" y2="0"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10444" x1="-9.425729166666667"
                                                          y1="39.53366666666667"
                                                          x2="571.4512239583333" y2="39.53366666666667" stroke="#56577a"
                                                          stroke-dasharray="5"
                                                          stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line id="SvgjsLine10445" x1="-9.425729166666667"
                                                          y1="79.06733333333334"
                                                          x2="571.4512239583333" y2="79.06733333333334" stroke="#56577a"
                                                          stroke-dasharray="5"
                                                          stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line id="SvgjsLine10446" x1="-9.425729166666667" y1="118.601"
                                                          x2="571.4512239583333"
                                                          y2="118.601" stroke="#56577a" stroke-dasharray="5"
                                                          stroke-linecap="butt"
                                                          class="apexcharts-gridline"></line>
                                                    <line id="SvgjsLine10447" x1="-9.425729166666667"
                                                          y1="158.13466666666667"
                                                          x2="571.4512239583333" y2="158.13466666666667"
                                                          stroke="#56577a" stroke-dasharray="5"
                                                          stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line id="SvgjsLine10448" x1="-9.425729166666667"
                                                          y1="197.66833333333335"
                                                          x2="571.4512239583333" y2="197.66833333333335"
                                                          stroke="#56577a" stroke-dasharray="5"
                                                          stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line id="SvgjsLine10449" x1="-9.425729166666667"
                                                          y1="237.20200000000003"
                                                          x2="571.4512239583333" y2="237.20200000000003"
                                                          stroke="#56577a" stroke-dasharray="5"
                                                          stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                </g>
                                                <g id="SvgjsG10434" class="apexcharts-gridlines-vertical">
                                                    <line id="SvgjsLine10435" x1="0" y1="0" x2="0" y2="237.202"
                                                          stroke="#56577a"
                                                          stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline"></line>
                                                    <line id="SvgjsLine10436" x1="80.28935639880953" y1="0"
                                                          x2="80.28935639880953" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10437" x1="160.57871279761906" y1="0"
                                                          x2="160.57871279761906" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10438" x1="240.8680691964286" y1="0"
                                                          x2="240.8680691964286" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10439" x1="321.1574255952381" y1="0"
                                                          x2="321.1574255952381" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10440" x1="401.4467819940477" y1="0"
                                                          x2="401.4467819940477" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10441" x1="481.73613839285724" y1="0"
                                                          x2="481.73613839285724" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                    <line id="SvgjsLine10442" x1="562.0254947916668" y1="0"
                                                          x2="562.0254947916668" y2="237.202"
                                                          stroke="#56577a" stroke-dasharray="5" stroke-linecap="butt"
                                                          class="apexcharts-gridline">
                                                    </line>
                                                </g>
                                                <line id="SvgjsLine10451" x1="0" y1="237.202" x2="562.0254947916667"
                                                      y2="237.202"
                                                      stroke="transparent" stroke-dasharray="0"
                                                      stroke-linecap="butt"></line>
                                                <line id="SvgjsLine10450" x1="0" y1="1" x2="0" y2="237.202"
                                                      stroke="transparent"
                                                      stroke-dasharray="0" stroke-linecap="butt"></line>
                                            </g>
                                            <g id="SvgjsG10358" class="apexcharts-bar-series apexcharts-plot-series">
                                                <g id="SvgjsG10359" class="apexcharts-series" rel="1"
                                                   seriesName="OrganicxSearch"
                                                   data:realIndex="0">
                                                    <path id="SvgjsPath10363"
                                                          d="M -3.211574255952381 276.73566666666665L -3.211574255952381 104.7875333333333Q -3.211574255952381 102.7875333333333 -1.2115742559523812 102.7875333333333L -3.788425744047619 102.7875333333333Q -1.7884257440476188 102.7875333333333 -1.7884257440476188 104.7875333333333L -1.7884257440476188 104.7875333333333L -1.7884257440476188 276.73566666666665L -1.7884257440476188 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M -3.211574255952381 276.73566666666665L -3.211574255952381 104.7875333333333Q -3.211574255952381 102.7875333333333 -1.2115742559523812 102.7875333333333L -3.788425744047619 102.7875333333333Q -1.7884257440476188 102.7875333333333 -1.7884257440476188 104.7875333333333L -1.7884257440476188 104.7875333333333L -1.7884257440476188 276.73566666666665L -1.7884257440476188 276.73566666666665z"
                                                          pathFrom="M -3.211574255952381 276.73566666666665L -3.211574255952381 276.73566666666665L -1.7884257440476188 276.73566666666665L -1.7884257440476188 276.73566666666665L -1.7884257440476188 276.73566666666665L -1.7884257440476188 276.73566666666665L -1.7884257440476188 276.73566666666665L -3.211574255952381 276.73566666666665"
                                                          cy="102.7875333333333" cx="0.7115742559523812" j="0" val="440"
                                                          barHeight="173.94813333333335"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10365"
                                                          d="M 77.07778214285715 276.73566666666665L 77.07778214285715 79.09064999999998Q 77.07778214285715 77.09064999999998 79.07778214285715 77.09064999999998L 76.5009306547619 77.09064999999998Q 78.5009306547619 77.09064999999998 78.5009306547619 79.09064999999998L 78.5009306547619 79.09064999999998L 78.5009306547619 276.73566666666665L 78.5009306547619 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 77.07778214285715 276.73566666666665L 77.07778214285715 79.09064999999998Q 77.07778214285715 77.09064999999998 79.07778214285715 77.09064999999998L 76.5009306547619 77.09064999999998Q 78.5009306547619 77.09064999999998 78.5009306547619 79.09064999999998L 78.5009306547619 79.09064999999998L 78.5009306547619 276.73566666666665L 78.5009306547619 276.73566666666665z"
                                                          pathFrom="M 77.07778214285715 276.73566666666665L 77.07778214285715 276.73566666666665L 78.5009306547619 276.73566666666665L 78.5009306547619 276.73566666666665L 78.5009306547619 276.73566666666665L 78.5009306547619 276.73566666666665L 78.5009306547619 276.73566666666665L 77.07778214285715 276.73566666666665"
                                                          cy="77.09064999999998" cx="81.0009306547619" j="1" val="505"
                                                          barHeight="199.64501666666666"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10367"
                                                          d="M 157.3671385416667 276.73566666666665L 157.3671385416667 115.06628666666666Q 157.3671385416667 113.06628666666666 159.3671385416667 113.06628666666666L 156.79028705357146 113.06628666666666Q 158.79028705357146 113.06628666666666 158.79028705357146 115.06628666666666L 158.79028705357146 115.06628666666666L 158.79028705357146 276.73566666666665L 158.79028705357146 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 157.3671385416667 276.73566666666665L 157.3671385416667 115.06628666666666Q 157.3671385416667 113.06628666666666 159.3671385416667 113.06628666666666L 156.79028705357146 113.06628666666666Q 158.79028705357146 113.06628666666666 158.79028705357146 115.06628666666666L 158.79028705357146 115.06628666666666L 158.79028705357146 276.73566666666665L 158.79028705357146 276.73566666666665z"
                                                          pathFrom="M 157.3671385416667 276.73566666666665L 157.3671385416667 276.73566666666665L 158.79028705357146 276.73566666666665L 158.79028705357146 276.73566666666665L 158.79028705357146 276.73566666666665L 158.79028705357146 276.73566666666665L 158.79028705357146 276.73566666666665L 157.3671385416667 276.73566666666665"
                                                          cy="113.06628666666666" cx="161.29028705357146" j="2"
                                                          val="414" barHeight="163.66938"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10369"
                                                          d="M 237.6564949404762 276.73566666666665L 237.6564949404762 13.46476333333328Q 237.6564949404762 11.46476333333328 239.6564949404762 11.46476333333328L 237.07964345238096 11.46476333333328Q 239.07964345238096 11.46476333333328 239.07964345238096 13.46476333333328L 239.07964345238096 13.46476333333328L 239.07964345238096 276.73566666666665L 239.07964345238096 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 237.6564949404762 276.73566666666665L 237.6564949404762 13.46476333333328Q 237.6564949404762 11.46476333333328 239.6564949404762 11.46476333333328L 237.07964345238096 11.46476333333328Q 239.07964345238096 11.46476333333328 239.07964345238096 13.46476333333328L 239.07964345238096 13.46476333333328L 239.07964345238096 276.73566666666665L 239.07964345238096 276.73566666666665z"
                                                          pathFrom="M 237.6564949404762 276.73566666666665L 237.6564949404762 276.73566666666665L 239.07964345238096 276.73566666666665L 239.07964345238096 276.73566666666665L 239.07964345238096 276.73566666666665L 239.07964345238096 276.73566666666665L 239.07964345238096 276.73566666666665L 237.6564949404762 276.73566666666665"
                                                          cy="11.46476333333328" cx="241.57964345238096" j="3" val="671"
                                                          barHeight="265.27090333333337"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10371"
                                                          d="M 317.94585133928575 276.73566666666665L 317.94585133928575 215.48179999999996Q 317.94585133928575 213.48179999999996 319.94585133928575 213.48179999999996L 317.3689998511905 213.48179999999996Q 319.3689998511905 213.48179999999996 319.3689998511905 215.48179999999996L 319.3689998511905 215.48179999999996L 319.3689998511905 276.73566666666665L 319.3689998511905 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 317.94585133928575 276.73566666666665L 317.94585133928575 215.48179999999996Q 317.94585133928575 213.48179999999996 319.94585133928575 213.48179999999996L 317.3689998511905 213.48179999999996Q 319.3689998511905 213.48179999999996 319.3689998511905 215.48179999999996L 319.3689998511905 215.48179999999996L 319.3689998511905 276.73566666666665L 319.3689998511905 276.73566666666665z"
                                                          pathFrom="M 317.94585133928575 276.73566666666665L 317.94585133928575 276.73566666666665L 319.3689998511905 276.73566666666665L 319.3689998511905 276.73566666666665L 319.3689998511905 276.73566666666665L 319.3689998511905 276.73566666666665L 319.3689998511905 276.73566666666665L 317.94585133928575 276.73566666666665"
                                                          cy="213.48179999999996" cx="321.8689998511905" j="4" val="160"
                                                          barHeight="63.25386666666667"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10373"
                                                          d="M 398.23520773809526 276.73566666666665L 398.23520773809526 61.30049999999997Q 398.23520773809526 59.30049999999997 400.23520773809526 59.30049999999997L 397.65835625 59.30049999999997Q 399.65835625 59.30049999999997 399.65835625 61.30049999999997L 399.65835625 61.30049999999997L 399.65835625 276.73566666666665L 399.65835625 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 398.23520773809526 276.73566666666665L 398.23520773809526 61.30049999999997Q 398.23520773809526 59.30049999999997 400.23520773809526 59.30049999999997L 397.65835625 59.30049999999997Q 399.65835625 59.30049999999997 399.65835625 61.30049999999997L 399.65835625 61.30049999999997L 399.65835625 276.73566666666665L 399.65835625 276.73566666666665z"
                                                          pathFrom="M 398.23520773809526 276.73566666666665L 398.23520773809526 276.73566666666665L 399.65835625 276.73566666666665L 399.65835625 276.73566666666665L 399.65835625 276.73566666666665L 399.65835625 276.73566666666665L 399.65835625 276.73566666666665L 398.23520773809526 276.73566666666665"
                                                          cy="59.30049999999997" cx="402.15835625" j="5" val="550"
                                                          barHeight="217.43516666666667"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10375"
                                                          d="M 478.52456413690476 276.73566666666665L 478.52456413690476 140.36783333333332Q 478.52456413690476 138.36783333333332 480.52456413690476 138.36783333333332L 477.9477126488095 138.36783333333332Q 479.9477126488095 138.36783333333332 479.9477126488095 140.36783333333332L 479.9477126488095 140.36783333333332L 479.9477126488095 276.73566666666665L 479.9477126488095 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 478.52456413690476 276.73566666666665L 478.52456413690476 140.36783333333332Q 478.52456413690476 138.36783333333332 480.52456413690476 138.36783333333332L 477.9477126488095 138.36783333333332Q 479.9477126488095 138.36783333333332 479.9477126488095 140.36783333333332L 479.9477126488095 140.36783333333332L 479.9477126488095 276.73566666666665L 479.9477126488095 276.73566666666665z"
                                                          pathFrom="M 478.52456413690476 276.73566666666665L 478.52456413690476 276.73566666666665L 479.9477126488095 276.73566666666665L 479.9477126488095 276.73566666666665L 479.9477126488095 276.73566666666665L 479.9477126488095 276.73566666666665L 479.9477126488095 276.73566666666665L 478.52456413690476 276.73566666666665"
                                                          cy="138.36783333333332" cx="482.4477126488095" j="6" val="350"
                                                          barHeight="138.36783333333332"
                                                          barWidth="6.423148511904762"></path>
                                                    <path id="SvgjsPath10377"
                                                          d="M 558.8139205357143 276.73566666666665L 558.8139205357143 224.17920666666663Q 558.8139205357143 222.17920666666663 560.8139205357143 222.17920666666663L 558.237069047619 222.17920666666663Q 560.237069047619 222.17920666666663 560.237069047619 224.17920666666663L 560.237069047619 224.17920666666663L 560.237069047619 276.73566666666665L 560.237069047619 276.73566666666665z"
                                                          fill="rgba(1,181,116,0.85)" fill-opacity="1" stroke="#01b574"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-bar-area"
                                                          index="0" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 558.8139205357143 276.73566666666665L 558.8139205357143 224.17920666666663Q 558.8139205357143 222.17920666666663 560.8139205357143 222.17920666666663L 558.237069047619 222.17920666666663Q 560.237069047619 222.17920666666663 560.237069047619 224.17920666666663L 560.237069047619 224.17920666666663L 560.237069047619 276.73566666666665L 560.237069047619 276.73566666666665z"
                                                          pathFrom="M 558.8139205357143 276.73566666666665L 558.8139205357143 276.73566666666665L 560.237069047619 276.73566666666665L 560.237069047619 276.73566666666665L 560.237069047619 276.73566666666665L 560.237069047619 276.73566666666665L 560.237069047619 276.73566666666665L 558.8139205357143 276.73566666666665"
                                                          cy="222.17920666666663" cx="562.737069047619" j="7" val="138"
                                                          barHeight="54.55646"
                                                          barWidth="6.423148511904762"></path>
                                                    <g id="SvgjsG10361" class="apexcharts-bar-goals-markers"
                                                       style="pointer-events: none">
                                                        <g id="SvgjsG10362" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10364" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10366" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10368" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10370" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10372" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10374" className="apexcharts-bar-goals-groups"></g>
                                                        <g id="SvgjsG10376" className="apexcharts-bar-goals-groups"></g>
                                                    </g>
                                                </g>
                                            </g>
                                            <g id="SvgjsG10378" class="apexcharts-line-series apexcharts-plot-series">
                                                <g id="SvgjsG10379" class="apexcharts-series" seriesName="Referral"
                                                   data:longestSeries="true"
                                                   rel="1" data:realIndex="1">
                                                    <path id="SvgjsPath10382"
                                                          d="M 0 185.01755999999997C 28.101274739583335 185.01755999999997 52.1880816592262 110.29892999999998 80.28935639880953 110.29892999999998C 108.39063113839286 110.29892999999998 132.47743805803572 137.57715999999996 160.57871279761906 137.57715999999996C 188.67998753720238 137.57715999999996 212.76679445684525 168.80875666666662 240.86806919642856 168.80875666666662C 268.9693439360119 168.80875666666662 293.0561508556548 220.20252333333332 321.1574255952381 220.20252333333332C 349.25870033482147 220.20252333333332 373.3455072544643 188.97092666666663 401.4467819940476 188.97092666666663C 429.548056733631 188.97092666666663 453.6348636532738 208.34242333333333 481.73613839285713 208.34242333333333C 509.8374131324405 208.34242333333333 533.9242200520833 153.7859633333333 562.0254947916667 153.7859633333333"
                                                          fill="none" fill-opacity="1" stroke="rgba(0,117,255,0.85)"
                                                          stroke-opacity="1"
                                                          stroke-linecap="butt" stroke-width="5" stroke-dasharray="0"
                                                          class="apexcharts-line"
                                                          index="1" clip-path="url(#gridRectMask71k78jxej)"
                                                          pathTo="M 0 185.01755999999997C 28.101274739583335 185.01755999999997 52.1880816592262 110.29892999999998 80.28935639880953 110.29892999999998C 108.39063113839286 110.29892999999998 132.47743805803572 137.57715999999996 160.57871279761906 137.57715999999996C 188.67998753720238 137.57715999999996 212.76679445684525 168.80875666666662 240.86806919642856 168.80875666666662C 268.9693439360119 168.80875666666662 293.0561508556548 220.20252333333332 321.1574255952381 220.20252333333332C 349.25870033482147 220.20252333333332 373.3455072544643 188.97092666666663 401.4467819940476 188.97092666666663C 429.548056733631 188.97092666666663 453.6348636532738 208.34242333333333 481.73613839285713 208.34242333333333C 509.8374131324405 208.34242333333333 533.9242200520833 153.7859633333333 562.0254947916667 153.7859633333333"
                                                          pathFrom="M -1 276.73566666666665L -1 276.73566666666665L 80.28935639880953 276.73566666666665L 160.57871279761906 276.73566666666665L 240.86806919642856 276.73566666666665L 321.1574255952381 276.73566666666665L 401.4467819940476 276.73566666666665L 481.73613839285713 276.73566666666665L 562.0254947916667 276.73566666666665">
                                                    </path>
                                                    <g id="SvgjsG10380" class="apexcharts-series-markers-wrap"
                                                       data:realIndex="1">
                                                        <g class="apexcharts-series-markers">
                                                            <circle id="SvgjsCircle10457" r="0" cx="0" cy="0"
                                                                    class="apexcharts-marker wyr42zdcf"
                                                                    stroke="#ffffff" fill="#0075ff" fill-opacity="1"
                                                                    stroke-width="2" stroke-opacity="0.9"
                                                                    default-marker-size="0"></circle>
                                                        </g>
                                                    </g>
                                                </g>
                                                <g id="SvgjsG10360" class="apexcharts-datalabels"
                                                   data:realIndex="0"></g>
                                                <g id="SvgjsG10381" class="apexcharts-datalabels"
                                                   data:realIndex="1"></g>
                                            </g>
                                            <line id="SvgjsLine10452" x1="-9.425729166666667" y1="0"
                                                  x2="571.4512239583333" y2="0"
                                                  stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                                  stroke-linecap="butt"
                                                  class="apexcharts-ycrosshairs"></line>
                                            <line id="SvgjsLine10453" x1="-9.425729166666667" y1="0"
                                                  x2="571.4512239583333" y2="0"
                                                  stroke-dasharray="0" stroke-width="0" stroke-linecap="butt"
                                                  class="apexcharts-ycrosshairs-hidden"></line>
                                            <g id="SvgjsG10454" class="apexcharts-yaxis-annotations"></g>
                                            <g id="SvgjsG10455" class="apexcharts-xaxis-annotations"></g>
                                            <g id="SvgjsG10456" class="apexcharts-point-annotations"></g>
                                            <rect id="SvgjsRect10458" width="0" height="0" x="0" y="0" rx="0" ry="0"
                                                  opacity="1"
                                                  stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"
                                                  class="apexcharts-zoom-rect">
                                            </rect>
                                            <rect id="SvgjsRect10459" width="0" height="0" x="0" y="0" rx="0" ry="0"
                                                  opacity="1"
                                                  stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"
                                                  class="apexcharts-selection-rect"></rect>
                                        </g>
                                        <rect id="SvgjsRect10354" width="0" height="0" x="0" y="0" rx="0" ry="0"
                                              opacity="1"
                                              stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                        <g id="SvgjsG10409" class="apexcharts-yaxis" rel="0"
                                           transform="translate(14.123046875, 0)">
                                            <g id="SvgjsG10410" class="apexcharts-yaxis-texts-g">
                                                <text id="SvgjsText10412"
                                                      font-family="Helvetica, Arial, sans-serif" x="20" y="31.6"
                                                      text-anchor="end"
                                                      dominant-baseline="auto" font-size="10px" font-weight="400"
                                                      fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10413">700</tspan>
                                                    <title>700</title>
                                                </text>
                                                <text id="SvgjsText10415" font-family="Helvetica, Arial, sans-serif"
                                                      x="20"
                                                      y="71.13366666666667" text-anchor="end" dominant-baseline="auto"
                                                      font-size="10px"
                                                      font-weight="400" fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10416">600</tspan>
                                                    <title>600</title>
                                                </text>
                                                <text id="SvgjsText10418" font-family="Helvetica, Arial, sans-serif"
                                                      x="20"
                                                      y="110.66733333333335" text-anchor="end" dominant-baseline="auto"
                                                      font-size="10px"
                                                      font-weight="400" fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10419">500</tspan>
                                                    <title>500</title>
                                                </text>
                                                <text id="SvgjsText10421" font-family="Helvetica, Arial, sans-serif"
                                                      x="20"
                                                      y="150.20100000000002" text-anchor="end" dominant-baseline="auto"
                                                      font-size="10px"
                                                      font-weight="400" fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10422">400</tspan>
                                                    <title>400</title>
                                                </text>
                                                <text id="SvgjsText10424" font-family="Helvetica, Arial, sans-serif"
                                                      x="20"
                                                      y="189.7346666666667" text-anchor="end" dominant-baseline="auto"
                                                      font-size="10px"
                                                      font-weight="400" fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10425">300</tspan>
                                                    <title>300</title>
                                                </text>
                                                <text id="SvgjsText10427" font-family="Helvetica, Arial, sans-serif"
                                                      x="20"
                                                      y="229.26833333333337" text-anchor="end" dominant-baseline="auto"
                                                      font-size="10px"
                                                      font-weight="400" fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10428">200</tspan>
                                                    <title>200</title>
                                                </text>
                                                <text id="SvgjsText10430" font-family="Helvetica, Arial, sans-serif"
                                                      x="20"
                                                      y="268.8020000000001" text-anchor="end" dominant-baseline="auto"
                                                      font-size="10px"
                                                      font-weight="400" fill="#a0aec0"
                                                      class="apexcharts-text apexcharts-yaxis-label "
                                                      style="font-family: Helvetica, Arial, sans-serif;">
                                                    <tspan id="SvgjsTspan10431">100</tspan>
                                                    <title>100</title>
                                                </text>
                                            </g>
                                        </g>
                                        <g id="SvgjsG10351" class="apexcharts-annotations"></g>
                                    </svg>
                                    <div class="apexcharts-legend" style="max-height: 150px;"></div>
                                    <div class="apexcharts-tooltip apexcharts-theme-dark">
                                        <div class="apexcharts-tooltip-title"
                                             style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                        <div class="apexcharts-tooltip-series-group" style="order: 1;"><span
                                                class="apexcharts-tooltip-marker"
                                                style="background-color: rgb(1, 181, 116);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                 style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-tooltip-series-group" style="order: 2;"><span
                                                class="apexcharts-tooltip-marker"
                                                style="background-color: rgb(0, 117, 255);"></span>
                                            <div class="apexcharts-tooltip-text"
                                                 style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                <div class="apexcharts-tooltip-y-group"><span
                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                        class="apexcharts-tooltip-text-y-value"></span></div>
                                                <div class="apexcharts-tooltip-goals-group"><span
                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                        class="apexcharts-tooltip-text-goals-value"></span></div>
                                                <div class="apexcharts-tooltip-z-group"><span
                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                        class="apexcharts-tooltip-text-z-value"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-dark">
                                        <div class="apexcharts-xaxistooltip-text"
                                             style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                    </div>
                                    <div
                                        class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-dark">
                                        <div class="apexcharts-yaxistooltip-text"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        // Dummy data
        var dummyData = [
            {'id': 1, 'value': 10},
            {'id': 2, 'value': 20},
            {'id': 3, 'value': 15}
            // Add more dummy data as needed
        ];

        // Extract ids and values from dummy data
        var ids = dummyData.map(item => item.id);
        var values = dummyData.map(item => item.value);

        // Create a chart
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ids,
                datasets: [{
                    label: 'Dummy Data',
                    data: values,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <div class="row mt-4">
        <div class="col-lg-5 mb-lg-0 mb-4">
            <div class="card z-index-2">
                <div class="card-body p-3">
                    <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
                        <div class="chart">
                            <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                        </div>
                    </div>
                    <h6 class="ms-2 mt-4 mb-0"> Active Users </h6>
                    <p class="text-sm ms-2"> (<span class="font-weight-bolder">+23%</span>) than last week </p>
                    <div class="container border-radius-lg">
                        <div class="row">
                            <div class="col-3 py-3 ps-0">
                                <div class="d-flex mb-2">
                                    <div
                                        class="icon icon-shape icon-xxs shadow border-radius-sm bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center">
                                        <svg width="10px" height="10px" viewBox="0 0 40 44" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
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
                                                                  d="M30.9090909,7.27272727 L1.81818182,7.27272727 C0.814545455,7.27272727 0,8.08727273 0,9.09090909 L0,41.8181818 C0,42.8218182 0.814545455,43.6363636 1.81818182,43.6363636 L30.9090909,43.6363636 C31.9127273,43.6363636 32.7272727,42.8218182 32.7272727,41.8181818 L32.7272727,9.09090909 C32.7272727,8.08727273 31.9127273,7.27272727 30.9090909,7.27272727 Z M18.1818182,34.5454545 L7.27272727,34.5454545 L7.27272727,30.9090909 L18.1818182,30.9090909 L18.1818182,34.5454545 Z M25.4545455,27.2727273 L7.27272727,27.2727273 L7.27272727,23.6363636 L25.4545455,23.6363636 L25.4545455,27.2727273 Z M25.4545455,20 L7.27272727,20 L7.27272727,16.3636364 L25.4545455,16.3636364 L25.4545455,20 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <p class="text-xs mt-1 mb-0 font-weight-bold">Users</p>
                                </div>
                                <h4 class="font-weight-bolder">36K</h4>
                                <div class="progress w-75">
                                    <div class="progress-bar bg-dark w-60" role="progressbar" aria-valuenow="60"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 py-3 ps-0">
                                <div class="d-flex mb-2">
                                    <div
                                        class="icon icon-shape icon-xxs shadow border-radius-sm bg-gradient-info text-center me-2 d-flex align-items-center justify-content-center">
                                        <svg width="10px" height="10px" viewBox="0 0 40 40" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>spaceship</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-1720.000000, -592.000000)" fill="#FFFFFF"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(4.000000, 301.000000)">
                                                            <path class="color-background"
                                                                  d="M39.3,0.706666667 C38.9660984,0.370464027 38.5048767,0.192278529 38.0316667,0.216666667 C14.6516667,1.43666667 6.015,22.2633333 5.93166667,22.4733333 C5.68236407,23.0926189 5.82664679,23.8009159 6.29833333,24.2733333 L15.7266667,33.7016667 C16.2013871,34.1756798 16.9140329,34.3188658 17.535,34.065 C17.7433333,33.98 38.4583333,25.2466667 39.7816667,1.97666667 C39.8087196,1.50414529 39.6335979,1.04240574 39.3,0.706666667 Z M25.69,19.0233333 C24.7367525,19.9768687 23.3029475,20.2622391 22.0572426,19.7463614 C20.8115377,19.2304837 19.9992882,18.0149658 19.9992882,16.6666667 C19.9992882,15.3183676 20.8115377,14.1028496 22.0572426,13.5869719 C23.3029475,13.0710943 24.7367525,13.3564646 25.69,14.31 C26.9912731,15.6116662 26.9912731,17.7216672 25.69,19.0233333 L25.69,19.0233333 Z">
                                                            </path>
                                                            <path class="color-background"
                                                                  d="M1.855,31.4066667 C3.05106558,30.2024182 4.79973884,29.7296005 6.43969145,30.1670277 C8.07964407,30.6044549 9.36054508,31.8853559 9.7979723,33.5253085 C10.2353995,35.1652612 9.76258177,36.9139344 8.55833333,38.11 C6.70666667,39.9616667 0,40 0,40 C0,40 0,33.2566667 1.855,31.4066667 Z">
                                                            </path>
                                                            <path class="color-background"
                                                                  d="M17.2616667,3.90166667 C12.4943643,3.07192755 7.62174065,4.61673894 4.20333333,8.04166667 C3.31200265,8.94126033 2.53706177,9.94913142 1.89666667,11.0416667 C1.5109569,11.6966059 1.61721591,12.5295394 2.155,13.0666667 L5.47,16.3833333 C8.55036617,11.4946947 12.5559074,7.25476565 17.2616667,3.90166667 L17.2616667,3.90166667 Z"
                                                                  opacity="0.598539807"></path>
                                                            <path class="color-background"
                                                                  d="M36.0983333,22.7383333 C36.9280725,27.5056357 35.3832611,32.3782594 31.9583333,35.7966667 C31.0587397,36.6879974 30.0508686,37.4629382 28.9583333,38.1033333 C28.3033941,38.4890431 27.4704606,38.3827841 26.9333333,37.845 L23.6166667,34.53 C28.5053053,31.4496338 32.7452344,27.4440926 36.0983333,22.7383333 L36.0983333,22.7383333 Z"
                                                                  opacity="0.598539807"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <p class="text-xs mt-1 mb-0 font-weight-bold">Clicks</p>
                                </div>
                                <h4 class="font-weight-bolder">2m</h4>
                                <div class="progress w-75">
                                    <div class="progress-bar bg-dark w-90" role="progressbar" aria-valuenow="90"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 py-3 ps-0">
                                <div class="d-flex mb-2">
                                    <div
                                        class="icon icon-shape icon-xxs shadow border-radius-sm bg-gradient-warning text-center me-2 d-flex align-items-center justify-content-center">
                                        <svg width="10px" height="10px" viewBox="0 0 43 36" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>credit-card</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(453.000000, 454.000000)">
                                                            <path class="color-background"
                                                                  d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                                                  opacity="0.593633743"></path>
                                                            <path class="color-background"
                                                                  d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <p class="text-xs mt-1 mb-0 font-weight-bold">Sales</p>
                                </div>
                                <h4 class="font-weight-bolder">435$</h4>
                                <div class="progress w-75">
                                    <div class="progress-bar bg-dark w-30" role="progressbar" aria-valuenow="30"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="col-3 py-3 ps-0">
                                <div class="d-flex mb-2">
                                    <div
                                        class="icon icon-shape icon-xxs shadow border-radius-sm bg-gradient-danger text-center me-2 d-flex align-items-center justify-content-center">
                                        <svg width="10px" height="10px" viewBox="0 0 40 40" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <title>settings</title>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-2020.000000, -442.000000)" fill="#FFFFFF"
                                                   fill-rule="nonzero">
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g transform="translate(304.000000, 151.000000)">
                                                            <polygon class="color-background" opacity="0.596981957"
                                                                     points="18.0883333 15.7316667 11.1783333 8.82166667 13.3333333 6.66666667 6.66666667 0 0 6.66666667 6.66666667 13.3333333 8.82166667 11.1783333 15.315 17.6716667">
                                                            </polygon>
                                                            <path class="color-background"
                                                                  d="M31.5666667,23.2333333 C31.0516667,23.2933333 30.53,23.3333333 30,23.3333333 C29.4916667,23.3333333 28.9866667,23.3033333 28.48,23.245 L22.4116667,30.7433333 L29.9416667,38.2733333 C32.2433333,40.575 35.9733333,40.575 38.275,38.2733333 L38.275,38.2733333 C40.5766667,35.9716667 40.5766667,32.2416667 38.275,29.94 L31.5666667,23.2333333 Z"
                                                                  opacity="0.596981957"></path>
                                                            <path class="color-background"
                                                                  d="M33.785,11.285 L28.715,6.215 L34.0616667,0.868333333 C32.82,0.315 31.4483333,0 30,0 C24.4766667,0 20,4.47666667 20,10 C20,10.99 20.1483333,11.9433333 20.4166667,12.8466667 L2.435,27.3966667 C0.95,28.7083333 0.0633333333,30.595 0.00333333333,32.5733333 C-0.0583333333,34.5533333 0.71,36.4916667 2.11,37.89 C3.47,39.2516667 5.27833333,40 7.20166667,40 C9.26666667,40 11.2366667,39.1133333 12.6033333,37.565 L27.1533333,19.5833333 C28.0566667,19.8516667 29.01,20 30,20 C35.5233333,20 40,15.5233333 40,10 C40,8.55166667 39.685,7.18 39.1316667,5.93666667 L33.785,11.285 Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <p class="text-xs mt-1 mb-0 font-weight-bold">Items</p>
                                </div>
                                <h4 class="font-weight-bolder">43</h4>
                                <div class="progress w-75">
                                    <div class="progress-bar bg-dark w-50" role="progressbar" aria-valuenow="50"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <h6>Sales overview</h6>
                    <p class="text-sm">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold">4% more</span> in 2021
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-lg-4 mt-2">
        <div class="fixed-plugin">
            <a style="background-color: #f2661c; color: white; border-radius: 5px !important;"
               class="btn col-12 fixed-plugin-button">H.A.I CHAT INTERFACE</a>
            <div class="card shadow-lg blur" style="background-color: #0f1534 !important;">
                <div class="card-header pb-0 pt-3" style="background-color: #f2661c">
                    <h5 class="text-center text-white">H.A.I CHAT INTERFACE</h5>
                    <div class="float-start d-flex">
                        <img src="{{asset('assets/img/team-3.jpg')}}" alt="Avatar" class="avatar">
                        <div class="header-info text-white">
                            <div class="header-title">Need help?</div>
                            <div class="header-subtitle">We reply immediately</div>
                        </div>
                    </div>
                    <div class="float-end mt-4">
                        <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                            <i class="fa fa-close"></i>
                        </button>
                    </div>
                    <!-- End Toggle Button -->
                </div>
                <div class="d-flex">
                    <div class="col-3">
                        <div class="chatbox">
                            <div class="chatbox-content" style="background-color: #f2661c">
                                <div class="mt-4 chat-hover d-flex" style="cursor: pointer;">
                                    <i class="fa fa-plus" style="color: white; margin-top: 8px"></i>
                                    <h5 class="text-white text-bold" style="margin-left: 12px">New chat</h5>
                                </div>
                                <hr>
                                <div class="mt-4">
                                    <h5 class="text-white text-bold">Today chat</h5>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Set Permissions for Directory</p>
                                </div>
                                <div class="mt-4">
                                    <h5 class="text-white text-bold">Yesterday chat</h5>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Permission Denied Error Troubleshooting</p>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Customizing Embedding Videos</p>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Bootstrap: Utilizar bordes blancos</p>
                                </div>
                                <div class="mt-4">
                                    <h5 class="text-white text-bold">Previous 30 Days chat</h5>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Merge Videos with FFmpeg</p>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Permission Denied Error Troubleshooting</p>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Customizing Embedding Videos</p>
                                    <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                        Bootstrap: Utilizar bordes blancos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="chatbox">
                            <div class="chatbox-content" id="chatbox-content">
                                <div style="display: flex; justify-content: flex-start">
                                    <div class="message bot-message">Welcome to our store! Whether you have a
                                        specific
                                        question or need
                                        assistance, we're here for you. What would you like to know? 😊
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: flex-end">
                                    <div class="message user-message">Shopping guide</div>
                                </div>
                                <div style="display: flex; justify-content: flex-start">
                                    <div class="message bot-message">Welcome to our store! Whether you have a
                                        specific
                                        question or need
                                        assistance, we're here for you. What would you like to know? 😊
                                    </div>
                                </div>
                                <div style="display: flex; justify-content: flex-end">
                                    <div class="message user-message">Shopping guide</div>
                                </div>
                                <div style="display: flex; justify-content: flex-start">
                                    <div class="message bot-message">Welcome to our store! Whether you have a
                                        specific
                                        question or need
                                        assistance, we're here for you. What would you like to know? 😊
                                    </div>
                                </div>
                            </div>
                            <div class="chatbox-input">
                                <input type="text" id="user-input" placeholder="Type your message here...">
                                <button id="send-button">&#9658;</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-10 mt-sm-3 mt-7 me-lg-7">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ URL::asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/threejs.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/orbit-controls.js') }}"></script>
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#fff",
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                    maxBarThickness: 6
                },],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        },
                    },
                },
            },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    tension: 0.4,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#cb0c9f",
                    borderWidth: 3,
                    backgroundColor: gradientStroke1,
                    fill: true,
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    maxBarThickness: 6

                },
                    {
                        label: "Websites",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });


        (function () {
            const container = document.getElementById("globe");
            const canvas = container.getElementsByTagName("canvas")[0];

            const globeRadius = 100;
            const globeWidth = 4098 / 2;
            const globeHeight = 1968 / 2;

            function convertFlatCoordsToSphereCoords(x, y) {
                let latitude = ((x - globeWidth) / globeWidth) * -180;
                let longitude = ((y - globeHeight) / globeHeight) * -90;
                latitude = (latitude * Math.PI) / 180;
                longitude = (longitude * Math.PI) / 180;
                const radius = Math.cos(longitude) * globeRadius;

                return {
                    x: Math.cos(latitude) * radius,
                    y: Math.sin(longitude) * globeRadius,
                    z: Math.sin(latitude) * radius
                };
            }

            function makeMagic(points) {
                const {
                    width,
                    height
                } = container.getBoundingClientRect();

                // 1. Setup scene
                const scene = new THREE.Scene();
                // 2. Setup camera
                const camera = new THREE.PerspectiveCamera(45, width / height);
                // 3. Setup renderer
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
                renderer.setSize(width, height);
                // 4. Add points to canvas
                // - Single geometry to contain all points.
                const mergedGeometry = new THREE.Geometry();
                // - Material that the dots will be made of.
                const pointGeometry = new THREE.SphereGeometry(0.5, 1, 1);
                const pointMaterial = new THREE.MeshBasicMaterial({
                    color: "#989db5",
                });

                for (let point of points) {
                    const {
                        x,
                        y,
                        z
                    } = convertFlatCoordsToSphereCoords(
                        point.x,
                        point.y,
                        width,
                        height
                    );

                    if (x && y && z) {
                        pointGeometry.translate(x, y, z);
                        mergedGeometry.merge(pointGeometry);
                        pointGeometry.translate(-x, -y, -z);
                    }
                }

                const globeShape = new THREE.Mesh(mergedGeometry, pointMaterial);
                scene.add(globeShape);

                container.classList.add("peekaboo");

                // Setup orbital controls
                camera.orbitControls = new THREE.OrbitControls(camera, canvas);
                camera.orbitControls.enableKeys = false;
                camera.orbitControls.enablePan = false;
                camera.orbitControls.enableZoom = false;
                camera.orbitControls.enableDamping = false;
                camera.orbitControls.enableRotate = true;
                camera.orbitControls.autoRotate = true;
                camera.position.z = -265;

                function animate() {
                    // orbitControls.autoRotate is enabled so orbitControls.update
                    // must be called inside animation loop.
                    camera.orbitControls.update();
                    requestAnimationFrame(animate);
                    renderer.render(scene, camera);
                }

                animate();
            }

            function hasWebGL() {
                const gl =
                    canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
                if (gl && gl instanceof WebGLRenderingContext) {
                    return true;
                } else {
                    return false;
                }
            }

            function init() {
                if (hasWebGL()) {
                    window
                    window.fetch("https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard-pro/assets/js/points.json")
                        .then(response => response.json())
                        .then(data => {
                            makeMagic(data.points);
                        });
                }
            }

            init();
        })();
    </script>
@endpush
