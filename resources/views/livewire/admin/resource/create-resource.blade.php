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
                        <div class="d-flex h-100" style="align-items: center">
                            <h2 class="font-weight-bolder custom-text-dark mb-0">Library Resources</h2>
                            <i class="fa-solid fa-info" data-toggle="modal"
                               data-target="#infoLibraryResource"
                               style="border: 2px solid #1b3a62;padding: 3px 8px;border-radius: 50%;margin-left: 15px; cursor: pointer"></i>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <button class=" btn-sm mt-2 mb-0" type="button" data-toggle="modal"
                                data-target="#createCategory"
                                style="background:#1b3a62;color:white;font-weight:bolder;border:none;">Add Category
                        </button>
                        <button data-bs-toggle="modal" data-bs-target="#createResource" wire:click="emptyCreateForm"
                                id="create_resourse_btn" class=" btn-sm float-end mt-2 mb-0"
                                style="background:#1b3a62;color:white;font-weight:bolder;border:none;">Create Resource
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
                                    wire:click="editMoveResource(`{{$category->id}}`)" data-bs-toggle="modal"
                                    data-bs-target="#moveResource" class="btn btn-sm mb-2 ">Edit Category
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
                            <label class="form-label fs-4" style="color: #1b3a62">Library Resource</label>
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
                                <span class="text-sm" style="color: black" id="html-formated-text-span">
                                    {!! $resource['content'] ?? null !!}
                                </span>
                            </div>
                        </div>
                        <div>
                            <button wire:click="deleteResource({{ $resource['id'] }}, '{{ $resource['slug'] }}')"
                                    style="background-color: red; color: white"
                                    class="btn btn-sm float-end mt-2 mb-4 mx-3">Delete Resource
                                <span wire:loading wire:target="deleteResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                            <button wire:click="editResource({{ $resource['id'] }})"
                                    style="background-color: #1B3A62 ; color: white"
                                    class="btn btn-sm float-end mt-2 mb-4 mx-3">Edit Resource
                                <span wire:loading wire:target="editResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @endforeach
    {{-- Create Library Resources Models--}}
    <div wire:ignore.self class="modal fade" id="createResource" tabindex="-1" role="dialog"
         data-bs-focus="false"
         aria-labelledby="createResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="CreateResource" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Create Library
                                        Resource</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Category</label>
                                        <select class="form-control input-form-style"
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
                                        <label class="form-label fs-4" style="color: #1b3a62">Heading</label>
                                        <input class="form-control input-form-style"
                                               wire:model.defer="heading" placeholder="heading" type="text"
                                               maxlength="150">
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">All Relevance</label>
                                        <select style="background-color: #eaf3ff; cursor: pointer"
                                                wire:model="relevance" class="form-control input-form-style">
                                            <option value="all_relevance">ALl Relevance</option>
                                            <option value="recommended">Highly Recommended</option>
                                            <option value="new">New</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Description</label>
                                        <textarea class="form-control input-form-style"
                                                  wire:model.defer="description" placeholder="Enter description"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="form-group mt-4" wire:ignore>
                                        <label class="form-label fs-4" style="color: #1b3a62">Content</label>
                                        <textarea class="form-control input-form-style" id="editor"
                                                  name="content" wire:model="content" rows="10" cols="10"></textarea>
                                    </div>
                                    <div class="form-group mt-4 ">
                                        <label class="form-label fs-4" style="color: #1b3a62">Gumlet Video Url</label>
                                        <input class="form-control input-form-style"
                                               wire:model.debounce.500ms="link" placeholder="Link" type="text"
                                               id="embedlink" wire:change="getVideoLink">
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">OR</label>
                                    <div class="form-group">
                                        <label class="form-label fs-4" style="color: #1b3a62">Resource (Image, Video, or
                                            Audio [PNG, JPG, GIF, MP4, MP3, MPEG, MOV])</label>
                                        <input wire:model="resource_file"
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
                                        <div class="col-4">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="1"
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="freemium">
                                                <label class="form-check-label" for="freemium">Freemium</label>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="2"
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="core">
                                                <label class="form-check-label" for="core">Focus Tier (core)</label>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="3"
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="premium">
                                                <label class="form-check-label" for="premium">Elevate Tier
                                                    (premium)</label>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-6">
                                                <label for="point">Point</label>
                                                <input type="number" id="point" class="form-control"
                                                       placeholder="Enter Point"
                                                       min="0"
                                                       wire:model.defer="pointValue"
                                                       style="border: 2px solid #1b3a62;">
                                            </div>

                                            <div class="col-6">
                                                <label for="price">Price</label>
                                                <input type="number" id="price" class="form-control"
                                                       placeholder="Enter Price"
                                                       min="0"
                                                       wire:model.defer="priceValue"
                                                       style="border: 2px solid #1b3a62;">
                                            </div>
                                        </div>

                                        <div class="row mt-4">

                                            @php
                                                $traits = ['VEN', 'MER', 'SO', 'SA', 'MA', 'JO', 'LU'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT TRAITS</label>
                                            <div class="row">
                                                @foreach($traits as $trait)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedTraits"
                                                                   value="{{ $trait }}" class="form-check-input"
                                                                   id="day_{{ $trait }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $trait }}">{{ $trait }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $features = ['DE', 'DOM', 'FE', 'GRE', 'LUN', 'NAI', 'NE', 'POW', 'SP', 'TRA', 'VAN', 'WIL'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT MOTIVATIONAL
                                                DRIVERS</label>
                                            <div class="row">
                                                @foreach($features as $feature)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedFeatures"
                                                                   value="{{ $feature }}" class="form-check-input"
                                                                   id="day_{{ $feature }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $feature }}">{{ $feature }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $alchemies = ['G', 'S', 'C', 'CS', 'GS', 'SC', 'SG'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT ALCHEMY</label>
                                            <div class="row">
                                                @foreach($alchemies as $alchemy)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedAlchemy"
                                                                   value="{{ $alchemy }}" class="form-check-input"
                                                                   id="day_{{ $alchemy }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $alchemy }}">{{ $alchemy }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $communications = ['EM', 'INS', 'INT', 'MOV'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT COMMUNICATION
                                                STYLE</label>
                                            <div class="row">
                                                @foreach($communications as $communication)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedCommunications"
                                                                   value="{{ $communication }}" class="form-check-input"
                                                                   id="day_{{ $communication }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $communication }}">{{ $communication }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $perceptions = ['Negative', 'Positive', 'Neutral'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT PERCEPTION OF
                                                LIFE</label>
                                            <div class="row">
                                                @foreach($perceptions as $perception)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedPerceptions"
                                                                   value="{{ $perception }}" class="form-check-input"
                                                                   id="day_{{ $perception }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $perception }}">{{ $perception }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $energyPools = ['Above Excellent', 'Average', 'Excellent', 'Fair'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT ENERGY
                                                POOL</label>
                                            <div class="row">
                                                @foreach($energyPools as $energyPool)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedEnergyPools"
                                                                   value="{{ $energyPool }}" class="form-check-input"
                                                                   id="day_{{ $energyPool }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $energyPool }}">{{ $energyPool }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Create
                                <span wire:loading wire:target="CreateResource" class="swal2-loader"
                                      style="font-size: 8px;"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Edit Library Resources Models--}}
    <div wire:ignore.self class="modal fade" id="editResource" tabindex="-1" role="dialog" data-bs-focus="false"
         aria-labelledby="editResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="updateResource">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4" style="color: #1b3a62">Edit Library Resource</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Category</label>
                                        <select class="form-control input-form-style"
                                                wire:model.defer="category_id" placeholder="Select category">
                                            @foreach($dropDownCategories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Heading</label>
                                        <input class="form-control input-form-style"
                                               wire:model.defer="heading" placeholder="heading" type="text">
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">All Relevance</label>
                                        <select style="background-color: #eaf3ff; cursor: pointer"
                                                wire:model.defer="relevance" class="form-control input-form-style">
                                            <option value="all_relevance">ALl Relevance</option>
                                            <option value="recommended">Highly Recommended</option>
                                            <option value="new">New</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4" style="color: #1b3a62">Description</label>
                                        <textarea class="form-control input-form-style"
                                                  wire:model.defer="description" placeholder="Enter description"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="form-group mt-4" wire:ignore>
                                        <label class="form-label fs-4" style="color: #1b3a62">Content</label>
                                        <textarea class="form-control input-form-style"
                                                  id="resourse_editor" name="update_content" wire:model="update_content"
                                                  rows="10">
                                        </textarea>
                                    </div>
                                    <div class="form-group mt-4 ">
                                        <label class="form-label fs-4" style="color: #1b3a62">Gumlet Video Url</label>
                                        <input class="form-control input-form-style"
                                               wire:model.debounce.500ms="link" placeholder="Link" type="text"
                                               id="embedlink" wire:change="getVideoLink">
                                    </div>
                                    <label class="form-label fs-4" style="color: #1b3a62">OR</label>
                                    <div class="form-group mt-4 " hidden>
                                        <label class="form-label fs-4" style="color: #1b3a62">Resource Id</label>
                                        <input class="form-control input-form-style"
                                               wire:model.defer="resourceId" type="text">
                                    </div>
                                    <div class="form-group mt-4 ">
                                        <label class="form-label fs-4" style="color: #1b3a62">Resource (Image, Video, or
                                            Audio [PNG, JPG, GIF, MP4, MP3, MPEG, MOV])</label>
                                        <input wire:model="resource_file"
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
                                    @if(!empty($editResourceData['photo_url']))
                                        <div class="form-group mt-4">
                                            <img src="{{$editResourceData['photo_url']['url'] ?? null}}" height="120"
                                                 width="200">
                                        </div>
                                    @elseif(!empty($editResourceData['video_url']))
                                        <div class="form-group mt-4">
                                            <video controls src="{{$editResourceData['video_url']['path'] ?? null}}"
                                                   style="height: 200px;"></video>
                                        </div>
                                    @elseif(!empty($editResourceData['audio_url']))
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

                                        <div class="col-4">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="1"
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="freemium">
                                                <label class="form-check-label" for="freemium">Freemium</label>
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="2"
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="core">
                                                <label class="form-check-label" for="core">Focus Tier (core)</label>
                                            </div>
                                        </div>


                                        <div class="col-4">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="3"
                                                       class="form-check-input option-checkbox"
                                                       style="border: 2px solid #1b3a62" id="premium">
                                                <label class="form-check-label" for="premium">Elevate Tier
                                                    (premium)</label>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-6">
                                                <label for="point">Point</label>
                                                <input type="number" id="point" class="form-control"
                                                       placeholder="Enter Point"
                                                       min="0"
                                                       wire:model.defer="pointValue"
                                                       style="border: 2px solid #1b3a62;">
                                            </div>

                                            <div class="col-6">
                                                <label for="price">Price</label>
                                                <input type="number" id="price" class="form-control"
                                                       placeholder="Enter Price"
                                                       min="0"
                                                       wire:model.defer="priceValue"
                                                       style="border: 2px solid #1b3a62;">
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            @php
                                                $traits = ['VEN', 'MER', 'SO', 'SA', 'MA', 'JO', 'LU'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT TRAITS</label>
                                            <div class="row">
                                                @foreach($traits as $trait)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedTraits"
                                                                   value="{{ $trait }}" class="form-check-input"
                                                                   id="day_{{ $trait }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $trait }}">{{ $trait }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $features = ['DE', 'DOM', 'FE', 'GRE', 'LUN', 'NAI', 'NE', 'POW', 'SP', 'TRA', 'VAN', 'WIL'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT MOTIVATIONAL
                                                DRIVERS</label>
                                            <div class="row">
                                                @foreach($features as $feature)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedFeatures"
                                                                   value="{{ $feature }}" class="form-check-input"
                                                                   id="day_{{ $feature }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $feature }}">{{ $feature }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $alchemies = ['G', 'S', 'C', 'CS', 'GS', 'SC', 'SG'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT ALCHEMY</label>
                                            <div class="row">
                                                @foreach($alchemies as $alchemy)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedAlchemy"
                                                                   value="{{ $alchemy }}" class="form-check-input"
                                                                   id="day_{{ $alchemy }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $alchemy }}">{{ $alchemy }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $communications = ['EM', 'INS', 'INT', 'MOV'];
                                            @endphp
                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT COMMUNICATION
                                                STYLE</label>
                                            <div class="row">
                                                @foreach($communications as $communication)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" wire:model="selectedCommunications"
                                                                   value="{{ $communication }}" class="form-check-input"
                                                                   id="day_{{ $communication }}">
                                                            <label class="form-check-label"
                                                                   for="day_{{ $communication }}">{{ $communication }}</label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $perceptionCodes = [
                                                    'NE' => 'Negative',
                                                    'P'  => 'Positive',
                                                    'N'  => 'Neutral'
                                                ];
                                            @endphp

                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT PERCEPTION OF
                                                LIFE</label>
                                            <div class="row">
                                                @foreach($perceptionCodes as $code => $label)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                   wire:model="selectedPerceptions"
                                                                   value="{{ $code }}"
                                                                   class="form-check-input"
                                                                   id="day_{{ $code }}">
                                                            <label class="form-check-label" for="day_{{ $code }}">
                                                                {{ $label }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @php
                                                $energyPoolCodes = [
                                                    'AE' => 'Above Excellent',
                                                    'A'  => 'Average',
                                                    'E'  => 'Excellent',
                                                    'F'  => 'Fair',
                                                ];
                                            @endphp

                                            <label class="form-label fs-4" style="color: #1b3a62">SELECT ENERGY
                                                POOL</label>
                                            <div class="row">
                                                @foreach($energyPoolCodes as $code => $label)
                                                    <div class="col-3">
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                   wire:model="selectedEnergyPools"
                                                                   value="{{ $code }}"
                                                                   class="form-check-input"
                                                                   id="day_{{ $code }}">
                                                            <label class="form-check-label" for="day_{{ $code }}">
                                                                {{ $label }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                            <span wire:loading wire:target="updateResource" class="swal2-loader"
                                  style="font-size: 8px;"></span>
                        </button>

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
    <div wire:ignore.self class="modal fade" id="createCategory" tabindex="-1" role="dialog"
         aria-labelledby="createCategory" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <label class="form-label fs-4" style="color: #1b3a62">Create Resource Category</label>
                    <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close"
                            id="create-category-close-modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br>
                    <p class="modal-close-btn">@include('layouts.message')</p>
                    <label>Category Name </label>
                    <input class="form-control input-form-style" wire:model.defer="category_name"
                           placeholder="Enter category name" type="text" maxlength="191">
                    <div class="p-2">
                        <button wire:click="createCategory" style="background-color: #1B3A62 ; color: white"
                                class="btn btn-sm float-end">submit
                            <span wire:loading wire:target="createCategory" class="swal2-loader"
                                  style="font-size: 8px;"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--info Library ResourceModal -->
    <div wire:ignore.self class="modal fade" id="infoLibraryResource" tabindex="-1" role="dialog"
         aria-labelledby="infoLibraryResource" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <label class="form-label fs-4" style="color: #1b3a62">Library Resource Info</label>
                    <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close"
                            id="create-category-close-modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br>
                    <p style="color: #344767">These are the exclusive Tools & Trainings you unlocked with your HPs or by
                        purchasing</p>
                </div>
            </div>
        </div>
    </div>

    {{--    move resouces--}}
    <div wire:ignore.self class="modal fade" id="moveResource" tabindex="-1" role="dialog"
         aria-labelledby="moveResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <form wire:submit.prevent="moveResourceToCategory">
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
                                    <label class="form-label fs-5 text-white">Move Resources To An Other
                                        Category</label>
                                    <br/>
                                    <select class="form-control input-form-style"
                                            wire:model.defer="category_id" placeholder="Select category">
                                        <option value="">Select Category</option>
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
        const editorElement = document.getElementById('editor');
        const updateEditorElement = document.getElementById('resourse_editor');
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
                $('#createResource').modal('toggle')
            }, 1000)
        })


        window.livewire.on('toggleEditResourceModal', () => {
            $('.modal-backdrop').hide();
            setTimeout(function () {
                $('#editResource').modal('toggle')
            })
        })

        window.livewire.on('reloadPage', () => {
            location.reload();
        });


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
                html: "<span style='color: white;'>Want to delete category and it's library resources permanently!</span>",
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
