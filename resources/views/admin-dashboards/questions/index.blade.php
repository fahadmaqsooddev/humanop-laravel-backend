@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>
    .pagination{
        float:right;
       margin-right:24px ;
    }
    .page-link {
        background: none !important;
    }
    .page-link:hover{
        background: #f2661c !important;
        color:white !important;
    }

    .page-item.active .page-link {
        background: #f2661c !important;
        color: white !important;
        border-color: #f2661c !important;
    }

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
                <!-- Card header -->
                <div class="card-header table-header-text">
                    <h5 class="mb-0 table-text-color">All Questions</h5>
                </div>
                @livewire('admin.question.question-show',['questions' => $questions])
            </div>
        </div>
    </div>
@endsection
