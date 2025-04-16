<div>

    <div class="py-2">

        <div style="border: 2px solid orangered; border-radius: 10px; padding: 10px;">

            <div>

                <p style="color: black; font-weight: 600;">Select Persona to View/Edit</p>

                <div class="d-flex justify-content-end">

                    <button wire:click="createNewPersona" class="btn-sm" style="background: none; border: 2px solid orangered; border-radius: 20px; padding: 5px 10px; color: black;">
                        Create New Persona
                    </button>

                </div>

            </div>

            <div class="py-3">

                <select id="persona_div" wire:model="persona_id" style="border: 2px solid orangered; padding: 10px; border-radius: 20px;width: 250px; height: 175px;" multiple>

                    @foreach($personas as $persona)

                        <option value="{{$persona['id']}}">{{$persona['persona_name']}}</option>

                    @endforeach

                </select>

                <div class="d-flex justify-content-end py-1">

                    <button class="btn-sm" wire:click="viewEditPersona" style="background: none; border: 2px solid orangered; border-radius: 20px; padding: 5px 10px; color: black;">
                        View/Edit Persona
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@push('js')

    <script>

        const descriptionContainer = document.querySelector('#persona_div');
        descriptionContainer.addEventListener('wheel', (event) => {
            event.preventDefault();

            descriptionContainer.scrollBy({
                top: event.deltaY < 0 ? -30 : 30,
            });
        });

    </script>

@endpush
