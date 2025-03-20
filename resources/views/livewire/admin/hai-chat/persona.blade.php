@push('css')
    <style>

        .input-styling{
            background: #F4E3C7 !important;
            box-shadow: 0 8px 20px 0 #0000001A !important;
            border-radius: 20px !important;
            border: none !important;
            color: black !important;
        }

    </style>
@endpush
<div>

    <div class="py-2">
        <div class="input-group">
            <h3 style="color: #1C365E;font-weight: 700;font-size: 28px;line-height: 16.5px;vertical-align: middle;text-transform: capitalize;">
                Persona's
            </h3>
        </div>
    </div>

    <div class="py-2">

        <div class="w-50">

            <label class="form-label pt-1">Brains</label>
            <select class="dropdown input-styling" style="padding: 10px; display: block; width: 100%;" wire:model="chat_bot_id">
                @foreach($chatBots as $chatBot)

                    <option value="{{$chatBot['id']}}">{{$chatBot['name']}}</option>

                @endforeach
            </select>

            <label class="form-label pt-1">Persona</label>
            <textarea wire:model.defer="persona_text" type="text"
                      placeholder="Enter persona's text" class="form-control input-styling p-3" rows="7">
            </textarea>

            <div class="py-3 d-flex justify-content-between">
                <span></span>
                <span id="success_message_span" class="d-none" style="color: #F95520; font-size: 14px; font-weight: 600;">Persona Updated</span>
                <button
                    style="background:#F95520;color:white;border-radius: 24px;border: 2px;
                    font-weight: 400;padding: 10px 15px 10px 15px; font-size: 13px;"
                wire:click="savePersona">

                    <span wire:target="savePersona" wire:loading.remove>
                        Update
                    </span>
                    <span wire:target="savePersona" wire:loading>
                        Updating
                    </span>
                </button>
            </div>

        </div>

    </div>

</div>

@push('javascript')
    <script>

        window.Livewire.on('successMessage', function (){

            $('#success_message_span').removeClass('d-none');

            setTimeout(function (){

                $('#success_message_span').addClass('d-none');
            }, 2000);

        });

    </script>
@endpush
