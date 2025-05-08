@push('css')

    <style>

        .session-buttons{
            background-color: #f2661c;
            border-radius: 7px;
            color: white;
            border: none;
            padding: 7px;
        }

        .message-label{
            color: black;
            background-color: lightgray;
            padding: 1px 10px;
            border-radius: 20px;
        }

    </style>

@endpush

<div>

    <div class="container">

        <div class="row">

            <div class="col-3">

                <button class="session-buttons">INITIATE NEW TRAINING SESSION</button>

            </div>
            <div class="col-9">
                <button class="session-buttons">
                    DROPDOWN SELECT HAi TRAINING PERSONA
                </button>
            </div>

        </div>

        <div class="row" style="padding-top: 30px;">

            <div class="col-3">

                <div>

                    <p style="line-height: 0; font-weight: 700;color: black;">Previous Training Sessions :</p>
                    <div style="border-radius: 5px; border: 2px solid black;" class="py-2 px-1">

                        <table class="table">

                            <tr class="custom-text-dark">
                                <td>
                                    Session # 01
                                </td>
                                <td>
                                    <a class="px-2 py-1" wire:click="" style="background-color: red; color: white; font-size: small; border-radius: 8px; border: none;cursor: pointer;">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <tr class="custom-text-dark">
                                <td>
                                    Session # 02
                                </td>
                                <td>
                                    <a class="px-2 py-1" wire:click="" style="background-color: red; color: white; font-size: small; border-radius: 8px; border: none;cursor: pointer;">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                        </table>

                    </div>

                    <div class="row p-1">

                        <div class="col-4">
                            <button class="session-buttons">
                                TRAIN MORE
                            </button>
                        </div>

                        <div class="col-4">
                            <button class="session-buttons">
                                EXPORT
                            </button>
                        </div>

                        <div class="col-4">
                            <button class="session-buttons">
                                DELETE
                            </button>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-6">

                <div style="border-radius: 5px;border: 2px solid black; width: 100%;height: 500px; padding-top: 10px;">

                    <div style="height: 450px;">

                        <div id="chatMessages" class="d-flex flex-column gap-3">
                            <div class="d-flex flex-row gap-1 justify-content-end">
                                <div class="rounded " style="max-width: 70%;">
                                    <div>
                                        <p class="text-end text-sm" style="color: #000000;margin-bottom: 3px;">Admin</p>
                                    </div>
                                    <div class="bg-secondary text-white p-2"  style="font-size:small;background: #E05A35 !important;border-radius: 10px 0px 10px 10px !important">
                                        User Message
                                    </div>
                                    <div>
                                        <p class="text-end" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse()->diffForHumans()}}</p>
                                    </div>
                                </div>

                                <div>
                                    <img src="{{URL::asset('assets/img/Human_OP.png')}}" width="50" height="35" style="border-radius: 50%">
                                </div>
                            </div>
                            <div style="padding: 5px;"></div>
{{--                            <div>--}}
{{--                                <img src="{{asset('assets\img\icons\assessment_intro_icon.png')}}" width="35" height="35" style="border-radius: 50%;background-color: white" >--}}
{{--                            </div>--}}
                            <div class="d-flex flex-row gap-3 align-items-start">
                                <div>
                                    <img src="{{asset('assets\img\icons\assessment_intro_icon.png')}}" width="35" height="35" style="border-radius: 50%;background-color: white" >
                                </div>
                                <div class="rounded " style="max-width: 70%;">
                                    <div class="bg-primary text-white  p-2"
                                         style="max-width: 100%; font-size:small;background-color: #F7F5F4 !important;color:#000000 !important;border-radius: 0px 10px 10px 10px !important">
                                        Reply
                                    </div>
                                    <div class="row" style="width: 100%;">
                                        <div class="col-9">
                                            <p class="text-start" style="color: #58534C;font-size: 14px"> {{\Carbon\Carbon::parse()->diffForHumans()}}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="px-3">
                        <input placeholder="Enter your message here." type="text" style="width: 90%; border-radius: 15px;" class="px-1">

                        <button class="bg-transparent" type="submit" style="border:none" id="submit_btn">
                            <img src="{{asset('assets\img\icons\mynaui_send-solid.png')}}"  width="25" height="25" >
                        </button>
                    </div>

                </div>

                <div class="py-2">

                    <button class="session-buttons">
                        END CURRENT TRAINING SESSION
                    </button>

                </div>

            </div>

            <div class="col-3">

                <div style="border-radius: 5px; border: 2px solid black; width: 100%; height: 500px;" class="p-1">

                    <div>

                        This will allow admin user to
                        observe how HAi is thinking and
                        processing its learning

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
