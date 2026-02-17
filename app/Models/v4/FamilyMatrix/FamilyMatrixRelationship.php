<?php

namespace App\Models\v4\FamilyMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMatrixRelationship extends Model
{
    use HasFactory;


    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getRelationships()
    {
        return self::orderBy('created_at', 'Desc')->get();
    }

    public static function createRelationship($data = null)
    {
        return self::create($data);
    }

    public static function updateRelationship($id = null, $name = null)
    {
        return self::where('id', $id)->update(['relationship_name' => $name]);
    }

    public static function deleteRelationship($id = null)
    {
        return self::where('id', $id)->delete();
    }

}
