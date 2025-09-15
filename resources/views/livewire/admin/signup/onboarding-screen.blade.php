<div>
    <div class="table-responsive table-header-text w-100 pt-4 table-orange-color">
        <table class="table table-flush">
            <thead class="thead-light">
            <tr class="text-color-blue">
                <th>Screen Name</th>
                <th>Screen Text</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($onboardingScreens as $screen)
                <tr class="text-color-blue">
                    <td class="text-sm font-weight-normal">{{ $screen['screen_name'] }}</td>
                    <td class="text-sm font-weight-normal">
                        @if(strlen(strip_tags($screen['description'])) > 40)
                            {!! Str::limit(strip_tags($screen['description']), 50, '') !!}
                            <a data-bs-toggle="modal"
                               data-bs-target="#viewOnboardingScreenModal{{ $screen['id'] }}"
                               style="color: #1b3a62; cursor: pointer; font-size: larger; font-weight: bold">
                                view more...
                            </a>
                        @else
                            {!! $screen['description'] !!}
                        @endif
                    </td>
                    <td class="text-sm font-weight-normal">
                        <a wire:click="editOnboardingScreenModal({{ $screen['id'] }},`{{ $screen['screen_name']  }}`,`{{ $screen['description']  }}`)"
                           class="btn-sm mt-2 mb-0"
                           data-bs-target="#editFormOnboardingScreenModal" data-bs-toggle="modal"
                           style="background:#1b3a62;color:white;font-weight:bolder;cursor:pointer;">Edit</a>
                    </td>
                </tr>

                <div wire:ignore.self class="modal fade" id="viewOnboardingScreenModal{{ $screen['id'] }}"
                     tabindex="-1"
                     role="dialog"
                     aria-labelledby="viewOnboardingScreenModal{{ $screen['id'] }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body" style=" border-radius: 9px">
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-query-view-modal-{{$screen['id']}}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <form wire:submit.prevent="">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="form-label"
                                                       style="color: #0f1534; font-size: 22px">Screen text:</label>
                                                <span
                                                    style="font-size: 18px;font-weight: 600; color: #1b3a62 !important; padding: 10px; border-radius: 5px; text-align: justify;">{!! $screen['description'] !!}</span>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </tbody>
        </table>
    </div>

    {{--    edit onboarding model   --}}
    <div wire:ignore.self class="modal fade" id="editFormOnboardingScreenModal" tabindex="-1" role="dialog"
         aria-labelledby="editFormOnboardingScreenModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body pt-0">
                        <label class="form-label fs-4" style="color: #1b3a62">Onboarding {{$title}} Screen</label>
                        <button type="button" class="close modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        @include('layouts.message')
                        <form wire:submit.prevent="updateForm">
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label" style="color: #1b3a62">Title</label>
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               type="text" wire:model="title" disabled placeholder="Enter tutorial title">
                                        <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                                               hidden="hidden" type="text" wire:model="screenId">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <label class="form-label" style="color: #1b3a62">Description</label>
                                        <div wire:ignore>
        <textarea id="edit-summernote"
                  class="form-control input-form-style editor"
                  placeholder="Enter description"></textarea>
                                        </div>
                                        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                    </div>


                                </div>
                                <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                                        style="background-color: #1b3a62">Edit Onboarding
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

        document.addEventListener("livewire:load", function () {
            // Initialize summernote
            $('#edit-summernote').summernote({
                height: 200,
                callbacks: {
                    onChange: function (contents) {
                    @this.set('description', contents); // send back to Livewire
                    }
                }
            });

            // Listen for Livewire event → load description into summernote
            window.addEventListener('loadDescription', event => {
                $('#edit-summernote').summernote('code', event.detail.description);
            });
        });



    </script>
@endpush

