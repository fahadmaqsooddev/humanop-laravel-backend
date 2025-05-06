@push('css')

    <style>
        .new-orange-button{
            background-color: #F95520 !important;
            padding: 10px 20px 10px 20px;
            border-radius: 8px;
            color: white;
            border-color: transparent;
            cursor: pointer;
            font-weight: 800;
        }

        .new-orange-button:hover{
            color: white;
        }

        .input-bg{
            background-color: #F3DEBA !important;
            color: #F95520 !important;
            border-radius: 40px !important;
            border: none !important;
            text-align: center;
            width: 300px !important;
        }

        .input-bg::placeholder{
            color: #F95520 !important;
        }

        textarea::placeholder{
            color: #F95520 !important;
        }

        .create-new-brain-btn{
            background-color: #F95520 !important;
            border-radius: 40px !important;
            border: none !important;
            text-align: center;
        }
    </style>

@endpush
<div>
    <div class="d-flex justify-content-end">

        <div class="d-flex justify-content-between gap-2">

            <input wire:model="search_brain" type="text" placeholder="Search brain database" class="input-bg">

            <a data-bs-toggle="modal" data-bs-target="#createChatModal"
                style="padding: 10px 16px 10px 16px; border-radius: 7px;"
               class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button create-new-brain-btn">Create New Brain
            </a>

        </div>
    </div>

    <!-- Chatbot Cards Container -->
    <div id="chatbotCardsContainer" class="mt-3 row p-3">
        <!-- Example Card -->
        @foreach($chats as $chat)

            <div class="mt-3 col-12" style="padding-right: 20px;">
                <div class="card card-body " style="border: 3px solid {{$chat->chat_bot_color}}; background-color: {{$chat->chat_bot_color}}">

                    <div class="d-flex justify-content-between w-100">
                        <a href="{{route('admin_edit_brain', $chat['id'])}}" class="w-60">
                            <div class="py-2">
                                <h5 style="color: #f2661c" class="text-decoration-none">
                                    <i class="bi bi-robot"></i>
                                    {{ $chat['brain_name'] ?? $chat['name'] }}
                                </h5>
                            </div>
                            <div class="py-2">
                                @if(strlen($chat['description']) > 100)

                                    <p class="card-text" style="color: black">{{ substr($chat['description'], 0, 50) }}  <span wire:click="showModalChatBotDetail({{$chat['id']}})" data-toggle="modal" data-target="#chatBotDetailModal" style="color: #f2661c; cursor: pointer;"><b>read more...</b></span></p>

                                @else
                                    <p class="card-text " style="color: black">{{ $chat['description'] }}</p>
                                @endif
                            </div>
                        </a>
                        <div>
                            <div class="d-flex justify-content-between">
                                @if($chat['is_published'] == 1)
                                    <div class="py-2 px-2">
                                        <a href="javascript:void(0)" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;">Connected</a>
                                    </div>
                                @endif
                                @if($chat['setting'] && $chat['setting']['maestro_app'] != 0)
                                    <div class="py-2 px-2">
                                        <a href="{{route('admin_hai_chat_persona', ['name' => $chat['name']])}}" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;">Connected to B2B</a>
                                    </div>
                                @endif
                                <div class="py-2">
                                    @if($chat['setting']['persona_name'] ?? false)
                                        <a href="{{route('admin_hai_chat_persona', ['name' => $chat['name']])}}" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;">Connected to {{ strlen($chat['setting']['persona_name']) > 20 ? substr($chat['setting']['persona_name'], 0, 20) . "..." : $chat['setting']['persona_name']}}</a>
                                    @else
                                        <a href="{{route('admin_hai_chat_persona', ['name' => $chat['name']])}}" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;">Not Connected</a>
                                    @endif
                                </div>

                            </div>
                            <div class="d-flex justify-content-end py-4">
                                {{--                            <p class="text-dark" style="padding-right: 8px; color: black"><i class="bi bi-clock text-white"></i> less--}}
                                {{--                                than a minute</p>--}}
                                <div class="d-flex gap-2">
                                    <button class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button navButtonResponsive"
                                            data-bs-toggle="modal" data-bs-target="#copyChatBot"
                                            wire:click="copyChatBot({{$chat->id}})">
                                        <i class="fa-solid fa-copy"></i></button>
                                    <button  onclick="deleteChatBot({{ $chat['id'] }})"
                                             class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">
                                        <i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

{{--                    <div class="d-flex flex-column gap-3 chat-card">--}}
{{--                        <div class="d-flex justify-content-between">--}}
{{--                            <a href="{{route('admin_edit_brain', $chat['id'])}}">--}}
{{--                                <h5 style="color: #f2661c" class="text-decoration-none"><i--}}
{{--                                        class="bi bi-robot"></i> {{ $chat['name'] }}--}}
{{--                                </h5>--}}
{{--                            </a>--}}

{{--                            <div>--}}

{{--                                @if($chat['setting']['persona_name'] ?? false)--}}
{{--                                    <a href="{{route('admin_hai_chat_persona', ['name' => $chat['name']])}}" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;">Connected to {{$chat['setting']['persona_name']}}</a>--}}
{{--                                @else--}}
{{--                                    <a href="{{route('admin_hai_chat_persona', ['name' => $chat['name']])}}" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;">Not Connected</a>--}}
{{--                                @endif--}}

{{--                                @if($chat['is_published'] === 1)--}}
{{--                                    <a style="border: 2px solid #f2661c; color: white; background-color: #f2661c;border-radius: 10px; padding: 7px;">Published</a>--}}
{{--                                @else--}}
{{--                                    <a wire:click="publishChatBot({{$chat->id}})" style="border: 2px solid #f2661c; color: #f2661c;border-radius: 10px; padding: 7px;cursor: pointer;">Publish</a>--}}
{{--                                @endif--}}

{{--                            </div>--}}

{{--                        </div>--}}
{{--                        @if(strlen($chat['description']) > 50)--}}

{{--                            <p class="card-text" style="color: black">{{ substr($chat['description'], 0, 50) }}  <span wire:click="showModalChatBotDetail({{$chat['id']}})" data-toggle="modal" data-target="#chatBotDetailModal" style="color: #f2661c; cursor: pointer;"><b>read more...</b></span></p>--}}

{{--                        @else--}}
{{--                            <p class="card-text " style="color: black">{{ $chat['description'] }}</p>--}}
{{--                        @endif--}}
{{--                        <div class="d-flex justify-content-end">--}}
{{--                            <p class="text-dark" style="padding-right: 8px; color: black"><i class="bi bi-clock text-white"></i> less--}}
{{--                                than a minute</p>--}}
{{--                            <div class="d-flex gap-2">--}}
{{--                                <button class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button navButtonResponsive"--}}
{{--                                        data-bs-toggle="modal" data-bs-target="#copyChatBot"--}}
{{--                                wire:click="copyChatBot({{$chat->id}})">--}}
{{--                                    <i class="fa-solid fa-copy"></i></button>--}}
{{--                                <button  onclick="deleteChatBot({{ $chat['id'] }})"--}}
{{--                                        class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">--}}
{{--                                    <i class="fa-solid fa-trash"></i></button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Chat Modal -->

    <div wire:ignore.self class="modal fade" id="createChatModal" tabindex="-1" aria-labelledby="createChatModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="modal-title text-white" id="createChatModalLabel">Create New Brain</h5>
                        <button type="button" class="close modal-close-btn new-orange-button" id="createChatModal"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @include('layouts.message')
                    <form wire:submit.prevent="redirectToCreateBrainInterface">
                        <div class="pt-0">
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label class="form-label text-white">Name of Brain</label>
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;color: lightgrey !important"
                                               class="form-control text-white"
                                               type="text" name="limit"
                                               placeholder="Enter name of brain"
                                               wire:model="name">
                                        @error('name')
                                        <span class="text-sm text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label text-white">Description of Brain</label>
                                    <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="5" cols="5"
                                                      name="description"
                                                      placeholder="Enter description of brain"
                                                      wire:model="description"></textarea>
                                        @error('information')
                                        <span class="text-sm text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-sm-2 float-end mt-6 mb-0 p-2 px-3 text-white create-new-brain-btn">
                                CREATE NEW BRAIN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


{{--    Chatbot Detail Modal--}}

    <div wire:ignore.self class="modal fade" id="chatBotDetailModal" tabindex="-1" aria-labelledby="chatBotDetailModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="modal-title text-white" id="chatBotDetailModalLabel">{{$chatBot->name ?? null}}</h4>
                        <button type="button" class="close modal-close-btn new-orange-button"
                                data-dismiss="modal" wire:click="closeChatBotDetailModal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <p class="text-white w-100 p-2 text-justify">
                        {{$chatBot->description ?? null}}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="copyChatBot" tabindex="-1" aria-labelledby="copyChatBotLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="modal-title text-white" id="copyChatBot">Create a Duplicate Brain</h5>
                        <button type="button" class="close modal-close-btn new-orange-button" id="copyChatBotCloseButton"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @include('layouts.message')
                    <form wire:submit.prevent="createDuplicateChatBot">
                        <div class="pt-0">
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label class="form-label text-white">Name of Duplicate Brain</label>
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;color: lightgrey !important"
                                               class="form-control text-white"
                                               type="text" name="limit"
                                               placeholder="Enter duplicate brain name"
                                               wire:model="name">
                                        @error('name')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label text-white">Description of Duplicate Brain</label>
                                    <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="5" cols="5"
                                                      name="description"
                                                      placeholder="Enter duplicate brain description"
                                                      wire:model="description"></textarea>
                                        @error('information')
                                        <span class="text-sm text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-sm-2 float-end mt-6 mb-0 text-white new-orange-button">

                                <span wire:loading.remove wire:target="createDuplicateChatBot">Copy Chatbot</span>

                                <span wire:loading wire:target="createDuplicateChatBot">Coping...</span>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@push('javascript')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>

        function deleteChatBot(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to delete Brain</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteChatbot', id)
                }
            })
        }

        window.livewire.on('closeAlert', function (){
            setTimeout(function (){
                $('.alert').alert('close');
            }, 5000);
        })

        window.Livewire.on('closeCopyChatBot', function () {

            setTimeout(function () {
                $('#copyChatBotCloseButton').click();
            }, 1000);
        });

    </script>

@endpush
