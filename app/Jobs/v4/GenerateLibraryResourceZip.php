<?php
namespace App\Jobs\v4;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Admin\Resources\LibraryResource;
use ZipArchive;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GenerateLibraryResourceZip implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    protected $resourceId, $userId;

    public function __construct($resourceId, $userId)
    {
        $this->resourceId = $resourceId;
        $this->userId = $userId;
    }

    public function handle()
    {
        $cacheKey = "zip_generating_{$this->resourceId}_user_{$this->userId}";

        try {
            $resource = LibraryResource::with('documents.upload')->find($this->resourceId);

            $noDocsKey = "zip_no_documents_{$this->resourceId}_user_{$this->userId}";

            if (!$resource || $resource->documents->isEmpty()) {
                Cache::put($noDocsKey, true, now()->addMinutes(10));
                return;
            }

            $zipDir = storage_path('app/temp_zips');
            if (!file_exists($zipDir)) mkdir($zipDir, 0775, true);

            $zipPath = storage_path("app/temp_zips/resource_{$this->resourceId}_user_{$this->userId}.zip");

            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception("Cannot create ZIP");
            }

            foreach ($resource->documents as $doc) {
                $upload = $doc->upload;
                if (!$upload) continue;

                $filePath = public_path("media/documents/{$upload->hash}/{$upload->name}");
                if (!file_exists($filePath)) continue;

                $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', 
                    ($doc->heading ?? pathinfo($upload->name, PATHINFO_FILENAME))
                ) . '.' . pathinfo($upload->name, PATHINFO_EXTENSION);

                $zip->addFile($filePath, $fileName);
            }

            $zip->close();

        } catch (\Exception $e) {
            Log::error("ZIP generation failed", [
                'resource_id' => $this->resourceId,
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;

        } finally {
            Cache::forget($cacheKey);
        }
    }

    public function failed(\Exception $e)
    {
    
        $cacheKey = "zip_generating_{$this->resourceId}_user_{$this->userId}";
        Cache::forget($cacheKey);
    }
}