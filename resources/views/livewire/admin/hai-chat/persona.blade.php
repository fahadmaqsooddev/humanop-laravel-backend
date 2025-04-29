@push('css')

    <style>
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
    </style>

@endpush
<div>

    <div class="card card-bg-white-orange-border" id="persona">
        @include('layouts.message')

        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-0">Name of Persona</h5>
            <input type="text" class="form-control input-bg change-input-form" id="chatDescription" wire:model.defer="persona_name" placeholder="Enter name of persona">
        </div>

        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-0">CONNECTED BRAIN FOR THIS PERSONA</h5>
            <select class="form-control input-bg" id="chatDescription" wire:model="chat_bot_id">
                <option value="">NONE</option>
                @foreach($chatBots as $chatBot)
                    <option value="{{$chatBot->id}}">{{$chatBot->brain_name ?? $chatBot->name}}</option>
                @endforeach
            </select>
            <span wire:loading wire:target="chat_bot_id" class="text-secondary text-sm" style="font-size: 10px;">
                Loading data, please wait...
            </span>
        </div>

        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-0">CONNECT WITH HUMANOP APP?</h5>
            <select class="form-control input-bg change-input-form" id="human_app" wire:model.defer="human_op_app" onchange="alreadyExistsHumanApp(this)">
                @if(empty($chat_bot_id))
                    <option value="">SELECT BRAIN FIRST</option>
                @else
                    <option value="">NONE</option>
                    <option value="1">FREEMIUM HAi</option>
                    <option value="2">CORE HAi</option>
                    <option value="3">PREMIUM HAi</option>
                    <option value="4">FREEMIUM "HELP!" HAi</option>
                    <option value="5">CORE "HELP!" HAi</option>
                    <option value="6">PREMIUM "HELP!" HAi</option>
                @endif
            </select>
        </div>

        <div class="card-header">
            <h5 class="text-orange setting-form-heading py-0">CONNECT WITH MAESTRO APP?</h5>
            <select class="form-control input-bg change-input-form" id="maestro_app" wire:model.defer="maestro_app" onchange="alreadyExistsMaestroApp(this)">
                @if(empty($chat_bot_id))
                    <option value="">SELECT BRAIN FIRST</option>
                @else
                    <option value="">NONE</option>
                    <option value="1">GENERAL MAESTRO HAi</option>
                    <option class="text-center" disabled>Client Companies</option>
                    @foreach($client_companies as $company)
                        <option value="2-{{$company['id']}}">{{$company['company_name']}}</option>
                    @endforeach
                    <option class="text-center" disabled>Industry Categories</option>
                    @foreach($industry_categories as $category)
                        <option value="3-{{$category['id']}}">{{$category['name']}}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="card-body d-sm-flex pt-0 justify-content-end">
            <button style="padding: 10px 16px 10px 16px; border-radius: 7px;" wire:click="updateOrSave"
                    class="mt-4 btn-sm-1 btn-md-3 btn-lg-5 float-end new-orange-button navButtonResponsive update-button">
                update
                <span wire:loading wire:target="updateOrSave" style="font-size: 8px;" class="swal2-loader">
                </span>
            </button>
        </div>
    </div>

</div>

@push('javascript')

    <script src="../../assets/js/plugins/sweetalert.min.js"></script>
    <script>

        window.livewire.on('hideAlerts', function (){

            var prompt = document.getElementById("prompt");
            prompt.scrollIntoView();

            setTimeout(function (){

                $('.alert').alert('close');

            }, 5000);

        });

        connected_human_apps = [];

        function alreadyExistsHumanApp(event){

            connected_human_app = {{json_encode(array_values($connected_human_apps))}};

            if(connected_human_app.includes(parseInt(event.value))){

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn bg-gradient-primary m-2',
                        cancelButton: 'btn bg-gradient-secondary m-2',
                    },
                    buttonsStyling: false,
                    background: '#3442b4',
                })
                swalWithBootstrapButtons.fire({
                    // title: '<span style="color: white;">Are you sure?</span>',
                    html: "<span style='color: white;'>This Connection already has another Persona attached to it.  Would you like to replace it with this Persona?</span>",
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {

                        window.livewire.emit('updateChatBotHumanApp', event.value);

                    }else {

                        document.getElementById('human_app').value = "";
                    }
                })
            }

        }

        connected_maestro_app = [];

        function alreadyExistsMaestroApp(event){

            connected_maestro_app = {!! json_encode($connected_maestro_apps)  !!};

            if(connected_maestro_app.includes(event.value)){

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn bg-gradient-primary m-2',
                        cancelButton: 'btn bg-gradient-secondary m-2',
                    },
                    buttonsStyling: false,
                    background: '#3442b4',
                })
                swalWithBootstrapButtons.fire({
                    // title: '<span style="color: white;">Are you sure?</span>',
                    html: "<span style='color: white;'>This Connection already has another Persona attached to it.  Would you like to replace it with this Persona?</span>",
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {

                        window.livewire.emit('updateChatBotMaestroApp', event.value);

                    }else {

                        document.getElementById('maestro_app').value = "";
                    }
                })
            }

        }

    </script>

@endpush




{{--New Persona Design--}}

{{--@push('css')--}}
{{--    <style>--}}

{{--        .input-styling{--}}
{{--            background: #F4E3C7 !important;--}}
{{--            box-shadow: 0 8px 20px 0 #0000001A !important;--}}
{{--            border-radius: 20px !important;--}}
{{--            border: none !important;--}}
{{--            color: black !important;--}}
{{--        }--}}

{{--    </style>--}}
{{--@endpush--}}
{{--<div>--}}

{{--    <div class="py-2">--}}
{{--        <div class="input-group">--}}
{{--            <h3 style="color: #1C365E;font-weight: 700;font-size: 28px;line-height: 16.5px;vertical-align: middle;text-transform: capitalize;">--}}
{{--                Persona's--}}
{{--            </h3>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="py-2">--}}

{{--        <div class="w-50">--}}

{{--            <label class="form-label pt-1">Brains</label>--}}
{{--            <select class="dropdown input-styling" style="padding: 10px; display: block; width: 100%;" wire:model="chat_bot_id">--}}
{{--                @foreach($chatBots as $chatBot)--}}

{{--                    <option value="{{$chatBot['id']}}">{{$chatBot['name']}}</option>--}}

{{--                @endforeach--}}
{{--            </select>--}}

{{--            <label class="form-label pt-1">Persona</label>--}}
{{--            <textarea wire:model.defer="persona_text" type="text"--}}
{{--                      placeholder="Enter persona's text" class="form-control input-styling p-3" rows="7">--}}
{{--            </textarea>--}}

{{--            <div class="py-3 d-flex justify-content-between">--}}
{{--                <span></span>--}}
{{--                <span id="success_message_span" class="d-none" style="color: #F95520; font-size: 14px; font-weight: 600;">Persona Updated</span>--}}
{{--                <button--}}
{{--                    style="background:#F95520;color:white;border-radius: 24px;border: 2px;--}}
{{--                    font-weight: 400;padding: 10px 15px 10px 15px; font-size: 13px;"--}}
{{--                wire:click="savePersona">--}}

{{--                    <span wire:target="savePersona" wire:loading.remove>--}}
{{--                        Update--}}
{{--                    </span>--}}
{{--                    <span wire:target="savePersona" wire:loading>--}}
{{--                        Updating--}}
{{--                    </span>--}}
{{--                </button>--}}
{{--            </div>--}}

{{--        </div>--}}

{{--    </div>--}}

{{--</div>--}}

{{--@push('javascript')--}}
{{--    <script>--}}

{{--        window.Livewire.on('successMessage', function (){--}}

{{--            $('#success_message_span').removeClass('d-none');--}}

{{--            setTimeout(function (){--}}

{{--                $('#success_message_span').addClass('d-none');--}}
{{--            }, 2000);--}}

{{--        });--}}

{{--    </script>--}}
{{--@endpush--}}
