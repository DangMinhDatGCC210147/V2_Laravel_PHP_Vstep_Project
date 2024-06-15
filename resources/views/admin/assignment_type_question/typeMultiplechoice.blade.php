@extends('admin.layouts.layout-admin')

@section('content')
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="page-title mb-0">MULTIPLE CHOICE</h4>
        </div>
        <div class="col-lg-6">
            <div class="d-none d-lg-block">
                <ol class="breadcrumb m-0 float-end">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">New Question For Multiple Choice Type of Assignment</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="py-3 py-lg-4">
    <div class="row">
        <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('create.assignemnt') }}">
                <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Assignment - New Question For Multiple Choice Type</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="p-2">

                            <form action="{{ route('storeMultiplechoiceType') }}" method="POST">
                                @csrf

                                <!-- Assignment Fields -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Assignment Details</h5>

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" class="form-control"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="isEnable">Enable</label>
                                            <select name="isEnable" id="isEnable" class="form-control" required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="show_detailed_feedback">Show Detailed Feedback</label>
                                            <input type="checkbox" name="show_detailed_feedback" id="show_detailed_feedback" class="form-check-input">
                                        </div>
                                    </div>
                                </div>

                                <!-- Multiple Choice Questions -->
                                @for ($i = 0; $i < $quantity; $i++)
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Question {{ $i + 1 }}</h5>

                                            <div class="form-group">
                                                <label for="question_text_{{ $i }}">Question Text</label>
                                                <textarea name="questions[{{ $i }}][question_text]" id="question_text_{{ $i }}" class="form-control" rows="3" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Options</label>
                                                <div id="options-container-{{ $i }}">
                                                    @for ($j = 0; $j < 4; $j++)
                                                        <div class="form-group d-flex align-items-center">
                                                            <input type="text" name="questions[{{ $i }}][options][{{ $j }}][option_text]" class="form-control mr-2" placeholder="Option {{ $j + 1 }}" required>
                                                            <label class="mr-2">Correct</label>
                                                            <input type="radio" name="questions[{{ $i }}][is_correct]" value="{{ $j }}" required>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
@endsection
