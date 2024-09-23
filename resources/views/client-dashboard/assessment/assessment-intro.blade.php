@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])
<style>
    .modal-close-btn {
        background: #f2661c;
        border: none;
        color: white;
        font-weight: bold;
        font-size: x-large;
        float: right;
        border-radius: 3px;
        padding: 0px 10px 1px 10px;
    }
    .top-heading{
        margin-top:100px;
    }
    .bg-pricing{
        height:100vh;
    }
</style>
@section('content')

    <div class="page-header position-relative m-3 border-radius-xl ">
        <img src="{{ URL::asset('assets/img/login.webp') }}" alt="pattern-lines" class="position-absolute opacity-6 start-0 top-0 w-100 bg-pricing" >
        <div class="container pb-md-2 pb-4 pt-5 pt-md-1 top-heading ">
            <div class="row">
                <div class="col-md-6 mx-auto   text-center">
                    <h3 class="text-white">The HumanOp Assessment</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="">
        <div class="container">
            <div class="tab-content tab-space">
                <div class="tab-pane active" id="monthly">
                    <div class="row d-flex justify-content-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
