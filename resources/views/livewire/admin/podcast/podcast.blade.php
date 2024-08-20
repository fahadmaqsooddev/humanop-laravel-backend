<div>
    <div class="col-6 position-relative z-index-2">
        <div class="mb-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-column h-100">
                            <h2 class="font-weight-bolder mb-0">Podcast</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card" style="height: content-box;border-radius: 2rem !important; background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%) border-box;">

                <div class="container">

                    <div class="row justify-content-between pt-3">
                        <div class="col-6">
                            <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)"> HIP -
                                HumanOp Integration Podcast
                            </p>
                        </div>
                        <div class="col-4">
                            @if($latest_podcast)
                            <button type="button" data-bs-toggle="modal"
                                    data-bs-target="#podcastModal" style="background-color: #f2661c; color: white"
                                    class="btn btn-sm float-end mt-2 mb-0">
                                update
                            </button>
                            @else
                                <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#podcastModal" style="background-color: #f2661c; color: white"
                                        class="btn btn-sm float-end mt-2 mb-0">
                                    create
                                </button>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="card-body p-3">
                    <div class="mb-4 justify-content-center">
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="numbers mt-3 d-flex justify-content-center">
                                    @if($latest_podcast && !empty($latest_podcast->embedded_url))
                                    <iframe height="600" width="600"
                                        src="{{$latest_podcast->embedded_url}}">
                                    </iframe>
                                    @else
                                        <p class="text-center text-white">There is not any podcast uploaded yet.
                                            Want to <a type="button" data-bs-toggle="modal"
                                                       data-bs-target="#podcastModal" style="text-decoration: underline">
                                                upload
                                            </a> ?
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                    <div class="form-group mt-4">
                                        <input style="background-color: #0f1534;" class="form-control text-white"
                                               type="url" wire:model.defer="podcast_url"
                                               placeholder="Add your podcast's Hiro.FM embedded link here">

                                        @error('podcast_url')
                                            <p class="text-danger">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Upload
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

        }, 1000)})

    </script>
@endpush
