<div>
    <div class="d-flex justify-content-end">
        <a data-bs-toggle="modal" data-bs-target="#createChatModal"
           style="padding: 10px 16px 10px 16px; border-radius: 7px;"
           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">Create Chatbot
        </a>
    </div>

    <!-- Chatbot Cards Container -->
    <div id="chatbotCardsContainer" class="mt-3 row p-3">
        <!-- Example Card -->
        @foreach($chats as $chat)
            <div class="mt-3 col-md-6 col-sm-12 col-lg-6 " style="padding-right: 5px;">
                <div class="card card-body" style="background-color: #FFFFFF !important;border: 2px solid #d26622;">
                    <div class="d-flex flex-column gap-3 chat-card">
                        <a href="{{route('admin_hai_chat_detail', $chat['name'])}}">
                            <h5 style="color: #f2661c" class="text-decoration-none"><i
                                    class="bi bi-robot"></i> {{ $chat['name'] }}
                            </h5>
                        </a>
                        @if(strlen($chat['description']) > 60)

                            <p class="card-text" style="color: black">{{ substr($chat['description'], 0, 60) }}  <span wire:click="showModalChatBotDetail({{$chat['id']}})" data-toggle="modal" data-target="#chatBotDetailModal" style="color: #f2661c; cursor: pointer;"><b>read more...</b></span></p>

                        @else
                            <p class="card-text " style="color: black">{{ $chat['description'] }}</p>
                        @endif
                        <div class="d-flex justify-content-between">
                            <p class="text-dark" style="padding-right: 8px; color: black"><i class="bi bi-clock text-white"></i> less
                                than a minute</p>
                            <div class="d-flex gap-2">
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-copy"></i></button>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteChatBot({{ $chat['id'] }})"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">
                                    <i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
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
                        <h5 class="modal-title text-white" id="createChatModalLabel">Create New Chat</h5>
                        <button type="button" class="close modal-close-btn " id="createChatModal"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @include('layouts.message')
                    <form wire:submit.prevent="submitForm">
                        <div class="pt-0">
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label class="form-label text-white">Chatbot Name</label>
                                    <div class="form-group">
                                        <input style="background-color: #0f1534;color: lightgrey !important"
                                               class="form-control text-white"
                                               type="text" name="limit"
                                               wire:model="name">
                                        @error('name')
                                        <span class="text-sm text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label text-white">Chatbot description</label>
                                    <div class="form-group">
                                            <textarea style="background-color: #0f1534;" class="form-control text-white"
                                                      rows="5" cols="5"
                                                      name="description"
                                                      wire:model="description"></textarea>
                                        @error('information')
                                        <span class="text-sm text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                    style="background-color: #f2661c ">create chatbot
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
                        <button type="button" class="close modal-close-btn"
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

</div>
@push('js')

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
                html: "<span style='color: white;'>Want to delete Chat bot</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteChatbot', id)
                }
            })
        }
    </script>

@endpush
