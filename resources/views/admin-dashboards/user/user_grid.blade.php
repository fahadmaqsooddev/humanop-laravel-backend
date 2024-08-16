@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .green
    {
        background-color: green !important;
    }
    .red
    {
        background-color: red !important;
    }
    .yellow
    {
        background-color: yellow !important;
        color: black !important;
        font-weight: bold !important;
    }
    .border-green
    {
        border: 2px solid green !important;
    }
</style>
@section('content')
    <div>
        <div>
            <a href="{{url('admin/generate-grid-pdf/'. $grid['id'])}}" target="_blank"
               class="btn btn-sm float-end mt-4 mb-4 text-white mx-4"
               style="background-color: #f2661c">PDF</a>
        </div>
        @php
            $second_row_em = $grid['jo'] + $grid['ven'] + $grid['lu'];
            $second_row_ins = $grid['ma'] + $grid['ven'] + $grid['mer'];
            $second_row_int = $grid['jo'] + $grid['sa'] + $grid['mer'];
            $second_row_mov = $grid['ma'] + $grid['so'] + $grid['mer'];
            $communication_style_array = [$second_row_em, $second_row_ins, $second_row_int, $second_row_mov];
            $communication_style = max($communication_style_array);

            $third_row_em = $grid['em'] * $second_row_em;
            $third_row_ins = $grid['ins'] * $second_row_ins;
            $third_row_int = $grid['int'] * $second_row_int;
            $third_row_mov = $grid['mov'] * $second_row_mov;

            $communication_third_style_array = [$third_row_em, $third_row_ins, $third_row_int, $third_row_mov];
            $communication_third_style = max($communication_third_style_array);
        @endphp

        <div class="row mt-4">
            <div class="col-8">
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
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['sa']) && $grid_code_color['sa'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('sa', array_keys($grid_code_color))) {{ $grid_code_color['sa'] }} @endif">{{$grid['sa']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['ma']) && $grid_code_color['ma'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('ma', array_keys($grid_code_color))) {{ $grid_code_color['ma'] }} @endif">{{$grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['jo']) && $grid_code_color['jo'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('jo', array_keys($grid_code_color))) {{ $grid_code_color['jo'] }} @endif">{{$grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['lu']) && $grid_code_color['lu'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('lu', array_keys($grid_code_color))) {{ $grid_code_color['lu'] }} @endif">{{$grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['ven']) && $grid_code_color['ven'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('ven', array_keys($grid_code_color))) {{ $grid_code_color['ven'] }} @endif">{{$grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['mer']) && $grid_code_color['mer'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('mer', array_keys($grid_code_color))) {{ $grid_code_color['mer'] }} @endif">{{$grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['so']) && $grid_code_color['so'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('so', array_keys($grid_code_color))) {{ $grid_code_color['so'] }} @endif">{{$grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border ">{{$grid['sa'] + $grid['ma'] + $grid['jo'] + $grid['lu'] + $grid['ven'] + $grid['mer'] + $grid['so']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['sa']) && $grid_code_color['sa'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('sa', array_keys($grid_code_color))) {{ $grid_code_color['sa'] }} @endif">{{$second_row_sa = $grid['sa'] + $grid['ma'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['ma']) && $grid_code_color['ma'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('ma', array_keys($grid_code_color))) {{ $grid_code_color['ma'] }} @endif">{{$second_row_ma = $grid['sa'] + $grid['ma'] + $grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['jo']) && $grid_code_color['jo'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('jo', array_keys($grid_code_color))) {{ $grid_code_color['jo'] }} @endif">{{$second_row_jo = $grid['ma'] + $grid['jo'] + $grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['lu']) && $grid_code_color['lu'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('lu', array_keys($grid_code_color))) {{ $grid_code_color['lu'] }} @endif">{{$second_row_lu = $grid['jo'] + $grid['lu'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['ven']) && $grid_code_color['ven'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('ven', array_keys($grid_code_color))) {{ $grid_code_color['ven'] }} @endif">{{$second_row_ven = $grid['lu'] + $grid['ven'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['mer']) && $grid_code_color['mer'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('mer', array_keys($grid_code_color))) {{ $grid_code_color['mer'] }} @endif">{{$second_row_mer = $grid['ven'] + $grid['mer'] + $grid['sa']}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['so']) && $grid_code_color['so'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('so', array_keys($grid_code_color))) {{ $grid_code_color['so'] }} @endif">0</td>
                                <td class="text-sm font-weight-normal text-center border ">{{$second_row_sa + $second_row_ma + $second_row_jo + $second_row_lu + $second_row_ven + $second_row_mer}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['sa']) && $grid_code_color['sa'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('sa', array_keys($grid_code_color))) {{ $grid_code_color['sa'] }} @endif">{{$third_row_sa = $grid['sa'] * $second_row_sa}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['ma']) && $grid_code_color['ma'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('ma', array_keys($grid_code_color))) {{ $grid_code_color['ma'] }} @endif">{{$third_row_ma = $grid['ma'] * $second_row_ma}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['jo']) && $grid_code_color['jo'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('jo', array_keys($grid_code_color))) {{ $grid_code_color['jo'] }} @endif">{{$third_row_jo = $grid['jo'] * $second_row_jo}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['lu']) && $grid_code_color['lu'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('lu', array_keys($grid_code_color))) {{ $grid_code_color['lu'] }} @endif">{{$third_row_lu = $grid['lu'] * $second_row_lu}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['ven']) && $grid_code_color['ven'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('ven', array_keys($grid_code_color))) {{ $grid_code_color['ven'] }} @endif">{{$third_row_ven = $grid['ven'] * $second_row_ven}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['mer']) && $grid_code_color['mer'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('mer', array_keys($grid_code_color))) {{ $grid_code_color['mer'] }} @endif">{{$third_row_mer = $grid['mer'] * $second_row_mer}}</td>
                                <td class="text-sm font-weight-normal text-center {{ $grid_code_color && isset($grid_code_color['so']) && $grid_code_color['so'] == 'border-green' ? 'border-green' : 'border' }} @if(in_array('so', array_keys($grid_code_color))) {{ $grid_code_color['so'] }} @endif">0</td>
                                <td class="text-sm font-weight-normal text-center border ">{{$third_row_sa + $third_row_ma + $third_row_jo + $third_row_lu + $third_row_ven + $third_row_mer}}</td>
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
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('de', array_keys($grid_code_color))) {{ $grid_code_color['de'] }} @endif">{{$de = $grid['de']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('dom', array_keys($grid_code_color))) {{ $grid_code_color['dom'] }} @endif">{{$dom = $grid['dom']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('fe', array_keys($grid_code_color))) {{ $grid_code_color['fe'] }} @endif">{{$fe = $grid['fe']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('gre', array_keys($grid_code_color))) {{ $grid_code_color['gre'] }} @endif">{{$gre = $grid['gre']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('lun', array_keys($grid_code_color))) {{ $grid_code_color['lun'] }} @endif">{{$lun = $grid['lun']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('nai', array_keys($grid_code_color))) {{ $grid_code_color['nai'] }} @endif">{{$nai = $grid['nai']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('ne', array_keys($grid_code_color))) {{ $grid_code_color['ne'] }} @endif">{{$ne = $grid['ne']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('pow', array_keys($grid_code_color))) {{ $grid_code_color['pow'] }} @endif">{{$pow = $grid['pow']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('sp', array_keys($grid_code_color))) {{ $grid_code_color['sp'] }} @endif">{{$sp = $grid['sp']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('tra', array_keys($grid_code_color))) {{ $grid_code_color['tra'] }} @endif">{{$tra = $grid['tra']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('van', array_keys($grid_code_color))) {{ $grid_code_color['van'] }} @endif">{{$van = $grid['van']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('wil', array_keys($grid_code_color))) {{ $grid_code_color['wil'] }} @endif">{{$wil = $grid['wil']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$result = $de + $dom + $fe + $gre + $lun + $nai + $ne + $pow + $sp + $tra + $van + $wil}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('de', array_keys($grid_code_color))) {{ $grid_code_color['de'] }} @endif">{{$second_row_de = $grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('dom', array_keys($grid_code_color))) {{ $grid_code_color['dom'] }} @endif">{{$second_row_dom = $grid['sa'] + $grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('fe', array_keys($grid_code_color))) {{ $grid_code_color['fe'] }} @endif">{{$second_row_fe = $grid['ma'] + $grid['lu'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('gre', array_keys($grid_code_color))) {{ $grid_code_color['gre'] }} @endif">{{$second_row_gre = $grid['mer'] + $grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('lun', array_keys($grid_code_color))) {{ $grid_code_color['lun'] }} @endif">{{$second_row_lun = $grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('nai', array_keys($grid_code_color))) {{ $grid_code_color['nai'] }} @endif">{{$second_row_nai = $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('ne', array_keys($grid_code_color))) {{ $grid_code_color['ne'] }} @endif">{{$second_row_ne = $grid['sa'] + $grid['lu'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('pow', array_keys($grid_code_color))) {{ $grid_code_color['pow'] }} @endif">{{$second_row_pow = $grid['jo'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('sp', array_keys($grid_code_color))) {{ $grid_code_color['sp'] }} @endif">{{$second_row_sp = $grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('tra', array_keys($grid_code_color))) {{ $grid_code_color['tra'] }} @endif">{{$second_row_tra = $grid['jo'] + $grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('van', array_keys($grid_code_color))) {{ $grid_code_color['van'] }} @endif">{{$second_row_van = $grid['jo'] + $grid['ven'] + $grid['mer'] + $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('wil', array_keys($grid_code_color))) {{ $grid_code_color['wil'] }} @endif">{{$second_row_wil = $grid['ma'] + $grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_result = $second_row_de + $second_row_dom + $second_row_fe + $second_row_gre + $second_row_lun + $second_row_nai + $second_row_ne + $second_row_pow + $second_row_sp + $second_row_tra + $second_row_van + $second_row_wil}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('de', array_keys($grid_code_color))) {{ $grid_code_color['de'] }} @endif">{{$third_row_de = $grid['de'] * $second_row_de}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('dom', array_keys($grid_code_color))) {{ $grid_code_color['dom'] }} @endif">{{$third_row_dom = $grid['dom'] * $second_row_dom}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('fe', array_keys($grid_code_color))) {{ $grid_code_color['fe'] }} @endif">{{$third_row_fe = $grid['fe'] * $second_row_fe}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('gre', array_keys($grid_code_color))) {{ $grid_code_color['gre'] }} @endif">{{$third_row_gre = $grid['gre'] * $second_row_gre}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('lun', array_keys($grid_code_color))) {{ $grid_code_color['lun'] }} @endif">{{$third_row_lun = $grid['lun'] * $second_row_lun}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('nai', array_keys($grid_code_color))) {{ $grid_code_color['nai'] }} @endif">{{$third_row_nai = $grid['nai'] * $second_row_nai}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('ne', array_keys($grid_code_color))) {{ $grid_code_color['ne'] }} @endif">{{$third_row_ne = $grid['ne'] * $second_row_ne}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('pow', array_keys($grid_code_color))) {{ $grid_code_color['pow'] }} @endif">{{$third_row_pow = $grid['pow'] * $second_row_pow}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('sp', array_keys($grid_code_color))) {{ $grid_code_color['sp'] }} @endif">{{$third_row_sp = $grid['sp'] * $second_row_sp}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('tra', array_keys($grid_code_color))) {{ $grid_code_color['tra'] }} @endif">{{$third_row_tra = $grid['tra'] * $second_row_tra}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('van', array_keys($grid_code_color))) {{ $grid_code_color['van'] }} @endif">{{$third_row_van = $grid['van'] * $second_row_van}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('wil', array_keys($grid_code_color))) {{ $grid_code_color['wil'] }} @endif">{{$third_row_wil = $grid['wil'] * $second_row_wil}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_result = $third_row_de + $third_row_dom + $third_row_fe + $third_row_gre + $third_row_lun + $third_row_nai + $third_row_ne + $third_row_pow + $third_row_sp + $third_row_tra + $third_row_van + $third_row_wil}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-5">
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
                                <td class="text-sm font-weight-normal text-center border border-white">{{$gold = $grid['g']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$silver = $grid['s']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$copper = $grid['c']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_gold = $grid['mer'] + $grid['sa'] + $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_silver = $grid['ven'] + $grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_copper = $grid['ma'] + $grid['lu']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$gold * $second_row_gold}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$silver * $second_row_silver}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$copper * $second_row_copper}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-5">
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
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['em'] < 7 || $grid['em'] > 12 ? 'red' : ''}}">{{$grid['em']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['ins'] < 7 || $grid['ins'] > 12 ? 'red' : ''}}">{{$grid['ins']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['int'] < 7 || $grid['int'] > 12 ? 'red' : ''}}">{{$grid['int']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['mov'] < 7 || $grid['mov'] > 12 ? 'red' : ''}}">{{$grid['mov']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_em ==  $communication_style ? 'green' : ''}}">{{$second_row_em}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_ins ==  $communication_style ? 'green' : ''}}">{{$second_row_ins}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_int ==  $communication_style ? 'green' : ''}}">{{$second_row_int}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_mov ==  $communication_style ? 'green' : ''}}">{{$second_row_mov}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_em > 29 && $third_row_em < 301 && $third_row_em == $communication_third_style) ? 'green' : (($third_row_em < 30 || $third_row_em > 300) ? 'red' : '') }}">{{$third_row_em}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_ins > 29 && $third_row_ins < 301 && $third_row_ins == $communication_third_style) ? 'green' : (($third_row_ins < 30 || $third_row_ins > 300) ? 'red' : '') }}">{{$third_row_ins}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_int > 29 && $third_row_int < 301 && $third_row_int == $communication_third_style) ? 'green' : (($third_row_int < 30 || $third_row_int > 300) ? 'red' : '') }}">{{$third_row_int}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_mov > 29 && $third_row_mov < 301 && $third_row_mov == $communication_third_style) ? 'green' : (($third_row_mov < 30 || $third_row_mov > 300) ? 'red' : '') }}">{{$third_row_mov}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-5">
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
                                <td class="text-sm font-weight-normal text-center border border-white">{{$positive = $grid['sa'] + $grid['jo'] + $grid['ven'] + $grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$negative = $grid['ma'] + $grid['lu'] + $grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$positive - $negative}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$positive + $negative}}</td>
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

