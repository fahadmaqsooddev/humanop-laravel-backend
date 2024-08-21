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
<div class="fixed-plugin">

    <div style="position: fixed; right: 30px; bottom: 20px; z-index: 99; cursor: pointer;">
        <div style="border-radius:50%; width: 70px; height: 70px; background-color: #f2661c; color: blue; padding-left: 5px;">
            <span style="font-size: 42px;" class="fixed-plugin-button">🐇</span>
        </div>
    </div>
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
                        </div>
                        <div class="mt-4">
                            <h5 class="text-white text-bold">Previous 30 Days chat</h5>
                            <p class="text-white chat-hover" style="font-size: 13px; cursor: pointer;">
                                Merge Videos with FFmpeg</p>
                        </div>
                    </div>
                </div>
            </div>
            @livewire('client.chat.index')
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.querySelector('.fixed-plugin-button').addEventListener('click', function () {

        var message = document.querySelector('.messageChat').value;
        Livewire.emit('chatMessage', message);
    });

    window.Livewire.on('hideModal', function () {
        $('#exampleModalMessage').modal('hide');
    })
</script>
