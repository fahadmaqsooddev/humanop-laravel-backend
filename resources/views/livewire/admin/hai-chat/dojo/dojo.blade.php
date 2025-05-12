@push('css')

    <style>

        .session-buttons{
            background-color: #f2661c;
            border-radius: 7px;
            color: white;
            border: none;
            padding: 7px;
            font-size: small;
        }

        .disabled-session-buttons{
            background-color: lightgrey;
            border-radius: 7px;
            color: white;
            border: none;
            padding: 7px;
            font-size: small;
        }

        .message-label{
            color: black;
            background-color: lightgray;
            padding: 1px 10px;
            border-radius: 20px;
        }

        #chatDots {
            margin: 32px;
        }
        .chatDot {
            width: 10px;
            height: 10px;
            background-color: #f2661c;
            display: inline-block;
            margin: 1px;
            border-radius: 50%;
        }

        .chatDot:nth-child(1) {
            animation: bounce 1s infinite;
        }

        .chatDot:nth-child(2) {
            animation: bounce 1s infinite .2s;
        }

        .chatDot:nth-child(3) {
            animation: bounce 1s infinite .4s;
        }
        @keyframes bounce {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .highlighted-td{
            color: #f2661c;
        }

        .disabledCard {
            pointer-events: none;
            opacity: 0.4;
        }

    </style>

@endpush

<div>

    <div class="container">

        <div class="row">

            <div class="col-3">

                <button wire:click="openNewTrainingSessionModal" class="session-buttons">INITIATE NEW TRAINING SESSION</button>

            </div>
            <div class="col-9">
                <button class="disabled-session-buttons" disabled>
                    DROPDOWN SELECT HAi TRAINING PERSONA
                </button>
            </div>

        </div>

        <div class="row" style="padding-top: 30px;">

            <div class="col-3">

                <div>

                    <p style="line-height: 0; font-weight: 700;color: black;">Previous Training Sessions :</p>
                    <div style="border-radius: 5px; border: 2px solid #f2661c; max-height: 300px;overflow-y: scroll;" class="py-2 px-1">

                        <table class="table">

                            @foreach($allSessions ?? [] as $session)
                                <tr class="custom-text-dark">
                                    <td class="{{$session_id === $session['id'] ? 'highlighted-td' : ''}}">
                                        <span style="cursor: pointer;" wire:click="selectSession('{{$session['id']}}')" title="Select session for training.">{{$session['title']}}</span>
                                        @if($session['status'] === 'active')
                                            <span style="color: green; font-size: 10px;">
                                                <i class="fa-solid fa-circle"></i>
                                            </span>
                                        @endif
                                        <br>
                                        <span style="font-size: small;">
                                            [{{\Carbon\Carbon::parse($session['created_at'])->format('Y-m-d')}}]
                                            [{{\Carbon\Carbon::parse($session['updated_at'])->format('Y-m-d')}}]
                                        </span>
                                    </td>
                                </tr>

                            @endforeach

                        </table>

                    </div>

                    <div class="row p-1">

                        <div class="col-4">

{{--                            @if($session_id)--}}

                                <button class="session-buttons" wire:click="trainMoreSession()">

                                    <span wire:loading.remove wire:target="trainMoreSession">TRAIN MORE</span>

                                    <span wire:loading wire:target="trainMoreSession">Activating...</span>

                                </button>

{{--                            @else--}}

{{--                                <button class="disabled-session-buttons">--}}
{{--                                    TRAIN MORE--}}
{{--                                </button>--}}

{{--                            @endif--}}

                        </div>

                        <div class="col-4" wire:key="{{$session_id}}">

                            @if($session_id)

                                <button class="session-buttons" wire:click="exportSessionConversation()">

                                    <span wire:loading.remove wire:target="exportSessionConversation">EXPORT</span>

                                    <span wire:loading wire:target="exportSessionConversation">Exporting...</span>
                                </button>

                            @else

                                <button class="disabled-session-buttons">
                                    EXPORT
                                </button>

                            @endif
                        </div>

                        <div class="col-4">

                            @if($session_id)

                            <button class="session-buttons" wire:click="deleteSession()">

                                <span wire:loading.remove wire:target="deleteSession">DELETE</span>

                                <span wire:loading wire:target="deleteSession">Deleting...</span>

                            </button>

                            @else

                                <button class="disabled-session-buttons" title="select session to delete">
                                    DELETE
                                </button>

                            @endif
                        </div>

                    </div>

                </div>

            </div>

                <div class="col-6">

                    <div style="border-radius: 5px;border: 2px solid #f2661c; width: 100%;height: auto; padding-top: 10px;">

                        <div class="spinner-border custom-text-dark" id="chat_switch_spinner" role="status" style="width: 30px; height: 30px;top: 250px;
                        position: relative; left: 50%;" wire:loading wire:target="selectSession">
                            <span class="sr-only">
                                Loading...
                            </span>
                        </div>

                    @include('layouts.message')

                    <div class="py-2" style="height: 425px; overflow-y: scroll;" id="chat_container">

                        <div id="chatMessages" class="d-flex flex-column gap-3">

                            @foreach($conversations as $conversation)

                                @if(isset($conversation['role']) && $conversation['role'] === 'user')
                                <div class="d-flex flex-row gap-1 justify-content-end">
                                    <div class="rounded " style="max-width: 70%;">
{{--                                        <div>--}}
{{--                                            <p class="text-end text-sm" style="color: #000000;margin-bottom: 3px;">Admin</p>--}}
{{--                                        </div>--}}
                                        <div class="bg-secondary text-white p-2"  style="font-size:small;background: #E05A35 !important;border-radius: 10px 0px 10px 10px !important">
                                            {!! $conversation['content'] !!}
                                        </div>
{{--                                        <div>--}}
{{--                                            <p class="text-end" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse()->diffForHumans()}}</p>--}}
{{--                                        </div>--}}
                                    </div>

                                    <div>
                                        <img src="{{URL::asset('assets/img/Human_OP.png')}}" width="50" height="35" style="border-radius: 50%">
                                    </div>
                                </div>

                                @else
                                <div style="padding: 5px;"></div>
                                <div class="d-flex flex-row gap-3 align-items-start" style="padding-left: 8px;">
                                    <div>
                                        <img src="{{asset('assets\img\icons\assessment_intro_icon.png')}}" width="35" height="35" style="border-radius: 50%;background-color: white" >
                                    </div>
                                    <div class="rounded " style="max-width: 70%;">
                                        <div class="bg-primary text-white  p-2"
                                             style="max-width: 100%; font-size:small;background-color: #F7F5F4 !important;color:#000000 !important;border-radius: 0px 10px 10px 10px !important">
                                            {!! $conversation['content'] !!}
                                        </div>
{{--                                        <div class="row" style="width: 100%;">--}}
{{--                                            <div class="col-9">--}}
{{--                                                <p class="text-start" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse()->diffForHumans()}}</p>--}}
{{--                                            </div>--}}

{{--                                        </div>--}}
                                    </div>
                                </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                    <div id="chatLoader" style="display: flex; justify-content:flex-start" wire:ignore.self>
                        <div id="chatDots" wire:loading wire:target="sendMessage">
                            <span class="chatDot"></span>
                            <span class="chatDot"></span>
                            <span class="chatDot"></span>
                        </div>
                    </div>

                    <div class="px-3 py-2">
                        <form wire:submit.prevent="sendMessage">

                            <input wire:model="message" placeholder="Enter your message here." type="text" style="width: 90%; border-radius: 15px;border: 1px solid #f2661c;" class="px-1">

                            <button class="bg-transparent" type="submit" style="border:none" id="submit_btn">
                                <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}"  width="25" height="25" >
                            </button>

                        </form>
                    </div>

                </div>

{{--                    </div>--}}

                <div class="py-2">

                    <button wire:click="endTrainingSession()" class="session-buttons">

                        <span wire:loading.remove wire:target="endTrainingSession">END CURRENT TRAINING SESSION</span>

                        <span wire:loading wire:target="endTrainingSession">Ending...</span>

                    </button>

                </div>

            </div>

            <div class="col-3">

                <div style="border-radius: 5px; border: 2px solid #f2661c; width: 100%; height: 500px;" class="p-1">

                    <div class="h-100">

                        This will allow admin user to
                        observe how HAi is thinking and
                        processing its learning

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div wire:ignore.self class="modal fade" id="newTrainingSession" tabindex="-1"
         role="dialog"
         aria-labelledby="newTrainingSessionModal" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: #1C365E !important; border-radius: 32px;">
                <div class="modal-body" style="padding: 25px 30px;">

                    <div class="d-flex justify-content-between">
                        <h5 style="font-weight: 800;font-size: 28px;line-height: 100%;color: white;">
                            New Training Session
                        </h5>
                        <div>
                            <a type="button" data-bs-dismiss="modal"
                               aria-label="Close" id="edit-embedding-close-modal-button">
                                <img src="{{asset('assets/img/icons/cross-white.svg')}}" width="25">
                            </a>
                        </div>
                    </div>

                    <div class="py-1">

                        @if(session('training_errors'))
                            <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                                <ul class="alert-text text-white mb-0">
                                    @foreach(session('training_errors') as $err)
                                        <li>{{ $err[0] }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @if(session('training_success'))
                            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('training_success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @if(session('training_error'))
                            <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('training_error')}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif

                        <form wire:submit.prevent="createNewTrainingSession">

                            <div class="py-3">
                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">
                                    Training Session Name
                                </label>
                                <input wire:model="training_session_name" type="text" placeholder="Enter training session name" style="padding:15px;border-radius: 10px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">
                            </div>

                            <div class="py-4 d-flex justify-content-center">
                                <button class="m-1"
                                        style="background:#F95520;color:white;border-radius: 24px;border: 2px; font-weight: 600;padding: 5px 15px 5px 15px;">
                                    <img src="{{asset('assets/img/icons/Add.svg')}}" width="20">

                                    <span wire:loading.remove wire:target="createNewTrainingSession">Add</span>

                                    <span wire:loading wire:target="createNewTrainingSession">Adding...</span>

                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@push('javascript')

    <script>

        function scrollToBottom() {
            const chatboxContent = $('#chat_container');
            chatboxContent.scrollTop(chatboxContent[0].scrollHeight);
        }

        window.Livewire.on('openNewTrainingSessionModal', function () {

            $('#newTrainingSession').modal('toggle');
        });

        window.Livewire.on('scrollToBottom', function () {

            scrollToBottom()
        });

        $(document).ready(function (){

            const descriptionContainer = document.querySelector('#chat_container');
            descriptionContainer.addEventListener('wheel', (event) => {
                event.preventDefault();

                descriptionContainer.scrollBy({
                    top: event.deltaY < 0 ? -30 : 30
                });
            }, {passive: true});
        });

    </script>

@endpush
