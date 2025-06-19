@push('css')
    <style>
        .input-styling{
            background: #eaf3ff !important;
            box-shadow: 0 8px 20px 0 #0000001A !important;
            border-radius: 20px !important;
            border: none !important;
            color: black !important;
        }

        #chatDots {
            margin: 32px;
        }
        .chatDot {
            width: 10px;
            height: 10px;
            background-color: #1b3a62;
            display: inline-block;
            margin: 1px;
            border-radius: 50%;
        }

        .chatDot:nth-child(1) {
            animation: bounce 1s infinite;
        }

        .chatDot:nth-child(2) {
            animation: bounce 1s infinite .2s;
        }

        .chatDot:nth-child(3) {
            animation: bounce 1s infinite .4s;
        }
        @keyframes bounce {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        input::placeholder{
            color: black !important;
        }
    </style>
@endpush
<div>
    <div class="py-2" style="margin-bottom:1rem;">
        <h3
            style="color: #1C365E;font-weight: 700;font-size: 28px;line-height: 16.5px;vertical-align: middle;text-transform: capitalize;">
            Model Comparison
        </h3>
    </div>
    <div style="max-width: 93%; padding: 0px; border-radius: 30px;">
        <div>
            <div>
                @include('layouts.message')
                <div class="py-1">
{{--                    <select class="form-control input-bg w-50" wire:model="chat_bot_id">--}}
{{--                        <option value="">Select ChatBot</option>--}}
{{--                        @foreach($chatBots as $chatBot)--}}

{{--                            <option value="{{$chatBot['id']}}">{{$chatBot['name']}}</option>--}}

{{--                        @endforeach--}}
{{--                    </select>--}}
                    <select class="dropdown input-styling"
                            style="padding: 10px; display: block; width: 49%; background-color: #F6BA81!important;"
                            wire:model.defer="chat_bot_id">
                        <option value="">Select Chat-bot</option>
                        @foreach($chatBots as $chatBot)

                            <option value="{{$chatBot['id']}}">{{$chatBot['name']}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 pt-2">
                    @if (empty($modelResponse))
                        @if ($val < $maxVal)
                            {{--                            <button wire:click="addMore" class="btn btn-primary mt-3"--}}
                            {{--                                    style="background-color:#1b3a62; color: white; border-radius: 8px; padding: 10px 20px;">--}}
                            {{--                                Add--}}
                            {{--                            </button>--}}
                        @endif
                    @else

                        <div wire:click="refreshComponent"
                             style="background-color:#1b3a62; color: white; border-radius: 8px;cursor:pointer;width:40px;margin-bottom:10px;">
                            <i class="fa-solid fa-arrows-rotate" style="color: white;padding:10px;"></i>
                        </div>
                    @endif

                </div>
                <form wire:submit.prevent="submitForm">

                    <div class="row py-2">
                        @if(!empty($modelResponse))
                            <div class="col-md-6">
                                <div class="card text-white shadow-sm p-3 mb-3" style="height: 400px;background-color: #F6BA81 !important;">
                                    <select class="dropdown input-styling" wire:model="selectedModel1"
                                            style="margin: 7px auto;padding: 10px; width: 100%;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <h5 class="text-bold custom-text-dark">
                                        LLm Model : <span
                                                style="margin-left: 27px; color: #1b3a62;">{{$modelResponse[0]['model']}}</span>
                                    </h5>
                                    <h5 class="text-bold custom-text-dark">
                                        Question : <span
                                                style="margin-left: 27px; color: #1b3a62;">{{$modelResponse[0]['question']}}</span>
                                    </h5>
                                    <div class="card-body custom-text-dark" style="height: 330px; overflow-y: auto;">
                                        {!! html_entity_decode(html_entity_decode($modelResponse[0]['response'])) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-white shadow-sm p-3 mb-3" style="height: 400px;background-color: #F6BA81 !important;">
                                    <select class="dropdown input-styling" wire:model="selectedModel2"
                                            style="margin: 7px auto;padding: 10px; width: 100%;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                    <h5 class="text-bold custom-text-dark">
                                        LLm Model : <span
                                                style="margin-left: 27px; color: #1b3a62;">{{$modelResponse[1]['model']}}</span>
                                    </h5>
                                    <h5 class="text-bold custom-text-dark">
                                        Question : <span
                                                style="margin-left: 27px; color: #1b3a62;">{{$modelResponse[1]['question']}}</span>
                                    </h5>
                                    <div class="card-body custom-text-dark" style="height: 330px; overflow-y: auto;">
                                        {!! html_entity_decode(html_entity_decode($modelResponse[1]['response'])) !!}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6" style="">
                                <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 200px; background-color: #F6BA81 !important;">
{{--                                    <span>Select LLM Model</span>--}}
                                    <select class="dropdown input-styling" wire:model="selectedModel1"
                                            style="margin: 7px auto;padding: 10px; width: 100%;">
                                        <option value="">Select LLM Model</option>
                                        @foreach($modelTypes as $model)
                                            <option value="{{ $model['model_value'] }}"
                                                    style="color: black">{{ $model['model_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" style="">
                                <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 200px; background-color: #F6BA81 !important;">
{{--                                    <span>Select LLM Model</span>--}}
                                    <select class="dropdown input-styling" wire:model="selectedModel2"
                                            style="margin: 7px auto;padding: 10px; width: 100%;">
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
                        <div style="background-color: #F6BA81; border-radius: 32px; margin-left: 10px; width: 98%;">
                            <div class="d-flex justify-content-between"
                                 style="margin-left: 4px;margin-right: 0;margin-bottom: 10px; margin-top: 10px;">
                                <div style="width: 100%">
                                    <input type="text" wire:loading.attr="disabled" wire:target="user_id"
                                           wire:model.defer="message" placeholder="Your message....."
                                           class="input-styling"
                                           style="padding: 4px;border-radius: 20px;padding-left: 10px;padding-right: 10px; width: 100%;">
                                </div>
                                <div style="width: 5%" class="pt-1">
                                    <button class="bg-transparent" type="submit" style="border:none" id="">
                                        <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}" width="25"
                                             height="25">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

