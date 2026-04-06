<?php

namespace App\Models\v4\Admin\LibraryResourceDocument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LibraryDocumentZip extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function deleteZipByResource($resourceId)
    {
        try {

            $zip = self::where('resource_id', $resourceId)->first();

            if (!$zip) {
                return;
            }

            $fullPath = storage_path('app/' . $zip->path);

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            $zip->delete();

        } catch (\Throwable $e) {
            Log::error("Failed to delete ZIP", [
                'resource_id' => $resourceId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
