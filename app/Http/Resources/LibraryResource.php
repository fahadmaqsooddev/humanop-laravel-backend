<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PlaylistLog;
use App\Enums\Admin\Admin;
use App\Helpers\Helpers;
class LibraryResource extends JsonResource
{


    public function toArray($request)
    {

        $user = $this->additional['user'] ?? null;

       
        if (!$user) {
          
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
                "document_url" =>  $this->document_urls,
                "allow_download" => (bool) $this->download_document,
                "resource_category_name" => $this->resourceCategory?->name,
                "library_permission_name" => null,
                "library_permission_allow" => false,
                "price" => 0,
                "point" => 0,
                "my_playlist" => 0,
                "note" => null,
                "note_id" => null,
            ];
        }
       
        $libraryPermission = optional($this->libraryPermissions);
        $permission = $libraryPermission->permission ?? null;
        $basePrice = (int) ($libraryPermission->price ?? 0);

      
        $finalPrice = ($user->plan_name === Admin::PREMIUM_PLAN_NAME && $basePrice)
            ? $basePrice * 0.5
            : $basePrice;

            
        $points = (int) ($libraryPermission->point ?? 0);
      
        $libraryPermissionName = match ($permission) {
            Admin::FREEMIUM_PLAN => Admin::FREEMIUM_TEXT,
            Admin::BETA_BREAKER_PLAN => Admin::BETA_BREAKER_TEXT,
            Admin::PREMIUM_PLAN => Admin::PREMIUM_PLAN_NAME,
            Admin::PERMISSION_FREEMIUM_ONLY => Admin::FREEMIUM_ONLY_TEXT,
            Admin::PERMISSION_BETA_BREAKER_ONLY => Admin::BETA_BREAKER_ONLY_TEXT,
            Admin::PERMISSION_PREMIUM_ONLY => Admin::PREMIUM_ONLY_TEXT,
            default => null,
        };

        $libraryPermissionAllow = match ($permission) {
            Admin::PERMISSION_FREEMIUM => true, // Freemium
            Admin::PERMISSION_FREEMIUM_ONLY => $user->plan_name === Admin::FREEMIUM_TEXT,
            Admin::PERMISSION_BETA_BREAKER => in_array($user->plan_name, [Admin::BETA_BREAKER_TEXT, Admin::PREMIUM_PLAN_NAME]),
            Admin::PERMISSION_BETA_BREAKER_ONLY => $user->plan_name === Admin::BETA_BREAKER_TEXT,
            Admin::PERMISSION_PREMIUM => true, // Premium
            Admin::PERMISSION_PREMIUM_ONLY => $user->plan_name === Admin::PREMIUM_PLAN_NAME,
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
            "document_urls" => $this->document_urls,
            "allow_download" => (bool) $this->download_document,
            "resource_category_name" => $this->resourceCategory?->name,
            "library_permission_name" => $libraryPermissionName,
            "library_permission_allow" => $libraryPermissionAllow && $finalPrice === 0 && $points === 0,
            "price" => $finalPrice,
            "point" => $points,
           'my_playlist' => $this->playlistLogs->isNotEmpty() ? 1 : 0,
            "note" => optional($this->notes)->notes,
            "note_id" => optional($this->notes)->id,
        ];
    }
}