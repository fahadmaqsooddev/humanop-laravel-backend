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
<div>
    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


<div wire:ignore.self class="modal fade" id="versionModel" tabindex="-1"
     role="dialog" data-bs-focus="false"
     aria-labelledby="dailyTipModel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style=" border-radius: 9px">
                <div class="card-body pt-0">
                    @if($version_id)
                    <label class="form-label fs-4 text-white">Edit Version</label>


                                @else
                                <label class="form-label fs-4 text-white">Add Version</label>

                                @endif

                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    
                    <form wire:submit.prevent="storeVersionAndDescription">
                        <!-- Version Field -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Version</label>
                                <input style="background-color: #0f1534;color:white;" wire:model="version"
                                       class="form-control table-header-text" type="text">
                            </div>
                        </div>
                    
                        @if($version_id)

                        @else
                        <div class="row mt-4">
                            <div class="col-12">
                                <label class="form-label text-white">Note</label>
                                <textarea class="form-control table-header-text"
                                          style="background-color: #0f1534;color:white;"
                                          rows="2" wire:model="note"></textarea>
                            </div>
                        </div>
                        @endif
                    
                        <!-- Dynamic Version Details -->
                        @if($version_id)
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm text-white" style="background-color: #f2661c;">
                                Update Version
                            </button>
                        </div>
                        @else
                        <!-- Add Field Button -->
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-success btn-sm" wire:click="addVersionField">
                                <i class="fas fa-plus-circle"></i> Add Field
                            </button>
                        </div>
                       
                        @foreach ($versionDetails as $index => $detail)
    <div class="row mb-3">
        <!-- Version Type Checkboxes -->
        <div class="col-md-12 mb-3">
            <label class="text-white mb-1">Issue Fixed</label><br>
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
        

        @if (count($versionDetails) > 1)
            <div class="col-md-2 d-flex align-items-end mt-2">
                <button type="button" class="btn btn-danger btn-sm"
                        wire:click="removeVersionField({{ $index }})">
                    <i class="fas fa-minus-circle"></i>
                </button>
            </div>
        @endif
    </div>
@endforeach

                    
                    
                        
                    
                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm text-white" style="background-color: #f2661c;">
                                Save Version
                            </button>
                        </div>
                        @endif
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
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
