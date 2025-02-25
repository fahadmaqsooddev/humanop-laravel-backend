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
                <form wire:submit.prevent="submitForm">
                    <div class="row d-flex justify-content-end align-items-center">
                        <div class="col-md-6 ">
                            @if ($val < $maxVal)
                                <button wire:click="addMore" class="btn btn-primary mt-3"
                                        style="background-color:#f2661c; color: white; border-radius: 8px; padding: 10px 20px;">
                                    Add
                                </button>
                            @endif
                        </div>
                        <div class="col-md-6 ">
                            <div class="d-flex justify-content-end"
                                 style="margin-right: 24px;margin-top: 18px;margin-bottom:10px;">
                                <select name="user" wire:model="user" id="" class="form-control "
                                        style="background-color: #F3DEB4;color: #000000;border-radius:0px">
                                    <option value="">Select User</option>
                                    @if(isset($user_details))
                                        @foreach($user_details as $user_detail)
                                            <option
                                                value="{{$user_detail['id']}}">{{$user_detail['first_name'] .' '. $user_detail['last_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(!empty($modelResponse))
                            @foreach($modelResponse as $response)
                                <div class="col-md-6" style="">
                                    <div class="card bg-dark text-white shadow-sm p-3 mb-3" style="height: 400px;">
                                        <h4 style="margin-left: 27px;color:#f2661c" class="text-bold">{{$response['model']}} Response</h4>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p>{{$response['response']}}</p>
                                            </div>
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

