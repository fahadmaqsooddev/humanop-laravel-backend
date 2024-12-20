
<div>
    <div class="row position-relative mb-2 mt-4 mx-3" style="height: auto; padding:10px;background: #8BB1AB;border-radius: 40px !important;">
        <div class="row sepratediv">


        <div class="col-6 my-auto  myprofile" style="padding-left: 30px">
            <div class="d-flex ">
                <div>
                    <img
                        src="{{ Auth::user()['photo_url']['url'] ?? URL::asset('assets/img/default-user-image.png') }}"
                        height="80" width="80" alt="profile_image"
                        class="shadow-sm  user_profile_image" style="border-radius: 50%">
                </div>
                <div style="margin-top: 12px">
                    <p class="mb-0 "
                       style="font-weight: bold;color: #F4ECE0;font-size: 18px;margin-left:10px">
                        Welcome Back {{Auth::user()['first_name']}} !</p>
                    @if(!empty(\App\Helpers\Helpers::getWebUser()['optional_trait']))
                        <p class="mb-0 font-weight-bold text-sm"
                           style="color: white;margin-left:10px">
                            Optimal Trait To Be In Right Now:
                        </p>
                        <h6 style="color: white;font-size: 18px;margin-left:10px; cursor:pointer;"
                            onclick="goToProfileOverviewPage('{{\App\Helpers\Helpers::getWebUser()['optional_trait'][2]}}','style_{{\App\Helpers\Helpers::getWebUser()['optional_trait'][0]}}')">
                            <strong>{{ \App\Helpers\Helpers::getWebUser()['optional_trait'][0] }}</strong>
                        </h6>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-6 my-auto myaccount">
            <div class="d-flex justify-content-around px-4" >
                <button class="bg-transparent text-center py-2"
                        style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: auto"
                        data-bs-toggle="modal"
                        data-bs-target="#qrCodeModal"
                >
                    Get free pro version
                </button>
                @if(\App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::IS_ADMIN || \App\Helpers\Helpers::getWebUser()->is_admin == \App\Enums\Admin\Admin::SUB_ADMIN)

                    <a href="{{route('assessments')}}" class="bg-transparent  position-relative text-center py-2 px-4"
                       style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;">
                        Access your results
                        <div class="position-absolute"
                             style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                            <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                 alt="notification" width="40" height="40">
                        </div>
                    </a>




                    {{--                                    <a href="{{route('assessments')}}" style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                    {{--                                       class="btn-sm-1 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                    {{--                                    </a>--}}

                @elseif(\App\Helpers\Helpers::getWebUser()->assessments()->where('page', 0)->count() > 0)

                    @php
                        $userId = \App\Helpers\Helpers::getWebUser()['id'];

                        $assessment = \App\Models\Assessment::where('user_id', $userId)->where('page', 0)->latest()->first();

                    @endphp
                    @if(\App\Helpers\Helpers::getWebUser()['is_admin'] == 4)
                        {{--                                        <a href="{{route('practitioner_profile_overview', $assessment['id'])}}"--}}
                        {{--                                           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                        {{--                                           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                        {{--                                        </a>--}}


                        <a href="{{route('practitioner_profile_overview', $assessment['id'])}}" class="bg-transparent  position-relative text-center py-2 "
                           style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                            Access your results
                            <div class="position-absolute"
                                 style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                     alt="notification" width="40" height="40">
                            </div>
                        </a>
                    @elseif(\App\Helpers\Helpers::getWebUser()['practitioner_id'] != null)
                        {{--                                        <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('practitioner-client-profile-overview', ['id' => $assessment['id'] ]) }}"--}}
                        {{--                                           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                        {{--                                           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                        {{--                                        </a>--}}

                        <a href="{{ \App\Helpers\Practitioner\PractitionerHelpers::makePractitionerUrl('practitioner-client-profile-overview', ['id' => $assessment['id'] ]) }}" class="bg-transparent text-center position-relative py-2"
                           style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                            Access your results
                            <div class="position-absolute"
                                 style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                     alt="notification" width="40" height="40">
                            </div>
                        </a>
                    @else
                        {{--                                        <a href="{{route('user_profile_overview', $assessment['id'])}}"--}}
                        {{--                                           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
                        {{--                                           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">Access Latest Results--}}
                        {{--                                        </a>--}}

                        <a href="{{route('user_profile_overview', $assessment['id'])}}" class="bg-transparent  text-center position-relative py-2 "
                           style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                            Access your results
                            <div class="position-absolute"
                                 style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                                <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                     alt="notification" width="40" height="40">
                            </div>
                        </a>

                    @endif

                @else
                    <a  class="bg-transparent text-center position-relative py-2 "
                        data-toggle="tooltip" data-placement="top" title="Take the assessment first"  style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px;width: 48%">
                        Access your results
                        <div class="position-absolute"
                             style="right: -10px;top: -16px;height: 36px;width: 36px;background: #8BB1AB;padding-left: 0px;">
                            <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"
                                 alt="notification" width="40" height="40">
                        </div>
                    </a>
                    {{--                                    <button--}}
                    {{--                                        style="padding: 10px 16px 10px 16px; border-radius: 7px; background-color: grey;"--}}
                    {{--                                        data-toggle="tooltip" data-placement="top" title="Take the assessment first"--}}
                    {{--                                        class="text-white btn-sm-2 btn-md-3 btn-lg-5  navButtonResponsive">Access Latest Results--}}
                    {{--                                    </button>--}}

                @endif






                {{--                                --}}
                {{--                                <button class="bg-transparent w-70 py-2 position-relative"--}}
                {{--                                        style="color: #F4ECE0;border: 1px solid #1C365E;border-radius: 24px;font-size: 18px">--}}
                {{--                                    Access your results--}}
                {{--                                    <div class="position-absolute"--}}
                {{--                                         style="right: -10px;top: -16px;height: 36px;width: 36px;background: #FCB178;padding-left: 0px;">--}}
                {{--                                        <img src="{{asset('assets/new-design/icon/dashboard/Arrow.svg')}}"--}}
                {{--                                             alt="notification" width="40" height="40">--}}
                {{--                                    </div>--}}
                {{--                                </button>--}}

            </div>
        </div>
    </div>
        <div class="position-absolute"
             style="right: -10px;top: -25px;height: 60px;width: 60px;border-radius: 50%;background: #1C365E;padding-left: 5px;border: 10px solid #8BB1AB">
            <img src="{{asset('assets/new-design/icon/dashboard/bell.svg')}}" alt="notification"
                 width="30" height="40">
        </div>
        <div class="position-absolute"
             style="left: 40%">
            <img src="{{asset('assets/new-design/icon/dashboard/header_badge.svg')}}" alt="notification" style="width: 10%"
                 height="120" >
        </div>
    </div>
    <div class="body-container d-flex flex-column flex-md-row align-items-center align-items-md-stretch  p-2 pb-5">
        <div
            class="side-bar w-md-25"
            id="message-bar">
            <div>
                <ul class="nav nav-tabs w-100 bg-dark" style="border-radius: 15px" role="tablist">
                    <li class="nav-item w-50">
                        <a class="nav-link active text-light text-center" style="border-radius: 15px" data-bs-toggle="tab"
                           href="#tabs-1" onclick="toggleDisplay('messages')"
                           role="tab">Messages</a>
                    </li>
                    <li class="nav-item w-50">
                        <a class="nav-link text-light text-center" style="border-radius: 15px" data-bs-toggle="tab"
                           onclick="toggleDisplay('connections')"
                           href="#tabs-2"
                           role="tab">Connections</a>
                    </li>
                </ul>
            </div>
            <form class="messenger-search-form d-flex align-items-center justify-content-center ">
                <button type="submit" class="btn btn-danger mb-0" style="box-shadow: none">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 16px; color: #F95520"></i>
                </button>
                <input wire:model="filter_text" style="color: #F95520" type="text" id="search-bar"
                       class="form-control searchUser"
                       placeholder="Search" maxlength="25">
            </form>
            <div class="tab-content">
                <div class="tab-pane" id="tabs-2" role="tabpanel">
                    @if(empty($chats[0]))
                        <p class="p-2">No chat found</p>
                    @endif
                    @foreach($chats as $chat)

                        <div class="messages-list mt-4 clientChatBox-{{$chat['id']}}"
                             onclick="scrollToSection('messenger-container1', {{$chat['id']}})">
                            @if($chat['user_data'])

                                <div class="chathead flex-shrink-0"
                                     style="background-color: #e4dede; width: 60px; height: 60px; border-radius: 50%;">
                                    <img src="{{$chat['user_data']['photo_url']['thumbnail_url'] ?? null}}"
                                         class="img-fluid rounded-circle">
                                </div>
                                <div class="chatlist cursor-pointer flex-grow-1 rounded-2 p-2"
                                     wire:click="messages({{$chat->id}}, {{$chat['user_data'] ?? null}})">
                                    <div class="chatlist-header d-flex justify-content-between">
                                    <span class="fw-bold"
                                          style="font-size: 15px;">{{$chat['user_data'] ? $chat['user_data']['first_name'] . ' ' . $chat['user_data']['last_name'] : ""}}</span>
                                        <span class="text-end"
                                              style="font-size: 10px;">{{$chat['lastMessage']['created_at'] ?? null}}</span>
                                    </div>
                                    <span
                                        style="font-size: 12px;">{{ $chat['lastMessage'] ? (strlen($chat['lastMessage']['message']) > 25 ? substr($chat['lastMessage']['message'], 0, 25) . "..." : substr($chat['lastMessage']['message'], 0, 25)) : null}}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="tab-pane active w-100" id="tabs-1" role="tabpanel">
                    @if(empty($chats[0]))
                        <p class="p-2">No chat found</p>
                    @endif
                    @foreach($chats as $chat)

                        <div class="messages-list mt-4 clientChatBox-{{$chat['id']}}"
                             onclick="scrollToSection('messenger-container1', {{$chat['id']}})">
                            @if($chat['user_data'])

                                <div class="chathead flex-shrink-0"
                                     style="background-color: #e4dede; width: 60px; height: 60px; border-radius: 50%;">
                                    <img src="{{$chat['user_data']['photo_url']['thumbnail_url'] ?? null}}"
                                         class="img-fluid rounded-circle">
                                </div>
                                <div class="chatlist cursor-pointer flex-grow-1 rounded-2 p-2"
                                     wire:click="messages({{$chat->id}}, {{$chat['user_data'] ?? null}})">
                                    <div class="chatlist-header d-flex justify-content-between">
                                    <span class="fw-bold"
                                          style="font-size: 15px;">{{$chat['user_data'] ? $chat['user_data']['first_name'] . ' ' . $chat['user_data']['last_name'] : ""}}</span>
                                        <span class="text-end"
                                              style="font-size: 10px;">{{$chat['lastMessage']['created_at'] ?? null}}</span>
                                    </div>
                                    <span
                                        style="font-size: 12px;">{{ $chat['lastMessage'] ? (strlen($chat['lastMessage']['message']) > 25 ? substr($chat['lastMessage']['message'], 0, 25) . "..." : substr($chat['lastMessage']['message'], 0, 25)) : null}}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if($chat_user)
            <div
                class="messenger-container messenger-cards show-messenger pb-2 flex-grow-1 w-sm-100 w-md-75 d-flex flex-column messageBox"
                id="messenger-container1">
                <div class="messenger-header text-dark">
                    <div style="display: flex; margin: 9px 0 -4px 33px;">
                        <img src="{{$chat_user['photo_url']['thumbnail_url'] ?? null}}" alt="user picture"
                             class="rounded-circle" style="width: 55px; height: 55px;">
                        <div>
                            <p style="margin-left: 10px;">
                                <strong
                                    style="color: #F95520;font-weight: 700; font-size: 18px">{{ $chat_user ? $chat_user['first_name'] . ' ' . $chat_user['last_name'] : "User Name"}}</strong>
                                <br> <span style="color: grey; padding: 0px">Online</span></p>
                        </div>
                    </div>
                </div>
                <div class="messenger-messages mx-4" style="border-bottom: 1px solid grey">
                    @foreach($messages as $message)
                        @if($message['sender_id'] === $logged_in_user_id)
                            <div class="d-flex flex-row gap-1 justify-content-end">
                                <div class="rounded " style="max-width: 70%;margin-right: 10px">
                                    <div>
                                        <p class="text-end text-sm" style="color: #000000;margin-bottom: 3px;">You</p>
                                    </div>
                                    <div class="bg-secondary text-white p-2"
                                         style="font-size:small;background: #E05A35 !important;border-radius: 10px 0px 10px 10px !important">
                                        {{$message['message']}}
                                    </div>
                                    <div>
                                        <p class="text-end"
                                           style="color: #58534C;font-size: 14px">{{$message['created_at'] ?? null}}</p>
                                    </div>
                                </div>
                                <div>
                                    <img
                                        src="{{\App\Helpers\Helpers::getWebUser()['photo_url'] ? \App\Helpers\Helpers::getWebUser()['photo_url']['thumbnail_url'] : ''}}"
                                        width="50" height="50" style="border-radius: 50%">
                                </div>
                            </div>
                        @else
                            <div class="d-flex flex-row gap-3 align-items-start">
                                <div>
                                    <img src="{{$chat_user['photo_url']['thumbnail_url'] ?? null}}"
                                         width="35" height="35" style="border-radius: 50%;background-color: white">
                                </div>
                                <div class="rounded " style="max-width: 70%;">
                                    <div class="bg-primary text-white  p-2"
                                         style="font-size:small;background-color: #F7F5F4 !important;color:#000000 !important;border-radius: 0px 10px 10px 10px !important">{{$message['message']}}</div>
                                    <div>
                                        <p class="text-start"
                                           style="color: #58534C;font-size: 14px">{{$message['created_at'] ?? null}}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div style="width: 92%; margin: auto; margin-top: 10px">
                    <form wire:submit.prevent="sendMessage"
                          class="messenger-input-form d-flex">
                        <input type="text" wire:model="message" id="user-input" class="form-control border-0 "
                               placeholder="Your message...">
                        <a type="submit" wire:click="sendMessage"  class="d-flex align-items-center m-2">
                            <i class="ni ni-send" style="font-size: 20px; color: #F95520"></i></a>
                    </form>
                </div>
            </div>
        @else
            <div
                class="messenger-cards d-block show-messenger w-md-75 d-flex flex-column justify-content-center align-items-center"
                id="messenger-container2">
                <img src="{{asset('assets/img/Click.png')}}">
                <p style="color: #CECECECC; font-size: 20px; font-weight: bold">Select Chat to Show</p>
            </div>
            @php
                // Define your CSS classes
                $classes = ['linear_blue', 'linear_orange', 'linear_green', 'linear_light_orange'];
                // Select a random class
                $randomClass = $classes[array_rand($classes)];
            @endphp
            <div class="row pt-2 connection-user-card d-none w-md-75 d-flex">
                @foreach($connections as $connection)
                    <div class="col-6 col-md-6 col-lg-4 col-xxl-3 py-3 ">
                        <div class="text-center shadow-sm connection-card"
                             style="height: 17rem; padding:0; border-radius: 20px 20px 8px 8px;background-color: #FFFFFF !important">
                            <div class="position-absolute w-100 {{$randomClass}}" style="height: 80px;border-radius: 20px">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-end" style="height: 40%; padding: 0;">
                                <div class="card-img flex-grow-5 position-absolute z-index-2" style="top:30px;">
                                    <img src="{{$connection['friend']['photo_url']['thumbnail_url'] ?? null}}" alt="profile pic"
                                         style="width: 70px; height: 70px; border-radius: 100%;  cursor: pointer; justify-content: center;">
                                    <h6 class="card-title mt-1 mb-0"
                                        style="cursor:pointer; color: #1E1D1D;font-weight: 700;">
                                        {{$connection['friend'] ? $connection['friend']['first_name'] : ""}}
                                    </h6>
                                    <h6 class="card-title mt-1 mb-2"
                                        style="cursor:pointer; color: #1E1D1D;font-weight: 700;">
                                        {{$connection['friend'] ? $connection['friend']['last_name'] : ""}}
                                    </h6>
                                    <div class="d-flex justify-content-center">
                                        <img src="{{asset('assets/new-design/icon/connection/like.svg')}}" height="20"
                                             width="20"/>
                                        <img src="{{asset('assets/new-design/icon/connection/comment.svg')}}" height="20"
                                             width="20" class="mx-3"/>
                                        <img src="{{asset('assets/new-design/icon/connection/share.svg')}}" height="20"
                                             width="20"/>
                                    </div>
                                </div>
                                <div class="profileCard mb-4" style="color: rgb(74, 74, 74);padding:5px 0px;">
                                    <div class="p-1">
                                        @if($connection['friend']['connection_status'] === 0)
                                            <a class="connection-btn px-3 py-2" wire:click="connectUnConnectUser({{$connection['friend']['id']}},'connect')" style=' font-size: x-small; font-weight: 700;'>Connect</a>
                                        @elseif($connection['friend']['connection_status'] === 1)
                                            <a class="connection-btn px-3 py-2" wire:click="connectUnConnectUser({{$connection['friend']['id']}},'un-connect')" style="font-size: x-small; font-weight: 700;">Connected</a>
                                        @elseif($connection['friend']['connection_status'] === 2)
                                            <a class=" connection-btn px-3 py-2" wire:click="connectUnConnectUser({{$connection['friend']['id']}},'un-connect')" style="font-size: x-small; font-weight: 700;">Pending</a>
                                        @elseif($connection['friend']['connection_status'] === 3)
                                            <a class="connection-btn px-3 py-2" wire:click="connectUnConnectUser({{$connection['friend']['id']}},'accept')" style="font-size: x-small; font-weight: 700;">Accept</a>
                                        @endif
                                    </div>
                                    <div class="p-1">
                                        <a class="connection-btn px-3 py-2" wire:click="messages('',{{$connection['friend'] ?? null}})" style="font-size: x-small; font-weight: 700;">Message</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
    <script>


        $(document).ready(function(){
        sanitizeInput('#search-bar');
    })


        function scrollToSection(name, id) {

            var element = document.getElementById(id);

            if (element) {

                element.scrollIntoView({behavior: 'auto'});

            }

            activeUserBox(id);
        }

        function activeUserBox(id) {
            var chatBox = document.querySelector('.clientChatBox-' + id);

            if (chatBox) {
                chatBox.style.backgroundColor = 'white';
                chatBox.style.borderRadius = '10px';

                chatBox.style.setProperty('background-color', 'white', 'important');
                chatBox.style.setProperty('border-radius', '10px', 'important');
            }
        }

        function toggleDisplay(type) {
            const messengerCards = $('.messenger-cards');
            const connectionCards = $('.connection-user-card');
            const messageContainer = $('.messageBox');

            if (type === 'messages') {
                messengerCards.removeClass('d-none').addClass('d-block');
                connectionCards.removeClass('d-block').addClass('d-none');
                messageContainer.addClass('d-none');
            } else if (type === 'connections') {
                connectionCards.removeClass('d-none').addClass('d-block');
                messengerCards.removeClass('d-block').addClass('d-none');
                messageContainer.addClass('d-none');
            }
        }


    </script>
</div>


@push('javascript')

    <script>

        Livewire.on('scrollToBottom', function (){

            const chatboxContent = $('.messenger-messages');
            chatboxContent.scrollTop(chatboxContent[0].scrollHeight);

        });

    </script>

@endpush

