@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
@push('css')
    <style>
    .form-switch .form-check-input:checked {
        border-color: rgba(58, 65, 111, 0.95);
        background-color: forestgreen !important;
    }
    </style>
@endpush
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="table-header-text" style="border-radius: 20px;">
                    @livewire('admin.user.all-user')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush
