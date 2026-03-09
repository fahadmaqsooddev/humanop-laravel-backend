@push('css')
<style>
    .note-editor .note-placeholder {
        color: white !important;
    }
</style>

@endpush
<div>
    <!-- Modal trigger button -->
    <div class="d-flex justify-content-end mt-0">
       <a data-bs-toggle="modal" data-bs-target="#addImpactProjectModal"
        class="btn btn-sm float-end mt-0 createForm me-3"
        style="background-color: #1B3A62; color: white;">
        Add Impact Project
        </a>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="addImpactProjectModal" tabindex="-1" aria-labelledby="addImpactProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            
                <div class="modal-body" style=" border-radius: 9px">
                    <label class="form-label fs-4" style="color: #1b3a62">Add Impact Project</label>
                     <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form wire:submit.prevent="createProject">
                        @include('layouts.message')

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control input-form-style" wire:model.defer="title" placeholder="Project title">
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label">Description</label>
                                <textarea class="form-control input-form-style" rows="4" wire:model.defer="description"></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label">HP Required</label>
                                <input type="number" class="form-control input-form-style" wire:model.defer="hp_required" placeholder="e.g. 10000">
                            </div>

                           <div class="col-12 mt-4">
                                <label class="form-label">Verification Text</label>
                                <textarea id="verification_text"
                                        class="form-control input-form-style" 
                                        rows="4" 
                                        wire:model.defer="verification_text" 
                                ></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label">Status</label>
                                <select class="form-select input-form-style" wire:model.defer="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                           
                        </div>

                        <button type="submit" class="btn btn-sm float-end mt-4 mb-0 text-white" style="background-color: #1b3a62;">
                            Create Project
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('javascript')
<script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
<script src="../../assets/js/plugins/sweetalert.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>



<script>
document.addEventListener('livewire:load', function () {

    function initSummernote() {
        if (!$('#verification_text').hasClass('summernote-initialized')) {
            $('#verification_text').summernote({
                height: 150,
                placeholder: 'Optional verification text',
                callbacks: {
                    onInit: function() {
                        $('.note-editor .note-placeholder').css('color', 'white');
                        $('#verification_text').addClass('summernote-initialized');
                    },
                   onChange: function(contents, $editable) {
                        @this.set('verification_text', contents); // save full HTML for now
                    }
                }
            });
        }
    }

    initSummernote();
    $('#addImpactProjectModal').on('shown.bs.modal', initSummernote);


    window.addEventListener('closeModal', () => {
        let modalEl = document.getElementById('addImpactProjectModal');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        } else {
    
            new bootstrap.Modal(modalEl).hide();
        }
    });

});
</script>

@endpush