<?php

namespace App\Models\Admin\Code;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeDetail extends Model
{
    use HasFactory;

    protected $appends = ['video_url'];

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

//    append
    public function getVideoUrlAttribute()
    {

        if (!empty($this->video)) {

            return asset('assets/video/') . $this->video;
        }
    }


    // query
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

    public static function getCodeDeatil($Stylekeys = null, $featureKeys = null, $alchemyCode = null, $communicationCode = null, $polarityCode = null, $energyCode = null, $pv = null, $ep = null, $user = null)
    {
        $style_code_detail = [];
        $feature_code_detail = [];
        $communication_code_detail = [];

        foreach ($Stylekeys['top_two_keys'] ?? null as $index => $style_key) {
            $style_key_code = strtoupper($style_key);

            $style_code_text = self::where('code', $style_key_code)->where('number', $index + 1)->first(['id', 'text', 'public_name', 'number', 'video', 'p_name']);

            $style_code_detail[] = $style_code_text;
        }

        foreach ($featureKeys['top_two_keys'] ?? null as $feature_key) {
            $feature_key_code = strtoupper($feature_key);

            $feature_code_text = self::where('code', $feature_key_code)->first(['id', 'text', 'public_name', 'video', 'p_name']);

            $feature_code_detail[] = $feature_code_text;
        }

        foreach ($communicationCode as $communication_key) {
            $communication_key_code = strtoupper($communication_key);

            $communication_code_text = self::where('code', $communication_key_code)->first(['id', 'text', 'public_name', 'video', 'p_name']);

            $communication_code_detail[] = $communication_code_text;
        }

        $alchemy_key_code = strtoupper($alchemyCode['code'] ?? null);
        $alchemy_code = self::where('code', $alchemy_key_code)->first(['id', 'text', 'public_name', 'video', 'p_name']);

        $alchemy_code_deatil = [
            'id' => $alchemyCode['id'] ?? null,
            'image' => $alchemyCode['image'] ?? null,
            'text' => $alchemy_code['text'] ?? null,
            'public_name' => $alchemy_code['public_name'] ?? null,
            'video' => $alchemy_code['video'] ?? null,
            'p_name' => $alchemy_code['p_name'] ?? null,
        ];

        $perception_life = self::where('id', 38)->first(['id', 'text', 'public_name', 'video', 'p_name']);
        $polarity_code_detail = self::where('id', $polarityCode)->first(['id', 'text', 'public_name', 'video', 'p_name']);
        $energy_code_detail = self::where('id', $energyCode)->first(['id', 'text', 'public_name', 'video', 'p_name']);

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
            'user_name' => $user ? $user['first_name'] . ' ' . $user['last_name'] : "",
            'user_gender' => $user['gender'] ?? null,
        ];

        return $results;

    }

    public static function styleAndFeatureCode()
    {

        return self::whereIn('type', ['Style', 'Feature'])->select(['id', 'code', 'public_name'])->get()->unique('public_name');

    }

    public static function alchemyCodes()
    {

        return self::where('type', 'Alchemy')->select(['id', 'code', 'public_name'])->get()->unique('public_name');

    }

    public static function getPublicNames($codekeys = null)
    {
        $publicName = [];
        foreach ($codekeys as $codeKey) {
            $key = strtoupper($codeKey);
            $keyPublicName = self::where('code', $key)->where('number', 1)->first('public_name');
            $publicName[] = $keyPublicName;
        }

        return $publicName;
    }

    public static function getSinglePublicName($codeKey = null)
    {
        return self::where('code', $codeKey)->where('number', 1)->first('public_name');
    }
}
