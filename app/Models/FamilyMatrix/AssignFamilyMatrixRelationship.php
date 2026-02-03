<?php

namespace App\Models\FamilyMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignFamilyMatrixRelationship extends Model
{
    use HasFactory;


    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function getRelationships($userId = null)
    {
        return self::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public static function checkRelationship($userId = null, $dataArray = null)
    {

        return self::where('user_id', $userId)
            ->where('target_id', $dataArray['target_id'])
            ->where('relationship_id', $dataArray['relationship_id'])
            ->exists();

    }

    public static function createAssignRelationships($userId = null, $dataArray = null)
    {

        return self::create([
            'user_id' => $userId,
            'target_id' => $dataArray['target_id'],
            'relationship_id' => $dataArray['relationship_id'],
        ]);

    }

    public static function deleteRelationship($targetId = null, $userId = null)
    {
        return self::where('user_id', $userId)->where('target_id', $targetId)->delete();
    }

}
