<?php

namespace App\Http\Livewire\Admin\MediaPlayer;

use App\Models\Admin\HumanOpItemsGridActivitiesLog;
use App\Models\Admin\MediaPlayer\MediaPlayerCategories;
use App\Models\Admin\MediaPlayer\MediaPlayerResources;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\PermissionResource;
use App\Models\Upload\Upload;
use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMediaPlayer extends Component
{

    use WithFileUploads;

    public $booleanValue = false;

    public $resourceId, $pointValue, $priceValue, $current_category, $resourceSlug, $heading, $description, $update_description, $content, $resource_file, $category_id, $permission = [], $editResourceData, $category_name, $link, $relevance, $getVideoLink, $thumbnail_file, $fileType, $showThumbnailUpload = false, $typeThumbnail = false;

    public $selectedTraits = [], $selectedFeatures = [], $selectedAlchemy = [], $selectedCommunications = [], $selectedPerceptions = [], $selectedEnergyPools = [];

    protected $listeners = ['toggleCreateResourceModal' => 'resetForm', 'toggleShowResourceModal' => 'handleRefreshQuery', 'deleteCategoryPermanently' => 'deleteCategory', 'fileChanged'];

    protected $rules = [
        'heading' => 'required|unique:library_resources,heading',
        'resource_file' => 'required|file|mimes:mp4,mov,avi,mkv,mp3,wav|max:204800', // Max file size 200MB
        'thumbnail_file' => 'required|file|mimes:jpeg,png,jpg,gif|max:204800', // Max file size 200MB
//        'permission' => 'required|array|min:1',
        'category_id' => 'required|exists:media_player_categories,id',
        'description' => 'nullable|string|max:3000',
    ];

    protected $messages = [
        'heading.required' => 'Heading is required.',
        'heading.unique' => 'The heading must be unique in the library resources.',
        'resource_file.mimes' => 'The resource must be a valid file of type: jpeg, png, jpg, gif, mp4, mov, avi, mkv, mp3, wav.',
        'resource_file.max' => 'The resource file size must not exceed 200MB.',
        'permission.required' => 'At least one permission is required.',
//        'permission.array' => 'Permissions must be an array.',
//        'permission.min' => 'At least one permission is required.',
        'category_id.required' => 'Category is required.',
        'category_id.exists' => 'The selected category does not exist.',
        'description.max' => 'Description may not exceed 1000 characters.',
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

            $extension = $this->resource_file->extension();

            if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {

                $this->validate([
                    'thumbnail_file' => 'required|file|mimes:jpeg,png,jpg,gif|max:204800',
                ]);

            }

            $upload_id = $this->uploadFile($this->resource_file);

            if (in_array($extension, ['mp3', 'wav', 'mpeg'])) {

                $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                MediaPlayerResources::createResource($this->heading, $this->category_id, $this->description, $thumbnail_id, null, $upload_id);

            } elseif (in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {

                $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                MediaPlayerResources::createResource($this->heading, $this->category_id, $this->description, $thumbnail_id, $upload_id, null);

            } else {

                MediaPlayerResources::createResource($this->heading, $this->category_id, $this->description, null, null, null);

            }

            $this->emit('toggleCreateResourceModal');

            $this->resetForm();

            DB::commit();

            session()->flash('success', 'Media Player Resource created successfully.');

            $this->emit('reloadPage');

        } catch (\Exception $exception) {
            DB::rollBack();

            session()->flash('error', $exception->getMessage());

        }

    }

    public function updateResource()
    {

        DB::beginTransaction();

        $this->validate(['heading' => 'required', 'category_id' => 'required', 'update_description' => 'nullable', 'resource_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mpeg,mp3,mp4,wav|max:204800', 'thumbnail_file' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048']);

        if ($this->resource_file) {

            $extension = $this->resource_file->extension();

            if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {

                $this->validate([
                    'thumbnail_file' => 'required|file|mimes:jpeg,png,jpg,gif|max:204800',
                ]);

            }

        }

        if (!empty($this->resource_file) && in_array($extension, ['mp3', 'wav', 'mpeg'])) {

            $upload_id = $this->uploadFile($this->resource_file);

            $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

            MediaPlayerResources::updateResource($this->resourceId, $this->heading, $this->category_id, $this->update_description, $thumbnail_id, null, $upload_id);

        } elseif (!empty($this->resource_file) && in_array($extension, ['mp4', 'mov', 'avi', 'mkv'])) {

            $upload_id = $this->uploadFile($this->resource_file);

            $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

            MediaPlayerResources::updateResource($this->resourceId, $this->heading, $this->category_id, $this->update_description, $thumbnail_id, $upload_id, null);

        } else {

            if (!empty($this->thumbnail_file)){

                $thumbnail_id = Upload::uploadFile($this->thumbnail_file, 200, 200, 'base64Image', 'png', true);

                MediaPlayerResources::whereId($this->resourceId)->update([
                    'heading' => $this->heading,
                    'slug' => Str::slug($this->heading),
                    'media_player_category_id' => $this->category_id,
                    'description' => $this->update_description,
                    'thumbnail_id' => $thumbnail_id,
                ]);

            }else{

                MediaPlayerResources::whereId($this->resourceId)->update([
                    'heading' => $this->heading,
                    'slug' => Str::slug($this->heading),
                    'media_player_category_id' => $this->category_id,
                    'description' => $this->update_description,
                ]);

            }
        }

        $this->emit('toggleEditResourceModal');

        $this->resetForm();

        DB::commit();

        session()->flash('success', 'Media Player resource updated successfully.');

        $this->emit('reloadPage');

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
            } // If file is video/audio
            elseif (in_array($this->fileType, ['mp4', 'mp3', 'mpeg', 'mov'])) {
                $this->showThumbnailUpload = true;
                $this->typeThumbnail = false;
            } // Unsupported type
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

            MediaPlayerResources::deleteResource($id);

            $this->resetForm();

            $this->emit('toggleShowResourceModal', $slug);

            DB::commit();

            session()->flash('success', 'Media Player resource deleted successfully.');

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

        $this->editResourceData = MediaPlayerResources::singleLibraryResource($resource_id);

        $this->resourceId = $resource_id;

        $this->heading = $this->editResourceData['heading'] ?? null;

        $this->category_id = $this->editResourceData['media_player_category_id'] ?? null;

        $this->update_description = $this->editResourceData['description'] ?? null;

        $this->priceValue = $this->editResourceData['prices'] ?? null;

        $this->pointValue = $this->editResourceData['points'] ?? null;

        $this->permission = match ((int) ($this->editResourceData['permission'] ?? 0)) {
            1 => 'freemium',
            2 => 'core',
            default => null,
        };

        $this->emit('contentUpdated', $this->update_description ?? '');

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

            MediaPlayerResources::updateCategory($this->current_category, $this->category_id);
        }

        session()->flash('success', 'Resource Moved successfully.');

        $this->current_category = '';

    }

    public function deleteCategory($id)
    {
        MediaPlayerCategories::deleteSingleCategory($id);
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

    public function createCategory()
    {

        $rule = [
            'category_name' => 'required|unique:media_player_categories,name|max:100',
        ];

        $message = [
            'category_name.required' => 'Category name is required',
            'category_name.unique' => 'This category name already exists',
            'category_name.max' => 'Category maximum length is 100 characters',
        ];

        $this->validate($rule, $message);

        MediaPlayerCategories::createCategory($this->category_name);

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

                $upload = Upload::uploadMp3($resourceFile);

                return $upload['id'];

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

        $categories = MediaPlayerCategories::categories();

        $dropDownCategories = MediaPlayerCategories::dropDownCategories();

        $resourceSlug = $this->resourceSlug;

        return view('livewire.admin.media-player.create-media-player', compact('resourceSlug', 'categories', 'dropDownCategories'));
    }

}
