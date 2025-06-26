<?php

namespace App\Models\Videos;

use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
use App\Models\Client\HumanOpPoints\HumanOpPoints;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');
        parent::__construct($attributes);
    }

    public static function getRecords($assessmentId = null)
    {
        return self::where('assessment_id', $assessmentId)->get();
    }

    public static function checkAllWatchVideos($assessmentId = null)
    {
        return self::where('assessment_id', $assessmentId)->where('video_progress', 1)->get();
    }

    public static function checkVideoProgress($assessmentId = null, $name = null)
    {
        $progress = self::where('assessment_id', $assessmentId)->where('video_name', $name)->first();

        return $progress ? $progress['video_progress'] == 1 ? Admin::COMPLETE_VIDEO : Admin::NOT_COMPLETE_VIDEO : Admin::NOT_COMPLETE_VIDEO;
    }

    public static function createVideoProgress($assessmentId = null, $resultNames = null, $traits = null, $topTwoFeatures = null, $topCommunications = null)
    {

        $records = self::getRecords($assessmentId)->toArray();

        if (!empty($records)) {
            return;
        }

        $itemsToInsert = [];

        // Add result names
        if (is_array($resultNames)) {
            foreach ($resultNames as $resultName) {
                $itemsToInsert[] = [
                    'assessment_id' => $assessmentId,
                    'video_name' => $resultName,
                ];
            }
        }

        // Add traits
        if (is_array($traits)) {
            foreach ($traits as $trait) {
                if (isset($trait['name'])) {
                    $itemsToInsert[] = [
                        'assessment_id' => $assessmentId,
                        'video_name' => $trait['name'],
                    ];
                }
            }
        }

        // Add top features
        if (is_array($topTwoFeatures)) {
            foreach ($topTwoFeatures as $feature) {
                if (isset($feature['name'])) {
                    $itemsToInsert[] = [
                        'assessment_id' => $assessmentId,
                        'video_name' => $feature['name'],
                    ];
                }
            }
        }

        // Add top communications
        if (is_array($topCommunications)) {
            foreach ($topCommunications as $communication) {
                if (isset($communication['name'])) {
                    $itemsToInsert[] = [
                        'assessment_id' => $assessmentId,
                        'video_name' => $communication['name'],
                    ];
                }
            }
        }

        // Create all records
        foreach ($itemsToInsert as $data) {

            self::create($data);

        }

    }

    public static function completeWatchVideo($assessmentId = null, $videoName = null)
    {
        $progress = self::where('assessment_id', $assessmentId)->where('video_name', $videoName)->first();

        if (!empty($progress) && $progress['video_progress'] == 0) {

            $progress->video_progress = 1;

            $progress->save();

            $userId = Helpers::getUser()['id'];

            HumanOpPoints::addPointsAfterCompleteWatchVideo($userId);

            $recordCount = self::getRecords($assessmentId)->count();

            $watchVideo = self::checkAllWatchVideos($assessmentId)->count();

            if ($recordCount == $watchVideo) {

                HumanOpPoints::addPointsAfterCompleteAllWatchVideos($userId);
            }

            return $progress;
        }

        return null;
    }
}
