<div wire:ignore.self class="modal fade" id="ComparisonModal" tabindex="-1" aria-labelledby="ComparisonModal"
     aria-hidden="true">
    <div class="modal-dialog " style="max-width: 93%;">
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
                            {{--                            <button wire:click="addMore" class="btn btn-primary mt-3"--}}
                            {{--                                    style="background-color:#f2661c; color: white; border-radius: 8px; padding: 10px 20px;">--}}
                            {{--                                Add--}}
                            {{--                            </button>--}}
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
                            <div class="col-md-6">
                                <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 400px;">
                                    <select class="form-control input-bg" wire:model="selectedModel1"
                                            style="margin: 7px auto;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <h5 class="text-bold text-white">
                                        LLm Model : <span
                                                style="margin-left: 27px; color: #f2661c;">{{$modelResponse[0]['model']}}</span>
                                    </h5>
                                    <h5 class="text-bold text-white">
                                        Question : <span
                                                style="margin-left: 27px; color: #f2661c;">{{$modelResponse[0]['question']}}</span>
                                    </h5>
                                    <div class="card-body" style="height: 330px; overflow-y: auto;">
                                        {!! html_entity_decode(html_entity_decode($modelResponse[0]['response'])) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 400px;">
                                    <select class="form-control input-bg" wire:model="selectedModel2"
                                            style="margin: 7px auto;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <h5 class="text-bold text-white">
                                        LLm Model : <span
                                                style="margin-left: 27px; color: #f2661c;">{{$modelResponse[1]['model']}}</span>
                                    </h5>
                                    <h5 class="text-bold text-white">
                                        Question : <span
                                                style="margin-left: 27px; color: #f2661c;">{{$modelResponse[1]['question']}}</span>
                                    </h5>
                                    <div class="card-body" style="height: 330px; overflow-y: auto;">
                                        {!! html_entity_decode(html_entity_decode($modelResponse[1]['response'])) !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6" style="">
                                <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 200px;">
                                    <span>Select LLM Model</span>
                                    <select class="form-control input-bg" wire:model="selectedModel1"
                                            style=" margin: 7px auto;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" style="">
                                <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 200px;">
                                    <span>Select LLM Model</span>
                                    <select class="form-control input-bg" wire:model="selectedModel2"
                                            style="margin: 7px auto;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                       wire:model.defer="message" placeholder="Your message....."
                                       class="form-control"
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

