
@push('css')

    <style>

        .text-color-dark {
            color: #0f1534 !important;
        }

        .card-bg-white-orange-border{
            background-color: #F4E3C7 !important;
            /*border: 2px solid #d26622 !important;*/
        }

        .input-bg{
            background-color: #F4ECE0 !important;
            color: #F95520 !important;
            border-radius: 40px !important;
            border: none !important;
        }

        .input-bg::placeholder{
            color: #F95520 !important;
        }

        .cluster-buttons{
            background-color: #F95520 !important;
            color: #F4ECE0;
            padding: 5px 10px;
            border-radius: 32px;
            border-width: 2px;
            border: none;
        }

        .configurations-drop-down{
            min-width: 250px;
            text-align: center;
            background-color: #F4ECE0 !important;
            color: #F95520 !important;
            border-radius: 40px !important;
            border: none !important;
            padding: 7px;
        }

        .cluster-table-rows{
            padding: 5px;
            /*border: 1px solid #F95520;*/
        }

        h5, h4, h6, .text-color-orange{
            color: #F95520 !important;
        }

        .cluster-badge{
            border-radius: 5px;
            background-color:#F4E3C7 !important;
            color: #F95520 !important;
        }

        .add-selected-btn{
            background-color: #F95520 !important;
            color: #F4ECE0;
            padding: 8px 13px;
            border-radius: 32px;
            border-width: 2px;
            border: none;
            font-size: 14px;
            font-weight: 600;

        }

    </style>

@endpush

<div>

    <div class="row">
        <h5 class="text-bolder">EDIT KNOWLEDGE CLUSTER</h5>
    </div>

    <div class="row">

        <div class="card card-bg-white-orange-border mt-4" id="prompt">

            <div class="py-5 px-4">

                <div class="py-2">
                    <h5>NAME OF KNOWLEDGE CLUSTER</h5>
                    <input type="text" class="form-control input-bg change-input-form"
                           placeholder="Enter name of knowledge cluster" wire:model.defer="name">
                </div>

                <div class="py-2">
                    <h5>DESCRIPTION OF KNOWLEDGE CLUSTER</h5>
                    <textarea rows="5" class="form-control input-bg change-input-form" wire:model.defer="description"
                              placeholder="Enter description of knowledge cluster"></textarea>
                </div>


                <div class="py-5">
                    <h4>CURRENT KNOWLEDGE SOURCES IN CLUSTER</h4>

                    <div class="py-2">

                        <div class="row py-1">

                            <div class="col-10">

                                <div class="d-flex justify-content-between">

                                    <div>
                                        <input wire:click="selectAllCurrentEmbeddings" type="checkbox">
                                        <label>Select all</label>
                                    </div>
                                    <select class="configurations-drop-down" wire:model="current_bulk_option">
                                        <option value="0">Bulk Options</option>
                                        <option value="1">Remove from Cluster</option>
                                    </select>

                                    <input type="text" wire:model="search_current_embedding" placeholder="Keyword Filter" style="width: 400px;" class="input-bg text-center">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="connected_cluster">

                        @if(count($currentEmbeddings) === 0)

                            <div class="text-center text-color-dark">
                                <p class="text-color-orange">No knowledge connected with this cluster.</p>
                            </div>

                        @else

                            <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                                <table class="table">
                                    <tbody>

                                    @foreach($currentEmbeddings as $embedding)

                                        <tr class="text-color-dark mt-1 cluster-table-rows">
                                            <td class="pt-3" style="font-size: 12px;">
                                                <input type="checkbox" wire:click="selectIndividualCurrentEmbedding({{$embedding['embedding']['id']}})" {{in_array($embedding['embedding']['id'], $selectedCurrentEmbeddings) ? 'checked' : ''}}>
                                                <span>
                                                    {{$embedding['embedding']['name']}} [{{$embedding['embedding']['created_at']}}] [{{$embedding['embedding']['updated_at']}}]
                                                </span>
                                            </td>
                                            <td>
{{--                                                @foreach($embedding['groups'] as $group)--}}
{{--                                                    @if(!empty($group['group']))--}}
{{--                                                        <span class="badge cluster-badge">{{$group['group']['name']}}</span>--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
                                            </td>
                                            <td class="float-end">
                                                <button wire:click="editEmbedding({{$embedding['embedding']['id']}})"
                                                        class="cluster-buttons">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                @if($embedding['embedding']['ready_for_training'])
                                                    <button wire:click="reTrainFile({{$embedding['embedding']['id']}})" class="cluster-buttons">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @else
                                                    <button class="cluster-buttons" style="background-color: darkgray !important;">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @endif
                                                <button onclick="deleteCurrentEmbedding({{$embedding['embedding']['id']}})" class="cluster-buttons">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endif
                    </div>
                </div>

                <div class="py-5">
                    <h4>SEARCH AND SELECT KNOWLEDGE SOURCES TO ADD</h4>

                    <div class="py-2">

                        <div class="row py-1">

                            <div class="col-10">

                                <div class="d-flex justify-content-between">

                                    <div>
                                        <input wire:click="selectAllEmbeddings" type="checkbox">
                                        <label>Select all</label>
                                    </div>
                                    <select class="configurations-drop-down" wire:model="bulk_option">
                                        <option value="0">Bulk Options</option>
                                        <option value="1">Add to Queue</option>
                                    </select>

                                    <input type="text" wire:model="search_embedding" placeholder="Keyword Filter" style="width: 400px;" class="input-bg text-center">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="non_active_clusters">

                        @if(count($embeddings) === 0)

                            <div class="text-center text-color-dark">
                                <p class="text-color-orange">No knowledge found</p>
                            </div>

                        @else

                            <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                                <table class="table">
                                    <tbody>

                                    @foreach($embeddings as $embedding)

                                        <tr class="text-color-dark mt-1 cluster-table-rows">
                                            <td class="pt-3" style="font-size: 12px;">
                                                <input type="checkbox" wire:click="selectIndividualEmbedding({{$embedding->id}})" {{in_array($embedding['id'], $selectedEmbeddings) ? 'checked' : ''}}>
                                                <span>
                                                    {{$embedding['name']}} [{{$embedding['created_at']}}] [{{$embedding['updated_at']}}]
                                                </span>
                                            </td>
                                            <td>
                                                {{--                                                @foreach($embedding['groups'] as $group)--}}
                                                {{--                                                    @if(!empty($group['group']))--}}
                                                {{--                                                        <span class="badge cluster-badge">{{$group['group']['name']}}</span>--}}
                                                {{--                                                    @endif--}}
                                                {{--                                                @endforeach--}}
                                            </td>
                                            <td class="float-end">
                                                <button wire:click="editEmbedding({{$embedding['id']}})"
                                                        class="cluster-buttons">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                @if($embedding['ready_for_training'])
                                                    <button wire:click="reTrainFile({{$embedding['id']}})" class="cluster-buttons">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @else
                                                    <button class="cluster-buttons" style="background-color: darkgray !important;">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @endif
{{--                                                <button class="cluster-buttons" >--}}
{{--                                                    <i class="fas fa-trash"></i>--}}
{{--                                                </button>--}}
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endif
                    </div>
                    @if(count($embeddings) > 0)
                        <div class="py-3 float-end">
                            <button wire:click="addToSelectedEmbeddings" class="add-selected-btn">ADD SELECTED</button>
                        </div>
                    @endif
                </div>

                <div class="py-5">
                    <h4>SELECTED KNOWLEDGE SOURCES</h4>

                    <div class="py-2">

                        <div class="row py-1">

                            <div class="col-10">

                                <div class="d-flex justify-content-between">

                                    <div>
                                        <input wire:click="selectConnectedEmbeddings" type="checkbox">
                                        <label>Select all</label>
                                    </div>
                                    <select class="configurations-drop-down" wire:model="connected_bulk_option">
                                        <option value="0">Bulk Options</option>
                                        <option value="1">Remove from Queue</option>
                                    </select>

                                    <input type="text" wire:model="search_connected_embedding" placeholder="Keyword Filter" style="width: 400px;" class="input-bg text-center">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="py-2" style="max-height: 400px; overflow-y: scroll;" id="non_active_clusters">

                        @if(count($selectedKnowledgeSource) === 0)

                            <div class="text-center text-color-dark">
                                <p class="text-color-orange">No connected knowledge found</p>
                            </div>

                        @else

                            <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                                <table class="table">
                                    <tbody>

                                    @foreach($selectedKnowledgeSource as $embedding)

                                        <tr class="text-color-dark mt-1 cluster-table-rows">
                                            <td class="pt-3" style="font-size: 12px;">
                                                <input type="checkbox" wire:click="selectConnectedIndividualEmbedding({{$embedding->id}})" {{in_array($embedding['id'], $selectedConnectedEmbeddings) ? 'checked' : ''}}>
                                                <span>
                                                    {{$embedding['name']}} [{{$embedding['created_at']}}] [{{$embedding['updated_at']}}]
                                                </span>
                                            </td>
                                            <td>
{{--                                                @foreach($embedding['groups'] as $group)--}}
{{--                                                    @if(!empty($group['group']))--}}
{{--                                                        <span class="badge cluster-badge">{{$group['group']['name']}}</span>--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
                                            </td>
                                            <td class="float-end">
                                                <button wire:click="editEmbedding({{$embedding['id']}})"
                                                        class="cluster-buttons">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                @if($embedding['ready_for_training'])
                                                    <button wire:click="reTrainFile({{$embedding['id']}})" class="cluster-buttons">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @else
                                                    <button class="cluster-buttons" style="background-color: darkgray !important;">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
                                                @endif
                                                <button onclick="deleteEmbedding({{$embedding['id']}})" class="cluster-buttons">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        @endif
                    </div>
                </div>


            </div>


            <div class="card-body d-sm-flex pt-0 justify-content-end">
                <button wire:click="updateCluster" class="float-end cluster-buttons py-1 px-3">

                    <span wire:loading.remove wire:target="updateCluster">SAVE</span>

                    <span wire:loading wire:target="updateCluster">SAVING...</span>

                </button>
            </div>

            @include('layouts.message')

        </div>

    </div>


    <div wire:ignore.self class="modal fade" id="editEmbeddingModel" tabindex="-1"
         role="dialog"
         aria-labelledby="editEmbeddingModel" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: #1C365E !important; border-radius: 32px;">
                <div class="modal-body" style="padding: 25px 30px;">

                    <div class="d-flex justify-content-between">
                        <h5 style="font-weight: 800;font-size: 28px;line-height: 100%;color: white;">
                            Edit Embedding
                        </h5>
                        <div>
                            <a type="button" data-bs-dismiss="modal"
                               aria-label="Close" id="edit-embedding-close-modal-button">
                                <img src="{{asset('assets/img/icons/cross-white.svg')}}" width="25">
                            </a>
                        </div>
                    </div>

                    <div class="py-1">
                        @if(session('embedding_errors'))
                            <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                                <ul class="alert-text text-white mb-0">
                                    @foreach(session('embedding_errors') as $err)
                                        <li>{{ $err[0] }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @if(session('embedding_success'))
                            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('embedding_success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        @if(session('embedding_error'))
                            <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('embedding_error')}}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                        <form wire:submit.prevent="updateEmbedding" enctype="multipart/form-data">

                            {{--                            <div class="py-3">--}}
                            {{--                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">--}}
                            {{--                                    Name--}}
                            {{--                                </label>--}}
                            {{--                                <input type="text"--}}
                            {{--                                       wire:model.defer="updateEmbeddingName"--}}
                            {{--                                       placeholder="Enter embedding name"--}}
                            {{--                                       style="padding:10px;border-radius: 40px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">--}}
                            {{--                            </div>--}}

                            <div class="py-3">
                                <label class="py-2" style="font-weight: 600;font-size: 19px;line-height: 100%;color: white; display: block;">
                                    Embedding
                                </label>
                                <textarea
                                    placeholder="Enter embedding text"
                                    wire:model="updateEmbeddingText"
                                    rows="5"
                                    style="padding:15px;border-radius: 10px; box-shadow: 0 8px 20px 0 #0000001A;background: #F4ECE0; width: 95%;">
                                </textarea>
                            </div>

                            <div class="py-4 d-flex justify-content-center">
                                <button class="m-1"
                                        style="background:#F95520;color:white;border-radius: 24px;border: 2px; font-weight: 600;padding: 5px 15px 5px 15px;">
                                    <img src="{{asset('assets/img/icons/Add.svg')}}" width="20">
                                    Update
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('js')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/plugins/sweetalert.min.js"></script>

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

        const connectedClusters = document.querySelector('#connected_cluster');
        connectedClusters.addEventListener('wheel', (event) => {
            event.preventDefault();

            connectedClusters.scrollBy({
                top: event.deltaY < 0 ? -30 : 30
            });
        });

        function deleteEmbedding(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                // title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'> THIS WILL ONLY REMOVE THE KNOWLEDGE SOURCE FROM THE QUEUE OF KNOWLEDGE SOURCES.<br>Are you sure you want to continue?</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteEmbedding', id)
                }
            })
        }

        function deleteCurrentEmbedding(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn bg-gradient-danger m-2',
                    cancelButton: 'btn bg-gradient-secondary m-2',
                },
                buttonsStyling: false,
                background: '#3442b4',
            })
            swalWithBootstrapButtons.fire({
                // title: '<span style="color: white;">Are you sure?</span>',
                html: "<span style='color: white;'>THIS WILL ONLY REMOVE THE KNOWLEDGE SOURCE FROM THIS CLUSTER.  IT DOES NOT DELETE THE KNOWLEDGE SOURCE. <br> Are you sure you want to continue?</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteCurrentEmbedding', id)
                }
            })
        }

    </script>


@endpush

@push('javascript')

    <script>

        window.livewire.on('closeEditEmbeddingModal', function (){
            setTimeout(function (){
                $('#edit-embedding-close-modal-button').click();
            }, 1000);
        });

        window.livewire.on('closeAlert', function (){
            setTimeout(function (){
                $('.alert').alert('close');
            }, 5000);
        });

        window.Livewire.on('openEditModal', function () {

            $('#editEmbeddingModel').modal('toggle');
        });

    </script>

@endpush
