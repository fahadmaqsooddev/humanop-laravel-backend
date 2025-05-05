@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>

    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }

</style>
@section('content')

    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                @livewire('admin.user.deleted-users')
            </div>
        </div>
    </div>

@endsection
