<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupEmbedding extends Model
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
    public function embedding(){

        return $this->belongsTo(HaiChatEmbedding::class,'embedding_id','id');
    }

    public function activeEmbeddings(){

        return $this->belongsToMany(HaiChatEmbedding::class,'hai_chat_active_embeddings','request_id','request_id','embedding_id','id');
    }


    // Queries
    public static function addOrUpdateGroupIds($embedding_ids = [], $group_id = null){

        self::where('group_id', $group_id)->delete();

        foreach ($embedding_ids as $embedding_id){

            self::create([
                'embedding_id' => $embedding_id,
                'group_id' => $group_id
            ]);

        }

    }

    public static function groupEmbeddings($group_id = null){

        return self::where('group_id', $group_id)

            ->has('embedding')

            ->with('embedding')

            ->get();

    }

    public static function deleteGroupEmbeddings($embedding_id){

        self::where('embedding_id', $embedding_id)->delete();
    }

    public static function groupEmbeddingsIds($group_id = null){

        return self::where('group_id', $group_id)->pluck('embedding_id');

    }
}
