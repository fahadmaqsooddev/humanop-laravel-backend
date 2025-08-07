@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #1b3a62; /* Example: Change this to your desired background color */
        }

        .ck-editor__editable {
            background-color: #1b3a62 !important;
        }

        .ck-editor {
            border-radius: 0 !important;
            width: 100% !important;
        }

        .card {
            background-color: white !important;
        }

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a {
            color: blue !important;
        }

    </style>
@endpush
<form wire:submit.prevent="updateResultVideo">
    @include('layouts.message')
    <input type="hidden" wire:model.defer="select_code.id">
    <div class="row">
        <div class="col-12 mt-4">
            <label class="form-label" style="color: #1b3a62">Public Name</label>
            <div class="input-group">
                <input name="public_name"
                       class="form-control input-form-style"
                       type="text"
                       wire:model.defer="select_video.public_name"
                       placeholder="Alec">
            </div>
        </div>

        <div class="col-12 mt-4">
            <label class="form-label" style="color: #1b3a62">Result Video</label>
            <div class="input-group w-100">
                <input
                    type="file"
                    accept="video/*"
                    class="form-control input-form-style"
                    wire:model="select_video.video_file"> <!-- Remove defer to allow immediate loading trigger -->
            </div>

            <!-- Uploading Spinner -->
            <span wire:loading.flex wire:target="select_video.video_file">
                <div class="d-flex align-items-center mt-2">
                    <div class="spinner-border" role="status" style="color: #1b3a62 !important;"></div>
                    <span class="ms-2" style="color: #1b3a62;">Uploading...</span>
                </div>
            </span>
        </div>
        @if (!empty($select_video['video']))
            <label class="form-label mt-4" style="color: #1b3a62">Preview</label>
            <div class="col-12 mt-4">
                <video width="50%" height="350" controls>
                    <source src="{{ $select_video['video_upload_id'] ? $select_video['video_upload_url']['path'] : asset('assets/video/' . $select_video['video']) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        @endif

    </div>

    <button class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #1b3a62">Update Result Video</button>
</form>

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
<script>
    window.addEventListener('pageReload', function () {
        location.reload(); // Reloads the full page
    });
</script>

<script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../assets/js/plugins/sweetalert.min.js"></script>
<script type="text/javascript">
    document.addEventListener('livewire:load', function () {
        const summernoteElement = $('#editor');

        summernoteElement.summernote({
            height: 300,
            callbacks: {
                onChange: function (contents, $editable) {
                @this.set('select_code.text', contents)
                    ;
                }
            }
        });

        Livewire.on('contentUpdated', function (content) {
            summernoteElement.summernote('code', content);
        });
    });

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
    let editorInstance;
    const editorElement = document.getElementById('editor');
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
                @this.set('select_code.text', editor.getData())
                    ;
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
