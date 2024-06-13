<form wire:submit.prevent="updateAssessment">
    @csrf

    @foreach($questions as $index => $question)
        <hr class="" style="border: 1px solid white">
        <div class="mb-4 text-white text-bold">
            <h4 class="text-white">{{ $offset+($index+1) }}. {{ $question['question'] }}</h4>
            @foreach($question['answers'] as $key=>$answer)
                <div class="form-check">
                    <input type="checkbox"
                           value="{{ $answer['answer'] }}"
                           class="q-{{ $question['id'] }} form-check-input"
                           onclick="onlyOne(this, 'q-{{ $question['id'] }}')"
                           @if($answer['answer_id'])
                           wire:click="selectAnswer({{ $question['id'] }}, '{{ $answer['id'] }}' , '{{ json_encode($answer['sub_answer_codes'] ?? []) }}')"
                           @else
                           wire:click="selectAnswer({{ $question['id'] }}, '{{ $answer['id'] }}' , '{{ json_encode($answer['answer_codes'] ?? []) }}')"
                           @endif
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
@push('js')
    <script>
        function onlyOne(checkbox, groupName) {
            var checkboxes = document.querySelectorAll('.' + groupName);
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
            });
        }
    </script>
@endpush

