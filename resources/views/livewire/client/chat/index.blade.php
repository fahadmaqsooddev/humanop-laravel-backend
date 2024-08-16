<div class="col-9">

    <div>
        <!-- Button trigger modal -->
        <button type="button" id="chat_ai_question_modal" class="btn bg-gradient-success btn-block mb-3" data-bs-toggle="modal" data-bs-target="#exampleModalMessage" hidden>
            2nd dislike question modal
        </button>
    </div>

    <div class="chatbox">
        <div class="chatbox-content" id="chatbox-content">

            @foreach($messages as $message)
                <div style="display: flex; justify-content: {{ $message['type'] === 'user' ? 'flex-end' : 'flex-start' }}">
                    @if($message['type'] === 'user')
                        <div class="message {{ $message['type'] === 'user' ? 'user-message' : 'bot-message' }}">
                            {{ $message['text'] }}
                        </div>
                    @elseif($message['type'] === 'bot')
                        <div class="d-flex flex-column">
                            <div class="message {{ $message['type'] === 'user' ? 'user-message' : 'bot-message' }}">
                                {!! $message['text'] !!}
                            </div>
                            <div class="rating d-flex mb-2">
                                <!-- Thumbs up -->
                                <div class="like grow {{ $likeActive ? 'active' : '' }}" wire:click="like">
                                    <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>
                                </div>
                                <!-- Thumbs down -->
                                <div class="dislike grow {{ $message['is_dislike'] ? 'active' : '' }}" id="thumbDown"
                                     wire:click="dislike">
                                    <i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

        </div>

{{--        This loader works for when user dislikes the AI chat answer --}}
        <div style="display: flex; justify-content:flex-start">
            <div id="chatDots" wire:loading wire:target="dislike">
                <span class="chatDot"></span>
                <span class="chatDot"></span>
                <span class="chatDot"></span>
            </div>
        </div>

        <form wire:submit.prevent="sendMessage">
            @csrf
            <div class="chatbox-input" style="margin-bottom: 50px">
                <input type="text" wire:model.defer="userMessage" id="userMessage"
                       placeholder="Type your message here...">
                <button type="submit" id="submitBtn">&#9658;</button>
            </div>
        </form>
    </div>
</div>


@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function scrollToBottom() {
            const chatboxContent = $('#chatbox-content');
            chatboxContent.scrollTop(chatboxContent[0].scrollHeight);
        }

        document.addEventListener('livewire:load', function () {
            const submitBtn = document.getElementById('submitBtn');
            $('#submitBtn').on('click', function () {

                let userMsg = $('#userMessage').val();
                if (userMsg.trim() !== '') {
                    $('#chatbox-content').append(`<div style="display: flex; justify-content: flex-end">
                        <div class="message user-message">` + userMsg + `</div>
                    </div>`);
                }
                $('#userMessage').val('');

                $('#chatbox-content').append(`<div id="chatLoader" style="display: flex; justify-content:flex-start">
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

    <script>
        window.Livewire.on('showUserAnswerModal', function ($q){

            $('.fixed-plugin-close-button').click();

            $('#chat_ai_question_modal').click();
        })
    </script>
@endpush
