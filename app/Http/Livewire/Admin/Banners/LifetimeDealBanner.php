<?php

namespace App\Http\Livewire\Admin\Banners;

use Livewire\Component;
use App\Models\Admin\LifeTimeDeal\LifetimeDealBanner as BannerModel;

class LifetimeDealBanner extends Component
{

    public $banner = [
        'title_for_beta_breaker' => '',
        'description_for_beta_breaker' => '',
        'title_for_freemium' => '',
        'description_for_freemium' => '',
        'shared_title' => '',
        'shared_description' => '',
        'freemium_url' => '',
        'beta_breaker_url' => '',
        'visible_on_mobile' => false,  // DB column
        'visible_on_web' => false,     // DB column
        'start_date' => '',
        'end_date' => '',
        'is_active' => false, // dummy field for now
    ];

    public $select_code = [
        'id' => null,
    ];

    public $bannerId;

    public function mount()
    {

        $banner = BannerModel::latest()->first();

        if ($banner) {

            $this->bannerId = $banner['id'];

            $this->select_code['id'] = $banner->id;
            $this->banner = [
                'title_for_beta_breaker' => $banner->title_for_beta_breaker,
                'description_for_beta_breaker' => $banner->description_for_beta_breaker,
                'title_for_freemium' => $banner->title_for_freemium,
                'description_for_freemium' => $banner->description_for_freemium,
                'shared_title' => $banner->shared_title ?? '',
                'shared_description' => $banner->shared_description ?? '',
                'freemium_url' => $banner->freemium_url ?? '',
                'beta_breaker_url' => $banner->beta_breaker_url ?? '',
                'visible_on_mobile' => (bool) ($banner->visible_on_mobile ?? false),
                'visible_on_web' => (bool) ($banner->visible_on_web ?? false),
                'start_date' => $banner->start_date,
                'end_date' => $banner->end_date,
                'status' => (bool)$banner->status,
                'is_active' => (bool)($banner->is_active ?? false), // dummy field
            ];
        }
    }

    public function updateIntro()
    {
        $validatedData = $this->validate([
            'banner.title_for_beta_breaker' => 'required|string|max:255',
            'banner.description_for_beta_breaker' => 'required|string',
            'banner.title_for_freemium' => 'required|string|max:255',
            'banner.description_for_freemium' => 'required|string',
            'banner.shared_title' => 'required|string|max:255',
            'banner.shared_description' => 'required|string',
            'banner.freemium_url' => 'required|url|max:255',
            'banner.beta_breaker_url' => 'required|url|max:255',
            'banner.visible_on_mobile' => 'boolean',
            'banner.visible_on_web' => 'boolean',
            'banner.start_date' => 'nullable|date',
            'banner.end_date' => 'nullable|date|after_or_equal:banner.start_date',
            'banner.is_active' => 'boolean',
        ]);


        BannerModel::createOrUpdateBanner(['id' => $this->select_code['id']], $validatedData['banner']);

        session()->flash('success', 'Banner updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.banners.lifetime-deal-banner');
    }
}
