@push('css')
    <style>
        .note-editable {
            background-color: #1b3a62 !important;
            color: #ffffff !important;
        }

        .note-placeholder {
            color: #cccccc !important;
        }

        .note-editor {
            border-radius: 0 !important;
            width: 100% !important;
        }

        .card {
            background-color: white !important;
        }

    </style>
@endpush
<form wire:submit.prevent="updatePlan">
    @include('layouts.message')
    <div class="row mt-4">
        <div class="col-12">

            <label class="form-label" style="color: #1b3a62">Plan Name</label>
            <div class="input-group">
                <input class="form-control input-form-style" type="text"
                       wire:model="plan_name"
                       placeholder="plan name">
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <select class="form-control input-form-style" style="color: #1b3a62"
                    wire:model="plan_type" disabled>
                <option value="" style="color: white">Select Plan Type</option>
                <option value="month" style="color: white">Month</option>
                <option value="year" style="color: white">Year</option>
            </select>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <label class="form-label" style="color: #1b3a62">Plan Amount</label>
            <div class="input-group">
                <input disabled wire:model="price"
                       class="form-control input-form-style table-header-text"
                       type="text">
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <label class="form-label" style="color: #1b3a62">Plan Detail</label>
            <div class="input-group" wire:ignore>
                <textarea class="form-control table-header-text" id="neditor" rows="10" cols="10" name="text"
                          wire:model="plan_detail"></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
            style="background-color: #1b3a62 ">Update plan
    </button>
</form>

@push('javascript')
    <script src="{{ URL::asset('assets/js/plugins/datatables.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            $('#neditor').summernote({
                height: 200,
                placeholder: 'Enter your text here...',
                callbacks: {
                    onChange: function (contents, $editable) {
                    @this.set('plan_detail', contents);
                    }
                }
            });

            Livewire.hook('message.processed', (message, component) => {
                if (!$('#neditor').next().hasClass('note-editor')) {
                    $('#neditor').summernote({
                        height: 200,
                        placeholder: 'Enter your text here...',
                        callbacks: {
                            onChange: function (contents, $editable) {
                            @this.set('plan_detail', contents);
                            }
                        }
                    });
                }
            });

            $('.createForm').on('click', function () {
                $('#neditor').summernote('reset');
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('refreshPage', () => {
                window.location.reload();
            });
        });
    </script>

@endpush
