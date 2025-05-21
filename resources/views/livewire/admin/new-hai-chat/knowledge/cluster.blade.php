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
            border-radius: 20px !important;
            border: none !important;
            padding: 5px;
        }

        .input-bg::placeholder{
            color: #F95520 !important;
        }

        .cluster-buttons{
            background-color: #F95520 !important;
            color: #F4ECE0;
            padding: 5px 10px;
            border-radius: 8px;
            border-width: 2px;
            border: none;
        }

        .cluster-buttons-a{
            background-color: #F95520 !important;
            color: #F4ECE0;
            /*padding: 5px 10px;*/
            border-radius: 8px;
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

        .cluster-buttons-a:hover{
            color: white;
        }

        a:hover{
            color: white !important;
        }

    </style>

@endpush
<div>

    <div class="px-4">

        <div class="py-5">

            <div class="d-flex justify-content-between col-11">
                <h4>KNOWLEDGE CLUSTER MANAGEMENT</h4>
                <a href="{{route('admin_create_cluster')}}" style="margin-top: 5px;" class="cluster-buttons">CREAT NEW CLUSTER</a>
            </div>

            <div class="py-3">

                <div class="row py-2">

                    <div class="col-10">

                        <div class="d-flex justify-content-between">

                            <div>
                                <input type="checkbox" wire:click="selectAllClusters">
                                <label>Select all</label>
                            </div>
                            <select class="configurations-drop-down" wire:model="bulk_option">
                                <option value="">Bulk Options</option>
                                <option value="1">Train/Re-Train</option>
                                <option value="2">Delete</option>
                            </select>

                            <input type="text" wire:model="search_clusters" placeholder="Keyword Filter" style="width: 250px;" class="input-bg text-center">

                            <select class="configurations-drop-down" wire:model="brain_id">
                                <option>Brain Filter</option>
                                @foreach($brains as $brain)
                                    <option value="{{$brain['id']}}">{{$brain['brain_name'] ?? $brain['name']}}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                </div>

                <div class="row py-3">

                    <div class="col-10" style="max-height: 400px; overflow-y: scroll;" id="clusters">

                        <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                            @if(count($clusters) === 0)
                                <div class="text-center">
                                    <p style="color: #F95520;">No clusters found</p>
                                </div>
                            @endif

                            <table class="table">
                                <tbody>

                                @foreach($clusters as $cluster)

                                    <tr class="text-color-dark mt-1 cluster-table-rows">
                                        <td class="pt-3" style="font-size: 12px;">
                                            <div style="padding: 2px;">
                                                <input type="checkbox" wire:click="selectIndividualCluster({{$cluster['id']}})" {{in_array($cluster['id'],$selectedClusters) ? 'checked' : '' }}>
                                                <span>
                                        {{$cluster['name']}} [{{$cluster['created_at']}}] [{{$cluster['updated_at']}}]
                                    </span>
                                            </div>
                                            <p style="font-size: 13px;">{{$cluster['description']}}</p>
                                        </td>
                                        <td>

                                            @foreach($cluster['connectedClusters'] as $brain)

                                                <a href="{{route('admin_edit_brain', ['id' => $brain['brain']['id']])}}">
                                                    <span class="badge cluster-badge">{{$brain['brain']['brain_name'] ?? $brain['brain']['name'] }}</span>
                                                </a>

                                            @endforeach

                                        </td>
                                        <td class="float-end">
                                            <a style="margin-right: 2px;padding: 1px 8px;" href="{{route('admin_edit_cluster', ['id' => $cluster['id']])}}" class="cluster-buttons-a" title="Edit Cluster">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            @if($cluster['is_ready_for_training'])

                                                <button class="cluster-buttons" wire:click="reTrainClusterEmbeddings({{$cluster['id']}})" title="Retrain Cluster">
                                                    <i class="fa-solid fa-arrows-rotate"></i>
                                                </button>

                                            @else

                                                <button class="cluster-buttons" style="background-color: darkgray !important;" title="Retrain Cluster">
                                                    <i class="fa-solid fa-arrows-rotate"></i>
                                                </button>

                                            @endif

                                            <button onclick="deleteCluster({{$cluster['id']}})" class="cluster-buttons" title="Delete Cluster">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>

                        </div>

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

        const clusters = document.querySelector('#clusters');
        clusters.addEventListener('wheel', (event) => {
            event.preventDefault();

            clusters.scrollBy({
                top: event.deltaY < 0 ? -30 : 30
            });
        });

        function deleteCluster(id) {

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
                html: "<span style='color: white;'>This knowledge cluster is connected to one or more brains.Deleting it will also remove it from all connected brains.Are you sure you want to proceed?</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteCluster', id)
                }
            })
        }
    </script>

@endpush
