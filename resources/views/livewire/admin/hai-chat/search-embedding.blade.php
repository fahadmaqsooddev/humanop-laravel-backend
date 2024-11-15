<div id="train" class="content-page" style="display: flex;">
    <!-- Responsive Dropdown Section -->


    <div class="d-flex flex-column flex-md-row gap-3 w-100">
        <!-- Left Column -->
        <div class="col-md-4">
            <!-- Search Box -->
            <div>
                @include('layouts.message')

                <div class="container-fluid mt-4 mx-0 px-0">
                    <form wire:submit.prevent="submitForm">
                        <div class="textarea-with-icon">
            <textarea class="form-control" rows="3"
                      style="font-size: small; background-color: #f3deba; color: #0f1534" wire:model.defer="query"
                      placeholder="Search across all documents"></textarea>
                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Responsive Tabs -->
            {{--                                <ul class="nav nav-tabs justify-content-between flex-wrap mt-3">--}}
            {{--                                    <li class="nav-item">--}}
            {{--                                        <a class="nav-link main-heading active" aria-current="page" href="#">All</a>--}}
            {{--                                    </li>--}}
            {{--                                    <li class="nav-item">--}}
            {{--                                        <a class="nav-link main-heading" href="#">Pending</a>--}}
            {{--                                    </li>--}}
            {{--                                    <li class="nav-item">--}}
            {{--                                        <a class="nav-link main-heading" href="#">Deleted</a>--}}
            {{--                                    </li>--}}
            {{--                                    <li class="nav-item">--}}
            {{--                                        <a class="nav-link main-heading" href="#">Failed</a>--}}
            {{--                                    </li>--}}
            {{--                                </ul>--}}
        </div>

        <!-- Right Column with Upload Options -->
        <div class="col-md-8">

            @if(count($chunks) > 0)
                @foreach($chunks as $chunk)
                    <div class="chunk-card">
                        <p>{{ $chunk['retrieved_docs'] }}</p>
                    </div>
                @endforeach
            @else
                <div class="d-flex flex-wrap justify-content-between p-5">
                    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                        <i class="bi bi-graph-up"></i>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <div class="fw-bold main-heading">Upload files</div>
                            <div class="fs-7 main-heading">Files supported: TXT, PDF</div>
                        </div>
                        <input type="file" id="fileInput" style="display: none;"/>
                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                            upload
                        </button>
                    </div>

                    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                        <i class="bi bi-graph-up"></i>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <div class="fw-bold main-heading">From Text</div>
                            <div class="fs-7 main-heading">Files supported: TXT, PDF</div>
                        </div>
                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                            add
                        </button>
                    </div>

                    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                        <i class="bi bi-graph-up"></i>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <div class="fw-bold main-heading">From questions and answers</div>
                            <div class="fs-7 main-heading">Files supported: TXT, PDF</div>
                        </div>
                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                            add
                        </button>
                    </div>
                </div>
                <hr/>
                <div>

                    @livewire('admin.hai-chat.embedding-setting.setting',['name' => $name])

                    <div class="flex-column">
                        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                            <i class="bi bi-graph-up"></i>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="fw-bold main-heading">From a webpage</div>
                                <div class="fs-7 main-heading">One link in each line (max 200)</div>
                            </div>
                            <input type="file" id="fileInput" style="display: none;"/>
                        </div>
                        <input type="text" placeholder="https://..." class="form-control my-2"
                               style="background-color: #f3deba" id="exampleInputPassword1">
                        <div class="d-flex justify-content-between">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Extract URLs</label>

                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                            </div>
                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                scrape
                            </button>

                        </div>
                    </div>
                </div>
                <hr/>
                <div>

                    <div class="flex-column">
                        <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
                            <i class="bi bi-graph-up"></i>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="fw-bold main-heading">From a Youtube Video</div>
                                <div class="fs-7 main-heading">Youtube video must have captions</div>
                            </div>
                            <input type="file" id="fileInput" style="display: none;"/>
                        </div>
                        <input type="text" placeholder="https://www.youtube.com?watch..."
                               style="background-color: #f3deba"
                               class="form-control my-2" id="exampleInputPassword1">
                        <div class="d-flex justify-content-between">
                            <div class="form-check form-switch">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Extract URLs</label>

                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                            </div>
                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;"
                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                                scrape
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
