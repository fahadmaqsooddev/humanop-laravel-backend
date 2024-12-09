@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
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
          color: lightgray;
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

      .disabledCard {
          pointer-events: none;
          opacity: 0.4;
      }
      </style>
    @endpush
<div class="card card-bg-white-orange-border mt-4" id="conversation">
    <div wire:loading.class="disabledCard" wire:target="user_id">

        <span class="swal2-loader" wire:loading wire:target="user_id" style="width: 30px; height: 30px;top: 230px;
        position: absolute; left: 400px;">
        </span>

        <div class="row h-100">

        {{--        <!-- Left-side Navigation Tabs -->--}}
        {{--        <div class="col-md-4 col-12 border-dark">--}}
        {{--            <ul class="nav nav-tabs flex-column flex-md-row">--}}
        {{--                <li class="nav-item">--}}
        {{--                    <a class="nav-link active" aria-current="page" href="#"--}}
        {{--                       style="font-size: small;">Active</a>--}}
        {{--                </li>--}}
        {{--                <li class="nav-item">--}}
        {{--                    <a class="nav-link" href="#" style="font-size: small;">Archive</a>--}}
        {{--                </li>--}}
        {{--                <li class="nav-item">--}}
        {{--                    <a class="nav-link" href="#" style="font-size: small;">Unread</a>--}}
        {{--                </li>--}}
        {{--                <li class="nav-item">--}}
        {{--                    <a class="nav-link" href="#" style="font-size: small;">Star</a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </div>--}}


        <!-- Chatbot Conversation Section -->
            <div class="col-md-12 col-12 d-flex flex-column container-fluid"
                 style="height: 85vh;">
                @include('layouts.message')

                <div class="row">
                    <div class="col-6">
                        <div class="d-flex justify-content-start" style="margin-left: 24px;margin-top: 14px;">
                            <div>
                                <img src="{{asset('assets\img\icons\assessment_intro_icon.png')}}" class="bg-white" width="50" height="50" style="border-radius: 25px;">
                            </div>
                            <div style="margin-left: 10px;font-weight: 600;font-size: 20px;margin-top: 5px">
                                <p class="mb-0" style="color: #E05A35;line-height: 20px">Assistant</p>
                                <p class="mb-0" style="color: #000000;font-size: 14px;font-weight: 400">Online</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-end" style="margin-right: 24px;margin-top: 18px" >
                            <div class="d-flex justify-content-end w-50" wire:ignore>
                                <label for="user_name"></label>
                                {{--                            <select name="user_name" wire:model="user_id" class="form-control chzn-select" id="user_name" style="background-color: #F3DEB4;color: #000000;border-radius:20px">--}}
                                {{--                                <option disabled value="">Select User</option>--}}
                                {{--                                @if(isset($user_details))--}}
                                {{--                                 @foreach($user_details as $user_detail)--}}
                                {{--                                    <option value="{{$user_detail['id']}}">{{$user_detail['first_name'] ?? ''}} {{$user_detail['first_name'] ?? ''}}</option>--}}
                                {{--                                 @endforeach--}}
                                {{--                                @endif--}}
                                {{--                            </select>--}}
                                <select wire:model="user_id" class="form-control" style="background-color: #F3DEB4;color: #000000;">
                                    <option value="">Select User</option>
                                    @if(isset($user_details))
                                        @foreach($user_details as $user_detail)
                                            <option value="{{$user_detail['id']}}">{{$user_detail['first_name'] ?? ''}} {{$user_detail['first_name'] ?? ''}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                            </div>
                            {{--                        <div class="col-6">--}}
                            {{--                            <span style="color: #f2661c;" class="text-sm">switching chat...</span>--}}
                            {{--                        </div>--}}
                        </div>
                    </div>
                </div>
                <hr style="color: #f2661c;" class="bold">
                <div class="d-flex flex-column justify-content-between flex-grow-1 p-3" id="chat_container"
                     style="overflow-y: auto;">
                    <!-- Message Container -->
                    <div id="chatMessages" class="d-flex flex-column gap-3">
                    @if(!empty($conversations))
                        @foreach($conversations as $conversation)
                            <!-- Initial User Message -->
                                <div class="d-flex flex-row gap-1 justify-content-end">
                                    @if($conversation['message'])

                                        <div class="rounded " style="max-width: 70%;">
                                            <div>
                                                <p class="text-end text-sm" style="color: #000000;margin-bottom: 3px;">Admin</p>
                                            </div>
                                            <div class="bg-secondary text-white p-2"  style="font-size:small;background: #E05A35 !important;border-radius: 10px 0px 10px 10px !important">
                                                {{ $conversation['message'] }}
                                            </div>
                                            <div>
                                                <p class="text-end" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse($conversation['created_at'] ?? null)->diffForHumans()}}</p>
                                            </div>
                                        </div>

                                        <div>
                                            <img src="{{URL::asset('assets/img/Human_OP.png')}}" width="50" height="35" style="border-radius: 50%">
                                        </div>
                                    @endif
                                </div>
                                <!-- Initial Assistant Message -->
                                <div class="d-flex flex-row gap-3 align-items-start">
                                    @if($conversation['reply'])
                                        <div>
                                            <img src="{{asset('assets\img\icons\assessment_intro_icon.png')}}" width="35" height="35" style="border-radius: 50%;background-color: white" >
                                        </div>
                                        <div class="rounded " style="max-width: 70%;">
                                            <div class="bg-primary text-white  p-2"
                                                 style="max-width: 70%; font-size:small;background-color: #F7F5F4 !important;color:#000000 !important;border-radius: 0px 10px 10px 10px !important">
                                                {!! $conversation['reply'] !!}
                                            </div>
                                            <div class="row" style="width: 100%;">
                                                <div class="col-7">
                                                    <p class="text-start" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse($conversation['created_at'] ?? null)->diffForHumans()}}</p>
                                                </div>
                                                @if(isset($conversation->id))
                                                    <div class="col-5">

                                                        <div class="rating d-flex mb-2">
                                                            <!-- Thumbs up -->
                                                            <div wire:loading.class="active" wire:target="likeReply" class="like grow {{$conversation['is_liked'] === 1 ? 'active' : ''}}"
                                                                 wire:click="likeReply({{$conversation['id'] ?? null}})">
                                                                <i class="fa fa-thumbs-up fa-2x" style="font-size: x-large;" aria-hidden="true"></i>
                                                            </div>
                                                            <!-- Thumbs down -->
                                                            <div wire:loading.class="active" wire:target="dislikeReply"
                                                                 class="dislike grow {{ $conversation['is_liked'] != null && in_array($conversation['is_liked'] ?? null, [2,3]) ? 'active' : ''}}"
                                                                 wire:click="dislikeReply({{$conversation->id ?? null}})">
                                                                <i class="fa fa-thumbs-down" style="font-size: x-large;" aria-hidden="true"></i>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        {{--                    This div is for append user message--}}
                        <div id="user_message_div" wire:ignore.self>
                        </div>
                    </div>
                    <div id="chatLoader" style="display: flex; justify-content:flex-start" wire:ignore.self>
                        <div id="chatDots" wire:loading wire:target="submitForm">
                            <span class="chatDot"></span>
                            <span class="chatDot"></span>
                            <span class="chatDot"></span>
                        </div>
                    </div>

                </div>
                <hr>
                <form wire:submit.prevent="submitForm" id="chat_form">
                    <div class="text-center">
                        @if(session('admin_conversation'))
                            <span class="text-success">{{session('admin_conversation')}}</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between" style="margin-left: 24px;margin-right: 24px;margin-bottom: 14px">
                        <div style="width: 100%">
                            <input type="text" wire:loading.attr="disabled" wire:target="user_id" id="userInput" wire:model.defer="message" placeholder="Your message....." class="form-control" style="padding: 4px;border-radius: 20px;padding-left: 10px;padding-right: 10px">
                        </div>
                        <div style="width: 5%" class="pt-1">
                            <button class="bg-transparent" type="submit" style="border:none" id="submit_btn">
                                <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}"  width="25" height="25" >
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@push('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
      $(".chzn-select").chosen();
      $(".chosen-single").css('background', '#F3DEB4');
      $(".chosen-single").css('height', '35px');
      $(".chosen-single").css('font-weight', '600');
      $(".chosen-single").css('padding-top', '5px');
      $(".chosen-single").css('width', '100%');
      $(".chosen-single div > b").css('margin-top', '5px');

      window.livewire.on('scrollToBottom', function (){

          scrollToBottom();
      });

      window.livewire.on('submitQuery', function (){

          $('#submit_btn').click();
      });


      $('#chat_form').on('submit', function () {

          let userMsg = $('#userInput');

          $('#user_message_div').append(`
                <div class="d-flex flex-row gap-3 justify-content-end">
                    <div class="rounded" style="max-width: 70%;">
                        <div>
                            <p class="text-end" style="color: #000000;margin-bottom: 3px">Admin</p>
                        </div>
                        <div class="bg-secondary text-white  p-2"  style="font-size:small;background: #E05A35 !important;border-radius: 10px 0px 10px 10px !important">
                            `+ userMsg.val() +`
                        </div>
                        <div>
                            <p class="text-end" style="color: #58534C;font-size: 14px">just now</p>
                        </div>
                    </div>

                    <div>
                        <img src="{{URL::asset('assets/img/default-user-image.png')}}" width="35" height="35" style="border-radius: 50%">
                    </div>
                </div>
          `);

          setTimeout(function (){
              scrollToBottom();
          }, 50);

          userMsg.val('');

          userMsg.disable();

      });

      function scrollToBottom() {
          const chatboxContent = $('#chat_container');
          chatboxContent.scrollTop(chatboxContent[0].scrollHeight);
      }

      $(document).ready(function (){
          setTimeout(function (){
              scrollToBottom();
          }, 500);

          const descriptionContainer = document.querySelector('#chat_container');
          descriptionContainer.addEventListener('wheel', (event) => {
              event.preventDefault();

              descriptionContainer.scrollBy({
                  top: event.deltaY < 0 ? -30 : 30,
              });
          });
      });

    </script>
@endpush
