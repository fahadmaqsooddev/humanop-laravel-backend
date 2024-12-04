<div>
    <div class="card card-bg-white-orange-border mt-4" id="train">
        <div class="card-header">
            <div id="train" class="content-page">
                <!-- Responsive Dropdown Section -->
                <div class="d-flex p-2">
                    <div class="btn-group m-1 col-md-4 d-flex justify-content-between ">

                        <select wire:model="group_id" class="form-control">
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{$group->id}}">{{$group->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="btn-group m-1 col-md-4 d-flex justify-content-between">
                        <select class="form-control" wire:model="embedding_id">
                            <option value="">Select @if($group_id) Embedding @else Group First @endif</option>
                            @if(count($embeddings) > 0)
                                <option disabled style="background-color: #0f1534; color: white;">All Embeddings</option>
                            @endif
                            @foreach($embeddings as $embedding)
                                <option value="{{$embedding['request_id'] ?? null}}">{{$embedding['name'] ?? null}}</option>
                            @endforeach
                            @if(count($active_embeddings) > 0)
                                <option disabled style="background-color: #0f1534; color: white;">Active Embeddings</option>
                            @endif
                            @foreach($active_embeddings as $active_embedding)
                                <option value="{{$active_embedding['request_id'] ?? null}}">{{$active_embedding['name'] ?? null}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($button_status_display)
                        <div style="margin-left: 10px">
                            <button style="padding:5px 10px 5px 10px; border-radius: 7px;"
                                    wire:click="changeEmbeddingStatus"
                                    class="btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                {{$button_status}}
                            </button>
                        </div>
                    @endif
                </div>

                <div class="d-flex flex-column flex-md-row gap-3">
                    <!-- Left Column -->
                    <div class="col-md-4">
                        <!-- Search Box -->
                        <div class="container-fluid mt-4 mx-0 px-0">
                            <form wire:submit.prevent="searchEmbedding">
                                <div class="textarea-with-icon">
                                        <textarea class="form-control input-bg" rows="3"
                                                  style="font-size: small;"
                                                  wire:model.defer="query"
                                                  placeholder="Search across all documents"></textarea>
                                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                            class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                        search
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Responsive Tabs -->
                        {{--                        <ul class="nav nav-tabs justify-content-between flex-wrap mt-3">--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a class="nav-link active" style="color: #0f1534" aria-current="page" href="#">All</a>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a class="nav-link" style="color: #0f1534" href="#">Pending</a>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a class="nav-link" style="color: #0f1534" href="#">Deleted</a>--}}
                        {{--                            </li>--}}
                        {{--                            <li class="nav-item">--}}
                        {{--                                <a class="nav-link" style="color: #0f1534" href="#">Failed</a>--}}
                        {{--                            </li>--}}
                        {{--                        </ul>--}}
                    </div>

                    <!-- Right Column with Upload Options -->
                    <div class="col-md-8">
                        @if(count($chunks) > 0)
                            @foreach($chunks as $chunk)
                                <div class="chunk-card input-bg">
                                    <p class="custom-text-dark">{{ $chunk['retrieved_docs'] }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="d-flex flex-wrap justify-content-around p-5">
                                <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                    <i class="bi bi-graph-up"></i>
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="fw-bold text-orange">Upload files</div>
                                        <div class="text-muted fs-7">Files supported: TXT, PDF</div>
                                    </div>
                                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                            data-bs-toggle="modal" data-bs-target="#createEmbedding"
                                            class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                        upload
                                    </button>
                                </div>

                                <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                    <i class="bi bi-graph-up"></i>
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="fw-bold text-orange">From Text</div>
                                        <div class="text-muted fs-7">Files supported: TXT, PDF</div>
                                    </div>
                                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                            class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                        add
                                    </button>
                                </div>

                                <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                    <i class="bi bi-graph-up"></i>
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="fw-bold text-orange">From questions and answers</div>
                                        <div class="text-muted fs-7">Files supported: TXT, PDF</div>
                                    </div>
                                    <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                            class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                        add
                                    </button>
                                </div>
                            </div>
                        @endif
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
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="createEmbedding" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
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
                                        <input style="background-color: #0f1534;" wire:model.defer="embedding"
                                               id="embedding_file"
                                               class="form-control text-white" type="file"
                                               accept="file/*">
                                        <span wire:loading.flex wire:target="embedding">
                                            Uploading ...
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
