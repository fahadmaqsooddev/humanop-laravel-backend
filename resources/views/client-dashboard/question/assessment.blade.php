@extends('user_type.auth', ['parentFolder' => 'client-dashboard', 'childFolder' => 'none'])

@section('content')

    <div class="container mt-9" style="padding-left: 200px;">
        @foreach($questions as $index => $question)
            <hr class="" style="border: 1px solid white">
            <div class="mb-4 text-white text-bold">
                <h4 class="text-white">{{$index +1}}. {{$question['question']}}</h4>
                @foreach($question['answers'] as $answer)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="q{{$question['id']}}a{{$answer['id']}}" value="{{$answer['answer']}}" name="q-{{$question['id']}}" onclick="onlyOne(this, 'q-{{$question['id']}}')">
                        <label class="form-check-label text-white" for="q{{$question['id']}}a{{$answer['id']}}">{{$answer['answer']}}</label>
                        @if($answer['image'] !== 'NULL')
                            <br>
                            <img src="{{ asset('assets/img/' . $answer['image']) }}" alt="Image for {{$answer['answer']}}">
                        @endif
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
        function onlyOne(checkbox, groupName) {
            var checkboxes = document.getElementsByName(groupName);
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
            });
        }
    </script>
@endpush
