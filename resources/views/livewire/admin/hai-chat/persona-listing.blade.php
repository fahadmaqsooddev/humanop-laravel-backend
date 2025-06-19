@push('css')

    <style>
        .disabledCard {
            pointer-events: none;
            opacity: 0.4;
        }
    </style>

@endpush

<div>

    <div class="py-2">

        <div style="border: 2px solid orangered; border-radius: 10px; padding: 2px;">

            <div>

                <p style="color: black; font-weight: 600; padding: 5px;">Select Persona to View/Edit</p>

                <div class="d-flex justify-content-end px-1">

                    <a href="{{route('admin_hai_chat_persona')}}" class="btn-sm" style="background: none; border: 2px solid orangered; border-radius: 20px; padding: 5px 10px; color: black;">
                        Create New Persona
                    </a>

                </div>

            </div>

            <div class="py-3">

                <div style="overflow-y: scroll; max-height: 200px;" id="persona_listing_div">

                    <div style="border: 2px solid orangered; padding: 10px; border-radius: 20px;">

                        <table class="table">

                            @foreach($personas as $persona)

                                <tr class="custom-text-dark">
                                    <td>
                                            <span style="font-weight: 600; font-size: 15px;">

                                                @if(strlen($persona['persona_name']) > 15)

                                                    {{substr($persona['persona_name'], 0, 15)}} ...

                                                @else

                                                    {{$persona['persona_name']}}

                                                @endif

                                            </span>
                                    </td>
                                    <td>
                                        <a class="px-2 py-1" href="{{route('admin_hai_chat_persona', ['name' => $persona['chatbot']['name']])}}" style="background-color: #1b3a62; color: white; font-size: small; border-radius: 8px; border: none;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        &nbsp;
                                        <a class="px-2 py-1" wire:click="deletePersona({{$persona['id']}})" style="background-color: red; color: white; font-size: small; border-radius: 8px; border: none;cursor: pointer;">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                        </table>

                    </div>

                </div>

                {{--                <select id="persona_div" wire:model="persona_id" style="border: 2px solid orangered; padding: 10px; border-radius: 20px;width: 250px; height: 175px;" multiple>--}}

                {{--                    @foreach($personas as $persona)--}}

                {{--                        <option value="{{$persona['id']}}">{{$persona['persona_name']}}</option>--}}

                {{--                    @endforeach--}}

                {{--                </select>--}}

                {{--                <div class="d-flex justify-content-end py-1">--}}

                {{--                    <button class="btn-sm" wire:click="viewEditPersona" style="background: none; border: 2px solid orangered; border-radius: 20px; padding: 5px 10px; color: black;">--}}
                {{--                        View/Edit Persona--}}
                {{--                    </button>--}}

                {{--                </div>--}}

            </div>

        </div>

    </div>

</div>

@push('js')

    <script>

        const descriptionContainer = document.querySelector('#persona_listing_div');
        descriptionContainer.addEventListener('wheel', (event) => {
            event.preventDefault();

            descriptionContainer.scrollBy({
                top: event.deltaY < 0 ? -30 : 30,
            });
        });

    </script>

@endpush
