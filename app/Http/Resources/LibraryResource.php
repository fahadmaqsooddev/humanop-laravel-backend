<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibraryResource extends JsonResource
{
    public function toArray($request)
    {
        $userNote = $this->whenLoaded('notes');
        return [
            "id" => $this->id,
            "heading" => $this->heading,
            "my_playlist" => 0,
            "slug" => $this->slug,
            "description" => $this->description,
            "content" => $this->content,
            "relevance" => $this->relevance,

            "photo_url" => $this->photo_url,
            "video_url" => $this->video_url,
            "audio_url" => $this->audio_url,

            "thumbnail_url" => data_get($this->thumbnail_url,'url'),
            "document_url" => data_get($this->document_url,'path'),

            "allow_download" => (bool) $this->download_document,

            "resource_category_name" => $this->resourceCategory?->name,

            "library_permission_name" => $this->libraryPermissions?->permission,
            "library_permission_allow" => true,

            "price" => $this->libraryPermissions?->price ?? 0,
            "point" => $this->libraryPermissions?->point ?? 0,
            "note" => $userNote?->notes,
            "note_id" => $userNote?->id
        ];
    }
}