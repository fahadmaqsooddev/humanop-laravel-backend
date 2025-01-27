<?php

namespace App\Models\HAIChai;

use App\Models\KnowledgeBase\KnowledgeBase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HaiChatEmbedding extends Model
{
    use HasFactory, SoftDeletes;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }


    protected $appends = ['is_active_embedding'];

    // Relation
    public function group(){

        return $this->hasOne(GroupEmbedding::class,'embedding_id', 'id');
    }

    public function activeEmbedding(){

        return $this->hasOne(HaiChatActiveEmbedding::class,'knowledge_base_id','id')->where('chat_bot_id', request()->input('chat_bot_id'));
    }

    public function knowledgeBase(){

        return $this->hasOne(KnowledgeBase::class,'embedding_id','id');
    }

    // Appends
    public function getIsActiveEmbeddingAttribute(){

        return $this->knowledgeBase()->has('activeEmbeddings')->exists();
    }


    // queries
    public static function singleEmbedding($id)
    {
        return self::whereId($id)->first();
    }

    public static function getEmbeddingByName($name = null)
    {
        return self::where('name', $name)->pluck('embedding_id')->toArray();
    }

    public static function allEmbeddings()
    {
        return self::whereNull('pine_cone_id')->orderBy('created_at', 'desc')->get();
    }
//    public static function allEmbeddingsExcept($embeddings = [])
//    {
//        return self::orderBy('created_at', 'desc')->whereNotIn('request_id',$embeddings)->get();
//    }

    public static function createEmbedding($name = null)
    {
        return self::create([
            'name' => $name,
        ]);
    }

    public static function deleteEmbedding($id)
    {
        return self::whereId($id)->delete();
    }

    public static function updateGroupId($id = null, $group_id = null){

        self::whereId($id)->update(['group_id' => $group_id]);

    }

    public static function embeddings($group_id, $chat_bot_id, $searchText, $is_pine_cone = false){

        request()->merge(['chat_bot_id' => $chat_bot_id]);

        return self::when($is_pine_cone,function ($query){

            $query->whereNotNull('pine_cone_id');

        }, function ($query){

            $query->whereNull('pine_cone_id');

        })->whereHas('group', function ($q) use($group_id){

            $q->where('group_id', $group_id);

        })->when($searchText, function ($query, $name){

            $query->where('name', 'LIKE', "%$name%");

        })->with('knowledgeBase')->get();

    }

//    public static function inActiveEmbeddings($group_id, $chat_bot){
//
//        return self::whereHas('group', function ($q) use($group_id){
//
//            $q->where('group_id', $group_id);
//
//        })
//            ->where(function ($q) use($chat_bot){
//
//                $q->doesntHave('activeEmbedding')
//
//                    ->orWhereHas('activeEmbedding', function ($query) use($chat_bot){
//
//                        $query->where('chat_bot', '!=', $chat_bot);
//
//                    });
//            })
//
//            ->get();
//
//    }

    public static function allEmbeddingsForDropDown($searchName = null)
    {
        return self::whereNull('pine_cone_id')->when($searchName, function ($query, $name){

            $query->where('name', 'LIKE', "%$name%");

        })

            ->orderBy('created_at', 'desc')

            ->get();
    }

    public static function embeddingByName($name){

        return self::whereNull('pine_cone_id')->where('name', $name)->first();
    }

    public static function updateEmbeddingChunks($embedding_id, $chunks){

        self::whereId($embedding_id)->update(['chunks' => $chunks]);

    }

}
