<?php

namespace App\Models\HAIChai;

use Carbon\Carbon;
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

    // accessor
    public function getCreatedAtAttribute($value){

        return Carbon::parse($value)->format('Y-m-d');

    }

    public function getUpdatedAtAttribute($value){

        return Carbon::parse($value)->format('Y-m-d');

    }

    // Relations
    public function embeddings(){

        return $this->hasMany(GroupEmbedding::class,'group_id','id');
    }


    public function getIsActiveGroupAttribute(){

        return $this->embeddings()->has('embedding.activeEmbedding')->exists();
    }

    // Queries
    public static function createEmbeddingGroup($name, $description){

        return self::create(['name' => $name, 'description' => $description]);
    }

    public static function allGroups(){

        return self::all();
    }

    public static function activeGroups($chatBotName = null, $name = null){

        request()->merge(['chat_bot' => $chatBotName]);

        return self::when($name, function ($query, $name){

            $query->where('name', "like", "%$name%");

        })->where(function ($query){

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

    public static function nonActiveGroups($chatBotName = null, $name = null){

        request()->merge(['chat_bot' => $chatBotName]);

        return self::when($name, function ($query, $name){

            $query->where('name', "like", "%$name%");
        })

            ->where(function ($query){

            return $query->whereDoesntHave('embeddings.embedding.activeEmbedding');

        })->get();
    }

    public static function allClusters($searchCluster = null, $brain_id = null){

        return self::when($searchCluster, function ($query, $search){

            $query->where('name', 'like', "%$search%");

        })

            ->get();
    }

    public static function updateEmbeddingGroup($cluster_id = null, $name = null, $description = null){

        self::whereId($cluster_id)->update(['name' => $name, 'description' => $description]);

    }

}
