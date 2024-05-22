@extends('user_type.auth', ['parentFolder' => 'practitioner-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="row">
        <div class="position-relative z-index-2">
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
                <div class="row col-lg-6">
                    <div class="col-lg-6 col-sm-5">
                        <a href="{{ url('pages-users-reports') }}">
                            <div class="card mb-4"
                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                                   style="color: white;">
                                                    Daily Assessments</p>
                                                <h5 class="font-weight-bolder mb-0" style="margin-top: 80px">
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
                                                   style="color: white;">
                                                    Weekly Assessments</p>
                                                <h5 class="font-weight-bolder mb-0" style="margin-top: 80px">
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
                    <div class="col-lg-6 col-sm-5 mt-sm-0 mt-4">
                        <a href="{{ url('pages-users-reports') }}">
                            <div class="card mb-4"
                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                                   style="color: white;">
                                                    Monthly Assessments</p>
                                                <h5 class="font-weight-bolder mb-0" style="margin-top: 80px">
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
                                                   style="color: white;">
                                                    Yearly Assessments</p>
                                                <h5 class="font-weight-bolder mb-0" style="margin-top: 80px">
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
                <div class="row col-lg-6">
                    <div>
                        <div class="card z-index-2">
                            <div class="card-header pb-0">
                                <h6>Sales overview</h6>
                                <p class="text-sm">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold text-white">4% more in 2021</span>
                                </p>
                            </div>
                            <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-12 MuiGrid-grid-md-6 css-ebtddw">
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
                                                <div id="apexchartsx83js5ia"
                                                     class="apexcharts-canvas apexchartsx83js5ia apexcharts-theme-light"
                                                     style="width: 559px; height: 300px;">
                                                    <svg id="SvgjsSvg4256" width="459" height="300"
                                                         xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink"
                                                         xmlns:svgjs="http://svgjs.dev"
                                                         class="apexcharts-svg" xmlns:data="ApexChartsNS"
                                                         transform="translate(0, 0)"
                                                         style="background: transparent;">
                                                        <g id="SvgjsG4258" class="apexcharts-inner apexcharts-graphical"
                                                           transform="translate(53.0146484375, 30)">
                                                            <defs id="SvgjsDefs4257">
                                                                <linearGradient id="SvgjsLinearGradient4262" x1="0"
                                                                                y1="0" x2="0"
                                                                                y2="1">
                                                                    <stop id="SvgjsStop4263" stop-opacity="0.4"
                                                                          stop-color="rgba(216,227,240,0.4)"
                                                                          offset="0"></stop>
                                                                    <stop id="SvgjsStop4264" stop-opacity="0.5"
                                                                          stop-color="rgba(190,209,230,0.5)"
                                                                          offset="1"></stop>
                                                                    <stop id="SvgjsStop4265" stop-opacity="0.5"
                                                                          stop-color="rgba(190,209,230,0.5)"
                                                                          offset="1"></stop>
                                                                </linearGradient>
                                                                <clipPath id="gridRectMaskx83js5ia">
                                                                    <rect id="SvgjsRect4267" width="499.9853515625"
                                                                          height="237.202"
                                                                          x="-2" y="0" rx="0" ry="0" opacity="1"
                                                                          stroke-width="0"
                                                                          stroke="none" stroke-dasharray="0"
                                                                          fill="#fff"></rect>
                                                                </clipPath>
                                                                <clipPath id="forecastMaskx83js5ia"></clipPath>
                                                                <clipPath id="nonForecastMaskx83js5ia"></clipPath>
                                                                <clipPath id="gridRectMarkerMaskx83js5ia">
                                                                    <rect id="SvgjsRect4268" width="499.9853515625"
                                                                          height="241.202"
                                                                          x="-2" y="-2" rx="0" ry="0" opacity="1"
                                                                          stroke-width="0"
                                                                          stroke="none" stroke-dasharray="0"
                                                                          fill="#fff"></rect>
                                                                </clipPath>
                                                            </defs>
                                                            <rect id="SvgjsRect4266" width="0" height="237.202" x="0"
                                                                  y="0" rx="0"
                                                                  ry="0" opacity="1" stroke-width="0"
                                                                  stroke-dasharray="3"
                                                                  fill="url(#SvgjsLinearGradient4262)"
                                                                  class="apexcharts-xcrosshairs"
                                                                  y2="237.202" filter="none" fill-opacity="0.9"></rect>
                                                            <g id="SvgjsG4303"
                                                               class="apexcharts-yaxis apexcharts-xaxis-inversed"
                                                               rel="0">
                                                                <g id="SvgjsG4304"
                                                                   class="apexcharts-yaxis-texts-g apexcharts-xaxis-inversed-texts-g"
                                                                   transform="translate(0, 0)">
                                                                    <text id="SvgjsText4305"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="-15"
                                                                          y="25.87658181818182" text-anchor="end"
                                                                          dominant-baseline="auto"
                                                                          font-size="10px" font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-yaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4306">16-20</tspan>
                                                                        <title>16-20</title>
                                                                    </text>
                                                                    <text id="SvgjsText4307"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="-15"
                                                                          y="73.31698181818182" text-anchor="end"
                                                                          dominant-baseline="auto"
                                                                          font-size="10px" font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-yaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4308">21-26</tspan>
                                                                        <title>21-26</title>
                                                                    </text>
                                                                    <text id="SvgjsText4309"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="-15"
                                                                          y="120.75738181818181" text-anchor="end"
                                                                          dominant-baseline="auto" font-size="10px"
                                                                          font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-yaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4310">26-30</tspan>
                                                                        <title>26-30</title>
                                                                    </text>
                                                                    <text id="SvgjsText4311"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="-15"
                                                                          y="168.1977818181818" text-anchor="end"
                                                                          dominant-baseline="auto"
                                                                          font-size="10px" font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-yaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4312">31-42</tspan>
                                                                        <title>31-42</title>
                                                                    </text>
                                                                    <text id="SvgjsText4313"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="-15"
                                                                          y="215.6381818181818" text-anchor="end"
                                                                          dominant-baseline="auto"
                                                                          font-size="10px" font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-yaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4314">42+</tspan>
                                                                        <title>42+</title>
                                                                    </text>
                                                                </g>
                                                            </g>
                                                            <g id="SvgjsG4283"
                                                               class="apexcharts-xaxis apexcharts-yaxis-inversed">
                                                                <g id="SvgjsG4284" class="apexcharts-xaxis-texts-g"
                                                                   transform="translate(0, -6.666666666666667)">
                                                                    <text
                                                                        id="SvgjsText4285"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="495.9853515625" y="267.202"
                                                                        text-anchor="middle"
                                                                        dominant-baseline="auto" font-size="10px"
                                                                        font-weight="400"
                                                                        fill="#a0aec0"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4287">50</tspan>
                                                                        <title>50</title>
                                                                    </text>
                                                                    <text id="SvgjsText4288"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="396.68828125"
                                                                          y="267.202" text-anchor="middle"
                                                                          dominant-baseline="auto"
                                                                          font-size="10px" font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4290">40</tspan>
                                                                        <title>40</title>
                                                                    </text>
                                                                    <text id="SvgjsText4291"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="297.39121093750003" y="267.202"
                                                                          text-anchor="middle"
                                                                          dominant-baseline="auto" font-size="10px"
                                                                          font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4293">30</tspan>
                                                                        <title>30</title>
                                                                    </text>
                                                                    <text id="SvgjsText4294"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="198.09414062500002" y="267.202"
                                                                          text-anchor="middle"
                                                                          dominant-baseline="auto" font-size="10px"
                                                                          font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4296">20</tspan>
                                                                        <title>20</title>
                                                                    </text>
                                                                    <text id="SvgjsText4297"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="98.79707031250001"
                                                                          y="267.202" text-anchor="middle"
                                                                          dominant-baseline="auto"
                                                                          font-size="10px" font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4299">10</tspan>
                                                                        <title>10</title>
                                                                    </text>
                                                                    <text id="SvgjsText4300"
                                                                          font-family="Helvetica, Arial, sans-serif"
                                                                          x="-0.49999999999994316" y="267.202"
                                                                          text-anchor="middle"
                                                                          dominant-baseline="auto" font-size="10px"
                                                                          font-weight="400"
                                                                          fill="#a0aec0"
                                                                          class="apexcharts-text apexcharts-xaxis-label "
                                                                          style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan4302">0</tspan>
                                                                        <title>0</title>
                                                                    </text>
                                                                </g>
                                                            </g>
                                                            <g id="SvgjsG4315" class="apexcharts-grid">
                                                                <g id="SvgjsG4316"
                                                                   class="apexcharts-gridlines-horizontal">
                                                                    <line id="SvgjsLine4318" x1="0" y1="0"
                                                                          x2="495.9853515625" y2="0"
                                                                          stroke="#56577a" stroke-dasharray="5"
                                                                          stroke-linecap="butt"
                                                                          class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine4319" x1="0" y1="47.4404"
                                                                          x2="495.9853515625"
                                                                          y2="47.4404" stroke="#56577a"
                                                                          stroke-dasharray="5"
                                                                          stroke-linecap="butt"
                                                                          class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine4320" x1="0" y1="94.8808"
                                                                          x2="495.9853515625"
                                                                          y2="94.8808" stroke="#56577a"
                                                                          stroke-dasharray="5"
                                                                          stroke-linecap="butt"
                                                                          class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine4321" x1="0"
                                                                          y1="142.32119999999998"
                                                                          x2="495.9853515625" y2="142.32119999999998"
                                                                          stroke="#56577a"
                                                                          stroke-dasharray="5" stroke-linecap="butt"
                                                                          class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine4322" x1="0" y1="189.7616"
                                                                          x2="495.9853515625"
                                                                          y2="189.7616" stroke="#56577a"
                                                                          stroke-dasharray="5"
                                                                          stroke-linecap="butt"
                                                                          class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine4323" x1="0" y1="237.202"
                                                                          x2="495.9853515625"
                                                                          y2="237.202" stroke="#56577a"
                                                                          stroke-dasharray="5"
                                                                          stroke-linecap="butt"
                                                                          class="apexcharts-gridline"></line>
                                                                </g>
                                                                <g id="SvgjsG4317"
                                                                   class="apexcharts-gridlines-vertical"></g>
                                                                <line id="SvgjsLine4325" x1="0" y1="237.202"
                                                                      x2="495.9853515625"
                                                                      y2="237.202" stroke="transparent"
                                                                      stroke-dasharray="0"
                                                                      stroke-linecap="butt"></line>
                                                                <line id="SvgjsLine4324" x1="0" y1="1" x2="0"
                                                                      y2="237.202"
                                                                      stroke="transparent" stroke-dasharray="0"
                                                                      stroke-linecap="butt">
                                                                </line>
                                                            </g>
                                                            <g id="SvgjsG4269"
                                                               class="apexcharts-bar-series apexcharts-plot-series">
                                                                <g id="SvgjsG4270" class="apexcharts-series" rel="1"
                                                                   seriesName="Salesxbyxage" data:realIndex="0">
                                                                    <path id="SvgjsPath4274"
                                                                          d="M 0.1 7.116059999999997L 190.49414062499997 7.116059999999997Q 198.49414062499997 7.116059999999997 198.49414062499997 15.116059999999997L 198.49414062499997 32.32434Q 198.49414062499997 40.32434 190.49414062499997 40.32434L 190.49414062499997 40.32434L 0.1 40.32434L 0.1 40.32434z"
                                                                          fill="rgba(0,117,255,0.85)" fill-opacity="1"
                                                                          stroke-opacity="1"
                                                                          stroke-linecap="round" stroke-width="0"
                                                                          stroke-dasharray="0"
                                                                          class="apexcharts-bar-area" index="0"
                                                                          clip-path="url(#gridRectMaskx83js5ia)"
                                                                          pathTo="M 0.1 7.116059999999997L 190.49414062499997 7.116059999999997Q 198.49414062499997 7.116059999999997 198.49414062499997 15.116059999999997L 198.49414062499997 32.32434Q 198.49414062499997 40.32434 190.49414062499997 40.32434L 190.49414062499997 40.32434L 0.1 40.32434L 0.1 40.32434z"
                                                                          pathFrom="M 0.1 7.116059999999997L 0.1 7.116059999999997L 0.1 40.32434L 0.1 40.32434L 0.1 40.32434L 0.1 40.32434L 0.1 40.32434L 0.1 7.116059999999997"
                                                                          cy="54.556459999999994"
                                                                          cx="198.49414062499997" j="0" val="20"
                                                                          barHeight="33.20828"
                                                                          barWidth="198.39414062499998"></path>
                                                                    <path id="SvgjsPath4276"
                                                                          d="M 0.1 54.556459999999994L 289.6912109375 54.556459999999994Q 297.6912109375 54.556459999999994 297.6912109375 62.556459999999994L 297.6912109375 79.76473999999999Q 297.6912109375 87.76473999999999 289.6912109375 87.76473999999999L 289.6912109375 87.76473999999999L 0.1 87.76473999999999L 0.1 87.76473999999999z"
                                                                          fill="rgba(0,117,255,0.85)" fill-opacity="1"
                                                                          stroke-opacity="1"
                                                                          stroke-linecap="round" stroke-width="0"
                                                                          stroke-dasharray="0"
                                                                          class="apexcharts-bar-area" index="0"
                                                                          clip-path="url(#gridRectMaskx83js5ia)"
                                                                          pathTo="M 0.1 54.556459999999994L 289.6912109375 54.556459999999994Q 297.6912109375 54.556459999999994 297.6912109375 62.556459999999994L 297.6912109375 79.76473999999999Q 297.6912109375 87.76473999999999 289.6912109375 87.76473999999999L 289.6912109375 87.76473999999999L 0.1 87.76473999999999L 0.1 87.76473999999999z"
                                                                          pathFrom="M 0.1 54.556459999999994L 0.1 54.556459999999994L 0.1 87.76473999999999L 0.1 87.76473999999999L 0.1 87.76473999999999L 0.1 87.76473999999999L 0.1 87.76473999999999L 0.1 54.556459999999994"
                                                                          cy="101.99686" cx="297.6912109375" j="1"
                                                                          val="30"
                                                                          barHeight="33.20828"
                                                                          barWidth="297.59121093749997"></path>
                                                                    <path id="SvgjsPath4278"
                                                                          d="M 0.1 101.99686L 388.88828125 101.99686Q 396.88828125 101.99686 396.88828125 109.99686L 396.88828125 127.20514Q 396.88828125 135.20514 388.88828125 135.20514L 388.88828125 135.20514L 0.1 135.20514L 0.1 135.20514z"
                                                                          fill="rgba(0,117,255,0.85)" fill-opacity="1"
                                                                          stroke-opacity="1"
                                                                          stroke-linecap="round" stroke-width="0"
                                                                          stroke-dasharray="0"
                                                                          class="apexcharts-bar-area" index="0"
                                                                          clip-path="url(#gridRectMaskx83js5ia)"
                                                                          pathTo="M 0.1 101.99686L 388.88828125 101.99686Q 396.88828125 101.99686 396.88828125 109.99686L 396.88828125 127.20514Q 396.88828125 135.20514 388.88828125 135.20514L 388.88828125 135.20514L 0.1 135.20514L 0.1 135.20514z"
                                                                          pathFrom="M 0.1 101.99686L 0.1 101.99686L 0.1 135.20514L 0.1 135.20514L 0.1 135.20514L 0.1 135.20514L 0.1 135.20514L 0.1 101.99686"
                                                                          cy="149.43725999999998" cx="396.88828125"
                                                                          j="2" val="40"
                                                                          barHeight="33.20828"
                                                                          barWidth="396.78828124999995"></path>
                                                                    <path id="SvgjsPath4280"
                                                                          d="M 0.1 149.43725999999998L 190.49414062499997 149.43725999999998Q 198.49414062499997 149.43725999999998 198.49414062499997 157.43725999999998L 198.49414062499997 174.64553999999998Q 198.49414062499997 182.64553999999998 190.49414062499997 182.64553999999998L 190.49414062499997 182.64553999999998L 0.1 182.64553999999998L 0.1 182.64553999999998z"
                                                                          fill="rgba(0,117,255,0.85)" fill-opacity="1"
                                                                          stroke-opacity="1"
                                                                          stroke-linecap="round" stroke-width="0"
                                                                          stroke-dasharray="0"
                                                                          class="apexcharts-bar-area" index="0"
                                                                          clip-path="url(#gridRectMaskx83js5ia)"
                                                                          pathTo="M 0.1 149.43725999999998L 190.49414062499997 149.43725999999998Q 198.49414062499997 149.43725999999998 198.49414062499997 157.43725999999998L 198.49414062499997 174.64553999999998Q 198.49414062499997 182.64553999999998 190.49414062499997 182.64553999999998L 190.49414062499997 182.64553999999998L 0.1 182.64553999999998L 0.1 182.64553999999998z"
                                                                          pathFrom="M 0.1 149.43725999999998L 0.1 149.43725999999998L 0.1 182.64553999999998L 0.1 182.64553999999998L 0.1 182.64553999999998L 0.1 182.64553999999998L 0.1 182.64553999999998L 0.1 149.43725999999998"
                                                                          cy="196.87766" cx="198.49414062499997" j="3"
                                                                          val="20"
                                                                          barHeight="33.20828"
                                                                          barWidth="198.39414062499998"></path>
                                                                    <path id="SvgjsPath4282"
                                                                          d="M 0.1 196.87766L 438.48681640625 196.87766Q 446.48681640625 196.87766 446.48681640625 204.87766L 446.48681640625 222.08594Q 446.48681640625 230.08594 438.48681640625 230.08594L 438.48681640625 230.08594L 0.1 230.08594L 0.1 230.08594z"
                                                                          fill="rgba(0,117,255,0.85)" fill-opacity="1"
                                                                          stroke-opacity="1"
                                                                          stroke-linecap="round" stroke-width="0"
                                                                          stroke-dasharray="0"
                                                                          class="apexcharts-bar-area" index="0"
                                                                          clip-path="url(#gridRectMaskx83js5ia)"
                                                                          pathTo="M 0.1 196.87766L 438.48681640625 196.87766Q 446.48681640625 196.87766 446.48681640625 204.87766L 446.48681640625 222.08594Q 446.48681640625 230.08594 438.48681640625 230.08594L 438.48681640625 230.08594L 0.1 230.08594L 0.1 230.08594z"
                                                                          pathFrom="M 0.1 196.87766L 0.1 196.87766L 0.1 230.08594L 0.1 230.08594L 0.1 230.08594L 0.1 230.08594L 0.1 230.08594L 0.1 196.87766"
                                                                          cy="244.31806" cx="446.48681640625" j="4"
                                                                          val="45"
                                                                          barHeight="33.20828"
                                                                          barWidth="446.38681640625"></path>
                                                                    <g id="SvgjsG4272"
                                                                       class="apexcharts-bar-goals-markers"
                                                                       style="pointer-events: none">
                                                                        <g id="SvgjsG4273"
                                                                           className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG4275"
                                                                           className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG4277"
                                                                           className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG4279"
                                                                           className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG4281"
                                                                           className="apexcharts-bar-goals-groups"></g>
                                                                    </g>
                                                                </g>
                                                                <g id="SvgjsG4271" class="apexcharts-datalabels"
                                                                   data:realIndex="0"></g>
                                                            </g>
                                                            <line id="SvgjsLine4326" x1="0" y1="0" x2="495.9853515625"
                                                                  y2="0"
                                                                  stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                                                  stroke-linecap="butt"
                                                                  class="apexcharts-ycrosshairs"></line>
                                                            <line id="SvgjsLine4327" x1="0" y1="0" x2="495.9853515625"
                                                                  y2="0"
                                                                  stroke-dasharray="0" stroke-width="0"
                                                                  stroke-linecap="butt"
                                                                  class="apexcharts-ycrosshairs-hidden"></line>
                                                            <g id="SvgjsG4328" class="apexcharts-yaxis-annotations"></g>
                                                            <g id="SvgjsG4329" class="apexcharts-xaxis-annotations"></g>
                                                            <g id="SvgjsG4330" class="apexcharts-point-annotations"></g>
                                                        </g>
                                                        <g id="SvgjsG4259" class="apexcharts-annotations"></g>
                                                    </svg>
                                                    <div class="apexcharts-legend" style="max-height: 150px;"></div>
                                                    <div class="apexcharts-tooltip apexcharts-theme-dark">
                                                        <div class="apexcharts-tooltip-title"
                                                             style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"></div>
                                                        <div class="apexcharts-tooltip-series-group"
                                                             style="order: 1;"><span
                                                                class="apexcharts-tooltip-marker"
                                                                style="background-color: rgb(0, 143, 251);"></span>
                                                            <div class="apexcharts-tooltip-text"
                                                                 style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                                <div class="apexcharts-tooltip-y-group"><span
                                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                                        class="apexcharts-tooltip-text-y-value"></span>
                                                                </div>
                                                                <div class="apexcharts-tooltip-goals-group"><span
                                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                                        class="apexcharts-tooltip-text-goals-value"></span>
                                                                </div>
                                                                <div class="apexcharts-tooltip-z-group"><span
                                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                                        class="apexcharts-tooltip-text-z-value"></span>
                                                                </div>
                                                            </div>
                                                        </div>
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
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h5 class="mb-0">Users</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-flush" id="datatable-search">
                                <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Date & Time</th>
                                    <th>Practitioner</th>
                                    <th>Project</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a
                                            href="{{ url('practitioner-user-detail') }}" type="submit"
                                            style="background-color: #f2661c; color: white"
                                            class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-sm font-weight-normal">Tiger Nixon</td>
                                    <td class="text-sm font-weight-normal">2011/04/25</td>
                                    <td class="text-sm font-weight-normal">System Architect</td>
                                    <td class="text-sm font-weight-normal">Edinburgh</td>
                                    <td class="text-sm font-weight-normal">user@gmail.com</td>
                                    <td class="text-sm font-weight-normal"><a href="{{ url('user-detail') }}"
                                                                              type="submit"
                                                                              style="background-color: #f2661c; color: white"
                                                                              class="btn btn-sm float-end mt-2 mb-0">View</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
