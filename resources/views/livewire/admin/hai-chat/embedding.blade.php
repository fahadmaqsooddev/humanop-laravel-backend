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
    </style>

@endpush
<div>

    <div class="d-flex justify-content-end">
        <a data-bs-toggle="modal" data-bs-target="#createEmbedding"
           style="padding: 10px 16px 10px 16px; border-radius: 7px;"
           class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">Create Embedding
        </a>
    </div>

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
                        <div class="d-flex justify-content-end">
{{--                            <p style="padding-right: 8px; color: black"><i class="bi bi-clock text-white"></i> less--}}
{{--                                than a minute</p>--}}
                            <div class="d-flex gap-2">
{{--                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
{{--                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">--}}
{{--                                    <i class="fa-solid fa-copy"></i></button>--}}
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteEmbedding({{ $embedding['embedding']['id'] ?? null }})"
                                        class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">
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
                <div class="modal-body" style="border-radius: 9px">
                    <form wire:submit.prevent="createEmbedding" enctype="multipart/form-data">
                        <div class="card-body w-100">
                            <div class="row w-100">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Create Embedding</label>
                                    <button type="button" class="close modal-close-btn new-orange-button" data-bs-dismiss="modal"
                                            aria-label="Close" id="embedding-close-modal-button"
                                            style="padding: 1px 10px 1px 10px;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{--                                    Alert messages--}}

                                    @if(session('embedding_errors'))
                                        <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                                            <ul class="alert-text text-white mb-0">
                                                @foreach(session('embedding_errors') as $err)
                                                    <li>{{ $err[0] }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif
                                    @if(session('embedding_success'))
                                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('embedding_success') }}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif
                                    @if(session('embedding_error'))
                                        <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('embedding_error')}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif

                                    {{--                                    End Alert error--}}
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Name</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="embedding_name" placeholder="Enter Embedding Name" type="text">
                                    </div>

                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Embedding (TXT,PDF)</label>
                                        <input style="background-color: #0f1534;" wire:model="embedding" id="embedding_file{{$fileInputId}}"
                                               class="form-control text-white" type="file"
                                               accept="file/*">
                                        <span wire:loading.flex wire:target="embedding">
                                            Uploading ...
                                        </span>
                                    </div>

{{--                                    <div class="form-group mt-4">--}}
{{--                                        <label class="form-label fs-4 text-white">Groups</label>--}}
{{--                                        <select wire:model.defer="group_ids" class="form-control" id="select2" multiple style="background-color: #0f1534; color: white;">--}}
{{--                                            <option value="">Select Group</option>--}}
{{--                                            @foreach($groups as $group)--}}
{{--                                                <option value="{{$group->id}}">{{$group->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn-sm-2 new-orange-button btn-sm float-end text-white mt-4 mb-0">Create
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

@push('javascript')

    <script>

        window.livewire.on('closeCreateEmbeddingModal', function (){
            setTimeout(function (){
                $('#embedding-close-modal-button').click();
            }, 1000);
        });

        window.livewire.on('closeAlert', function (){
            setTimeout(function (){
                $('.alert').alert('close');
            }, 5000);
        });

    </script>

@endpush
