<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PlaylistLog;
use App\Enums\Admin\Admin;

class LibraryResource extends JsonResource
{
    protected $user;

    public function __construct($resource, $user)
    {
        parent::__construct($resource);
        $this->user = $user;
    }

    public function toArray($request)
    {
      
        $playList = PlaylistLog::getSingleResourceItem($this->id);

       
        $libraryPermission = optional($this->libraryPermissions);
        $permission = $libraryPermission->permission ?? null;
        $basePrice = (int) ($libraryPermission->price ?? 0);

      
        $finalPrice = ($this->user->plan_name === Admin::PREMIUM_PLAN_NAME && $basePrice)
            ? $basePrice * 0.5
            : $basePrice;

      
        $libraryPermissionName = match ($permission) {
            1 => Admin::FREEMIUM_TEXT,
            2 => Admin::BETA_BREAKER_TEXT,
            3 => Admin::PREMIUM_PLAN_NAME,
            4 => 'Freemium Only',
            5 => 'Beta Breaker Only',
            6 => 'Premium Only',
            default => null,
        };

        // Permission allow logic
        $libraryPermissionAllow = match ($permission) {
            1 => true, // Freemium
            4 => $this->user->plan_name === Admin::FREEMIUM_TEXT,
            2 => in_array($this->user->plan_name, [Admin::BETA_BREAKER_TEXT, Admin::PREMIUM_PLAN_NAME]),
            5 => $this->user->plan_name === Admin::BETA_BREAKER_TEXT,
            3 => true, // Premium
            6 => $this->user->plan_name === Admin::PREMIUM_PLAN_NAME,
            default => false,
        };

        return [
            "id" => $this->id,
            "heading" => $this->heading,
            "slug" => $this->slug,
            "description" => $this->description,
            "content" => $this->content,
            "relevance" => $this->relevance,

            "photo_url" => $this->photo_url,
            "video_url" => $this->video_url,
            "audio_url" => $this->audio_url,

            "thumbnail_url" => data_get($this->thumbnail_url, 'url'),
            "document_url" => data_get($this->document_url, 'path'),

            "allow_download" => (bool) $this->download_document,
            "resource_category_name" => $this->resourceCategory?->name,

            "library_permission_name" => $libraryPermissionName,
            "library_permission_allow" => $libraryPermissionAllow,

            "price" => $finalPrice,
            "point" => (int) ($libraryPermission->point ?? 0),

            "my_playlist" => !empty($playList) ? 1 : 0,

            "note" => optional($this->notes)->notes,
            "note_id" => optional($this->notes)->id,
        ];
    }
}