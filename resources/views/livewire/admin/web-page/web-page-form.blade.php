<div wire:ignore.self class="modal fade" id="page-{{$page['id']}}" tabindex="-1" aria-hidden="true"
     role="dialog" aria-labelledby="page-Label{{$page['id']}}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body"
                 style="background-color: #0f1535; border-radius: 9px">
                <form wire:submit.prevent="updateWebPage">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-white">Web Page</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                @include('layouts.message')
                                <div class="form-group">
                                    <label class="form-label text-white">Name</label>
                                    <input
                                        style="background-color: #0f1534;"
                                        class="form-control text-white"
                                        type="text" name="name"
                                        wire:model.defer="page.name"
                                        placeholder="name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-white">Title</label>
                                    <input
                                        style="background-color: #0f1534;"
                                        class="form-control text-white"
                                        type="text" name="title"
                                        wire:model.defer="page.title"
                                        placeholder="name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-white">Meta Key</label>
                                    <input
                                        style="background-color: #0f1534;"
                                        class="form-control text-white"
                                        type="text" name="meta_key"
                                        wire:model.defer="page.meta_key"
                                        placeholder="name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-white">Meta Description</label>
                                    <input
                                        style="background-color: #0f1534;"
                                        class="form-control text-white"
                                        type="text" name="meta_description"
                                        wire:model.defer="page.meta_description"
                                        placeholder="name">
                                </div>
                                <div class="form-group" wire:ignore>
                                    <label class="form-label text-white">Text</label>
                                    <textarea cols="10" rows="10" style="background-color: #0f1534;"
                                              class="form-control text-white summernote" id="summernote" type="text" name="text"
                                              wire:model.defer="page.text" placeholder="name">{{$page['text']}}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                                class="btn updateBtn btn-sm float-end mt-4 mb-0">
                            Update Web Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
