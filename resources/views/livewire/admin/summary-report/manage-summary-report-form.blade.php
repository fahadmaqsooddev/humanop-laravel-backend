@push('css')
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
<form wire:submit.prevent="updateSummaryReport">
    @include('layouts.message')
    <input type="hidden" wire:model.defer="select_code.id">
    <div class="row">
        <div class="col-12">
            <label class="form-label">Name</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="name"
                       class="form-control" type="text" wire:model.defer="select_code.name">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Public Name</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="public_name"
                       class="form-control" type="text" wire:model.defer="select_code.public_name"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Code</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="code"
                       class="form-control" type="text" wire:model.defer="select_code.code"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Type</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="type"
                       class="form-control" type="text" wire:model.defer="select_code.type"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Text</label>
            <div class="input-group w-100" wire:ignore>
                <textarea class="form-control table-header-text" id="summernote" rows="10" cols="10" name="text"
                          wire:model.defer="select_code.text">{{ $select_code['text'] }}</textarea>
            </div>
        </div>
    </div>
    <button class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #1B3A62 ">Update Summary</button>
</form>

@push('javascript')
    <!-- jQuery & Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script>
        document.addEventListener('livewire:load', function () {

            function initSummernote() {
                $('#summernote').summernote({
                    height: 200,
                    callbacks: {
                        onChange: function (contents, $editable) {
                        @this.set('select_code.text', contents);
                        }
                    }
                });
            }

            initSummernote();

            Livewire.hook('message.processed', () => {
                initSummernote();
            });

        });
    </script>
@endpush
