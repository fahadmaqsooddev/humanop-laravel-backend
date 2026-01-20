<?php

namespace App\Http\Livewire\Admin\Hotspots;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\HotSpot;
class Hotspots extends Component
{
    public $hotspots=[];

    public function render()
    {
        $this->hotspots = HotSpot::all();
        return view('livewire.admin.hotspots.hotspots', ['hotspots' => $this->hotspots]);

    }
}
