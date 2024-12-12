<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HaiChatEmbedding extends Model
{
    use HasFactory;
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

        return $this->hasOne(HaiChatActiveEmbedding::class,'request_id','request_id')->where('chat_bot', request()->input('chat_bot'));
    }

    // Appends
    public function getIsActiveEmbeddingAttribute(){

        return $this->activeEmbedding()->exists();
    }


    // queries
    public static function singleEmbedding($id)
    {
        return self::whereId($id)->first();
    }

    public static function getEmbeddingByName($name = null)
    {
        return self::where('name', $name)->pluck('request_id')->toArray();
    }

    public static function allEmbeddings()
    {
        return self::orderBy('created_at', 'desc')->get();
    }
    public static function allEmbeddingsExcept($embeddings = [])
    {
        return self::orderBy('created_at', 'desc')->whereNotIn('request_id',$embeddings)->get();
    }

    public static function createEmbedding($name = null, $request_id = null)
    {
        return self::create([
            'name' => $name,
            'request_id' => $request_id,
        ]);
    }

    public static function deleteEmbedding($id)
    {
        return self::whereId($id)->delete();
    }

    public static function updateGroupId($embedding_id = null, $group_id = null){

        self::whereId($embedding_id)->update(['group_id' => $group_id]);

    }

    public static function embeddings($group_id, $chat_bot, $searchText){

        request()->merge(['chat_bot' => $chat_bot]);

        return self::whereHas('group', function ($q) use($group_id){

            $q->where('group_id', $group_id);

        })->when($searchText, function ($query, $name){

            $query->where('name', 'LIKE', "%$name%");

        })->get();

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
        return self::when($searchName, function ($query, $name){

            $query->where('name', 'LIKE', "%$name%");

        })

            ->orderBy('created_at', 'desc')

            ->get();
    }

}
