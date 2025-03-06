<div>

    <div class="p-2">
        <div class="input-group ms-md-4 pe-md-4 d-flex justify-content-between">
            <h3>Fine Tune Content</h3>

            <div>

                <button class="btn-sm m-1"
                        data-bs-toggle="modal"
                        data-bs-target="#addQuestionAnswerModel"
                        style="background:#f2661c;color:white;font-weight:bolder;border-radius: 7px;border: none">
                    <i class="fa-solid fa-plus"></i>
                    Question
                </button>

                <button class="btn-sm m-1"
                        title="It download questions in jsonl format"
                        wire:click="downloadQuestions"
                        style="background:#f2661c;color:white;font-weight:bolder;border-radius: 7px;border: none">
                    <i class="fa-solid fa-download"></i>
                    Questions
                </button>

            </div>
        </div>
    </div>

    <div class="table-responsive w-100 pt-4 table-orange-color" style="border-radius: 10px;">
        @if(count($contents) > 0)
            <table class="table table-flush">
                <thead class="thead-light">
                <tr class="table-text-color">
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($contents as $index => $content)
                    <tr class="table-text-color">
                        <td class="text-md font-weight-normal" style="padding-left: 15px !important;">
                            @if(strlen($content['question']) > 25)
                                {{substr($content['question'], 0, 22)}}
                                &nbsp;<span style="color: #f2661c; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#viewQuestionAnswerModel_{{$content['id']}}">
                                    read more</span>
                            @else
                                {{$content['question']}}
                            @endif

                        </td>
                        <td class="text-md font-weight-normal">
                            @if(strlen($content['answer']) > 45)
                                {{substr($content['answer'], 0, 42)}}
                                &nbsp;<span style="color: #f2661c; cursor: pointer;"
                                            data-bs-toggle="modal" data-bs-target="#viewQuestionAnswerModel_{{$content['id']}}">
                                    read more</span>
                            @else
                                {{ $content['answer'] }}
                            @endif
                        </td>
                        <td>
                            @if($content['is_fine_tuned'] === 0)
                                @if($content['queued_for_fine_tuning'])
                                    <button wire:click="changeQuestionStatus({{$content['id']}}, 0)" class="btn mb-0 text-white" style="font-size: 10px;background-color: lightgray;border-radius: 5px">
                                        Added to Queue
                                    </button>
                                @else
                                    <button wire:click="changeQuestionStatus({{$content['id']}}, 1)" class="btn mb-0 text-white" style="font-size: 10px;background-color: #f2661c;border-radius: 5px">
                                        Add to Queue
                                    </button>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($content['is_fine_tuned'] === 0)
                                <button class="btn mb-0 text-white"
                                        wire:click='updateQuestionAnswer("{{$content['id']}}","{{$content['question']}}", "{{$content['answer']}}")'
                                        style="background-color: #f2661c;border-radius: 5px"
                                        data-bs-toggle="modal" data-bs-target="#editQuestionAnswerModel">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn mb-0 text-white" onclick="deleteQuestionAnswer({{$content['id']}})"
                                        style="background-color: #ff0000;border-radius: 5px;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>


                    <div wire:ignore.self class="modal fade" id="viewQuestionAnswerModel_{{$content['id']}}" tabindex="-1"
                         role="dialog"
                         aria-labelledby="viewQuestionAnswerModel_{{$content['id']}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body" style=" border-radius: 9px">
                                    <div class="card-body pt-0">
{{--                                        <label class="form-label fs-4 text-white">Edit content for Fine-tuning</label>--}}

                                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                                aria-label="Close" id="close-edit-modal-button">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div>
                                            <h4 style="color: #f2661c;">{{$content['question']}}</h4>
                                            <p style="color: white;">{{$content['answer']}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
                </tbody>
            </table>

        @else
            <div class="text-center p-5">

                <span class="custom-text-dark">
                    No records found. Add a question and answer, and they will appear here!
                </span>

            </div>
        @endif

        {{$contents->links('pagination.table-pagination')}}

    </div>
    <div wire:ignore.self class="modal fade" id="addQuestionAnswerModel" tabindex="-1"
         role="dialog"
         aria-labelledby="addQuestionAnswerModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Add content for Fine-tuning</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-add-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="addFineTuneContent">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="p-1">
                                                <label class="text-white">Question</label>
                                                <input style="background-color: #0f1534;color: lightgrey !important"
                                                       class="form-control text-white"
                                                       type="text" wire:model="question" placeholder="Enter question">
                                            </div>

                                            <div class="p-1">
                                                <label for="textarea" class="text-white">Answer</label>
                                                <textarea id="textarea" rows="3" style="background-color: #0f1534;color: lightgrey !important"
                                                       class="form-control text-white"
                                                       type="text" wire:model="answer" placeholder="Enter question's answer">
                                                </textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"
                                                    style="background-color: #f2661c ">Add
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

    <div wire:ignore.self class="modal fade" id="editQuestionAnswerModel" tabindex="-1"
         role="dialog"
         aria-labelledby="editQuestionAnswerModel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4 text-white">Edit content for Fine-tuning</label>

                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                aria-label="Close" id="close-edit-modal-button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        @include('layouts.message')
                        <form wire:submit.prevent="editFineTuneContent">
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="p-1">
                                                <label class="text-white">Question</label>
                                                <input style="background-color: #0f1534;color: lightgrey !important"
                                                       class="form-control text-white"
                                                       type="text" wire:model="updateQuestion" placeholder="Enter question">
                                            </div>

                                            <div class="p-1">
                                                <label for="textarea" class="text-white">Answer</label>
                                                <textarea id="textarea" rows="3" style="background-color: #0f1534;color: lightgrey !important"
                                                          class="form-control text-white"
                                                          type="text" wire:model="updateAnswer" placeholder="Enter question's answer">
                                                </textarea>
                                            </div>
                                            <button type="submit" class="btn btn-sm mt-4 float-end text-white"
                                                    style="background-color: #f2661c ">Update
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

@push('javascript')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>

        function deleteQuestionAnswer(id) {

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
                html: "<span style='color: white;'>Want to delete.</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteQuestionAnswer', id)
                }
            })
        }

        window.livewire.on('closeAlert', function (){
            setTimeout(function (){
                $('.alert').alert('close');
            }, 2000);
        })

        window.Livewire.on('closeEditModal', function () {

            setTimeout(function () {
                $('#close-edit-modal-button').click();
            }, 1000);
        });

        window.Livewire.on('closeAddModal', function () {

            setTimeout(function () {
                $('#close-add-modal-button').click();
            }, 1000);
        });

    </script>

@endpush
