
@push('css')

    <style>

        .text-color-dark {
            color: #0f1534 !important;
        }

        input::placeholder {
            color: black !important;
        }

        .card-bg-white-orange-border{
            background-color: #eaf3ff !important;
            /*border: 2px solid #1b3a62 !important;*/
        }

        .input-bg{
            background-color: #F4ECE0 !important;
            color: #1b3a62 !important;
            border-radius: 40px !important;
            border: none !important;
        }

        .input-bg::placeholder{
            color: #1b3a62 !important;
        }

        .cluster-buttons{
            background-color: #1b3a62 !important;
            color: #F4ECE0;
            padding: 5px 10px;
            border-radius: 32px;
            border-width: 2px;
            border: none;
        }

        .configurations-drop-down{
            min-width: 250px;
            text-align: center;
            padding: 7px;
            background-color: #F4ECE0 !important;
            color: #1b3a62 !important;
            border-radius: 40px !important;
            border: none !important;
        }

        .cluster-table-rows{
            padding: 5px;
            /*border: 1px solid #1b3a62;*/
        }

        h5, h4, h6, .text-color-orange{
            color: #1b3a62 !important;
        }

    </style>

@endpush

<div>

    <div class="row">
        <h5 class="text-bolder text-center"> BRAIN BUILDING INTERFACE</h5>
    </div>

    <div class="row">

        <div class="card card-bg-white-orange-border mt-4" id="prompt">

            <div class="py-5 px-4">

                <div class="py-2">
                    <h5>Name of Brain</h5>
                    <input type="text" class="form-control input-bg change-input-form"
                           placeholder="Enter name of brain" wire:model.defer="brain_name">
                </div>

                <div class="py-2">
                    <h5>Description of Brain</h5>
                    <textarea rows="5" class="form-control input-bg change-input-form" wire:model.defer="description"
                              placeholder="Enter description of brain"></textarea>
                </div>


                <div class="py-5">
                    <h4>CONNECT KNOWLEDGE CLUSTERS</h4>

                    <div class="py-2">

                        <div class="row">

                            <div class="col-8">
                                <input type="text" class="input-bg px-3 py-1 w-100"
                                       placeholder="Keyword Search" wire:model="search_clusters">
                            </div>
                            <div class="col-4">
                                <button wire:click="addAllClustersToActiveClusters"
                                    class="cluster-buttons">Add selected knowledge cluster to brain</button>
                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="non_active_clusters">

                        @if(count($groups) === 0)

                            <div class="text-center text-color-dark">
                                <p class="text-color-orange">No cluster found</p>
                            </div>

                        @else

                            <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                                <table class="table">
                                    <tbody>

                                    @foreach($groups as $group)

                                        @if(array_search($group->id, $activeGroupIds) === false)

                                            <tr class="text-color-dark mt-1 cluster-table-rows">
                                                <td class="pt-3">
                                                    <input wire:click="selectCluster({{$group->id}})" type="checkbox">
                                                </td>
                                                <td>
                                                    {{$group->name}}
                                                </td>
                                                <td>
                                                    <button wire:click="addToCluster({{$group->id}})" class="cluster-buttons">ADD CLUSTER</button>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-content-center">
                                                        <a href="{{route('admin_embedding', ['id' => $group->id])}}" class="cluster-buttons">VIEW/EDIT CLUSTER</a>
                                                    </div>
                                                </td>
                                            </tr>

                                        @endif

                                    @endforeach
                                    </tbody>
                                </table>

                            </div>

                        @endif
                    </div>
                </div>

                <div class="py-5">
                    <h4> CONNECTED KNOWLEDGE CLUSTERS</h4>

                    <div class="py-2">

                        <div class="row">

                            <div class="col-7">
                                <input type="text" class="input-bg py-1 px-3 w-100"
                                       placeholder="Keyword Search" wire:model="search_connected_clusters">
                            </div>
                            <div class="col-5">
                                <button wire:click="removeAllSelectedClusters"
                                    class="cluster-buttons">Remove selected knowledge cluster to brain</button>
                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="active_clusters">

                        @if(count($connectedGroups) === 0)

                            <div class="text-center text-color-dark">
                                <p class="text-color-orange">No active cluster found</p>
                            </div>

                        @else

                            <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                                <table class="table">
                                    <tbody>
                                    @foreach($connectedGroups as $group)

                                        {{--                                @if(array_search($group->id, $activeGroupIds) !== false)--}}

                                        <tr class="text-color-dark mt-1 cluster-table-rows">
                                            <td class="pt-3">
                                                <input wire:click="selectClusterForRemove({{$group->id}})" type="checkbox">
                                            </td>
                                            <td>
                                                {{$group->name}}
                                            </td>
                                            <td>
                                                <button wire:click="removeFromCluster({{$group->id}})" class="cluster-buttons">REMOVE CLUSTER</button>
                                            </td>
                                            <td>
                                                <div class="d-flex align-content-center">
                                                    <a href="{{route('admin_embedding', ['id' => $group->id])}}" class="cluster-buttons">VIEW/EDIT CLUSTER</a>
                                                </div>
                                            </td>
                                        </tr>

                                        {{--                                @endif--}}

                                    @endforeach
                                    </tbody>
                                </table>

                            </div>

                        @endif
                    </div>
                </div>

                <div class="py-5">
                    <h4> CONFIGURE LLM MODEL CONNECTION</h4>

                    <div class="py-2">
                        <h6> SELECT LLM MODEL TO CONNECT</h6>
                        <select wire:model.defer="llm_model_id" class="configurations-drop-down change-input-form">
                            <option value="">Select LLM model</option>
                            @foreach($llmModels as $model)
                                <option value="{{$model['id']}}">{{$model['model_name']}}</option>
                            @endforeach
                            <option value="5">Bedrock - Deepseek</option>
                        </select>
                    </div>

                    <div class="py-2">
                        <h6> TEMPERATURE (CREATIVITY)</h6>
                        <input type="range" wire:model.debounce="temperature" min="0" max="1.5" step="0.1" style="min-width: 250px;">
                        <span style="color: black;">{{$temperature}}</span>
                        <br>
                        <span class="text-secondary" style="font-size: 12px;">
                             Amount of randomness injected into the response. Ranges from 0 to 1.5. Use closer to 0 for analytical/multiple choice, and closer to 1 for creative and generative tasks.
                        </span>
                    </div>

                    <div class="py-2">
                        <h6> MAX TOKENS</h6>
                        <select wire:model.defer="max_tokens" class="configurations-drop-down change-input-form">
                            <option value="250">250</option>
                            @for($i = 500; $i <= 5000; $i += 500)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="py-2">
                        <h6>CHUNKS</h6>
                        <select wire:model.defer="chunks" class="configurations-drop-down change-input-form">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

            </div>


            <div class="card-body d-sm-flex pt-0 justify-content-end">

                @if($is_published)

                    <button class="float-end cluster-buttons py-1 px-3" style="background-color: gray !important;">

                        <span>Published</span>

                    </button>

                @else

                    <button wire:click="publishChatBot" class="float-end cluster-buttons py-1 px-3">

                        <span wire:loading.remove wire:target="publishChatBot">Publish</span>

                        <span wire:loading wire:target="publishChatBot">Publishing...</span>

                    </button>

                @endif

                &nbsp;

                <button wire:click="updateBrain" class="float-end cluster-buttons py-1 px-3 update-button">

                    <span wire:loading.remove wire:target="updateBrain">Update</span>

                    <span wire:loading wire:target="updateBrain">Updating...</span>

                </button>
            </div>

            @include('layouts.message')

        </div>

    </div>

</div>

@push('js')

    <script>

        const nonActiveClusters = document.querySelector('#non_active_clusters');
        nonActiveClusters.addEventListener('wheel', (event) => {
            event.preventDefault();

            nonActiveClusters.scrollBy({
                top: event.deltaY < 0 ? -30 : 30
            });
        });


        const activeClusters = document.querySelector('#active_clusters');
        activeClusters.addEventListener('wheel', (event) => {
            event.preventDefault();

            activeClusters.scrollBy({
                top: event.deltaY < 0 ? -30 : 30
            });
        });

    </script>

    {{--    <script language="JavaScript">--}}
    {{--        window.onbeforeunload = confirmExit;--}}
    {{--        function confirmExit() {--}}
    {{--            return "Please click Update. Unsaved changes will be lost.";--}}
    {{--        }--}}
    {{--    </script>--}}

@endpush
