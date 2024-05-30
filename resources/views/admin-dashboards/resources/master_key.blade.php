@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'none'])

@section('content')
    <div class="row">
        <div class="col-12 position-relative z-index-2">
            <div class="mb-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-column h-100">
                                <h2 class="font-weight-bolder mb-0">Master Key</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="card mb-4" style="background: linear-gradient(127.09deg, rgba(6, 11, 40, 0.94) 19.41%, rgba(10, 14, 35, 0.49) 76.65%);">
                        <div class="card-body p-3">
                            <img style="width: 100%; height: 600px" src="{{asset('assets/img/Master_Key.jpg')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
