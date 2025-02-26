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

    <div id="chat_switch_loader">

        <div class="spinner-border custom-text-dark invisible" id="chat_switch_spinner" role="status" style="width: 30px; height: 30px;top: 230px;
                position: absolute; left: 400px;">
            <span class="sr-only">
                Loading...
        </span>
        </div>

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
                                <select name="user_name" wire:model="user_id" class="form-control chzn-select" id="user_name" style="background-color: #F3DEB4;color: #000000;border-radius:20px">
                                    <option value="">Select User</option>
                                    @if(isset($user_details))
                                     @foreach($user_details as $user_detail)
                                        <option value="{{$user_detail['id']}}">{{$user_detail['first_name'] .' '. $user_detail['last_name']}}</option>
                                     @endforeach
                                    @endif
                                </select>
{{--                                <select wire:model="user_id" class="form-control" style="background-color: #F3DEB4;color: #000000;">--}}
{{--                                    <option value="">Select User</option>--}}
{{--                                    @if(isset($user_details))--}}
{{--                                        @foreach($user_details as $user_detail)--}}
{{--                                            <option value="{{$user_detail['id']}}">{{$user_detail['first_name'] ?? ''}} {{$user_detail['first_name'] ?? ''}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </select>--}}
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
                        @foreach($conversations as $key => $conversation)
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
                                                 style="max-width: 100%; font-size:small;background-color: #F7F5F4 !important;color:#000000 !important;border-radius: 0px 10px 10px 10px !important">
                                                {!! $conversation['reply'] !!}
                                            </div>
                                            <div class="row" style="width: 100%;">
                                                <div class="col-10">
                                                    <p class="text-start" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse($conversation['created_at'] ?? null)->diffForHumans()}}</p>
                                                </div>
                                                @if(isset($conversation->id))
                                                    <div class="col-2">

                                                        <div class="rating d-flex mb-2">
                                                            <!-- Thumbs up -->
                                                            <div wire:loading.class="active" wire:target="likeReply" class="like grow {{$conversation['is_liked'] === 1 ? 'active' : ''}}"
                                                                 wire:click="likeReply({{$conversation['id'] ?? null}})">
                                                                <i class="fa fa-thumbs-up fa-2x" style="font-size: x-large;" aria-hidden="true"></i>
                                                            </div>
                                                            <!-- Edit Response -->
                                                            <div class="dislike" wire:click="editHaiResponse({{$conversation['id']}})"
                                                                 data-bs-toggle="modal" data-bs-target="#editHaiReplyModal{{$conversation['id']}}">
{{--                                                                <i class="fa fa-thumbs-down" style="font-size: x-large;" aria-hidden="true"></i>--}}
                                                                <i class="fa-solid fa-pen-to-square" style="font-size: x-large;" aria-hidden="true"></i>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endif

                                            </div>
                                        </div>

{{--                                    Edit Hai Reply Modal--}}
                                        <div wire:ignore.self class="modal fade" id="editHaiReplyModal{{ $conversation->id }}" tabindex="-1" role="dialog"
                                             aria-labelledby="editHaiReplyModal{{ $conversation->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-xl" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body" style=" border-radius: 9px">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label class="form-label fs-4 text-white">Query Answer</label>
                                                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                                                            aria-label="Close" id="close-query-edit-modal-{{$conversation->id}}">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    @include('layouts.message')
                                                                    <form wire:submit.prevent="updateHaiReply">
                                                                        @csrf
                                                                        <div class="form-group mt-2">
                                                                            <label class="form-label fs-6 text-white">Client Query :</label>
                                                                            <span
                                                                                style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$conversation['message'] ?? null}}</span>
                                                                            <label class="form-label fs-4 text-white">Answer :</label>
                                                                            <span class="copy-text float-end" >
                                       <!-- Copy text link -->
{{--                                        <a class="btn-sm text-white px-3"  style="background-color: #f2661c;" onclick="copyToClipboard(`{{$conversation['reply']}}`,`{{$key}}`, this)"><strong id="copy-text{{$key}}">Copy</strong></a>--}}

                                                                            </span>
                                                                            <br>
                                                                            <span class="mt-2">{!! $conversation['reply'] ?? null !!}</span>
                                                                            <br>
                                                                            <label class="form-label fs-6 text-white mt-4">Update Answer :</label>
                                                                    <div class="form-group" wire:ignore>
                                                                        <textarea rows="4" class="form-control text-white mt-2 editor"
                                                                                  style="background-color: #0f1535"
                                                                                  wire:model.defer="updated_reply"
                                                                                  placeholder="update answer">
                                                                        </textarea>
                                                                    </div>
                                                                </div>
                                                                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
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
                            <textarea rows="1" type="text" wire:loading.attr="disabled" wire:target="user_id" id="userInput" wire:model.defer="message" placeholder="Your message....." class="form-control" style="padding: 4px;border-radius: 20px;padding-left: 10px;padding-right: 10px"></textarea>
                        </div>
                        <div style="width: 5%" class="pt-1">
                            <button class="bg-transparent" type="submit" style="border:none" id="submit_btn">
                                <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}"  width="25" height="25">
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> --}}
    <script src="https://cdn.ckeditor.com/4.20.0/full/ckeditor.js"></script>

{{--
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(() => {
                CKEDITOR.replace('editor');
                CKEDITOR.instances.editor.on('change', function () {
                @this.set('updated_reply', this.getData());
                });
            }, 500);
        });

        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                if (CKEDITOR.instances.editor) {
                    CKEDITOR.instances.editor.destroy();
                }
                CKEDITOR.replace('editor');
                CKEDITOR.instances.editor.on('change', function () {
                @this.set('updated_reply', this.getData());
                });
            });
        });
    </script> --}}


    {{-- my --}}
    <script>
        function initializeEditors() {
    document.querySelectorAll('.editor').forEach((element, index) => {
        // Assign a unique ID if not already assigned
        if (!element.dataset.ckeditorId) {
            const uniqueId = 'editor-' + index;
            element.dataset.ckeditorId = uniqueId;
            element.setAttribute('id', uniqueId);
        }

        const editorId = element.dataset.ckeditorId;

        // Destroy existing instance if already initialized
        if (CKEDITOR.instances[editorId]) {
            CKEDITOR.instances[editorId].destroy();
        }

        // Initialize CKEditor
        CKEDITOR.replace(editorId);

        // Sync CKEditor with Livewire
        CKEDITOR.instances[editorId].on('change', function () {
            Livewire.find(element.closest('[wire\\:id]').getAttribute('wire:id'))
                .set(element.getAttribute('wire:model.defer'), this.getData());
        });
    });
}

// Run when page loads
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(() => {
        initializeEditors();
    }, 500);
});

// Re-initialize CKEditor after Livewire updates the DOM
document.addEventListener('livewire:load', function () {
    Livewire.hook('message.processed', (message, component) => {
        initializeEditors();
    });
});

    </script>

{{--
<script>
    function initializeEditors() {
        document.querySelectorAll('.editor').forEach((element, index) => {
            // Assign a unique ID if not already assigned
            if (!element.dataset.ckeditorId) {
                const uniqueId = 'editor-' + index;
                element.dataset.ckeditorId = uniqueId;
                element.setAttribute('id', uniqueId);
            }

            const editorId = element.dataset.ckeditorId;

            // Destroy existing instance if already initialized
            if (CKEDITOR.instances[editorId]) {
                CKEDITOR.instances[editorId].destroy();
            }

            // Initialize CKEditor with text color and background color options
            CKEDITOR.replace(editorId, {
                extraPlugins: 'colorbutton,colordialog',  // Ensure color options are enabled
                removePlugins: 'elementspath',
                resize_enabled: false,
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] }, // Add text & background color buttons
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                    { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                    { name: 'insert', items: ['Image', 'Link'] },
                    { name: 'clipboard', items: ['Undo', 'Redo'] },
                    { name: 'tools', items: ['Maximize', 'Source'] }
                ]
            });

            // Sync CKEditor with Livewire
            CKEDITOR.instances[editorId].on('change', function () {
                Livewire.find(element.closest('[wire\\:id]').getAttribute('wire:id'))
                    .set(element.getAttribute('wire:model.defer'), this.getData());
            });
        });
    }

    // Run when page loads
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            initializeEditors();
        }, 500);
    });

    // Re-initialize CKEditor after Livewire updates the DOM
    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            initializeEditors();
        });
    });

</script>
 --}}




    <script>
      $(".chzn-select").chosen();
      $(".chosen-single").css('background', '#F3DEB4');
      $(".chosen-single").css('height', '35px');
      $(".chosen-single").css('font-weight', '600');
      $(".chosen-single").css('padding-top', '5px');
      $(".chosen-single").css('width', '100%');
      $(".chosen-single div > b").css('margin-top', '5px');
      $(".chosen-single span").attr('id', 'user_id');

      window.livewire.on('scrollToBottom', function (){

          scrollToBottom();
      });

      window.livewire.on('submitQuery', function (){

          $('#submit_btn').click();
      });

      window.livewire.on('closeEditHaiReplyModal', function (id){

          $('#close-query-edit-modal-' + id).click();
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
                        <img src="{{URL::asset('assets/img/Human_OP.png')}}" width="50" height="35" style="border-radius: 50%">
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

      $('.chosen-single').click(function (){

              var dop = document.querySelectorAll('.chosen-drop > ul > li');

              var dop2 = document.querySelector('.chosen-drop > .chosen-search > .chosen-search-input');

              dop2.addEventListener('keydown', function (){

                  setTimeout(function (){

                      dop = document.querySelectorAll('.chosen-drop > ul > li');

                      dopElements(dop);

                  }, 800);

              });

              dopElements(dop);
      });

      function dopElements(dop){

          dop.forEach(function (value, key){

              value.addEventListener('click', function (event){

                  $('#chat_switch_loader').addClass('disabledCard');
                  $('#chat_switch_spinner').removeClass('invisible');

                  console.log(event);

                  updateUserId(event.target.dataset.optionArrayIndex);

              });
          });
      }

    </script>
@endpush

@push('js')

    <script>

        function updateUserId(id){

            window.livewire.emit('updateUserId', id);
        }

        document.getElementById('userInput').addEventListener('keydown', function (e) {
            if (e.shiftKey === false && e.key === 'Enter') {
                $('#submit_btn').click();
            }
        });

    </script>

@endpush
