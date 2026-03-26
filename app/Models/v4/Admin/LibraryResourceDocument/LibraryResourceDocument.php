<?php

namespace App\Models\v4\Admin\LibraryResourceDocument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Upload\Upload;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
class LibraryResourceDocument extends Model
{
    use HasFactory;

    public function __construct(array $attributes = array())
    {
        $this->table = config('database.models.' . class_basename(__CLASS__) . '.table');
        $this->fillable = config('database.models.' . class_basename(__CLASS__) . '.fillable');
        $this->hidden = config('database.models.' . class_basename(__CLASS__) . '.hidden');
        parent::__construct($attributes);
    }

    public static function storeDocuments(array $documents, int $resourceId): void
    {
        foreach ($documents as $doc) {
            if (empty($doc['file'])) continue;

            try {
                $uploadId = Upload::uploadFile($doc['file'], '', '', 'document');

                self::create([
                    'resource_id' => $resourceId,
                    'document_id' => $uploadId,
                    'download_document' => $doc['allow_download'] ?? false,
                ]);

            } catch (\Throwable $e) {
                Log::error('Document upload failed', [
                    'resource_id' => $resourceId,
                    'error' => $e->getMessage()
                ]);

                throw $e; 
            }
        }
    }

    public static function getDocumentsByResource($resourceId)
    {
        $documents = self::where('resource_id', $resourceId)->get();

        $documentIds = $documents->pluck('document_id')->filter()->all();

        $uploads = Upload::whereIn('id', $documentIds)->get()->keyBy('id');

        return $documents->map(function ($doc) use ($uploads) {

            $upload = $uploads[$doc->document_id] ?? null;

            return [
                'id' => $doc->id,
                'document_id' => $doc->document_id,
                'allow_download' => (bool) $doc->download_document,
                'file_path' => $upload 
                    ? asset("media/documents/{$upload->hash}/{$upload->name}") 
                    : null,
                'file_name' => $upload?->name,
            ];
        })->toArray();
    }

   
    public static function updateDocuments(int $resourceId, array $existingDocuments, array $newDocuments = [])
    {


        $existingIds = collect($existingDocuments)->pluck('id')->filter()->values();

        $query = self::where('resource_id', $resourceId);

        if ($existingIds->isNotEmpty()) {
            $query->whereNotIn('id', $existingIds);
        }

        $toDelete = $query->get();

        foreach ($toDelete as $doc) {
            if ($doc->document_id) {
                Upload::deleteFile($doc->document_id);
            }
        }

        self::whereIn('id', $toDelete->pluck('id'))->delete();
       
        foreach ($existingDocuments as $existingDocument) {

            if (!empty($existingDocument['id'])) {
                $existingDocRecord = self::find($existingDocument['id']);

                if ($existingDocRecord) {
                    if (!empty($existingDocument['file'])) {

                        if ($existingDocRecord->document_id) {
                            Upload::deleteFile($existingDocRecord->document_id);
                        }

                        $uploadId = Upload::uploadFile($existingDocument['file'], '', '', 'document');
                        $existingDocRecord->document_id = $uploadId;
                    }

                    $existingDocRecord->download_document = $existingDocument['allow_download'] ?? false;

                    $existingDocRecord->save();
                }
            }
        }

        
        foreach ($newDocuments as $doc) {
            if (!empty($doc['file'])) {
                $uploadId = Upload::uploadFile($doc['file'], '', '', 'document');

                self::create([
                    'resource_id' => $resourceId,
                    'document_id' => $uploadId,
                    'download_document' => $doc['allow_download'] ?? false,
                ]);
            }
        }
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'document_id', 'id');
    }


    public function getDocumentUrlAttribute()
    {
        $upload = $this->getRelationValue('upload');

        if (!$upload) {
            return null;
        }

        return asset("media/documents/{$upload->hash}/{$upload->name}");
    }
}
