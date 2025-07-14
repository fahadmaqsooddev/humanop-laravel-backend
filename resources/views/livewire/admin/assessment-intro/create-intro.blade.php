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
<form wire:submit.prevent="createIntro">
    @include('layouts.message')
    <div class="row">
        <div class="col-12">
            <label class="form-label text-white">Name</label>
            <div class="input-group">
                <input name="name"
                       class="form-control input-form-style" type="text" wire:model="name">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Public Name</label>
            <div class="input-group">
                <input name="public_name"
                       class="form-control input-form-style" type="text" wire:model="public_name"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Code</label>
            <div class="input-group">
                <input name="code"
                       class="form-control input-form-style" type="text" wire:model="code"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Number</label>
            <div class="input-group">
                <input name="number"
                       class="form-control input-form-style" type="text" wire:model="number"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Type</label>
            <div class="input-group">
                <input name="type"
                       class="form-control input-form-style" type="text" wire:model="type"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Text</label>
            <div class="input-group w-100" wire:ignore>
                <textarea class="form-control table-header-text" id="neditor" rows="10" cols="10"
                          name="text"
                          wire:model="text"></textarea>
            </div>
        </div>
    </div>
    <button class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #1b3a62">Create code</button>
</form>

@push('javascript')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            $('#neditor').summernote({
                height: 200,
                placeholder: 'Enter your text here...',
                callbacks: {
                    onChange: function (contents, $editable) {
                    @this.set('text', contents);
                    }
                }
            });

            Livewire.hook('message.processed', (message, component) => {
                if (!$('#neditor').next().hasClass('note-editor')) {
                    $('#neditor').summernote({
                        height: 200,
                        placeholder: 'Enter your text here...',
                        callbacks: {
                            onChange: function (contents, $editable) {
                            @this.set('text', contents);
                            }
                        }
                    });
                }
            });

            $('.createForm').on('click', function () {
                $('#neditor').summernote('reset');
            });
        });
    </script>

@endpush
