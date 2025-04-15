<?php

namespace App\Models\HAIChai;

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


}
