@if(session('errors'))
    @foreach(session('errors') as $err)
        <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
            <span class="alert-text text-white">
                                {{$err[0]}}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fa fa-close" aria-hidden="true"></i>
            </button>
        </div>
    @endforeach
@endif
@if ($errors->any())

        <div class="m-3 alert alert-warning alert-dismissible fade show" id="alert" role="alert">
           <ul class="alert-text text-white mb-0">
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
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
@if(session('error'))
    <div class="m-3  alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <span class="alert-text text-white">
                            {{session('error')}}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
@endif


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Automatically close alert messages after 3 seconds
    $(document).ready(function () {
        setTimeout(function () {
            $(".alert").alert('close');
        }, 2000);
    });
</script>
