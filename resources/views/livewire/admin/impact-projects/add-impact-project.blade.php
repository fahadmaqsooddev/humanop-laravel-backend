@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #eaf3ff; /* Optional: you can adjust if needed */
        }

        /* Consistent styling for all inputs, textareas, selects */
        .input-form-style, 
        textarea.input-form-style, 
        select.input-form-style {
            background-color: #1b3a62 !important;  /* Dark blue background */
            color: white !important;                /* White text */
            border-radius: 6px !important;         /* Rounded corners */
            padding: 0.375rem 0.75rem !important;  /* Padding */
            border: none !important;                /* No border */
            box-shadow: none !important;            /* No shadow */
        }

        /* Placeholder text color */
        .input-form-style::placeholder,
        textarea.input-form-style::placeholder {
            color: #d1d7e0 !important; /* Light placeholder */
        }

        /* Dropdown option styling */
        select.input-form-style option {
            background-color: #1b3a62;  
            color: white;
        }

        /* Modal header close button fix */
        .modal-header .btn-close {
            filter: invert(1);
        }
    </style>
@endpush

<div>
    <!-- Modal trigger button -->
    <div class="d-flex justify-content-end mt-0">
       <a data-bs-toggle="modal" data-bs-target="#addImpactProjectModal"
        class="btn btn-sm float-end mt-0 createForm me-3"
        style="background-color: #1B3A62; color: white;">
        Add Impact Project
        </a>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="addImpactProjectModal" tabindex="-1" aria-labelledby="addImpactProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1b3a62;">
                    <h5 class="modal-title text-white" id="addImpactProjectModalLabel">Add Impact Project</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" style="background-color: #eaf3ff;">
                    <form wire:submit.prevent="createCode">
                        @include('layouts.message')

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" style="color:black">Title</label>
                                <input type="text" class="form-control input-form-style" wire:model.defer="title" placeholder="Project title">
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label" style="color:black">Description</label>
                                <textarea class="form-control input-form-style" rows="4" wire:model.defer="description" placeholder="Project description"></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label" style="color:black">HP Required</label>
                                <input type="number" class="form-control input-form-style" wire:model.defer="hp_required" placeholder="10000">
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label" style="color:black">Verification Text</label>
                                <textarea class="form-control input-form-style" rows="3" wire:model.defer="verification_text" placeholder="Optional verification text"></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label" style="color:black">Status</label>
                                <select class="form-select input-form-style" wire:model.defer="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                           
                        </div>

                        <button type="submit" class="btn btn-sm float-end mt-4 mb-0 text-white" style="background-color: #1b3a62;">
                            Create Project
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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

    document.addEventListener('livewire:load', function () {
        let editorInstance;

        Livewire.hook('message.processed', (message, component) => {
            const editorElement = document.getElementById('neditor');
            if (editorElement && !editorElement.classList.contains('ck-editor')) {
                ClassicEditor.create(editorElement, {
                    plugins: [Essentials, Paragraph, Bold, Italic, Font, List, Link, AutoLink],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', 'link'
                    ]
                }).then(editor => {
                    editor.model.document.on('change:data', () => {
                        @this.set('text', editor.getData());
                    });
                    editorInstance = editor;
                }).catch(error => console.error(error));
            }
        });
    });

     Livewire.on('impactProjectAdded', () => {
    
        Livewire.emit('refreshImpactProjects');
        $('#addImpactProjectModal').modal('hide');
    });
</script>
@endpush