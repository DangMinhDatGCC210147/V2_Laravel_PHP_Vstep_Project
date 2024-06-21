@extends('admin.layouts.layout-admin')

@section('content')
    <div class="py-3 py-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="page-title mb-0">READING SKILL</h4>
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
            <div class="col-lg-6"><a class="btn btn-secondary" href="{{ route('questionBank.reading') }}">
                    <i class="mdi mdi-arrow-left-bold"></i> Turn back to previous page</a>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Reading Skill - New Question For {{ str_replace('_', ' ', $partName) }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="p-2">
                                @if ($partName == 'Part_1')
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionReading') }}" method="POST"
                                            id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 1:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here" required>{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            @foreach ($questions as $index => $question)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $index }}">Question
                                                        {{ $question->question_number }}:</label>
                                                    <input type="hidden" name="questions[{{ $index }}][id]"
                                                        value="{{ $question->id }}">
                                                    <input type="text" id="question{{ $index }}"
                                                        name="questions[{{ $index }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $index + 1 }}"
                                                        value="{{ old('questions.' . $index . '.text', $question->question_text ?? '') }}"
                                                        required>
                                                    <div class="mt-2">
                                                        @foreach ($question->options as $optionIndex => $option)
                                                            <div class="form-check mt-2">
                                                                <input type="hidden"
                                                                    name="questions[{{ $index }}][options][{{ $optionIndex }}][id]"
                                                                    value="{{ $option->id }}">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $index }}][correct_option]"
                                                                    id="option{{ $index }}{{ $optionIndex }}"
                                                                    value="{{ $option->id }}"
                                                                    {{ old('questions.' . $index . '.correct_option', $option->correct_answer == 1 ? $option->id : '') == $option->id ? 'checked' : '' }}>
                                                                <label class="form-check"
                                                                    for="option{{ $index }}{{ $optionIndex }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $index }}][options][{{ $optionIndex }}][text]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $optionIndex + 1 }}"
                                                                        value="{{ old('questions.' . $index . '.options.' . $optionIndex . '.text', $option->option_text ?? '') }}">
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionReading') }}" method="POST"
                                            id="questionForm">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 1:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here"></textarea>
                                            </div>
                                            @for ($i = 1; $i <= 10; $i++)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $i }}">Question
                                                        {{ $i }}:</label>
                                                    <input type="text" id="question{{ $i }}"
                                                        name="questions[{{ $i }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $i }}" required>
                                                    <div class="mt-2">
                                                        @for ($j = 1; $j <= 4; $j++)
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $i }}][correct_option]"
                                                                    id="option{{ $i }}{{ $j }}"
                                                                    value="{{ $j }}" required>
                                                                <label class="form-check"
                                                                    for="option{{ $i }}{{ $j }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $i }}][options][{{ $j }}]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $j }}" required>
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endfor
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    @endif
                                @elseif ($partName == 'Part_2')
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionReading') }}" method="POST" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 2:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            @foreach ($questions as $index => $question)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $index }}">Question
                                                        {{ $question->question_number }}:</label>
                                                    <input type="hidden" name="questions[{{ $index }}][id]"
                                                        value="{{ $question->id }}">
                                                    <input type="text" id="question{{ $index }}"
                                                        name="questions[{{ $index }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $index + 1 }}"
                                                        value="{{ old('questions.' . $index . '.text', $question->question_text ?? '') }}">
                                                    <div class="mt-2">
                                                        @foreach ($question->options as $optionIndex => $option)
                                                            <div class="form-check mt-2">
                                                                <input type="hidden"
                                                                    name="questions[{{ $index }}][options][{{ $optionIndex }}][id]"
                                                                    value="{{ $option->id }}">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $index }}][correct_option]"
                                                                    id="option{{ $index }}{{ $optionIndex }}"
                                                                    value="{{ $option->id }}"
                                                                    {{ old('questions.' . $index . '.correct_option', $option->correct_answer == 1 ? $option->id : '') == $option->id ? 'checked' : '' }}>
                                                                <label class="form-check"
                                                                    for="option{{ $index }}{{ $optionIndex }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $index }}][options][{{ $optionIndex }}][text]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $optionIndex + 1 }}"
                                                                        value="{{ old('questions.' . $index . '.options.' . $optionIndex . '.text', $option->option_text ?? '') }}">
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionReading') }}" method="POST"
                                            id="questionForm">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 2:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here"></textarea>
                                            </div>
                                            @for ($i = 11; $i <= 20; $i++)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $i }}">Question
                                                        {{ $i }}:</label>
                                                    <input type="text" id="question{{ $i }}"
                                                        name="questions[{{ $i }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $i }}" required>
                                                    <div class="mt-2">
                                                        @for ($j = 1; $j <= 4; $j++)
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $i }}][correct_option]"
                                                                    id="option{{ $i }}{{ $j }}"
                                                                    value="{{ $j }}" required>
                                                                <label class="form-check"
                                                                    for="option{{ $i }}{{ $j }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $i }}][options][{{ $j }}]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $j }}" required>
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endfor
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    @endif
                                    {{-- USE TO SAVE AND UPDATE PART 3 --}}
                                @elseif ($partName == 'Part_3')
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionReading') }}" method="POST" id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug" value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName" value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName" value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 3:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            @foreach ($questions as $index => $question)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $index }}">Question
                                                        {{ $question->question_number }}:</label>
                                                    <input type="hidden" name="questions[{{ $index }}][id]"
                                                        value="{{ $question->id }}">
                                                    <input type="text" id="question{{ $index }}"
                                                        name="questions[{{ $index }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $index + 1 }}"
                                                        value="{{ old('questions.' . $index . '.text', $question->question_text ?? '') }}">
                                                    <div class="mt-2">
                                                        @foreach ($question->options as $optionIndex => $option)
                                                            <div class="form-check mt-2">
                                                                <input type="hidden"
                                                                    name="questions[{{ $index }}][options][{{ $optionIndex }}][id]"
                                                                    value="{{ $option->id }}">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $index }}][correct_option]"
                                                                    id="option{{ $index }}{{ $optionIndex }}"
                                                                    value="{{ $option->id }}"
                                                                    {{ old('questions.' . $index . '.correct_option', $option->correct_answer == 1 ? $option->id : '') == $option->id ? 'checked' : '' }}>
                                                                <label class="form-check"
                                                                    for="option{{ $index }}{{ $optionIndex }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $index }}][options][{{ $optionIndex }}][text]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $optionIndex + 1 }}"
                                                                        value="{{ old('questions.' . $index . '.options.' . $optionIndex . '.text', $option->option_text ?? '') }}">
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionReading') }}" method="POST" id="questionForm">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 3:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here"></textarea>
                                            </div>
                                            @for ($i = 21; $i <= 30; $i++)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $i }}">Question
                                                        {{ $i }}:</label>
                                                    <input type="text" id="question{{ $i }}"
                                                        name="questions[{{ $i }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $i }}"
                                                        value="{{ old('questions.' . $i . '.text') }}" required>
                                                    <div class="mt-2">
                                                        @for ($j = 1; $j <= 4; $j++)
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $i }}][correct_option]"
                                                                    id="option{{ $i }}{{ $j }}"
                                                                    value="{{ $j }}" required>
                                                                <label class="form-check"
                                                                    for="option{{ $i }}{{ $j }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $i }}][options][{{ $j }}]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $j }}"
                                                                        value="{{ old('questions.' . $i . '.options.' . $j) }}"
                                                                        required>
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endfor
                                            <hr>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </form>
                                    @endif
                                @else
                                    @if (isset($questions))
                                        <form action="{{ route('updateQuestionReading') }}" method="POST"
                                            id="questionForm">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" id="slug" name="slug"
                                                value="{{ $slug->id }}">
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 4:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here">{{ old('passage', $passage->reading_audio_file ?? '') }}</textarea>
                                            </div>
                                            @foreach ($questions as $index => $question)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $index }}">Question
                                                        {{ $question->question_number }}:</label>
                                                    <input type="hidden" name="questions[{{ $index }}][id]"
                                                        value="{{ $question->id }}">
                                                    <input type="text" id="question{{ $index }}"
                                                        name="questions[{{ $index }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $index + 1 }}"
                                                        value="{{ old('questions.' . $index . '.text', $question->question_text ?? '') }}">
                                                    <div class="mt-2">
                                                        @foreach ($question->options as $optionIndex => $option)
                                                            <div class="form-check mt-2">
                                                                <input type="hidden"
                                                                    name="questions[{{ $index }}][options][{{ $optionIndex }}][id]"
                                                                    value="{{ $option->id }}">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $index }}][correct_option]"
                                                                    id="option{{ $index }}{{ $optionIndex }}"
                                                                    value="{{ $option->id }}"
                                                                    {{ old('questions.' . $index . '.correct_option', $option->correct_answer == 1 ? $option->id : '') == $option->id ? 'checked' : '' }}>
                                                                <label class="form-check"
                                                                    for="option{{ $index }}{{ $optionIndex }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $index }}][options][{{ $optionIndex }}][text]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $optionIndex + 1 }}"
                                                                        value="{{ old('questions.' . $index . '.options.' . $optionIndex . '.text', $option->option_text ?? '') }}">
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                            <hr>
                                            <button type="submit" class="btn btn-warning">Save Changes</button>
                                        </form>
                                    @else
                                        <form action="{{ route('storeQuestionReading') }}" method="POST"
                                            id="questionForm">
                                            @csrf
                                            <input type="hidden" id="partName" name="partName"
                                                value="{{ $partName }}">
                                            <input type="hidden" id="skillName" name="skillName"
                                                value="{{ $skillName }}">
                                            <div class="form-group">
                                                <label for="textareaInput">Passage 4:</label>
                                                <textarea id="textareaInput" name="passage" class="form-control" rows="8" placeholder="Enter text here"></textarea>
                                            </div>
                                            @for ($i = 31; $i <= 40; $i++)
                                                <div class="form-group mt-3">
                                                    <label for="question{{ $i }}">Question
                                                        {{ $i }}:</label>
                                                    <input type="text" id="question{{ $i }}"
                                                        name="questions[{{ $i }}][text]"
                                                        class="form-control mt-2"
                                                        placeholder="Enter question {{ $i }}" required>
                                                    <div class="mt-2">
                                                        @for ($j = 1; $j <= 4; $j++)
                                                            <div class="form-check mt-2">
                                                                <input class="form-check-input" type="radio"
                                                                    name="questions[{{ $i }}][correct_option]"
                                                                    id="option{{ $i }}{{ $j }}"
                                                                    value="{{ $j }}" required>
                                                                <label class="form-check"
                                                                    for="option{{ $i }}{{ $j }}">
                                                                    <input type="text"
                                                                        name="questions[{{ $i }}][options][{{ $j }}]"
                                                                        class="form-control"
                                                                        placeholder="Option {{ $j }}"
                                                                        required>
                                                                </label>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endfor
                                            <hr>
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
        // document.addEventListener('DOMContentLoaded', function() {
        //     const inputs = document.querySelectorAll('input, textarea');
        //     inputs.forEach(input => {
        //         // Load saved data from Local Storage
        //         const savedValue = localStorage.getItem(input.name);
        //         if (savedValue) {
        //             input.value = savedValue; // Set the value if it exists
        //         }

        //         // Update Local Storage on change
        //         input.addEventListener('change', () => {
        //             localStorage.setItem(input.name, input.value);
        //         });
        //     });

        //     // For radio buttons, check if saved value matches current and check it
        //     const radios = document.querySelectorAll('input[type="radio"]');
        //     radios.forEach(radio => {
        //         const savedValue = localStorage.getItem(radio.name);
        //         if (radio.value === savedValue) {
        //             radio.checked = true;
        //         }

        //         radio.addEventListener('change', () => {
        //             localStorage.setItem(radio.name, radio.value);
        //         });
        //     });
        // });

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
