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
    <div class="row mt-4 container-fluid mainDivClass">
        <div class="col-12">
            <div class="card table-orange-color">
                <div class="table-header-text" style="border-radius: 20px;">
                    @livewire('admin.practitioners.all-practitioner')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush
