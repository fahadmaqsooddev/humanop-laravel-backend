@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row">
        <div class="col-12 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h2 class="font-weight-bolder mb-0">Code</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card mb-4"
                         style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <img style="width: 100%; height: 600px" src="{{asset('assets/img/Cycle_Of_Life.jpg')}}">
                            <div class="col-6">
                                <div class="table-responsive mt-4">
                                    <table class="table table-flush" id="datatable-search">
                                        <thead class="thead-light">
                                        <tr>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-sm font-weight-normal">1</td>
                                            <td class="text-sm font-weight-normal">0-3</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">2</td>
                                            <td class="text-sm font-weight-normal">4-6</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">3</td>
                                            <td class="text-sm font-weight-normal">7-11</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">4</td>
                                            <td class="text-sm font-weight-normal">12-15</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">5</td>
                                            <td class="text-sm font-weight-normal">16-20</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">6</td>
                                            <td class="text-sm font-weight-normal">21-29</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">7a</td>
                                            <td class="text-sm font-weight-normal">30-33</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">7b</td>
                                            <td class="text-sm font-weight-normal">34-42</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">8</td>
                                            <td class="text-sm font-weight-normal">43-51</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">9</td>
                                            <td class="text-sm font-weight-normal">52-65</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">10</td>
                                            <td class="text-sm font-weight-normal">66-69</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">11a</td>
                                            <td class="text-sm font-weight-normal">70-74</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">11b</td>
                                            <td class="text-sm font-weight-normal">75-83</td>
                                        </tr>
                                        <tr>
                                            <td class="text-sm font-weight-normal">12</td>
                                            <td class="text-sm font-weight-normal">84-93</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
