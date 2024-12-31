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
<form wire:submit.prevent="updateCode">
    @include('layouts.message')
    <input type="hidden" wire:model.defer="select_code.id">
    <div class="row">
        <div class="col-12">
            <label class="form-label text-white">Name</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="name"
                       class="form-control text-white" type="text" wire:model.defer="select_code.name">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Public Name</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="public_name"
                       class="form-control text-white" type="text" wire:model.defer="select_code.public_name"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Code</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="code"
                       class="form-control text-white" type="text" wire:model.defer="select_code.code"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Type</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="type"
                       class="form-control text-white" type="text" wire:model.defer="select_code.type"
                       placeholder="Alec">
            </div>
        </div>
        {{-- <div class="row"> --}}
        <div class="col-12 mt-4">
            <label class="form-label text-white">Text</label>
            <div class="input-group w-100" wire:ignore>
                {{-- <textarea id="summernote" rows="10" cols="10" style="background-color: #0f1534;" name="text"
                          class="form-control text-white summernote" wire:model.defer="select_code.text">{{ $select_code['text'] }}</textarea> --}}
                          
                                       <textarea class="form-control table-header-text" id="editor" rows="10" cols="10"
                                       name="text"
                                       wire:model.defer="select_code.text">{{ $select_code['text'] }}</textarea>
            </div>
        </div>
    {{-- </div> --}}
    </div>
    <button class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #f2661c">Update code</button>
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
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
<script type="text/javascript">
    document.addEventListener('livewire:load', function () {
        const summernoteElement = $('#editor');

        summernoteElement.summernote({
            height: 300,
            callbacks: {
                onChange: function(contents, $editable) {
                @this.set('select_code.text', contents);
                }
            }
        });

        Livewire.on('contentUpdated', function(content) {
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
                    @this.set('select_code.text', editor.getData());
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
