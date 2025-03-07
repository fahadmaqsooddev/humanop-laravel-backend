<div wire:ignore.self class="modal fade" id="ComparisonModal" tabindex="-1" aria-labelledby="ComparisonModal"
     aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex justify-content-between " style="margin-bottom:1rem;">
                    <h5 class="modal-title text-white" id="createChatModalLabel">Comparison Model</h5>
                    <button type="button" class="close modal-close-btn new-orange-button" id="ComparisonModal"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @include('layouts.message')
                <div class="col-md-6 ">
                    @if (empty($modelResponse))
                        @if ($val < $maxVal)
                            <button wire:click="addMore" class="btn btn-primary mt-3"
                                    style="background-color:#f2661c; color: white; border-radius: 8px; padding: 10px 20px;">
                                Add
                            </button>
                        @endif
                    @else

                        <div wire:click="refreshComponent"
                             style="background-color:#f2661c; color: white; border-radius: 8px;cursor:pointer;width:40px;margin-bottom:10px;">
                            <i class="fa-solid fa-arrows-rotate" style="color: white;padding:10px;"></i>
                        </div>
                    @endif

                </div>
                <form wire:submit.prevent="submitForm">

                    <div class="row">
                        @if(!empty($modelResponse))
                            @foreach($modelResponse as $response)
                                <div class="col-md-6">
                                    <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 400px;">
                                        <h5 class="text-bold text-white">
                                           LLm Model : <span style="margin-left: 27px; color: #f2661c;">{{$response['model']}}</span>
                                        </h5>
                                        <h5 class="text-bold text-white">
                                            Question : <span style="margin-left: 27px; color: #f2661c;">{{$response['question']}}</span>
                                        </h5>
                                        <div class="card-body" style="height: 330px; overflow-y: auto;">
                                            {!! html_entity_decode(html_entity_decode($response['response'])) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach(array_slice($modelTypes, 0, $val) as $model)
                                <div class="col-md-6" style="">
                                    <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 200px;">
                                        <span style="margin-left: 27px;">Select LLM Model</span>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="card-title m-0 "
                                                    style="color: white">{{ $model['model_name'] }}</h5>
                                                <input type="checkbox" name="selectedModels" wire:model="selectedModels"
                                                       value="{{ $model['model_value'] }}" class="form-check-input">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <div class="row">
                        <div id="chatLoader" style="display: flex; justify-content:flex-start" wire:ignore.self>
                            <div id="chatDots" wire:loading wire:target="submitForm">
                                <span class="chatDot"></span>
                                <span class="chatDot"></span>
                                <span class="chatDot"></span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between"
                             style="margin-left: 4px;margin-right: 0px;margin-bottom: 14px">
                            <div style="width: 100%">
                                <input type="text" wire:loading.attr="disabled" wire:target="user_id"
                                       wire:model.defer="message" placeholder="Your message....." class="form-control"
                                       style="padding: 4px;border-radius: 20px;padding-left: 10px;padding-right: 10px">
                            </div>
                            <div style="width: 5%" class="pt-1">
                                <button class="bg-transparent" type="submit" style="border:none" id="">
                                    <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}" width="25"
                                         height="25">
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

