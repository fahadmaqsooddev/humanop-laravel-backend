{{--@section('content')--}}
{{--    @push('css')--}}
{{--        <style>--}}

{{--            .select2{--}}
{{--                width: 625px !important;--}}
{{--            }--}}

{{--            .select2-selection{--}}
{{--                width: 625px !important;--}}
{{--                border: 1px solid #f2661c !important;--}}
{{--                background-color: #0f1534 !important;--}}
{{--                color: white !important;--}}
{{--                border-radius: 10px;--}}
{{--            }--}}

{{--            .select2-selection__choice{--}}
{{--                color: black;--}}
{{--            }--}}

{{--        </style>--}}
{{--    @endpush--}}
<div>
    <div class="d-flex justify-content-end">
        <a data-bs-toggle="modal" data-bs-target="#createEmbedding"
           style="padding: 10px 16px 10px 16px; border-radius: 7px;"
           class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">Create Embedding
        </a>
        <div class="px-3">
            <a data-bs-toggle="modal" data-bs-target="#createGroup"
               style="padding: 10px 16px 10px 16px; border-radius: 7px;"
               class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">Create Group
            </a>
        </div>
    </div>

    <!-- Chatbot Cards Container -->
    <div id="chatbotCardsContainer" class="mt-3 row p-3">
        <!-- Example Card -->
        @foreach($groups as $group)
            <div class="mt-3 col-md-3 col-sm-3 col-lg-3" style="padding-right: 5px;">
                <div class="card card-body" style="background-color: #FFFFFF !important;border: 2px solid #d26622;">
                    <div class="d-flex flex-column gap-3 chat-card" style="width: 100%">
                        <div class="d-flex flex-row">
                            <div class="col-12 text-center">
                                <a href="{{route('admin_embedding', $group['id'])}}">
                                    <h3 style="color: #f2661c" class="text-decoration-none w-100"><i
                                            class="bi bi-robot"></i> {{ $group['name'] }}
                                    </h3>
                                </a>
                            </div>
                        </div>
{{--                        <div class="d-flex justify-content-between">--}}
{{--                            <div class="d-flex gap-2">--}}
{{--                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteEmbedding({{ $embedding['id'] }})"--}}
{{--                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn">--}}
{{--                                    <i class="fa-solid fa-trash"></i></button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
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
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Name</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="embedding_name" placeholder="Enter Embedding Name" type="text">
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

                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Group </label>
                                        <select wire:model.defer="group_ids" class="form-control" id="select2" multiple>
                                            <option value="">Select Group</option>
                                            @foreach($groups as $group)
                                                <option value="{{$group->id}}">{{$group->name}}</option>
                                            @endforeach
                                        </select>
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


    <div wire:ignore.self class="modal fade" id="createGroup" tabindex="-1" role="dialog"
         aria-labelledby="createResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="createGroup">
                        <div class="card-body w-100">
                            <div class="row w-100">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Create Group</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Name</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="name" placeholder="Enter group name" type="text">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">
                            Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('javascript')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

{{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>--}}
{{--    <script src="//cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js"></script>--}}
{{--    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}

    <script>

        window.livewire.on('closeCreateGroupModal', function (){
            setTimeout(function (){
                $('#createGroup').modal('hide');
            }, 1000);
        });

        // $(document).ready(function () {
        //
        //     $('#select2').select2({
        //         placeholder : 'Select Group'
        //     });
        //
        //     $('.select2-selection__rendered').addAttribute('wire:model="group_ids"');
        //
        // });

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

{{--@endsection--}}
