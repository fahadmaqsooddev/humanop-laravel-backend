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

    // Relations
    public function embeddings(){

        return $this->hasMany(GroupEmbedding::class,'group_id','id');
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

    public static function inActiveGroups(){

        return self::doesnthave('embeddings.embedding.activeEmbedding')->get();
    }

}
