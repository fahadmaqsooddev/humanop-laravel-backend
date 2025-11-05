@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h5 class="mb-0 text-bold" style="color: #1B3A62 ">Edit Intro</h5>
                </div>
                <div class="card-body pt-0">
                    @livewire('admin.banners.lifetime-deal-banner', ['banner' => $banner])

                </div>
            </div>
        </div>
    </div>
@endsection
