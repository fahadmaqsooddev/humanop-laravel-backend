@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0">Edit Web Page</h5>
                </div>
                <div class="card-body pt-0">
                    @livewire('admin.web-page.web-page-form', ['page' => $web_page])

                </div>
            </div>
        </div>
    </div>
@endsection
