<?php

namespace App\Helpers\LearningCluster;



use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LearningClusterHelpers
{

    public static function addContentToLearningCluster($brain_name, $content = null){

        $file_name = $brain_name . "_LEARNING_CLUSTER.txt";

        Storage::disk('local')->put('/learning_clusters/' . $file_name, "");

    }

    public static function getLearningCluster($brain_name){

        $file_name = $brain_name . "_LEARNING_CLUSTER.txt";

        $content = Storage::disk('local')->get('/learning_clusters/' . $file_name);

        return $content;

    }

    public static function updateLearningCluster($brain_name, $question, $answer, $action){

        $content = self::getLearningCluster($brain_name);

        $dateTime = Carbon::now()->format('Y-m-d H:i:s');

        $new_content = $content . "\n" . "Question: " . $question . "\nAnswer: " . $answer .

            "\nDate Time: " . $dateTime . "\n Action: " . $action;

        self::addContentToLearningCluster($brain_name, $new_content);
    }
}
