<?php

namespace App\Http\Livewire\Admin\Resource;

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
    public $priceValue = null;
    public $resourceId, $current_category, $resourceSlug, $heading, $update_content, $resource_file, $category_id, $permission, $editResourceData, $category_name;
    public $selectedTraits = [];
    protected $listeners = ['toggleCreateResourceModal' => 'resetForm', 'toggleShowResourceModal' => 'handleRefreshQuery', 'deleteCategoryPermanently' => 'deleteCategory', 'fileChanged'];

    protected $rules = [
        'heading' => 'required|unique:humanop_shop_resources,heading|regex:/^[A-Za-z]/',
        'resource_file' => 'nullable|file|mimes:mp4,mov,avi,mkv,mp3,wav,pdf,doc,docx|max:204800', // Max file size 200MB
        'permission' => 'required',
        'category_id' => 'required|exists:humanop_shop_categories,id',

        'priceValue' => 'required',


    ];

    protected $messages = [
        'heading.required' => 'Heading is required.',
        'heading.regex' => 'The heading must start with a letter.',
        'heading.unique' => 'The heading must be unique in the library resources.',
        'resource_file.mimes' => 'The resource file must be a valid type: MP4, MOV, AVI, MKV (video), MP3, WAV (audio), or PDF (document).',
        'resource_file.max' => 'The resource file size must not exceed 200MB.',
        'permission.required' => 'At least one permission is required.',
        'category_id.required' => 'Category is required.',
        'category_id.exists' => 'The selected category does not exist.',

        'priceValue.required' => 'Price or Point is required.',

    ];

    public function togglePermission($value)
    {
        $this->permission = ($this->permission === $value) ? null : $value;
    }


    public function createShopResource()
    {

        DB::beginTransaction();

        try {

            $this->validate();

            $extension = $this->resource_file->extension();
            $upload_id = $this->uploadFile($this->resource_file);
            $resource = null;
            if (in_array($extension, ['mp3', 'wav', 'mpeg'])) {
                $resource = ShopCategoryResource::createShopResource(
                    $this->heading,
                    $this->category_id,
                    $this->permission,
                    null,
                    $upload_id,
                    null,
                    $this->priceValue
                );

            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
                $resource = ShopCategoryResource::createShopResource(
                    $this->heading,
                    $this->category_id,
                    $this->permission,
                    $upload_id,
                    null,
                    null,
                    $this->priceValue
                );

            } else {

                $resource = ShopCategoryResource::createShopResource(
                    $this->heading,
                    $this->category_id,
                    $this->permission,
                    null,
                    null,
                    $upload_id,
                    $this->priceValue
                );
            }

            foreach ($this->selectedTraits as $traitCode) {
                HumanOpShopTraits::storeTraits($resource->id, $traitCode);
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

        $this->reset(['heading', 'resource_file', 'permission', 'resource_file',]);
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

        $this->category_id = $this->editResourceData['humanop_shop_category_id'] ?? null;

        $this->update_content = $this->editResourceData['content'] ?? null;

        $this->permission = $this->editResourceData['buy_from'] ?? null;

        $this->priceValue = $this->editResourceData['point_price'] ?? null;

        $this->selectedTraits = $this->editResourceData['resourceTraits']->pluck('trait_name')->toArray();

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

        $this->validate(['heading' => 'required', 'category_id' => 'required', 'update_content' => 'nullable', 'resource_file' => 'nullable|file|mimes:mp4,mov,avi,mkv,mp3,wav,pdf,doc,docx|max:204800']);

        if (!empty($this->resource_file)) {
            $extension = $this->resource_file->extension();
            $upload_id = $this->uploadFile($this->resource_file);

            if (in_array($extension, ['mp3', 'wav', 'mpeg'])) {
                $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->permission, null, $upload_id, null, $this->priceValue);
            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {
                $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->permission, $upload_id, null, null, $this->priceValue);
            } elseif (in_array($extension, ['pdf', 'doc'])) {
                $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->permission, null, null, $upload_id, $this->priceValue);
            }
        } else {
            $updateResource = ShopCategoryResource::updateResource($this->heading, $this->resourceId, $this->category_id, $this->permission, null, null, null, $this->priceValue);
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
