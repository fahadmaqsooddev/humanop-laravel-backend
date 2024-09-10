<div wire:ignore.self class="modal fade" id="answerQueryModal{{$query['id']}}" tabindex="-1" role="dialog"
     aria-labelledby="answerQueryModal{{$query['id']}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="background-color: #0f1535; border-radius: 9px">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fs-4 text-white">Query Answer</label>
                            <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-answer-modal-button-{{$query['id']}}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            @include('layouts.message')
                            <form wire:submit.prevent="submitForm">
                                <div class="form-group mt-4">
                                    <label class="form-label fs-6 text-white">Client Query:</label>
                                    <span
                                        style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$query['query'] ?? null}}</span>
                                    <label class="form-label fs-6 text-white mt-2">HAI Answer:</label>
                                    <br>
                                    <span class="mt-2">{!!$query['haiChatMessage']['answer'] ?? null!!}</span>
                                    <br>
                                    <label class="form-label fs-6 text-white mt-4">Answer:</label>
                                    <textarea rows="4" class="form-control text-white mt-2"
                                              style="background-color: #0f1535"
                                              wire:model.defer="answer" id="message-text"
                                              placeholder="Type your answer here...">
                                    </textarea>
                                </div>
                                <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mb-4 mt-4">
                        <div class="card" style="height: 500px;" style="border-radius: 3rem !important;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between">
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)">
                                        CORE STATS</p>
                                    <p class="text-sm mt-3 text-white text-bold" style="color: rgb(160, 174, 192)">
                                        Interval of Life: (<span class="text-bold text-sm"
                                                                 style="color: #f2661c">{{$user_age}}</span>)</p>
                                </div>
                                <p class="text-sm mt-3" style="color: rgb(160, 174, 192)"> Top 3 Traits:</p>
                                <div class="d-flex" style="margin-top: -10px">
                                    @if($topThreeStyles)
                                        @foreach($topThreeStyles as $index => $style)
                                            <p class="fw-bold" style="color: #f2661c">
                                                ({{ $style }}) {{ $index }}@if(!$loop->last),@endif
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Motivational Drivers:</p>
                                <div class="d-flex" style="margin-top: -10px">
                                    @if($topTwoFeatures)
                                        @foreach($topTwoFeatures as $index => $feature)
                                            <p class="fw-bold" style="color: #f2661c">
                                                ({{ $feature }}) {{ $index }}@if(!$loop->last),@endif
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Tolerance Boundaries:</p>
                                @if($boundary)
                                    <p class="fw-bold" style="color: #f2661c; margin-top: -10px">
                                        ({{ $boundary['code_number'] ?? '' }}) {{ $boundary['public_name'] ?? '' }}
                                    </p>
                                @endif
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Communication Styles:</p>
                                <div class="d-flex" style="margin-top: -10px">
                                    @if($topCommunication)
                                        @foreach($topCommunication as $communication)
                                            <p class="fw-bold" style="color: #f2661c">
                                                {{ $communication }}@if(!$loop->last),@endif
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                                <p class="text-sm" style="color: rgb(160, 174, 192)"> Perception of Life:</p>
                                <p class="fw-bold" style="color: #f2661c; margin-top: -10px">
                                    {{ $preception == 40 ? "Negative" : ($preception == 41 ? "Neutral" : ($preception == 42 ? "Positive" : '')) }}
                                </p>
                                <p class="text-sm" style="color: rgb(160, 174, 192)">Energy Pool:</p>
                                @if($energyPool)
                                    <p class="fw-bold" style="color: #f2661c; margin-top: -10px">
                                        {{ $energyPool }}
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

@push('javascript')
    <script>
        window.Livewire.on('closeAnswerModal', function (e) {

            $('#close-answer-modal-button-' + e.id).click();
        })
    </script>
@endpush
