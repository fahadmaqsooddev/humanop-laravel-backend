<?php

namespace App\Models\KnowledgeBase;

use App\Models\HAIChai\HaiChatActiveEmbedding;
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

        self::create([
            'content' => $content,
            'embedding' => json_encode($embedding['embedding']),
            'embedding_id' => $embedding_id
        ]);

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
