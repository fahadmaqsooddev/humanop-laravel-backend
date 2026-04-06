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
use App\Models\v4\Admin\LibraryResourceDocument\LibraryDocumentZip;


class GenerateLibraryResourceZip implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    protected $resourceId;

    public function __construct($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    public function handle()
    {
        $cacheKey = "zip_generating_{$this->resourceId}";

        try {
            $resource = LibraryResource::with('documents.upload')->find($this->resourceId);

            $noDocsKey = "zip_no_documents_{$this->resourceId}";

            if (!$resource || $resource->documents->isEmpty()) {
                Cache::put($noDocsKey, true, now()->addMinutes(10));
                return;
            }

            $zipDir = storage_path('app/resource_zips');
            if (!file_exists($zipDir)) mkdir($zipDir, 0775, true);

            $zipPath = storage_path("app/resource_zips/resource_{$this->resourceId}.zip");

            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception("Cannot create ZIP");
            }

            foreach ($resource->documents as $doc) {


                $upload = $doc->upload;
                if (!$upload) continue;

                $fileUrl = $upload->path;

                if (file_exists($fileUrl)) {
                    $zip->addFile($fileUrl, basename($upload->name)); // Add to zip
                } else {
                    Log::warning("File missing for ZIP", ['file' => $fileUrl]);
                }

            }

             $zip->close();

            LibraryDocumentZip::updateOrCreate(
                ['resource_id' => $this->resourceId],
                ['path' => 'resource_zips/resource_' . $this->resourceId . '.zip']
            );

        } catch (\Exception $e) {
            Log::error("ZIP generation failed", [
                'resource_id' => $this->resourceId,
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
    
        $cacheKey = "zip_generating_{$this->resourceId}";
        Cache::forget($cacheKey);
    }
}