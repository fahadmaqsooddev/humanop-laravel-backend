<form wire:submit.prevent="submitForm">
    @include('layouts.message')

    <div class="card-body pt-0">
        <div class="row">
            <div class="col-12 mt-4">
                <label class="form-label" style="color: #1b3a62">Title</label>
                <input style="background-color: #eaf3ff;" class="form-control input-form-style"
                       type="text" wire:model="title"
                       placeholder="Enter tutorial title">
                @error('title') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-12 mt-4"
                 x-data="{ progress: 0 }"
                 x-on:livewire-upload-start="progress = 0"
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                 x-on:livewire-upload-finish="progress = 0"
                 x-on:livewire-upload-error="progress = 0">

                <label class="form-label" style="color:#1b3a62;">
                    Upload Audio File [MP3, WAV, MPEG]
                </label>

                <input class="form-control input-form-style" type="file" wire:model="audio_file"
                       id="audioInput" accept="audio/*" style="background-color:#eaf3ff;">

                <div class="progress mt-2" x-show="progress > 0" style="height: 20px;">
                    <div class="progress-bar" role="progressbar"
                         :style="`width: ${progress}%; background-color:#1b3a62; color:white; padding-top: 8px; padding-bottom: 8px`"
                         x-text="progress + '%'">
                    </div>
                </div>

                <span wire:loading wire:target="audio_file" style="color:#1b3a62;"></span>

                @error('audio_file')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>


            <div class="col-12 mt-4"
                 x-data="{ progress: 0 }"
                 x-on:livewire-upload-start="progress = 0"
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                 x-on:livewire-upload-finish="progress = 0"
                 x-on:livewire-upload-error="progress = 0">

                <label class="form-label" style="color:#1b3a62;">
                    Upload Thumbnail Image File [PNG, JPG, JPEG, GIF]
                </label>

                <input class="form-control input-form-style" type="file" wire:model="thumbnail_file"
                       id="thumbnailInput" accept="image/*" style="background-color:#eaf3ff;">

                <div class="progress mt-2" x-show="progress > 0" style="height: 20px;">
                    <div class="progress-bar" role="progressbar"
                         :style="`width: ${progress}%; background-color:#1b3a62; color:white; padding-top: 8px; padding-bottom: 8px`"
                         x-text="progress + '%'">
                    </div>
                </div>

                <span wire:loading wire:target="audio_file" style="color:#1b3a62;"></span>

                @error('thumbnail_file')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>
        <button type="submit" class="btn btn-sm float-end mt-6 mb-0 text-white"
                style="background-color: #1b3a62">Create Podcast
        </button>
    </div>
</form>
@push('javascript')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('refreshPage', () => {
                window.location.reload();
            });
        });
    </script>
@endpush
