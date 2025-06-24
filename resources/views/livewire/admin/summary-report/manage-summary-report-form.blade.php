@push('css')
    <style>
        .note-editor.note-frame .note-editing-area .note-editable {
            background-color: #1b3a62 !important;
            color: #1b3a62 !important;
        }
        .note-editor.note-frame {
            border: 1px solid #1b3a62;
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
                       class="form-control input-form-style" type="text" wire:model.defer="select_code.name">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Public Name</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="public_name"
                       class="form-control input-form-style" type="text" wire:model.defer="select_code.public_name"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Code</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="code"
                       class="form-control input-form-style" type="text" wire:model.defer="select_code.code"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Type</label>
            <div class="input-group">
                <input style="color: #0f1534; background-color: #eaf3ff " name="type"
                       class="form-control input-form-style" type="text" wire:model.defer="select_code.type"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label">Text</label>
            <div class="input-group w-100" wire:ignore>
                <textarea class="form-control input-form-style table-header-text" id="summernote" rows="10" cols="10" name="text"
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
        // document.addEventListener('livewire:load', function () {
        //
        //     function initSummernote() {
        //         $('#summernote').summernote({
        //             height: 200,
        //             callbacks: {
        //                 onChange: function (contents, $editable) {
        //                 @this.set('select_code.text', contents);
        //                 }
        //             }
        //         });
        //     }
        //
        //     initSummernote();
        //
        //     Livewire.hook('message.processed', () => {
        //         initSummernote();
        //     });

            document.addEventListener('livewire:load', function () {
                $('#summernote').summernote({
                    height: 200,
                    callbacks: {
                        onChange: function(contents, $editable) {
                        @this.set('select_code.text', contents);
                        }
                    }
                });

                Livewire.hook('message.processed', (message, component) => {
                    // Re-init if needed after Livewire DOM updates
                    if (!$('#summernote').next().hasClass('note-editor')) {
                        $('#summernote').summernote({
                            height: 200,
                            callbacks: {
                                onChange: function(contents, $editable) {
                                @this.set('select_code.text', contents);
                                }
                            }
                        });
                    }
                });

        });
    </script>
@endpush
