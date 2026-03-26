<?php

namespace App\Models\v4\Admin\LibraryResourceDocument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Upload\Upload;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
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
        $toDelete = collect();
        $filesToDelete = [];

        DB::transaction(function () use ($resourceId, $existingDocuments, $newDocuments, &$toDelete, &$filesToDelete) {

            $existingIds = collect($existingDocuments)->pluck('id')->filter()->values();

          
            if ($existingIds->isNotEmpty()) {
                $toDelete = self::where('resource_id', $resourceId)
                    ->whereNotIn('id', $existingIds)
                    ->get();
            } else {
                $toDelete = collect();
            }

            self::whereIn('id', $toDelete->pluck('id'))->delete();

            $existingDocRecords = self::whereIn('id', $existingIds)->get()->keyBy('id');

            foreach ($existingDocuments as $existingDocument) {

                if (!empty($existingDocument['id']) && isset($existingDocRecords[$existingDocument['id']])) {

                    $existingDocRecord = $existingDocRecords[$existingDocument['id']];

                    if (!empty($existingDocument['file'])) {

                        $uploadId = Upload::uploadFile($existingDocument['file'], '', '', 'document');

                        $oldFileId = $existingDocRecord->document_id;

                        $existingDocRecord->document_id = $uploadId;

                        if ($oldFileId) {
                            $filesToDelete[] = $oldFileId;
                        }
                    }

                    $existingDocRecord->download_document = $existingDocument['allow_download'] ?? false;

                    $existingDocRecord->save();
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

        });

       
        foreach ($toDelete as $doc) {
            if ($doc->document_id) {
                Upload::deleteFile($doc->document_id);
            }
        }

        foreach ($filesToDelete as $fileId) {
            Upload::deleteFile($fileId);
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
