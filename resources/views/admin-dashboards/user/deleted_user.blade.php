@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                @livewire('admin.user.deleted-users')
            </div>
        </div>
    </div>

@endsection
