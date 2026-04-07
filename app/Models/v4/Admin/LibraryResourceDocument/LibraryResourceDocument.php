<?php

namespace App\Models\v4\Admin\LibraryResourceDocument;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Upload\Upload;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\DB;
use App\Jobs\v4\GenerateLibraryResourceZip;
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

            $uploadId = null;

            try {
              
                $uploadId = Upload::uploadFile($doc['file'], '', '', 'document');

               
                self::create([
                    'resource_id' => $resourceId,
                    'document_id' => $uploadId,
                    'download_document' => $doc['allow_download'] ?? false,
                ]);

            } catch (\Throwable $e) {

                if ($uploadId) {
                    try {
                        Upload::deleteFile($uploadId);
                    } catch (\Throwable $deleteException) {
                        Log::error('Failed to delete orphan upload', [
                            'upload_id' => $uploadId,
                            'error' => $deleteException->getMessage(),
                        ]);
                    }
                }

                throw $e;
            }
        }

        GenerateLibraryResourceZip::dispatch($resourceId);
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
        $filesToDelete = [];

        DB::transaction(function () use ($resourceId, $existingDocuments, $newDocuments, &$filesToDelete) {

            // Get existing IDs
            $existingIds = collect($existingDocuments)->pluck('id')->filter()->values();

            // Find records to delete
            $toDeleteQuery = self::where('resource_id', $resourceId);
            if ($existingIds->isNotEmpty()) {
                $toDeleteQuery->whereNotIn('id', $existingIds);
            }

            $toDelete = $toDeleteQuery->get();

            // Collect files to delete later
            $filesToDelete = $toDelete->pluck('document_id')->filter()->all();

            // Delete DB records
            self::whereIn('id', $toDelete->pluck('id'))->delete();

            // Fetch existing records
            $existingDocRecords = self::whereIn('id', $existingIds)->get()->keyBy('id');

            // 🔹 Handle existing documents
            foreach ($existingDocuments as $existingDocument) {

                if (!empty($existingDocument['id']) && isset($existingDocRecords[$existingDocument['id']])) {

                    $record = $existingDocRecords[$existingDocument['id']];

                    // Replace file if new file uploaded
                    if (!empty($existingDocument['file'])) {

                        $oldFileId = $record->document_id;

                        $uploadId = Upload::uploadFile($existingDocument['file'], '', '', 'document');

                        $record->document_id = $uploadId;

                        if ($oldFileId) {
                            $filesToDelete[] = $oldFileId;
                        }
                    }

                    $record->download_document = $existingDocument['allow_download'] ?? false;
                    $record->save();
                }
            }

            // 🔹 Add new documents
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

            // ✅ Run ZIP rebuild ONLY after transaction commits
            DB::afterCommit(function () use ($resourceId) {

                app(self::class)->rebuildZip($resourceId);

                LibraryDocumentZip::updateOrCreate(
                    ['resource_id' => $resourceId],
                    ['path' => "resource_zips/resource_{$resourceId}.zip"]
                );
            });
        });

        // 🔹 Delete old files AFTER transaction
        foreach ($filesToDelete as $fileId) {
            if ($fileId) {
                try {
                    Upload::deleteFile($fileId);
                } catch (\Exception $e) {
                    report($e);
                }
            }
        }
    }


    public function rebuildZip(int $resourceId): void
    {
        $zipDir = storage_path('app/resource_zips');
        
        if (!is_dir($zipDir)) {
            if (!mkdir($zipDir, 0775, true) && !is_dir($zipDir)) {
                throw new \Exception("Unable to create ZIP directory: {$zipDir}");
            }
        }

        $zipPath = $zipDir . "/resource_{$resourceId}.zip";

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new \Exception("Unable to open ZIP file: {$zipPath}");
        }

        $documents = self::where('resource_id', $resourceId)->get();

        foreach ($documents as $doc) {
            if ($doc->document_id) {
                $upload = Upload::find($doc->document_id);

                if ($upload && file_exists($upload->path)) {
                    $zip->addFile($upload->path, basename($upload->name));
                }
            }
        }

        $zip->close();
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
