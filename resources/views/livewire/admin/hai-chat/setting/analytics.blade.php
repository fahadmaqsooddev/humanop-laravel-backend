@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #chatDots {
            margin: 32px;
        }
        .chatDot {
            width: 10px;
            height: 10px;
            background-color: #f2661c;
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

        .like,
        .dislike {
            display: inline-block;
            cursor: pointer;
            margin: 10px;
            color: lightgray;
        }

        .dislike:hover,
        .like:hover {
            color: #f2661c;
            transition: all .2s ease-in-out;
            transform: scale(1.1);
        }

        .active {
            color: #f2661c;
        }

        .disabledCard {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
@endpush
<div class="card card-bg-white-orange-border mt-4" id="analytics">

    <div id="chat_switch_loader">

        <div class="spinner-border custom-text-dark invisible" id="chat_switch_spinner" role="status" style="width: 30px; height: 30px;top: 230px;
                position: absolute; left: 400px;">
            <span class="sr-only">
                Loading...
        </span>
        </div>

        <div class="row h-100">


        {{-- {{dd($model_value)}} --}}

        <!-- Chatbot Conversation Section -->
            <div class="col-md-12 col-12 d-flex flex-column container-fluid"
                 style="height: 85vh;">
                @include('layouts.message')

                <div class="row">
                    <div class="col-8">
                        <div class="d-flex justify-content-start" style="margin-left: 24px;margin-top: 14px;">

                            <div style="margin-left: 10px;font-weight: 600;font-size: 20px;margin-top: 5px;margin-bottom:0px;">
                                <p class="mb-0" style="color: #E05A35;line-height: 20px">Select LLM Model</p>
                                {{-- <p class="mb-0" style="color: #000000;font-size: 14px;font-weight: 400">Online</p> --}}
                            </div>
                        </div>
                        <div class="" style="margin-left: 24px;margin-top: 18px;display:flex;align-items:center" >
                            <div class="w-100" wire:ignore>
                                <select class="form-control input-bg"
                                        wire:model="model_value">
                                    <option value="">Select LLM Model</option>
                                    @foreach($modelTypes as $type)
                                        <option value="{{ $type->model_value }}" style="color: black">{{ $type->model_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div wire:click="refreshComponent" style="background-color:#f2661c;margin-left:20px;border-radius:5px;cursor:pointer;">
                                <i class="fa-solid fa-arrows-rotate" style="color: white;padding:10px;"></i>
                            </div>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end" style="margin-right: 24px;margin-top: 18px" >
                            <div class="d-flex justify-content-end w-50" wire:ignore>



                            </div>
                        </div>

                    </div>
                </div>
                <hr style="color: #f2661c;" class="bold">
                <div class="d-flex flex-column justify-content-between flex-grow-1 p-3" id="chat_container"
                     style="overflow-y: auto; margin-bottom:-10px; ">


                    <?php
                    $totalcompletiontokens=0;
                    $totalprompttokens=0;
                    $totalalltokens=0;
                    ?>
                    <div class="table-responsive w-100 "  style="margin-top:-10px;">

                        <table class="table table-flush">
                            <thead class="thead-light" style=" margin-top:-10px;">
                            <tr class="table-text-color text-dark">
                                <th style="color: #f2661c;">Query</th>
                                <th style="color: #f2661c;">Prompt Tokens</th>
                                <th style="color: #f2661c;">Completion Tokens</th>
                                <th style="color: #f2661c;">Total Tokens</th>
                            </tr>
                            </thead>
                            <tbody>
                            color: #f2661c;
                            @foreach($data as $item)

                                @php
                                    $totalcompletiontokens +=  $item['completion_token'];
                                    $totalprompttokens += $item['prompt_token'];
                                    $totalalltokens += $item['total_token'];
                                @endphp

                                <tr>
                                    <td style="color: black">{{ $item['query'] ?? 'N/A' }}</td>
                                    <td style="color: black">{{ $item['prompt_token'] ?? 'N/A' }}</td>
                                    <td style="color: black">{{ $item['completion_token'] ?? 'N/A' }}</td>
                                    <td style="color: black">{{ $item['total_token'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- @else --}}
                        <tr>
                            <td style="color: #f2661c;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        {{-- @endif --}}
                    </div>

                    @if($totalcompletiontokens > 0 && $totalprompttokens > 0 && $totalalltokens > 0)
                        <div style="color: black; width:35%;margin-left:auto;margin-top:3rem;">
                            <div style="display: flex;">
                                <span>Total Completion Tokens : </span>
                                <p style="padding-left:5px; ">{{ $totalcompletiontokens }}</p>
                            </div>
                            <div style="display: flex;">
                                <span>Total Prompt Tokens : </span>
                                <p style="padding-left:5px; ">{{ $totalprompttokens }}</p>
                            </div>
                            <div style="display: flex;">
                                <span>Total All Tokens : </span>
                                <p style="padding-left:5px; ">{{ $totalalltokens }}</p>
                            </div>
                        </div>
                    @endif


                </div>
                <hr>

            </div>
        </div>

    </div>

</div>
@push('javascript')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endpush

@push('js')



@endpush
