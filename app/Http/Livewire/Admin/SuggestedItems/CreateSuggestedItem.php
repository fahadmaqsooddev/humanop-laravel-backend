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

    public $title, $description, $suggested_item_file, $booleanValue = false, $selectedTraits = [];

    protected $listeners = ['deleteSuggestedItemPermanently' => 'deleteSuggested'];

    protected $rules = [
        'title' => 'required|unique:humanop_shop_resources,heading|regex:/^[A-Za-z]/',
        'description' => 'required|string',
        'suggested_item_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp3,wav,mpeg|max:204800',
    ];

    protected $messages = [
        'title.required' => 'The title field is required.',
        'title.unique' => 'This title already exists in the shop resources.',
        'title.regex' => 'The title must start with an alphabet letter.',

        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be a valid text.',

        'suggested_item_file.required' => 'Please upload a suggested item file.',
        'suggested_item_file.file' => 'The suggested item must be a valid file.',
        'suggested_item_file.mimes' => 'Allowed file types: mp4, mov, avi, mkv, mp3, wav, pdf, doc, docx.',
        'suggested_item_file.max' => 'The file size may not be greater than 200 MB.',
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

        try {

            $this->validate();

            $extension = $this->suggested_item_file->extension();

            $upload_id = $this->uploadFile($this->suggested_item_file);

            if (in_array($extension, ['jpeg', 'png', 'jpg', 'gif'])) {

                $suggestedItem = SuggestedItem::createSuggestedItem($this->title, $this->description, $upload_id, null, null);

            } elseif (in_array($extension, ['mp3', 'wav', 'mpeg'])) {

                $suggestedItem = SuggestedItem::createSuggestedItem($this->title, $this->description, null, null, $upload_id);

            } else {

                $suggestedItem = SuggestedItem::createSuggestedItem($this->title, $this->description, null, $upload_id, null);

            }

            foreach ($this->selectedTraits as $traitCode) {

                HumanOpItemsGridActivitiesLog::storeSuggestedItemTraits($suggestedItem['id'], $traitCode);

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

        $this->reset(['title', 'suggested_item_file', 'description','selectedTraits']);
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
