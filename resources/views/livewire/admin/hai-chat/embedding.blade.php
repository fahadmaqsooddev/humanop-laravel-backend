<div>

    <!-- Chatbot Cards Container -->
    <div id="chatbotCardsContainer" class="mt-3 row p-3">

        @if(count($embeddings) == 0)
            <p style="color: #f2661c;">No embedding is linked with this Group</p>
        @endif

        <!-- Example Card -->
        @foreach($embeddings as $embedding)
            <div class="mt-3 col-md-6 col-sm-12 col-lg-6 " style="padding-right: 5px;">
                <div class="card card-body" style="background-color: #F3DEBA !important;border: 2px solid #d26622;">
                    <div class="d-flex flex-column gap-3 chat-card" style="width: 100%">
                        <div class="d-flex flex-row">
                            <div class="col-12">
                                <a href="{{route('admin_embedding_detail', $embedding['embedding']['name'] ?? null)}}">
                                    <h5 style="color: #f2661c" class="text-decoration-none w-100"><i
                                            class="bi bi-robot"></i> {{ $embedding['embedding']['name'] ?? null }}
                                    </h5>
                                    {{--                                                        <p class="card-text text-white">{{ $chat['description'] }}</p>--}}
                                </a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p style="padding-right: 8px; color: black"><i class="bi bi-clock text-white"></i> less
                                than a minute</p>
                            <div class="d-flex gap-2">
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">
                                    <i class="fa-solid fa-copy"></i></button>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteEmbedding({{ $embedding['embedding']['id'] ?? null }})"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">
                                    <i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
