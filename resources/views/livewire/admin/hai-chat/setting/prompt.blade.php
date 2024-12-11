<div>

    <div class="card card-bg-white-orange-border mt-4" id="prompt">
        @include('layouts.message')
        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-2">Base Prompt</h5>
            <textarea class="form-control input-bg" id="chatDescription" wire:model="prompt"
                      rows="6" placeholder="Enter chat prompt">
                            </textarea>
        </div>

{{--        <div class="card-body d-sm-flex pt-0">--}}
{{--                        --}}
{{--        </div>--}}
        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-2">LLM Restrictions</h5>
            <textarea class="form-control input-bg" id="chatDescription" wire:model="restriction"
                      rows="6" placeholder="Enter chat restrictions"></textarea>
        </div>

{{--        <div class="card-body d-sm-flex pt-0">--}}
{{--                        --}}
{{--        </div>--}}

        <div class="card-body d-sm-flex pt-0 justify-content-end">
            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="update"
                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                update
                <span wire:loading wire:target="update" class="swal2-loader" style="font-size: 8px;">
                </span>
            </button>
        </div>
    </div>

    <div class="card card-bg-white-orange-border mt-4" id="keyword_restriction">
{{--        Error alerts--}}
        @if(session('keyword_restriction_errors'))
            <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                <ul class="alert-text text-white mb-0">
                    @foreach(session('keyword_restriction_errors') as $err)
                        <li>{{ $err[0] }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        @if(session('keyword_restriction_success'))
            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('keyword_restriction_success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        @if(session('keyword_restriction_error'))
            <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('keyword_restriction_error')}}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
{{--        End Error Alerts--}}
        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-2">Keyword Restrictions</h5>
            @foreach($keywords as $keyword)
                <span class="badge badge-info">
                    {{$keyword->word}}
                    <span class="p-1"></span>
                    <span wire:click="removeKeyword({{$keyword->id}})" class="custom-text-dark cursor-pointer">x</span>
                </span>
            @endforeach

            <div class="pt-2">
                <input class="form-control input-bg" wire:model="keyword"
                       rows="10" placeholder="Enter chat keyword restrictions"
                       wire:keydown.enter="createKeyword">
                <span class="text-sm" style="padding-top: 1px; color: #67748e;">Type and press ENTER to save keyword</span>
            </div>
        </div>
{{--        <div class="card-body d-sm pt-0">--}}
{{--        </div>--}}

        <div class="card-header">

            <h5 class="text-orange setting-form-heading py-2">Keyword Restrictions Message</h5>

            <input class="form-control input-bg" wire:model="keyword_restriction_message"
                   rows="10" placeholder="Enter chat keyword restrictions message">
{{--            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="updateKeywordRestrictionMessage"--}}
{{--                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">--}}
{{--                update--}}
{{--                <span wire:loading wire:target="update" class="swal2-loader" style="font-size: 8px;">--}}
{{--                </span>--}}
{{--            </button>--}}
        </div>
{{--        <div class="card-body d-sm pt-0">--}}
{{--        </div>--}}

    </div>
</div>

@push('javascript')

    <script>

        window.livewire.on('hideAlerts', function (){

            setTimeout(function (){

                $('.alert').alert('close');

            }, 5000);

        })

    </script>

@endpush
