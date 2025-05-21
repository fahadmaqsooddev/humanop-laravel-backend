<?php

namespace App\Models\Admin\AssessmentIntro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentIntro extends Model
{
    use HasFactory;


    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function allIntro()
    {
        return self::where('code', '!=', 'SI')->get();
    }

    public static function createIntro($data = null)
    {

        self::create([
            'name' => $data['name'],
            'public_name' => $data['public_name'],
            'code' => $data['code'],
            'number' => $data['number'],
            'type' => $data['type'],
            'text' => $data['text'],
        ]);
    }

    public static function getSingleAssessmentIntro($id = null)
    {
        return self::find($id);
    }

    public static function updateIntro($data = null, $id = null)
    {
        $assesssment = self::find($id)->update($data);

        return $assesssment;

    }

    public static function summaryReport()
    {
        return self::where('code', 'SI')->get();
    }

    public static function summaryIntro()
    {
        $data = self::where('code', 'SI')->first();

        return [
            'public_name' => $data->name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function getSingleSummaryReport($id)
    {
        return self::where('code', 'SI')->where('id', $id)->first();
    }

    public static function mainResult()
    {

        $data = self::where('code', 'MRI')->first();

        return [
            'public_name' => $data->name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];

    }

    public static function cycleLife()
    {

        $data = self::where('code', 'CLI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function traitIntro()
    {

        $data = self::where('code', 'TI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function motivationIntroduction()
    {

        $data = self::where('code', 'MI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function introBoundaries()
    {

        $data = self::where('code', 'BI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function introCommunication()
    {

        $data = self::where('code', 'CI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function introEnergypool()
    {

        $data = self::where('code', 'EI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video' => $data['video_url'] ?? ''
        ];
    }

    public static function perceptionLife()
    {

        $data = self::where('code', 'PLI')->first();

        return [
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $data['video_url'] ?? ''
        ];
    }

    public static function getPerceptionStaticText()
    {

        $result = self::where('code', 'PLI')->first(['id', 'text', 'public_name', 'video', 'p_name']);
        return [
            'code_number' => $result['id'],
            'public_name' => $result['public_name'],
            'description' => $result['text'],
            'video' => $result['video'],
            'p_name' => $result['p_name'],
            'video_url' => $result['video_url']
        ];


    }

}
