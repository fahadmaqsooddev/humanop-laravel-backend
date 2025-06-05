@push('css')

    <style>
        .new-orange-button{
            background-color: #1b3a62 !important;
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

        input::placeholder{
            color: black !important;
        }

        textarea::placeholder{
            color: black; !important;
        }
    </style>

@endpush
<div>

    <div class="py-2">
        <div id="chat_switch_loader">

            <div wire:loading.flex wire:target="editEmbedding" class="spinner-border custom-text-dark" id="chat_switch_spinner" role="status"
                 style="width: 30px; height: 30px;top: 50%; left: 50%;position: absolute;">
                <span class="sr-only">
                    Loading...
                </span>
            </div>
        <div class="input-group d-flex justify-content-between">
            <h3 style="color: #1C365E;font-weight: 700;font-size: 28px;line-height: 16.5px;vertical-align: middle;text-transform: capitalize;">
                Embedding
            </h3>

            <div>

                <button class="m-1"
                        data-bs-toggle="modal"
                        data-bs-target="#addEmbeddingModel"
                        style="background:#1b3a62;color:white;border-radius: 24px;border: 2px; font-weight: 400;padding: 5px 10px 5px 10px;">
                    <img src="{{asset('assets/img/icons/Add.svg')}}" width="20">
                    Add Embedding
                </button>
            </div>
        </div>
        </div>
    </div>

    <div class="table-responsive w-100 pt-4" style="border-radius: 10px; background-color: #F6BA81;">
        @if(count($embeddings) > 0)
            <table class="table table-flush">
                <thead class="thead-light">
                <tr class="table-text-color" style="color: #1C365E;font-size: 18px;">
                    <th style="font-weight: 700; padding: 12px 12px;">Name</th>
{{--                    <th style="font-weight: 700; padding: 12px;">Answer</th>--}}
{{--                    <th style="font-weight: 700; text-align: center;">Fine Tuned</th>--}}
                    <th style="font-weight: 700; text-align: left;">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($embeddings as $index => $embedding)
                    <tr class="table-text-color" style="color: #1C365E;font-size: 15px;font-weight: 600;">
                        <td class="text-md" style="padding-left: 15px !important;">
                            {{$embedding['embedding']['name']}}
                        </td>
                        <td style="padding: 12px 24px;">
                            <button class="mb-0 text-white"
                                    wire:click="editEmbedding({{$embedding['embedding']['id'] ?? null}})"
                                    style="background-color: transparent;border:none;">
                                <i class="fa fa-pencil" style="color: #1C365E; font-weight: 600;"></i>
                            </button>
                            <button class="mb-0 text-white" onclick="deleteEmbedding({{$embedding['embedding']['id']}})"
                                    style="background-color: transparent;border:none;">
                                <i class="fa fa-trash" style="color: #1C365E; font-weight: 600;"></i>
                            </button>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>

        @else
            <div class="text-center p-5">

                <span class="custom-text-dark">
                    No records found. Add a question and answer, and they will appear here!
                </span>

            </div>
        @endif

{{--        {{$contents->links('pagination.table-pagination')}}--}}

    </div>
    <div wire:ignore.self class="modal fade" id="addEmbeddingModel" tabindex="-1"
         role="dialog"
         aria-labelledby="addEmbeddingModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: #1C365E !important; border-radius: 32px;">
                <div class="modal-body" style="padding: 25px 30px;">

                    <div class="d-flex justify-content-between">
                        <h5 style="font-weight: 800;font-size: 28px;line-height: 100%;color: white;">
                            Add Embedding
                        </h5>
                        <div>
                            <a type="button" data-bs-dismiss="modal"
                               aria-label="Close" id="add-embedding-close-modal-button">
                                <img src="{{asset('assets/img/icons/cross-white.svg')}}" width="25">
                            </a>
                        </div>
                    </div>

                    <div class="py-1">
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
                        <form wire:submit.prevent="createEmbedding" enctype="multipart/form-data">

                            <div class="py-3">
                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">
                                    Name
                                </label>
                                <input type="text"
                                       wire:model.defer="embedding_name"
                                       placeholder="Enter embedding name"
                                       style="padding:10px;border-radius: 40px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">
                            </div>

                            <div class="py-3">
                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">
                                    Embedding
                                </label>
                                <input type="file"
                                       wire:model="embedding"
                                       id="embedding_file{{$fileInputId}}"
                                       accept="file/text"
                                       style="padding:10px;border-radius: 40px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">
                                        <span wire:loading.flex wire:target="embedding">
                                            Uploading ...
                                        </span>
                                        <span class="d-block">
                                            Only text files are supported.
                                        </span>

                            </div>

{{--                            <div class="py-3">--}}
{{--                                <input type="checkbox"--}}
{{--                                       wire:model.defer="is_upload_production"--}}
{{--                                       style="background: #F4ECE0;">--}}
{{--                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white;">--}}
{{--                                    Want to upload on production.--}}
{{--                                </label>--}}
{{--                            </div>--}}

                            <div class="py-4 d-flex justify-content-center">
                                <button class="m-1"
                                        style="background:#1b3a62;color:white;border-radius: 24px;border: 2px; font-weight: 600;padding: 5px 15px 5px 15px;">
                                    <img src="{{asset('assets/img/icons/Add.svg')}}" width="20">
                                    Add
                                </button>
                            </div>

                        </form>
                    </div>

{{--                    <div class="card-body pt-0">--}}
{{--                        <label class="form-label fs-4 text-white">Add content for Fine-tuning</label>--}}

{{--                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"--}}
{{--                                aria-label="Close" id="close-add-modal-button">--}}
{{--                            <span aria-hidden="true">&times;</span>--}}
{{--                        </button>--}}
{{--                        @include('layouts.message')--}}
{{--                        <form wire:submit.prevent="addFineTuneContent">--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="row mt-4">--}}
{{--                                    <div class="col-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class="p-1">--}}
{{--                                                <label class="text-white">Question</label>--}}
{{--                                                <input style="background-color: #eaf3ff;color: #1b3a62 !important"--}}
{{--                                                       class="form-control"--}}
{{--                                                       type="text" wire:model="question" placeholder="Enter question">--}}
{{--                                            </div>--}}

{{--                                            <div class="p-1">--}}
{{--                                                <label for="textarea" class="text-white">Answer</label>--}}
{{--                                                <textarea id="textarea" rows="3" style="background-color: #eaf3ff;color: #1b3a62 !important"--}}
{{--                                                          class="form-control"--}}
{{--                                                          type="text" wire:model="answer" placeholder="Enter question's answer">--}}
{{--                                                </textarea>--}}
{{--                                            </div>--}}
{{--                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"--}}
{{--                                                    style="background-color: #1b3a62 ">Add--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editEmbeddingModel" tabindex="-1"
         role="dialog"
         aria-labelledby="editEmbeddingModel" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: #1C365E !important; border-radius: 32px;">
                <div class="modal-body" style="padding: 25px 30px;">

                    <div class="d-flex justify-content-between">
                        <h5 style="font-weight: 800;font-size: 28px;line-height: 100%;color: white;">
                            Edit Embedding
                        </h5>
                        <div>
                            <a type="button" data-bs-dismiss="modal"
                               aria-label="Close" id="edit-embedding-close-modal-button">
                                <img src="{{asset('assets/img/icons/cross-white.svg')}}" width="25">
                            </a>
                        </div>
                    </div>

                    <div class="py-1">
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
                        <form wire:submit.prevent="updateEmbedding" enctype="multipart/form-data">

                            <div class="py-3">
                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">
                                    Name
                                </label>
                                <input type="text"
                                       wire:model.defer="updateEmbeddingName"
                                       placeholder="Enter embedding name"
                                       style="padding:10px;border-radius: 40px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">
                            </div>

                            <div class="py-3">
                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">
                                    Embedding
                                </label>
                                <textarea
                                    placeholder="Enter embedding text"
                                       wire:model="updateEmbeddingText"
                                       rows="5"
                                       style="padding:15px;border-radius: 10px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">
                                </textarea>
                            </div>

                            <div class="py-4 d-flex justify-content-center">
                                <button class="m-1"
                                        style="background:#1b3a62;color:white;border-radius: 24px;border: 2px; font-weight: 600;padding: 5px 15px 5px 15px;">
                                    <img src="{{asset('assets/img/icons/Add.svg')}}" width="20">
                                    Update
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style=" border-radius: 9px">--}}
{{--                    <div class="card-body pt-0">--}}
{{--                        <label class="form-label fs-4 text-white">Edit content for Fine-tuning</label>--}}

{{--                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"--}}
{{--                                aria-label="Close" id="close-edit-modal-button">--}}
{{--                            <span aria-hidden="true">&times;</span>--}}
{{--                        </button>--}}

{{--                        @if(session('embedding_errors'))--}}
{{--                            <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">--}}
{{--                                <ul class="alert-text text-white mb-0">--}}
{{--                                    @foreach(session('embedding_errors') as $err)--}}
{{--                                        <li>{{ $err[0] }}</li>--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                    <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if(session('embedding_success'))--}}
{{--                            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">--}}
{{--                        <span class="alert-text text-white">--}}
{{--                            {{ session('embedding_success') }}</span>--}}
{{--                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                    <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if(session('embedding_error'))--}}
{{--                            <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">--}}
{{--                        <span class="alert-text text-white">--}}
{{--                            {{session('embedding_error')}}</span>--}}
{{--                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                    <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <form wire:submit.prevent="editFineTuneContent">--}}

{{--                            <div class="card-body">--}}
{{--                                <div class="row mt-4">--}}
{{--                                    <div class="col-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class="p-1">--}}
{{--                                                <label class="text-white">Question</label>--}}
{{--                                                <input style="background-color: #eaf3ff;color: #1b3a62 !important"--}}
{{--                                                       class="form-control"--}}
{{--                                                       type="text" wire:model="updateEmbeddingName" placeholder="Enter question">--}}
{{--                                            </div>--}}

{{--                                            <div class="p-1">--}}
{{--                                                <label for="textarea" class="text-white">Answer</label>--}}
{{--                                                <textarea id="textarea" rows="3" style="background-color: #eaf3ff;color: #1b3a62 !important"--}}
{{--                                                          class="form-control"--}}
{{--                                                          type="text" wire:model="updateEmbeddingText" placeholder="Enter question's answer">--}}
{{--                                                </textarea>--}}
{{--                                            </div>--}}
{{--                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"--}}
{{--                                                    style="background-color: #1b3a62 ">Update--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>

{{--    <div class="d-flex justify-content-end">--}}
{{--        <a data-bs-toggle="modal" data-bs-target="#createEmbedding"--}}
{{--           style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
{{--           class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">Create Embedding--}}
{{--        </a>--}}
{{--    </div>--}}

{{--    <!-- Chatbot Cards Container -->--}}
{{--    <div id="chatbotCardsContainer" class="mt-3 row p-3">--}}

{{--        @if(count($embeddings) == 0)--}}
{{--            <p style="color: #1b3a62;">No embedding is linked with this Group</p>--}}
{{--        @endif--}}

{{--        <!-- Example Card -->--}}
{{--        @foreach($embeddings as $embedding)--}}
{{--            <div class="mt-3 col-md-6 col-sm-12 col-lg-6 " style="padding-right: 5px;">--}}
{{--                <div class="card card-body" style="background-color: #F3DEBA !important;border: 2px solid #1b3a62;">--}}
{{--                    <div class="d-flex flex-column gap-3 chat-card" style="width: 100%">--}}
{{--                        <div class="d-flex flex-row">--}}
{{--                            <div class="col-12">--}}
{{--                                <a href="{{route('admin_embedding_detail', $embedding['embedding']['name'] ?? null)}}">--}}
{{--                                    <h5 style="color: #1b3a62" class="text-decoration-none w-100"><i--}}
{{--                                            class="bi bi-robot"></i> {{ $embedding['embedding']['name'] ?? null }}--}}
{{--                                    </h5>--}}
{{--                                    --}}{{--                                                        <p class="card-text text-white">{{ $chat['description'] }}</p>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="d-flex justify-content-end">--}}
{{--                            <p style="padding-right: 8px; color: black"><i class="bi bi-clock text-white"></i> less--}}
{{--                                than a minute</p>--}}
{{--                            <div class="d-flex gap-2">--}}
{{--                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"--}}
{{--                                        class="btn-sm-2 btn-md-3 btn-lg-5 rainbow-border-user-nav-btn navButtonResponsive">--}}
{{--                                    <i class="fa-solid fa-copy"></i></button>--}}
{{--                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" onclick="deleteEmbedding({{ $embedding['embedding']['id'] ?? null }})"--}}
{{--                                        class="btn-sm-2 btn-md-3 btn-lg-5 new-orange-button">--}}
{{--                                    <i class="fa-solid fa-trash"></i></button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}
{{--    </div>--}}

{{--    --}}{{-- Create Embedding Models--}}
{{--    <div wire:ignore.self class="modal fade" id="createEmbedding" tabindex="-1" role="dialog"--}}
{{--         aria-labelledby="createResource" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body" style="border-radius: 9px">--}}
{{--                    <form wire:submit.prevent="createEmbedding" enctype="multipart/form-data">--}}
{{--                        <div class="card-body w-100">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-12">--}}
{{--                                    <label class="form-label fs-4 text-white">Create Embedding</label>--}}
{{--                                    <button type="button" class="close modal-close-btn new-orange-button" data-bs-dismiss="modal"--}}
{{--                                            aria-label="Close" id="embedding-close-modal-button"--}}
{{--                                            style="padding: 1px 10px 1px 10px;">--}}
{{--                                        <span aria-hidden="true">&times;</span>--}}
{{--                                    </button>--}}
{{--                                    --}}{{--                                    Alert messages--}}

{{--                                    @if(session('embedding_errors'))--}}
{{--                                        <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">--}}
{{--                                            <ul class="alert-text text-white mb-0">--}}
{{--                                                @foreach(session('embedding_errors') as $err)--}}
{{--                                                    <li>{{ $err[0] }}</li>--}}
{{--                                                @endforeach--}}
{{--                                            </ul>--}}
{{--                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                                <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                    @if(session('embedding_success'))--}}
{{--                                        <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">--}}
{{--                        <span class="alert-text text-white">--}}
{{--                            {{ session('embedding_success') }}</span>--}}
{{--                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                                <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                    @if(session('embedding_error'))--}}
{{--                                        <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">--}}
{{--                        <span class="alert-text text-white">--}}
{{--                            {{session('embedding_error')}}</span>--}}
{{--                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">--}}
{{--                                                <i class="fa fa-close" aria-hidden="true"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}

{{--                                    --}}{{--                                    End Alert error--}}
{{--                                    <div class="form-group mt-4">--}}
{{--                                        <label class="form-label fs-4 text-white">Name</label>--}}
{{--                                        <input style="background-color: #eaf3ff;" class="form-control"--}}
{{--                                               wire:model.defer="embedding_name" placeholder="Enter Embedding Name" type="text">--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group mt-4">--}}
{{--                                        <label class="form-label fs-4 text-white">Embedding (TXT,PDF)</label>--}}
{{--                                        <input style="background-color: #eaf3ff;" wire:model="embedding" id="embedding_file{{$fileInputId}}"--}}
{{--                                               class="form-control" type="file"--}}
{{--                                               accept="file/*">--}}
{{--                                        <span wire:loading.flex wire:target="embedding">--}}
{{--                                            Uploading ...--}}
{{--                                        </span>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group mt-4">--}}
{{--                                        <label class="form-label fs-4 text-white">Groups</label>--}}
{{--                                        <select wire:model.defer="group_ids" class="form-control" id="select2" multiple style="background-color: #0f1534; color: white;">--}}
{{--                                            <option value="">Select Group</option>--}}
{{--                                            @foreach($groups as $group)--}}
{{--                                                <option value="{{$group->id}}">{{$group->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            </div>--}}

{{--                        </div>--}}
{{--                        <button type="submit" class="btn-sm-2 new-orange-button btn-sm float-end text-white mt-4 mb-0">Create--}}
{{--                        </button>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

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
                $('#add-embedding-close-modal-button').click();
            }, 1000);
        });

        window.livewire.on('closeEditEmbeddingModal', function (){
            setTimeout(function (){
                $('#edit-embedding-close-modal-button').click();
            }, 1000);
        });

        window.livewire.on('closeAlert', function (){
            setTimeout(function (){
                $('.alert').alert('close');
            }, 5000);
        });

        window.Livewire.on('openEditModal', function () {

            $('#editEmbeddingModel').modal('toggle');
        });

    </script>

@endpush
