<div class="body-container d-flex flex-column flex-md-row align-items-center align-items-md-stretch m p-2 pb-5">


    <div class="side-bar flex-grow-1 d1 w-100 w-md-30 border border-dark rounded-3 shadow-sm p-0 bg-white "
        id="message-bar">
        <h3 class="text-center text-white bg-dark-blue rounded-top m-0 p-3">Messages & Connections</h3>
        <form class="messenger-search-form d-flex align-items-center justify-content-center ">
            <button type="submit" class="btn btn-danger mb-0">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <input wire:model="filter_text" type="text" id="serch-input" class="form-control"
                placeholder="Search by name">
        </form>
        <div>
            <ul class="nav nav-tabs w-100" role="tablist">
                <li class="nav-item w-50">
                    <a class="nav-link active bg-dark text-light" data-bs-toggle="tab" href="#tabs-1"
                        role="tab">Messages</a>
                </li>
                <li class="nav-item w-50">
                    <a class="nav-link bg-dark text-light" data-bs-toggle="tab" href="#tabs-2"
                        role="tab">Connections</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="tabs-2" role="tabpanel">
                @foreach($connections as $connection)
                    <div class="messages-list d-flex align-items-center mx-4 my-3 gap-3"
                        onclick="scrollToSection('messenger-container1')">
                        <div class="chathead flex-shrink-0"
                            style="background-color: #e4dede; width: 60px; height: 60px; border-radius: 50%;">
                            <img src="{{$connection['friend']['photo_url']['thumbnail_url'] ?? null}}"
                                class="img-fluid rounded-circle">
                        </div>
                        <div class="chatlist cursor-pointer flex-grow-1 bg-dark-blue text-white rounded-2 p-2"
                            wire:click="messages('',{{$connection['friend'] ?? null}})">
                            <div class="chatlist-header d-flex justify-content-between">
                                <span class="fw-bold" style="font-size: 16px;">
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
                    <div class="messages-list d-flex align-items-center mx-4 my-3 gap-3"
                        onclick="scrollToSection('messenger-container1')">
                        @if($chat['user_data'])
                            <div class="chathead flex-shrink-0"
                                style="background-color: #e4dede; width: 60px; height: 60px; border-radius: 50%;">
                                <img src="{{$chat['user_data']['photo_url']['thumbnail_url'] ?? null}}"
                                    class="img-fluid rounded-circle">
                            </div>
                            <div class="chatlist cursor-pointer flex-grow-1 bg-dark-blue text-white rounded-2 p-2"
                                wire:click="messages({{$chat->id}}, {{$chat['user_data'] ?? null}})">
                                <div class="chatlist-header d-flex justify-content-between">
                                    <span class="fw-bold" style="font-size: 15px;">
                                        {{$chat['user_data'] ? $chat['user_data']['first_name'] . ' ' . $chat['user_data']['last_name'] : ""}}
                                    </span>
                                    <span class="text-end" style="font-size: 10px;">
                                        {{$chat['lastMessage']['created_at'] ?? null}}
                                    </span>
                                </div>
                                <span style="font-size: 12px;">
                                    {{ $chat['lastMessage'] ? (strlen($chat['lastMessage']['message']) > 30 ? substr($chat['lastMessage']['message'], 0, 30) . "..." : substr($chat['lastMessage']['message'], 0, 30)) : null}}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @if($chat_user)
        <div class="messenger-container show-messenger pb-2   flex-grow-1 w-100  w-md-70 border border-dark rounded-3 shadow-sm bg-light d-flex flex-column "
            id="messenger-container1">
            <div
                class="messenger-header d-flex justify-content-between align-items-center text-white bg-dark-blue rounded-top p-2">
                <span>
                    {{ $chat_user ? $chat_user['first_name'] . ' ' . $chat_user['last_name'] : "User Name"}}
                </span>
                <div class="dropdown">
                    <a id="dropdownMenuButton" class="text-white" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        ...
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" wire:click="deleteChat({{$chat_user['id'] ?? null}})">Delete Chat</a>
                    </div>
                </div>
                <img src="{{$chat_user['photo_url']['thumbnail_url'] ?? null}}" alt="user picture" class="rounded-circle"
                    style="width: 40px; height: 40px;">
            </div>
            <div class="messenger-messages flex-grow-1 overflow-auto p-2 d-flex flex-column">
                @foreach($messages as $message)
                    @if($message['sender_id'] === $logged_in_user_id)
                        <div class="chat-message user-chat-message bg-white text-dark align-self-end p-2  my-1 rounded-4">
                            {{$message['message']}}
                        </div>
                    @else
                        <div class="chat-message ai-message bg-dark-blue text-white align-self-start p-2 my-1 rounded-4">
                            {{$message['message']}}
                        </div>
                    @endif
                @endforeach
            </div>
            <form wire:submit.prevent="sendMessage"
                class="messenger-input-form d-flex h-auto align-items-center   justify-content-center border-top">
                <input type="text" wire:model="message" id="user-input" class="form-control p-1 border-0 "
                    placeholder="Type your message...">
                <button type="submit" class="btn btn-danger d-flex align-items-center m-0"><i
                        class="ni ni-send"></i></button>
            </form>
        </div>
    @else
        <div class="messenger-container  show-messenger w-100 w-md-70 flex-grow-1 border border-dark rounded-3 shadow-sm bg-light d-flex flex-column"
            id="messenger-container2">
            <div class="messenger-header text-center text-white bg-dark-blue rounded-top p-2">
                <p>Select the chat to display messages</p>
            </div>
            <div class="messenger-messages flex-grow-1 overflow-auto p-2"></div>
        </div>
    @endif
</div>

<style>
    .bg-dark-blue {
        background-color: rgb(35 35 82) !important;
    }

    @media (max-width:575px) {

        /* .messenger-container{
    height: 60vh;
} */


    }
</style>

<script>


    //     function showMessenger() {
    //         if (window.innerWidth < 575) {
    //             document.addEventListener("DOMContentLoaded", function() {
    //     // Check if the message sidebar should be hidden
    //     if (localStorage.getItem("messageSidebarHidden") === "true") {
    //         document.querySelector(".side-bar").style.display = "none";
    //         localStorage.setItem("messageSidebarHidden", "true");
    //     }
    // });       

    //         }

    //     }

    function scrollToSection(id) {
        var element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'auto' });
        }
    }
</script>