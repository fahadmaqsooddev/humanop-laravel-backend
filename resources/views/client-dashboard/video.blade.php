@extends('user_type.auth', ['parentFolder' => 'dashboards', 'childFolder' => 'virtual'])

@section('content')
    <main class="main-content mt-1 border-radius-lg">
        <div class="section min-vh-85 position-relative transform-scale-0 transform-scale-md-7">
            <div class="container-fluid">
                <div class="row">
                    <iframe width="500" height="515" src="{{asset('assets/video/Intro to Communication Style.mp4')}} "
                            allowfullscreen frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </main>
@endsection
