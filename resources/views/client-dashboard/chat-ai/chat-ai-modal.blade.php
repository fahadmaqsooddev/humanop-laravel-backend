<style>
    .description-container::-webkit-scrollbar {
        width: 10px; /* Width of the scrollbar */
    }

    .description-container::-webkit-scrollbar-track {
        background: rgb(160, 174, 192); /* Color of the track */
    }

    .description-container::-webkit-scrollbar-thumb {
        background-color: #888; /* Color of the handle */
        border-radius: 10px; /* Roundness of the handle */
        /*border: 2px solid #555; !* Space around the handle *!*/
    }

    /* Custom scrollbar for Firefox */
    .description-container {
        scrollbar-width: thin; /* Thickness of the scrollbar */
        scrollbar-color: #888 rgb(160, 174, 192); /* Color of the scrollbar and track */
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

    .like,
    .dislike {
        display: inline-block;
        cursor: pointer;
        margin: 10px;
    }

    .dislike:hover,
    .like:hover {
        color: #f2661c;
        transition: all .2s ease-in-out;
        transform: scale(1.1);
    }

    .active {
        color: #f2661c;
    }

    .modal-close-btn {
        background: #f2661c;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float: right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }

    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #f2661c !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
    }

</style>
<div>
    <div style="position: fixed; right: 30px; bottom: 20px; z-index: 100000; cursor: pointer;">

        @livewire('client.chat.pop-up-chat')

{{--        <div class="card shadow-lg blur chatBoxShow d-none" style="background-color: lightgrey !important;">--}}

{{--            <div class="card-header pb-0 pt-3" style="background-color: lightgrey; width: 500px">--}}
{{--                <div class="d-flex" style="justify-content: space-between">--}}
{{--                    <h5 style="color: #f2661c; font-weight: bold; font-size: 25px">H.A.I CHAT INTERFACE</h5>--}}
{{--                    <div class="">--}}
{{--                        <button class="btn btn-link chatBoxClose"--}}
{{--                                style="background-color: #f2661c;color: white;padding: 3px 8px 3px 8px;border-radius: 8px;width: 32px; height: 32px; font-size: 20px">--}}
{{--                            <i class="fa fa-close"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="chatbox">--}}
{{--                    <div class="chatbox-content" id="chatbox-content">--}}
{{--                        <div style="display: flex; justify-content: end">--}}
{{--                            <div class="message chat-message">Hello! Welcome to HumanOp, where we help individuals and--}}
{{--                                organizations unlock their full potential. We're excited to have you on board!--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="flex-start">--}}
{{--                            <div class="message chat-reply-message">Hello! Welcome to HumanOp, where we help individuals--}}
{{--                                and--}}
{{--                                organizations unlock their full potential. We're excited to have you on board!--}}

{{--                                At HumanOp, we believe that understanding your unique design and applying this--}}
{{--                                understanding to your life can help you effortlessly access and operate from within your--}}
{{--                                individual zone-of-genius.--}}
{{--                            </div>--}}
{{--                            <div class="rating d-flex mb-2">--}}
{{--                                <!-- Thumbs up -->--}}
{{--                                <div class="like grow ">--}}
{{--                                    <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>--}}
{{--                                </div>--}}
{{--                                <!-- Thumbs down -->--}}
{{--                                <div class="dislike grow ">--}}
{{--                                    <i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div style="display: flex; justify-content: end">--}}
{{--                            <div class="message chat-message">Hello! Welcome to HumanOp, where we help individuals and--}}
{{--                                organizations unlock their full potential. We're excited to have you on board!--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="flex-start">--}}
{{--                            <div class="message chat-reply-message">Hello! Welcome to HumanOp, where we help individuals--}}
{{--                                and--}}
{{--                                organizations unlock their full potential. We're excited to have you on board!--}}

{{--                                At HumanOp, we believe that understanding your unique design and applying this--}}
{{--                                understanding to your life can help you effortlessly access and operate from within your--}}
{{--                                individual zone-of-genius.--}}
{{--                            </div>--}}
{{--                            <div class="rating d-flex mb-2">--}}
{{--                                <!-- Thumbs up -->--}}
{{--                                <div class="like grow ">--}}
{{--                                    <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>--}}
{{--                                </div>--}}
{{--                                <!-- Thumbs down -->--}}
{{--                                <div class="dislike grow ">--}}
{{--                                    <i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div style="display: flex; justify-content: end">--}}
{{--                            <div class="message chat-message">Hello! Welcome to HumanOp, where we help individuals and--}}
{{--                                organizations unlock their full potential. We're excited to have you on board!--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="flex-start">--}}
{{--                            <div class="message chat-reply-message">Hello! Welcome to HumanOp, where we help individuals--}}
{{--                                and--}}
{{--                                organizations unlock their full potential. We're excited to have you on board!--}}

{{--                                At HumanOp, we believe that understanding your unique design and applying this--}}
{{--                                understanding to your life can help you effortlessly access and operate from within your--}}
{{--                                individual zone-of-genius.--}}
{{--                            </div>--}}
{{--                            <div class="rating d-flex mb-2">--}}
{{--                                <!-- Thumbs up -->--}}
{{--                                <div class="like grow ">--}}
{{--                                    <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>--}}
{{--                                </div>--}}
{{--                                <!-- Thumbs down -->--}}
{{--                                <div class="dislike grow ">--}}
{{--                                    <i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                    <div style="display: flex; justify-content:flex-start;">--}}
{{--                        <div id="chatDots" wire:loading wire:target="dislike">--}}
{{--                            <span class="chatDot"></span>--}}
{{--                            <span class="chatDot"></span>--}}
{{--                            <span class="chatDot"></span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <form>--}}
{{--                        @csrf--}}
{{--                        <div class="chatbox-input">--}}
{{--                            <input type="text" id="userMessage"--}}
{{--                                   placeholder="Type your message here...">--}}
{{--                            <button type="submit" id="submitBtn">&#9658;</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="chatBoxx d-block" style="position: fixed; right: 30px; bottom: 20px;cursor: pointer;">
            <div style="border-radius:50%; width: 75px; height: 75px; background-color: #f2661c; color: blue; padding-left: 12px;padding-top: 8px">
                <span style="font-size: 42px;" >
                    <img style="width: 50px" src="{{asset('assets/img/rabbit.png')}}">
                </span>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    document.querySelector('.chatBoxx').addEventListener('click', function () {
        document.querySelector('.chatBoxShow').classList.remove('d-none');
        document.querySelector('.chatBoxShow').classList.add('d-block');
        document.querySelector('.chatBoxx').classList.remove('d-block');
        document.querySelector('.chatBoxx').classList.add('d-none');

        // keep chat box scrollbar to bottom
        var objDiv = document.getElementById("chatbox-content-pop-up");
        objDiv.scrollTop = objDiv.scrollHeight;

    });

    document.querySelector('.chatBoxClose').addEventListener('click', function () {
        document.querySelector('.chatBoxShow').classList.remove('d-block');
        document.querySelector('.chatBoxShow').classList.add('d-none');
        document.querySelector('.chatBoxx').classList.remove('d-none');
        document.querySelector('.chatBoxx').classList.add('d-block');
    });
</script>
