<?php 
namespace App\Http\Livewire\Admin\Hotspots;

use Livewire\Component;
use App\Models\HotSpot;
use Illuminate\Validation\Rule;

class EditHotSpot extends Component
{
    public $select_hotspot;

    public function mount(HotSpot $hotspot)
    {
        $this->select_hotspot = $hotspot->toArray();
    }

    // ---------------- Validation rules ----------------
   protected function rules()
    {
        return [
            'select_hotspot.hotspot' => [
                'required',
                'string',
                'max:50',
                Rule::unique('hotspots', 'hotspot')
                    ->ignore($this->select_hotspot['id']),
            ],
            'select_hotspot.name' => 'required|string|max:255',
        ];
    }

    protected function messages()
    {
        return [
            'select_hotspot.hotspot.required' => 'Hotspot number is required',
            'select_hotspot.hotspot.unique'   => 'This hotspot number already exists',
            'select_hotspot.name.required'    => 'Public name is required',
        ];
    }


    // ---------------- Update function ----------------
    public function updateHotspot()
    {

        $this->validate();
        HotSpot::updateHotspot($this->select_hotspot);
        session()->flash('success', 'Hotspot updated successfully ✅');
    }

    public function render()
    {
        return view('livewire.admin.hotspots.edit-hotspot');
    }
}
