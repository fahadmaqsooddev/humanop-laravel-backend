<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assessment;

class AssessmentColorCode extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // relations
    public function assessments()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id', 'id');
    }

    public static function createStylesCodeAndColor($assessment = null)
    {
        $styles = [
            'sa' => $assessment['sa'],
            'ma' => $assessment['ma'],
            'jo' => $assessment['jo'],
            'lu' => $assessment['lu'],
            'ven' => $assessment['ven'],
            'mer' => $assessment['mer'],
            'so' => $assessment['so'],
        ];

        // Calculate second row values
        $second_row_sa = $assessment['sa'] + $assessment['ma'] + $assessment['mer'];
        $second_row_ma = $assessment['sa'] + $assessment['ma'] + $assessment['jo'];
        $second_row_jo = $assessment['ma'] + $assessment['jo'] + $assessment['lu'];
        $second_row_lu = $assessment['jo'] + $assessment['lu'] + $assessment['ven'];
        $second_row_ven = $assessment['lu'] + $assessment['ven'] + $assessment['mer'];
        $second_row_mer = $assessment['ven'] + $assessment['mer'] + $assessment['sa'];

        // Calculate third row values
        $third_row_sa = $assessment['sa'] * $second_row_sa;
        $third_row_ma = $assessment['ma'] * $second_row_ma;
        $third_row_jo = $assessment['jo'] * $second_row_jo;
        $third_row_lu = $assessment['lu'] * $second_row_lu;
        $third_row_ven = $assessment['ven'] * $second_row_ven;
        $third_row_mer = $assessment['mer'] * $second_row_mer;
        $third_row_so = 10 * $assessment['so'];

        $style_greater_than_4 = [];
        $style_equal_to_0 = [];
        $style_border_green = [];

        // Separate the styles based on conditions
        foreach ($styles as $key => $style) {
            if ($style > 4) {
                $style_greater_than_4[$key] = $style;
            } elseif ($style == 0) {
                $style_equal_to_0[$key] = $style;
            }
        }

        // Check for styles that should have a green border
        foreach ($styles as $key => $style) {
            if ($style < 5) {
                switch ($key) {
                    case 'sa':
                        if ($assessment['ma'] > 4 && $assessment['mer'] > 4 && $third_row_sa > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                    case 'ma':
                        if ($assessment['sa'] > 4 && $assessment['jo'] > 4 && $third_row_ma > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                    case 'jo':
                        if ($assessment['ma'] > 4 && $assessment['lu'] > 4 && $third_row_jo > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                    case 'lu':
                        if ($assessment['jo'] > 4 && $assessment['ven'] > 4 && $third_row_lu > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                    case 'ven':
                        if ($assessment['lu'] > 4 && $assessment['mer'] > 4 && $third_row_ven > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                    case 'mer':
                        if ($assessment['ven'] > 2 && $assessment['sa'] > 4 && $third_row_mer > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                }
            }
        }

        // Create entries for styles greater than 4
        foreach ($style_greater_than_4 as $key => $style) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'green',
            ]);
        }

        // Create entries for styles equal to 0
        foreach ($style_equal_to_0 as $key => $style) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'red',
            ]);
        }

        // Create entries for styles border green
        foreach ($style_border_green as $key => $style) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'border-green',
            ]);
        }

    }

    public static function createFeaturesCodeAndColor($assessment = null)
    {
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

        // Calculate second row values
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

        // Calculate third row values
        $third_row_de = $assessment['de'] * $second_row_de;
        $third_row_dom = $assessment['dom'] * $second_row_dom;
        $third_row_fe = $assessment['fe'] * $second_row_fe;
        $third_row_gre = $assessment['gre'] * $second_row_gre;
        $third_row_lun = $assessment['lun'] * $second_row_lun;
        $third_row_nai = $assessment['nai'] * $second_row_nai;
        $third_row_ne = $assessment['ne'] * $second_row_ne;
        $third_row_pow = $assessment['pow'] * $second_row_pow;
        $third_row_sp = $assessment['sp'] * $second_row_sp;
        $third_row_tra = $assessment['tra'] * $second_row_tra;
        $third_row_van = $assessment['van'] * $second_row_van;
        $third_row_wil = $assessment['wil'] * $second_row_wil;

        $third_row_feature = [
            'de' => $third_row_de,
            'dom' => $third_row_dom,
            'fe' => $third_row_fe,
            'gre' => $assessment['gre'] * ($assessment['jo'] + $assessment['mer']),
            'lun' => $third_row_lun,
            'nai' => $third_row_nai,
            'ne' => $third_row_ne,
            'pow' => $third_row_pow,
            'sp' => $third_row_sp,
            'tra' => $third_row_tra,
            'van' => $third_row_van,
            'wil' => $third_row_wil,
        ];

        // Sort features in descending order while maintaining key associations
        arsort($features);

        // Filter keys based on conditions
        $filtered_keys = [];
        $filtered_keys_red = [];
        foreach ($features as $key => $value) {
            switch ($key) {
                case 'de':
                    if (($assessment['de'] > 2 && $assessment['ma'] > 4) || ($assessment['de'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['de'] > 2 && $assessment['ma'] < 5) && ($assessment['sa'] < 5 || $assessment['jo'] < 5 )) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'dom':
                    if (($assessment['dom'] > 2 && ($assessment['sa'] > 4 || $assessment['ma'] > 4)) || ($assessment['dom'] > 2 && $assessment['ma'] > 4 && $assessment['mer'] > 4 && $assessment['sa'] > 4 && $assessment['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['dom'] > 2 && ($assessment['sa'] < 5 && $assessment['ma'] < 5)) && ($assessment['ma'] < 5 || $assessment['mer'] < 5 || $assessment['sa'] < 5 || $assessment['jo'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'fe':
                    if (($assessment['fe'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['fe'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4 && $assessment['ven'] > 4 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['fe'] > 2 && ($assessment['ma'] < 5 && $assessment['lu'] < 5 && $assessment['ven'] < 5)) && ($assessment['sa'] < 5 || $assessment['jo'] < 5 || $assessment['ven'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'gre':
                    if (($assessment['gre'] > 2 && ($assessment['jo'] > 6)) || ($assessment['gre'] > 2 && $assessment['ven'] > 4 && $assessment['so'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['gre'] > 2 && ($assessment['jo'] < 6)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'lun':
                    if (($assessment['lun'] > 2 && $assessment['lu'] > 4) || ($assessment['lun'] > 2 && $assessment['ven'] > 4 && $assessment['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['lun'] > 2 && $assessment['lu'] < 5) && ($assessment['ven'] < 5 || $assessment['jo'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'nai':
                    if (($assessment['nai'] > 2 && $assessment['so'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['nai'] > 2 && $assessment['so'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'ne':
                    if (($assessment['ne'] > 2 && ($assessment['sa'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['ne'] > 2 && $assessment['ma'] > 4 && $assessment['mer'] > 4 && $assessment['ven'] > 4 && $assessment['jo'] > 4 && $assessment['lu'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif(($assessment['ne'] > 2 && ($assessment['sa'] < 5 && $assessment['lu'] < 5 && $assessment['ven'] < 5)) && ($assessment['ne'] < 5 || $assessment['ma'] < 5 || $assessment['mer'] < 5 || $assessment['ven'] < 5 || $assessment['jo'] < 5 || $assessment['lu'] < 5)){
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'pow':
                    if (($assessment['pow'] > 2 && ($assessment['jo'] > 4 || $assessment['mer'] > 4)) || ($assessment['pow'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4 && $assessment['ven'] > 4 && $assessment['sa'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['pow'] > 2 && ($assessment['jo'] < 5 && $assessment['mer'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['ven'] < 5 || $assessment['sa'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'sp':
                    if (($assessment['sp'] > 2 && $assessment['jo'] > 4) || ($assessment['sp'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['sp'] > 2 && $assessment['jo'] < 5) && ($assessment['ma'] < 5 || $assessment['lu'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    elseif (($assessment['tra'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'tra':
                    if (($assessment['tra'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4)) || ($assessment['tra'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['tra'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'van':
                    if (($assessment['van'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4 || $assessment['mer'] > 4 || $assessment['so'] > 4)) || ($assessment['van'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4 && $assessment['mer'] > 4 && $assessment['ven'] > 4 && $assessment['sa'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['van'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5 && $assessment['mer'] < 5 && $assessment['so'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5 || $assessment['ven'] < 5 || $assessment['sa'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'wil':
                    if (($assessment['wil'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4)) || ($assessment['wil'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4 && $assessment['ven'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['wil'] > 2 && ($assessment['ma'] < 5 && $assessment['lu'] < 5)) && ($assessment['sa'] < 5 || $assessment['jo'] < 5 || $assessment['ven'] < 5)) {
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
            $nextTwoKeys = array_slice(array_keys($topAllKeys), 2, 2);
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

            if (!empty($remaining_keys)) {
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

                $topTwoKeys = array_slice(array_keys($allValuesGets), 0, 2);
                $nextTwoKeys = array_slice(array_keys($allValuesGets), 2, 2);
            }
            else {
                $topTwoKeys = array_keys($unique_filtered_keys);
                $nextTwoKeys = [];
            }

        }

        // Create entries for features top two
        foreach ($topTwoKeys as $feature) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $feature,
                'code_color' => 'green',
            ]);
        }

        // Create entries for features top two
        foreach ($nextTwoKeys as $feature) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $feature,
                'code_color' => 'yellow',
            ]);
        }

        // Create entries for features top two
        foreach ($redKeys as $feature) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $feature,
                'code_color' => 'red',
            ]);
        }

    }
}
