<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin\Code\CodeDetail;
use App\Models\Admin\Alchemy\AlchemyCode;

class Assessment extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function createAssessment($data = null)
    {
        return self::create($data);
    }

    public static function updateAssessment($data = null, $id = null)
    {
        return self::find($id)->update($data);
    }

    public static function getLastPage()
    {
        $page = self::where('user_id', Auth::user()->id)->select(['page'])->latest()->first();
        if ($page) {
            return $page->page;
        } else {
            return 0;
        }
    }

    public static function getGrid($id = null)
    {
        return self::whereId($id)->where('user_id', Auth::user()['id'])->first();
    }

    public static function getReport($id = null)
    {
        $assessment =  self::whereId($id)->where('user_id', Auth::user()['id'])->first();

        $style = [
            'sa' => $assessment['sa'],
            'ma' => $assessment['ma'],
            'jo' => $assessment['jo'],
            'lu' => $assessment['lu'],
            'ven' => $assessment['ven'],
            'mer' => $assessment['mer'],
            'so' => $assessment['so'],
        ];

        $features = [
            'de' => $assessment['de'],
            'dom' => $assessment['dom'],
            'fe' => $assessment['fe'],
            'gre' => $assessment['gre'],
            'lun' => $assessment['lun'],
            'nai' => $assessment['nai'],
            'ne' => $assessment['ne'],
            'pow' => $assessment['pow'],
            'sp' => $assessment['sp'],
            'tra' => $assessment['tra'],
            'van' => $assessment['van'],
            'wil' => $assessment['wil'],
        ];

        $communications = [
            'em' => $assessment['em'],
            'ins' => $assessment['ins'],
            'int' => $assessment['int'],
            'mov' => $assessment['mov'],
        ];

        $second_row_em = $assessment['jo'] + $assessment['ven'] + $assessment['lu'];
        $second_row_ins = $assessment['ma'] + $assessment['ven'] + $assessment['mer'];
        $second_row_int = $assessment['jo'] + $assessment['sa'] + $assessment['mer'];
        $second_row_mov = $assessment['ma'] + $assessment['so'] + $assessment['mer'];

        $communication_third_row = [
            'em' => $assessment['em'] * $second_row_em,
            'ins' => $assessment['ins'] * $second_row_ins,
            'int' => $assessment['int'] * $second_row_int,
            'mov' => $assessment['mov'] * $second_row_mov,
        ];

        $gold = $assessment['g'];
        $silver = $assessment['s'];
        $copper = $assessment['c'];
        $alchemy = $gold.''.$silver.''.$copper;

        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];

        $second_row_de = $assessment['ma'];
        $second_row_dom = $assessment['sa'] + $assessment['ma'];
        $second_row_fe = $assessment['ma'] + $assessment['lu'] + $assessment['ven'];
        $second_row_gre = $assessment['mer'];
        $second_row_lun = $assessment['lu'];
        $second_row_nai = $assessment['so'];
        $second_row_ne = $assessment['sa'] + $assessment['lu'] + $assessment['ven'];
        $second_row_pow = $assessment['jo'] + $assessment['mer'];
        $second_row_sp = $assessment['jo'];
        $second_row_tra = $assessment['jo'] + $assessment['ven'];
        $second_row_van = $assessment['jo'] + $assessment['ven'] + $assessment['mer'] + $assessment['so'];
        $second_row_wil = $assessment['ma'] + $assessment['lu'];

        $third_row_sa = $assessment['sa'] * $second_row_sa;
        $third_row_ma = $assessment['ma'] * $second_row_ma;
        $third_row_jo = $assessment['jo'] * $second_row_jo;
        $third_row_lu = $assessment['lu'] * $second_row_lu;
        $third_row_ven = $assessment['ven'] * $second_row_ven;
        $third_row_mer = $assessment['mer'] * $second_row_mer;
        $third_row_so = 10 * $assessment['so'];

        $third_row_feature = [
            'de' => $assessment['de'] * $second_row_de,
            'dom' => $assessment['dom'] * $second_row_dom,
            'fe' => $assessment['fe'] * $second_row_fe,
            'gre' => $assessment['gre'] * ($assessment['jo'] + $assessment['mer']),
            'lun' => $assessment['lun'] * $second_row_lun,
            'nai' => $assessment['nai'] * $second_row_nai,
            'ne' => $assessment['ne'] * $second_row_ne,
            'pow' => $assessment['pow'] * $second_row_pow,
            'sp' => $assessment['sp'] * $second_row_sp,
            'tra' => $assessment['tra'] * $second_row_tra,
            'van' => $assessment['van'] * $second_row_van,
            'wil' => $assessment['wil'] * $second_row_wil,
        ];

        // Sort features in descending order while maintaining key associations
        arsort($features);

        // Filter keys based on conditions
        $filtered_keys = [];
        foreach ($features as $key => $value) {
            switch ($key) {
                case 'de':
                    if (($assessment['de'] > 2 && $assessment['ma'] > 4) || ($assessment['de'] > 2 && $third_row_ma > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'dom':
                    if (($assessment['dom'] > 2 && ($assessment['sa'] > 4 || $assessment['ma'] > 4)) || ($assessment['dom'] > 2 && $third_row_ma > 30 && $third_row_sa > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'fe':
                    if (($assessment['fe'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['fe'] > 2 && $third_row_ma > 30 && $third_row_lu > 30 && $third_row_ven > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'gre':
                    if (($assessment['gre'] > 2 && ($assessment['jo'] > 6)) || ($assessment['gre'] > 2 && $third_row_mer > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'lun':
                    if (($assessment['lun'] > 2 && $assessment['lu'] > 4) || ($assessment['lun'] > 2 && $third_row_lu > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'nai':
                    if (($assessment['nai'] > 2 && $assessment['so'] > 4) || ($assessment['nai'] > 2 && $third_row_so > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'ne':
                    if (($assessment['ne'] > 2 && ($assessment['sa'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['ne'] > 2 && $third_row_sa > 30 && $third_row_lu > 30 && $third_row_ven > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'pow':
                    if (($assessment['pow'] > 2 && ($assessment['jo'] > 4 || $assessment['mer'] > 4)) || ($assessment['pow'] > 2 && $third_row_jo > 30 && $third_row_mer > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'sp':
                    if ((($assessment['sp'] > 2 && $assessment['jo'] > 4) || ($assessment['sp'] > 2 && $third_row_jo > 30)) || ($assessment['sp'] > 2 && $third_row_jo > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'tra':
                    if (($assessment['tra'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4)) || ($assessment['tra'] > 2 && $third_row_jo > 30 && $third_row_ven > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'van':
                    if (($assessment['van'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4 || $assessment['mer'] > 4 || $assessment['so'] > 4)) || ($assessment['van'] > 2 && $third_row_jo > 30 && $third_row_ven > 30 && $third_row_mer > 30 && $third_row_so > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
                case 'wil':
                    if (($assessment['wil'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4)) || ($assessment['wil'] > 2 && $third_row_ma > 30 && $third_row_lu > 30)) {
                        $filtered_keys[$key] = $value;
                    }
                    break;
            }
        }

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

        }
        else {

            // Count the occurrences of each value
            $value_counts = array_count_values($filtered_keys);

            // Filter unique values
            $unique_filtered_keys = array_filter($filtered_keys, function($value) use ($value_counts) {
                return $value_counts[$value] === 1;
            });

            // Filter remaining values (including repeating ones)
            $remaining_keys = array_filter($filtered_keys, function($value) use ($value_counts) {
                return $value_counts[$value] > 1 || $value_counts[$value] === 1;
            });

            // Removing the unique values from the remaining_keys array
            $remaining_keys = array_filter($remaining_keys, function($value) use ($value_counts) {
                return $value_counts[$value] > 1;
            });

            // Find the highest and second highest values
            $values = array_values($remaining_keys);
            $highest_value = max($values);
            $second_highest_value = count(array_diff($values, [$highest_value])) ? max(array_diff($values, [$highest_value])) : null;

            // Separate arrays for highest and second-highest values
            $highest_array = [];
            $second_highest_array = [];

            foreach ($remaining_keys as $key => $value) {
                if ($value == $highest_value) {
                    $highest_array[$key] = $value;
                } elseif ($value == $second_highest_value) {
                    $second_highest_array[$key] = $value;
                }
            }

            $firstHighestArrayValue = array_intersect_key($third_row_feature, array_flip(array_keys($highest_array)));
            arsort($firstHighestArrayValue);
            $secondHighestArrayValue = array_intersect_key($third_row_feature, array_flip(array_keys($second_highest_array)));
            arsort($secondHighestArrayValue);

            $allValuesGets = array_merge($unique_filtered_keys, $firstHighestArrayValue, $secondHighestArrayValue);

            $topTwoKeysFeature = array_slice(array_keys($allValuesGets), 0, 2);

        }

        $highlightStyle = [];

        foreach ($style as $key => $value) {
            if ($value > 4) {
                $highlightStyle[$key] = $value;
            }
        }

        arsort($highlightStyle);

        $topTwoKeysStyle = array_slice(array_keys($highlightStyle), 0, 2);

        // Sort styles by value
        arsort($communications);

        $communication_array = array_filter($communications);

        // If values are the same, check $styles_third and get greater value if exists
        uksort($communication_array, function($a, $b) use ($communications, $communication_third_row) {
            if ($communications[$a] == $communications[$b]) {
                $a_third = isset($communication_third_row[$a]) ? $communication_third_row[$a] : -1;
                $b_third = isset($communication_third_row[$b]) ? $communication_third_row[$b] : -1;
                if ($a_third == $b_third) {
                    // If $styles_third values are the same, sort from right to left
                    return array_search($b, array_keys(array_reverse($communications))) - array_search($a, array_keys(array_reverse($communications)));
                }
                return $a_third < $b_third ? 1 : -1; // Compare $styles_third values
            }
            return $communications[$a] < $communications[$b] ? 1 : -1;
        });

        // Get keys of filtered styles
        $communication_keys = array_keys($communication_array);

        $positive = $assessment['sa'] + $assessment['jo'] + $assessment['ven'] + $assessment['so'];
        $negative = $assessment['ma'] + $assessment['lu'] + $assessment['mer'];
        $pv = $positive - $negative;

        if ($pv <= -8)
        {
            $polarity_code = 40;
        }
        elseif ($pv >= -7 and $pv <= 7)
        {
            $polarity_code = 41;
        }
        elseif ($pv >= 8)
        {
            $polarity_code = 42;
        }

        $ep = $positive + $negative;

        if ($ep < 25)
        {
            $energy_code = 21;
        }
        elseif ($ep >= 25 and $ep <= 30)
        {
            $energy_code = 18;
        }
        elseif ($ep >= 31 and $ep <= 35)
        {
            $energy_code = 20;
        }
        elseif ($ep >= 36)
        {
            $energy_code = 16;
        }

        $alchemyCodeDetail = AlchemyCode::getCodeDeatil($alchemy);
        $code_detail = CodeDetail::getCodeDeatil($topTwoKeysStyle, $topTwoKeysFeature, $alchemyCodeDetail, $communication_keys, $polarity_code, $energy_code, $pv, $ep);

        return $code_detail;
    }

    public static function getAssessment()
    {
        return static::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    }

    public static function allAssessment()
    {
        return self::with('user')->where('page', 0)->get();
    }

    public static function abandonedAssessment()
    {
        return self::with('user')->where('page','>', 0)->get();
    }

}
