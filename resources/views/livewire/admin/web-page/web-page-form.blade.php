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

        .ck > p > a{
            color: blue !important;
        }

    </style>
@endpush
<form wire:submit.prevent="updateWebPage">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                @include('layouts.message')
                <div class="form-group">
                    <label class="form-label text-white">Name</label>
                    <input
                        style="background-color: #eaf3ff;"
                        class="form-control"
                        type="text" name="name"
                        wire:model.defer="page.name"
                        placeholder="name">
                </div>
                <div class="form-group">
                    <label class="form-label text-white">Title</label>
                    <input
                        style="background-color: #eaf3ff;"
                        class="form-control"
                        type="text" name="title"
                        wire:model.defer="page.title"
                        placeholder="name">
                </div>
                <div class="form-group">
                    <label class="form-label text-white">Meta Key</label>
                    <input
                        style="background-color: #eaf3ff;"
                        class="form-control"
                        type="text" name="meta_key"
                        wire:model.defer="page.meta_key"
                        placeholder="name">
                </div>
                <div class="form-group">
                    <label class="form-label text-white">Meta Description</label>
                    <input
                        style="background-color: #eaf3ff;"
                        class="form-control"
                        type="text" name="meta_description"
                        wire:model.defer="page.meta_description"
                        placeholder="name">
                </div>
                {{-- <div class="form-group" wire:ignore>
                    <label class="form-label text-white">Text</label>
                    <textarea cols="10" rows="10" style="background-color: #eaf3ff;"
                              class="form-control text-white summernote" id="summernote" type="text" name="text"
                              wire:model.defer="page.text" placeholder="name">{{$page['text']}}</textarea>
                </div> --}}

                <div class="form-group" wire:ignore>
                    <label class="form-label text-white">Text</label>
                   <textarea class="form-control text-white summernote" id="neditor"
                   style="background-color: #eaf3ff;"
                   rows="10" cols="10"
                   name="text"
                   wire:model.defer="page.text">{{$page['text']}}</textarea>

                </div>

            </div>
        </div>
        <button type="submit"
                class="btn updateBtn btn-sm float-end mt-4 mb-0">
            Update Web Page
        </button>
    </div>
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

<script type="text/javascript">
    document.addEventListener('livewire:load', function () {
        const summernoteElement = $('.summernote');

        summernoteElement.summernote({
            height: 300,
            callbacks: {
                onChange: function(contents, $editable) {
                @this.set('page.text', contents);
                }
            }
        });

        Livewire.on('contentUpdated', function(content) {
            summernoteElement.summernote('code', content);
        });
    });

</script>

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
    const editorElement = document.getElementById('neditor');
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
                @this.set('page.text', editor.getData());
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

