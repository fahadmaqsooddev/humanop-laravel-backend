@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .font-weight-normal {
        color: black;
    }
</style>
@section('content')
    @livewire('admin.resource.create-resource')
    <div class="row" style="margin-top: 200px">
        <div class="col-12">
            <div id="globe" class="position-absolute end-0 top-2 mt-sm-3 me-lg-7">
                <canvas width="700" height="600" class="w-lg-100 h-lg-100 w-75 h-75 me-lg-0 me-n10 mt-lg-5"></canvas>
            </div>
        </div>
    </div>
@endsection

