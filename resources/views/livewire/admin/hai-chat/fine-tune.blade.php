<div>

    <div class="py-2">
        <div class="input-group d-flex justify-content-between">
            <h3 style="color: #1C365E;font-weight: 700;font-size: 28px;line-height: 16.5px;vertical-align: middle;text-transform: capitalize;">
                Fine Tune Content
            </h3>

            <div>

                <button class="m-1"
                        data-bs-toggle="modal"
                        data-bs-target="#addQuestionAnswerModel"
                        style="background:#F95520;color:white;border-radius: 24px;border: 2px; font-weight: 400;padding: 5px 10px 5px 10px;">
                    <img src="{{asset('assets/img/icons/Add.svg')}}" width="20">
                    Add Question
                </button>

                <button class="m-1"
                        title="It download questions in jsonl format"
                        wire:click="downloadQuestions"
                        wire:target="downloadQuestions" wire:loading.attr="disabled"
                        style="background:#F95520;color:white;border-radius: 24px;border: 2px; font-weight: 400;padding: 5px 10px 5px 10px;">
                    <img src="{{asset('assets/img/icons/pushicon.svg')}}" width="20">
                    Push Fine Tune
                </button>

            </div>
        </div>
    </div>

    <div class="table-responsive w-100 pt-4" style="border-radius: 10px; background-color: #F6BA81;">
        @if(count($contents) > 0)
            <table class="table table-flush">
                <thead class="thead-light">
                <tr class="table-text-color" style="color: #1C365E;font-size: 18px;">
                    <th style="font-weight: 700; padding: 12px 12px;">Question</th>
                    <th style="font-weight: 700; padding: 12px;">Answer</th>
                    <th style="font-weight: 700; text-align: center;">Fine Tuned</th>
                    <th style="font-weight: 700; text-align: left;">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($contents as $index => $content)
                    <tr class="table-text-color" style="color: #1C365E;font-size: 15px;font-weight: 600;">
                        <td class="text-md" style="padding-left: 15px !important;">
                            @if(strlen($content['question']) > 25)
                                {{substr($content['question'], 0, 22)}}
                                &nbsp;<span style="color: #f2661c; cursor: pointer;font-size: 11px;"
                                        data-bs-toggle="modal" data-bs-target="#viewQuestionAnswerModel_{{$content['id']}}">
                                    Read More</span>
                            @else
                                {{$content['question']}}
                            @endif

                        </td>
                        <td class="text-md">
                            @if(strlen($content['answer']) > 45)
                                {{substr($content['answer'], 0, 42)}}
                                &nbsp;<span style="color: #f2661c; cursor: pointer; font-size: 11px;"
                                            data-bs-toggle="modal" data-bs-target="#viewQuestionAnswerModel_{{$content['id']}}">
                                    Read More</span>
                            @else
                                {{ $content['answer'] }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if($content['is_fine_tuned'])
                                <img src="{{asset('assets/img/icons/Group 33730.svg')}}" width="20">
{{--                                <i class="fa-solid fa-check" style="color: #f2661c;"></i>--}}
                            @else
                                <img src="{{asset('assets/img/icons/Cross.svg')}}" width="20">
{{--                                <i class="fa-solid fa-xmark"></i>--}}
                            @endif
                        </td>
                        <td style="padding: 12px 24px;">
                            @if($content['is_fine_tuned'] === 0)
                                <button class="mb-0 text-white"
                                        wire:click='updateQuestionAnswer("{{$content['id']}}","{{$content['question']}}", "{{$content['answer']}}")'
                                        style="background-color: transparent;border:none;"
                                        data-bs-toggle="modal" data-bs-target="#editQuestionAnswerModel">
                                    <i class="fa fa-pencil" style="color: #1C365E; font-weight: 600;"></i>
                                </button>
                                <button class="mb-0 text-white" onclick="deleteQuestionAnswer({{$content['id']}})"
                                        style="background-color: transparent;border:none;">
                                    <i class="fa fa-trash" style="color: #1C365E; font-weight: 600;"></i>
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
