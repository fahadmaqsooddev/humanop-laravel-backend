<?php

namespace App\Models\Admin\Code;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeDetail extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    public static function allCodes()
    {
        return self::all();
    }

    public static function getSingleCode($id = null)
    {
        return self::find($id);
    }

    public static function updateCode($data = null, $id = null)
    {

        $code = self::find($id)->update($data);

        return $code;

    }

    public static function getCodeDeatil($Stylekeys = null, $featureKeys = null, $alchemyCode = null, $communicationCode = null, $polarityCode = null, $energyCode = null, $pv = null, $ep = null)
    {
        $style_code_detail = [];
        $feature_code_detail = [];
        $communication_code_detail = [];

        foreach ($Stylekeys['top_two_keys'] as $index => $style_key) {
            $style_key_code = strtoupper($style_key);

            $style_code_text = self::where('code', $style_key_code)->where('number', $index + 1)->first(['text', 'public_name', 'number', 'video']);

            $style_code_detail[] = $style_code_text;
        }

        foreach ($featureKeys['top_two_keys'] as $feature_key) {
            $feature_key_code = strtoupper($feature_key);

            $feature_code_text = self::where('code', $feature_key_code)->first(['text', 'public_name', 'video']);

            $feature_code_detail[] = $feature_code_text;
        }

        foreach ($communicationCode as $communication_key) {
            $communication_key_code = strtoupper($communication_key);

            $communication_code_text = self::where('code', $communication_key_code)->first(['text', 'public_name', 'video']);

            $communication_code_detail[] = $communication_code_text;
        }

        $alchemy_key_code = strtoupper($alchemyCode['code']);
        $alchemy_code = self::where('code', $alchemy_key_code)->first(['text', 'public_name', 'video']);

        $alchemy_code_deatil = [
            'image' => $alchemyCode['image'],
            'text' => $alchemy_code['text'],
            'public_name' => $alchemy_code['public_name'],
        ];

        $perception_life = self::where('id', 38)->first(['text', 'public_name', 'video']);
        $polarity_code_detail = self::where('id', $polarityCode)->first(['text', 'public_name', 'video']);
        $energy_code_detail = self::where('id', $energyCode)->first(['text', 'public_name', 'video']);

        $results = [
            'style_code_details' => $style_code_detail,
            'feature_code_details' => $feature_code_detail,
            'alchemy_code_details' => $alchemy_code_deatil,
            'communication_code_details' => $communication_code_detail,
            'perception_life' => $perception_life,
            'polarity_code_detail' => $polarity_code_detail,
            'energy_code_detail' => $energy_code_detail,
            'pv' => $pv,
            'ep' => $ep,
        ];

        return $results;

    }
}
