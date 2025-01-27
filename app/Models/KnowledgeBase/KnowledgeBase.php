<?php

namespace App\Models\KnowledgeBase;

use App\Models\HAIChai\HaiChatActiveEmbedding;
use App\Models\HAIChai\HaiChatEmbedding;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    // Relations
    public function activeEmbeddings(){

        return $this->hasMany(HaiChatActiveEmbedding::class,'knowledge_base_id','id');
    }


    // queries
    public static function createEmbeddingKnowledge($content, $embedding, $embedding_id){

        $apiKey = "pcsk_RvRK3_8wKwiqZAapNbMNhEpPZvP6nx9szRX3UtKv49VPX25L4VP7vt8MXsRs1C2Csx5xk";

        $pinecone = new \Probots\Pinecone\Client($apiKey, 'https://my-index-wgj0px8.svc.aped-4627-b74a.pinecone.io');

        $id = \Illuminate\Support\Str::random(10);

        $response = $pinecone->data()->vectors()->upsert([
            'id' => $id,
            'values' => $embedding['embedding'],
            'metadata' => [
                'text' => $content,
                'database_id' => $id
            ]
        ]);

        if ($response->successful()){

            HaiChatEmbedding::whereId($embedding_id)->update(['pine_cone_id' => $id]);

            self::create([
                'content' => $content,
                'embedding' => json_encode($embedding['embedding']),
                'embedding_id' => $embedding_id,
                'pine_cone_id' => $id,
            ]);

        }

    }

    public static function embeddings($ids = []){

        return self::when(!empty($ids), function ($query) use ($ids){

            $query->whereIn('id', $ids);

        })->get();

    }

    public static function deleteEmbedding($id){

        $knowledge = self::where('embedding_id', $id)->first();

        if ($knowledge){

            HaiChatActiveEmbedding::where('knowledge_base_id', $knowledge->id)->delete();

            $knowledge->delete();

        }
    }


}
