<?php

namespace App\Http\Livewire\Admin\Resource;

use App\Enums\Admin\Admin;
use App\Events\Resource\NewResource;
use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\Notification\Notification;
use App\Models\Admin\ResourceCategory\ResourceCategory;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\PermissionResource;
use App\Models\Upload\Upload;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateResource extends Component
{
    use WithFileUploads;

    public $booleanValue = false;

    public $resourceId, $pointValue, $priceValue, $current_category, $resourceSlug, $heading, $description, $update_content, $content, $resource_file, $category_id, $permission = [], $editResourceData, $category_name, $link, $relevance, $getVideoLink, $thumbnail_file, $fileType, $showThumbnailUpload = false, $typeThumbnail = false;

    public $selectedTraits = [], $selectedFeatures = [], $selectedAlchemy = [], $selectedCommunications = [], $selectedPerceptions = [], $selectedEnergyPools = [];

    protected $listeners = ['toggleCreateResourceModal' => 'resetForm', 'toggleShowResourceModal' => 'handleRefreshQuery', 'deleteCategoryPermanently' => 'deleteCategory', 'fileChanged'];

    protected $rules = [
        'heading' => 'required|unique:library_resources,heading',
        'relevance' => 'required|string',
        'resource_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv,mp3,wav|max:204800', // Max file size 200MB
        'thumbnail_file' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:204800', // Max file size 200MB
        'permission' => 'required|array|min:1',
        'category_id' => 'required|exists:resource_categories,id',
        'description' => 'nullable|string|max:1000',
        'content' => 'nullable|string',
        'link' => ['nullable', 'max:90', 'regex:/^https?:\/\/video\.gumlet\.io\/[a-zA-Z0-9]+\/[a-zA-Z0-9]+\/[a-zA-Z0-9_-]+\.(mp4)$/'],
    ];

    protected $messages = [
        'heading.required' => 'Heading is required.',
        'relevance.required' => 'Relevance is required.',
        'heading.unique' => 'The heading must be unique in the library resources.',
        'resource_file.mimes' => 'The resource must be a valid file of type: jpeg, png, jpg, gif, mp4, mov, avi, mkv, mp3, wav.',
        'resource_file.max' => 'The resource file size must not exceed 200MB.',
        'permission.required' => 'At least one permission is required.',
        'permission.array' => 'Permissions must be an array.',
        'permission.min' => 'At least one permission is required.',
        'category_id.required' => 'Category is required.',
        'category_id.exists' => 'The selected category does not exist.',
        'description.max' => 'Description may not exceed 1000 characters.',
        'link.max' => 'Video URL may not exceed 90 characters.',
        'link.regex' => 'The video URL must match the required format (e.g., https://video.gumlet.io/xyz/abc.mp4).',
    ];

    public function fileChanged($value)
    {
        $this->booleanValue = $value;
    }

    public function handleFileChange()
    {

        $this->booleanValue = true;

    }

    public function CreateResource()
    {
        try {

            DB::beginTransaction();

            $this->validate();

            if ($this->resource_file) {

                $extension = $this->resource_file->extension();

                if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {

                    $this->validate([
                        'thumbnail_file' => 'required|file|mimes:jpeg,png,jpg,gif',
                    ]);

                }

                $upload_id = $this->uploadFile($this->resource_file);

                if (in_array($extension, ['mp4', 'mov', 'avi', 'mkv']) || in_array($extension, ['mp3', 'wav', 'mpeg'])) {

                    $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                } else {

                    $thumbnail_id = null;
                }

                $resource = LibraryResource::createResource($this->heading, $upload_id, $this->category_id, $this->description, $this->content, $this->link, $this->relevance, $thumbnail_id);

            }else{

                $resource = LibraryResource::createResource($this->heading, null, $this->category_id, $this->description, $this->content, $this->link, $this->relevance, null);

            }

            PermissionResource::createResourcePermission($resource['id'], $this->permission, $this->priceValue, $this->pointValue);

            if (!empty($resource)) {

                foreach ($this->permission as $permission) {

                    $message = 'Your New Training & Resource';

                    event(new NewResource($permission, 'new training & resource', $message));

                    Notification::createNotification('new training & resource', $message, null, null, $permission, Admin::TRAINING_RESOURCE_NOTIFICATION, Admin::B2C_NOTIFICATION);

                }

            }

            $this->emit('toggleCreateResourceModal');

            foreach ($this->selectedTraits as $traitCode) {
                HumanOpItemsGridActivitiesLog::storeResourceItemTraits($resource['id'], $traitCode);
            }

            foreach ($this->selectedFeatures as $featureCode) {
                HumanOpItemsGridActivitiesLog::storeResourceItemTraits($resource['id'], $featureCode);
            }

            foreach ($this->selectedAlchemy as $alchemyCode) {
                HumanOpItemsGridActivitiesLog::storeResourceItemTraits($resource['id'], $alchemyCode);
            }

            foreach ($this->selectedCommunications as $communicationCode) {
                HumanOpItemsGridActivitiesLog::storeResourceItemTraits($resource['id'], $communicationCode);
            }

//            $perceptionCodes = [
//                'Negative' => 'NE',
//                'Positive' => 'P',
//                'Neutral' => 'N',
//            ];

            foreach ($this->selectedPerceptions as $perception) {
//                if (isset($perceptionCodes[$perception])) {
                HumanOpItemsGridActivitiesLog::storeResourceItemTraits($resource['id'], $perception);
//                }
            }

//            $energyPoolCodes = [
//                'Above Excellent' => 'AE',
//                'Average' => 'A',
//                'Excellent' => 'E',
//                'Fair' => 'F',
//            ];

            foreach ($this->selectedEnergyPools as $energyPoolCode) {
//                if (isset($energyPoolCodes[$energyPoolCode])) {
                HumanOpItemsGridActivitiesLog::storeResourceItemTraits($resource['id'], $energyPoolCode);
//                }
            }

            $this->resetForm();

            DB::commit();

            session()->flash('success', 'Library resource created successfully.');

            $this->emit('reloadPage');

        } catch (\Exception $exception) {
            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function getVideoLink()
    {
        $this->booleanValue = true;
        $this->resource_file = null;

    }

    public function updatedResourceFile()
    {
        if ($this->resource_file) {
            $this->fileType = strtolower($this->resource_file->getClientOriginalExtension());

            // Clear Gumlet link if file is uploaded
            $this->link = null;

            // If file is image
            if (in_array($this->fileType, ['jpeg', 'jpg', 'png', 'gif'])) {
                $this->showThumbnailUpload = false;
                $this->typeThumbnail = $this->fileType;
            }
            // If file is video/audio
            elseif (in_array($this->fileType, ['mp4', 'mp3', 'mpeg', 'mov'])) {
                $this->showThumbnailUpload = true;
                $this->typeThumbnail = false;
            }
            // Unsupported type
            else {
                $this->showThumbnailUpload = false;
                $this->typeThumbnail = false;
            }
        } else {
            $this->showThumbnailUpload = false;
            $this->typeThumbnail = false;
        }
    }

    public function updatedLink($value)
    {
        if (!empty($value)) {
            // Clear resource file if Gumlet link is provided
            $this->reset('resource_file');

            $this->showThumbnailUpload = true;
            $this->typeThumbnail = false;
        } else {
            $this->showThumbnailUpload = false;
            $this->typeThumbnail = false;
        }
    }


    public function deleteResource($id, $slug)
    {
        try {

            $this->resourceSlug = $slug;

            DB::beginTransaction();

            $getResource = LibraryResource::singleLibraryResource($id);

            $this->deleteFileToGumlet($getResource['source_id']);

            PermissionResource::deleteResourcePermission($id);

            LibraryResource::deleteResource($id);

            $this->resetForm();

            $this->emit('toggleShowResourceModal', $slug);

            DB::commit();

            session()->flash('success', 'Library resource deleted successfully.');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }
    }

    public function allResources()
    {
        return LibraryResource::getResources();
    }

    public function resetForm()
    {
        $this->booleanValue = false;

        $this->reset(['heading', 'resource_file', 'permission', 'resource_file', 'link', 'description', 'content', 'priceValue', 'pointValue']);
    }

    public function handleRefreshQuery()
    {
        $this->render();
    }

    public function editResource($resource_id)
    {

        $this->emit('toggleEditResourceModal');

        $this->editResourceData = LibraryResource::singleLibraryResource($resource_id);

        $this->resourceId = $resource_id;

        $this->heading = $this->editResourceData['heading'] ?? null;

        $this->link = $this->editResourceData['embed_link'] ?? null;

        $this->relevance = $this->editResourceData['relevance'] ?? null;

        $this->category_id = $this->editResourceData['resource_category_id'] ?? null;

        $this->description = $this->editResourceData['description'] ?? null;

        $this->update_content = $this->editResourceData['content'] ?? null;

        $this->priceValue = $this->editResourceData['libraryPermissions']['price'] ?? null;

        $this->pointValue = $this->editResourceData['libraryPermissions']['point'] ?? null;

        $this->permission[] = $this->editResourceData['libraryPermissions']['permission'] ?? null;

        // Define your code groups
        $traits = ['VEN', 'MER', 'SO', 'SA', 'MA', 'JO', 'LU'];
        $features = ['DE', 'DOM', 'FE', 'GRE', 'LUN', 'NAI', 'NE', 'POW', 'SP', 'TRA', 'VAN', 'WIL'];
        $alchemies = ['G', 'S', 'C', 'CS', 'GS', 'SC', 'SG'];
        $communications = ['EM', 'INS', 'INT', 'MOV'];
        $perceptions = ['NEG', 'P', 'N'];
        $energyPools = ['AE', 'A', 'E', 'F'];

        $allCodes = $this->editResourceData['resourceTraits']->pluck('grid_name')->toArray();

        // Filter them into groups
        $this->selectedTraits = array_values(array_intersect($allCodes, $traits));
        $this->selectedFeatures = array_values(array_intersect($allCodes, $features));
        $this->selectedAlchemy = array_values(array_intersect($allCodes, $alchemies));
        $this->selectedCommunications = array_values(array_intersect($allCodes, $communications));
        $this->selectedPerceptions = array_values(array_intersect($allCodes, $perceptions));
        $this->selectedEnergyPools = array_values(array_intersect($allCodes, $energyPools));


        $this->emit('contentUpdated', $this->update_content ?? '');
    }

    public function editMoveResource($category_id)
    {
        $this->current_category = $category_id;
    }

    public function moveResourceToCategory()
    {

        $rule = [
            'category_id' => 'required',
        ];

        $message = [
            'category_id.required' => 'Please Select New Category',

        ];

        $this->validate($rule, $message);

        if ($this->current_category) {

            LibraryResource::updateCategory($this->current_category, $this->category_id);
        }
        session()->flash('success', 'Resource Moved successfully.');
        $this->current_category = '';
    }

    public function deleteCategory($id)
    {
        ResourceCategory::deleteSingleCategory($id);
    }

    public function emptyCreateForm()
    {
        $this->category_id = '';
        $this->heading = '';
        $this->description = '';
        $this->content = '';
        $this->resource_file = '';
        $this->priceValue = '';
        $this->pointValue = '';
        $this->permission = [];
    }

    public function updateContent($editorId, $data)
    {

        $this->update_content = $data;
    }

    public function updateResource()
    {

        DB::beginTransaction();

        $this->validate(['heading' => 'required', 'category_id' => 'required', 'link' => 'nullable', 'description' => 'nullable|max:1000', 'update_content' => 'nullable', 'resource_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mpeg,mp3,mp4,wav|max:204800', 'thumbnail_file' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048']);

        if ($this->resource_file) {
            if ($this->resource_file instanceof UploadedFile) {
                $ext = strtolower($this->resource_file->getClientOriginalExtension());
            } else {
                $ext = strtolower(pathinfo($this->resource_file, PATHINFO_EXTENSION));
            }

            if (!in_array($ext, ['jpeg', 'jpg', 'png', 'gif'])) {
                $this->validate([
                    'thumbnail_file' => 'required|file|mimes:jpeg,png,jpg,gif',
                ]);
            }
        }

        $checkPermission = count($this->permission);

        if ($checkPermission == 2) {

            unset($this->permission[0]);

            $this->permission = array_values($this->permission);

        } elseif ($checkPermission == 3) {

            unset($this->permission[0]);

            unset($this->permission[1]);

            $this->permission = array_values($this->permission);

        }

        $upload_id = null;

        if (!empty($this->resource_file) && in_array($this->resource_file->extension(), ['mp4'])) {

//            $getResource = LibraryResource::singleLibraryResource($this->resourceId);

//            $this->deleteFileToGumlet($getResource['source_id']);

            $upload_id = $this->uploadFile($this->resource_file);

            $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

            $updateResource = LibraryResource::updateResource($this->heading, $upload_id, $this->resourceId, $this->category_id, $this->description, $this->update_content, $this->link, $this->relevance, $thumbnail_id);

            tap($updateResource);

//            $this->uploadFileToGumlet($this->resource_file, $updateResource['id']);

        } else {

            if ($this->resource_file) {

                $upload_id = $this->uploadFile($this->resource_file);

                $extension = $this->resource_file->extension();

                if (in_array($extension, ['mp4', 'mov', 'avi', 'mkv']) || in_array($extension, ['mp3', 'wav', 'mpeg'])) {

                    $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                    LibraryResource::whereId($this->resourceId)->update([
                        'heading' => $this->heading,
                        'slug' => Str::slug($this->heading),
                        'upload_id' => $upload_id,
                        'resource_category_id' => $this->category_id,
                        'description' => $this->description,
                        'content' => $this->update_content,
                        'source_id' => null,
                        'source_url' => null,
                        'embed_link' => $this->link,
                        'relevance' => $this->relevance,
                        'thumbnail_id' => $thumbnail_id,
                    ]);

                } else {

                    LibraryResource::whereId($this->resourceId)->update([
                        'heading' => $this->heading,
                        'slug' => Str::slug($this->heading),
                        'upload_id' => $upload_id,
                        'resource_category_id' => $this->category_id,
                        'description' => $this->description,
                        'content' => $this->update_content,
                        'source_id' => null,
                        'source_url' => null,
                        'embed_link' => $this->link,
                        'relevance' => $this->relevance,
                        'thumbnail_id' => null,
                    ]);
                }

            } else {
                if (!empty($this->thumbnail_file)) {

                    $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                    LibraryResource::whereId($this->resourceId)->update([
                        'heading' => $this->heading,
                        'slug' => Str::slug($this->heading),
                        'resource_category_id' => $this->category_id,
                        'description' => $this->description,
                        'content' => $this->update_content,
                        'source_id' => null,
                        'source_url' => null,
                        'embed_link' => $this->link,
                        'relevance' => $this->relevance,
                        'thumbnail_id' => $thumbnail_id,
                    ]);

                } else {

                    LibraryResource::whereId($this->resourceId)->update([
                        'heading' => $this->heading,
                        'slug' => Str::slug($this->heading),
                        'resource_category_id' => $this->category_id,
                        'description' => $this->description,
                        'content' => $this->update_content,
                        'source_id' => null,
                        'source_url' => null,
                        'embed_link' => $this->link,
                        'relevance' => $this->relevance
                    ]);

                }

            }


        }

        PermissionResource::createResourcePermission($this->resourceId, $this->permission, $this->priceValue, $this->pointValue);

        $resourceGrids = HumanOpItemsGridActivitiesLog::getResourceGrid($this->resourceId);

        foreach ($resourceGrids as $gird) {
            $gird->delete();
        }

//        dd($this->selectedTraits,$this->selectedFeatures,$this->selectedAlchemy,$this->selectedCommunications,$this->selectedPerceptions,$this->selectedEnergyPools);

        foreach ($this->selectedTraits as $traitCode) {
            HumanOpItemsGridActivitiesLog::storeResourceItemTraits($this->resourceId, $traitCode);
        }

        foreach ($this->selectedFeatures as $featureCode) {
            HumanOpItemsGridActivitiesLog::storeResourceItemTraits($this->resourceId, $featureCode);
        }

        foreach ($this->selectedAlchemy as $alchemyCode) {
            HumanOpItemsGridActivitiesLog::storeResourceItemTraits($this->resourceId, $alchemyCode);
        }

        foreach ($this->selectedCommunications as $communicationCode) {
            HumanOpItemsGridActivitiesLog::storeResourceItemTraits($this->resourceId, $communicationCode);
        }

//        $perceptionCodes = [
//            'Negative' => 'NE',
//            'Positive' => 'P',
//            'Neutral' => 'N',
//        ];

        foreach ($this->selectedPerceptions as $perception) {
//            if (isset($perceptionCodes[$perception])) {
            HumanOpItemsGridActivitiesLog::storeResourceItemTraits($this->resourceId, $perception);
//            }
        }

//        $energyPoolCodes = [
//            'Above Excellent' => 'AE',
//            'Average' => 'A',
//            'Excellent' => 'E',
//            'Fair' => 'F',
//        ];

        foreach ($this->selectedEnergyPools as $energyPoolCode) {
//            if (isset($energyPoolCodes[$energyPoolCode])) {
            HumanOpItemsGridActivitiesLog::storeResourceItemTraits($this->resourceId, $energyPoolCode);
//            }
        }

        $this->emit('toggleEditResourceModal');

        $this->resetForm();

        DB::commit();

        session()->flash('success', 'Library resource updated successfully.');

        $this->emit('reloadPage');

    }

    public function createCategory()
    {

        $rule = [
            'category_name' => 'required|unique:resource_categories,name|max:100',
        ];

        $message = [
            'category_name.required' => 'Category name is required',
            'category_name.unique' => 'This category name already exists',
            'category_name.max' => 'Category maximum length is 100 characters',
        ];

        $this->validate($rule, $message);

        ResourceCategory::createCategory($this->category_name);

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
            } else {

                return Upload::uploadFile($resourceFile, '', '', 'video');

            }

        } else {

            return null;

        }
    }

    public function uploadFileToGumlet($resourceFile = null, $resourceId = null)
    {
        if (!empty($resourceFile) && in_array($resourceFile->extension(), ['mp4'])) {

            $getResource = LibraryResource::singleLibraryResource($resourceId);

            $responseData = $this->sendRequestFromGuzzle('post', 'https://api.gumlet.com/v1/video/assets',
                [
                    'format' => 'MP4',
                    'input' => $getResource['video_url'] ? $getResource['video_url']['path'] : '',
                    'collection_id' => "675260ac948718dd9422d8bb",
                    'title' => $this->heading
                ]
            );

            LibraryResource::whereId($getResource['id'])->update(['source_id' => $responseData['asset_id'], 'source_url' => $responseData['output']['playback_url']]);

        }

    }

    public function deleteFileToGumlet($resourceId = null)
    {

        if (!empty($resourceId)) {

            $url = 'https://api.gumlet.com/v1/video/assets/' . $resourceId;

            $this->sendRequestFromGuzzle('DELETE', $url);
        }

    }

    public function sendRequestFromGuzzle($method = null, $route_name = null, $body = [])
    {

        $authorization = config('gumlet.gumlet_api_key');

        $queryArray = [
            'headers' => ['Authorization' => "Bearer {$authorization}"],
            'json' => $body
        ];

        $client = new Client(['http_errors' => false, 'timeout' => 180]);

        $route = $route_name;

        $response = $client->request($method, $route, $queryArray);

        $response_body = json_decode($response->getBody()->getContents(), true);

        return $response_body;
    }

    public function render()
    {

        $categories = ResourceCategory::categories();

        $dropDownCategories = ResourceCategory::dropDownCategories();

        $resourceSlug = $this->resourceSlug;

        return view('livewire.admin.resource.create-resource', compact('resourceSlug', 'categories', 'dropDownCategories'));
    }
}
