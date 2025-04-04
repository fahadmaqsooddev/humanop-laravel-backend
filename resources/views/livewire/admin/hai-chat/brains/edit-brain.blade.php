
@push('css')

    <style>

        .text-color-dark {
            color: #0f1534 !important;
        }

        input::placeholder {
            color: black !important;
        }

        .card-bg-white-orange-border{
            background-color: white !important;
            /*background-image: linear-gradient(#F3DEBA, #F3DEBA), linear-gradient(90deg, rgb(146, 11, 11), orange, yellow, rgb(22, 200, 22), rgb(0, 238, 255), rgb(26, 58, 222), rgb(4, 19, 113));*/
            border: 2px solid #d26622 !important;
        }

        .input-bg{
            color: black !important;
            border: 1px solid black !important;
        }

        .input-bg::placeholder{
            color: black !important;
        }

        .cluster-buttons{
            background-color: transparent;
            border-radius: 20px;
            border: 1px solid black;
            padding: 5px 10px;
        }

        .configurations-drop-down{
            border: 2px solid black;
            min-width: 250px;
            background-color: transparent;
            border-radius: 20px;
            text-align: center;
            color: black !important;
        }

        .cluster-table-rows{
            padding: 5px;
            border-radius: 10px;
            border: 1px solid black;
        }

    </style>

@endpush

<div>

    <div class="row">
        <h5 class="text-bolder text-center"> BRAIN BUILDING INTERFACE</h5>
    </div>

    <div class="row">

        <div class="card card-bg-white-orange-border mt-4" id="prompt">
            @include('layouts.message')

            <div class="py-5 px-4">

                <div class="py-2">
                    <h5>Name of Brain</h5>
                    <input type="text" class="form-control input-bg" readonly
                           placeholder="Enter name of brain" wire:model.defer="name">
                </div>

                <div class="py-2">
                    <h5>Description of Brain</h5>
                    <textarea rows="5" class="form-control input-bg" wire:model.defer="description"
                              placeholder="Enter description of brain"></textarea>
                </div>


                <div class="py-5">
                    <h4>CONNECT KNOWLEDGE CLUSTERS</h4>

                    <div class="py-2">

                        <div class="row">

                            <div class="col-5">
                                <input type="text" class="cluster-buttons text-center w-100"
                                       placeholder="Keyword Search" wire:model="search_clusters">
                            </div>
                            <div class="col-4">
                                <button class="cluster-buttons">Add selected knowledge cluster to brain</button>
                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="non_active_clusters">

                        @if(count($groups) === 0)

                            <div class="text-center text-color-dark">
                                <p>No cluster found</p>
                            </div>

                        @else

                            <table class="table">
                                <tbody>

                                @foreach($groups as $group)

                                    @if(array_search($group->id, $activeGroupIds) === false)

                                        <tr class="text-color-dark mt-1 cluster-table-rows">
                                            <td class="pt-3">
                                                <input type="checkbox">
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
                        @endif
                    </div>
                </div>

                <div class="py-5">
                    <h4> CONNECTED KNOWLEDGE CLUSTERS</h4>

                    <div class="py-2">

                        <div class="row">

                            <div class="col-5">
                                <input type="text" class="cluster-buttons text-center w-100"
                                       placeholder="Keyword Search" wire:model="search_connected_clusters">
                            </div>
                            <div class="col-5">
                                <button class="cluster-buttons">Remove selected knowledge cluster to brain</button>
                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="active_clusters">

                        @if(count($connectedGroups) === 0)

                            <div class="text-center text-color-dark">
                                <p>No active cluster found</p>
                            </div>

                        @else

                            <table class="table">
                                <tbody>
                                @foreach($connectedGroups as $group)

                                    {{--                                @if(array_search($group->id, $activeGroupIds) !== false)--}}

                                    <tr class="text-color-dark mt-1 cluster-table-rows">
                                        <td class="pt-3">
                                            <input type="checkbox">
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
                        @endif
                    </div>
                </div>

                <div class="py-5">
                    <h4> CONFIGURE LLM MODEL CONNECTION</h4>

                    <div class="py-2">
                        <h6> SELECT LLM MODEL TO CONNECT</h6>
                        <select wire:model.defer="llm_model_id" class="configurations-drop-down">
                            <option value="">Select LLM model</option>
                            @foreach($llmModels as $model)
                                <option value="{{$model['id']}}">{{$model['model_name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="py-2">
                        <h6> TEMPERATURE (CREATIVITY)</h6>
                        <input type="range" wire:model.defer="temperature" min="0" max="1.5" step="0.1" style="min-width: 250px;">
                        <br>
                        <span class="text-secondary" style="font-size: 12px;">
                             Amount of randomness injected into the response. Ranges from 0 to 1.5. Use closer to 0 for analytical/multiple choice, and closer to 1 for creative and generative tasks.
                        </span>
                    </div>

                    <div class="py-2">
                        <h6> MAX TOKENS</h6>
                        <select wire:model.defer="max_tokens" class="configurations-drop-down">
                            <option value="250">250</option>
                            @for($i = 500; $i <= 5000; $i += 500)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="py-2">
                        <h6>CHUNKS</h6>
                        <select wire:model.defer="chunks" class="configurations-drop-down">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

            </div>


            <div class="card-body d-sm-flex pt-0 justify-content-end">
                <button wire:click="updateBrain" class="float-end cluster-buttons py-1 px-3">

                    <span wire:loading.remove wire:target="updateBrain">Update</span>

                    <span wire:loading wire:target="updateBrain">Updating...</span>

                </button>
            </div>
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
