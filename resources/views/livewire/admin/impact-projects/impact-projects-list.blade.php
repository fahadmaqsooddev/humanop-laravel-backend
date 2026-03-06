<div>
    @livewire('admin.impact-projects.add-impact-project')
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush text-center">
            <thead class="thead-light">
             <tr class="text-color-blue">
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>HP Required</th>
                    <th>Verification Text</th>
                    <th>Status</th>
                    <th>Actions</th>    
                </tr>
            </thead>
            <tbody style="color:black">
                @foreach($impact_projects as $index => $project)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ $project->hp_required }}</td>
                        <td>{{ $project->verification_text ?? '-' }}</td>
                        <td>
                            @if($project->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                         <td>
                            <button type="button" class="btn btn-sm me-1 text-white"
                                style="background-color: #1b3a62" 
                                    wire:click="$emit('editProject', {{ $project->id }})">
                                Edit
                            </button>
                           <button type="button" class="btn btn-sm btn-danger" 
                                    wire:click="$emit('confirmDeleteProject', {{ $project->id }})">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach

                @if($impact_projects->isEmpty())
                    <tr>
                        <td colspan="10" class="text-center">No Impact Projects found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div wire:ignore.self class="modal fade" id="editImpactProjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body" style="border-radius: 9px">
                    <label class="form-label fs-4" style="color: #1b3a62">Edit Project</label>
                    <form wire:submit.prevent="updateProject">
                         @include('layouts.message')
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control input-form-style" wire:model.defer="title">
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control input-form-style" wire:model.defer="description"></textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">HP Required</label>
                                <input type="number" class="form-control input-form-style" wire:model.defer="hp_required">
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Verification Text</label>
                                <textarea class="form-control input-form-style" wire:model.defer="verification_text"></textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Status</label>
                                <select class="form-select input-form-style" wire:model.defer="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Update Project</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:load', function () {
    window.addEventListener('show-edit-modal', event => {
        $('#editImpactProjectModal').modal('show');
    });
    window.addEventListener('hide-edit-modal', event => {
        $('#editImpactProjectModal').modal('hide');
    });

    window.addEventListener('show-delete-confirmation', event => {
        const id = event.detail.project_id;
        const message = event.detail.message || "Are you sure you want to delete this project?";
        if(confirm(message)) {
            Livewire.emit('deleteProject', id);
        }
    });
});
</script>
@endpush