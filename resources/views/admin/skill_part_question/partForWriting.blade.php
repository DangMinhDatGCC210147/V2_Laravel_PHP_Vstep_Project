@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">WRITING SKILL</h4>
            </div>
            <div class="col-lg-6">
                <div class="d-none d-lg-block">
                    <ol class="breadcrumb m-0 float-end">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">New Question For {{ str_replace('_', ' ', $partName) }} of
                            {{ $skillName }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('create.skill.part') }}">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Writing Skill - New Question For {{ str_replace('_', ' ', $partName) }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                @if ($partName ==  'Part_1')
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionWriting') }}" method="POST" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 1:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" value="{{ old('questions', $questions->question_text ?? '') }}" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 1:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form> 
                                    @else
                                        <form id="questionForm" action="{{ route('storeQuestionWriting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 1:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" value="" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 1:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    @endif
                                @else
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionWriting') }}" method="POST" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 2:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" value="{{ old('questions', $questions->question_text ?? '') }}" required>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 2:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form> 
                                    @else
                                        <form id="questionForm" action="{{ route('storeQuestionWriting') }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group mt-3">
                                                <label for="question" class="mb-3">Requirement 2:</label>
                                                <input type="text" id="question" name="question" class="form-control" placeholder="Enter requirement here" required value="{{ $questions[0]->question_text ?? '' }}">
                                            </div>
                                            <div class="form-group mt-3">
                                                <label for="passage{{ $partName }}" class="mb-3">Passage 2:</label>
                                                <textarea id="editor{{ $partName }}" class="form-control" name="passage" rows="6" placeholder="Enter passage here"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <script src="{{ asset('admin/assets/build/ckeditor.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('questionForm');
            const editors = {};

            // Initialize CKEditor
            document.querySelectorAll('.form-control[name="passage"]').forEach((textarea, index) => {
                ClassicEditor
                    .create(textarea, {
                        // Configuration options
                    })
                    .then(editor => {
                        editors[index] = editor;
                    })
                    .catch(error => {
                        console.error('Error occurred in initializing the editor:', error);
                    });
            });
        });
    </script>
@endsection
