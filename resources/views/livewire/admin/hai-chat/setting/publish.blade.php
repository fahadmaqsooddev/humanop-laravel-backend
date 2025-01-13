@push('css')

    <style>

        .new-orange-button {
            background-color: #F95520 !important;
            padding: 10px 20px 10px 20px;
            border-radius: 8px;
            color: white;
            border-color: transparent;
            cursor: pointer;
            font-weight: 800;
        }

        .new-orange-button:hover {
            color: white;
        }

    </style>

@endpush
<div class="card card-bg-white-orange-border mt-4" id="setting">
    <div class="card-header">
        <div>
            @include('layouts.message')
            <div class="card-body">
                <div class="col-12">
                    <h5 class="text-orange setting-form-heading py-2">Publish Brain</h5>
                    <div class="card-text" style="color: #0f1535;">
                        Click the Publish button below to publish your brain.
                    </div>
                </div>
                    <form wire:submit.prevent="publishForm">
                        <div class="d-flex gap-2 float-end">
                            @if(!empty($chatBot['publish']))
                            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5  new-orange-button navButtonResponsive">
                                Published
                                <span wire:loading wire:target="publishForm" class="swal2-loader"
                                      style="font-size: 8px;">
                                </span>
                            </button>
                            @else
                                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                        class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5  new-orange-button navButtonResponsive">
                                    {{ $buttonText }}
                                    <span wire:loading wire:target="publishForm" class="swal2-loader"
                                          style="font-size: 8px;">
                                </span>
                                </button>
                            @endif
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@push('js')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

    <script>

        Livewire.on('changeButtonText', () => {
            console.log('Button text changed!');
        });
    </script>

@endpush
