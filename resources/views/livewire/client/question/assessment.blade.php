<form wire:submit.prevent="updateAssessment" >
    @csrf
    <div class="m-3  alert alert-warning alert-dismissible fade" id="alert" role="alert">
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
                <ul class="sortable mb-5">
                    @foreach($question['answers'] as $key => $answer)
                        <li id="s-{{ $answer['id'] }}" class="mb-4">
                            <div class="d-block w-100 ui-state-default sortable-number pl-3">
                                <span class="number text-white">{{ $key + 1 }}</span> {{ $answer['answer'] }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                @foreach($question['answers'] as $key=>$answer)
                <div class="form-check">
                    <input type="checkbox"
                           value="{{ $answer['answer'] }}"
                           class="q-{{ $question['id'] }} form-check-input"
                           onclick="onlyOne(this, 'q-{{ $question['id'] }}')"
                           wire:click="selectAnswer({{ $question['id'] }}, '{{ $answer['id'] }}', '{{ addslashes(json_encode($answer['sub_answer_codes'] ?? $answer['answer_codes'] ?? [])) }}', '{{ addslashes($question['question']) }}', '{{ addslashes($answer['answer']) }}')">
                    <label class="form-check-label text-white">{{ $answer['answer'] }}</label>
                    @if($answer['image'] !== 'NULL')
                        <br>
                        <img src="{{ asset('assets/img/' . $answer['image']) }}"
                             alt="Image for {{ $answer['answer'] }}">
                    @endif
                </div>
                @endforeach
            @endif
        </div>
    @endforeach

    <button type="submit" class="btn btn-icon bg-gradient-primary mt-4" >

        Submit
        <i class="fas fa-arrow-right ms-1"></i>
    </button>

</form>
@push('js')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" >
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script >
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"> </script>
    <script>
        $(function() {
            $(".sortable").sortable({
                update: function(event, ui) {
                    // Loop through each sortable list and update the numbering within each list
                    $('.sortable').each(function() {
                        $(this).children('li').each(function(index) {
                            // Update the number displayed based on the new index
                            $(this).find('.number').text(index + 1);
                        });
                    });
                }
            }).disableSelection();
        });

        document.addEventListener('livewire:load', function () {
            Livewire.on('addSortable', () => {
                $(".sortable").sortable({
                    update: function(event, ui) {
                        // Loop through each sortable list and update the numbering within each list
                        $('.sortable').each(function() {
                            $(this).children('li').each(function(index) {

                                $(this).find('.number').text(index + 1);
                            });
                        });
                    }
                }).disableSelection();
            });
        });

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

