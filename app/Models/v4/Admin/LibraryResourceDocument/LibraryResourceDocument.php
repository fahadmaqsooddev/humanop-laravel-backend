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

    public static function storeDocuments($documents, $resourceId)
    {

        foreach ($documents as $doc) {

            Log::info("Store Documents");
            if (!empty($doc['file'])) {

                $upload_id = Upload::uploadFile($doc['file'], '', '', 'document');

                self::create([
                    'resource_id' => $resourceId,
                    'document_id' => $upload_id,
                    'download_document' => $doc['allow_download'] ?? false,
                ]);
            }
        }
    }

    public static function getDocumentsByResource($resourceId)
    {
       
        $documents = self::where('resource_id', $resourceId)->get();

       
        $documentIds = $documents->pluck('document_id')->filter()->all();

       
        $uploads = Upload::whereIn('id', $documentIds)->get()->keyBy('id');

       
        return $documents->map(function ($doc) use ($uploads) {

            $fileUrl = null;

            if ($doc->document_id && isset($uploads[$doc->document_id])) {
                $upload = $uploads[$doc->document_id];

              
                if ($upload->extension === 'pdf') {
                    $fileUrl = url('/') . '/media/documents/' . $upload->hash . '/' . $upload->name;
                }
            }

            return [
                'id' => $doc->id,
                'file' => null,
                'document_id' => $doc->document_id,
                'allow_download' => (bool) $doc->download_document,
                'file_path' => $fileUrl,
                'file_name' => $doc->upload?->name ?? null, // optional, already loaded relation
            ];
        })->toArray();
    }

   
    public static function updateDocuments(int $resourceId, array $existingDocuments, array $newDocuments = [])
    {
       
        foreach ($existingDocuments as $existingDocument) {

            if (!empty($existingDocument['id'])) {
                $existingDocRecord = self::find($existingDocument['id']);

                if ($existingDocRecord) {
                    if (!empty($existingDocument['file'])) {
                      
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
        if (!$this->relationLoaded('upload') || !$this->upload) {
            return null;
        }

        if ($this->upload->extension !== 'pdf') return null;

        return url('/') . '/media/documents/' . $this->upload->hash . '/' . $this->upload->name;
    }
}
