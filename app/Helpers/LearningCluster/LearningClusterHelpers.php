<?php

namespace App\Helpers\LearningCluster;



use App\Models\HAIChai\Chatbot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use function OpenAI\ValueObjects\Transporter\data;

class LearningClusterHelpers
{

    public static function addContentToLearningCluster($brain_name, $content = ""){

        $file_name = $brain_name . "_LEARNING_CLUSTER.txt";

        Storage::disk('local')->put('/learning_clusters/' . $file_name, $content);

    }

    public static function getLearningCluster($brain_name){

        $file_name = $brain_name . "_LEARNING_CLUSTER.txt";

        $content = Storage::disk('local')->get('/learning_clusters/' . $file_name);

        return $content;

    }

    public static function updateLearningCluster($brain_name, $question, $answer, $action){

        $brain_name = Chatbot::where('name', $brain_name)->first()->brain_name ?? null;

        if ($brain_name){

            $content = self::getLearningCluster($brain_name);

            $dateTime = Carbon::now()->format('Y-m-d H:i:s');

            $new_content = $content . "\nQuestion: " . $question . "\nAnswer: " . $answer .

                "\nDate Time: " . $dateTime . "\nAction: " . $action ."\n";

            self::addContentToLearningCluster($brain_name, $new_content);

        }
    }

    public static function deleteLearningClusterFile($brain_name){

        $file_name = $brain_name . "_LEARNING_CLUSTER.txt";

        Storage::disk('local')->delete('/learning_clusters/' . $file_name);
    }
}
