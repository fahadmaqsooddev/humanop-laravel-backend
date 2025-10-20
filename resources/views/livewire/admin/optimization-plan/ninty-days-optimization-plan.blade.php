@push('css')
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">
    <style>
        .ck-editor__editable_inline {
            background-color: #1b3a62; /* Example: Change this to your desired background color */
            color: white;
        }

        .ck-editor__editable {
            background-color: #1b3a62 !important;
            color: white;
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
<div>

    <div class="table-responsive table-orange-color">
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
                                wire:click="updateOptimizationModal({{ $plan['id']}},`{{$plan['condition']}}`,`{{$plan['priority']}}`,`{{$plan['ninty_days_plan']}}`,`{{$plan['day1_30']}}`,`{{$plan['day31_60']}}`,`{{$plan['day61_90']}}`)"
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
                        @include('layouts.message')
                        <form wire:submit.prevent="updateOptimizationPlan">
                            <div class="row mt-4">
                                <div class="col-12">

                                    <label class="form-label" style="color: #1b3a62">Priority</label>
                                    <div class="input-group">
                                        <input
                                            class="form-control input-form-style"
                                            type="text" disabled
                                            wire:model="priority" placeholder="icon name">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label class="form-label" style="color: #1b3a62">Condition</label>
                                    <div class="input-group">
                                        <input
                                            wire:model="condition" disabled
                                            class="form-control input-form-style table-header-text" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" wire:ignore>
                                <label>Optimization Plan Info</label>
                                <textarea id="editor_ninty_days_plan"></textarea>
                            </div>

                            <div class="form-group mt-3" wire:ignore>
                                <label>Day 1 - 30</label>
                                <textarea id="editor_day1_30"></textarea>
                            </div>

                            <div class="form-group mt-3" wire:ignore>
                                <label>Day 31 - 60</label>
                                <textarea id="editor_day31_60"></textarea>
                            </div>

                            <div class="form-group mt-3" wire:ignore>
                                <label>Day 61 - 90</label>
                                <textarea id="editor_day61_90"></textarea>
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

        document.addEventListener('livewire:load', () => {
            const editors = {};
            const editorConfigs = [
                { id: 'editor_ninty_days_plan', property: 'ninty_days_plan' },
                { id: 'editor_day1_30', property: 'day1_30' },
                { id: 'editor_day31_60', property: 'day31_60' },
                { id: 'editor_day61_90', property: 'day61_90' },
            ];

            // ✅ Create editors
            function initEditors() {
                editorConfigs.forEach(({ id, property }) => {
                    const el = document.getElementById(id);
                    if (el && !editors[property]) {
                        ClassicEditor
                            .create(el, {
                                plugins: [Essentials, Paragraph, Bold, Italic, Font, List, Link, AutoLink],
                                toolbar: [
                                    'undo', 'redo', '|', 'bold', 'italic', '|',
                                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                                    'bulletedList', 'numberedList', 'link'
                                ]
                            })
                            .then(editor => {
                                editors[property] = editor;

                                // 🔁 CKEditor → Livewire
                                editor.model.document.on('change:data', () => {
                                @this.set(property, editor.getData());
                                });
                            })
                            .catch(error => console.error('CKEditor init failed:', error));
                    }
                });
            }

            initEditors();

            // ✅ Load existing HTML data
            Livewire.on('loadEditorsData', data => {
                for (const key in data) {
                    if (editors[key]) {
                        editors[key].setData(data[key] || '');
                    }
                }
            });

            // ✅ If Livewire re-renders modal, re-init CKEditor
            Livewire.hook('message.processed', () => {
                initEditors();
            });
        });
    </script>

    <script>
        window.Livewire.on('closeInfoModal', function (e) {
            $('#close-optimization-modal-button').click();
        })

    </script>

@endpush
