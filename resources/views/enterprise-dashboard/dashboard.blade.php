@extends('user_type.auth', ['parentFolder' => 'enterprise-dashboard', 'childFolder' => 'none'])

@section('content')
    <div class="container-fluid">
        <div class="page-header min-height-100 border-radius-xl">
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="{{ URL::asset('assets/img/bruce-mars.jpg') }}" alt="profile_image"
                             class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1 text-white">
                            Mark Johnson
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm text-white">
                            Optimal Trait To Be In Right Now:
                        </p>
                        <p class="text-white">Perceptive Trait (Thinking) for Strategy and Problem-Solving Activities</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" href="javascript:;"
                                   role="tab" aria-selected="true">
                                    <span class="ms-1 text-white text-bold">Access Personal <br> Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" href="javascript:;" role="tab"
                                   aria-selected="false">
                                    <span class="ms-1 text-white text-bold">Teams</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <section class="py-3">
            <div class="row mt-lg-4 mt-2">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card mb-4"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Enterprise Stats</p>
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
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card mb-4"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Enterprise Stats</p>
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
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card mb-4"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Enterprise Stats</p>
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
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card mb-4"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold" style="color: white;">
                                            Enterprise Stats</p>
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
                </div>
            </div>
            <div class="row mt-lg-4 mt-2">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM MEMBER
                                OPTIMIZATION HOTSPOTS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> John T : Driver Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Syivia : Space Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Marcus G : Role Optimization</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Tiana R : Personal Support</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)">etc...</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="card" style="border-radius: 3rem !important;">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> COMPANY DISCUSSION<br>
                                FEEDS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Latest Posts:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> General Discussion - </p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Sales Team -</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> HR - </p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Operations - </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM OPTIMIZATION
                                STRATEGIES FOR THE NEXT 90 DAYS</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Summary:</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Optimize Bullpen Seating Based
                                on Alchemy</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Define Director of Sales
                                Role</p>
                            <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> - Separate Director of Operation
                                into 2 position</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-lg-4 mt-2">
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <p class="text-sm mt-3 text-white" style="color: rgb(160, 174, 192)"> TEAM PERFORMANCE R. O.
                                I. METRICS</p>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table table-flush" id="datatable-search">
                                <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>SA</th>
                                    <th>MA</th>
                                    <th>JO</th>
                                    <th>LU</th>
                                    <th>VEN</th>
                                    <th>MER</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="text-center">
                                    <td>Tiger Nixon</td>
                                    <td>user@gmail.com</td>
                                    <td>Male</td>
                                    <td>0</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>2</td>
                                    <td>0</td>
                                    <td style="color: greenyellow">6</td>
                                    <td><a href="{{ url('client-grid') }}" type="submit" style="background-color: #f2661c; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                                </tr>
                                <tr class="text-center">
                                    <td>Nancy Grace</td>
                                    <td>user1@gmail.com</td>
                                    <td>Female</td>
                                    <td>4</td>
                                    <td>0</td>
                                    <td style="color: greenyellow">7</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>5</td>
                                    <td><a href="{{ url('client-grid') }}" type="submit" style="background-color: #f2661c; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                                </tr>
                                <tr class="text-center">
                                    <td>Ronald jordge</td>
                                    <td>user2@gmail.com</td>
                                    <td>Male</td>
                                    <td style="color: greenyellow">7</td>
                                    <td>4</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>0</td>
                                    <td>6</td>
                                    <td><a href="{{ url('client-grid') }}" type="submit" style="background-color: #f2661c; color: white" class="btn btn-sm float-end mt-2 mb-0">View</a></td>
                                </tr>
                      
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('layouts/footers/auth/footer')
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
                                <div class="message bot-message">Welcome to our store! Whether you have a
                                    specific
                                    question or need
                                    assistance, we're here for you. What would you like to know? 😊
                                </div>
                                <div class="message user-message">Shopping guide</div>
                                <div class="message bot-message">Don’t spend hours searching for the right
                                    product.
                                    We’ll help you find
                                    exactly what you need in no-time! Also, feel free to check our items on sale
                                    now. 💥
                                </div>
                                <div class="message user-message">Shopping guide</div>
                                <div class="message bot-message">Don’t spend hours searching for the right
                                    product.
                                    We’ll help you find
                                    exactly what you need in no-time! Also, feel free to check our items on sale
                                    now. 💥
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

@endsection
