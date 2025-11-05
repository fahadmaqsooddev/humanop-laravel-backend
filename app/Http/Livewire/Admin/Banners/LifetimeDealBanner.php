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
        'start_date' => '',
        'end_date' => '',
        'is_active' => false,
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
                'start_date' => $banner->start_date,
                'end_date' => $banner->end_date,
                'status' => (bool)$banner->status,
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
            'banner.start_date' => 'required|date',
            'banner.end_date' => 'required|date|after_or_equal:banner.start_date',
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
