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
     * Reusable finder for a note by user + relation
     */
    public static function findUserNote(int $relationId, int $userId): ?self
    {
        return self::where('assign_relation_id', $relationId)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Optionally: Add a note (static helper method)
     */
    /**
     * Add a note
     */
    public static function addFamilyMatrixNote(int $userId, int $relationId, ?string $note = null): ?self
    {
        if (self::findUserNote($relationId, $userId)) {
            return null;
        }

        return self::create([
            'user_id'            => $userId,
            'assign_relation_id' => $relationId,
            'note'               => $note,
        ]);
    }


    /**
     * Get note for edit/display
     */
    public static function getNoteByRelationId(int $relationId, int $userId): ?self
    {
        return self::findUserNote($relationId, $userId);
    }



    /**
     * Update note
     */
    public static function updateFamilyMatrixNote(int $assignRelationId, int $userId, ?string $noteText): ?self
    {
        $note = self::findUserNote($assignRelationId, $userId);

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


    /**
     * Delete a note
     */
    public static function deleteFamilyMatrixNote(int $assignRelationId, int $userId): bool
    {
        $note = self::findUserNote($assignRelationId, $userId);

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
