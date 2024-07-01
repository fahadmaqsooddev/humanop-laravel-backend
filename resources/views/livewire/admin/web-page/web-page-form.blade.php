<form wire:submit.prevent="updateWebPage">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-12">
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

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script type="text/javascript">
    document.addEventListener('livewire:load', function () {
        const summernoteElement = $('#summernote');

        summernoteElement.summernote({
            height: 300,
            callbacks: {
                onChange: function(contents, $editable) {
                @this.set('page.text', contents);
                }
            }
        });

        Livewire.on('contentUpdated', function(content) {
            summernoteElement.summernote('code', content);
        });
    });

</script>
