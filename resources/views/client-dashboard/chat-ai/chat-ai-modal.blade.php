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
@if(\App\Helpers\Helpers::getWebUser()['hai_chat'] == \App\Enums\Admin\Admin::HAI_CHAT_SHOW)
<div>
    <div style="position: fixed; right: 30px; bottom: 20px; z-index: 100000; cursor: pointer;">

        @livewire('client.chat.pop-up-chat')

        <div class="chatBoxx d-block" style="position: fixed; right: 30px; bottom: 20px;cursor: pointer;">
            <div style="border-radius:50%; width: 75px; height: 75px; background-color: #f2661c; color: blue; padding-left: 8px;padding-top: 4px">
                <span style="font-size: 42px;" >
                    🐇
{{--                    <img style="width: 50px" src="{{asset('assets/img/rabbit.png')}}">--}}
                </span>
            </div>
        </div>
    </div>
</div>
@endif
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
