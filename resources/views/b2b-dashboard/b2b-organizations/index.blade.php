


@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
@push('css')
    <style>
    .form-switch .form-check-input:checked {
        border-color: rgba(58, 65, 111, 0.95);
        background-color: forestgreen !important;
    }

    .table-text-color{
        color: #1c365e !important;
    }

    .dataTable-table th a{
        color: #1c365e !important;
    }
    .swal2-styled.swal2-confirm {
        background-image: none !important;
        background-color: #f2661c !important;
        padding: 0.75rem 1.5rem;
        font-size: 0.75rem;
        border-radius: 0.5rem;
        color: white !important; /* optional: ensure text is readable */
    }

    </style>
@endpush
@section('content')
    <div class="row mt-4 container-fluid ">
        <div class="col-12">
            <div class="card table-orange-color">
                <div class="table-header-text" style="border-radius: 20px;">
                    @livewire('b2b.b2b-organizations.b2b-organization')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush
