@push('css')
    <style>

        input::placeholder{
            color: lightslategray !important;
        }

        .new-orange-button{
            background-color: #F95520 !important;
            padding: 10px 20px 10px 20px;
            border-radius: 8px;
            color: white;
            border-color: transparent;
            cursor: pointer;
            font-weight: 800;
        }

        .new-orange-button:hover{
            color: white;
        }

        .multi-select-button{
            width: 100%;
            padding: 5px;
            border-radius: 8px;
            border: 1px solid #f2661c;
            background-color: #F3DEBA;
            color: black;
        }

    </style>
@endpush
<div>
    <div class="card card-bg-white-orange-border mt-4" id="train">
        <div class="card-header">
            <div id="train" class="content-page">

                <div class="row">
                    <div class="col-5">

                        <div class="btn-group d-flex justify-content-between ">

                            <div class="dropdown w-100">
                                <button class="dropdown-toggle multi-select-button {{$showGroupDropdownMenu ? 'show' : ''}}" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{$groupButtonText}}
                                </button>
                                {{--                            @if($showEmbeddingDropdown)--}}
                                <div class="dropdown-menu w-100 {{$showGroupDropdownMenu ? 'show' : ''}}" aria-labelledby="dropdownMenuButton2">

                                    <div style="padding: 10px;">

                                        <div style="padding-bottom: 5px;">
                                            <input type="text" placeholder="search group" wire:model="group_search" style="border-radius: 1px; border: 1px solid #f2661c; width: 100%;">
                                        </div>

                                        @if(count($groups) > 0)

                                            <div style="max-height: 100px; overflow-y: scroll;padding-top: 5px;" id="group_dropdown_scroll">

                                                <ul style="list-style: none; padding-top: 5px; padding-left: inherit;">
                                                    @foreach($groups as $key => $group)
                                                        <li>
                                                            <div class="form-check" wire:click="updateGroupId('{{$group['id']}}', '{{$group['name']}}')">
                                                                <input class="form-check-input" type="checkbox" id="checkboxGroup{{$key}}" {{($group['is_active_group'] ? 'checked' : '')}} onclick="return false;">
                                                                <label class="form-check-label" for="checkboxGroup{{$key}}">
                                                                    {{$group->name}}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            </div>
                                        @else
                                            <div class="text-center">
                                                <span>No group</span>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>

{{--                            <select wire:model="group_id" class="form-control" style="background-color: #F3DEBA; color: black;">--}}
{{--                                <option value="" class="text-center">Select Group</option>--}}
{{--                                <option disabled style="background-color: #0f1534; color: white;">All Groups</option>--}}
{{--                                @foreach($groups as $group)--}}
{{--                                    <option value="{{$group->id}}">{{$group->name}}</option>--}}
{{--                                @endforeach--}}
{{--                                <option disabled style="background-color: #0f1534; color: white;">Active Groups</option>--}}
{{--                                @foreach($activeGroups as $group)--}}
{{--                                    <option value="{{$group->id}}">{{$group->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
                        </div>

                    </div>
                    <div class="col-5">

                        <div class="btn-group d-flex justify-content-between">

                            <div class="dropdown w-100">
                                <button class="dropdown-toggle multi-select-button {{$showDropdownMenu ? 'show' : ''}}" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select @if($group_id) Embedding @else Group First @endif
                                </button>
                                {{--                            @if($showEmbeddingDropdown)--}}
                                <div class="dropdown-menu w-100 {{$showDropdownMenu ? 'show' : ''}}" aria-labelledby="dropdownMenuButton1">

                                    <div style="padding: 10px;">

                                        <div style="padding-bottom: 5px;">
                                            <input type="text" placeholder="search embedding" wire:model="embedding_search" style="border-radius: 1px; border: 1px solid #f2661c; width: 100%;">
                                        </div>

                                        @if(count($embeddings) > 0)

                                            <div style="max-height: 100px; overflow-y: scroll;padding-top: 5px;" id="embedding_dropdown_scroll">

                                                <ul style="list-style: none; padding-top: 5px; padding-left: inherit;">
                                                    @foreach($embeddings as $key => $embedding)
                                                        <li>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="checkbox{{$key}}" wire:click="changeEmbeddingStatus('{{$embedding['request_id']}}')" {{($embedding['is_active_embedding'] ? 'checked' : '')}}>
                                                                <label class="form-check-label" for="checkbox{{$key}}">
                                                                    {{$embedding->name}}
                                                                </label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            </div>
                                        @else
                                            <div class="text-center">
                                                <span>No embedding</span>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-2">
                        <button wire:click="showActiveEmbeddings"
                            class="text-sm new-orange-button navButtonResponsive"
                            style="font-size: 10px !important;" >Active Embeddings</button>

                        <button id="showActiveEmbeddingsModalButton" data-bs-toggle="modal" data-bs-target="#showActiveEmbeddingsModal" hidden>
                            Show Active Embeddings Modal Button
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div>
                            <!-- Search Box -->
                            <div class="container-fluid mt-4 mx-0 px-0">
                                @include('layouts.message')
                                <form wire:submit.prevent="searchEmbedding">
                                    <div class="textarea-with-icon">
                                        <textarea class="form-control input-bg" rows="3"
                                                  style="font-size: small;"
                                                  wire:model.defer="query"
                                                  placeholder="Search across all documents"></textarea>
                                        <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" type="submit"
                                                class=" mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end new-orange-button navButtonResponsive">
                                            search
                                            <span wire:loading wire:target="searchEmbedding" class="swal2-loader" style="font-size: 8px;">
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="pt-2">
                            <div style="max-height: 200px; overflow-y: scroll;" id="chunks_div">
                                @if(count($chunks) > 0)
                                    @foreach($chunks as $chunk)
                                        <div class="chunk-card input-bg">
                                            <p class="custom-text-dark">{{ $chunk['retrieved_docs'] }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            </div>
        </div>

    <div class="modal fade" id="showActiveEmbeddingsModal" tabindex="-1" role="dialog"
         aria-labelledby="showActiveEmbeddings" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <h4 style="color: #F95520;" class="text-center">Active Embeddings</h4>
                    <ul class="text-white">
                        @foreach($active_embeddings as $activeEmbedding)
                            <li>{{$activeEmbedding}}</li>
                        @endforeach
                    </ul>

                    @if(count($active_embeddings) == 0)
                        <span class="text-white">No embedding is connected with this chatbot.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </div>

@push('js')

    <script>

        const descriptionContainer = document.querySelector('#chunks_div');
        descriptionContainer.addEventListener('wheel', (event) => {
            event.preventDefault();

            descriptionContainer.scrollBy({
                top: event.deltaY < 0 ? -30 : 30,
            });
        });

        const groupDropDownScrollDiv = document.querySelector('#group_dropdown_scroll');
        groupDropDownScrollDiv.addEventListener('wheel', (event) => {
            event.preventDefault();

            groupDropDownScrollDiv.scrollBy({
                top: event.deltaY < 0 ? -30 : 30,
            });
        });

    </script>

@endpush

@push('javascript')

    <script>

        window.livewire.on('makeGroupDownDownScrollable', function (){
            const groupDropDownScrollDiv = document.querySelector('#group_dropdown_scroll');
            groupDropDownScrollDiv.addEventListener('wheel', (event) => {
                event.preventDefault();

                groupDropDownScrollDiv.scrollBy({
                    top: event.deltaY < 0 ? -30 : 30,
                });
            });
        });

        window.livewire.on('makeEmbeddingDownDownScrollable', function (){
            const embDropDownScrollDiv = document.querySelector('#embedding_dropdown_scroll');
            embDropDownScrollDiv.addEventListener('wheel', (event) => {
                event.preventDefault();

                embDropDownScrollDiv.scrollBy({
                    top: event.deltaY < 0 ? -30 : 30,
                });
            });
        });

        window.livewire.on('showActiveEmbeddingsModal', function (){

            $('#showActiveEmbeddingsModalButton').click();

        });

    </script>

@endpush

