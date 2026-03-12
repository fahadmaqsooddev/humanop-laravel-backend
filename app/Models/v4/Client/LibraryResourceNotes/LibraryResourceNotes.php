<?php

namespace App\Models\v4\Client\LibraryResourceNotes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryResourceNotes extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');

        parent::__construct($attributes);
    }

    public static function createLibraryResourceNote($data, $user_id)
    {
        return self::updateOrCreate(
            [
                'resource_id' => $data['resource_id'],
                'user_id'     => $user_id
            ],
            [
                'notes' => $data['notes']
            ]
        );
    }

    public static function getLibraryResourceNote($resource_id, $user_id)
    {
        return self::where('resource_id', $resource_id)
            ->where('user_id', $user_id)
            ->first();
    }
}
