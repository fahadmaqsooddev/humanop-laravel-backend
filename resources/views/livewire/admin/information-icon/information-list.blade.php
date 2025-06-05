<div>
    @push('css')
        <link rel="stylesheet" href="{{asset('js/rangerover/src/jquery.rangerover.css')}}">
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css">

        <style>
            .ck-editor__editable_inline {
                background-color: #eaf3ff; /* Example: Change this to your desired background color */
            }
            .ck-editor__editable{
                background-color: #eaf3ff !important;
            }
            .ck-editor{
                border-radius: 0 !important;
                width: 100% !important;
            }
            #ep_slider {
                width: 1000px;
                margin: 0 auto;
            }
            #pv_slider {
                width: 1000px;
                margin: 0 auto;
            }
            @media (min-width: 992px) and (max-width: 1200px) {
                #ep_slider {
                    width: 700px;
                    margin: 0 auto;
                }
                #pv_slider {
                    width: 700px;
                    margin: 0 auto;
                }
                #interval_of_life{
                    width: 50% !important;
                }
            }

            @media (min-width: 500px) and (max-width: 992px) {
                #ep_slider {
                    width: 400px;
                    margin: 0 auto;
                }
                #pv_slider {
                    width: 400px;
                    margin: 0 auto;
                }
                #interval_of_life{
                    width: 100% !important;
                }
            }

            @media (max-width: 500px)  {
                #ep_slider {
                    width: 300px;
                    margin: 0 auto;
                }
                #interval_of_life{
                    width: 100% !important;
                }
                #pv_slider {
                    width: 300px;
                    margin: 0 auto;
                }
            }

            .card{
                background-color: #eaf3ff !important;
            }

            .ck.ck-balloon-panel {
                z-index: 1050 !important;
            }

            .ck > p > a{
                color: blue !important;
            }

            #html-formated-text-span > p > a{
                color: blue !important;
            }
        </style>
    @endpush
    <div class="table-responsive table-orange-color">
        @include('layouts.message')
        <table class="table table-flush" id="datatable-search">
            <thead class="thead-light">
            <tr class="table-text-color">
                <th>name</th>
                <th>Information</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($iconInformations as $info)
                <tr class="table-text-color">
                    <td class="text-md font-weight-normal">{{$info['name'] }} </td>
                    <td class="text-md font-weight-normal">
                        @if($info['information'] && strlen($info['information']) > 40)
                            {!! substr($info['information'], 0, 40) !!}
                            &nbsp;&nbsp;<a data-bs-toggle="modal"
                                           data-bs-target="#viewQueryModal{{$info['id']}}"
                                           style="color: #1b3a62; cursor: pointer;"
                                           class="mt-2 mb-0">
                                view more...
                            </a>
                        @else
                            {!! $info['information'] ?? null !!}
                        @endif </td>
                    <td>
                        <button class="btn btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#informationIconModel" style="background-color: #1b3a62"
                                wire:click="updateEditModal({{$info['id']}},`{{$info['name']}}`,`{{$info['information']}}`)">
                            update
                        </button>
                    </td>
                </tr>
                @if($info['information'] && strlen($info['information']) > 40)
                    <div wire:ignore.self class="modal fade" id="viewQueryModal{{ $info['id'] }}" tabindex="-1"
                         role="dialog"
                         aria-labelledby="viewQueryModal{{ $info['id'] }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body" style=" border-radius: 9px">
                                    <form wire:submit.prevent="">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="button" class="close modal-close-btn"
                                                            data-bs-dismiss="modal"
                                                            aria-label="Close"
                                                            id="close-query-view-modal-{{ $info['id'] }}">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>

                                                    <label class="form-label fs-6 "
                                                           style="font-size: 24px !important;font-weight: 800 !important;color: #1b3a62;"><strong>{{$info['name'] }}
                                                            :</strong></label>
                                                    <span class="mt-3" id="html-formated-text-span"
                                                          style="color: #1b3a62;font-size: 20px;font-weight: 800">{!! $info['information'] ?? null !!}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            </tbody>
        </table>
    </div>


    <div wire:ignore.self class="modal fade" id="informationIconModel"
         role="dialog" data-bs-focus="false"
         aria-labelledby="informationIconModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Create Information Icon</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-info-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <form wire:submit.prevent="updateInfo">
                            <div class="card-body pt-0">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Name</label>
                                        <div class="form-group">
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   class="form-control"
                                                   type="text" name="limit"
                                                   wire:model="name" placeholder="icon name" >
                                            @error('name')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Information</label>
                                        <div class="form-group" style="color: #1b3a62" wire:ignore>
                                            <textarea style="background-color: #eaf3ff;" class="form-control"
                                                      rows="5" cols="5" name="information" id="editor"
                                                      wire:model="information"></textarea>
                                            @error('information')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62 ">Update Information
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('javascript')
    <script type="text/javascript" src="{{asset('js/rangerover/src/jquery.rangerover.js')}}"></script>
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
                    plugins: [ Essentials, Paragraph, Bold, Italic, Font ,List, Link, AutoLink ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                        'bulletedList', 'numberedList', 'link' // Add list options to toolbar
                    ],
                    link : {

                    }
                })
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                    @this.set('information', editor.getData());
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

    </script>

@endpush
@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>

        document.addEventListener('livewire:load', function () {
            Livewire.on('closeUpdateModal', () => {
                $('#informationIconModel').modal('hide');
            });
        });

        window.Livewire.on('closeInfoModal', function (e) {
            $('#close-info-modal-button').click();
        })

    </script>

@endpush
