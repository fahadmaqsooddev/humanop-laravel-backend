<div class="d-flex container-fluid vh-100   px-0">

    <div>
        <!-- Button trigger modal -->
        <button type="button" id="chat_ai_question_modal" class="btn bg-gradient-success btn-block mb-3"
                data-bs-toggle="modal" data-bs-target="#exampleModalMessage" hidden>
            2nd dislike question modal
        </button>
    </div>

    <div class="col-12 col-lg-12  px-0 d-flex flex-column ">


        {{--    @empty($messages)--}}
        <div class="fixed-plugin prompt-suggestion d-flex align-items-center justify-content-evenly text-center p-4"
             id="suggestion_text_box">

            <div class="d-flex align-items-center justify-content-center text-center p-0 w-20 h-100 text-wrap"
                 onclick="suggestionChatQueries('What can you tell me about myself?')"
                 style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">

                <div class="d-flex align-items-center  justify-content-center text-center p-2 word-wrap  ">
                    <p class="prompt-text fs-7px">What can you tell me about myself?</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center text-center p-0 w-20 h-100 text-wrap"
                 onclick="suggestionChatQueries('Tell me about my main (driver) (style) (alchemy) (energy center) [random between the four] and what motivates me in life?')"
                 style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">
                <div class="d-flex align-items-center text-center  justify-content-center p-2 word-wrap ">
                    <p class="prompt-text fs-7px">Tell me about my main (driver) (style) (alchemy) (energy center)
                        [random between the four] and what motivates me in life?</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center text-center p-0 w-20 h-100 text-wrap"
                 onclick="suggestionChatQueries('How to use this platform?')"
                 style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">
                <div class="d-flex align-items-center text-center  justify-content-center p-2 word-wrap ">
                    <p class="prompt-text fs-7px">How to use this platform?</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center text-center p-0 w-20 h-100 text-wrap"
                 onclick="suggestionChatQueries('How to optimize my actions to be in alignment with my highest self?')"
                 style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">
                <div class="d-flex align-items-center text-center  justify-content-center p-2 word-wrap ">
                    <p class="prompt-text fs-7px">How to optimize my actions to be in alignment with my highest
                        self?</p>
                </div>
            </div>
        </div>


        {{--    @endempty--}}
        <form wire:submit.prevent="sendMessage" class="m-0">
            @csrf
            <div class="chatbox-input right-0 w-100">
                {{--            <input type="text" wire:model="userMessage" id="userMessage" style="border-radius: 30px 0 0 30px"--}}
                {{--                   placeholder="Talk with Hai">--}}
                <textarea rows="3" cols="3" style="background-color: #0f1534;" wire:model="userMessage" id="userMessage"
                          class="form-control text-white messageChat mb-2 "
                          placeholder="Type your message here..."></textarea>

                <button type="submit" id="submitBtn" style="display:none !important;border-radius: 0 30px 30px 0">
                    <div style="background-color: #f2661c; color: white; border-radius: 50%; padding: 10px;">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                </button>
            </div>

        </form>
        <button id="toggle-btn" class="btn btn-primary w-10 align-self-center"
                style="background-color:rgb(210, 103, 34);"><i
                class="chat-ham fa-solid fa-bars "
                onclick=""></i></button>
        <div class="chatbox h-80"
             style="display: none;background-color: #0f1534;border-radius: 5px;border:1px solid white" id="content">
            <div style="display: flex; justify-content:flex-start;">
                <div id="chatDots" wire:loading wire:target="dislike">
                    <span class="chatDot"></span>
                    <span class="chatDot"></span>
                    <span class="chatDot"></span>
                </div>
            </div>

            <div class="chatbox-content  d-flex flex-column justify-content-between" id="chatbox-content">

                <div id="chat-box-new-content" wire:ignore.self>

                </div>

            @foreach($messages as $key => $message)

                <!-- user side message  -->
                    <div class="message user-message ">
                        {{ $message['query'] }}
                    </div>

                    <!-- chatbot side message  -->
                    <div class="">
                        <div class="message bot-message">
                            {!! $message['answer'] !!}
                        </div>
                        <!-- rating -->
                        <div class="rating d-flex mb-2">
                            <!-- Thumbs up -->
                            <div class="like grow {{ $message['likedislike'] == 2 ? 'active' : '' }}"
                                 wire:click="like({{ $message['id'] }})">
                                <i class="fa fa-thumbs-up fa-2x" aria-hidden="true"></i>
                            </div>
                            <!-- Thumbs down -->
                            <div
                                class="dislike grow {{ $message['likedislike'] == 1 || $message['likedislike'] == 0 ? 'active' : '' }}"
                                wire:click="dislike({{ $message['id'] }})">
                                <i class="fa fa-thumbs-down fa-2x" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>

</div>


@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function scrollToBottom() {
            const chatboxContent = $('#chatbox-content');
            chatboxContent.scrollTop(chatboxContent[0].scrollHeight);
        }

        window.Livewire.on('showChatBox', function () {
            chatBox = document.getElementById('content');
            chatToggleButton = document.querySelector('.chat-ham');
            chatBox.style.display = "flex";
            chatToggleButton.className = 'fa-solid fa-xmark';
        });

        document.addEventListener('livewire:load', function () {
            const submitBtn = document.getElementById('submitBtn');

            $('#submitBtn').on('click', function () {

                let userMsg = $('#userMessage').val();

                if (userMsg.trim() !== '') {
                    $('#chat-box-new-content').append(`<div style="display: flex; justify-content: flex-end">
                        <div class="message user-message">` + userMsg + `</div>
                    </div>`);
                }

                $('#userMessage').val('');

                $('#chat-box-new-content').append(`<div id="chatLoader" style="display: flex; justify-content:flex-start">
                    <div id="chatDots">
                        <span class="chatDot"></span>
                        <span class="chatDot"></span>
                        <span class="chatDot"></span>
                    </div>
                </div>`);

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

        function suggestionChatQueries(text) {
            $('#userMessage').val(text);

            Livewire.emit('chatMessage', text);

            setTimeout(function () {
                $('#submitBtn').click();
                chatBox = document.getElementById('content');
                chatToggleButton = document.querySelector('.chat-ham')
                chatBox.style.display = "flex";
                chatToggleButton.className = 'fa-solid fa-xmark';
            }, 1000)
        }

        document.querySelector('.messageChat').addEventListener('keypress', function (e) {
            if (e.key === '\n' && e.ctrlKey) {

                console.log('sadfsdafasd nice');

                $('.fixed-plugin-button').click();

                var message = document.querySelector('.messageChat').value;

                Livewire.emit('chatMessage', message);

                setTimeout(function () {

                    $('#submitBtn').click()

                    $('#messageChat').val('');
                }, 1000);

            }
        });
    </script>
@endpush
