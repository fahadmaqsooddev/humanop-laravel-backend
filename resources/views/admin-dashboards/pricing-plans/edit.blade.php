@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                <div class="card-header">
                    <h3 class="mb-0 text-bold">Edit Pricing Plan</h3>
                </div>
                <div class="card-body pt-0">

                    @livewire('admin.pricing-plan.edit-pricing-plan', ['plan' => $plan])

                </div>
            </div>
        </div>
    </div>
@endsection
