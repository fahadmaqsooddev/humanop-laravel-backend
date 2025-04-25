@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #0f1534; /* Example: Change this to your desired background color */
        }
        .ck-editor__editable{
            background-color: #0f1534 !important;
        }
        .ck-editor{
            border-radius: 0 !important;
            width: 100% !important;
        }

        .card{
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
<div wire:ignore.self class="modal fade" id="descriptionModel" tabindex="-1"
     role="dialog" data-bs-focus="false"
     aria-labelledby="dailyTipModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <div class="card-body pt-0">
                    @if($description_id)
                    <label class="form-label fs-4 text-white">Edit Version Description</label>


                                @else
                                <label class="form-label fs-4 text-white">Add Version Description </label>

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
                                    <select style="background-color: #0f1534;" class="form-control text-white"
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
                                        <input class="form-check-input bg-white border-white" type="checkbox" wire:model="platform" value="App" id="appPlatform">
                                        <label class="form-check-label text-white fw-semibold" for="appPlatform">
                                            App
                                        </label>
                                    </div>
                                
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input bg-white border-white" type="checkbox" wire:model="platform" value="Web" id="webPlatform">
                                        <label class="form-check-label text-white fw-semibold" for="webPlatform">
                                            Web
                                        </label>
                                    </div>
                                </div>
                                
                                
                                <label class="form-label text-white">Description</label>
                                <div class="input-group w-100" wire:ignore >
                             <textarea class="form-control table-header-text" style="background-color: #0f1534;color:white;"  rows="5" cols="5"
                                       name="description"
                                       wire:model="description"></textarea>
                                </div>


                                @if($description_id)
                                    <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                            style="background-color: #f2661c">Update Description
                                    </button>

                                @else
                                    <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                            style="background-color: #f2661c">Add Description
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
    {{-- <script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
        }
    }
</script> --}}

    {{-- <script type="module">
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
        const editorElement = document.getElementById('editor');
        if (editorElement && !editorElement.classList.contains('ck-editor')) { // Check if not already initialized
            ClassicEditor
                .create(editorElement, {
                    plugins: [ Essentials, Paragraph, Bold, Italic, Font ,List, Link, AutoLink ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', 'link'  // Add list options to toolbar
                    ]
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                    @this.set('description', editor.getData());
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

        $('.createForm').on('click', function() {
            if (editorInstance) {
                editorInstance.setData('');
            }
        });
        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                // Close the modal
                $('#versionModel').modal('hide');
            });
        });
    </script> --}}


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
