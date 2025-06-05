@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])
<style>

    .text-color-dark {
        color: #0f1534 !important;
    }

    .card-bg-white-orange-border{
        background-color: #eaf3ff !important;
        /*border: 2px solid #1b3a62 !important;*/
    }

    .input-bg{
        background-color: #F4ECE0 !important;
        color: #1b3a62 !important;
        border-radius: 20px !important;
        border: none !important;
        padding: 5px;
    }

    .input-bg::placeholder{
        color: #1b3a62 !important;
    }

    .cluster-buttons{
        background-color: #1b3a62 !important;
        color: #F4ECE0;
        padding: 5px 10px;
        border-radius: 8px;
        border-width: 2px;
        border: none;
        font-size: 14px;
        font-weight: 600;
    }

    .configurations-drop-down{
        min-width: 250px;
        text-align: center;
        background-color: #F4ECE0 !important;
        color: #1b3a62 !important;
        border-radius: 40px !important;
        border: none !important;
        padding: 7px;
    }

    .cluster-table-rows{
        padding: 5px;
        /*border: 1px solid #1b3a62;*/
    }

    h5, h4, h6, .text-color-orange{
        color: #1b3a62 !important;
    }

    .cluster-badge{
        border-radius: 5px;
        background-color:#eaf3ff !important;
        color: #1b3a62 !important;
    }

</style>
@section('content')
    <div class="container-fluid mt-4">

        <div class="row">

            <div class="card card-bg-white-orange-border mt-4" id="prompt">

                @livewire('admin.hai-chat.knowledge.new-embedding')

                @livewire('admin.hai-chat.knowledge.universal-embedding')

                @livewire('admin.hai-chat.knowledge.cluster')

            </div>
        </div>
    </div>

@endsection

