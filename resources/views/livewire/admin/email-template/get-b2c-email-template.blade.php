


@push('css')
    <style>

        .no-select {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        /* Custom CKEditor editable area styles */
        .ck-editor__editable {
            background-color: #eaf3ff !important;
            color: #1b3a62 !important;
            min-height: 200px;
        }
    </style>
    </style>

@endpush
<div>

    <div class="p-2">
        {{--        <div class="input-group ms-md-4 pe-md-4 d-flex justify-content-end">--}}
        {{--            <input type="email" wire:model="searched_email"--}}
        {{--                   style="border-radius: 5px; width: 30%; padding: 5px;"--}}
        {{--                   class="table-orange-color search-bar" placeholder="Search Email">--}}
        {{--        </div>--}}
    </div>

    {{--    @if(count($selectedItems) > 0)--}}
    {{--        <div class=" d-flex justify-content-end ms-md-4 pe-md-4">--}}
    {{--            <button type="button" onclick="deleteBulkLink()" class="btn btn-danger">All Delete Links</button>--}}
    {{--        </div>--}}
    {{--    @endif--}}

    <div class="table-responsive w-100 pt-4 table-orange-color">

        @include('layouts.message')
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="table-text-color text-center">
                <th>Id</th>
                <th>Type</th>

                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($b2cTemplates as $index => $template)
                <tr class="table-text-color text-center">

                    <td class="text-md font-weight-normal">{{$index + 1}}</td>
                    <td class="text-md font-weight-normal">{{$template->name}} </td>
                    <td>

                        <button class="btn mb-0 text-white" wire:click="EditTemplate({{ $template->id }})"
                                data-bs-toggle="modal"
                                data-bs-target="#b2cUpdateEmailTemplate"
                                style="background-color: #1b3a62;border-radius: 0px 5px 5px 0px">Edit
                        </button>


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


{{--                {{$b2cTemplates->links()}}--}}

    </div>
    <div wire:ignore.self class="modal fade" id="b2cUpdateEmailTemplate" tabindex="-1"
         role="dialog"
         aria-labelledby="b2cUpdateEmailTemplate" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Update B2C Template</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="UpdateTemplate">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="">title</label>
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   class="form-control text-white"
                                                   type="text" wire:model="title" name="title" placeholder="icon name">
                                            @error('title')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror

                                            <label class="">subject</label>
                                            <input style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                   class="form-control text-white"
                                                   type="text" wire:model="subject" name="subject" placeholder="icon name">
                                            @error('subject')
                                            <span class="text-sm text-danger">{{$message}}</span>
                                            @enderror



                                            <div class="form-group mt-4" wire:ignore>
                                                <label class="">Body</label>
                                                <textarea class="form-control input-form-style" style="background-color: #eaf3ff;color: #1b3a62 !important"
                                                          id="template_body_editor"
                                                          name="body" rows="10">
                                            {{ $body }}
                                                </textarea>
                                            </div>

                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"
                                                    style="background-color: #1b3a62 ">Update Template
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')

    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>

    <script type="module">

        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                // Close the modal
                $('#b2cUpdateEmailTemplate').modal('hide');


            });

        });
    </script>

    <script>
        let  bodyEditor;

        document.addEventListener('livewire:load', function () {

            ClassicEditor
                .create(document.querySelector('#template_body_editor'))
                .then(editor => {
                    bodyEditor = editor;
                    editor.model.document.on('change:data', () => {
                    @this.set('body', editor.getData());
                    });
                });

            // Listen for event from Livewire to update editor content
            window.addEventListener('update-editors', event => {

                if (bodyEditor && event.detail.body !== undefined) {
                    bodyEditor.setData(event.detail.body);
                }
            });
        });
    </script>

@endpush

