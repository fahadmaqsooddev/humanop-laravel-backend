<form wire:submit.prevent="updateCode">
    @include('layouts.message')
    <input type="hidden" wire:model.defer="select_code.id">
    <div class="row">
        <div class="col-12">
            <label class="form-label text-white">Name</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="name"
                       class="form-control text-white" type="text" wire:model.defer="select_code.name">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Public Name</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="public_name"
                       class="form-control text-white" type="text" wire:model.defer="select_code.public_name"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Code</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="code"
                       class="form-control text-white" type="text" wire:model.defer="select_code.code"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Type</label>
            <div class="input-group">
                <input style="background-color: #0f1534;" name="type"
                       class="form-control text-white" type="text" wire:model.defer="select_code.type"
                       placeholder="Alec">
            </div>
        </div>
        <div class="col-12 mt-4">
            <label class="form-label text-white">Text</label>
            <div class="input-group" wire:ignore>
                <textarea id="summernote" rows="10" cols="10" style="background-color: #0f1534;" name="text"
                          class="form-control text-white summernote" wire:model.defer="select_code.text">{{ $select_code['text'] }}</textarea>
            </div>
        </div>
    </div>
    <button class="btn btn-sm float-end mt-6 mb-0 text-white" style="background-color: #f2661c">Update code</button>
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
                @this.set('select_code.text', contents);
                }
            }
        });

        Livewire.on('contentUpdated', function(content) {
            summernoteElement.summernote('code', content);
        });
    });

</script>
