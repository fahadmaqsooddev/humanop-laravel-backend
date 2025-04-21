@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #0f1534; /* Example: Change this to your desired background color */
        }

        .ck-editor__editable {
            background-color: #0f1534 !important;
        }

        .ck-editor {
            border-radius: 0 !important;
            width: 100% !important;
        }

        .card {
            background-color: #1C365E !important;
        }

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a{
            color: blue !important;
        }

    </style>
@endpush
<div>

    @if (session()->has('success'))
    <div class="alert alert-success text-white">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger text-white">
        {{ session('error') }}
    </div>
@endif

    <div class="container">
        <div style="margin-top: 80px; margin-left: 50px">
            <a data-bs-toggle="modal" data-bs-target="#inviteLinkSendModel" style="background-color: #f2661c; color: white" class="btn btn-sm float-end">Add pricing plan</a>
            <br>
        </div>
    </div>
    <br>

    <div class="row container">
        <div class="card text-center border rounded-4 shadow-sm mx-auto p-4 col-md-5"
            style="max-width: 450px; background-color:#F6BA81 !important">
            <div class="card-header bg-opacity-50 rounded-4">
                <img src="{{ asset('assets/img/maestro-logo.svg') }}" alt="Membership Icon"
                    style="width: 100px; object-fit: contain;" />
                <div class="mt-3 px-3 py-1 mx-auto border shadow-sm rounded-pill w-50 text-dark fw-semibold">
                    Premium
                </div>
                <h4 class="mt-3 fw-bold display-6">$29</h4>
                <small class="text-muted">/ per month</small>
            </div>
            <hr class="my-4 border border-secondary" />
            <div class="card-body px-4">
                <h5 class="fw-semibold">Features</h5>
                <p class="text-muted">Everything in Basic Plan</p>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item">Unlimited Access</li>
                </ul>
            </div>
        </div>

        <div class="card text-center border rounded-4 shadow-sm mx-auto p-4 col-md-5"
            style="max-width: 450px;background-color:#bcdec6 !important">
            <div class="card-header bg-opacity-50 rounded-4">
                <img src="{{ asset('assets/img/maestro-logo.svg') }}" alt="Membership Icon"
                    style="width: 100px; object-fit: contain;" />
                <div class="mt-3 px-3 py-1 mx-auto border shadow-sm rounded-pill w-50 text-dark fw-semibold">
                    Premium
                </div>
                <h4 class="mt-3 fw-bold display-6">$29</h4>
                <small class="text-muted">/ per month</small>
            </div>
            <hr class="my-4 border border-secondary" />
            <div class="card-body px-4">
                <h5 class="fw-semibold">Features</h5>
                <p class="text-muted">Everything in Basic Plan</p>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item">Unlimited Access</li>
                </ul>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="inviteLinkSendModel" tabindex="-1" role="dialog"
        aria-labelledby="inviteLinkSendModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Create An Plan</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="text-white">Plan Name</label>
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                class="form-control text-white" type="text" wire:model="plan_name"
                                                name="plan_name" placeholder="icon name">
                                            @error('plan_name')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror
                                            <label class="text-white">Plan Type</label>
                                            <select style="background-color: #0f1534; color: lightgrey !important"
                                                class="form-control text-white" wire:model="plan_type" name="plan_type">
                                                <option value="">-- Select Plan --</option>
                                                <option value="month">Monthly</option>
                                                <option value="year">Yearly</option>
                                            </select>
                                            @error('plan_type')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror

                                            <label class="text-white">Team Members</label>
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                class="form-control text-white" type="number" wire:model="team_members"
                                                name="team_members" placeholder="icon name">
                                            @error('team_members')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror

                                            <label class="text-white">Price</label>
                                            <input style="background-color: #0f1534;color: lightgrey !important"
                                                class="form-control text-white" type="number" wire:model="price"
                                                name="price" placeholder="icon name">

                                            @error('price')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror

                                            <label class="text-white">Description</label>
                                            <textarea style="background-color: #0f1534; color: lightgrey !important" id="peditor" class="form-control text-white"
                                                wire:model="plan_desc" name="plan_desc" placeholder=""></textarea>


                                            @error('plan_desc')
                                                <span class="text-sm text-danger">{{ $message }}</span>
                                            @enderror


                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"
                                                style="background-color: #f2661c ">Create Plan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
        }
    }




    </script>
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font,
            List,
            Link,
            AutoLink
        } from 'ckeditor5';

        // Function to initialize CKEditor for a specific textarea by ID
        let editorInstance;
        const editorElement = document.getElementById('peditor');
        if (editorElement && !editorElement.classList.contains('ck-editor')) { // Check if not already initialized
            ClassicEditor
                .create(editorElement, {
                    plugins: [Essentials, Paragraph, Bold, Italic, Font, List, Link, AutoLink],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', 'link'  // Add list options to toolbar
                    ]
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                    @this.set('plan_desc', editor.getData());
                    })
                    Livewire.on('contentUpdated', content => {
                        editor.setData(content); // Set new content into CKEditor
                    });
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });

        }
        $('.createForm').on('click', function () {
            if (editorInstance) {
                editorInstance.setData('');
            }
        });


    </script>
