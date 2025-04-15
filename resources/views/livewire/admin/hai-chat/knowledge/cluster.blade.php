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

    </style>

@endpush
<div>

    <div class="px-4">

        <div class="py-5">

            <div class="d-flex justify-content-between col-11">
                <h4>KNOWLEDGE CLUSTER MANAGEMENT</h4>
                <a href="{{route('admin_create_cluster')}}" class="cluster-buttons">CREAT NEW CLUSTER</a>
            </div>

            <div class="py-3">

                <div class="row py-2">

                    <div class="col-10">

                        <div class="d-flex justify-content-between">

                            <div>
                                <input type="checkbox">
                                <label>Select all</label>
                            </div>
                            <select class="configurations-drop-down">
                                <option>Bulk Options</option>
                                <option>Train/Re-Train</option>
                                <option>Delete</option>
                            </select>

                            <input type="text" placeholder="Keyword Filter" style="width: 250px;" class="input-bg text-center">

                            <select class="configurations-drop-down">
                                <option>Cluster Filter</option>
                                <option>Train/Re-Train</option>
                                <option>Export</option>
                                <option>Delete</option>
                            </select>

                        </div>

                    </div>

                </div>

                <div class="row py-3">

                    <div class="col-10" style="max-height: 400px; overflow-y: scroll;" id="clusters">

                        <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                            <table class="table">
                                <tbody>

                                @foreach($clusters as $cluster)

                                    <tr class="text-color-dark mt-1 cluster-table-rows">
                                        <td class="pt-3" style="font-size: 12px;">
                                            <div style="padding: 2px;">
                                                <input type="checkbox">
                                                <span>
                                        {{$cluster['name']}} [{{$cluster['created_at']}}] [{{$cluster['updated_at']}}]
                                    </span>
                                            </div>
                                            <p style="font-size: 13px;">Description</p>
                                        </td>
                                        <td>
                                            <span class="badge cluster-badge">BRAIN 1</span>
                                            <span class="badge cluster-badge">BRAIN 2</span>
                                            <span class="badge cluster-badge">BRAIN 3</span>
                                        </td>
                                        <td class="float-end">
                                            <a style="margin-right: 2px;padding: 1px 8px;" href="{{route('admin_edit_cluster', ['id' => $cluster['id']])}}" class="cluster-buttons-a">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button wire:click="" class="cluster-buttons">
                                                <i class="fa-solid fa-arrows-rotate"></i>
                                            </button>
                                            <button onclick="deleteCluster({{$cluster['id']}})" class="cluster-buttons">
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
