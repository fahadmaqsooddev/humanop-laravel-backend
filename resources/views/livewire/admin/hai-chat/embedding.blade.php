<div>
    <div class="d-flex justify-content-end">
        <a data-bs-toggle="modal" data-bs-target="#createEmbedding"
           style="padding: 10px 16px 10px 16px; border-radius: 7px;"
           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">Create Embedding
        </a>
    </div>

    <!-- Chatbot Cards Container -->
    <div id="chatbotCardsContainer" class="mt-3 row p-3">
        <!-- Example Card -->
        @foreach($embeddings as $embedding)
            <div class="card mt-3 col-md-6 col-sm-12 col-lg-6 " style="padding-right: 5px;">
                <div class="card-body" >
                    <div class="d-flex flex-column gap-3 chat-card">
                        <a href="{{route('admin_hai_chat_detail', $embedding['name'])}}">
                            <h5 style="color: #f2661c" class="text-decoration-none"><i
                                    class="bi bi-robot"></i> {{ $embedding['name'] }}
                            </h5>
{{--                            <p class="card-text text-white">{{ $chat['description'] }}</p>--}}
                        </a>
                        <div class="d-flex justify-content-between">
                            <p class="text-white" style="padding-right: 8px"><i class="bi bi-clock text-white"></i> less
                                than a minute</p>
                            <div class="d-flex gap-2">
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-copy"></i></button>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteEmbedding({{ $embedding['id'] }})"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">
                                    <i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- Create Embedding Models--}}
    <div wire:ignore.self class="modal fade" id="createEmbedding" tabindex="-1" role="dialog"
         aria-labelledby="createResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="createEmbedding" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body w-100">
                            <div class="row w-100">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Create Embeding</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Name</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="name" placeholder="Enter Embedding Name" type="text">
                                    </div>

                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Embedding (TXT,PDF)</label>
                                        <input style="background-color: #0f1534;" wire:model.defer="embedding" id="embedding_file"
                                               class="form-control text-white" type="file"
                                               accept="file/*">
                                        <span wire:loading.flex wire:target="embedding">
                                            Uploading ...
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>
        window.Livewire.on('closeModel', function () {
            $('#createChatModal').modal('hide');
        });

        function deleteEmbedding(id) {

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
                html: "<span style='color: white;'>Want to delete Embedding</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteEmbedding', id)
                }
            })
        }
    </script>

@endpush
