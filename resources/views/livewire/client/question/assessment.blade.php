<form wire:submit.prevent="updateAssessment">
    @csrf
    @foreach($questions as $index => $question)
        <hr class="" style="border: 1px solid white">
        <div class="mb-4 text-white text-bold">
            <h4 class="text-white">{{ $question['id'] }}. {{ $question['question'] }}</h4>
            @foreach($question['answers'] as $key=>$answer)
                <div class="form-check">
                    <input type="checkbox"
                           value="{{ $answer['answer'] }}"
                           class="q-{{ $question['id'] }} form-check-input"
                           @if(!empty($answers[$question['id']]))
                           {{$answers[$question['id']]['answer_id'] == $answer['id'] ? 'checked' : ''}}
                           @endif
                           wire:click="selectAnswer({{ $question['id'] }}, '{{ $answer['id'] }}' , '{{$answer['answerCodes']}}')"
                    >
                    <label class="form-check-label text-white">{{ $answer['answer'] }}</label>
                    @if($answer['image'] !== 'NULL')
                        <br>
                        <img src="{{ asset('assets/img/' . $answer['image']) }}"
                             alt="Image for {{ $answer['answer'] }}">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <button type="submit" class="btn btn-icon bg-gradient-primary mt-4">
        Submit
        <i class="fas fa-arrow-right ms-1"></i>
    </button>
</form>

