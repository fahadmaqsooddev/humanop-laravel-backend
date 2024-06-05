@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

    <div class="container mt-9" style="padding-left: 200px;">
        @foreach($questions as $index => $question)
            <hr class="" style="border: 1px solid white">
            <div class="mb-4 text-white text-bold">
                <h4 class="text-white">{{$index +1}}. {{$question['question']}}</h4>
                @foreach($question['answers'] as $answer)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="q1a1" name="q1" onclick="onlyOne(this)">
                        <label class="form-check-label text-white" for="q1a1">{{$answer['answer']}}</label>
                    </div>
                @endforeach
            </div>
        @endforeach

        <a href="{{url('client-user-detail')}}" class="btn btn-icon bg-gradient-primary mt-4">
            Submit
            <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>


@endsection
@push('js')
    <script>
        function onlyOne(checkbox) {
            var checkboxes = document.getElementsByName('q1');
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
            });
        }
    </script>
@endpush
