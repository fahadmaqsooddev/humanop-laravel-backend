<div class="col-9">

    <div>
        <!-- Button trigger modal -->
        <button type="button" id="chat_ai_question_modal" class="btn bg-gradient-success btn-block mb-3"
                data-bs-toggle="modal" data-bs-target="#exampleModalMessage" hidden>
            2nd dislike question modal
        </button>
    </div>


    <div class="chatbox">
        <div class="chatbox-content" id="chatbox-content">
            @foreach($messages as $key => $message)
                <div class="message user-message flex-end">
                    {{ $message['query'] }}
                </div>
                <div class="flex-start">
                    <div class="message bot-message">
                        {!! $message['answer'] !!}
                    </div>
                    <div class="rating d-flex mb-2">
                        <!-- Thumbs up -->
                        <div class="like grow {{ $message['likedislike'] == 2 ? 'active' : '' }}" wire:click="like({{ $message['id'] }})">
                            <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>
                        </div>
                        <!-- Thumbs down -->
                        <div class="dislike grow {{ $message['likedislike'] == 1 || $message['likedislike'] == 0 ? 'active' : '' }}"
                             wire:click="dislike({{ $message['id'] }})">
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
        window.Livewire.on('showUserAnswerModal', function () {
            // Close any open plugin modal
            $('.fixed-plugin-close-button').click();

            $('.chatBoxClose').click();

            // Trigger the modal to show
            $('#exampleModalMessage').modal('show'); // Assuming you're using Bootstrap's modal

            // Alternatively, if you're not using Bootstrap, you might need to trigger the modal using custom jQuery:
            // $('#chat_ai_question_modal').click();
        });
    </script>
@endpush
