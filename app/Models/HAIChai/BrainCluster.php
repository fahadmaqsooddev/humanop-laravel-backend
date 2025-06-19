<?php

namespace App\Models\HAIChai;

use App\Models\Assessment;
use App\Models\AssessmentColorCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrainCluster extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.'.class_basename(__CLASS__).'.table');
        $this->fillable = config('database.models.'.class_basename(__CLASS__).'.fillable');
        $this->hidden = config('database.models.'.class_basename(__CLASS__).'.hidden');

        parent::__construct($attributes);
    }

    // relation
    public function brain(){

        return $this->belongsTo(Chatbot::class,'chat_bot_id','id');
    }

    public function activeEmbeddings(){

        return $this->hasManyThrough(HaiChatEmbedding::class,GroupEmbedding::class,'group_id','id','cluster_id','embedding_id');

    }

    public function cluster(){

        return $this->belongsTo(EmbeddingGroup::class,'cluster_id','id');
    }

    // query
    public static function addClustersWithBrain($cluster_ids = [], $chat_bot_id = null){

        foreach ($cluster_ids as $cluster_id){

            self::create(['cluster_id' => $cluster_id, 'chat_bot_id' => $chat_bot_id]);
        }
    }

    public static function addClusterWithBrain($cluster_id = null, $chat_bot_id = null){

        self::create(['cluster_id' => $cluster_id, 'chat_bot_id' => $chat_bot_id]);

    }

    public static function removeClusterFromBrain($cluster_id = null, $chat_bot_id = null){

        self::where('chat_bot_id', $chat_bot_id)->where('cluster_id', $cluster_id)->delete();

    }

    public static function removeClustersFromBrain($cluster_ids = [], $chat_bot_id = null){

        foreach($cluster_ids as $cluster_id){

            self::where('chat_bot_id', $chat_bot_id)->where('cluster_id', $cluster_id)->delete();
        }


    }

    public static function connectedClusterEmbeddingIds($chat_bot_id){

        $request_ids = self::has('cluster')->where('chat_bot_id',$chat_bot_id)->with('activeEmbeddings')->get()->flatMap(function ($item) {
            return $item->activeEmbeddings->pluck('request_id');
        })
            ->unique()
            ->values()
            ->toArray();

        return [
            'file_name' => $request_ids
        ];
    }

    public static function addDuplicateBrainClusters($oldChatBotId, $newChatBotId){

        $clusters = self::where('chat_bot_id', $oldChatBotId)->get()->toArray();

        foreach ($clusters as $cluster){

            self::create([
                'chat_bot_id' => $newChatBotId,
                'cluster_id' => $cluster['cluster_id']
            ]);

        }

    }

    public static function connectedNewClusterEmbeddingIds($chat_bot_id){

        $request_ids = self::has('cluster')->where('chat_bot_id',$chat_bot_id)->with('activeEmbeddings')->get()->flatMap(function ($item) {
            return $item->activeEmbeddings->pluck('request_id');
        })
            ->unique()
            ->values()
            ->toArray();

        return $request_ids;
    }


}
