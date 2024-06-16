@extends('students.layouts.layout-student')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ $assignment->title }}</h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Passage</h3>
            <p>{{ $assignment->description }}</p>
        </div>
        <div class="col-md-6">
            <h3>Questions</h3>
            <div id="timer" class="mb-4 text-danger"></div>
            <form id="assignmentForm" action="{{ route('assignments.submit', $assignment) }}" method="POST">
                @csrf
                @foreach ($questions as $question)
                    <div class="mb-3 p-3 border rounded">
                        @if ($question->question_type !== 'matching_headline')
                            <label><strong>{{ $question->question_text }}</strong></label>
                        @endif
                        @switch($question->question_type)
                            @case('multiple_choice')
                                @foreach ($question->multipleChoiceOptions as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="{{ $option->option_text }}" id="option_{{ $option->id }}">
                                        <label class="form-check-label" for="option_{{ $option->id }}">{{ $option->option_text }}</label>
                                    </div>
                                @endforeach
                                @break
                            @case('true_false')
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="true" id="true_{{ $question->id }}">
                                    <label class="form-check-label" for="true_{{ $question->id }}">True</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="false" id="false_{{ $question->id }}">
                                    <label class="form-check-label" for="false_{{ $question->id }}">False</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $question->id }}" value="not_given" id="not_given_{{ $question->id }}">
                                    <label class="form-check-label" for="not_given_{{ $question->id }}">Not Given</label>
                                </div>
                                @break
                            @case('fill_in_the_blank')
                                <input type="text" name="question_{{ $question->id }}" class="form-control">
                                @break
                            @case('matching_headline')
                                @foreach ($question->matchingHeadlines as $headline)
                                    @if (!empty($headline->match_text))
                                        <div class="mb-2">
                                            <label><strong>{{ $headline->match_text }}</strong></label>
                                            <select name="question_{{ $question->id }}[]" class="form-control">
                                                <option value="">Select Heading</option>
                                                @foreach ($question->matchingHeadlines as $option)
                                                    <option value="{{ $option->headline }}">{{ $option->headline }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endforeach
                                @break
                        @endswitch
                    </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const duration = {{ $assignment->duration }} * 60; // Chuyển đổi sang giây
        let startTime = localStorage.getItem('assignment_start_time');

        if (!startTime) {
            startTime = Date.now();
            localStorage.setItem('assignment_start_time', startTime);
        }

        function updateTimer() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const remaining = duration - elapsed;

            if (remaining <= 0) {
                document.getElementById('assignmentForm').submit();
                localStorage.removeItem('assignment_start_time');
            } else {
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                document.getElementById('timer').textContent = `Time left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
        }

        setInterval(updateTimer, 1000);
        updateTimer();

        document.getElementById('assignmentForm').addEventListener('submit', function() {
            localStorage.removeItem('assignment_start_time');
        });
    });
</script>
@endsection