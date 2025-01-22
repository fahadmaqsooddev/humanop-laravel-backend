<?php

namespace App\Models\HAIChai;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmbeddingGroup extends Model
{
    use HasFactory, SoftDeletes;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    protected $appends = ['is_active_group'];

    // Relations
    public function embeddings(){

        return $this->hasMany(GroupEmbedding::class,'group_id','id');
    }


    public function getIsActiveGroupAttribute(){

        return $this->embeddings()->has('embedding.activeEmbedding')->exists();
    }

    // Queries
    public static function createEmbeddingGroup($name){

        return self::create(['name' => $name]);
    }

    public static function allGroups(){

        return self::all();
    }

    public static function activeGroups(){

        return self::where(function ($query){

            return $query->has('embeddings.embedding.activeEmbedding');

        })->get();
    }

    public static function groups($chat_bot_name = null, $searchName = null){

        request()->merge(['chat_bot' => $chat_bot_name]);

        return self::when($searchName,function ($query)use ($searchName){

            $query->where('name', 'LIKE', "%$searchName%");

        })->get();
    }

    public static function groupsForDropDown($searchName = null){

        return self::when($searchName, function ($query)use ($searchName){

            $query->where('name', 'LIKE', "%$searchName%");

        })->get();
    }

    public static function groupNames($group_ids = null){

        return self::whereIn('id', $group_ids)->get()->pluck('name')->toArray();

    }

}
