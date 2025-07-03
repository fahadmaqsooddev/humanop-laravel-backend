@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #1b3a62; /* Example: Change this to your desired background color */
            color: white;
        }

        .ck-editor__editable {
            background-color: #1b3a62 !important;
            color: white;
        }

        .ck-editor {
            border-radius: 0 !important;
        }

        .card {
            background-color: #eaf3ff !important;
        }

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a {
            color: blue !important;
        }

        #html-formated-text-span > p > a {
            color: blue !important;
        }

    </style>
@endpush
<div class="row container-fluid">
    <div class="col-lg-9 position-relative z-index-2">
        <div class="mb-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="d-flex flex-column h-100">
                            <h2 class="font-weight-bolder custom-text-dark mb-0">HumanOp Shop</h2>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <button class=" btn-sm mt-2 mb-0" type="button" data-toggle="modal"
                                data-target="#createShopCategory"
                                style="background:#1b3a62;color:white;font-weight:bolder;border:none;">Add Shop Category
                        </button>
                        <button data-bs-toggle="modal" data-bs-target="#createShopResource" wire:click="emptyCreateForm"
                                id="create_resourse_btn" class=" btn-sm float-end mt-2 mb-0"
                                style="background:#1b3a62;color:white;font-weight:bolder;border:none;">Create Shop Resource
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-lg-8 col-sm-8">
                    <div class="card mb-4">
                        <a style="cursor: pointer; background-color: white; border-radius: 15px; border: 2px solid #1b3a62"
                           onclick="toggleCategoryBtn(`{{$category->id}}`);" data-toggle="collapse"
                           data-target="#collapse-{{$category->name}}" aria-expanded="false"
                           aria-controls="collapse-{{$category->name}}">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: #1b3a62;">
                                                {{$category['name']}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div
                                            class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="d-none p-3 py-0 mt-4" id="category_edit_{{$category->id}}">
                            <button style="background-color: red; color: white;margin-right: 5px;margin-bottom: 0px"
                                    onclick="confirmDeleteCategory('{{$category->id }}')" class="btn btn-sm mb-2">Delete
                                Category
                            </button>
                            <button style="background-color: #1b3a62; color: white;margin-bottom: 0px"
                                    wire:click="editHumanOpShopResource(`{{$category->id}}`)" data-bs-toggle="modal"
                                    data-bs-target="#moveShopResource" class="btn btn-sm mb-2 ">Edit Category
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="collapse pb-3" id="collapse-{{$category->name}}">
                        <div class="card-body p-3">
                            <div class="row">
                                @foreach($category['libraryResources'] as $resource)
                                    <div class="col-lg-5 col-sm-5">
                                        <div data-bs-toggle="modal" data-bs-target="#{{$resource['slug']}}">
                                            <div class="card mb-4"
                                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%); cursor: pointer;">
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-8 m-auto">
                                                            <div class="numbers">
                                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                                                   style="color: white;">{{$resource['heading']}}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-4 text-end">
                                                            <div
                                                                class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                                <i class="ni ni-world-2 text-lg opacity-10"
                                                                   aria-hidden="true"></i>
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
            @endforeach
        </div>
    </div>
    {{-- Library Resources Models--}}

    @foreach($categories as $category)
        @foreach($category['libraryResources'] as $resource)
            <div class="modal fade" id="{{$resource['slug']}}" aria-hidden="true"
                 aria-labelledby="{{$resource['slug']}}" tabindex="-1" role="dialog">
                <a class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style=" border-radius: 9px">
                        <div class="modal-body">
                            <label class="form-label fs-4" style="color: #1b3a62">HumanOp Shop Resource</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="mt-2">
                                <span class="text-sm"
                                      style="color: black">{!! $resource['description'] ?? null !!}</span>
                            </div>
                            @if($resource['upload_id'] != null)
                                @if(!empty($resource['photo_url']))
                                    <img style="width: 100%; max-height: 400px;"
                                         src="{{ $resource['photo_url'] ? $resource['photo_url']['url'] : '' }}">
                                @elseif(!empty($resource['video_url']))
                                    <video controls style="width: 100%; max-height: 400px;">
                                        <source src="{{ $resource['video_url'] ? $resource['video_url']['path'] : '' }}"
                                                type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif(!empty($resource['audio_url']))
                                    <audio controls style="width: 100%;">
                                        <source src="{{ $resource['audio_url'] ? $resource['audio_url']['path'] : '' }}"
                                                type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif
                            @elseif($resource['embed_link'] != null)
                                <video controls style="width: 100%; max-height: 400px;">
                                    <source src="{{ $resource['video_url'] ? $resource['video_url']['path'] : '' }}"
                                            type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                            <div class="mt-2 text-white">
                                <span class="text-white text-sm" id="html-formated-text-span">
                                    {!! $resource['content'] ?? null !!}
                                </span>
                            </div>
                        </div>
                        <div>
                            <button wire:click="deleteResource({{ $resource['id'] }})"
                                    style="background-color: red; color: white"
                                    class="btn btn-sm float-end mt-2 mb-4 mx-3">Delete Resource
                                <span wire:loading wire:target="deleteResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                            <button wire:click="editShopResource({{ $resource['id'] }})"
                                    style="background-color: #1B3A62 ; color: white"
                                    class="btn btn-sm float-end mt-2 mb-4 mx-3">Edit Resource
                                <span wire:loading wire:target="editShopResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @endforeach
    {{-- Create Library Resources Models--}}
    <div wire:ignore.self class="modal fade" id="createShopResource" tabindex="-1" role="dialog"
         data-bs-focus="false"
         aria-labelledby="createShopResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="createShopResource" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Create Shop
                                        Resource</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Category</label>
                                        <select  class="form-control input-form-style"
                                                 wire:model.defer="category_id" placeholder="Select category">
                                            <option>Select a category</option>
                                            @foreach($dropDownCategories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <span>{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Title</label>
                                        <input  class="form-control input-form-style"
                                                wire:model.defer="heading" placeholder="title" type="text"
                                                maxlength="150">
                                    </div>


                                    <div class="form-group">
                                        <label class="form-label fs-4" style="color: #1b3a62">Resource (Image, Video, or
                                            Audio [PNG, JPG, GIF, MP4, MP3, MPEG, MOV])</label>
                                        <input  wire:model="resource_file"
                                                id="resourse_file" wire:change="getResourceFile"
                                                class="form-control input-form-style resource_file" type="file"
                                                accept="image/*,video/*,audio/*">
                                        <span wire:loading.flex wire:target="resource_file">
                                            <div class="d-flex align-items-center mt-2">
                                                <div class="spinner-border" role="status"
                                                     style="color: #1b3a62 !important;"></div>
                                                <span class="ms-2" style="color: #1b3a62;">Uploading...</span>
                                              </div>
                                        </span>
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">Permission Level</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox"  wire:click="togglePermission(2)"
                                                       @if($permission == 2) checked @endif
                                                       class="form-check-input option-checkbox"

                                                       style="border: 2px solid #1b3a62" id="">
                                                <label class="form-check-label" for="freemium">Point</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox"  wire:click="togglePermission(1)"
                                                       @if($permission == 1) checked @endif
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="">
                                                <label class="form-check-label" for="premium">Price</label>
                                            </div>
                                        </div>

                                        @if($permission == 1 || $permission == 2)
                                            <input type="number" class="form-control mt-2 "
                                                   placeholder="Enter {{ $permission == 1 ? 'Price' : 'Point' }}"
                                                   wire:model.defer="priceValue">
                                        @endif
                                    </div>

                                    @php
                                        $traits = ['VEN', 'MER', 'SO', 'SA', 'MA', 'JO', 'LU'];
                                    @endphp
                                    <label class="form-label fs-4" style="color: #1b3a62">Select Traits</label>

                                    <div class="row">
                                        @foreach($traits as $trait)
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           wire:model="selectedTraits"
                                                           value="{{ $trait }}"
                                                           class="form-check-input"
                                                           id="day_{{ $trait }}">
                                                    <label class="form-check-label" for="day_{{ $trait }}">{{ $trait }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Create
                                <span wire:loading wire:target="createShopResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Edit Library Resources Models--}}
    <div wire:ignore.self class="modal fade" id="editShopResource" tabindex="-1" role="dialog" data-bs-focus="false"
         aria-labelledby="editShopResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="updateShopResource">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Edit Shop Resource</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Category</label>
                                        <select  class="form-control input-form-style"
                                                 wire:model.defer="category_id" placeholder="Select category">
                                            @foreach($dropDownCategories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Title</label>
                                        <input  class="form-control input-form-style"
                                                wire:model.defer="heading" placeholder="title" type="text">
                                    </div>



                                    <div class="form-group mt-4 " hidden>
                                        <label class="form-label fs-4" style="color: #1b3a62">Resource Id</label>
                                        <input  class="form-control input-form-style"
                                                wire:model.defer="resourceId" type="text">
                                    </div>
                                    <div class="form-group mt-4 ">
                                        <label class="form-label fs-4" style="color: #1b3a62">Resource (Pdf, Video, or
                                            Audio [PNG, JPG, GIF, MP4, MP3, MPEG, MOV])</label>
                                        <input  wire:model="resource_file"
                                                id="resource_file" wire:change="getResourceFile"
                                                class="form-control input-form-style resource_file1" type="file"
                                                accept="image/,video/,audio/*" onchange="logSelectedFile(event)">
                                    </div>
                                    <span wire:loading.flex wire:target="resource_file">
                                            <div class="d-flex align-items-center mt-2">
                                                <div class="spinner-border" role="status"
                                                     style="color: #1b3a62 !important;"></div>
                                                <span class="ms-2" style="color: #1b3a62;">Uploading...</span>
                                              </div>
                                        </span>
                                    @if(!empty($editResourceData['document_id']))
{{--                                        {{dd($editResourceData['document_url']['path'])}}--}}
                                        <div class="form-group mt-4">
                                            <iframe src="{{ $editResourceData['document_url']['path']??null }}" width="100%" height="500px">
                                                This browser does not support PDFs. Please download the PDF to view it:
                                                <a href="{{ $editResourceData['document_url']['path'] ?? null }}">Download PDF</a>
                                            </iframe>
                                        </div>
                                    @elseif(!empty($editResourceData['video_id']))
{{--                                        {{dd($editResourceData['video_url']['path'])}}--}}
                                        <div class="form-group mt-4">
                                            <video controls src="{{$editResourceData['video_url']['path'] ?? null}}"
                                                   style="height: 200px;"></video>
                                        </div>
                                    @elseif(!empty($editResourceData['audio_id']))
{{--                                        {{dd($editResourceData['audio_url']['path'])}}--}}
                                        <div class="form-group mt-4">
                                            <audio controls style="width: 100%;">
                                                <source src="{{ $editResourceData['audio_url']['path'] }}"
                                                        type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        </div>
                                    @else
                                    @endif
                                    <label class="form-label fs-4" style="color: #1b3a62">Permission Level</label>
                                    <div class="row">
                                        <ul>
                                            @if(!empty($editResourceData))
                                                @if($editResourceData['buy_from'] == 1)
                                                    <li>Price</li>
                                                @elseif($editResourceData['buy_from'] == 2)
                                                    <li>Point</li>

                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">Permission Level</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox"  wire:click="togglePermission(2)"
                                                       @if($permission == 2) checked @endif
                                                       class="form-check-input option-checkbox"

                                                       style="border: 2px solid #1b3a62" id="">
                                                <label class="form-check-label" for="freemium">Point</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox"  wire:click="togglePermission(1)"
                                                       @if($permission == 1) checked @endif
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="">
                                                <label class="form-check-label" for="premium">Price</label>
                                            </div>
                                        </div>

                                        @if($permission == 1 || $permission == 2)
                                            <input type="number" class="form-control mt-2 "
                                                   placeholder="Enter {{ $permission == 1 ? 'Price' : 'Point' }}"
                                                   wire:model.defer="priceValue">
                                        @endif
                                    </div>

                                    @php
                                        $traits = ['VEN', 'MER', 'SO', 'SA', 'MA', 'JO', 'LU'];
                                    @endphp
                                    <label class="form-label fs-4" style="color: #1b3a62">Select Traits</label>

                                    <div class="row">
                                        @foreach($traits as $trait)
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                           wire:model="selectedTraits"
                                                           value="{{ $trait }}"
                                                           class="form-check-input"
                                                           id="day_{{ $trait }}">
                                                    <label class="form-check-label" for="day_{{ $trait }}">{{ $trait }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                                <span wire:loading wire:target="updateResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if($booleanValue)
        <script>
            const resourceFileInput = document.querySelector('.resource_file1');
            if (resourceFileInput) {
                resourceFileInput.value = "";
            } else {
                console.log("Resource file input not found.");
            }
        </script>
    @endif
    @if($booleanValue)
        <script>
            const resourceFileInput = document.querySelector('.resource_file');
            if (resourceFileInput) {
                resourceFileInput.value = "";
            } else {
                console.log("Resource file input not found.");
            }
        </script>
    @endif
    <!--Create Category Modal -->
    <div wire:ignore.self class="modal fade" id="createShopCategory" tabindex="-1" role="dialog"
         aria-labelledby="createShopCategory" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <label class="form-label fs-4" style="color: #1b3a62">Create Shop Category</label>
                    <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close"
                            id="create-category-close-modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br>

                    <p class="modal-close-btn">@include('layouts.message')</p>
                    <label>Category Name </label>
                    <input  class="form-control input-form-style" wire:model.defer="category_name"
                            placeholder="Enter category name" type="text" maxlength="191">
                    <div class="p-2">
                        <button wire:click="createShopCategory" style="background-color: #1B3A62 ; color: white"
                                class="btn btn-sm float-end">submit
                            <span wire:loading wire:target="createShopCategory" class="swal2-ladeor"
                                  style="font-size: 8px;"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    move resouces--}}
    <div wire:ignore.self class="modal fade" id="moveShopResource" tabindex="-1" role="dialog"
         aria-labelledby="moveShopResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="moveShopResourceToCategory">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Edit Category</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <br/>
                                    <br/>
                                    <label class="form-label fs-5 " style="color: #1b3a62">Move Resources To An Other
                                        Category</label>
                                    <br/>
                                    <select  class="form-control input-form-style"
                                             wire:model.defer="category_id" placeholder="Select category">
                                        <option value="">Select Category
                                        @foreach($dropDownCategories as $category)
                                            @if($current_category != $category->id)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
            }
        }
    </script>
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
        let editorInstance, updateEditorInstance;
        const editorElement = document.getElementById('shop_editor');
        const updateEditorElement = document.getElementById('shop_resourse_editor');
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
                    @this.set('content', editor.getData())
                        ;
                    })
                    Livewire.on('editorContentUpdated', content => {
                        editor.setData(content); // Set new content into CKEditor
                    });
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }
        $('#create_resourse_btn').on('click', function () {
            if (editorInstance) {
                editorInstance.setData('');
            }
            $('#resourse_file').val('');
        });
        if (updateEditorElement && !updateEditorElement.classList.contains('ck-editor')) { // Check if not already initialized
            ClassicEditor
                .create(updateEditorElement, {
                    plugins: [Essentials, Paragraph, Bold, Italic, Font, List, Link, AutoLink],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', 'link'  // Add list options to toolbar
                    ]
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                    @this.set('update_content', editor.getData())
                        ;
                    })
                    Livewire.on('contentUpdated', content => {
                        editor.setData(content); // Set new content into CKEditor
                    });

                    updateEditorInstance = editor;
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

        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                // Close the modal
                $('#dailyTipModel').modal('hide');
            });
        });
    </script>

    <script>
        window.livewire.on('toggleCreateResourceModal', () => {
            setTimeout(function () {
                $('#createShopeResource').modal('toggle')
            }, 1000)
        })


        window.livewire.on('toggleEditShopResourceModal', () => {
            $('.modal-backdrop').hide();
            setTimeout(function () {
                $('#editShopResource').modal('toggle')
            })
        })


        window.livewire.on('toggleShowResourceModal', (slug) => {
            $('.modal-backdrop').hide();
            setTimeout(function () {
                $('#' + slug).modal('hide');
            }, 1000);
        });

        window.livewire.on('toggleCreateCategoryModal', () => {
            $('.modal-backdrop').hide();
            setTimeout(function () {
                $('#create-category-close-modal').click();
            }, 1000);
        });

    </script>
    <!-- script for checkbox multiple check  -->
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <script>
        document.querySelectorAll('.option-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    document.querySelectorAll('.option-checkbox').forEach(function (other) {
                        if (other !== checkbox) {
                            other.checked = false;
                        }
                    });
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const optionCheckboxes1 = document.querySelectorAll('.option-checkbox1');
            const allOptionseditCheckbox = document.getElementById('editOptions');

            // Function to check/uncheck all other checkboxes when "All of these" is clicked
            allOptionseditCheckbox.addEventListener('change', function () {
                optionCheckboxes1.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Function to control the "All of these" checkbox state when individual checkboxes are clicked
            optionCheckboxes1.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    allOptionseditCheckbox.checked = [...optionCheckboxes1].every(checkbox => checkbox.checked);
                });
            });


        });

        function toggleCategoryBtn(id) {
            if ($('#category_edit_' + id).hasClass('d-flex')) {
                $('#category_edit_' + id).removeClass('d-flex justify-content-end').addClass('d-none');
            } else {
                $('#category_edit_' + id).removeClass('d-none').addClass('d-flex justify-content-end');
            }
        }

        function confirmDeleteCategory(category_id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-primary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to delete category and it's HumanOP Shop resources permanently!</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteCategoryPermanently', category_id);
                }
            })
        }
    </script>
@endpush
