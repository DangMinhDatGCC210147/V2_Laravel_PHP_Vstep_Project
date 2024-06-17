@extends('admin.layouts.layout-admin')

@section('content')
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="page-title mb-0">MATCHING HEADING</h4>
        </div>
        <div class="col-lg-6">
            <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">{{ isset($assignment) ? 'Edit' : 'New' }} Question For Matching Heading Type of Assignment</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('tableAssignment.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Assignment - {{ isset($assignment) ? 'Edit' : 'New' }} Question For Matching Heading Type</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="p-2">
                            <form action="{{ isset($assignment) ? route('updateAssignment', $assignment->id) : route('storeMatchingType') }}" method="POST">
                                @csrf
                                @if (isset($assignment))
                                    @method('PUT')
                                @endif
                                <!-- Assignment Fields -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignment Details</h5>

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" value="{{ $assignment->title ?? '' }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Passage</label>
                                            <textarea name="description" id="description" rows="10" style="resize: vertical;" class="form-control">{{ $assignment->description ?? '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="duration">Duration (in minutes)</label>
                                            <input type="number" name="duration" id="duration" class="form-control" value="{{ $assignment->duration ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="isEnable">Enable</label>
                                            <select name="isEnable" id="isEnable" class="form-control" required>
                                                <option value="1" {{ isset($assignment) && $assignment->isEnable ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($assignment) && !$assignment->isEnable ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="show_detailed_feedback">Show Detailed Feedback</label>
                                            <input type="hidden" name="show_detailed_feedback" value="0">
                                            <input type="checkbox" name="show_detailed_feedback" id="show_detailed_feedback" class="form-check-input" value="1" {{ isset($assignment) && $assignment->show_detailed_feedback ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <!-- Matching Heading Questions -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Headings and Match Texts</h5>

                                        <input type="hidden" name="questions[0][question_text]" value="This type of assignment does not have a question text.">
                                        <input type="hidden" name="questions[0][question_type]" value="matching_headline">

                                        <div id="headlines-container-0" class="headlines-container">
                                            @if (isset($assignment))
                                            {{-- @dd($questions) --}}
                                                @foreach ($questions->first()->matchingHeadlines as $j => $headline)
                                                    <div class="form-group d-flex align-items-center mb-2">
                                                        <input type="text" name="questions[0][headlines][{{ $j }}][headline]" class="form-control mr-2" placeholder="Heading {{ $j + 1 }}" value="{{ $headline->headline }}" style="flex: 3;" required>
                                                        <input type="text" name="questions[0][headlines][{{ $j }}][match_text]" class="form-control mr-2" placeholder="Match Text {{ $j + 1 }}" value="{{ $headline->match_text }}" style="flex: 1;">
                                                    </div>
                                                @endforeach
                                            @else
                                                @for ($j = 0; $j < $quantity; $j++)
                                                    <div class="form-group d-flex align-items-center mb-2">
                                                        <input type="text" name="questions[0][headlines][{{ $j }}][headline]" class="form-control mr-2" placeholder="Heading {{ $j + 1 }}" style="flex: 3;" required>
                                                        <input type="text" name="questions[0][headlines][{{ $j }}][match_text]" class="form-control mr-2" placeholder="Match Text {{ $j + 1 }}" style="flex: 1;">
                                                    </div>
                                                @endfor
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-secondary add-headline" data-index="0">Add Headline</button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ isset($assignment) ? 'Update' : 'Submit' }}</button>
                            </form>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addHeadlineButton = document.querySelector('.add-headline');

            addHeadlineButton.addEventListener('click', function () {
                const index = addHeadlineButton.getAttribute('data-index');
                const container = document.getElementById('headlines-container-' + index);
                const currentCount = container.children.length;
                const newHeadline = document.createElement('div');
                newHeadline.classList.add('form-group', 'd-flex', 'align-items-center', 'mb-2');
                newHeadline.innerHTML = `
                    <input type="text" name="questions[0][headlines][${currentCount}][headline]" class="form-control mr-2" placeholder="Heading ${currentCount + 1}" style="flex: 3;" required>
                    <input type="text" name="questions[0][headlines][${currentCount}][match_text]" class="form-control mr-2" placeholder="Match Text ${currentCount + 1}" style="flex: 1;">
                `;
                container.appendChild(newHeadline);
            });
        });
    </script>
@endsection




{{-- @extends('admin.layouts.layout-admin')

@section('content')
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="page-title mb-0">MATCHING HEADLINE</h4>
        </div>
        <div class="col-lg-6">
            <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">{{ isset($assignment) ? 'Edit' : 'New' }} Question For Matching Headline Type of Assignment</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('tableAssignment.index') }}">
                <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Assignment - {{ isset($assignment) ? 'Edit' : 'New' }} Question For Matching Headline Type</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="p-2">
                            <form action="{{ isset($assignment) ? route('updateAssignment', $assignment->id) : route('storeMatchingType') }}" method="POST">
                                @csrf
                                @if (isset($assignment))
                                    @method('PUT')
                                @endif
                                <!-- Assignment Fields -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignment Details</h5>

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" value="{{ $assignment->title ?? '' }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Passage</label>
                                            <textarea name="description" id="description" rows="10" style="resize: vertical;" class="form-control">{{ $assignment->description ?? '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="duration">Duration (in minutes)</label>
                                            <input type="number" name="duration" id="duration" class="form-control" value="{{ $assignment->duration ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="isEnable">Enable</label>
                                            <select name="isEnable" id="isEnable" class="form-control" required>
                                                <option value="1" {{ isset($assignment) && $assignment->isEnable ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($assignment) && !$assignment->isEnable ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="show_detailed_feedback">Show Detailed Feedback</label>
                                            <input type="hidden" name="show_detailed_feedback" value="0">
                                            <input type="checkbox" name="show_detailed_feedback" id="show_detailed_feedback" class="form-check-input" value="1" {{ isset($assignment) && $assignment->show_detailed_feedback ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>

                                <!-- Matching Headline Questions -->
                                @if (isset($assignment))
                                    @foreach ($questions as $i => $question)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                                <div class="form-group">
                                                    <label for="question_text_{{ $i }}">Question Text</label>
                                                    <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required>{{ $question->question_text }}</textarea>
                                                </div>

                                                <input type="hidden" name="questions[{{ $i }}][question_type]" value="matching_headline">

                                                <div class="form-group">
                                                    <label>Headlines and Match Texts</label>
                                                    <div id="headlines-container-{{ $i }}" class="headlines-container">
                                                        @foreach ($question->matchingHeadlines as $j => $headline)
                                                            <div class="form-group d-flex align-items-center mb-2">
                                                                <input type="text" name="questions[{{ $i }}][headlines][{{ $j }}][headline]" class="form-control mr-2" placeholder="Headline {{ $j + 1 }}" value="{{ $headline->headline }}" required>
                                                                <input type="text" name="questions[{{ $i }}][headlines][{{ $j }}][match_text]" class="form-control mr-2" placeholder="Match Text {{ $j + 1 }}" value="{{ $headline->match_text }}">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-secondary add-headline" data-index="{{ $i }}">Add Headline</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @for ($i = 0; $i < $quantity; $i++)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                                <div class="form-group">
                                                    <label for="question_text_{{ $i }}">Question Text</label>
                                                    <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required></textarea>
                                                </div>

                                                <input type="hidden" name="questions[{{ $i }}][question_type]" value="matching_headline">

                                                <div class="form-group">
                                                    <label>Headlines and Match Texts</label>
                                                    <div id="headlines-container-{{ $i }}" class="headlines-container">
                                                        <div class="form-group d-flex align-items-center mb-2">
                                                            <input type="text" name="questions[{{ $i }}][headlines][0][headline]" class="form-control mr-2" placeholder="Headline 1" required>
                                                            <input type="text" name="questions[{{ $i }}][headlines][0][match_text]" class="form-control mr-2" placeholder="Match Text 1">
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-secondary add-headline" data-index="{{ $i }}">Add Headline</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif

                                <button type="submit" class="btn btn-primary">{{ isset($assignment) ? 'Update' : 'Submit' }}</button>
                            </form>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addHeadlineButtons = document.querySelectorAll('.add-headline');

            addHeadlineButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const index = button.getAttribute('data-index');
                    const container = document.getElementById('headlines-container-' + index);
                    const currentCount = container.children.length;
                    const newHeadline = document.createElement('div');
                    newHeadline.classList.add('form-group', 'd-flex', 'align-items-center', 'mb-2');
                    newHeadline.innerHTML = `
                        <input type="text" name="questions[${index}][headlines][${currentCount}][headline]" class="form-control mr-2" placeholder="Headline ${currentCount + 1}" required>
                        <input type="text" name="questions[${index}][headlines][${currentCount}][match_text]" class="form-control mr-2" placeholder="Match Text ${currentCount + 1}">
                    `;
                    container.appendChild(newHeadline);
                });
            });
        });
    </script>
@endsection --}}
