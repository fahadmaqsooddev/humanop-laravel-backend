@if($errors->any())
    <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{$errors->first()}}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
@endif
@if(session('success'))
    <div class="m-3  alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{ session('success') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
@endif
