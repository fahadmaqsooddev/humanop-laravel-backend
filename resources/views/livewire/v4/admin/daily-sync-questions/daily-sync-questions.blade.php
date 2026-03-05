<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>#</th>
                <th>Question</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($dailySyncQuestions as $key => $item)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal">{{ $key + 1 }}</td>
                    <td class="text-sm font-weight-normal">
                        @php
                            $plainText = strip_tags($item->question_text);
                            $isLong = strlen($plainText) > $questionTruncateLength;
                        @endphp
                        @if($isLong)
                            {{ Str::limit($plainText, $questionTruncateLength) }}
                            <a href="javascript:void(0)"
                               wire:click="showFullQuestion({{ $item->id }})"
                               style="color: #1b3a62; cursor: pointer; font-weight: bold;">Read more</a>
                        @else
                            {{ $plainText }}
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal">
                        @if($item->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal">
                        <button type="button" class="btn btn-sm me-1 text-white"
                                style="background-color: #1b3a62"
                                wire:click="openEditModal({{ $item->id }})">
                            Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-danger"
                                onclick="deleteDailySyncQuestion({{ $item->id }})">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No daily sync questions yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{--    add daily sync question model   --}}
    <div wire:ignore.self class="modal fade" id="addDailySyncQuestionModal" tabindex="-1"
         role="dialog"
         aria-labelledby="addDailySyncQuestionModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Daily Sync Question</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitForm">
                            <div class="card-body pt-0">
                                @foreach($questions as $index => $question)
                                    <div class="row mb-3" wire:key="question-{{ $index }}">
                                        <div class="col-12">
                                            <label class="form-label" style="color: #1b3a62">
                                                Question {{ $index + 1 }}
                                            </label>
                                            <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                                   type="text"
                                                   wire:model="questions.{{ $index }}"
                                                   placeholder="Enter daily sync question">
                                            @error('questions.'.$index) <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                    </div>
                                @endforeach

                                <div class="row mb-3">
                                    <div class="col-12 d-flex gap-2">
                                        <button type="button" class="btn btn-sm text-white"
                                                style="background-color: #1b3a62"
                                                wire:click="addQuestion">
                                            Add Question
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                wire:click="removeQuestion"
                                                @if(count($questions) <= 1) disabled @endif>
                                            Remove Question
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Create Daily Sync Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View full question modal --}}
    <div wire:ignore.self class="modal fade" id="viewQuestionModal" tabindex="-1" role="dialog"
         aria-labelledby="viewQuestionModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Question</label>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="card-body pt-0">
                            <p class="mb-0" style="font-size: 16px; line-height: 1.6; background-color: #1b3a62; padding: 12px; border-radius: 5px;">
                                {{ $fullQuestionText ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit daily sync question modal --}}
    <div wire:ignore.self class="modal fade" id="editDailySyncQuestionModal" tabindex="-1" role="dialog"
         aria-labelledby="editDailySyncQuestionModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style="border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Edit Daily Sync Question</label>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="submitEditForm">
                            <div class="card-body pt-0">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Question</label>
                                        <textarea style="background-color: #eaf3ff;" class="form-control input-form-style"
                                                  rows="3"
                                                  wire:model="editQuestionText"
                                                  placeholder="Enter daily sync question"></textarea>
                                        @error('editQuestionText') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model="editIsActive" id="editIsActive">
                                            <label class="form-check-label" style="color: #1b3a62" for="editIsActive">Active</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Update Question
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
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        window.addEventListener('close-add-daily-sync-question-modal', function () {
            const modal = document.getElementById('addDailySyncQuestionModal');
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                bsModal.hide();
            }
        });

        window.addEventListener('show-view-question-modal', function () {
            const modal = document.getElementById('viewQuestionModal');
            if (modal) {
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            }
        });

        window.addEventListener('show-edit-daily-sync-question-modal', function () {
            const modal = document.getElementById('editDailySyncQuestionModal');
            if (modal) {
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            }
        });

        window.addEventListener('close-edit-daily-sync-question-modal', function () {
            const modal = document.getElementById('editDailySyncQuestionModal');
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }
        });

        function deleteDailySyncQuestion(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            });
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Do you want to delete this question?</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteDailySyncQuestion', id);
                }
            });
        }

    </script>
@endpush

