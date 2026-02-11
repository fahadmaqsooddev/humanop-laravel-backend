<?php

namespace App\Http\Livewire\Admin\FamilyMatrix;

use Livewire\Component;
use App\Models\FamilyMatrix\FamilyMatrixConfiguration as FamilyMatrixConfigurationModel;
class FamilyMatrixConfiguration extends Component
{

    public $configurations=[];
    public $configText;
    public $configId;


    public function getFamilyMatrixConfiguration()
    {
        try {

            return FamilyMatrixConfigurationModel::getConfigurations();

        } catch (\Exception $exception) {

            session()->flash('error', $exception->getMessage());
        }

    }
    public function editConfigurationModal($id, $text)
    {
        $this->configId = $id;
        $this->configText = $text;

        $this->dispatchBrowserEvent('show-edit-modal');
    }

    public function validateConfigurationText()
    {
        return $this->validate([
            'configText' => 'required|string|max:1000',
        ], [
            'configText.required' => 'Configuration text is required.',
            'configText.string'   => 'Configuration text must be a string.',
            'configText.max'      => 'Configuration text may not be greater than 1000 characters.',
        ]);
    }

    public function updateConfiguration()
    {
        // Call validation
        $validated = $this->validateConfigurationText();

        // Update using model method
        $updated = FamilyMatrixConfigurationModel::updateConfigurationText($this->configId, $validated['configText']);

        if (!$updated) {
            session()->flash('error', 'Configuration not found');
            return;
        }

        session()->flash('success', 'Configuration updated successfully!');
        $this->emit('refreshComponent'); // Refresh table if needed
        $this->dispatchBrowserEvent('hide-edit-modal');
    }


    public function render()
    {
        $this->configurations = $this->getFamilyMatrixConfiguration();
        return view('livewire.admin.family-matrix.family-matrix-configuration');
    }

}
