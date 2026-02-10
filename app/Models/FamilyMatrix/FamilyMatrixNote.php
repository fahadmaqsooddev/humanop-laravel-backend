<?php

namespace App\Models\FamilyMatrix;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMatrixNote extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    /**
     * Optionally: Add a note (static helper method)
     */
    public static function addFamilyMatrixNote(int $userId, int $relationId, ?string $note = null): ?self
    {

        $existingNote = self::where('assign_relation_id', $relationId)->first();

        if ($existingNote) {
            return null;
        }


        return self::create([
            'user_id'            => $userId,
            'assign_relation_id' => $relationId,
            'note'               => $note,
        ]);
    }


    public static function getNoteByRelationId(int $relationId, int $userId)
    {
        return self::where('assign_relation_id', $relationId)
            ->where('user_id', $userId)
            ->first();
    }


    public static function updateFamilyMatrixNote(
        int $assignRelationId,
        int $userId,
        ?string $noteText
    ): ?self {

        $note = self::getNoteByRelationId($assignRelationId, $userId);

        if (!$note) {
            return null;
        }


        $note->note = $noteText;

        if ($note->isDirty('note')) {
            $note->save();
        }

        return $note;
    }





    /**
     * Delete a note based on user_id and assign_relation_id
     */


    public static function deleteFamilyMatrixNote(int $assignRelationId, int $userId): bool
    {
        $note = self::where('assign_relation_id', $assignRelationId)
            ->where('user_id', $userId)
            ->first();

        if (!$note) {
            return false;
        }

        return (bool) $note->delete();
    }


    public static function getAllNotes($user_id)
    {
        return self::where('user_id',$user_id)
        ->orderBy('created_at', 'desc')->get();
    }
}
