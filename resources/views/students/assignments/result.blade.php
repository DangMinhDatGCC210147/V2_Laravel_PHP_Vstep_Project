@extends('students.layouts.layout-student')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ $assignment->title }} - Result</h1>
    <div class="row">
        <div class="col-md-6">
            <h3>Passage</h3>
            <p>{{ $assignment->description }}</p>
        </div>
        <div class="col-md-6">
            <p class="mb-4">You got {{ $correctAnswers }} out of {{ $totalQuestions }} correct.</p>
            @if ($assignment->show_detailed_feedback)
                <h3>Detailed Feedback</h3>
                @foreach ($answers as $answer)
                    <div class="mb-3 p-3 border rounded">
                        <label><strong>{{ $answer->question->question_text }}</strong></label>
                        @switch($answer->question->question_type)
                            @case('multiple_choice')
                                @foreach ($answer->question->multipleChoiceOptions as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_{{ $answer->question->id }}" value="{{ $option->option_text }}" id="option_{{ $option->id }}" disabled {{ $option->option_text == $answer->answer_text ? 'checked' : '' }}>
                                        <label class="form-check-label" for="option_{{ $option->id }}">{{ $option->option_text }}
                                            @if ($option->is_correct)
                                                <span class="text-success">(Correct)</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                                @break
                            @case('true_false')
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $answer->question->id }}" value="true" id="true_{{ $answer->question->id }}" disabled {{ $answer->answer_text == 'true' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="true_{{ $answer->question->id }}">True
                                        @if ($answer->question->trueFalse->correct_answer == 'true')
                                            <span class="text-success">(Correct)</span>
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $answer->question->id }}" value="false" id="false_{{ $answer->question->id }}" disabled {{ $answer->answer_text == 'false' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="false_{{ $answer->question->id }}">False
                                        @if ($answer->question->trueFalse->correct_answer == 'false')
                                            <span class="text-success">(Correct)</span>
                                        @endif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="question_{{ $answer->question->id }}" value="not_given" id="not_given_{{ $answer->question->id }}" disabled {{ $answer->answer_text == 'not_given' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="not_given_{{ $answer->question->id }}">Not Given
                                        @if ($answer->question->trueFalse->correct_answer == 'not_given')
                                            <span class="text-success">(Correct)</span>
                                        @endif
                                    </label>
                                </div>
                                @break
                            @case('fill_in_the_blank')
                                <input type="text" name="question_{{ $answer->question->id }}" class="form-control" value="{{ $answer->answer_text }}" disabled>
                                <p class="mt-2">Correct Answer: <span class="text-success">{{ $answer->question->fillInTheBlanks->pluck('correct_answer')->join(', ') }}</span></p>
                                @break
                            @case('matching_headline')
                                @foreach ($answer->question->matchingHeadlines as $index => $headline)
                                    @if (!empty($headline->match_text))
                                        <div class="mb-2">
                                            <label><strong>{{ $headline->match_text }}</strong></label>
                                            <select name="question_{{ $answer->question->id }}[]" class="form-control" disabled>
                                                @foreach ($answer->question->matchingHeadlines as $option)
                                                    <option value="{{ $option->headline }}" {{ $option->headline == ($answer->answer_text[$index] ?? '') ? 'selected' : '' }}>{{ $option->headline }}</option>
                                                @endforeach
                                            </select>
                                            <p class="mt-2">Correct Answer: <span class="text-success">{{ $headline->headline }}</span></p>
                                        </div>
                                    @endif
                                @endforeach
                                @break
                        @endswitch
                        <p class="mt-2">Your Answer: <span class="{{ $answer->is_correct ? 'text-success' : 'text-danger' }}">{{ is_array($answer->answer_text) ? implode(', ', $answer->answer_text) : $answer->answer_text }}</span></p>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
