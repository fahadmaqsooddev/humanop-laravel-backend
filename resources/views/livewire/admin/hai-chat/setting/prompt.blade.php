@push('css')

    <style>
        .new-orange-button{
            background-color: #F95520 !important;
            padding: 10px 20px 10px 20px;
            border-radius: 8px;
            color: white;
            border-color: transparent;
            cursor: pointer;
            font-weight: 800;
        }

        .new-orange-button:hover{
            color: white;
        }

    </style>

@endpush
<div>

    <div class="card card-bg-white-orange-border mt-4" id="prompt">
        @include('layouts.message')
        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-2">BASE PROMPT (Defining The Personality)</h5>
            <textarea class="form-control input-bg change-input-form" id="chatDescription" wire:model.defer="prompt"
                      rows="6" placeholder="Enter base prompt"></textarea>
        </div>

        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-2"> LLM RESTRICTIONS (Guardrails of Expression)</h5>
            <textarea class="form-control input-bg change-input-form" id="chatDescription" wire:model.defer="restriction"
                      rows="6" placeholder="Enter llm restrictions"></textarea>
        </div>

        <div class="card-header">

{{--            <input type="checkbox" wire:model.defer="is_training">--}}
            <span class="text-orange" style="font-weight: 700;font-size: 22px;">
                Want to add this persona for training ?
            </span>
            <div class="w-25 d-flex justify-content-between custom-text-dark">
                <div>
                    <input type="radio" wire:model.defer="is_training" value="1" name="radio" style="accent-color:#F95520; cursor: pointer;">&nbsp;
                    <span>Yes</span>
                </div>
                <div>
                    <input type="radio" wire:model.defer="is_training" value="0" name="radio" style="accent-color:#F95520; cursor: pointer;">&nbsp;
                    <span>No</span>
                </div>
            </div>

        </div>

        <div class="card-body d-sm-flex pt-0 justify-content-end">

            @if($prompt)

                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="update"
                        class="mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end new-orange-button navButtonResponsive update-button">
                    update
                    <span wire:loading wire:target="update" style="font-size: 8px;" class="swal2-loader">
                </span>
                </button>

            @else

                <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="update"
                        class="mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end new-orange-button navButtonResponsive update-button">
                    save
                    <span wire:loading wire:target="update" style="font-size: 8px;" class="swal2-loader">
                </span>
                </button>

            @endif
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
                <span class="badge" style="background-color: #F3DEBA; color: black; border: 1px solid #f2661c;">
                    {{$keyword->word}}
                    <span class="p-1"></span>
                    <span wire:click="removeKeyword({{$keyword->id}})" class="custom-text-dark cursor-pointer">x</span>
                </span>
            @endforeach

            <div class="pt-2">
                <input class="form-control input-bg" wire:model.defer="keyword"
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
        </div>

    </div>
</div>

@push('javascript')

    <script>

        window.livewire.on('hideAlerts', function (){

            var prompt = document.getElementById("prompt");
            prompt.scrollIntoView();

            setTimeout(function (){

                $('.alert').alert('close');

            }, 5000);

        })

    </script>

@endpush
