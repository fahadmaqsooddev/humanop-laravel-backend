@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')
    <div>
        <div class="row mt-4">
            <div class="col-6">
                <div class="card" >
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white" id="style_sa" onmousemove="changeColorStyleSA()" onmouseout="clearColorStyleSA()">SA</th>
                                <th class="text-center border border-white" id="style_ma" onmousemove="changeColorStyleMA()" onmouseout="clearColorStyleMA()">MA</th>
                                <th class="text-center border border-white" id="style_jo" onmousemove="changeColorStyleJO()" onmouseout="clearColorStyleJO()">JO</th>
                                <th class="text-center border border-white" id="style_lu" onmousemove="changeColorStyleLU()" onmouseout="clearColorStyleLU()">LU</th>
                                <th class="text-center border border-white" id="style_ven" onmousemove="changeColorStyleVEN()" onmouseout="clearColorStyleVEN()">VEN</th>
                                <th class="text-center border border-white" id="style_mer" onmousemove="changeColorStyleMER()" onmouseout="clearColorStyleMER()">MER</th>
                                <th class="text-center border border-white" id="style_so" onmousemove="changeColorStyleSO()" onmouseout="clearColorStyleSO()">SO</th>
                                <th class="text-center border border-white">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['sa'] == 0 ? 'bg-danger' : ($grid['sa'] >= 5 ? 'bg-success text-dark' : (($grid['sa'] <= 4) && ($grid['sa'] >= 1) && ($grid['ma'] >= 5) && ($grid['mer'] >= 5) ? 'border-success' : 'border-white')) }}">{{$grid['sa']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ma'] == 0 ? 'bg-danger' : ($grid['ma'] >= 5 ? 'bg-success text-dark' : (($grid['ma'] <= 4) && ($grid['ma'] >= 1) && ($grid['sa'] >= 5) && ($grid['jo'] >= 5) ? 'border-success' : 'border-white')) }}">{{$grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['jo'] == 0 ? 'bg-danger' : ($grid['jo'] >= 5 ? 'bg-success text-dark' : (($grid['jo'] <= 4) && ($grid['jo'] >= 1) && ($grid['ma'] >= 5) && ($grid['lu'] >= 5) ? 'border-success' : 'border-white')) }}">{{$grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['lu'] == 0 ? 'bg-danger' : ($grid['lu'] >= 5 ? 'bg-success text-dark' : (($grid['lu'] <= 4) && ($grid['lu'] >= 1) && ($grid['jo'] >= 5) && ($grid['ven'] >= 5) ? 'border-success' : 'border-white')) }}">{{$grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ven'] == 0 ? 'bg-danger' : ($grid['ven'] >= 5 ? 'bg-success text-dark' : (($grid['ven'] <= 4) && ($grid['ven'] >= 1) && ($grid['lu'] >= 5) && ($grid['mer'] >= 5) ? 'border-success' : 'border-white')) }}">{{$grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['mer'] == 0 ? 'bg-danger' : ($grid['mer'] >= 5 ? 'bg-success text-dark' : (($grid['mer'] <= 4) && ($grid['mer'] >= 1) && ($grid['ven'] >= 5) && ($grid['sa'] >= 5) ? 'border-success' : 'border-white')) }}">{{$grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['so'] == 0 ? 'bg-danger' : ($grid['so'] >= 5 ? 'bg-success' : '') }}">{{$grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['sa'] + $grid['ma'] + $grid['jo'] + $grid['lu'] + $grid['ven'] + $grid['mer'] + $grid['so']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['sa'] == 0 ? 'bg-danger' : ($grid['sa'] >= 5 ? 'bg-success text-dark' : (($grid['sa'] <= 4) && ($grid['sa'] >= 1) && ($grid['ma'] >= 5) && ($grid['mer'] >= 5) ? 'border-success' : 'border-white')) }}">{{$second_row_sa = $grid['sa'] + $grid['ma'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['ma'] == 0 ? 'bg-danger' : ($grid['ma'] >= 5 ? 'bg-success text-dark' : (($grid['ma'] <= 4) && ($grid['ma'] >= 1) && ($grid['sa'] >= 5) && ($grid['jo'] >= 5) ? 'border-success' : 'border-white')) }}">{{$second_row_ma = $grid['sa'] + $grid['ma'] + $grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['jo'] == 0 ? 'bg-danger' : ($grid['jo'] >= 5 ? 'bg-success text-dark' : (($grid['jo'] <= 4) && ($grid['jo'] >= 1) && ($grid['ma'] >= 5) && ($grid['lu'] >= 5) ? 'border-success' : 'border-white')) }}">{{$second_row_jo = $grid['ma'] + $grid['jo'] + $grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['lu'] == 0 ? 'bg-danger' : ($grid['lu'] >= 5 ? 'bg-success text-dark' : (($grid['lu'] <= 4) && ($grid['lu'] >= 1) && ($grid['jo'] >= 5) && ($grid['ven'] >= 5) ? 'border-success' : 'border-white')) }}">{{$second_row_lu = $grid['jo'] + $grid['lu'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['ven'] == 0 ? 'bg-danger' : ($grid['ven'] >= 5 ? 'bg-success text-dark' : (($grid['ven'] <= 4) && ($grid['ven'] >= 1) && ($grid['lu'] >= 5) && ($grid['mer'] >= 5) ? 'border-success' : 'border-white')) }}">{{$second_row_ven = $grid['lu'] + $grid['ven'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['mer'] == 0 ? 'bg-danger' : ($grid['mer'] >= 5 ? 'bg-success text-dark' : (($grid['mer'] <= 4) && ($grid['mer'] >= 1) && ($grid['ven'] >= 5) && ($grid['sa'] >= 5) ? 'border-success' : 'border-white')) }}">{{$second_row_mer = $grid['ven'] + $grid['mer'] + $grid['sa']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white ">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_sa + $second_row_ma + $second_row_jo + $second_row_lu + $second_row_ven + $second_row_mer}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['sa'] == 0 ? 'bg-danger' : ($grid['sa'] >= 5 ? 'bg-success text-dark' : (($grid['sa'] <= 4) && ($grid['sa'] >= 1) && ($grid['ma'] >= 5) && ($grid['mer'] >= 5) ? 'border-success' : 'border-white')) }}">{{$third_row_sa = $grid['sa'] * $second_row_sa}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['ma'] == 0 ? 'bg-danger' : ($grid['ma'] >= 5 ? 'bg-success text-dark' : (($grid['ma'] <= 4) && ($grid['ma'] >= 1) && ($grid['sa'] >= 5) && ($grid['jo'] >= 5) ? 'border-success' : 'border-white')) }}">{{$third_row_ma = $grid['ma'] * $second_row_ma}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['jo'] == 0 ? 'bg-danger' : ($grid['jo'] >= 5 ? 'bg-success text-dark' : (($grid['jo'] <= 4) && ($grid['jo'] >= 1) && ($grid['ma'] >= 5) && ($grid['lu'] >= 5) ? 'border-success' : 'border-white')) }}">{{$third_row_jo = $grid['jo'] * $second_row_jo}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['lu'] == 0 ? 'bg-danger' : ($grid['lu'] >= 5 ? 'bg-success text-dark' : (($grid['lu'] <= 4) && ($grid['lu'] >= 1) && ($grid['jo'] >= 5) && ($grid['ven'] >= 5) ? 'border-success' : 'border-white')) }}">{{$third_row_lu = $grid['lu'] * $second_row_lu}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['ven'] == 0 ? 'bg-danger' : ($grid['ven'] >= 5 ? 'bg-success text-dark' : (($grid['ven'] <= 4) && ($grid['ven'] >= 1) && ($grid['lu'] >= 5) && ($grid['mer'] >= 5) ? 'border-success' : 'border-white')) }}">{{$third_row_ven = $grid['ven'] * $second_row_ven}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['mer'] == 0 ? 'bg-danger' : ($grid['mer'] >= 5 ? 'bg-success text-dark' : (($grid['mer'] <= 4) && ($grid['mer'] >= 1) && ($grid['ven'] >= 5) && ($grid['sa'] >= 5) ? 'border-success' : 'border-white')) }}">{{$third_row_mer = $grid['mer'] * $second_row_mer}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $grid['so'] == 0 ? 'bg-danger' : ($grid['so'] >= 5 ? 'bg-success' : '') }}">{{$third_row_so = 10 * $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_sa + $third_row_ma + $third_row_jo + $third_row_lu + $third_row_ven + $third_row_mer + $third_row_so}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-11">
                <div class="card" >
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white" id="feature_de" onmousemove="changeColorFeatureDE()" onmouseout="clearColorFeatureDE()">DE</th>
                                <th class="text-center border border-white" id="feature_dom" onmousemove="changeColorFeatureDOM()" onmouseout="clearColorFeatureDOM()">DOM</th>
                                <th class="text-center border border-white" id="feature_fe" onmousemove="changeColorFeatureFE()" onmouseout="clearColorFeatureFE()">FE</th>
                                <th class="text-center border border-white" id="feature_gre" onmousemove="changeColorFeatureGRE()" onmouseout="clearColorFeatureGRE()">GRE</th>
                                <th class="text-center border border-white" id="feature_lun" onmousemove="changeColorFeatureLUN()" onmouseout="clearColorFeatureLUN()">LUN</th>
                                <th class="text-center border border-white" id="feature_nai" onmousemove="changeColorFeatureNAI()" onmouseout="clearColorFeatureNAI()">NAI</th>
                                <th class="text-center border border-white" id="feature_ne" onmousemove="changeColorFeatureNE()" onmouseout="clearColorFeatureNE()">NE</th>
                                <th class="text-center border border-white" id="feature_pow" onmousemove="changeColorFeaturePOW()" onmouseout="clearColorFeaturePOW()">POW</th>
                                <th class="text-center border border-white" id="feature_sp" onmousemove="changeColorFeatureSP()" onmouseout="clearColorFeatureSP()">SP</th>
                                <th class="text-center border border-white" id="feature_tra" onmousemove="changeColorFeatureTRA()" onmouseout="clearColorFeatureTRA()">TRA</th>
                                <th class="text-center border border-white" id="feature_van" onmousemove="changeColorFeatureVAN()" onmouseout="clearColorFeatureVAN()">VAN</th>
                                <th class="text-center border border-white" id="feature_wil" onmousemove="changeColorFeatureWIL()" onmouseout="clearColorFeatureWIL()">WIL</th>
                                <th class="text-center border border-white">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['de']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['dom']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['fe']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['gre']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['lun']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['nai']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['ne']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['pow']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['sp']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['tra']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['van']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['wil']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['de'] + $grid['dom'] + $grid['fe'] + $grid['gre'] + $grid['lun'] + $grid['nai'] + $grid['ne'] + $grid['pow'] + $grid['sp'] + $grid['tra'] + $grid['van'] + $grid['wil']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_de = $grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_dom = $grid['sa'] + $grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_fe = $grid['ma'] + $grid['lu'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_gre = $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_lun = $grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_nai = $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_ne = $grid['sa'] + $grid['lu'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_pow = $grid['jo'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_sp = $grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_tra = $grid['jo'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_van = $grid['jo'] + $grid['ven'] + $grid['mer'] + $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_wil = $grid['ma'] + $grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_de + $second_row_dom + $second_row_fe + $second_row_gre + $second_row_lun + $second_row_nai + $second_row_ne + $second_row_pow + $second_row_sp + $second_row_tra + $second_row_van + $second_row_wil}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_de = $grid['de'] * $second_row_de}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_dom = $grid['dom'] * $second_row_dom}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_fe = $grid['fe'] * $second_row_fe}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_gre = $grid['gre'] * $second_row_gre}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_lun = $grid['lun'] * $second_row_lun}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_nai = $grid['nai'] * $second_row_nai}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_ne = $grid['ne'] * $second_row_ne}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_pow = $grid['pow'] * $second_row_pow}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_sp = $grid['sp'] * $second_row_sp}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_tra = $grid['tra'] * $second_row_tra}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_van = $grid['van'] * $second_row_van}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_wil = $grid['wil'] * $second_row_wil}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_de + $third_row_dom + $third_row_fe + $third_row_gre + $third_row_lun + $third_row_nai + $third_row_ne + $third_row_pow + $third_row_sp + $third_row_tra + $third_row_van + $third_row_wil}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-4">
                <div class="card" >
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white">G</th>
                                <th class="text-center border border-white">S</th>
                                <th class="text-center border border-white">C</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['g']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['s']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['c']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-4">
                <div class="card" >
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white">EM</th>
                                <th class="text-center border border-white">INS</th>
                                <th class="text-center border border-white">INT</th>
                                <th class="text-center border border-white">MOV</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['em']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['ins']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['int']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['mov']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-4">
                <div class="card" >
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white">+</th>
                                <th class="text-center border border-white">-</th>
                                <th class="text-center border border-white">PV</th>
                                <th class="text-center border border-white">EP</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                                <td class="text-sm font-weight-normal text-center border border-white">0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-10 mt-sm-3 me-lg-7" style="z-index: -1">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ URL::asset('assets/js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/threejs.js') }}"></script>
    <script src="{{ URL::asset('assets/js/plugins/orbit-controls.js') }}"></script>
    <script>

        function changeColorStyleSA(style){
            document.getElementById("style_sa").style.backgroundColor = "#ffff00";
            document.getElementById("style_sa").style.color = "black";

            document.getElementById("feature_dom").style.backgroundColor = "#ffff00";
            document.getElementById("feature_dom").style.color = "black";

            document.getElementById("feature_ne").style.backgroundColor = "#ffff00";
            document.getElementById("feature_ne").style.color = "black";
        }

        function clearColorStyleSA() {
            document.getElementById("style_sa").style.backgroundColor = "";
            document.getElementById("style_sa").style.color = "";

            document.getElementById("feature_dom").style.backgroundColor = "";
            document.getElementById("feature_dom").style.color = "";

            document.getElementById("feature_ne").style.backgroundColor = "";
            document.getElementById("feature_ne").style.color = "";
        }

        function changeColorStyleMA(){
            document.getElementById("style_ma").style.backgroundColor = "#ffff00";
            document.getElementById("style_ma").style.color = "black";

            document.getElementById("feature_de").style.backgroundColor = "#ffff00";
            document.getElementById("feature_de").style.color = "black";

            document.getElementById("feature_dom").style.backgroundColor = "#ffff00";
            document.getElementById("feature_dom").style.color = "black";

            document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
            document.getElementById("feature_fe").style.color = "black";

            document.getElementById("feature_wil").style.backgroundColor = "#ffff00";
            document.getElementById("feature_wil").style.color = "black";
        }

        function clearColorStyleMA(){
            document.getElementById("style_ma").style.backgroundColor = "";
            document.getElementById("style_ma").style.color = "";

            document.getElementById("feature_de").style.backgroundColor = "";
            document.getElementById("feature_de").style.color = "";

            document.getElementById("feature_dom").style.backgroundColor = "";
            document.getElementById("feature_dom").style.color = "";

            document.getElementById("feature_fe").style.backgroundColor = "";
            document.getElementById("feature_fe").style.color = "";

            document.getElementById("feature_wil").style.backgroundColor = "";
            document.getElementById("feature_wil").style.color = "";
        }

        function changeColorStyleJO(){
            document.getElementById("style_jo").style.backgroundColor = "#ffff00";
            document.getElementById("style_jo").style.color = "black";

            document.getElementById("feature_pow").style.backgroundColor = "#ffff00";
            document.getElementById("feature_pow").style.color = "black";

            document.getElementById("feature_sp").style.backgroundColor = "#ffff00";
            document.getElementById("feature_sp").style.color = "black";

            document.getElementById("feature_tra").style.backgroundColor = "#ffff00";
            document.getElementById("feature_tra").style.color = "black";

            document.getElementById("feature_van").style.backgroundColor = "#ffff00";
            document.getElementById("feature_van").style.color = "black";
        }

        function clearColorStyleJO(){
            document.getElementById("style_jo").style.backgroundColor = "";
            document.getElementById("style_jo").style.color = "";

            document.getElementById("feature_pow").style.backgroundColor = "";
            document.getElementById("feature_pow").style.color = "";

            document.getElementById("feature_sp").style.backgroundColor = "";
            document.getElementById("feature_sp").style.color = "";

            document.getElementById("feature_tra").style.backgroundColor = "";
            document.getElementById("feature_tra").style.color = "";

            document.getElementById("feature_van").style.backgroundColor = "";
            document.getElementById("feature_van").style.color = "";
        }

        function changeColorStyleLU(){
            document.getElementById("style_lu").style.backgroundColor = "#ffff00";
            document.getElementById("style_lu").style.color = "black";

            document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
            document.getElementById("feature_fe").style.color = "black";

            document.getElementById("feature_lun").style.backgroundColor = "#ffff00";
            document.getElementById("feature_lun").style.color = "black";

            document.getElementById("feature_ne").style.backgroundColor = "#ffff00";
            document.getElementById("feature_ne").style.color = "black";

            document.getElementById("feature_wil").style.backgroundColor = "#ffff00";
            document.getElementById("feature_wil").style.color = "black";
        }

        function clearColorStyleLU(){
            document.getElementById("style_lu").style.backgroundColor = "";
            document.getElementById("style_lu").style.color = "";

            document.getElementById("feature_fe").style.backgroundColor = "";
            document.getElementById("feature_fe").style.color = "";

            document.getElementById("feature_lun").style.backgroundColor = "";
            document.getElementById("feature_lun").style.color = "";

            document.getElementById("feature_ne").style.backgroundColor = "";
            document.getElementById("feature_ne").style.color = "";

            document.getElementById("feature_wil").style.backgroundColor = "";
            document.getElementById("feature_wil").style.color = "";
        }

        function changeColorStyleVEN(){
            document.getElementById("style_ven").style.backgroundColor = "#ffff00";
            document.getElementById("style_ven").style.color = "black";

            document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
            document.getElementById("feature_fe").style.color = "black";

            document.getElementById("feature_lun").style.backgroundColor = "#ffff00";
            document.getElementById("feature_lun").style.color = "black";

            document.getElementById("feature_tra").style.backgroundColor = "#ffff00";
            document.getElementById("feature_tra").style.color = "black";

            document.getElementById("feature_van").style.backgroundColor = "#ffff00";
            document.getElementById("feature_van").style.color = "black";
        }

        function clearColorStyleVEN(){
            document.getElementById("style_ven").style.backgroundColor = "";
            document.getElementById("style_ven").style.color = "";

            document.getElementById("feature_fe").style.backgroundColor = "";
            document.getElementById("feature_fe").style.color = "";

            document.getElementById("feature_lun").style.backgroundColor = "";
            document.getElementById("feature_lun").style.color = "";

            document.getElementById("feature_tra").style.backgroundColor = "";
            document.getElementById("feature_tra").style.color = "";

            document.getElementById("feature_van").style.backgroundColor = "";
            document.getElementById("feature_van").style.color = "";
        }

        function changeColorStyleMER(){
            document.getElementById("style_mer").style.backgroundColor = "#ffff00";
            document.getElementById("style_mer").style.color = "black";

            document.getElementById("feature_gre").style.backgroundColor = "#ffff00";
            document.getElementById("feature_gre").style.color = "black";

            document.getElementById("feature_pow").style.backgroundColor = "#ffff00";
            document.getElementById("feature_pow").style.color = "black";

            document.getElementById("feature_van").style.backgroundColor = "#ffff00";
            document.getElementById("feature_van").style.color = "black";
        }

        function clearColorStyleMER(){
            document.getElementById("style_mer").style.backgroundColor = "";
            document.getElementById("style_mer").style.color = "";

            document.getElementById("feature_gre").style.backgroundColor = "";
            document.getElementById("feature_gre").style.color = "";

            document.getElementById("feature_pow").style.backgroundColor = "";
            document.getElementById("feature_pow").style.color = "";

            document.getElementById("feature_van").style.backgroundColor = "";
            document.getElementById("feature_van").style.color = "";
        }

        function changeColorStyleSO(){
            document.getElementById("style_so").style.backgroundColor = "#ffff00";
            document.getElementById("style_so").style.color = "black";

            document.getElementById("feature_nai").style.backgroundColor = "#ffff00";
            document.getElementById("feature_nai").style.color = "black";

            document.getElementById("feature_van").style.backgroundColor = "#ffff00";
            document.getElementById("feature_van").style.color = "black";
        }

        function clearColorStyleSO(){
            document.getElementById("style_so").style.backgroundColor = "";
            document.getElementById("style_so").style.color = "";

            document.getElementById("feature_nai").style.backgroundColor = "";
            document.getElementById("feature_nai").style.color = "";

            document.getElementById("feature_van").style.backgroundColor = "";
            document.getElementById("feature_van").style.color = "";
        }

        function changeColorFeatureDE(){
            document.getElementById("feature_de").style.backgroundColor = "#ffff00";
            document.getElementById("feature_de").style.color = "black";

            document.getElementById("style_ma").style.backgroundColor = "#ffff00";
            document.getElementById("style_ma").style.color = "black";
        }

        function clearColorFeatureDE(){
            document.getElementById("feature_de").style.backgroundColor = "";
            document.getElementById("feature_de").style.color = "";

            document.getElementById("style_ma").style.backgroundColor = "";
            document.getElementById("style_ma").style.color = "";
        }

        function changeColorFeatureDOM(){
            document.getElementById("feature_dom").style.backgroundColor = "#ffff00";
            document.getElementById("feature_dom").style.color = "black";

            document.getElementById("style_sa").style.backgroundColor = "#ffff00";
            document.getElementById("style_sa").style.color = "black";

            document.getElementById("style_ma").style.backgroundColor = "#ffff00";
            document.getElementById("style_ma").style.color = "black";
        }

        function clearColorFeatureDOM(){
            document.getElementById("feature_dom").style.backgroundColor = "";
            document.getElementById("feature_dom").style.color = "";

            document.getElementById("style_sa").style.backgroundColor = "";
            document.getElementById("style_sa").style.color = "";

            document.getElementById("style_ma").style.backgroundColor = "";
            document.getElementById("style_ma").style.color = "";
        }

        function changeColorFeatureFE(){
            document.getElementById("feature_fe").style.backgroundColor = "#ffff00";
            document.getElementById("feature_fe").style.color = "black";

            document.getElementById("style_ma").style.backgroundColor = "#ffff00";
            document.getElementById("style_ma").style.color = "black";

            document.getElementById("style_lu").style.backgroundColor = "#ffff00";
            document.getElementById("style_lu").style.color = "black";

            document.getElementById("style_ven").style.backgroundColor = "#ffff00";
            document.getElementById("style_ven").style.color = "black";
        }

        function clearColorFeatureFE(){
            document.getElementById("feature_fe").style.backgroundColor = "";
            document.getElementById("feature_fe").style.color = "";

            document.getElementById("style_ma").style.backgroundColor = "";
            document.getElementById("style_ma").style.color = "";

            document.getElementById("style_lu").style.backgroundColor = "";
            document.getElementById("style_lu").style.color = "";

            document.getElementById("style_ven").style.backgroundColor = "";
            document.getElementById("style_ven").style.color = "";
        }

        function changeColorFeatureGRE(){
            document.getElementById("feature_gre").style.backgroundColor = "#ffff00";
            document.getElementById("feature_gre").style.color = "black";

            document.getElementById("style_mer").style.backgroundColor = "#ffff00";
            document.getElementById("style_mer").style.color = "black";
        }

        function clearColorFeatureGRE(){
            document.getElementById("feature_gre").style.backgroundColor = "";
            document.getElementById("feature_gre").style.color = "";

            document.getElementById("style_mer").style.backgroundColor = "";
            document.getElementById("style_mer").style.color = "";
        }

        function changeColorFeatureLUN(){
            document.getElementById("feature_lun").style.backgroundColor = "#ffff00";
            document.getElementById("feature_lun").style.color = "black";

            document.getElementById("style_lu").style.backgroundColor = "#ffff00";
            document.getElementById("style_lu").style.color = "black";
        }

        function clearColorFeatureLUN(){
            document.getElementById("feature_lun").style.backgroundColor = "";
            document.getElementById("feature_lun").style.color = "";

            document.getElementById("style_lu").style.backgroundColor = "";
            document.getElementById("style_lu").style.color = "";
        }

        function changeColorFeatureNAI(){
            document.getElementById("feature_nai").style.backgroundColor = "#ffff00";
            document.getElementById("feature_nai").style.color = "black";

            document.getElementById("style_so").style.backgroundColor = "#ffff00";
            document.getElementById("style_so").style.color = "black";
        }

        function clearColorFeatureNAI(){
            document.getElementById("feature_nai").style.backgroundColor = "";
            document.getElementById("feature_nai").style.color = "";

            document.getElementById("style_so").style.backgroundColor = "";
            document.getElementById("style_so").style.color = "";
        }

        function changeColorFeatureNE(){
            document.getElementById("feature_ne").style.backgroundColor = "#ffff00";
            document.getElementById("feature_ne").style.color = "black";

            document.getElementById("style_sa").style.backgroundColor = "#ffff00";
            document.getElementById("style_sa").style.color = "black";

            document.getElementById("style_lu").style.backgroundColor = "#ffff00";
            document.getElementById("style_lu").style.color = "black";

            document.getElementById("style_ven").style.backgroundColor = "#ffff00";
            document.getElementById("style_ven").style.color = "black";
        }

        function clearColorFeatureNE(){
            document.getElementById("feature_ne").style.backgroundColor = "";
            document.getElementById("feature_ne").style.color = "";

            document.getElementById("style_sa").style.backgroundColor = "";
            document.getElementById("style_sa").style.color = "";

            document.getElementById("style_lu").style.backgroundColor = "";
            document.getElementById("style_lu").style.color = "";

            document.getElementById("style_ven").style.backgroundColor = "";
            document.getElementById("style_ven").style.color = "";
        }

        function changeColorFeaturePOW(){
            document.getElementById("feature_pow").style.backgroundColor = "#ffff00";
            document.getElementById("feature_pow").style.color = "black";

            document.getElementById("style_jo").style.backgroundColor = "#ffff00";
            document.getElementById("style_jo").style.color = "black";

            document.getElementById("style_mer").style.backgroundColor = "#ffff00";
            document.getElementById("style_mer").style.color = "black";
        }

        function clearColorFeaturePOW(){
            document.getElementById("feature_pow").style.backgroundColor = "";
            document.getElementById("feature_pow").style.color = "";

            document.getElementById("style_jo").style.backgroundColor = "";
            document.getElementById("style_jo").style.color = "";

            document.getElementById("style_mer").style.backgroundColor = "";
            document.getElementById("style_mer").style.color = "";
        }

        function changeColorFeatureSP(){
            document.getElementById("feature_sp").style.backgroundColor = "#ffff00";
            document.getElementById("feature_sp").style.color = "black";

            document.getElementById("style_jo").style.backgroundColor = "#ffff00";
            document.getElementById("style_jo").style.color = "black";
        }

        function clearColorFeatureSP(){
            document.getElementById("feature_sp").style.backgroundColor = "";
            document.getElementById("feature_sp").style.color = "";

            document.getElementById("style_jo").style.backgroundColor = "";
            document.getElementById("style_jo").style.color = "";
        }

        function changeColorFeatureTRA(){
            document.getElementById("feature_tra").style.backgroundColor = "#ffff00";
            document.getElementById("feature_tra").style.color = "black";

            document.getElementById("style_jo").style.backgroundColor = "#ffff00";
            document.getElementById("style_jo").style.color = "black";

            document.getElementById("style_ven").style.backgroundColor = "#ffff00";
            document.getElementById("style_ven").style.color = "black";
        }

        function clearColorFeatureTRA(){
            document.getElementById("feature_tra").style.backgroundColor = "";
            document.getElementById("feature_tra").style.color = "";

            document.getElementById("style_jo").style.backgroundColor = "";
            document.getElementById("style_jo").style.color = "";

            document.getElementById("style_ven").style.backgroundColor = "";
            document.getElementById("style_ven").style.color = "";
        }

        function changeColorFeatureVAN(){
            document.getElementById("feature_van").style.backgroundColor = "#ffff00";
            document.getElementById("feature_van").style.color = "black";

            document.getElementById("style_jo").style.backgroundColor = "#ffff00";
            document.getElementById("style_jo").style.color = "black";

            document.getElementById("style_ven").style.backgroundColor = "#ffff00";
            document.getElementById("style_ven").style.color = "black";

            document.getElementById("style_mer").style.backgroundColor = "#ffff00";
            document.getElementById("style_mer").style.color = "black";

            document.getElementById("style_so").style.backgroundColor = "#ffff00";
            document.getElementById("style_so").style.color = "black";
        }

        function clearColorFeatureVAN(){
            document.getElementById("feature_van").style.backgroundColor = "";
            document.getElementById("feature_van").style.color = "";

            document.getElementById("style_jo").style.backgroundColor = "";
            document.getElementById("style_jo").style.color = "";

            document.getElementById("style_ven").style.backgroundColor = "";
            document.getElementById("style_ven").style.color = "";

            document.getElementById("style_mer").style.backgroundColor = "";
            document.getElementById("style_mer").style.color = "";

            document.getElementById("style_so").style.backgroundColor = "";
            document.getElementById("style_so").style.color = "";
        }

        function changeColorFeatureWIL(){
            document.getElementById("feature_wil").style.backgroundColor = "#ffff00";
            document.getElementById("feature_wil").style.color = "black";

            document.getElementById("style_ma").style.backgroundColor = "#ffff00";
            document.getElementById("style_ma").style.color = "black";

            document.getElementById("style_lu").style.backgroundColor = "#ffff00";
            document.getElementById("style_lu").style.color = "black";
        }

        function clearColorFeatureWIL(){
            document.getElementById("feature_wil").style.backgroundColor = "";
            document.getElementById("feature_wil").style.color = "";

            document.getElementById("style_ma").style.backgroundColor = "";
            document.getElementById("style_ma").style.color = "";

            document.getElementById("style_lu").style.backgroundColor = "";
            document.getElementById("style_lu").style.color = "";
        }

        (function() {
            const container = document.getElementById("globe");
            const canvas = container.getElementsByTagName("canvas")[0];

            const globeRadius = 100;
            const globeWidth = 4098 / 2;
            const globeHeight = 1968 / 2;

            function convertFlatCoordsToSphereCoords(x, y) {
                let latitude = ((x - globeWidth) / globeWidth) * -180;
                let longitude = ((y - globeHeight) / globeHeight) * -90;
                latitude = (latitude * Math.PI) / 180;
                longitude = (longitude * Math.PI) / 180;
                const radius = Math.cos(longitude) * globeRadius;

                return {
                    x: Math.cos(latitude) * radius,
                    y: Math.sin(longitude) * globeRadius,
                    z: Math.sin(latitude) * radius
                };
            }

            function makeMagic(points) {
                const {
                    width,
                    height
                } = container.getBoundingClientRect();

                // 1. Setup scene
                const scene = new THREE.Scene();
                // 2. Setup camera
                const camera = new THREE.PerspectiveCamera(45, width / height);
                // 3. Setup renderer
                const renderer = new THREE.WebGLRenderer({
                    canvas,
                    antialias: true
                });
                renderer.setSize(width, height);
                // 4. Add points to canvas
                // - Single geometry to contain all points.
                const mergedGeometry = new THREE.Geometry();
                // - Material that the dots will be made of.
                const pointGeometry = new THREE.SphereGeometry(0.5, 1, 1);
                const pointMaterial = new THREE.MeshBasicMaterial({
                    color: "#989db5",
                });

                for (let point of points) {
                    const {
                        x,
                        y,
                        z
                    } = convertFlatCoordsToSphereCoords(
                        point.x,
                        point.y,
                        width,
                        height
                    );

                    if (x && y && z) {
                        pointGeometry.translate(x, y, z);
                        mergedGeometry.merge(pointGeometry);
                        pointGeometry.translate(-x, -y, -z);
                    }
                }

                const globeShape = new THREE.Mesh(mergedGeometry, pointMaterial);
                scene.add(globeShape);

                container.classList.add("peekaboo");

                // Setup orbital controls
                camera.orbitControls = new THREE.OrbitControls(camera, canvas);
                camera.orbitControls.enableKeys = false;
                camera.orbitControls.enablePan = false;
                camera.orbitControls.enableZoom = false;
                camera.orbitControls.enableDamping = false;
                camera.orbitControls.enableRotate = true;
                camera.orbitControls.autoRotate = true;
                camera.position.z = -265;

                function animate() {
                    // orbitControls.autoRotate is enabled so orbitControls.update
                    // must be called inside animation loop.
                    camera.orbitControls.update();
                    requestAnimationFrame(animate);
                    renderer.render(scene, camera);
                }
                animate();
            }

            function hasWebGL() {
                const gl =
                    canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
                if (gl && gl instanceof WebGLRenderingContext) {
                    return true;
                } else {
                    return false;
                }
            }

            function init() {
                if (hasWebGL()) {
                    window
                    window.fetch("https://raw.githubusercontent.com/creativetimofficial/public-assets/master/soft-ui-dashboard-pro/assets/js/points.json")
                        .then(response => response.json())
                        .then(data => {
                            makeMagic(data.points);
                        });
                }
            }
            init();
        })();
    </script>
@endpush
