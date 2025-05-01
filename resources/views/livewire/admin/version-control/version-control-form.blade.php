<div>

    
    <div class="col-lg-9 position-relative z-index-2">
        <div class="mb-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="d-flex flex-column h-100">
                            <h2 class="font-weight-bolder custom-text-dark mb-0">Version Controls</h2>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <button class=" btn-sm mt-2 mb-0" type="button" data-toggle="modal"
                                data-target="#versionModel"
                                style="background:#f2661c;color:white;font-weight:bolder;border:none;">
                            Add version
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            @foreach($versions as $item)

                <div class="col-lg-8 col-sm-8">

                    <div class="card mb-4">
                        <a style="cursor: pointer;" onclick="toggleCategoryBtn(`{{$item->id}}`)"
                           data-toggle="collapse" data-target="#collapse-{{$item->id}}" aria-expanded="false"
                           aria-controls="collapse-{{$item->name}}">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8 m-auto">
                                        <div class="numbers">

                                            <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                               style="color: white;">
                                                {{$item['version']}}
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
                        <div class="d-none p-3 py-0" id="category_edit_{{$item->id}}">
                            <button style="background-color: red; color: white;margin-right: 5px;margin-bottom: 0px"
                                    onclick="confirmDeleteVersion('{{$item->id }}')" class="btn btn-sm mb-2">Delete
                                Version
                            </button>

                            <button style="background-color: #f2661c; color: white;margin-bottom: 0px"
                            data-bs-toggle="modal"
                            wire:click="editVersion({{ $item['id'] }}, '{{ $item['version'] }}','{{ $item['note'] }}')"
                            data-bs-target="#versionModel"  class="btn btn-sm mb-2 ">Edit Version
                            </button>
                        </div>
                    </div>


                </div>
                <div class="col-12">
                    <div class="collapse pb-3" id="collapse-{{$item->id}}">
                        <div class="card-body p-3">
                            <div class="row"> 

                                @foreach($item['versionDescriptions'] as $resource)
                                @if($resource)

                                    <div class="col-lg-5 col-sm-5">
                                        <div data-bs-toggle="modal" data-bs-target="#{{$resource['id']}}">
                                            <div class="card mb-4"
                                                 style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%); cursor: pointer;">
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-8 m-auto">
                                                            <div class="numbers">
                                                                <p class="text-sm mb-0 text-capitalize font-weight-bold"
                                                                   style="color: white;">
                                                                    {{$resource['description']}}
                                                                </p>
                                                                
                                                            </div>
                                                        </div>

                                                        
                                                        <div class="col-4 text-end">
                                                            <div
                                                                class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                                                <i class="ni ni-world-2 text-lg opacity-10"
                                                                   aria-hidden="true"></i>
                                                            </div>
                                                        </div>


                                                        <div class=" p-3 py-0" id="description_edit_{{$resource->id}}">
                                                            <button style="background-color: red; color: white;margin-right: 5px;margin-bottom: 0px"
                                                                    onclick="confirmDeleteDescription('{{$resource->id }}')" class="btn btn-sm mb-2">Delete
                                                                
                                                            </button>
                                
                                                            <button style="background-color: #f2661c; color: white;margin-bottom: 0px"
                                                            data-bs-toggle="modal"
                                                            wire:click="editDescription({{ $resource['id'] }},'{{$resource['version_id']}}','{{ $resource['description'] }}','{{ $resource['platform'] }}','{{ $resource['version_heading'] }}')"
                                                            data-bs-target="#descriptionModel"  class="btn btn-sm mb-2 ">Edit 
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                <p>no</p>
                                @endif
                                @endforeach 
                             </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
    
    {{-- <div class="table-responsive table-orange-color"> --}}
        {{-- @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>#sr</th>
                <th>Version</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($versions as $item)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$item['id']}} </td>
                    <td class="text-md font-weight-normal">{{$item['version']}} </td>
                    <td>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal"
                                wire:click="editVersion({{ $item['id'] }}, '{{ $item['version'] }}','{{ $item['details'] }}')"
                                data-bs-target="#versionModel"  style="background-color: #f2661c;" >
                            update
                        </button> --}}
{{--                        <button class="btn btn-sm btn-danger text-white"--}}
{{--                                onclick="confirmBoxForPermanentDelete({{$tip['id'] ?? null}})" >--}}
{{--                            delete--}}
{{--                        </button>--}}
                    {{-- </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {{ $versions->links() }}
    </div> --}}


    
    @foreach($versions as $item)
        @foreach($item['versionDescriptions'] as $resource)
            <div class="modal fade" id="{{$resource['id']}}" aria-hidden="true"
                 aria-labelledby="{{$resource['id']}}"
                 tabindex="-1" role="dialog">
                <a class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style=" border-radius: 9px">
                        <div class="modal-body">
                            <label class="form-label fs-4 text-white">Library Resource</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                            <div class="mt-2">

                                <p class="text-white text-sm">
                                    {{$resource['description'] ?? null}}
                                </p>

                            </div>
                           

                            <div class="mt-2 text-white">

                                <p class="text-white text-sm">
                                    {{$resource['platform'] ?? null}}
                                </p>

                            </div>

                        </div>
                        <div>
                            <button wire:click="deleteResource({{ $resource['id'] }}, '{{ $resource['slug'] }}')"
                                    style="background-color: red; color: white"
                                    class="btn btn-sm float-end mt-2 mb-4 mx-3">Delete Resource
                                <span wire:loading wire:target="deleteResource" class="swal2-loader"
                                      style="font-size: 8px;">
                                </span>
                            </button>
                            <button wire:click="editResource({{ $resource['id'] }})"
                                    style="background-color: #f2661c; color: white"
                                    class="btn btn-sm float-end mt-2 mb-4 mx-3">Edit Resource
                                <span wire:loading wire:target="editResource" class="swal2-loader"
                                      style="font-size: 8px;">
                                </span>
                            </button>
                        </div>
                    </div>
                </a>
            </div>

        @endforeach
    @endforeach

    <!-- Coupon Discount -->
    @livewire('admin.version-control.create-version-control-form')
    @livewire('admin.version-control.create-version-control-description-form')
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>


    <script>
        function toggleCategoryBtn(id) {
            if ($('#category_edit_' + id).hasClass('d-flex')) {
                $('#category_edit_' + id).removeClass('d-flex justify-content-end').addClass('d-none');
            } else {
                $('#category_edit_' + id).removeClass('d-none').addClass('d-flex justify-content-end');
            }
        }



        
        function confirmDeleteVersion(category_id) {

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
    html: "<span style='color: white;'>Want to delete version  permanently!</span>",
    showCancelButton: true,
    confirmButtonText: 'Delete',
}).then((result) => {
    if (result.isConfirmed) {
        window.livewire.emit('deleteVersionPermanently', category_id);
    }
})
}
       
function confirmDeleteDescription(description_id) {

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
    html: "<span style='color: white;'>Want to delete Description permanently!</span>",
    showCancelButton: true,
    confirmButtonText: 'Delete',
}).then((result) => {
    if (result.isConfirmed) {
        window.livewire.emit('deleteDescriptionPermanently', description_id);
    }
})
}


    
    </script>


@endpush
