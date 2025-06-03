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
<div>

    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>#</th>
                <th>Priority</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($optimizationPlans as $plan)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$plan['id']}} </td>
                    <td class="text-md font-weight-normal">{{$plan['priority']}} </td>
                    <td>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal"
                                wire:click="updateOptimizationModal({{ $plan['id']}},`{{$plan['condition']}}`,`{{$plan['priority']}}`,`{{$plan['content']}}`)"
                                data-bs-target="#optimizationPlanModel" style="background-color: #1b3a62;">
                            update
                        </button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {{ $optimizationPlans->links() }}
    </div>

    <div wire:ignore.self class="modal fade" id="optimizationPlanModel" tabindex="-1"
         role="dialog" data-bs-focus="false"
         aria-labelledby="optimizationPlanModel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Optimization Plan</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-optimization-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form wire:submit.prevent="updateOptimizationPlan">
                            <div class="row mt-4">
                                <div class="col-12">

                                    <label class="form-label" style="color: #1b3a62">Priority</label>
                                    <div class="input-group">
                                        <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                               class="form-control"
                                               type="text" disabled
                                               wire:model="priority" placeholder="icon name">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label class="form-label" style="color: #1b3a62">Condition</label>
                                    <div class="input-group">
                                        <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                               wire:model="condition" disabled
                                               class="form-control table-header-text" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label class="form-label" style="color: #1b3a62">Description</label>
                                    <div class="input-group w-100" wire:ignore style="color: #1b3a62">
                                        <textarea class="form-control table-header-text" id="editor" rows="10" cols="10"
                                       name="content"
                                       wire:model="content"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                    style="background-color: #1b3a62 ">Update Optimization plan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js')

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

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('closeUpdateModal', () => {
                $('#informationIconModel').modal('hide');
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
                    @this.set('content', editor.getData());
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

        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                // Close the modal
                $('#dailyTipModel').modal('hide');
            });
        });
    </script>

    <script>
        window.Livewire.on('closeInfoModal', function (e) {
            $('#close-optimization-modal-button').click();
        })

    </script>

@endpush
