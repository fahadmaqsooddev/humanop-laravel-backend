@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #eaf3ff; /* Example: Change this to your desired background color */
        }

        .ck-editor__editable {
            background-color: #eaf3ff !important;
        }

        .ck-editor {
            border-radius: 0 !important;
            width: 100% !important;
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

    </style>
@endpush
<div wire:ignore.self class="modal fade" id="descriptionModel" tabindex="-1"
     role="dialog" data-bs-focus="false"
     aria-labelledby="dailyTipModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <div class="card-body pt-0">
                    @if($description_id)
                        <label class="form-label fs-4" style="color: #1b3a62">Edit Version Description</label>

                    @else
                        <label class="form-label fs-4" style="color: #1b3a62">Add Version Description </label>

                    @endif

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form wire:submit.prevent="updateDescription">
                        <div class="row mt-4">
                            <div class="col-12">

                                <div class="form-group mt-4">

                                    <label class="form-label text-white">Select Version</label>
                                    <select style="background-color: #eaf3ff;" class="form-control"
                                            wire:model.defer="version_id" placeholder="Select category">

                                        <option>Select a category</option>

                                        @foreach($versions as $ver)
                                            <option value="{{$ver->id}}">{{$ver->version}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12">

                                    <label class="form-label text-white">Select Type</label>
                                    <div class="d-flex gap-4 align-items-center mt-2">

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input bg-white border-white" type="checkbox"
                                                   wire:model="platform" value="App" id="appPlatform">
                                            <label class="form-check-label text-white fw-semibold" for="appPlatform">
                                                App
                                            </label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input bg-white border-white" type="checkbox"
                                                   wire:model="platform" value="Web" id="webPlatform">
                                            <label class="form-check-label text-white fw-semibold" for="webPlatform">
                                                Web
                                            </label>
                                        </div>
                                    </div>


                                    <label class="form-label text-white">Description</label>
                                    <div class="input-group w-100" wire:ignore>
                                        <textarea class="form-control table-header-text" style="background-color: #eaf3ff;color:#1b3a62;" rows="5" cols="5" name="description" wire:model="description"></textarea>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label for="version_heading" class="form-label text-white">Select Version
                                            Heading</label>
                                        <select name="version_heading" wire:model='version_heading' id="version_heading"
                                                class="form-select">
                                            <option value="">Select Option</option>
                                            <option value="0">Issue Fixed</option>
                                            <option value="1">New Feature</option>
                                        </select>
                                    </div>


                                    @if($description_id)
                                        <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                                style="background-color: #1b3a62">Update Description
                                        </button>

                                    @else
                                        <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                                style="background-color: #1b3a62">Add Description
                                        </button>
                                    @endif
                                </div>

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
        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                // Close the modal
                $('#descriptionModel').modal('hide');
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $('#descriptionModel').on('hidden.bs.modal', function () {
                Livewire.emit('emptyVersionControlValues');
            });
        });
    </script>

@endpush
