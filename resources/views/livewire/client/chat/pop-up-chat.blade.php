<div>


    <div class="card shadow-lg blur chatBoxShow d-none" style="background-color: lightgrey !important;" wire:ignore.self>
        <div class="card-header pb-0 pt-3" style="background-color: lightgrey; width: 500px">
            <div class="d-flex" style="justify-content: space-between">
                <h5 style="color: #f2661c; font-weight: bold; font-size: 25px">H.A.I CHAT INTERFACE</h5>
                <div class="">
                    <button class="btn btn-link chatBoxClose"
                            style="background-color: #f2661c;color: white;padding: 3px 8px 3px 8px;border-radius: 8px;width: 32px; height: 32px; font-size: 20px">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
            </div>

            <div class="chatbox">
                <div class="chatbox-content" id="chatbox-content-pop-up">

                    @foreach($chats as $chat)

                        <div style="display: flex; justify-content: end">
                            <div class="message chat-message">
                                {{$chat['query']}}
                            </div>
                        </div>
                        <div class="flex-start">
                            <div class="message chat-reply-message">
                                {!! $chat['answer'] !!}
                            </div>
                            <div class="rating d-flex mb-2">
                                <!-- Thumbs up -->
                                <div class="like grow {{ $chat['likedislike'] == 2 ? 'active' : '' }}" wire:click="like({{ $chat['id'] }})">
                                    <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>
                                </div>
                                <!-- Thumbs down -->
                                <div class="dislike grow {{ $chat['likedislike'] == 1 || $chat['likedislike'] == 0 ? 'active' : '' }}"
                                     wire:click="dislike({{ $chat['id'] }})">
                                    <i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>


                    @endforeach

                </div>
                    <div style="display: flex; justify-content:flex-start;">
                        <div id="chatDots" wire:loading wire:target="dislike">
                            <span class="chatDot"></span>
                            <span class="chatDot"></span>
                            <span class="chatDot"></span>
                        </div>
                    </div>
                <form wire:submit.prevent="sendMessage">
                    @csrf
                    <div class="chatbox-input">
                        <input type="text" wire:model.defer="userMessage" id="userMessage-pop-up" style="border-radius: 30px 0 0 30px"
                               placeholder="Talk with HAI">
                        <button type="submit" id="submitBtn-pop-up" style="border-radius: 0 30px 30px 0">
                            <div style="background-color: #f2661c; color: white; border-radius: 50%; padding: 10px;">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        function scrollToBottom() {
            const chatboxContent = $('#chatbox-content-pop-up');
            chatboxContent.scrollTop(chatboxContent[0].scrollHeight);
        }

        document.addEventListener('livewire:load', function () {
            const submitBtn = document.getElementById('submitBtn-pop-up');
            $('#submitBtn-pop-up').on('click', function () {

                let userMsg = $('#userMessage-pop-up').val();
                if (userMsg.trim() !== '') {
                    $('#chatbox-content-pop-up').append(`<div style="display: flex; justify-content: flex-end">
                        <div class="message user-message">` + userMsg + `</div>
                    </div>`);
                }
                $('#userMessage-pop-up').val('');

                $('#chatbox-content-pop-up').append(`<div id="chatLoader" style="display: flex; justify-content:flex-start">
                    <div id="chatDots">
                        <span class="chatDot"></span>
                        <span class="chatDot"></span>
                        <span class="chatDot"></span>
                    </div>
                </div>`);
                scrollToBottom();
            });
        });
    </script>
@endpush
