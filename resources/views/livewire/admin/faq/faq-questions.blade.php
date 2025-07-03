<div>


    <div class="card">
        <div class="card-header table-header-text">
            <h5 class="mb-0">All Faq Questions</h5>
            <a data-bs-toggle="modal"
               data-bs-target="#createQuestionModel"
               style="background-color: #1B3A62 ; color: white" class="btn btn-sm float-end mb-0">Add Questions</a>

        </div>
        <div class="table-responsive w-100 pt-4 table-orange-color">
            @include('layouts.message')
            <table class="table table-flush" id="datatable-search">
                <thead class="thead-light">
                <tr class="text-color-blue ">
                    <th class="text-center">id</th>
                    <th class="text-center">Question</th>
                    <th class="text-center">Answer</th>
                    <th class="text-center">Action</th>

                </tr>
                </thead>
                <tbody>
                @foreach($allQuestions as $plan)
                    <tr class="text-color-blue">
                        <td class="text-sm font-weight-normal text-center">{{ $loop->iteration }}</td>
                        <td class="text-sm font-weight-normal text-center">{{ $plan->question }}</td>
                        <td class="text-sm font-weight-normal text-center">{{ $plan->answer }}</td>


                        <td>
                            <button class="btn btn-sm text-white" style="background-color: #1b3a62"
                                    data-bs-toggle="modal"
                                    wire:click="updateFaqModal({{ $plan->id }})"
                                    data-bs-target="#editPlan">
                                Edit
                            </button>
                            <button class="btn btn-sm text-white" style="border: 1px solid #1b3a62; color: white; background-color: red;"
                                    data-bs-toggle="modal"
                                    onclick="deleteQuestion({{$plan->id}})"
                                    data-bs-target="">
                                Delete
                            </button>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div wire:ignore.self class="modal fade" id="createQuestionModel" tabindex="-1"
             role="dialog" data-bs-focus="false"
             aria-labelledby="createQuestionModel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4" style="color: #1b3a62">Create Question</label>

                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-optimization-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <form wire:submit.prevent="submitForm">
                                <div class="row mt-4">
                                    <div class="col-12">

                                        <label class="form-label" style="color: #1b3a62">Question</label>
                                        <div class="input-group">
                                            <input class="form-control input-form-style" type="text" wire:model="question"
                                                   placeholder="type Question">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">

                                        <label class="form-label" style="color: #1b3a62">Answer</label>
                                        <div class="input-group">
                                            <input class="form-control input-form-style" type="text" wire:model="answer"
                                                   placeholder="write Answer">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62 ">create Faq
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editPlan" tabindex="-1"
             role="dialog" data-bs-focus="false"
             aria-labelledby="editPlan" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body" style=" border-radius: 9px">
                        <div class="card-body pt-0">
                            <label class="form-label fs-4" style="color: #1b3a62">Edit Faq</label>

                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-optimization-modal-button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <form wire:submit.prevent="updateFaqPlan">
                                <div class="row mt-4">
                                    <div class="col-12">

                                        <label class="form-label" style="color: #1b3a62">Question</label>
                                        <div class="input-group">
                                            <input class="form-control input-form-style" type="text" wire:model="question"
                                                   placeholder="type Question">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">

                                        <label class="form-label" style="color: #1b3a62">Answer</label>
                                        <div class="input-group">
                                            <input class="form-control input-form-style" type="text" wire:model="answer"
                                                   placeholder="write Answer">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62 ">Update Faq
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')

    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <script>
        window.Livewire.on('closeInfoModal', function (e) {
            $('#close-optimization-modal-button').click();
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('refreshPage', () => {
                location.reload();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Detect when modal is closed
            const modal = document.getElementById('editPlan');
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function () {

                    Livewire.emit('resetForm');
                });
            }
        });
    </script>


    <script>
        function deleteQuestion(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>Want to delete Question</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteQuestion', id)
                }
            })
        }

    </script>


@endpush
