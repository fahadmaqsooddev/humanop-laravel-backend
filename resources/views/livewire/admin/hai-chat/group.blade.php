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

        .dropdown-search::placeholder{
            color: lightslategray !important;
        }

        .multi-select-button{
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #f2661c;
            background-color: #F3DEBA;
            color: black;
        }
    </style>
@endpush
<div>
    <div class="d-flex justify-content-end">
        <div class="px-1">
            <a data-bs-toggle="modal" data-bs-target="#createEmbedding"
               {{--           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
               class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">Create Embedding
            </a>
        </div>
        <div class="px-1">
            <a data-bs-toggle="modal" data-bs-target="#createGroup"
{{--               style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
           class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">Create Group
            </a>
        </div>
    </div>

    <div class="row">

        <div class="col-12 col-md-6 nav-tab  ">

            <div class="nav nav-tabs border-0 " id="myTab" role="tablist" style="max-width: fit-content;">
                <div class="nav-item connectionDev" role="presentation">
                    <button class="connectionBtn new-orange-button me-2 mt-2 mt-md-0 updateBtn {{(session('embedding_deleted') ? '' : 'active')}}" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane"
                            wire:click="changeIsGroup(true)"
                            aria-selected="true">Groups</button>
                </div>

                <div class="nav-item connectionDev" role="presentation">
                    <button class="connectionBtn new-orange-button mt-2 mt-md-0 updateBtn {{(session('embedding_deleted') ? 'active' : '')}}" id="profile-tab" data-bs-toggle="tab"
                            data-bs-target="#profile-tab-pane" type="button" role="tab"
                            wire:click="changeIsGroup(false)"
                            aria-controls="profile-tab-pane" aria-selected="false">Embeddings</button>
                </div>
            </div>
        </div>

    </div>

    <div class="row ">

        <div class="col-12 mx-auto">

            @if(session('embedding_deleted'))
                <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('embedding_deleted') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fa fa-close" aria-hidden="true"></i>
                    </button>
                </div>
            @endif

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade pt-3 {{ (!$is_group || session('embedding_deleted') ? '' : 'show active') }}" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                    @empty($groups[0])
                        <p class="text-white">No group found</p>
                    @endempty

                    <div class="row pt-2">

                        @foreach($groups as $group)
                            <div class="mt-3 col-md-3 col-sm-3 col-lg-3" style="padding-right: 5px;">
                                <div class="card card-body" style="background-color: #F3DEBA !important;border: 2px solid #d26622;">
                                    <div class="d-flex flex-column gap-3 chat-card" style="width: 100%">
                                        <div class="d-flex flex-row">
                                            <div class="col-12 text-center">
                                                <a href="{{route('admin_embedding', $group['id'])}}">
                                                    <h4 style="color: #f2661c" class="text-decoration-none w-100"><i
                                                            class="bi bi-robot"></i> {{ $group['name'] }}
                                                    </h4>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
                <div class="tab-pane fade pt-3 {{(!$is_group || session('embedding_deleted') ? 'show active' : '')}}" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                    @empty($embeddings[0])
                        <p style="color: #f2661c;font-size: 20px; font-weight: bold">No embedding found</p>
                    @endempty

                    <div class="row">

                        @foreach($embeddings as $embedding)
                            <div class="mt-3 col-md-6 col-sm-6 col-lg-6" style="padding-right: 5px;">
                                <div class="card card-body" style="background-color: #F3DEBA !important;border: 2px solid #d26622;">
                                    <div class="d-flex flex-column gap-3 chat-card" style="width: 100%">
                                        <div class="d-flex flex-row">
                                            <div class="col-12">
                                                <a href="{{route('admin_embedding_detail', $embedding['name'])}}">
                                                    <h5 style="color: #f2661c" class="text-decoration-none w-100"><i
                                                            class="bi bi-robot"></i> {{ $embedding['name'] }}
                                                    </h5>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="d-flex">

                                                <div class="col-11 mt-3">
                                                    <a wire:click="setEmbeddingId({{$embedding->id}})" data-bs-toggle="modal" data-bs-target="#addEmbeddingToGroups"
                                                        class="btn-sm new-orange-button">
                                                        Add to groups
                                                    </a>
                                                </div>

                                                <div class="col-1">
                                                    <div class="gap-2">
                                                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteEmbedding({{ $embedding['id'] }})"
                                                                class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">
                                                            <i class="fa-solid fa-trash"></i></button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>

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
                                            aria-label="Close" id="embedding-close-modal-button" style="padding: 1px 10px 1px 10px;">
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

                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Groups</label>
                                        <div class="dropdown w-100">
                                            <button class="dropdown-toggle multi-select-button {{($showGroupDropdownMenu ? 'show' : '')}}"
                                                    style="background-color: #0f1534; color: white;{{(count($selectedGroups) > 0 ? 'text-align:left;' : '')}}" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                @if(count($selectedGroups) > 0)
                                                    @foreach($selectedGroups as $selectedGroup)
                                                        <span class="badge" style="background-color: #f2661c; color: white; border: 1px solid #f2661c;">
                                                            {{$selectedGroup}}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    Select Group
                                                @endif
                                            </button>
                                            <div class="dropdown-menu w-100 {{$showGroupDropdownMenu ? 'show' : ''}}" aria-labelledby="dropdownMenuButton1">

                                                <div style="padding: 10px;">

                                                    <div style="padding-bottom: 5px;">
                                                        <input type="text" placeholder="search group" class="dropdown-search" wire:model="group_search" style="border-radius: 1px; border: 1px solid #f2661c; width: 100%;">
                                                    </div>

                                                    @if(count($dropDownGroups) > 0)

                                                        <div style="max-height: 100px; overflow-y: scroll;padding-top: 5px;" id="group_dropdown_scroll">

                                                            <ul style="list-style: none; padding-top: 5px; padding-left: inherit;cursor: default;">
                                                                @foreach($dropDownGroups as $key => $group)
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" wire:click="addGroupIds('{{$group['id']}}','{{$group['name']}}')" type="checkbox" id="checkbox{{$key}}" {{in_array($group->id, $group_ids) ? 'checked' : ''}}>
                                                                            <label class="form-check-label" for="checkbox{{$key}}" style="cursor: default;">
                                                                                {{$group->name}}
                                                                            </label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>

                                                        </div>
                                                    @else
                                                        <div class="text-center">
                                                            <span>No groups</span>
                                                        </div>
                                                    @endif

                                                </div>

                                            </div>
                                        </div>
                                    </div>
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
                                    <button type="button" class="close modal-close-btn new-orange-button" data-bs-dismiss="modal"
                                            aria-label="Close" id="group-close-modal-button" style="padding: 1px 10px 1px 10px;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Name</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="name" placeholder="Enter group name" type="text">
                                    </div>

                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Embeddings </label>
{{--                                        <select wire:model.defer="embedding_ids" class="form-control" id="select2" multiple style="background-color: #0f1534; color: white;">--}}
{{--                                            <option value="">Select Embeddings</option>--}}
{{--                                            @foreach($embeddings as $embedding)--}}
{{--                                                <option value="{{$embedding->id}}">{{$embedding->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}

                                        <div class="dropdown w-100">

                                            <button class="dropdown-toggle multi-select-button  {{$showEmbDropdownMenu ? 'show' : ''}}"
                                                    style="background-color: #0f1534; color: white; {{(count($selectedEmbeddings) > 0 ? 'text-align:left;' : '')}}" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                @if(count($selectedEmbeddings) > 0)
                                                    @foreach($selectedEmbeddings as $selectedEmbedding)
                                                        <span class="badge" style="background-color: #f2661c; color: white; border: 1px solid #f2661c;">
                                                            {{$selectedEmbedding}}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    Select Embedding
                                                @endif
                                            </button>


{{--                                            <button class="dropdown-toggle form-control" style="background-color: #0f1534; color: white;" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">--}}
{{--                                                Select Embedding--}}
{{--                                            </button>--}}
                                            <div class="dropdown-menu w-100 {{$showEmbDropdownMenu ? 'show' : ''}}" aria-labelledby="dropdownMenuButton1">

                                                <div style="padding: 10px;">

                                                    <div style="padding-bottom: 5px;">
                                                        <input type="text" placeholder="search embedding" class="dropdown-search" wire:model="embedding_search" style="border-radius: 1px; border: 1px solid #f2661c; width: 100%;">
                                                    </div>

                                                    @if(count($dropDownEmbeddings) > 0)

                                                        <div style="max-height: 100px; overflow-y: scroll;padding-top: 5px;" id="group_dropdown_scroll">

                                                            <ul style="list-style: none; padding-top: 5px; padding-left: inherit;cursor: default;">
                                                                @foreach($dropDownEmbeddings as $key => $embedding)
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" wire:click="addEmbeddingIds('{{$embedding['id']}}','{{$embedding['name']}}')" type="checkbox" id="checkbox{{$key}}" {{in_array($embedding->id, $embedding_ids) ? 'checked' : ''}}>
                                                                            <label class="form-check-label" for="checkbox{{$key}}" style="cursor: default;">
                                                                                {{$embedding->name}}
                                                                            </label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>

                                                        </div>
                                                    @else
                                                        <div class="text-center">
                                                            <span>No embeddings</span>
                                                        </div>
                                                    @endif

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn-sm-2 new-orange-button btn-sm float-end text-white mt-4 mb-0">
                            Create
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="addEmbeddingToGroups" tabindex="-1" role="dialog"
         aria-labelledby="addEmbeddingToGroups" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="addEmbeddingToGroups">
                        <div class="card-body w-100">
                            <div class="row w-100">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Add embedding into groups</label>
                                    <button type="button" class="close modal-close-btn new-orange-button" data-bs-dismiss="modal"
                                            aria-label="Close" id="embedding-group-close-modal-button"
                                            style="padding: 1px 10px 1px 10px;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
{{--                                    Alert messages--}}

                                    @if(session('embedding_group_errors'))
                                        <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                                            <ul class="alert-text text-white mb-0">
                                                @foreach(session('embedding_group_errors') as $err)
                                                    <li>{{ $err[0] }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif
                                    @if(session('embedding_group_success'))
                                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('embedding_group_success') }}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif
                                    @if(session('embedding_group_error'))
                                        <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('embedding_group_error')}}</span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                                <i class="fa fa-close" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @endif

                                    {{--                                    End Alert error--}}

                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Groups</label>
{{--                                        <select wire:model.defer="group_ids" class="form-control" id="select2" multiple style="background-color: #0f1534; color: white;">--}}
{{--                                            <option value="">Select Group</option>--}}
{{--                                            @foreach($groups as $group)--}}
{{--                                                <option value="{{$group->id}}">{{$group->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}

                                        <div class="dropdown w-100">
                                            <button class="dropdown-toggle multi-select-button {{($showGroupDropdownMenu ? 'show' : '')}}"
                                                    style="background-color: #0f1534; color: white; {{(count($selectedGroups) > 0 ? 'text-align:left;' : '')}}" type="button"
                                                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                @if(count($selectedGroups) > 0)
                                                    @foreach($selectedGroups as $selectedGroup)
                                                        <span class="badge" style="background-color: #f2661c; color: white; border: 1px solid #f2661c;">
                                                            {{$selectedGroup}}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    Select Group
                                                @endif
                                            </button>
                                            <div class="dropdown-menu w-100 {{$showGroupDropdownMenu ? 'show' : ''}}" aria-labelledby="dropdownMenuButton3">

                                                <div style="padding: 10px;">

                                                    <div style="padding-bottom: 5px;">
                                                        <input type="text" placeholder="search group" class="dropdown-search" wire:model="group_search" style="border-radius: 1px; border: 1px solid #f2661c; width: 100%;">
                                                    </div>

                                                    @if(count($dropDownGroups) > 0)

                                                        <div style="max-height: 100px; overflow-y: scroll;padding-top: 5px;" id="group_dropdown_scroll">

                                                            <ul style="list-style: none; padding-top: 5px; padding-left: inherit;cursor: default;">
                                                                @foreach($dropDownGroups as $key => $group)
                                                                    <li>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" wire:click="addGroupIds('{{$group['id']}}', '{{$group['name']}}')" type="checkbox" id="checkbox{{$key}}" {{in_array($group->id, $group_ids) ? 'checked' : ''}}>
                                                                            <label class="form-check-label" for="checkbox{{$key}}" style="cursor: default;">
                                                                                {{$group->name}}
                                                                            </label>
                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>

                                                        </div>
                                                    @else
                                                        <div class="text-center">
                                                            <span>No groups</span>
                                                        </div>
                                                    @endif

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <button type="submit" class="btn-sm-2 new-orange-button btn-sm float-end text-white mt-4 mb-0">
                            Add
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
                $('#group-close-modal-button').click();
            }, 1000);
        });

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

        window.livewire.on('closeAddGroupToEmbeddingModal', function (){
            setTimeout(function (){
                $('#embedding-group-close-modal-button').click();
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
