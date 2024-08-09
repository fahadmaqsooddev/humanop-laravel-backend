<div>
    <div wire:ignore.self class="modal fade" id="podcastModal" tabindex="-1" role="dialog"
         aria-labelledby="podcastModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="margin-top: 80px">
            <div class="modal-content">
                <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                    <form wire:submit.prevent="updatePodcast">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fs-4 text-white">Podcast</label>
                                    <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    @include('layouts.message')
                                    <div class="form-group mt-4">
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               type="file" wire:model.defer="podcast_video">
                                        <span class="mt-2" wire:loading.flex wire:target="podcast_video">Video Uploading ...</span>

                                        @error('podcast_video')
                                        <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                                Podcast <span wire:loading wire:target="updatePodcast">
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.livewire.on('toggleCreatePodcastFormModal', () => {setTimeout(function (){

            $('#podcastModal').modal('toggle')

        }, 3000)})

    </script>
@endpush
