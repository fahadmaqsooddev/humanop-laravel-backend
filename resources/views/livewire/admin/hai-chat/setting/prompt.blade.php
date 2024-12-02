<div>

    <div class="card setting-box-background mt-4" id="prompt">
        @include('layouts.message')
        <div class="card-header">
            <h5 class="text-color-dark setting-form-heading">Base Prompt</h5>
        </div>

        <div class="card-body d-sm-flex pt-0">
                        <textarea class="form-control" id="chatDescription" wire:model="prompt"
                                  style="background-color: #8bb1ab; color: #0f1535" rows="10"
                                  placeholder="Enter chat prompt">
                            </textarea>
        </div>
        <div class="card-header">
            <h5 class="text-color-dark setting-form-heading">LLM Restrictions</h5>
        </div>
        <div class="card-body d-sm-flex pt-0">
                        <textarea class="form-control" id="chatDescription" wire:model="restriction"
                                  style="background-color: #8bb1ab; color: #0f1535" rows="10"
                                  placeholder="Enter chat restrictions"></textarea>
        </div>
        <div class="card-header">
            <h5 class="text-color-dark setting-form-heading">Keyword Restrictions</h5>
            @foreach($keywords as $keyword)
                <span class="badge badge-info">
                    {{$keyword->word}}
                    <span class="p-1"></span>
                    <span wire:click="removeKeyword({{$keyword->id}})" class="custom-text-dark cursor-pointer">x</span>
                </span>
            @endforeach
        </div>
        <div class="card-body d-sm pt-0">
                        <input class="form-control" wire:model="keyword"
                                  style="background-color: #8bb1ab; color: #0f1535" rows="10"
                                  placeholder="Enter chat keyword restrictions"
                                  wire:keydown.enter="createKeyword">
            <span class="text-sm" style="padding-top: 1px; color: #67748e;">Type and press ENTER to save keyword</span>
        </div>
        <div class="card-body d-sm-flex pt-0 justify-content-end">
            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="update"
                    class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end rainbow-border-user-nav-btn navButtonResponsive">
                update
            </button>
        </div>

    </div>
</div>
