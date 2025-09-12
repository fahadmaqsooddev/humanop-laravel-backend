<?php

namespace App\Models\Admin\AssessmentIntro;

use App\Helpers\Helpers;
use App\Models\Admin\Code\ResultVideo;
use App\Models\Videos\VideoProgress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Twilio\Rest\Video;

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

    public function video()
    {
        return $this->belongsTo(ResultVideo::class, 'video_id', 'id');
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

    public static function getRecord($videoName = null)
    {
        return self::where('name', $videoName)->first();
    }

    public static function summaryIntro($assessmentId = null)
    {
        $data = self::where('code', 'SI')->first();

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $data['video_url'] ?? '',
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function getSingleSummaryReport($id)
    {
        return self::where('code', 'SI')->where('id', $id)->first();
    }

    public static function mainResult($assessmentId = null)
    {

        $data = self::where('code', 'MRI')->first();


        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];

    }

    public static function cycleLife($assessmentId = null)
    {

        $data = self::where('code', 'CLI')->first();

        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function traitIntro($assessmentId = null)
    {

        $data = self::where('code', 'TI')->first();

        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function motivationIntroduction($assessmentId = null)
    {

        $data = self::where('code', 'MI')->with('video')->first();

        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function introBoundaries($assessmentId = null)
    {

        $data = self::where('code', 'BI')->with('video')->first();

        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function introCommunication($assessmentId = null)
    {

        $data = self::where('code', 'CI')->with('video')->first();

        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];
    }

    public static function introEnergypool($assessmentId = null)
    {

        $data = self::where('code', 'EI')->with('video')->first();

        $video = $data->video;

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $data->name);

        return [
            'name' => $data->name ?? '',
            'public_name' => $data->public_name ?? '',
            'description' => $data->text ?? '',
            'video_url' => $videoUrl,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
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

    public static function  getPerceptionStaticText($assessmentId = null)
    {

        $result = self::where('code', 'PLI')->with('video')->first();

        $video = $result['video'];

        $videoUrl = !empty($video['video_upload_id']) && !empty($video['video_upload_url']['path'])
            ? $video['video_upload_url']['path']
            : ($video['video_url'] ?? null);

        $progress = VideoProgress::checkVideoProgress($assessmentId, $result->name);

        return [
            'code_number' => $result['id'],
            'public_name' => $result['public_name'],
            'name' => $result['name'],
            'description' => $result['text'],
            'video' => $videoUrl,
            'p_name' => $result['p_name'],
            'video_url' => $result['video'] ? $result['video']['video_url'] : null,
            'video_progress' => $progress['video_progress'],
            'video_time' => $progress['video_time']
        ];


    }

}
