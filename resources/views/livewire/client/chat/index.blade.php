<div class="col-9">
    <div class="chatbox">
        <div class="chatbox-content" id="chatbox-content">
            @foreach($messages as $message)
                <div style="display: flex; justify-content: {{ $message['type'] === 'user' ? 'flex-end' : 'flex-start' }}">
                    <div class="message {{ $message['type'] === 'user' ? 'user-message' : 'bot-message' }}">
                        {{ $message['text'] }}
                    </div>
                </div>
            @endforeach
        </div>
        <form wire:submit.prevent="sendMessage">
            @csrf
            <div class="chatbox-input" style="margin-bottom: 50px">
                <input type="text" wire:model.defer="userMessage" id="userMessage"  placeholder="Type your message here...">
                <button type="submit" id="submitBtn" disabled>&#9658;</button>
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
            $('#userMessage').on('input',function(){
                if ($(this).val().trim() !== '') {
                    console.log('not empty');
                    $('#submitBtn').prop('disabled',false);
                }else{
                    console.log('empty');
                    $('#submitBtn').prop('disabled',true);
                }
            });
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

            Livewire.on('updateAiMessage', function() {
                scrollToBottom();
            });
        });
    </script>
@endpush
