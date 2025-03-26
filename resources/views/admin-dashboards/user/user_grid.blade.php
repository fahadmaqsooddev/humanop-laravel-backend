@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .greenBox {
        background-color: green !important;
    }

    .redBox {
        background-color: red !important;
    }

    .lightGreenBox {
        background-color: yellow !important;
        color: black !important;
        font-weight: bold !important;
    }

    .border-green {
        border: 2px solid green !important;
    }

    .left-nav-blue-light-color {
        background: #2C4C7E !important;
    }
</style>
@section('content')
    <div class="d-flex flex-column container-fluid">
        <div>
            <a href="{{url('admin/generate-grid-pdf/'. $grid['id'])}}" target="_blank"
               class="btn btn-sm float-end mt-4 mb-4 text-white mx-4"
               style="background-color: #f2661c">PDF</a>
        </div>
        @php
            $second_row_sa = $grid['sa'] + $grid['ma'] + $grid['mer'];
            $second_row_ma = $grid['sa'] + $grid['ma'] + $grid['jo'];
            $second_row_jo = $grid['ma'] + $grid['jo'] + $grid['lu'];
            $second_row_lu = $grid['jo'] + $grid['lu'] + $grid['ven'];
            $second_row_ven = $grid['lu'] + $grid['ven'] + $grid['mer'];
            $second_row_mer = $grid['ven'] + $grid['mer'] + $grid['sa'];

            $third_row_sa = $grid['sa'] * $second_row_sa;
            $third_row_ma = $grid['ma'] * $second_row_ma;
            $third_row_jo = $grid['jo'] * $second_row_jo;
            $third_row_lu = $grid['lu'] * $second_row_lu;
            $third_row_ven = $grid['ven'] * $second_row_ven;
            $third_row_mer = $grid['mer'] * $second_row_mer;
            $third_row_so = 0;

            // Initialize variables based on $grid values
            $de = $grid['de'];
            $dom = $grid['dom'];
            $fe = $grid['fe'];
            $gre = $grid['gre'];
            $lun = $grid['lun'];
            $nai = $grid['nai'];
            $ne = $grid['ne'];
            $pow = $grid['pow'];
            $sp = $grid['sp'];
            $tra = $grid['tra'];
            $van = $grid['van'];
            $wil = $grid['wil'];

            // Calculate result sums
            $result = $de + $dom + $fe + $gre + $lun + $nai + $ne + $pow + $sp + $tra + $van + $wil;

            // Calculate second row values
            $second_row_de = $grid['ma'];
            $second_row_dom = $grid['sa'] + $grid['ma'];
            $second_row_fe = $grid['ma'] + $grid['lu'] + $grid['ven'];
            $second_row_gre = $grid['jo'] > 6 ? $grid['jo'] + $grid['mer'] : $grid['mer'];
            $second_row_lun = $grid['lu'];
            $second_row_nai = $grid['so'];
            $second_row_ne = $grid['sa'] + $grid['lu'] + $grid['ven'];
            $second_row_pow = $grid['jo'] + $grid['mer'];
            $second_row_sp = $grid['jo'];
            $second_row_tra = $grid['jo'] + $grid['ven'];
            $second_row_van = $grid['jo'] + $grid['ven'] + $grid['mer'] + $grid['so'];
            $second_row_wil = $grid['ma'] + $grid['lu'];
            $second_row_result = $second_row_de + $second_row_dom + $second_row_fe + $second_row_gre + $second_row_lun + $second_row_nai + $second_row_ne + $second_row_pow + $second_row_sp + $second_row_tra + $second_row_van + $second_row_wil;

            // Calculate third row values
            $third_row_de = $grid['de'] * $second_row_de;
            $third_row_dom = $grid['dom'] * $second_row_dom;
            $third_row_fe = $grid['fe'] * $second_row_fe;
            $third_row_gre = $grid['gre'] * $second_row_gre;
            $third_row_lun = $grid['lun'] * $second_row_lun;
            $third_row_nai = $grid['nai'] * $second_row_nai;
            $third_row_ne = $grid['ne'] * $second_row_ne;
            $third_row_pow = $grid['pow'] * $second_row_pow;
            $third_row_sp = $grid['sp'] * $second_row_sp;
            $third_row_tra = $grid['tra'] * $second_row_tra;
            $third_row_van = $grid['van'] * $second_row_van;
            $third_row_wil = $grid['wil'] * $second_row_wil;
            $third_row_result = $third_row_de + $third_row_dom + $third_row_fe + $third_row_gre + $third_row_lun + $third_row_nai + $third_row_ne + $third_row_pow + $third_row_sp + $third_row_tra + $third_row_van + $third_row_wil;

            // Define features array
            $features = [
                'de' => $grid['de'],
                'dom' => $grid['dom'],
                'fe' => $grid['fe'],
                'gre' => $grid['gre'],
                'lun' => $grid['lun'],
                'nai' => $grid['nai'],
                'ne' => $grid['ne'],
                'pow' => $grid['pow'],
                'sp' => $grid['sp'],
                'tra' => $grid['tra'],
                'van' => $grid['van'],
                'wil' => $grid['wil'],
            ];

            $third_row_feature = [
                'de' => $grid['de'] * $second_row_de,
                'dom' => $grid['dom'] * $second_row_dom,
                'fe' => $grid['fe'] * $second_row_fe,
                'gre' => $grid['gre'] * $second_row_gre,
                'lun' => $grid['lun'] * $second_row_lun,
                'nai' => $grid['nai'] * $second_row_nai,
                'ne' => $grid['ne'] * $second_row_ne,
                'pow' => $grid['pow'] * $second_row_pow,
                'sp' => $grid['sp'] * $second_row_sp,
                'tra' => $grid['tra'] * $second_row_tra,
                'van' => $grid['van'] * $second_row_van,
                'wil' => $grid['wil'] * $second_row_wil,
            ];

            // Sort features in descending order while maintaining key associations
            arsort($features);

            // Filter keys based on conditions
            $filtered_keys = [];
            $filtered_keys_red = [];

            foreach ($features as $key => $value) {
            switch ($key) {
                case 'de':
                    if (($grid['de'] > 2 && $grid['ma'] > 4) || ($grid['de'] > 2 && $grid['sa'] > 4 && $grid['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['de'] > 2 && $grid['ma'] < 5) && ($grid['sa'] < 5 || $grid['jo'] < 5 )) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'dom':
                    if (($grid['dom'] > 2 && ($grid['sa'] > 4 || $grid['ma'] > 4)) || ($grid['dom'] > 2 && $grid['ma'] > 4 && $grid['mer'] > 4) || ($grid['dom'] > 2 && $grid['sa'] > 4 && $grid['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['dom'] > 2 && ($grid['sa'] < 5 && $grid['ma'] < 5)) && ($grid['ma'] < 5 || $grid['mer'] < 5 || $grid['sa'] < 5 || $grid['jo'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'fe':
                    if (($grid['fe'] > 2 && ($grid['ma'] > 4 || $grid['lu'] > 4 || $grid['ven'] > 4)) || ($grid['fe'] > 2 && $grid['sa'] > 4 && $grid['jo'] > 4) || ($grid['fe'] > 2 && $grid['jo'] > 4 && $grid['ven'] > 4) || ($grid['fe'] > 2 && $grid['lu'] > 4 && $grid['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['fe'] > 2 && ($grid['ma'] < 5 && $grid['lu'] < 5 && $grid['ven'] < 5)) && ($grid['sa'] < 5 || $grid['jo'] < 5 || $grid['ven'] < 5 || $grid['lu'] < 5 || $grid['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'gre':
                    if (($grid['gre'] > 2 && ($grid['jo'] > 6 || $grid['mer'] > 4 )) || ($grid['gre'] > 2 && $grid['ven'] > 4 && $grid['so'] > 4) || ($grid['gre'] > 2 && $grid['ma'] > 4 && $grid['lu'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['gre'] > 2 && $grid['jo'] < 7 && $grid['mer'] < 5) && ($grid['gre'] > 2 && ($grid['ma'] < 5 || $grid['lu'] < 5)) && ($grid['gre'] > 2 && ($grid['ven'] < 5 || $grid['so'] < 5))) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'lun':
                    if (($grid['lun'] > 2 && $grid['lu'] > 4) || ($grid['lun'] > 2 && $grid['ven'] > 4 && $grid['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['lun'] > 2 && $grid['lu'] < 5) && ($grid['ven'] < 5 || $grid['jo'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'nai':
                    if (($grid['nai'] > 2 && $grid['so'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['nai'] > 2 && $grid['so'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'ne':
                    if (($grid['ne'] > 2 && ($grid['sa'] > 4 || $grid['lu'] > 4 || $grid['ven'] > 4)) || ($grid['ne'] > 2 && $grid['ma'] > 4 && $grid['mer'] > 4) || ($grid['ne'] > 2 && $grid['ven'] > 4 && $grid['jo'] > 4) || ($grid['ne'] > 2 && $grid['lu'] > 4 && $grid['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif(($grid['ne'] > 2 && ($grid['sa'] < 5 && $grid['lu'] < 5 && $grid['ven'] < 5)) && ($grid['ne'] < 5 || $grid['ma'] < 5 || $grid['mer'] < 5 || $grid['ven'] < 5 || $grid['jo'] < 5 || $grid['lu'] < 5)){
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'pow':
                    if (($grid['pow'] > 2 && ($grid['jo'] > 4 || $grid['mer'] > 4)) || ($grid['pow'] > 2 && $grid['ma'] > 4 && $grid['lu'] > 4) || ($grid['pow'] > 2 && $grid['ven'] > 4 && $grid['sa'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['pow'] > 2 && ($grid['jo'] < 5 && $grid['mer'] < 5)) && ($grid['ma'] < 5 || $grid['lu'] < 5 || $grid['ven'] < 5 || $grid['sa'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'sp':
                    if (($grid['sp'] > 2 && $grid['jo'] > 4) || ($grid['sp'] > 2 && $grid['ma'] > 4 && $grid['lu'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['sp'] > 2 && $grid['jo'] < 5) && ($grid['ma'] < 5 || $grid['lu'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    elseif (($grid['tra'] > 2 && ($grid['jo'] < 5 && $grid['ven'] < 5)) && ($grid['ma'] < 5 || $grid['lu'] < 5 || $grid['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'tra':
                    if (($grid['tra'] > 2 && ($grid['jo'] > 4 || $grid['ven'] > 4)) || ($grid['tra'] > 2 && $grid['ma'] > 4 && $grid['lu'] > 4) || ($grid['tra'] > 2 && $grid['lu'] > 4 && $grid['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['tra'] > 2 && ($grid['jo'] < 5 && $grid['ven'] < 5)) && ($grid['ma'] < 5 || $grid['lu'] < 5 || $grid['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'van':
                    if (($grid['van'] > 2 && ($grid['jo'] > 4 || $grid['ven'] > 4 || $grid['mer'] > 4 || $grid['so'] > 4)) || ($grid['van'] > 2 && $grid['ma'] > 4 && $grid['lu'] > 4) || ($grid['van'] > 2 && $grid['lu'] > 4 && $grid['mer'] > 4) || ($grid['van'] > 2 && $grid['ven'] > 4 && $grid['sa'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['van'] > 2 && ($grid['jo'] < 5 && $grid['ven'] < 5 && $grid['mer'] < 5 && $grid['so'] < 5)) && ($grid['ma'] < 5 || $grid['lu'] < 5 || $grid['mer'] < 5 || $grid['ven'] < 5 || $grid['sa'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'wil':
                    if (($grid['wil'] > 2 && ($grid['ma'] > 4 || $grid['lu'] > 4)) || ($grid['wil'] > 2 && $grid['sa'] > 4 && $grid['jo'] > 4) || ($grid['wil'] > 2 && $grid['jo'] > 4 && $grid['ven'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($grid['wil'] > 2 && ($grid['ma'] < 5 && $grid['lu'] < 5)) && ($grid['sa'] < 5 || $grid['jo'] < 5 || $grid['ven'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
            }
        }

            $redKeys = array_keys($filtered_keys_red);

            if (count($filtered_keys) < 2) {

                // Get the matching keys and their values from $third_row_feature
                $matchingKeys = array_intersect_key($third_row_feature, array_flip(array_keys($filtered_keys)));
                arsort($matchingKeys);

                $all_values_are_2 = [];
                foreach ($features as $key => $value) {
                    if ($value == 2) {
                        $all_values_are_2[$key] = $value;
                    }
                }

                $matchingKeysLessThanTwo = array_intersect_key($third_row_feature, array_flip(array_keys($all_values_are_2)));
                arsort($matchingKeysLessThanTwo);

                $topAllKeys = array_merge($matchingKeys, $matchingKeysLessThanTwo);

                $topTwoKeys = array_slice(array_keys($topAllKeys), 0, 2);
                $nextTwoKeys = [];
            }
            else {
                $greater_than_three_filtered_keys = [];
                foreach ($filtered_keys as $key => $value) {
                    if ($value > 3) { // Check if the value is greater than 3
                        $greater_than_three_filtered_keys[$key] = $value;
                    }
                }

                // Get keys that are in $filtered_keys but not in $greater_than_three_filtered_keys
                $remainingFilterKeys = array_diff_key($filtered_keys, $greater_than_three_filtered_keys);

                $firstHighestArrayValue = [];
                $remainingHighestArrayValue = [];
                if (count($greater_than_three_filtered_keys) > 1 || count($greater_than_three_filtered_keys) == 1) {
                    $firstHighestArrayValue = array_intersect_key($third_row_feature, array_flip(array_keys($greater_than_three_filtered_keys)));
                    arsort($firstHighestArrayValue);
                }
                if (count($remainingFilterKeys) != 0){
                    $remainingHighestArrayValue = array_intersect_key($third_row_feature, array_flip(array_keys($remainingFilterKeys)));
                    arsort($remainingHighestArrayValue);
                }
                $allValuesGets = array_merge($firstHighestArrayValue, $remainingHighestArrayValue);

                $topTwoKeys = array_slice(array_keys($allValuesGets), 0, 2);
                $nextTwoKeys = array_slice(array_keys($allValuesGets), 2);

            }

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
            <div class="col-12 col-md-8">
                <div class="card left-nav-blue-light-color">
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white" id="style_sa"
                                    onmousemove="changeColorStyleSA()" onmouseout="clearColorStyleSA()">SA
                                </th>
                                <th class="text-center border border-white" id="style_ma"
                                    onmousemove="changeColorStyleMA()" onmouseout="clearColorStyleMA()">MA
                                </th>
                                <th class="text-center border border-white" id="style_jo"
                                    onmousemove="changeColorStyleJO()" onmouseout="clearColorStyleJO()">JO
                                </th>
                                <th class="text-center border border-white" id="style_lu"
                                    onmousemove="changeColorStyleLU()" onmouseout="clearColorStyleLU()">LU
                                </th>
                                <th class="text-center border border-white" id="style_ven"
                                    onmousemove="changeColorStyleVEN()" onmouseout="clearColorStyleVEN()">VEN
                                </th>
                                <th class="text-center border border-white" id="style_mer"
                                    onmousemove="changeColorStyleMER()" onmouseout="clearColorStyleMER()">MER
                                </th>
                                <th class="text-center border border-white" id="style_so"
                                    onmousemove="changeColorStyleSO()" onmouseout="clearColorStyleSO()">SO
                                </th>
                                <th class="text-center border border-white">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['sa'] == 0 ? 'redBox' : ($grid['sa'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['mer'] > 4 && $third_row_sa > 30? 'border-success' : 'border-white')) }}">{{$grid['sa']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ma'] == 0 ? 'redBox' : ($grid['ma'] > 4 ? 'greenBox text-dark' : ($grid['sa'] > 4 && $grid['jo'] > 4 && $third_row_ma > 30 ? 'border-success' : 'border-white')) }}">{{$grid['ma']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['jo'] == 0 ? 'redBox' : ($grid['jo'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['lu'] > 4 && $third_row_jo > 30 ? 'border-success' : 'border-white')) }}">{{$grid['jo']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['lu'] == 0 ? 'redBox' : ($grid['lu'] > 4 ? 'greenBox text-dark' : ($grid['jo'] > 4 && $grid['ven'] > 4 && $third_row_lu > 30 ? 'border-success' : 'border-white')) }}">{{$grid['lu']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ven'] == 0 ? 'redBox' : ($grid['ven'] > 4 ? 'greenBox text-dark' : ($grid['lu'] > 4 && $grid['mer'] > 4 && $third_row_ven > 30 ? 'border-success' : 'border-white')) }}">{{$grid['ven']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['mer'] == 0 ? 'redBox' : ($grid['mer'] > 4 ? 'greenBox text-dark' : ($grid['ven'] > 4 && $grid['sa'] > 4 && $third_row_mer > 30 ? 'border-success' : 'border-white')) }}">{{$grid['mer']}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['so'] == 0 ? 'redBox' : ($grid['so'] > 4 ? 'greenBox' : '') }}">{{$grid['so']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$grid['sa'] + $grid['ma'] + $grid['jo'] + $grid['lu'] + $grid['ven'] + $grid['mer'] + $grid['so']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['sa'] == 0 ? 'redBox' : ($grid['sa'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['mer'] > 4 && $third_row_sa > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_sa}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ma'] == 0 ? 'redBox' : ($grid['ma'] > 4 ? 'greenBox text-dark' : ($grid['sa'] > 4 && $grid['jo'] > 4 && $third_row_ma > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_ma}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['jo'] == 0 ? 'redBox' : ($grid['jo'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['lu'] > 4 && $third_row_jo > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_jo}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['lu'] == 0 ? 'redBox' : ($grid['lu'] > 4 ? 'greenBox text-dark' : ($grid['jo'] > 4 && $grid['ven'] > 4 && $third_row_lu > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_lu}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ven'] == 0 ? 'redBox' : ($grid['ven'] > 4 ? 'greenBox text-dark' : ($grid['lu'] > 4 && $grid['mer'] > 4 && $third_row_ven > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_ven}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['mer'] == 0 ? 'redBox' : ($grid['mer'] > 4 ? 'greenBox text-dark' : ($grid['ven'] > 4 && $grid['sa'] > 4 && $third_row_mer > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_mer}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['so'] == 0 ? 'redBox' : ($grid['so'] > 4 ? 'greenBox' : '') }}">
                                    0
                                </td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_sa + $second_row_ma + $second_row_jo + $second_row_lu + $second_row_ven + $second_row_mer}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['sa'] == 0 ? 'redBox' : ($grid['sa'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['mer'] > 4 && $third_row_sa > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_sa}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ma'] == 0 ? 'redBox' : ($grid['ma'] > 4 ? 'greenBox text-dark' : ($grid['sa'] > 4 && $grid['jo'] > 4 && $third_row_ma > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_ma}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['jo'] == 0 ? 'redBox' : ($grid['jo'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['lu'] > 4 && $third_row_jo > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_jo}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['lu'] == 0 ? 'redBox' : ($grid['lu'] > 4 ? 'greenBox text-dark' : ($grid['jo'] > 4 && $grid['ven'] > 4 && $third_row_lu > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_lu}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['ven'] == 0 ? 'redBox' : ($grid['ven'] > 4 ? 'greenBox text-dark' : ($grid['lu'] > 4 && $grid['mer'] > 4 && $third_row_ven > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_ven}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['mer'] == 0 ? 'redBox' : ($grid['mer'] > 4 ? 'greenBox text-dark' : ($grid['ven'] > 4 && $grid['sa'] > 4 && $third_row_mer > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_mer}}</td>
                                <td class="text-sm font-weight-normal text-center border {{ $grid['so'] == 0 ? 'redBox' : ($grid['so'] > 4 ? 'greenBox' : '') }}">{{$third_row_so}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_sa + $third_row_ma + $third_row_jo + $third_row_lu + $third_row_ven + $third_row_mer + $third_row_so}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card left-nav-blue-light-color">
                    <div class="table-responsive">
                        <table class="table table-flush" style="border-collapse: separate">
                            <thead class="thead-light">
                            <tr>
                                <th class="text-center border border-white" id="feature_de"
                                    onmousemove="changeColorFeatureDE()" onmouseout="clearColorFeatureDE()">DE
                                </th>
                                <th class="text-center border border-white" id="feature_dom"
                                    onmousemove="changeColorFeatureDOM()" onmouseout="clearColorFeatureDOM()">DOM
                                </th>
                                <th class="text-center border border-white" id="feature_fe"
                                    onmousemove="changeColorFeatureFE()" onmouseout="clearColorFeatureFE()">FE
                                </th>
                                <th class="text-center border border-white" id="feature_gre"
                                    onmousemove="changeColorFeatureGRE()" onmouseout="clearColorFeatureGRE()">GRE
                                </th>
                                <th class="text-center border border-white" id="feature_lun"
                                    onmousemove="changeColorFeatureLUN()" onmouseout="clearColorFeatureLUN()">LUN
                                </th>
                                <th class="text-center border border-white" id="feature_nai"
                                    onmousemove="changeColorFeatureNAI()" onmouseout="clearColorFeatureNAI()">NAI
                                </th>
                                <th class="text-center border border-white" id="feature_ne"
                                    onmousemove="changeColorFeatureNE()" onmouseout="clearColorFeatureNE()">NE
                                </th>
                                <th class="text-center border border-white" id="feature_pow"
                                    onmousemove="changeColorFeaturePOW()" onmouseout="clearColorFeaturePOW()">POW
                                </th>
                                <th class="text-center border border-white" id="feature_sp"
                                    onmousemove="changeColorFeatureSP()" onmouseout="clearColorFeatureSP()">SP
                                </th>
                                <th class="text-center border border-white" id="feature_tra"
                                    onmousemove="changeColorFeatureTRA()" onmouseout="clearColorFeatureTRA()">TRA
                                </th>
                                <th class="text-center border border-white" id="feature_van"
                                    onmousemove="changeColorFeatureVAN()" onmouseout="clearColorFeatureVAN()">VAN
                                </th>
                                <th class="text-center border border-white" id="feature_wil"
                                    onmousemove="changeColorFeatureWIL()" onmouseout="clearColorFeatureWIL()">WIL
                                </th>
                                <th class="text-center border border-white">#</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('de', $topTwoKeys)) greenBox @elseif(in_array('de', $nextTwoKeys)) lightGreenBox @elseif(in_array('de', $redKeys)) redBox @endif">{{$de}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('dom', $topTwoKeys)) greenBox @elseif(in_array('dom', $nextTwoKeys)) lightGreenBox @elseif(in_array('dom', $redKeys)) redBox  @endif">{{$dom}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('fe', $topTwoKeys)) greenBox @elseif(in_array('fe', $nextTwoKeys)) lightGreenBox @elseif(in_array('fe', $redKeys)) redBox @endif">{{$fe}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('gre', $topTwoKeys)) greenBox @elseif(in_array('gre', $nextTwoKeys)) lightGreenBox @elseif(in_array('gre', $redKeys)) redBox @endif">{{$gre}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('lun', $topTwoKeys)) greenBox @elseif(in_array('lun', $nextTwoKeys)) lightGreenBox @elseif(in_array('lun', $redKeys)) redBox @endif">{{$lun}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('nai', $topTwoKeys)) greenBox @elseif(in_array('nai', $nextTwoKeys)) lightGreenBox @elseif(in_array('nai', $redKeys)) redBox @endif">{{$nai}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('ne', $topTwoKeys)) greenBox @elseif(in_array('ne', $nextTwoKeys)) lightGreenBox @elseif(in_array('ne', $redKeys)) redBox  @endif">{{$ne}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('pow', $topTwoKeys)) greenBox @elseif(in_array('pow', $nextTwoKeys)) lightGreenBox @elseif(in_array('pow', $redKeys)) redBox @endif">{{$pow}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('sp', $topTwoKeys)) greenBox @elseif(in_array('sp', $nextTwoKeys)) lightGreenBox @elseif(in_array('sp', $redKeys)) redBox @endif">{{$sp}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('tra', $topTwoKeys)) greenBox @elseif(in_array('tra', $nextTwoKeys)) lightGreenBox @elseif(in_array('tra', $redKeys)) redBox @endif">{{$tra}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('van', $topTwoKeys)) greenBox @elseif(in_array('van', $nextTwoKeys)) lightGreenBox @elseif(in_array('van', $redKeys)) redBox @endif">{{$van}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('wil', $topTwoKeys)) greenBox @elseif(in_array('wil', $nextTwoKeys)) lightGreenBox @elseif(in_array('wil', $redKeys)) redBox @endif">{{$wil}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$result}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('de', $topTwoKeys)) greenBox @elseif(in_array('de', $nextTwoKeys)) lightGreenBox @elseif(in_array('de', $redKeys)) redBox @endif">{{$second_row_de}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('dom', $topTwoKeys)) greenBox @elseif(in_array('dom', $nextTwoKeys)) lightGreenBox @elseif(in_array('dom', $redKeys)) redBox  @endif">{{$second_row_dom}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('fe', $topTwoKeys)) greenBox @elseif(in_array('fe', $nextTwoKeys)) lightGreenBox @elseif(in_array('fe', $redKeys)) redBox @endif">{{$second_row_fe}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('gre', $topTwoKeys)) greenBox @elseif(in_array('gre', $nextTwoKeys)) lightGreenBox @elseif(in_array('gre', $redKeys)) redBox @endif">{{$second_row_gre}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('lun', $topTwoKeys)) greenBox @elseif(in_array('lun', $nextTwoKeys)) lightGreenBox @elseif(in_array('lun', $redKeys)) redBox @endif">{{$second_row_lun}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('nai', $topTwoKeys)) greenBox @elseif(in_array('nai', $nextTwoKeys)) lightGreenBox @elseif(in_array('nai', $redKeys)) redBox @endif">{{$second_row_nai}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('ne', $topTwoKeys)) greenBox @elseif(in_array('ne', $nextTwoKeys)) lightGreenBox @elseif(in_array('ne', $redKeys)) redBox  @endif">{{$second_row_ne}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('pow', $topTwoKeys)) greenBox @elseif(in_array('pow', $nextTwoKeys)) lightGreenBox @elseif(in_array('pow', $redKeys)) redBox @endif">{{$second_row_pow}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('sp', $topTwoKeys)) greenBox @elseif(in_array('sp', $nextTwoKeys)) lightGreenBox @elseif(in_array('sp', $redKeys)) redBox @endif">{{$second_row_sp}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('tra', $topTwoKeys)) greenBox @elseif(in_array('tra', $nextTwoKeys)) lightGreenBox @elseif(in_array('tra', $redKeys)) redBox @endif">{{$second_row_tra}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('van', $topTwoKeys)) greenBox @elseif(in_array('van', $nextTwoKeys)) lightGreenBox @elseif(in_array('van', $redKeys)) redBox @endif">{{$second_row_van}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('wil', $topTwoKeys)) greenBox @elseif(in_array('wil', $nextTwoKeys)) lightGreenBox @elseif(in_array('wil', $redKeys)) redBox @endif">{{$second_row_wil}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$second_row_result}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('de', $topTwoKeys)) greenBox @elseif(in_array('de', $nextTwoKeys)) lightGreenBox @elseif(in_array('de', $redKeys)) redBox @endif">{{$third_row_de}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('dom', $topTwoKeys)) greenBox @elseif(in_array('dom', $nextTwoKeys)) lightGreenBox @elseif(in_array('dom', $redKeys)) redBox  @endif">{{$third_row_dom}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('fe', $topTwoKeys)) greenBox @elseif(in_array('fe', $nextTwoKeys)) lightGreenBox @elseif(in_array('fe', $redKeys)) redBox @endif">{{$third_row_fe}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('gre', $topTwoKeys)) greenBox @elseif(in_array('gre', $nextTwoKeys)) lightGreenBox @elseif(in_array('gre', $redKeys)) redBox @endif">{{$third_row_gre}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('lun', $topTwoKeys)) greenBox @elseif(in_array('lun', $nextTwoKeys)) lightGreenBox @elseif(in_array('lun', $redKeys)) redBox @endif">{{$third_row_lun}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('nai', $topTwoKeys)) greenBox @elseif(in_array('nai', $nextTwoKeys)) lightGreenBox @elseif(in_array('nai', $redKeys)) redBox @endif">{{$third_row_nai}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('ne', $topTwoKeys)) greenBox @elseif(in_array('ne', $nextTwoKeys)) lightGreenBox @elseif(in_array('ne', $redKeys)) redBox  @endif">{{$third_row_ne}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('pow', $topTwoKeys)) greenBox @elseif(in_array('pow', $nextTwoKeys)) lightGreenBox @elseif(in_array('pow', $redKeys)) redBox @endif">{{$third_row_pow}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('sp', $topTwoKeys)) greenBox @elseif(in_array('sp', $nextTwoKeys)) lightGreenBox @elseif(in_array('sp', $redKeys)) redBox @endif">{{$third_row_sp}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('tra', $topTwoKeys)) greenBox @elseif(in_array('tra', $nextTwoKeys)) lightGreenBox @elseif(in_array('tra', $redKeys)) redBox @endif">{{$third_row_tra}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('van', $topTwoKeys)) greenBox @elseif(in_array('van', $nextTwoKeys)) lightGreenBox @elseif(in_array('van', $redKeys)) redBox @endif">{{$third_row_van}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white @if(in_array('wil', $topTwoKeys)) greenBox @elseif(in_array('wil', $nextTwoKeys)) lightGreenBox @elseif(in_array('wil', $redKeys)) redBox @endif">{{$third_row_wil}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white">{{$third_row_result}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-5">
                <div class="card left-nav-blue-light-color">
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
            <div class="col-12 col-md-5">
                <div class="card left-nav-blue-light-color">
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
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['em'] < 7 || $grid['em'] > 12 ? 'redBox' : ''}}">{{$grid['em']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['ins'] < 7 || $grid['ins'] > 12 ? 'redBox' : ''}}">{{$grid['ins']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['int'] < 7 || $grid['int'] > 12 ? 'redBox' : ''}}">{{$grid['int']}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{$grid['mov'] < 7 || $grid['mov'] > 12 ? 'redBox' : ''}}">{{$grid['mov']}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_em ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_em}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_ins ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_ins}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_int ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_int}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{ $second_row_mov ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_mov}}</td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_em > 29 && $third_row_em < 301 && $third_row_em == $communication_third_style) ? 'greenBox' : (($third_row_em < 30 || $third_row_em > 300) ? 'redBox' : '') }}">{{$third_row_em}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_ins > 29 && $third_row_ins < 301 && $third_row_ins == $communication_third_style) ? 'greenBox' : (($third_row_ins < 30 || $third_row_ins > 300) ? 'redBox' : '') }}">{{$third_row_ins}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_int > 29 && $third_row_int < 301 && $third_row_int == $communication_third_style) ? 'greenBox' : (($third_row_int < 30 || $third_row_int > 300) ? 'redBox' : '') }}">{{$third_row_int}}</td>
                                <td class="text-sm font-weight-normal text-center border border-white {{($third_row_mov > 29 && $third_row_mov < 301 && $third_row_mov == $communication_third_style) ? 'greenBox' : (($third_row_mov < 30 || $third_row_mov > 300) ? 'redBox' : '') }}">{{$third_row_mov}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-5">
                <div class="card left-nav-blue-light-color">
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

