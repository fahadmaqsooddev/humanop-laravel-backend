<div class="d-flex container-fluid vh-100  px-0  ">

<div class="col-4 col-lg-3  ">

    <div class="chatbox h-100">
        <div class="chatbox-content" style="background-color: #f2661c">
            <div class="mt-4 chat-hover d-flex align-items-center justify-content-center" style="cursor: pointer;">
                <i class="fa fa-plus pb-2 me-1" style="color: white;"></i>
                <h5 class="text-white text-bold fs-10px" style="">New chat</h5>
            </div>
            <hr>

            <div class="mt-4">
                <h5 class="text-white text-bold fs-10px">Today chat</h5>
                <p class="text-white fs-7px {{$chatFilter === 0 ? "chat-history" : ""}}" wire:click="filterChats(0)" style="cursor: pointer;">
                    click to load today's chat
                </p>
            </div>
            <div class="mt-4">
                <h5 class="text-white text-bold fs-10px">Yesterday chat</h5>
                <p class="text-white fs-7px {{$chatFilter === 2 ? "chat-history" : ""}}" wire:click="filterChats(2)" style="cursor: pointer;">
                    click to load yesterday chat
                </p>
            </div>
            <div class="mt-4">
                <h5 class="text-white text-bold fs-10px">Previous 5 Days chat</h5>
                <p class="text-white fs-7px {{$chatFilter === 5 ? "chat-history" : ""}}" wire:click="filterChats(5)" style="cursor: pointer;">
                    click to load previous 5 days chat
                </p>
            </div>
            <div class="mt-4">
                <h5 class="text-white text-bold fs-10px">Previous 7 Days chat</h5>
                <p class="text-white  fs-7px {{$chatFilter === 7 ? "chat-history" : ""}}" wire:click="filterChats(7)" style="cursor: pointer;">
                    click to load previous 7 days chat
                </p>
            </div>
        </div>
    </div>
</div>

<div>
        <!-- Button trigger modal -->
        <button type="button" id="chat_ai_question_modal" class="btn bg-gradient-success btn-block mb-3"
                data-bs-toggle="modal" data-bs-target="#exampleModalMessage" hidden>
            2nd dislike question modal
        </button>
    </div>

<div class="col-8 col-lg-9 px-0 d-flex flex-column  ">




 {{--    @empty($messages)--}}
    <div class="prompt-suggestion d-flex    align-items-center  justify-content-center text-center  p-2 " id="suggestion_text_box">

        <div class="d-flex text-center  p-0   w-20 h-100  text-wrap" onclick="suggestionQueries('What can you tell me about myself?')" style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">

            <div class="d-flex align-items-center  justify-content-center text-center p-2 word-wrap  ">
                <p class="prompt-text fs-7px" >What can you tell me about myself?</p>
            </div>
        </div>
        &nbsp;&nbsp;&nbsp;

        <div class=" d-flex align-items-center  justify-content-center text-center   p-0   w-20 h-100 text-wrap  " onclick="suggestionQueries('Tell me about my main (driver) (style) (alchemy) (energy center) [random between the four] and what motivates me in life?')" style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">
            <div class="d-flex align-items-center text-center  justify-content-center p-2 word-wrap ">
                <p class="prompt-text fs-7px">Tell me about my main (driver) (style) (alchemy) (energy center) [random between the four] and what motivates me in life?</p>
            </div>
        </div>

        &nbsp;&nbsp;&nbsp;
        <div class=" d-flex align-items-center  justify-content-center text-center   p-0   w-20 h-100  text-wrap " onclick="suggestionQueries('How to use this platform?')" style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">
            <div class="d-flex align-items-center text-center  justify-content-center p-2 word-wrap ">
                <p class="prompt-text fs-7px">How to use this platform?</p>
            </div>
        </div>
        
        &nbsp;&nbsp;&nbsp;
        <div class=" d-flex align-items-center  justify-content-center text-center   p-0   w-20 h-100 text-wrap " onclick="suggestionQueries('How to optimize my actions to be in alignment with my highest self?')" style="border: 1px solid #f2661c; border-radius: 7px;  cursor: pointer;">
            <div class="d-flex align-items-center text-center  justify-content-center p-2 word-wrap ">
                <p class="prompt-text fs-7px">How to optimize my actions to be in alignment with my highest self?</p>
            </div>
        </div>
    </div>
    

 {{--    @endempty--}}
    <div class="chatbox  position-relative">
        <div class="chatbox-content  d-flex flex-column justify-content-between" id="chatbox-content">
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

        
    </div>
    <form wire:submit.prevent="sendMessage " class=" m-0">
            @csrf
            <div class="chatbox-input  position-absolute bottom-0 right-0 w-75" >
                <input type="text" wire:model="userMessage" id="userMessage" style="border-radius: 30px 0 0 30px"
                placeholder="Talk with Pi">
                <button type="submit" id="submitBtn" style="border-radius: 0 30px 30px 0">
                    <div style="background-color: #f2661c; color: white; border-radius: 50%; padding: 10px;">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                    </div>
                </button>
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

                // scroll to bottom
                var objDiv = document.getElementById("chatbox-content");
                objDiv.scrollTop = objDiv.scrollHeight;

                // scrollToBottom();
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

        function suggestionQueries(text){

            $('#userMessage').val(text);

            Livewire.emit('chatMessage', text);

            setTimeout(function (){
                $('#submitBtn').click();
            }, 1000)
        }

        window.livewire.on('scrollToBottom', function (){

            var objDiv = document.getElementById("chatbox-content");

            $("#chatbox-content").animate({ scrollTop: objDiv.scrollHeight }, "slow");
        });

 

    </script>
@endpush
