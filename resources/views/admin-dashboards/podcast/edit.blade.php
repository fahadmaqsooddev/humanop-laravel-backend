@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0 text-bold">Edit Podcast</h3>
                </div>
                <div class="card-body pt-0">

                    @livewire('admin.podcast.edit-podcast', ['podcast' => $podcast])

                </div>
            </div>
        </div>
    </div>
@endsection
