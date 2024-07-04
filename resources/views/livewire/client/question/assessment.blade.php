<form wire:submit.prevent="updateAssessment">
    @csrf
    <div class="m-3 alert alert-warning alert-dismissible fade" id="alert" role="alert">
        <span class="alert-text text-white">All Questions Are Required</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close" aria-hidden="true"></i>
        </button>
    </div>
    @foreach($questions as $index => $question)
        <hr class="" style="border: 1px solid white">
        <div class="mb-4 text-white text-bold">
            <h4 class="text-white">{{ $offset+($index+1) }}. {{ $question['question'] }}</h4>
            @if($question['multiple'] == 1)
                <ul class="mb-5" wire:sortable="updateOrder" style="list-style: none">
                    @foreach($question['answers'] as $key => $answer)
                        <li class="mb-4" wire:sortable.item="{{$answer['id'] }}" wire:sortable.handle>
                            <div class="w-100 pl-3">
                                <span class="number text-white">{{ $key + 1 }}</span> <span class="bg-white" style="color: black;cursor: pointer;width: 400px;"> {{ $answer['answer'] }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                @foreach($question['answers'] as $key => $answer)
                    <div class="form-check">
                        <input type="checkbox"
                               value="{{ $answer['answer'] }}"
                               class="q-{{ $question['id'] }} form-check-input"
                               onclick="onlyOne(this, 'q-{{ $question['id'] }}')"
                               wire:click="selectAnswer({{ $question['id'] }}, '{{ $answer['id'] }}', '{{ addslashes(json_encode($answer['sub_answer_codes'] ?? $answer['answer_codes'] ?? [])) }}', '{{ addslashes($question['question']) }}', '{{ addslashes($answer['answer']) }}')">
                        <label class="form-check-label text-white">{{ $answer['answer'] }}</label>
                        @if($answer['image'] !== 'NULL')
                            <br>
                            <img src="{{ asset('assets/img/' . $answer['image']) }}" alt="Image for {{ $answer['answer'] }}">
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    @endforeach

    <button type="submit" class="btn btn-icon bg-gradient-primary mt-4">
        Submit
        <i class="fas fa-arrow-right ms-1"></i>
    </button>
</form>

@push('javascript')
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
    <script>
        function onlyOne(checkbox, groupName) {
            var checkboxes = document.querySelectorAll('.' + groupName);
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
            });
        }

        document.addEventListener('livewire:load', function () {
            Livewire.on('scrollToTop', () => {
                document.getElementById('alert').classList.add('show');
                window.scrollTo(0, 0);
            });
        });
    </script>
@endpush
