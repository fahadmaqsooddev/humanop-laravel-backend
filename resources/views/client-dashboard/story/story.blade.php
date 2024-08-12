<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Human Network Stories</title>
    <link rel="icon" type="image/png" href="{{ URL::asset('assets/img/favicon.png') }}">

    <link href="{{ URL::asset('assets/css/custom.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('assets/story-slider/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>
<body class="background_image">

<div class="main-container">

    <div style="padding: 20px;">
        <a class="align-items-center d-flex m-0 text-wrap" href="{{ url('/client/dashboard') }}">
            <img src="{{ URL::asset('assets/img/logo.png') }}" class="h-100" style="margin-left: 33px" alt="main_logo">
        </a>
    </div>

    <div class="main-nav">
{{--        <button><img src="{{asset('assets/story-slider/icons/leftArrow.png')}}" alt="" id="prev-story"></button>--}}
    </div>
    <div data-slide="slide" class="slide">
        <div class="slide-items">
            @foreach($stories as $story)

                @if($story['file_type'] === 'image')
                    <img src="{{$story['upload_url']['url']}}" alt="Cat 1">
{{--                @elseif($story['file_type'] === 'video')--}}
{{--                    <video src="{{$story['upload_url']['path']}}" alt="Cat 1" autoplay>--}}
                @endif

            @endforeach
{{--            <img src="{{asset('/assets/story-slider/images/img2.jfif')}}" alt="Cat 2">--}}
{{--            <img src="{{asset("/assets/story-slider/images/img3.jfif")}}" alt="Cat 3">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
{{--            <img src="{{asset('/assets/story-slider/images/img4.jfif')}}" alt="Cat 4">--}}
        </div>
        <div class="slide-nav">
            <div class="slide-thumbs"></div>
            <div>
                @if(empty($stories[0]))
                    <h3>Stories not found</h3>
                @endif
            </div>
            <div class="username">
                <div class="head1">
                    <img src="{{$user->user_picture_url ?? ""}}" alt="" class="dp">
                    <p>{{$user ? $user->first_name . ' ' . $user->last_name : ""}}</p>
                </div>
                <div class="head2">
{{--                    <button><img src="{{asset('assets/story-slider/icons/play.png')}}" alt="" id="play"></button>--}}
{{--                    <button><img src="{{asset('assets/story-slider/icons/dot.png')}}" alt=""></button>--}}
                </div>
            </div>
            <button class="slide-prev">Previous</button>
            <button class="slide-next">Next</button>
            <div class="like-forward">
{{--                <button><img src="{{asset('assets/icons/heart.png')}}" alt=""></button>--}}
{{--                <button><img src="{{asset('assets/story-slider/icons/send.png')}}" alt=""></button>--}}
            </div>

        </div>
    </div>

    <div class="main-nav">
{{--        <button><img src="{{asset('assets/story-slider/icons/rightArrow.png')}}" alt="" id="next-story"></button>--}}
    </div>
    <div style="padding: 30px;">
        <a href="{{url('/client/newsfeed')}}" style="color: white; text-decoration: none;font-size: 30px;">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
    </div>
</div>

<script src="{{asset('assets/story-slider/slide-stories.js')}}"></script>


</body>
</html>
