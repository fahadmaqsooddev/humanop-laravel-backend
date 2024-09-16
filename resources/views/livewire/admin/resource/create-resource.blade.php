<div class="row container-fluid">
    <div class="col-lg-7 position-relative z-index-2">
        <div class="mb-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="d-flex flex-column h-100">
                            <h2 class="font-weight-bolder mb-0">Library Resources</h2>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <button data-bs-toggle="modal" data-bs-target="#createResource"
                                style="background-color: #f2661c; color: white"
                                class="btn btn-sm float-end mt-2 mb-0">Create Resource
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($resources as $resource)
                <div class="col-lg-5 col-sm-5">
                    <a data-bs-toggle="modal" href="#{{$resource['slug']}}">
                        <div class="card mb-4"
                             style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
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
                                            <i class="ni ni-world-2 text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    {{--    Library Resources Models--}}
    @foreach($resources as $resource)
        <div class="modal fade" id="{{$resource['slug']}}" aria-hidden="true" aria-labelledby="MasterKeyLabel"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background-color: #0f1535; border-radius: 9px">
                    <div class="modal-body">
                        <label class="form-label fs-4 text-white">Library Resource</label>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        @if(!empty($resource['photo_url']))
                            <img class="mt-4" style="width: 100%; max-height: 400px;" src="{{$resource['photo_url']['url']}}">
                        @else
                            <video class="mt-4" controls style="width: 100%; max-height: 400px;" src="{{$resource['video_url']['path']}}">
                        @endif

                    </div>
                    <div>
                        <button wire:click="deleteResource({{ $resource['id'] }}, '{{ $resource['slug'] }}')"
                                style="background-color: red; color: white"
                                class="btn btn-sm float-end mt-2 mb-4 mx-3">Delete Resource
                        </button>
                        <button wire:click="editResource({{ $resource['id'] }})"
                                style="background-color: #f2661c; color: white"
                                class="btn btn-sm float-end mt-2 mb-4 mx-3">Edit Resource
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{--    Create Library Resources Models--}}
    <div wire:ignore.self class="modal fade" id="createResource" tabindex="-1" role="dialog"
         aria-labelledby="createResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <form wire:submit.prevent="CreateResource" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Create Library Resource</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Heading</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="heading" placeholder="heading" type="text">
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Resource (Image or Video)</label>
                                        <input style="background-color: #0f1534;" wire:model.defer="resource"
                                               class="form-control text-white" type="file" accept="image/*,video/*">
                                        <span wire:loading.flex wire:target="resource">
                                            Uploading ...
                                        </span>
                                    </div>
                                    <label class="form-label fs-4 text-white">Permission</label>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="1"
                                                       class="form-check-input">
                                                <label class="form-check-label text-white">Freemium</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="2"
                                                       class="form-check-input">
                                                <label class="form-check-label text-white">Core</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="3"
                                                       class="form-check-input">
                                                <label class="form-check-label text-white">Preemium</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="4"
                                                       class="form-check-input">
                                                <label class="form-check-label text-white">All of these</label>
                                            </div>
                                        </div>
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

    {{--    Edit Library Resources Models--}}
    <div wire:ignore.self class="modal fade" id="editResource" tabindex="-1" role="dialog"
         aria-labelledby="editResource" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <form wire:submit.prevent="updateResource">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Edit Library Resource</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Heading</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="heading" placeholder="heading" type="text">
                                    </div>
                                    <div class="form-group mt-4" hidden>
                                        <label class="form-label fs-4 text-white">Resource Id</label>
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               wire:model.defer="resourceId" type="text">
                                    </div>
                                    <div class="form-group mt-4">
                                        <label class="form-label fs-4 text-white">Resource (Image or Video)</label>
                                        <input style="background-color: #0f1534;" wire:model.defer="resource"
                                               class="form-control text-white" type="file" accept="image/*,video/*">
                                    </div>
                                    @if(!empty($editResourceData['photo_url']))
                                    <div class="form-group mt-4">
                                        <img src="{{$editResourceData['photo_url']['url'] ?? null}}" height="120" width="200">
                                    </div>
                                    @else
                                        <div class="form-group mt-4">
                                            <video controls src="{{$editResourceData['video_url']['path'] ?? null}}" style="height: 200px;"></video>
                                        </div>
                                    @endif
                                    <label class="form-label fs-4 text-white">Permission</label>
                                    <div class="row">

                                        <ul>
                                            @foreach($editResourceData['library_permissions'] ?? [] as $permission)
                                                @if($permission['permission'] === 1)
                                                    <li>Freemium</li>
                                                @elseif($permission['permission'] === 2)
                                                    <li>Core</li>
                                                @elseif($permission['permission'] === 3)
                                                    <li>Premium</li>
                                                @elseif($permission['permission'] === 4)
                                                    <li>Freemium, Core, Premium</li>
                                                @endif
                                            @endforeach
                                        </ul>

                                    </div>
                                    <div class="row">

                                        <div class="col-6">
                                            <div class="form-check">

                                                <input type="checkbox" wire:model.defer="permission" value="1"
                                                       class="form-check-input">

                                                <label class="form-check-label text-white">Freemium</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="2"
                                                           class="form-check-input">
                                                <label class="form-check-label text-white">Core</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="3"
                                                       class="form-check-input">
                                                <label class="form-check-label text-white">Premium</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check">
                                                <input type="checkbox" wire:model.defer="permission" value="4"
                                                       class="form-check-input">
                                                <label class="form-check-label text-white">All of these</label>
                                            </div>
                                        </div>
                                    </div>
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

        window.livewire.on('toggleShowResourceModal', (slug) => {
            $('.modal-backdrop').hide();
            setTimeout(function () {
                $('#' + slug).modal('hide');
            }, 1000);
        });

    </script>
@endpush
