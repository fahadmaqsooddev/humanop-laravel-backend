<div>
    <form wire:submit.prevent="updatePermission">
        @csrf
        @if(session('success'.$adminId))
            <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('success'.$adminId) }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @elseif(session('error'.$adminId))
            <div class="m-3  alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                        <span class="alert-text text-white">
                                            {{session('error'.$adminId) }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <i class="fa fa-close" aria-hidden="true"></i>
                </button>
            </div>
        @endif
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4 col-6 w-50">
                    @foreach(['users', 'deletedClient','clientQueries', 'questions', 'resources', 'approveQueries'] as $permissionName)
                        <input type="checkbox" class="form-check-input" wire:model="permission" value="{{$permissionName}}">
                        <label class="form-check-label text-white">{{ ucfirst($permissionName) }}</label>
                        <br>
                    @endforeach
                </div>
                <div class="col-sm-4 col-6 w-50" style="padding-left: 27px">
                    @foreach(['cms', 'chat', 'projects','deletedClient'] as $permissionName)
                        <input type="checkbox" class="form-check-input" wire:model="permission" value="{{$permissionName}}" >
                        <label class="form-check-label text-white">{{ ucfirst($permissionName) }}</label>
                        <br>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn updateBtn btn-sm float-end text-white mt-4 mb-0">Update Permission</button>
        </div>
    </form>
</div>


