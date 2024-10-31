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

    </style>
@endpush
<div wire:ignore.self class="modal fade" id="dailyTipModel" tabindex="-1"
     role="dialog"
     aria-labelledby="dailyTipModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <div class="card-body pt-0">
                    <label class="form-label fs-4 text-white">Daily Tip</label>

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form wire:submit.prevent="updateTip">

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['sa', 'ma', 'jo', 'lu', 'ven', 'mer', 'so'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive ">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['de', 'dom', 'fe', 'gre', 'lun', 'nai', 'ne', 'pow', 'sp', 'tra', 'van', 'wil'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['g', 's', 'c'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['em', 'ins', 'int', 'mov'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-flush" style="border-collapse: separate">
                                            <thead class="thead-light">
                                            <tr>
                                                @foreach(['+', '-', 'pv', 'ep'] as $select_code)
                                                    <th class="text-center border border-white cursor-pointer {{ $code === $select_code ? 'bg-success' : '' }}"
                                                        wire:click="selectCode('{{ $select_code }}')">
                                                        {{ strtoupper($select_code) }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Title</label>
                                <div class="input-group">
                                    <input id="firstName" wire:model="title" name="title"
                                           class="form-control table-header-text" type="text">
                                    <input id="code" wire:model="code" name="code"
                                           class="form-control table-header-text" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Description</label>
                                <div class="input-group w-100" wire:ignore >
                             <textarea class="form-control table-header-text" id="editor" rows="5" cols="5"
                                    name="description"
                                    wire:model="description"></textarea>
                                </div>
                                @if($tip_id)
                                <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                        style="background-color: #f2661c">Update Tip
                                </button>

                                @else
                                    <button type="submit" class="btn btn-sm float-end mt-4 mb-4 text-white"
                                            style="background-color: #f2661c">Add Tip
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
    <script type="importmap">
    {
        "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
        }
    }
</script>

    <script type="module">
        import {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font,
            List
        } from 'ckeditor5';

        // Function to initialize CKEditor for a specific textarea by ID
        let editorInstance;
            const editorElement = document.getElementById('editor');
            if (editorElement && !editorElement.classList.contains('ck-editor')) { // Check if not already initialized
                ClassicEditor
                    .create(editorElement, {
                        plugins: [ Essentials, Paragraph, Bold, Italic, Font ,List ],
                        toolbar: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                            'bulletedList', 'numberedList'  // Add list options to toolbar
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
                $('#dailyTipModel').modal('hide');
            });
        });
    </script>



@endpush
