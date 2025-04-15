<div>
    <div class="px-4">

        <div class="py-5">
            <h4>EMBED NEW KNOWLEDGE</h4>

            <span class="text-color-dark" style="font-size: 11px;">
                        Upload & Add New Knowledge Source(s)
                        <br>
                        (Click to see supported formats)
                    </span>

            <div class="py-3">

                <div class="row py-2">

                    @include('layouts.message')

                    <div class="col-10">
                        <textarea class="input-bg w-100" rows="5" wire:model="file_text"
                                  placeholder="Enter website URL, sitemap, YouTube video link, drag & drop files,click to pick files or enter plain text."></textarea>
                    </div>
                    <div class="col-2">

                        <div class="pt-1">

                            <input type="file" wire:model="uploadedFile" style="color: black;">
                            <span style="color: black;" wire:loading.flex wire:target="uploadedFile">Uploading ...</span>

                        </div>

                        <div class="d-flex align-items-end" style="height: 75%;">

                            <button class="cluster-buttons" wire:click="addToTrainingQueue">
                                Add to Training Queue
                            </button>

                        </div>

{{--                        <input type="file" wire:model="">--}}
                    </div>

                </div>

                <div class="row py-3">

                    <div class="col-10" style="max-height: 200px; overflow-y: scroll;" id="fileTrainingQueue">

                        <div style="background-color: #F4ECE0; padding: 10px; border-radius: 20px;">

                            <table class="table">
                                <tbody>

                                @if(count($allFiles) === 0)
                                    <div style="color: #F95520;">
                                        <p class="text-center">Add training files to display here.</p>
                                    </div>
                                @endif


                                @foreach($allFiles as $file)

                                    <tr class="text-color-dark mt-1 cluster-table-rows">
                                        <td class="pt-3" style="font-size: 12px;">

                                            @if($edit_file_id === $file->id)

                                                <input class="input-bg" style="border: 1px solid #F95520 !important;" wire:model="edited_name">
                                                <button
                                                    wire:click="updateFileName"
                                                    style="border-radius: 10px; background-color: #F95520;
                                                    color: white; font-size: 12px;border: none; padding: 5px;">
                                                    UPDATE
                                                </button>
                                            @else

                                                <span>
                                                        {{$file->name}}
                                                    </span>
                                                &nbsp;&nbsp;
                                                <span wire:click="editFileName({{$file->id}})" class="text-secondary" style="cursor: pointer;">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </span>

                                            @endif
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td class="float-end">
                                            <button wire:click="deleteFileFromTrainingQueue({{$file->id}})" class="cluster-buttons">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>

                        </div>

                    </div>
                    <div class="col-2 d-flex align-items-end">
                        <button class="cluster-buttons" wire:click="startTraining">
                            Start Training
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@push('js')

    <script>

        const fileTrainingQueue = document.querySelector('#fileTrainingQueue');
        fileTrainingQueue.addEventListener('wheel', (event) => {
            event.preventDefault();

            fileTrainingQueue.scrollBy({
                top: event.deltaY < 0 ? -30 : 30,
            });
        });

    </script>

@endpush
