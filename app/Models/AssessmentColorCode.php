<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assessment;
use function Symfony\Component\Mime\Test\Constraint\toString;

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

    // scope

    public function scopeSelection($query){

        return $query->select(['id', 'assessment_id', 'code', 'code_color', 'code_number']);
    }

    public static function getCodeColor($assessmentId = null)
    {
        $assessmentCodeColors = self::where('assessment_id', $assessmentId)->get();

        $code_color = [];
        foreach ($assessmentCodeColors as $assessment) {
            $code_color[$assessment['code']] = $assessment['code_number'];
        }

        return $code_color;
    }

    public static function deleteAssessemntColorCodeData($assessment = null)
    {
        self::where('assessment_id', $assessment['id'])->delete();
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
        $third_row_so = 0;

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
                        if ($assessment['ven'] > 4 && $assessment['sa'] > 4 && $third_row_mer > 30) {
                            $style_border_green[$key] = $style;
                        }
                        break;
                }
            }
        }

//        dd($style_greater_than_4);
        // Create entries for styles greater than 4
        foreach ($style_greater_than_4 as $key => $style_num) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'green',
                'code_number' => $style_num,
            ]);
        }

        // Create entries for styles equal to 0
        foreach ($style_equal_to_0 as $key => $style_num) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'red',
                'code_number' => $style_num,
            ]);
        }

        // Create entries for styles border green
        foreach ($style_border_green as $key => $style_num) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'border-green',
                'code_number' => $style_num,
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
                    if (($assessment['dom'] > 2 && ($assessment['sa'] > 4 || $assessment['ma'] > 4)) || ($assessment['dom'] > 2 && $assessment['ma'] > 4 && $assessment['mer'] > 4) || ($assessment['dom'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['dom'] > 2 && ($assessment['sa'] < 5 && $assessment['ma'] < 5)) && ($assessment['ma'] < 5 || $assessment['mer'] < 5 || $assessment['sa'] < 5 || $assessment['jo'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'fe':
                    if (($assessment['fe'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['fe'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4) || ($assessment['fe'] > 2 && $assessment['jo'] > 4 && $assessment['ven'] > 4) || ($assessment['fe'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['fe'] > 2 && ($assessment['ma'] < 5 && $assessment['lu'] < 5 && $assessment['ven'] < 5)) && ($assessment['sa'] < 5 || $assessment['jo'] < 5 || $assessment['ven'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'gre':
                    if (($assessment['gre'] > 2 && ($assessment['jo'] > 6 || $assessment['mer'] > 4 )) || ($assessment['gre'] > 2 && $assessment['ven'] > 4 && $assessment['so'] > 4) || ($assessment['gre'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['gre'] > 2 && $assessment['jo'] < 6 && $assessment['mer'] < 5) && ($assessment['gre'] > 2 && $assessment['ma'] < 5 && $assessment['lu'] < 5) && ($assessment['gre'] > 2 && $assessment['ven'] < 5 && $assessment['so'] < 5)) {
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
                    if (($assessment['ne'] > 2 && ($assessment['sa'] > 4 || $assessment['lu'] > 4 || $assessment['ven'] > 4)) || ($assessment['ne'] > 2 && $assessment['ma'] > 4 && $assessment['mer'] > 4) || ($assessment['ne'] > 2 && $assessment['ven'] > 4 && $assessment['jo'] > 4) || ($assessment['ne'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif(($assessment['ne'] > 2 && ($assessment['sa'] < 5 && $assessment['lu'] < 5 && $assessment['ven'] < 5)) && ($assessment['ne'] < 5 || $assessment['ma'] < 5 || $assessment['mer'] < 5 || $assessment['ven'] < 5 || $assessment['jo'] < 5 || $assessment['lu'] < 5)){
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'pow':
                    if (($assessment['pow'] > 2 && ($assessment['jo'] > 4 || $assessment['mer'] > 4)) || ($assessment['pow'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4) || ($assessment['pow'] > 2 && $assessment['ven'] > 4 && $assessment['sa'] > 4)) {
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
                    if (($assessment['tra'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4)) || ($assessment['tra'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4) || ($assessment['tra'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['tra'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'van':
                    if (($assessment['van'] > 2 && ($assessment['jo'] > 4 || $assessment['ven'] > 4 || $assessment['mer'] > 4 || $assessment['so'] > 4)) || ($assessment['van'] > 2 && $assessment['ma'] > 4 && $assessment['lu'] > 4) || ($assessment['van'] > 2 && $assessment['lu'] > 4 && $assessment['mer'] > 4) || ($assessment['van'] > 2 && $assessment['ven'] > 4 && $assessment['sa'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['van'] > 2 && ($assessment['jo'] < 5 && $assessment['ven'] < 5 && $assessment['mer'] < 5 && $assessment['so'] < 5)) && ($assessment['ma'] < 5 || $assessment['lu'] < 5 || $assessment['mer'] < 5 || $assessment['ven'] < 5 || $assessment['sa'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
                case 'wil':
                    if (($assessment['wil'] > 2 && ($assessment['ma'] > 4 || $assessment['lu'] > 4)) || ($assessment['wil'] > 2 && $assessment['sa'] > 4 && $assessment['jo'] > 4) || ($assessment['wil'] > 2 && $assessment['jo'] > 4 && $assessment['ven'] > 4)) {
                        $filtered_keys[$key] = $value;
                    }
                    elseif (($assessment['wil'] > 2 && ($assessment['ma'] < 5 && $assessment['lu'] < 5)) && ($assessment['sa'] < 5 || $assessment['jo'] < 5 || $assessment['ven'] < 5)) {
                        $filtered_keys_red[$key] = $value;
                    }
                    break;
            }
        }

        $redKeys = $filtered_keys_red;

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
            $nextTwoKeys = array_slice(array_keys($allValuesGets), 2, 2);
        }

        $matchedTopTwoFeatures = [];
        foreach ($topTwoKeys as $key) {
            if (array_key_exists($key, $features)) {
                $matchedTopTwoFeatures[$key] = $features[$key];
            }
        }

        // Create entries for features top two
        foreach ($matchedTopTwoFeatures as $key => $feature) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'green',
                'code_number' => $feature,
            ]);
        }

        $matchedNextTwoFeatures = [];
        foreach ($nextTwoKeys as $key) {
            if (array_key_exists($key, $features)) {
                $matchedNextTwoFeatures[$key] = $features[$key];
            }
        }

        // Create entries for features top two
        foreach ($matchedNextTwoFeatures as $key => $feature) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'yellow',
                'code_number' => $feature
            ]);
        }

        // Create entries for features top two
        foreach ($redKeys as $key => $feature) {
            self::create([
                'assessment_id' => $assessment['id'],
                'code' => $key,
                'code_color' => 'red',
                'code_number' => $feature,
            ]);
        }

    }

    public static function getStylePosition($assessment_id = null)
    {

        $styles = [
            'sa' => 1,
            'ma' => 2,
            'jo' => 3,
            'lu' => 4,
            'ven' => 5,
            'mer' => 6,
            'so' => 7,
        ];

        $assessment_code_colors = self::where('assessment_id', $assessment_id)
            ->whereIn('code_color', ['green', 'border-green'])
            ->get(['code']);

        $matched = [];

        foreach ($assessment_code_colors as $codeObj) {
            $code = $codeObj->code; // Assuming 'code' is the column name
            if (isset($styles[$code])) {
                $matched[$code] = $styles[$code];
            }
        }

        asort($matched);

        $style_highlighted = array_values($matched);

        $style_highlighted_string = implode(',', $style_highlighted);

        return $style_highlighted_string;

    }

    public static function getFeaturePosition($assessment_id = null)
    {

        $features = [
            'de' => 1,
            'dom' => 2,
            'fe' => 3,
            'gre' => 4,
            'lun' => 5,
            'nai' => 6,
            'ne' => 7,
            'pow' => 8,
            'sp' => 9,
            'tra' => 10,
            'van' => 11,
            'wil' => 12,
        ];

        $assessment_code_colors = self::where('assessment_id', $assessment_id)
            ->where('code_color', 'green')
            ->get(['code']);

        $matched = [];

        foreach ($assessment_code_colors as $codeObj) {
            $code = $codeObj->code; // Assuming 'code' is the column name
            if (isset($features[$code])) {
                $matched[$code] = $features[$code];
            }
        }

        asort($matched);

        $feature_highlighted = array_values($matched);

        $feature_highlighted_string = implode(',', $feature_highlighted);

        return $feature_highlighted_string;

    }
}
