<?php

namespace App\Http\Livewire\Admin\Resource;

use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\HumanOpShopCategory\HumanOpShopTraits;
use App\Models\Admin\HumanOpShopCategory\ShopCategory;
use App\Models\Admin\Resources\ShopCategoryResource;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Upload\Upload;


class CreateHumanOpShop extends Component
{
    use WithFileUploads;

    public $booleanValue = false;

    public $description,$resourceId, $pointValue, $priceValue, $current_category, $resourceSlug, $heading, $update_content, $resource_file, $category_id, $editResourceData, $category_name;
    public $selectedTraits = [], $selectedFeatures = [], $selectedAlchemy = [], $selectedCommunications = [], $selectedPerceptions = [], $selectedEnergyPools = [];

    protected $listeners = ['toggleCreateResourceModal' => 'resetForm', 'toggleShowResourceModal' => 'handleRefreshQuery', 'deleteCategoryPermanently' => 'deleteCategory', 'fileChanged'];


    protected $rules = [
        'heading' => 'required|unique:humanop_shop_resources,heading|regex:/^[A-Za-z]/',
        'description' => 'required|string',
        'resource_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv,mp3,wav,pdf,doc,docx|max:204800', // Max file size 200MB
        'category_id' => 'required|exists:humanop_shop_categories,id',
        'pointValue' => 'required_without:priceValue|nullable|numeric|min:0',
        'priceValue' => 'required_without:pointValue|nullable|numeric|min:0',

    ];

    protected $messages = [
        'heading.required' => 'Heading is required.',
        'heading.regex' => 'The heading must start with a letter.',
        'heading.unique' => 'The heading must be unique in the library resources.',
        'resource_file.mimes' => 'The resource file must be a valid type: MP4, MOV, AVI, MKV (video), MP3, WAV (audio), or PDF (document).',
        'resource_file.max' => 'The resource file size must not exceed 200MB.',
        'description.required' => 'The description field is required.',
        'description.string' => 'The description must be valid text.',
        'category_id.required' => 'Category is required.',
        'category_id.exists' => 'The selected category does not exist.',
        'pointValue.required_without' => 'Either point value or price value must be provided.',
        'priceValue.required_without' => 'Either point value or price value must be provided.',

    ];

    public function createShopResource()
    {

        DB::beginTransaction();

        try {

            $this->validate();

            $extension = $this->resource_file->extension();

            $upload_id = $this->uploadFile($this->resource_file);

            $resource = null;
            if (in_array($extension, ['mp3', 'wav', 'mpeg'])) {

                $resource = ShopCategoryResource::createShopResource($this->heading, $this->category_id, $this->priceValue, null, $upload_id, null, null, $this->pointValue,$this->description);

            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {

                $resource = ShopCategoryResource::createShopResource($this->heading, $this->category_id, $this->priceValue, $upload_id, null, null, null, $this->pointValue,$this->description);

            }elseif (in_array($extension, ['jpeg', 'png', 'jpg', 'gif'])) {

                $resource = ShopCategoryResource::createShopResource($this->heading, $this->category_id, $this->priceValue, null, null, null, $upload_id, $this->pointValue,$this->description);

            } else {

                $resource = ShopCategoryResource::createShopResource($this->heading, $this->category_id, $this->priceValue, null, null, $upload_id, null, $this->pointValue,$this->description);

            }

            foreach ($this->selectedTraits as $traitCode) {
                HumanOpItemsGridActivitiesLog::storeShopItemTraits($resource['id'], $traitCode);
            }

            foreach ($this->selectedFeatures as $featureCode) {
                HumanOpItemsGridActivitiesLog::storeShopItemTraits($resource['id'], $featureCode);
            }

            foreach ($this->selectedAlchemy as $alchemyCode) {
                HumanOpItemsGridActivitiesLog::storeShopItemTraits($resource['id'], $alchemyCode);
            }

            foreach ($this->selectedCommunications as $communicationCode) {
                HumanOpItemsGridActivitiesLog::storeShopItemTraits($resource['id'], $communicationCode);
            }

            $perceptionCodes = [
                'Negative' => 'NE',
                'Positive' => 'P',
                'Neutral'  => 'N',
            ];

            foreach ($this->selectedPerceptions as $perception) {
                if (isset($perceptionCodes[$perception])) {
                    HumanOpItemsGridActivitiesLog::storeShopItemTraits($resource['id'], $perceptionCodes[$perception]);
                }
            }

            $energyPoolCodes = [
                'Above Excellent' => 'AE',
                'Average' => 'A',
                'Excellent'  => 'E',
                'Fair'  => 'F',
            ];

            foreach ($this->selectedEnergyPools as $energyPoolCode) {
                if (isset($energyPoolCodes[$energyPoolCode])) {
                    HumanOpItemsGridActivitiesLog::storeShopItemTraits($resource['id'], $energyPoolCodes[$energyPoolCode]);
                }
            }

            $this->resetForm();

            DB::commit();

            session()->flash('success', 'HumanOp Shop resource created successfully.');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function getResourceFile()
    {
        $this->booleanValue = false;
    }

    public function deleteResource($id)
    {
        try {

            DB::beginTransaction();

            ShopCategoryResource::deleteResource($id);

            $this->resetForm();

            $this->emit('toggleShowResourceModal');

            DB::commit();

            session()->flash('success', 'shop resource deleted successfully.');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }
    }

    public function resetForm()
    {
        $this->booleanValue = false;

        $this->reset(['heading', 'resource_file', 'pointValue', 'resource_file', 'priceValue', 'selectedEnergyPools', 'selectedPerceptions', 'selectedCommunications', 'selectedAlchemy', 'selectedFeatures','selectedTraits']);
    }

    public function handleRefreshQuery()
    {
        $this->render();
    }

    public function editShopResource($resource_id)
    {


        $this->emit('toggleEditShopResourceModal');

        $this->editResourceData = ShopCategoryResource::singleLibraryResource($resource_id);

        $this->resourceId = $resource_id;

        $this->heading = $this->editResourceData['heading'] ?? null;
        $this->description = $this->editResourceData['description'] ?? null;

        $this->category_id = $this->editResourceData['humanop_shop_category_id'] ?? null;

        $this->update_content = $this->editResourceData['content'] ?? null;

        $this->pointValue = $this->editResourceData['point'] ?? null;

        $this->priceValue = $this->editResourceData['price'] ?? null;

        $this->selectedTraits = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();
        $this->selectedFeatures = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();
        $this->selectedAlchemy = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();
        $this->selectedCommunications = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();
        $this->selectedPerceptions = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();
        $this->selectedEnergyPools = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();

        $this->emit('contentUpdated', $this->update_content ?? '');
    }

    public function editHumanOpShopResource($category_id)
    {
        $this->current_category = $category_id;
    }

    public function moveShopResourceToCategory()
    {

        $rule = [
            'category_id' => 'required',
        ];

        $message = [
            'category_id.required' => 'Please Select New Category',

        ];

        $this->validate($rule, $message);

        if ($this->current_category) {

            ShopCategoryResource::updateCategory($this->current_category, $this->category_id);
        }
        session()->flash('success', 'HumanOP Shop Resource Moved successfully.');

        $this->current_category = '';
    }

    public function deleteCategory($id)
    {
        ShopCategory::deleteSingleCategory($id);
    }

    public function emptyCreateForm()
    {
        $this->category_id = '';
        $this->heading = '';

        $this->resource_file = '';
        $this->permission = '';
    }


    public function updateShopResource()
    {


        DB::beginTransaction();

        $this->validate(['heading' => 'required', 'description' => 'required', 'category_id' => 'required', 'update_content' => 'nullable', 'resource_file' => 'nullable|file|mimes:mp4,mov,avi,mkv,mp3,wav,pdf,doc,docx|max:204800',
            'pointValue' => 'required_without:priceValue|nullable|numeric|min:0',
            'priceValue' => 'required_without:pointValue|nullable|numeric|min:0']);

        if (!empty($this->resource_file)) {
            $extension = $this->resource_file->extension();
            $upload_id = $this->uploadFile($this->resource_file);

            if (in_array($extension, ['mp3', 'wav', 'mpeg'])) {
                $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->priceValue, null, $upload_id, null, $this->pointValue,$this->description);
            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
                $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->priceValue, $upload_id, null, null, $this->pointValue,$this->description);
            } elseif (in_array($extension, ['pdf', 'doc'])) {
                $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->priceValue, null, null, $upload_id, $this->pointValue,$this->description);
            }
        } else {
            $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->priceValue, null, null, null, $this->pointValue,$this->description);
        }

        HumanOpShopTraits::where('humanop_shop_resource_id', $updateResource->id)->delete();

        foreach ($this->selectedTraits as $traitCode) {
            HumanOpShopTraits::create([
                'humanop_shop_resource_id' => $updateResource->id,
                'trait_name' => $traitCode,
            ]);
        }


        $this->emit('toggleEditShopResourceModal');

        $this->resetForm();

        DB::commit();

        session()->flash('success', 'HumanOp Shop resource updated successfully.');

    }

    public function createShopCategory()
    {

        $rule = [
            'category_name' => 'required|unique:humanop_shop_categories,name|max:100',
        ];

        $message = [
            'category_name.required' => 'Category name is required',
            'category_name.unique' => 'This category name already exists',
            'category_name.max' => 'Category maximum length is 100 characters',
        ];

        $this->validate($rule, $message);

        ShopCategory::createShopeCategory($this->category_name);

        $this->reset('category_name');

        session()->flash('success', 'Category added');

        $this->emit('toggleCreateCategoryModal');
    }

    public function uploadFile($resourceFile = null)
    {

        if (!empty($resourceFile)) {

            if (in_array($resourceFile->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {

                return Upload::uploadFile($resourceFile, 200, 200, 'base64Image', 'png', true);

            } elseif (in_array($resourceFile->extension(), ['mp3', 'wav', 'mpeg'])) {

                return Upload::uploadFile($resourceFile, '', '', 'audio');
            } elseif (in_array($resourceFile->extension(), ['pdf', 'doc', 'docx'])) {

                return Upload::uploadFile($resourceFile, '', '', 'document'); // Add this block for documents

            } else {

                return Upload::uploadFile($resourceFile, '', '', 'video');

            }

        } else {

            return null;

        }
    }

    public function render()
    {

        $categories = ShopCategory::categories();

        $dropDownCategories = ShopCategory::dropDownCategories();

        $resourceSlug = $this->resourceSlug;

        return view('livewire.admin.resource.create-human-op-shop', compact('resourceSlug', 'categories', 'dropDownCategories'));
    }
}
