<div wire:ignore.self class="modal fade" id="editQueryModal{{ $queryId }}" tabindex="-1" role="dialog"
         aria-labelledby="editQueryModal{{ $queryId }}" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body" style=" border-radius: 9px">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label fs-4 text-white">Query Answer</label>
                                <button type="button" class="close modal-close-btn" data-bs-dismiss="modal"
                                        aria-label="Close" id="close-query-edit-modal-{{$queryId}}">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                @include('layouts.message')
                    <form wire:submit.prevent="updateAndApproveAnswer">
                        @csrf
                            <div class="form-group mt-2">
                                    <label class="form-label fs-6 text-white">Client Query:</label>
                                    <span
                                        style="color: #f2661c;font-size: 20px;font-weight: 800;display: flex;">{{$question['query'] ?? null}}</span>
                                    <label class="form-label fs-4 text-white">Answer:</label>
                                    <span class="copy-text float-end" >
                                       <!-- Copy text link -->
                                        <a class="btn-sm text-white px-3"  style="background-color: #f2661c;" onclick="copyToClipboard(`{{$answer}}`,`{{$queryId}}`, this)"><strong id="copy-text{{$queryId}}">Copy</strong></a>

                                  </span>
                                    <br>
                                    <span class="mt-2">{{$answer ?? null}}</span>
                                    <br>
                                    <label class="form-label fs-6 text-white mt-4">Update Answer:</label>
                                    <div class="form-group">
                                        <textarea rows="4" class="form-control text-white mt-2"
                                                  style="background-color: #0f1535"
                                                  wire:model.defer="updatedAnswer"
                                                  placeholder="update answer">
                                        </textarea>
                                    </div>
                            </div>
                            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update
                            </button>
                    </form>
                </div>
            </div>

                        @if(!empty($grid))
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
                                            elseif (($grid['gre'] > 2 && $grid['jo'] < 6 && $grid['mer'] < 5) && ($grid['gre'] > 2 && $grid['ma'] < 5 && $grid['lu'] < 5) && ($grid['gre'] > 2 && $grid['ven'] < 5 && $grid['so'] < 5)) {
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
                                <div class="d-flex">
                                    <div class="text-white box-grid-size" id="style_sa" onmousemove="changeColorStyleSA()"
                                         onmouseout="clearColorStyleSA()">SA
                                    </div>
                                    <div class="text-white box-grid-size" id="style_ma" onmousemove="changeColorStyleMA()"
                                         onmouseout="clearColorStyleMA()">MA
                                    </div>
                                    <div class="text-white box-grid-size" id="style_jo" onmousemove="changeColorStyleJO()"
                                         onmouseout="clearColorStyleJO()">JO
                                    </div>
                                    <div class="text-white box-grid-size" id="style_lu" onmousemove="changeColorStyleLU()"
                                         onmouseout="clearColorStyleLU()">LU
                                    </div>
                                    <div class="text-white box-grid-size" id="style_ven" onmousemove="changeColorStyleVEN()"
                                         onmouseout="clearColorStyleVEN()">VEN
                                    </div>
                                    <div class="text-white box-grid-size" id="style_mer" onmousemove="changeColorStyleMER()"
                                         onmouseout="clearColorStyleMER()">MER
                                    </div>
                                    <div class="text-white box-grid-size" id="style_so" onmousemove="changeColorStyleSO()"
                                         onmouseout="clearColorStyleSO()">SO
                                    </div>
                                    <div class="text-white box-grid-size">#</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size {{ $grid['sa'] == 0 ? 'redBox' : ($grid['sa'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['mer'] > 4 && $third_row_sa > 30? 'border-success' : 'border-white')) }}">{{$grid['sa']}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['ma'] == 0 ? 'redBox' : ($grid['ma'] > 4 ? 'greenBox text-dark' : ($grid['sa'] > 4 && $grid['jo'] > 4 && $third_row_ma > 30 ? 'border-success' : 'border-white')) }}">{{$grid['ma']}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['jo'] == 0 ? 'redBox' : ($grid['jo'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['lu'] > 4 && $third_row_jo > 30 ? 'border-success' : 'border-white')) }}">{{$grid['jo']}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['lu'] == 0 ? 'redBox' : ($grid['lu'] > 4 ? 'greenBox text-dark' : ($grid['jo'] > 4 && $grid['ven'] > 4 && $third_row_lu > 30 ? 'border-success' : 'border-white')) }}">{{$grid['lu']}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['ven'] == 0 ? 'redBox' : ($grid['ven'] > 4 ? 'greenBox text-dark' : ($grid['lu'] > 4 && $grid['mer'] > 4 && $third_row_ven > 30 ? 'border-success' : 'border-white')) }}">{{$grid['ven']}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['mer'] == 0 ? 'redBox' : ($grid['mer'] > 4 ? 'greenBox text-dark' : ($grid['ven'] > 4 && $grid['sa'] > 4 && $third_row_mer > 30 ? 'border-success' : 'border-white')) }}">{{$grid['mer']}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['so'] == 0 ? 'redBox' : ($grid['so'] > 4 ? 'greenBox' : '') }}">{{$grid['so']}}</div>
                                    <div
                                        class="text-white box-grid-size">{{$grid['sa'] + $grid['ma'] + $grid['jo'] + $grid['lu'] + $grid['ven'] + $grid['mer'] + $grid['so']}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size {{ $grid['sa'] == 0 ? 'redBox' : ($grid['sa'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['mer'] > 4 && $third_row_sa > 30? 'border-success' : 'border-white')) }}">{{$second_row_sa}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['ma'] == 0 ? 'redBox' : ($grid['ma'] > 4 ? 'greenBox text-dark' : ($grid['sa'] > 4 && $grid['jo'] > 4 && $third_row_ma > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_ma}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['jo'] == 0 ? 'redBox' : ($grid['jo'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['lu'] > 4 && $third_row_jo > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_jo}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['lu'] == 0 ? 'redBox' : ($grid['lu'] > 4 ? 'greenBox text-dark' : ($grid['jo'] > 4 && $grid['ven'] > 4 && $third_row_lu > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_lu}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['ven'] == 0 ? 'redBox' : ($grid['ven'] > 4 ? 'greenBox text-dark' : ($grid['lu'] > 4 && $grid['mer'] > 4 && $third_row_ven > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_ven}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['mer'] == 0 ? 'redBox' : ($grid['mer'] > 4 ? 'greenBox text-dark' : ($grid['ven'] > 4 && $grid['sa'] > 4 && $third_row_mer > 30 ? 'border-success' : 'border-white')) }}">{{$second_row_mer}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['so'] == 0 ? 'redBox' : ($grid['so'] > 4 ? 'greenBox' : '') }}">
                                        0
                                    </div>
                                    <div
                                        class="text-white box-grid-size">{{$second_row_sa + $second_row_ma + $second_row_jo + $second_row_lu + $second_row_ven + $second_row_mer}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size {{ $grid['sa'] == 0 ? 'redBox' : ($grid['sa'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['mer'] > 4 && $third_row_sa > 30? 'border-success' : 'border-white')) }}">{{$third_row_sa}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['ma'] == 0 ? 'redBox' : ($grid['ma'] > 4 ? 'greenBox text-dark' : ($grid['sa'] > 4 && $grid['jo'] > 4 && $third_row_ma > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_ma}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['jo'] == 0 ? 'redBox' : ($grid['jo'] > 4 ? 'greenBox text-dark' : ($grid['ma'] > 4 && $grid['lu'] > 4 && $third_row_jo > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_jo}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['lu'] == 0 ? 'redBox' : ($grid['lu'] > 4 ? 'greenBox text-dark' : ($grid['jo'] > 4 && $grid['ven'] > 4 && $third_row_lu > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_lu}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['ven'] == 0 ? 'redBox' : ($grid['ven'] > 4 ? 'greenBox text-dark' : ($grid['lu'] > 4 && $grid['mer'] > 4 && $third_row_ven > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_ven}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['mer'] == 0 ? 'redBox' : ($grid['mer'] > 4 ? 'greenBox text-dark' : ($grid['ven'] > 4 && $grid['sa'] > 4 && $third_row_mer > 30 ? 'border-success' : 'border-white')) }}">{{$third_row_mer}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $grid['so'] == 0 ? 'redBox' : ($grid['so'] > 4 ? 'greenBox' : '') }}">
                                        0
                                    </div>
                                    <div
                                        class="text-white box-grid-size">{{$third_row_sa + $third_row_ma + $third_row_jo + $third_row_lu + $third_row_ven + $third_row_mer + $third_row_so}}</div>
                                </div>
                            </div>
                            <div class="row mt-4" style="overflow: overlay;">
                                <div class="d-flex">
                                    <div class="text-white box-grid-size" id="feature_de"
                                         onmousemove="changeColorFeatureDE()" onmouseout="clearColorFeatureDE()">DE
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_dom"
                                         onmousemove="changeColorFeatureDOM()" onmouseout="clearColorFeatureDOM()">DOM
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_fe"
                                         onmousemove="changeColorFeatureFE()" onmouseout="clearColorFeatureFE()">FE
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_gre"
                                         onmousemove="changeColorFeatureGRE()" onmouseout="clearColorFeatureGRE()">GRE
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_lun"
                                         onmousemove="changeColorFeatureLUN()" onmouseout="clearColorFeatureLUN()">LUN
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_nai"
                                         onmousemove="changeColorFeatureNAI()" onmouseout="clearColorFeatureNAI()">NAI
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_ne"
                                         onmousemove="changeColorFeatureNE()" onmouseout="clearColorFeatureNE()">NE
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_pow"
                                         onmousemove="changeColorFeaturePOW()" onmouseout="clearColorFeaturePOW()">POW
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_sp"
                                         onmousemove="changeColorFeatureSP()" onmouseout="clearColorFeatureSP()">SP
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_tra"
                                         onmousemove="changeColorFeatureTRA()" onmouseout="clearColorFeatureTRA()">TRA
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_van"
                                         onmousemove="changeColorFeatureVAN()" onmouseout="clearColorFeatureVAN()">VAN
                                    </div>
                                    <div class="text-white box-grid-size" id="feature_wil"
                                         onmousemove="changeColorFeatureWIL()" onmouseout="clearColorFeatureWIL()">WIL
                                    </div>
                                    <div class="text-white box-grid-size">#</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size @if(in_array('de', $topTwoKeys)) greenBox @elseif(in_array('de', $nextTwoKeys)) lightGreenBox @elseif(in_array('de', $redKeys)) redBox @endif">{{$de}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('dom', $topTwoKeys)) greenBox @elseif(in_array('dom', $nextTwoKeys)) lightGreenBox @elseif(in_array('dom', $redKeys)) redBox  @endif">{{$dom}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('fe', $topTwoKeys)) greenBox @elseif(in_array('fe', $nextTwoKeys)) lightGreenBox @elseif(in_array('fe', $redKeys)) redBox @endif">{{$fe}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('gre', $topTwoKeys)) greenBox @elseif(in_array('gre', $nextTwoKeys)) lightGreenBox @elseif(in_array('gre', $redKeys)) redBox @endif">{{$gre}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('lun', $topTwoKeys)) greenBox @elseif(in_array('lun', $nextTwoKeys)) lightGreenBox @elseif(in_array('lun', $redKeys)) redBox @endif">{{$lun}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('nai', $topTwoKeys)) greenBox @elseif(in_array('nai', $nextTwoKeys)) lightGreenBox @elseif(in_array('nai', $redKeys)) redBox @endif">{{$nai}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('ne', $topTwoKeys)) greenBox @elseif(in_array('ne', $nextTwoKeys)) lightGreenBox @elseif(in_array('ne', $redKeys)) redBox  @endif">{{$ne}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('pow', $topTwoKeys)) greenBox @elseif(in_array('pow', $nextTwoKeys)) lightGreenBox @elseif(in_array('pow', $redKeys)) redBox @endif">{{$pow}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('sp', $topTwoKeys)) greenBox @elseif(in_array('sp', $nextTwoKeys)) lightGreenBox @elseif(in_array('sp', $redKeys)) redBox @endif">{{$sp}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('tra', $topTwoKeys)) greenBox @elseif(in_array('tra', $nextTwoKeys)) lightGreenBox @elseif(in_array('tra', $redKeys)) redBox @endif">{{$tra}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('van', $topTwoKeys)) greenBox @elseif(in_array('van', $nextTwoKeys)) lightGreenBox @elseif(in_array('van', $redKeys)) redBox @endif">{{$van}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('wil', $topTwoKeys)) greenBox @elseif(in_array('wil', $nextTwoKeys)) lightGreenBox @elseif(in_array('wil', $redKeys)) redBox @endif">{{$wil}}</div>
                                    <div class="text-white box-grid-size">{{$result}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size @if(in_array('de', $topTwoKeys)) greenBox @elseif(in_array('de', $nextTwoKeys)) lightGreenBox @elseif(in_array('de', $redKeys)) redBox @endif">{{$second_row_de}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('dom', $topTwoKeys)) greenBox @elseif(in_array('dom', $nextTwoKeys)) lightGreenBox @elseif(in_array('dom', $redKeys)) redBox  @endif">{{$second_row_dom}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('fe', $topTwoKeys)) greenBox @elseif(in_array('fe', $nextTwoKeys)) lightGreenBox @elseif(in_array('fe', $redKeys)) redBox @endif">{{$second_row_fe}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('gre', $topTwoKeys)) greenBox @elseif(in_array('gre', $nextTwoKeys)) lightGreenBox @elseif(in_array('gre', $redKeys)) redBox @endif">{{$second_row_gre}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('lun', $topTwoKeys)) greenBox @elseif(in_array('lun', $nextTwoKeys)) lightGreenBox @elseif(in_array('lun', $redKeys)) redBox @endif">{{$second_row_lun}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('nai', $topTwoKeys)) greenBox @elseif(in_array('nai', $nextTwoKeys)) lightGreenBox @elseif(in_array('nai', $redKeys)) redBox @endif">{{$second_row_nai}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('ne', $topTwoKeys)) greenBox @elseif(in_array('ne', $nextTwoKeys)) lightGreenBox @elseif(in_array('ne', $redKeys)) redBox  @endif">{{$second_row_ne}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('pow', $topTwoKeys)) greenBox @elseif(in_array('pow', $nextTwoKeys)) lightGreenBox @elseif(in_array('pow', $redKeys)) redBox @endif">{{$second_row_pow}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('sp', $topTwoKeys)) greenBox @elseif(in_array('sp', $nextTwoKeys)) lightGreenBox @elseif(in_array('sp', $redKeys)) redBox @endif">{{$second_row_sp}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('tra', $topTwoKeys)) greenBox @elseif(in_array('tra', $nextTwoKeys)) lightGreenBox @elseif(in_array('tra', $redKeys)) redBox @endif">{{$second_row_tra}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('van', $topTwoKeys)) greenBox @elseif(in_array('van', $nextTwoKeys)) lightGreenBox @elseif(in_array('van', $redKeys)) redBox @endif">{{$second_row_van}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('wil', $topTwoKeys)) greenBox @elseif(in_array('wil', $nextTwoKeys)) lightGreenBox @elseif(in_array('wil', $redKeys)) redBox @endif">{{$second_row_wil}}</div>
                                    <div class="text-white box-grid-size">{{$second_row_result}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size @if(in_array('de', $topTwoKeys)) greenBox @elseif(in_array('de', $nextTwoKeys)) lightGreenBox @elseif(in_array('de', $redKeys)) redBox @endif">{{$third_row_de}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('dom', $topTwoKeys)) greenBox @elseif(in_array('dom', $nextTwoKeys)) lightGreenBox @elseif(in_array('dom', $redKeys)) redBox  @endif">{{$third_row_dom}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('fe', $topTwoKeys)) greenBox @elseif(in_array('fe', $nextTwoKeys)) lightGreenBox @elseif(in_array('fe', $redKeys)) redBox @endif">{{$third_row_fe}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('gre', $topTwoKeys)) greenBox @elseif(in_array('gre', $nextTwoKeys)) lightGreenBox @elseif(in_array('gre', $redKeys)) redBox @endif">{{$third_row_gre}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('lun', $topTwoKeys)) greenBox @elseif(in_array('lun', $nextTwoKeys)) lightGreenBox @elseif(in_array('lun', $redKeys)) redBox @endif">{{$third_row_lun}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('nai', $topTwoKeys)) greenBox @elseif(in_array('nai', $nextTwoKeys)) lightGreenBox @elseif(in_array('nai', $redKeys)) redBox @endif">{{$third_row_nai}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('ne', $topTwoKeys)) greenBox @elseif(in_array('ne', $nextTwoKeys)) lightGreenBox @elseif(in_array('ne', $redKeys)) redBox  @endif">{{$third_row_ne}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('pow', $topTwoKeys)) greenBox @elseif(in_array('pow', $nextTwoKeys)) lightGreenBox @elseif(in_array('pow', $redKeys)) redBox @endif">{{$third_row_pow}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('sp', $topTwoKeys)) greenBox @elseif(in_array('sp', $nextTwoKeys)) lightGreenBox @elseif(in_array('sp', $redKeys)) redBox @endif">{{$third_row_sp}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('tra', $topTwoKeys)) greenBox @elseif(in_array('tra', $nextTwoKeys)) lightGreenBox @elseif(in_array('tra', $redKeys)) redBox @endif">{{$third_row_tra}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('van', $topTwoKeys)) greenBox @elseif(in_array('van', $nextTwoKeys)) lightGreenBox @elseif(in_array('van', $redKeys)) redBox @endif">{{$third_row_van}}</div>
                                    <div
                                        class="text-white box-grid-size @if(in_array('wil', $topTwoKeys)) greenBox @elseif(in_array('wil', $nextTwoKeys)) lightGreenBox @elseif(in_array('wil', $redKeys)) redBox @endif">{{$third_row_wil}}</div>
                                    <div class="text-white box-grid-size">{{$third_row_result}}</div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="d-flex">
                                    <div class="text-white box-grid-size">G</div>
                                    <div class="text-white box-grid-size">S</div>
                                    <div class="text-white box-grid-size">C</div>
                                </div>
                                <div class="d-flex">
                                    <div class="text-white box-grid-size ">{{$gold = $grid['g']}}</div>
                                    <div class="text-white box-grid-size ">{{$silver = $grid['s']}}</div>
                                    <div class="text-white box-grid-size ">{{$copper = $grid['c']}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size ">{{$second_row_gold = $grid['mer'] + $grid['sa'] + $grid['so']}}</div>
                                    <div
                                        class="text-white box-grid-size ">{{$second_row_silver = $grid['ven'] + $grid['jo']}}</div>
                                    <div
                                        class="text-white box-grid-size ">{{$second_row_copper = $grid['ma'] + $grid['lu']}}</div>
                                </div>
                                <div class="d-flex">
                                    <div class="text-white box-grid-size ">{{$gold * $second_row_gold}}</div>
                                    <div class="text-white box-grid-size ">{{$silver * $second_row_silver}}</div>
                                    <div class="text-white box-grid-size ">{{$copper * $second_row_copper}}</div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="d-flex">
                                    <div class="text-white box-grid-size">EM</div>
                                    <div class="text-white box-grid-size">INS</div>
                                    <div class="text-white box-grid-size">INT</div>
                                    <div class="text-white box-grid-size">MOV</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size {{$grid['em'] < 7 || $grid['em'] > 12 ? 'redBox' : ''}}">{{$grid['em']}}</div>
                                    <div
                                        class="text-white box-grid-size {{$grid['ins'] < 7 || $grid['ins'] > 12 ? 'redBox' : ''}}">{{$grid['ins']}}</div>
                                    <div
                                        class="text-white box-grid-size {{$grid['int'] < 7 || $grid['int'] > 12 ? 'redBox' : ''}}">{{$grid['int']}}</div>
                                    <div
                                        class="text-white box-grid-size {{$grid['mov'] < 7 || $grid['mov'] > 12 ? 'redBox' : ''}}">{{$grid['mov']}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size {{ $second_row_em ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_em}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $second_row_ins ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_ins}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $second_row_int ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_int}}</div>
                                    <div
                                        class="text-white box-grid-size {{ $second_row_mov ==  $communication_style ? 'greenBox' : ''}}">{{$second_row_mov}}</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size {{($third_row_em > 29 && $third_row_em < 301 && $third_row_em == $communication_third_style) ? 'greenBox' : (($third_row_em < 30 || $third_row_em > 300) ? 'redBox' : '') }}">{{$third_row_em}}</div>
                                    <div
                                        class="text-white box-grid-size {{($third_row_ins > 29 && $third_row_ins < 301 && $third_row_ins == $communication_third_style) ? 'greenBox' : (($third_row_ins < 30 || $third_row_ins > 300) ? 'redBox' : '') }}">{{$third_row_ins}}</div>
                                    <div
                                        class="text-white box-grid-size {{($third_row_int > 29 && $third_row_int < 301 && $third_row_int == $communication_third_style) ? 'greenBox' : (($third_row_int < 30 || $third_row_int > 300) ? 'redBox' : '') }}">{{$third_row_int}}</div>
                                    <div
                                        class="text-white box-grid-size {{($third_row_mov > 29 && $third_row_mov < 301 && $third_row_mov == $communication_third_style) ? 'greenBox' : (($third_row_mov < 30 || $third_row_mov > 300) ? 'redBox' : '') }}">{{$third_row_mov}}</div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="d-flex">
                                    <div class="text-white box-grid-size">+</div>
                                    <div class="text-white box-grid-size">-</div>
                                    <div class="text-white box-grid-size">PV</div>
                                    <div class="text-white box-grid-size">EP</div>
                                </div>
                                <div class="d-flex">
                                    <div
                                        class="text-white box-grid-size ">{{$positive = $grid['sa'] + $grid['jo'] + $grid['ven'] + $grid['so']}}</div>
                                    <div
                                        class="text-white box-grid-size ">{{$negative = $grid['ma'] + $grid['lu'] + $grid['mer']}}</div>
                                    <div class="text-white box-grid-size ">{{$positive - $negative}}</div>
                                    <div class="text-white box-grid-size ">{{$positive + $negative}}</div>
                                </div>
                            </div>
                        @endif
        </div>
    </div>

</div>
        </div>
    </div>


@push('javascript')
    <script>

        window.Livewire.on('closeEditQueryModal', function (e) {
            $('#close-query-edit-modal-' + e.id).click();
        });

        async function copyToClipboard(text,id,button) {
            try {
                // Use the Clipboard API to copy the text
                await navigator.clipboard.writeText(text);

                $('#copy-text'+id).text('Copied!')
                // Hide the tooltip after 2 seconds
                setTimeout(() => {
                    setTimeout(() => {
                        $('#copy-text'+id).text('Copy')
                    }, 300);  // Match the fade-out duration
                }, 2000);

            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        }

    </script>
@endpush
