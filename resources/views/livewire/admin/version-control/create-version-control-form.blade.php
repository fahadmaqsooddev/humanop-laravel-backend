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

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a{
            color: blue !important;
        }

    </style>
@endpush
<div class="row mt-4 container-fluid">
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif



@if (!empty($versionId))
    
<h1>Edit Version control</h1>
@else
<h1>Create Version</h1>
@endif

<div style="background-color: #0f1534">
<form wire:submit.prevent="storeVersionAndDescription" style="margin-left: 10%">
    <!-- Version Field -->
    <div class="row mt-4">
        <div class="col-12">
            <label class="form-label text-white">Version</label>
            <input style="background-color: #0f1534;color:white;" wire:model="version"
                   class="form-control table-header-text" type="text">
                   @error('version') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <label class="form-label text-white">Note</label>
            <textarea class="form-control table-header-text"
                      style="background-color: #0f1534;color:white;"
                      rows="2" wire:model="note"></textarea>
                      @error('note') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
    </div>
    
    <div class="text-end mt-3">
        <button type="button" class="btn text-white fw-bolder" style="background-color: #f2661c" wire:click="addVersionField">
            <span style="font-weight: bolder;font-size:1rem;">+</span>
        </button>
    </div>
   
    @foreach ($versionDetails as $index => $detail)
<div class="row mb-3">
<!-- Version Type Checkboxes -->
<div class="col-md-12 mb-3">
<label class="text-white mb-1">Select Type</label><br>
<div class="form-check form-check-inline">
<input class="form-check-input"
   type="checkbox"
   wire:model="versionDetails.{{ $index }}.type"
   value="Web"
   id="web_{{ $index }}">
<label class="form-check-label text-white" for="web_{{ $index }}">Web</label>
</div>
<div class="form-check form-check-inline">
<input class="form-check-input"
   type="checkbox"
   wire:model="versionDetails.{{ $index }}.type"
   value="App"
   id="app_{{ $index }}">
<label class="form-check-label text-white" for="app_{{ $index }}">App</label>
</div>
</div>

<!-- Description -->
<div class="col-md-12">
<label class="text-white mb-1">Description</label>
<textarea class="form-control" 
  style="background-color: #0f1534; color: white;" 
  rows="3" 
  wire:model="versionDetails.{{ $index }}.description"></textarea>
</div>

<div class="col-md-12 mt-3">
<select name="" wire:model='versionDetails.{{ $index }}.version_heading' id="" class="form-select">
<label for="version_heading" class="form-label text-white">Select Version Heading</label>
<option value="">Select Option</option>
<option value="0">Issue Fixed</option>
<option value="1">New Feature</option>
</select>
</div>


@if (count($versionDetails) > 1)
<div class="col-md-2 d-flex align-items-end mt-2">
<button type="button" class="btn btn-danger btn-sm"
    wire:click="removeVersionField({{ $index }})">

<span>-</span>
</button>
</div>
@endif
</div>
@endforeach



    

    <!-- Submit Button -->
    @if(!empty($versionId))
    <div class="text-end">
        <button type="submit" class="btn btn-sm text-white" style="background-color: #f2661c;">
            Update Version
        </button>
    </div>
    @else
    <div class="text-end">
        <button type="submit" class="btn btn-sm text-white" style="background-color: #f2661c;">
            Save Version
        </button>
    </div>
    @endif
</form>
</div>  



</div>


    
@push('javascript')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('closeModal', () => {
                
                const modalElement = document.getElementById('versionModel');
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        });
    </script>
    

<script>
    $(document).ready(function () {
        $('#versionModel').on('hidden.bs.modal', function () {
            Livewire.emit('emptyVersionControlValues');
        });
    });
</script>




@endpush
