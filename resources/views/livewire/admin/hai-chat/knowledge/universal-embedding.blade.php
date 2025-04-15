<div>
    <div class="px-4">

        <div wire:loading.flex wire:target="editEmbedding" wire:target="bulk_option" class="spinner-border custom-text-dark" id="chat_switch_spinner" role="status"
             style="width: 30px; height: 30px;top: 55%; left: 35%;position: absolute;">
                <span class="sr-only">
                    Loading...
                </span>
        </div>

        <div wire:loading.flex wire:target="bulk_option" class="spinner-border custom-text-dark" id="chat_switch_spinner" role="status"
             style="width: 30px; height: 30px;top: 47%; left: 35%;position: absolute;">
                <span class="sr-only">
                    Loading...
                </span>
        </div>

        <div class="py-5">

            <h4>HAi UNIVERSAL KNOWLEDGE BASE</h4>

            <div class="py-3">

                <div class="row py-2">

                    <div class="col-10">

                        <div class="d-flex justify-content-between">

                            <div>
                                <input type="checkbox" wire:click="selectAllEmbeddings">
                                <label>Select all</label>
                            </div>
                            <select class="configurations-drop-down" wire:model="bulk_option">
                                <option value="">Bulk Options</option>
                                <option value="1">Train/Re-Train</option>
                                <option value="2">Export</option>
                                <option value="3">Delete</option>
                            </select>

                            <input type="text" wire:model="search_embedding" placeholder="Keyword Filter" style="width: 250px;" class="input-bg text-center">

                            <select wire:model="cluster_id" class="configurations-drop-down">
                                <option value="">Cluster Filter</option>
                                @foreach($clusters as $cluster)
                                    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                </div>

                <div class="row py-3">

                    <div class="col-10" style="max-height: 400px; overflow-y: scroll;" id="embeddings">

                        <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                            @if(count($embeddings) === 0)
                                <div style="color: #F95520;">
                                    <p class="text-center">No knowledge found.</p>
                                </div>
                            @endempty

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
                                            @foreach($embedding['groups'] as $group)
                                                @if(!empty($group['group']))
                                                    <span class="badge cluster-badge">{{$group['group']['name']}}</span>
                                                @endif
                                            @endforeach
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

                    </div>

                </div>

            </div>

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

        const nonActiveClusters = document.querySelector('#embeddings');
        nonActiveClusters.addEventListener('wheel', (event) => {
            event.preventDefault();

            nonActiveClusters.scrollBy({
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
                html: "<span style='color: white;'>If you delete it, it will also be removed from all connected clusters.Are you sure you want to continue?</span>",
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('deleteEmbedding', id)
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
