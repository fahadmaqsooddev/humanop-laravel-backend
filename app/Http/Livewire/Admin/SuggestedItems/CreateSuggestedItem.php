<?php

namespace App\Http\Livewire\Admin\SuggestedItems;

use App\Helpers\Helpers;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\SuggestedItem\SuggestedItem;
use App\Models\Admin\SuggestedItem\SuggestedItemTrait;
use App\Models\Upload\Upload;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSuggestedItem extends Component
{

    use WithFileUploads;

    public $module_type, $title, $description, $suggested_item_file, $booleanValue = false, $selectedTraits = [], $selectedFeatures = [], $selectedAlchemy = [], $selectedCommunications = [], $selectedPerceptions = [], $selectedEnergyPools = [];

    protected $listeners = ['deleteSuggestedItemPermanently' => 'deleteSuggested'];

    protected $rules = [
        'title' => 'required|unique:humanop_shop_resources,heading|regex:/^[A-Za-z]/',
        'description' => 'required|string',
        'module_type' => 'required|in:tool_training,humanop_shop,video_result,sound_track_library,hai_chat,support,humanop_network,humanop_integration,reward_hb',
//        'suggested_item_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv,mp3,wav|max:204800',
    ];

    protected $messages = [
        'title.required' => 'The title field is required.',
        'title.unique' => 'This title already exists in the shop resources.',
        'title.regex' => 'The title must start with an alphabet letter.',

        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be a valid text.',

        'module_type.required' => 'Please Select any module.',
        'module_type.in' => 'Selected module is invalid.',

//        'suggested_item_file.required' => 'Please upload a suggested item file.',
//        'suggested_item_file.file' => 'The suggested item must be a valid file.',
//        'suggested_item_file.mimes' => 'Allowed file types: mp4, mov, avi, mkv, mp3, wav, pdf, doc, docx.',
//        'suggested_item_file.max' => 'The file size may not be greater than 200 MB.',
    ];

    public function getSuggestedItems()
    {

        return SuggestedItem::all();

    }

    public function uploadFile($suggestedItemFile = null)
    {

        if (!empty($suggestedItemFile)) {

            if (in_array($suggestedItemFile->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {

                return Upload::uploadFile($suggestedItemFile, 200, 200, 'base64Image', 'png', true);

            } elseif (in_array($suggestedItemFile->extension(), ['mp3', 'wav', 'mpeg'])) {

                return Upload::uploadFile($suggestedItemFile, '', '', 'audio');

            } else {

                return Upload::uploadFile($suggestedItemFile, '', '', 'video');

            }

        } else {

            return null;

        }
    }

    public function createSuggestedItem()
    {

        DB::beginTransaction();

//        dd($this);
        try {

            $this->validate();

            $extension = $this->suggested_item_file ? $this->suggested_item_file->extension() : null;

            $upload_id = $this->suggested_item_file ? $this->uploadFile($this->suggested_item_file) : null;

            if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif', null])) {

                $suggestedItem = SuggestedItem::createSuggestedItem($this->module_type, $this->title, $this->description, $upload_id, null, null);

            } elseif (in_array($extension, ['mp3', 'wav', 'mpeg', null])) {

                $suggestedItem = SuggestedItem::createSuggestedItem($this->module_type, $this->title, $this->description, null, null, $upload_id);

            } else {

                $suggestedItem = SuggestedItem::createSuggestedItem($this->module_type, $this->title, $this->description, null, $upload_id, null);

            }

            foreach ($this->selectedTraits as $traitCode) {
                HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $traitCode);
            }

            foreach ($this->selectedFeatures as $featureCode) {
                HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $featureCode);
            }

            foreach ($this->selectedAlchemy as $alchemyCode) {
                HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $alchemyCode);
            }

            foreach ($this->selectedCommunications as $communicationCode) {
                HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $communicationCode);
            }

            $perceptionCodes = [
                'Negative' => 'NE',
                'Positive' => 'P',
                'Neutral' => 'N',
            ];

            foreach ($this->selectedPerceptions as $perception) {
                if (isset($perceptionCodes[$perception])) {
                    HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $perceptionCodes[$perception]);
                }
            }

            $energyPoolCodes = [
                'Above Excellent' => 'AE',
                'Average' => 'A',
                'Excellent' => 'E',
                'Fair' => 'F',
            ];

            foreach ($this->selectedEnergyPools as $energyPoolCode) {
                if (isset($energyPoolCodes[$energyPoolCode])) {
                    HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $energyPoolCodes[$energyPoolCode]);
                }
            }

            $this->resetForm();

            DB::commit();

            session()->flash('success', 'Suggested Item created successfully.');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function getSuggestedFile()
    {
        $this->booleanValue = false;

    }

    public function resetForm()
    {
        $this->booleanValue = false;

        $this->reset(['title', 'suggested_item_file', 'description', 'selectedTraits']);
    }

    public function emptyCreateForm()
    {
        $this->title = '';
        $this->description = '';

        $this->suggested_item_file = '';
        $this->permission = '';
    }

    public function deleteSuggested($id)
    {

        $suggestedItem = SuggestedItem::getItem($id);

        HumanOpItemsGridActivitiesLog::deleteSuggestItems($suggestedItem['id']);

        $suggestedItem->delete();

        return Helpers::successResponse(__('Suggested Item deleted successfully.'));

    }

    public function render()
    {
        return view('livewire.admin.suggested-items.create-suggested-item', ['suggestedItems' => $this->getSuggestedItems()]);
    }
}
