@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .font-weight-normal {
        color: black;
    }
</style>
@section('content')
    @livewire('admin.resource.create-resource')
    <div class="row" style="margin-top: 200px">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-2 mt-sm-3 me-lg-7">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>

    {{--    Master Key Modal--}}
{{--    <div class="modal fade" id="MasterKey" aria-hidden="true" aria-labelledby="MasterKeyLabel"--}}
{{--         tabindex="-1">--}}
{{--        <div class="modal-dialog modal-dialog-centered modal-lg">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body">--}}
{{--                    <img style="width: 100%;" src="{{asset('assets/img/Master_Key.jpg')}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--    Style Modal--}}
{{--    <div class="modal fade" id="Style" aria-hidden="true" aria-labelledby="StyleLabel"--}}
{{--         tabindex="-1">--}}
{{--        <div class="modal-dialog modal-dialog-centered modal-lg">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body">--}}
{{--                    <img style="width: 100%;" src="{{asset('assets/img/Styles_Wheel.jpg')}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--    Cycle Modal--}}
{{--    <div class="modal fade" id="Cycle" aria-hidden="true" aria-labelledby="CycleLabel"--}}
{{--         tabindex="-1">--}}
{{--        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body">--}}
{{--                    <img style="width: 100%;" src="{{asset('assets/img/Cycle_Of_Life.jpg')}}">--}}
{{--                    <div class="table-responsive mt-4">--}}
{{--                        <table class="table table-flush" id="datatable-search">--}}
{{--                            <thead class="thead-light">--}}
{{--                            <tr>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">1</td>--}}
{{--                                <td class="text-sm font-weight-normal">0-3</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">2</td>--}}
{{--                                <td class="text-sm font-weight-normal">4-6</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">3</td>--}}
{{--                                <td class="text-sm font-weight-normal">7-11</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">4</td>--}}
{{--                                <td class="text-sm font-weight-normal">12-15</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">5</td>--}}
{{--                                <td class="text-sm font-weight-normal">16-20</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">6</td>--}}
{{--                                <td class="text-sm font-weight-normal">21-29</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">7a</td>--}}
{{--                                <td class="text-sm font-weight-normal">30-33</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">7b</td>--}}
{{--                                <td class="text-sm font-weight-normal">34-42</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">8</td>--}}
{{--                                <td class="text-sm font-weight-normal">43-51</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">9</td>--}}
{{--                                <td class="text-sm font-weight-normal">52-65</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">10</td>--}}
{{--                                <td class="text-sm font-weight-normal">66-69</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">11a</td>--}}
{{--                                <td class="text-sm font-weight-normal">70-74</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">11b</td>--}}
{{--                                <td class="text-sm font-weight-normal">75-83</td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="text-sm font-weight-normal">12</td>--}}
{{--                                <td class="text-sm font-weight-normal">84-93</td>--}}
{{--                            </tr>--}}

{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--    Alchemy Modal--}}
{{--    <div class="modal fade" id="Alchemy" aria-hidden="true" aria-labelledby="AlchemyLabel"--}}
{{--         tabindex="-1">--}}
{{--        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body">--}}
{{--                    <img style="width: 100%;" src="{{asset('assets/img/Alchemy.jpg')}}">--}}
{{--                    <img style="width: 100%;" src="{{asset('assets/img/Periodic_Table_Alchemy.jpg')}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    --}}{{--    Energy Center Modal--}}
{{--    <div class="modal fade" id="EnergyCenter" aria-hidden="true" aria-labelledby="EnergyCenterLabel"--}}
{{--         tabindex="-1">--}}
{{--        <div class="modal-dialog modal-dialog-centered modal-lg">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-body">--}}
{{--                    <img style="width: 100%;" src="{{asset('assets/img/Energy_Center_Doorways.jpg')}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
