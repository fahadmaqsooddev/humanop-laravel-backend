<div>
    <div class="body-container">
        <div class="side-bar">
            <h3 style="background-color: #0f1534; font-size: 21px; padding: 13px;">Messages & Connections</h3>
            <form class="messenger-search-form">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <input wire:model="filter_text" type="text" id="serch-input" placeholder="Search by name">
            </form>
            <div>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item w-50">
                        <a class="nav-link active bg-dark text-light" data-toggle="tab" href="#tabs-1" role="tab">Messages</a>
                    </li>
                    <li class="nav-item w-50">
                        <a class="nav-link bg-dark text-light" data-toggle="tab" href="#tabs-2" role="tab">Connections</a>
                    </li>
                </ul>

            </div>

                <div class="tab-content">
                    <div class="tab-pane" id="tabs-2" role="tabpanel">
                        @foreach($connections as $connection)
                            <div class="messages-list">
                                <div class="chathead">
                                    <img src="{{$connection['friend']['user_picture_url'] ?? null}}" class="img-fluid">
                                </div>
                                <div class="chatlist cursor-pointer" wire:click="messages('',{{$connection['friend'] ?? null}})" style="background-color: #0f1534;">
                                    <div class="chatlist-header">
                                        <span style="font-size: 16px; padding:8px; font-weight: 600">
                                            {{$connection['friend'] ? $connection['friend']['first_name'] . ' ' . $connection['friend']['last_name'] : ""}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="tab-pane active w-100" id="tabs-1" role="tabpanel">

                        @if(empty($chats[0]))
                            <p class="p-2">No chat found</p>
                        @endif

                        @foreach($chats as $chat)
                        <div class="messages-list">
                            @if($chat['user_data'])
                            <div class="chathead">
                                <img src="{{$chat['user_data']['user_picture_url'] ?? null}}" class="img-fluid">
                            </div>
                            <div class="chatlist cursor-pointer" wire:click="messages({{$chat->id}}, {{$chat['user_data'] ?? null}})" style="background-color: #0f1534;">
                                <div class="chatlist-header">
                                    <span style="font-size: 15px; padding:8px; font-weight: 600">
                                        {{$chat['user_data']  ? $chat['user_data']['first_name'] . ' ' . $chat['user_data']['last_name'] : ""}}
                                        &nbsp;
                                    </span>
                                    <span style="font-size: 10px;display: flex; justify-content: end; padding:8px;">
                                        {{$chat['lastMessage']['created_at'] ?? null}}
                                    </span>
                                </div>
                                <span style="font-size: 12px; padding-left: 10px;">
                                    {{ $chat['lastMessage'] ? strlen($chat['lastMessage']['message']) > 30 ? substr($chat['lastMessage']['message'], 0, 30) . "..." : substr($chat['lastMessage']['message'], 0, 30)  : null}}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

{{--            <div class="messages-list">--}}
{{--                <div class="chathead">DP</div>--}}
{{--                <div class="chatlist">--}}
{{--                    <div class="chatlist-header">--}}
{{--                        <h4>UserName-2</h4>--}}
{{--                        <p>x minutes ago</p>--}}
{{--                    </div>--}}
{{--                    <p>last Message</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="messages-list">--}}
{{--                <div class="chathead">DP</div>--}}
{{--                <div class="chatlist">--}}
{{--                    <div class="chatlist-header">--}}
{{--                        <h4>UserName-3</h4>--}}
{{--                        <p>x minutes ago</p>--}}
{{--                    </div>--}}
{{--                    <p>last Message</p>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>

        @if($chat_user)

        <div class="messenger-container">
            <div class="messenger-header text-center" id="messenger-header">
                <span>
                    {{ $chat_user ? $chat_user['first_name'] . ' ' . $chat_user['last_name'] : "User Name"}}
                </span>
                <div class="p-2 cursor-pointer">
                    <a id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ...
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" wire:click="deleteChat({{$chat_user['id'] ?? null}})">Delete Chat</a>
                    </div>
                </div>

                <img src="{{$chat_user['user_picture_url'] ?? null}}" alt="user picture" style="width: 40px; height: 40px;">
            </div>
            <div class="messenger-messages" id="messenger-messages">

                @foreach($messages as $message)

                    @if($message['sender_id'] === $logged_in_user_id)
                        <div class="chat-message user-chat-message">{{$message['message']}}</div>
                    @else
                        <div class="chat-message ai-message">{{$message['message']}}</div>
                    @endif
                @endforeach

{{--                <div class="message ai-message">Receiver Message</div>--}}
{{--                <div class="message user-message">Sender Message</div>--}}
{{--                <div class="message ai-message">Receiver Message</div>--}}
{{--                <div class="message user-message">Sender Message</div>--}}
{{--                <div class="message ai-message">Receiver Message</div>--}}
{{--                <div class="message user-message">Sender Message</div>--}}
{{--                <div class="message ai-message">Receiver Message</div>--}}
{{--                <div class="message user-message">Sender Message</div>--}}
{{--                <div class="message ai-message">--}}
{{--                    Receiver Message--}}
{{--                </div>--}}
{{--                <div class="message user-message">Sender Message</div>--}}
            </div>
            <form wire:submit.prevent="sendMessage" class="messenger-input-form">
                <input type="text" wire:model="message" id="user-input" placeholder="Type your message...">
                <button style="background-color: #0f1534;"><i class="ni ni-send"></i></button>
            </form>
        </div>
        @else

            <div class="messenger-container">

                <div class="messenger-header text-center pt-2" id="messenger-header">
                    <p>Select the chat to display messages</p>
                </div>

                <div class="messenger-messages" id="messenger-messages">
                </div>

            </div>

        @endif
    </div>
</div>
