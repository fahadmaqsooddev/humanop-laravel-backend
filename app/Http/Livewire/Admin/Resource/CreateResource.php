<?php

namespace App\Http\Livewire\Admin\Resource;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Upload\Upload;
use App\Models\Admin\Resources\LibraryResource;
use App\Models\Admin\Resources\PermissionResource;

class CreateResource extends Component
{
    use WithFileUploads;

    public $resourceId, $resourceSlug, $heading, $resource, $permission = [], $editResourceData;

    protected $listeners = ['toggleCreateResourceModal' => 'resetForm', 'toggleShowResourceModal' => 'handleRefreshQuery'];

    protected $rules = [
        'heading' => 'required',
        'resource' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,mkv', // Max file size 10MB
        'permission' => 'required|array|min:1',
    ];

    protected $messages = [
        'heading.required' => 'Heading is required',
        'resource.required' => 'Resource is required',
        'resource.mimes' => 'Resource must be a valid image or video file (jpeg, png, jpg, gif, mp4, mov, avi, mkv).',
        'permission.required' => 'At least one permission is required',
    ];

    public function CreateResource()
    {
        try {

            DB::beginTransaction();

            $this->validate();

            if (in_array($this->resource->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {

                $upload_id = Upload::uploadFile($this->resource, 200, 200, 'base64Image', 'png', true);

            } else {

                $upload_id = Upload::uploadFile($this->resource, '', '', 'video');
            }

            $resource = LibraryResource::createResource($this->heading, $upload_id);

            PermissionResource::createResourcePermission($resource['id'], $this->permission);

            $this->emit('toggleCreateResourceModal');

            $this->resetForm();

            DB::commit();

            session()->flash('success', 'Library resource created successfully.');

        } catch (\Exception $exception) {

            DB::rollBack();

            session()->flash('error', $exception->getMessage());
        }
    }

    public function deleteResource($id, $slug)
    {
        try {

            $this->resourceSlug = $slug;

            DB::beginTransaction();

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
        $this->reset(['heading', 'resource', 'permission','resource']);
    }

    public function handleRefreshQuery()
    {
        $this->render();
    }

    public function editResource($resource_id){

        $this->emit('toggleEditResourceModal');

        $this->editResourceData = LibraryResource::singleLibraryResource($resource_id);

        $this->resourceId = $resource_id;

        $this->heading = $this->editResourceData['heading'] ?? null;
    }

    public function updateResource(){

        DB::beginTransaction();

        $this->validate(['heading' => 'required']);

        if ($this->resource){

            if (in_array($this->resource->extension(), ['jpeg', 'png', 'jpg', 'gif'])) {

                $upload_id = Upload::uploadFile($this->resource, 200, 200, 'base64Image', 'png', true);

            } else {

                $upload_id = Upload::uploadFile($this->resource, '', '', 'video');
            }

        }else{

            $upload_id = $this->editResourceData['upload_id'] ?? null;
        }

        LibraryResource::updateResource($this->heading, $upload_id, $this->resourceId);

        PermissionResource::createResourcePermission($this->resourceId, $this->permission);

        $this->emit('toggleEditResourceModal');

        $this->resetForm();

        DB::commit();

        session()->flash('success', 'Library resource updated successfully.');

    }

    public function render()
    {
        $resources = $this->allResources();

        $resourceSlug = $this->resourceSlug;

        return view('livewire.admin.resource.create-resource', compact('resources','resourceSlug'));
    }
}
