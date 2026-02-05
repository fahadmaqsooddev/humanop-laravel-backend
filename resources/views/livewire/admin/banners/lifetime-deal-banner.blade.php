@push('css')
    <style>
        .note-editor.note-frame .note-editing-area .note-editable {
            background-color: #1b3a62 !important;
            color: white !important;
        }

        .note-editor.note-frame {
            border: 1px solid #1b3a62;
            width: 100% !important; /* Ensure full width */
        }

        .note-editor.note-editing-area,
        .note-editable,
        .note-editable p {
            width: 100% !important;
        }

        .card {
            background-color: white !important;
        }

        .ck.ck-balloon-panel {
            z-index: 1050 !important;
        }

        .ck > p > a {
            color: blue !important;
        }

        /* Toggle switch styling */
        .form-switch {
            display: flex;
            align-items: center;
        }

        .form-switch input {
            width: 2em;
            height: 1em;
            margin-right: 0.5rem;
        }

        textarea.editor {
            width: 100% !important; /* Ensure textarea itself takes full width */
        }
    </style>
@endpush

<form wire:submit.prevent="updateIntro">
    @include('layouts.message')
    <input type="hidden" wire:model.defer="select_code.id">

    <div class="row">

        <!-- Title for Beta Breaker -->
        <!--<div class="col-12">
            <label class="form-label">Title for Beta Breaker</label>
            <div class="input-group">
                <input name="title_for_beta_breaker"
                       class="form-control input-form-style"
                       type="text"
                       wire:model.defer="banner.title_for_beta_breaker">
            </div>
        </div>


        <div class="col-12 mt-4">
            <label class="form-label">Description for Beta Breaker</label>
            <div class="input-group w-100" wire:ignore>
        <textarea id="summernote_beta"
                  class="form-control editor"
                  rows="10">{{ $banner['description_for_beta_breaker'] ?? '' }}</textarea>
            </div>
        </div>


        <div class="col-12 mt-4">
            <label class="form-label">Title for Freemium</label>
            <div class="input-group">
                <input name="title_for_freemium"
                       class="form-control input-form-style"
                       type="text"
                       wire:model.defer="banner.title_for_freemium">
            </div>
        </div>


        <div class="col-12 mt-4">
            <label class="form-label">Description for Freemium</label>
            <div class="input-group w-100" wire:ignore>
        <textarea id="summernote_freemium"
                  class="form-control editor"
                  rows="10">{{ $banner['description_for_freemium'] ?? '' }}</textarea>
            </div>
        </div>




        -->


        <div class="col-12 mt-4">
            <label class="form-label">Title for Banner</label>
            <div class="input-group">
                <input name="title"
                        class="form-control input-form-style"
                        type="text"
                        wire:model.defer="banner.title">
            </div>
        </div>

        <!-- Description for Both Web - Mobile -->
        <div class="col-12 mt-4">
            <label class="form-label">Title for Description</label>

            <div class="input-group w-100" wire:ignore>
                <textarea
                    id="summernote_both"
                    class="form-control editor"
                    rows="10"
                    wire:model.defer="banner.description">{{ $banner['description'] ?? '' }}</textarea>
            </div>

            <!-- URLs -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="form-label">Payment URL</label>
                    <input type="url"
                           class="form-control input-form-style"
                           placeholder="https://example.com/freemium"
                           wire:model.defer="banner.payment_url">
                </div>

            </div>

            <!-- Checkboxes -->
            <div class="mt-3 d-flex gap-4">
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           wire:model.defer="banner.visible_on_mobile"
                           id="platform_mobile">
                    <label class="form-check-label" for="platform_mobile">
                        Mobile
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           wire:model.defer="banner.visible_on_web"
                           id="platform_web">
                    <label class="form-check-label" for="platform_web">
                        Web
                    </label>
                </div>
            </div>

        </div>

        <!-- Start & End Date -->
{{--        <div class="col-12 d-flex justify-content-between mt-4">--}}
{{--            <div class="col-5">--}}
{{--                <label class="form-label">Start Date</label>--}}
{{--                <div class="input-group">--}}
{{--                    <input class="form-control input-form-style"--}}
{{--                           type="date"--}}
{{--                           wire:model.defer="banner.start_date">--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-5">--}}
{{--                <label class="form-label">End Date</label>--}}
{{--                <div class="input-group">--}}
{{--                    <input class="form-control input-form-style"--}}
{{--                           type="date"--}}
{{--                           wire:model.defer="banner.end_date">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <!-- Enable/Disable Toggle -->
        <div class="col-12 mt-4">
            <label class="form-label d-block">Status</label>
            <div class="form-switch">
                <input type="checkbox" class="form-check-input" id="statusToggle"
                       wire:model="banner.status">
                <label for="statusToggle" class="form-check-label">
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="col-12 mt-5">
            <button class="btn btn-sm float-end text-white" style="background-color: #1B3A62">
                Update Banner
            </button>
        </div>
    </div>
</form>

@push('javascript')
    <!-- jQuery & Summernote -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script>
        document.addEventListener('livewire:load', function () {

            function initSummernote(selector, modelPath) {
                $(selector).summernote({
                    height: 200,
                    width: '100%',
                    callbacks: {
                        onChange: function (contents) {
                            @this.set(modelPath, contents);
                        }
                    }
                });
            }

            // Initialize single editor
            initSummernote('#summernote_both', 'banner.description');

            // Reinitialize after Livewire updates
            Livewire.hook('message.processed', () => {
                if (!$('#summernote_both').next().hasClass('note-editor')) {
                    initSummernote('#summernote_both', 'banner.description');
                }
            });

        });


    </script>
@endpush
