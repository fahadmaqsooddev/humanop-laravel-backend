<div>
    <div class="card setting-box-background mt-4" id="train">
        <div class="card-header">
            <div id="train" class="content-page">
                <!-- Responsive Dropdown Section -->
                <div class="d-flex p-2">
                    <div class="btn-group col-md-4 d-flex justify-content-between ">
                        <button class="btn btn-outline-secondary text-dark dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" style="font-size: small; color: #0f1534; background-color: #8bb1ab" aria-expanded="false">
                            Select a Chatbot
                        </button>
                        <ul class="dropdown-menu" id="chatbotDropdown" style="width: 100%;">
                            <li><a class="dropdown-item" href="#" disabled>Select a chatbot</a></li>
                            <!-- Chatbot options will be populated here -->
                        </ul>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row gap-3">
                    <!-- Left Column -->
                    <div class="col-md-4">
                        <!-- Search Box -->
                        <div class="container-fluid mt-4 mx-0 px-0">
                            <div class="textarea-with-icon">
                                        <textarea class="form-control" rows="3" style="font-size: small; background-color: #8bb1ab"
                                                  placeholder="Search across all documents"></textarea>
                            </div>
                        </div>

                        <!-- Responsive Tabs -->
                        <ul class="nav nav-tabs justify-content-between flex-wrap mt-3">
                            <li class="nav-item">
                                <a class="nav-link active" style="color: #0f1534" aria-current="page" href="#">All</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: #0f1534" href="#">Pending</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: #0f1534" href="#">Deleted</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" style="color: #0f1534" href="#">Failed</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Right Column with Upload Options -->
                    <div class="col-md-8">
                        <div class="d-flex flex-wrap justify-content-around p-5">
                            <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                <i class="bi bi-graph-up"></i>
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="fw-bold" style="color: #0f1534">Upload files</div>
                                    <div class="text-muted fs-7">Files supported: TXT, PDF</div>
                                </div>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        data-bs-toggle="modal" data-bs-target="#createEmbedding"   class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                    upload
                                </button>
                            </div>

                            <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                                <i class="bi bi-graph-up"></i>
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="fw-bold" style="color: #0f1534">From Text</div>
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
                                    <div class="fw-bold" style="color: #0f1534">From questions and answers</div>
                                    <div class="text-muted fs-7">Files supported: TXT, PDF</div>
                                </div>
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                        class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                    add
                                </button>
                            </div>
                        </div>
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
                                        <input style="background-color: #0f1534;" wire:model.defer="embedding" id="embedding_file"
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
