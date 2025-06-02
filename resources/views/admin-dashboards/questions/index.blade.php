@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .pagination {
        float: right;
        margin-right: 24px;
    }

    .page-link {
        background: none !important;
    }

    .page-link:hover {
        background: #1B3A62 !important;
        color: white !important;
    }

    .page-item.active .page-link {
        background: #1B3A62 !important;
        color: white !important;
        border-color: #1B3A62 !important;
    }

    .table-text-color {
        color: #1c365e !important;
    }

    .dataTable-table th a {
        color: #1c365e !important;
    }

</style>
@section('content')
    <div class="row mt-4 container-fluid">
        <div class="col-12">
            <div class="card">
                <!-- Card header -->
                @livewire('admin.question.question-show',['questions' => $questions])
            </div>
        </div>
    </div>
@endsection
