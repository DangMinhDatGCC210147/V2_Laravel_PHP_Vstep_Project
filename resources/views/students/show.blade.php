@extends('students.layouts.layout-student')

@section('content')
    @php
        $badgeColors = [
            'Listening' => 'bg-primary',
            'Speaking' => 'bg-danger',
            'Reading' => 'bg-success',
            'Writing' => 'bg-secondary',
        ];
        $skillIds = $skills->pluck('id')->sortDesc()->values(); // Lấy danh sách các skill ID theo thứ tự giảm dần
    @endphp
    <div class="px-3">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="card">
                <div class="row text-dark card-header navbar">
                    <div class="col-md-1">
                        <button class="btn btn-warning d-flex justify-content-center" id="theme-mode"><i
                                class="bx bx-moon font-size-18"></i></button>
                    </div>
                    <div class="col-md-4 text-start">
                        <h2>{{ $test->test_name }}</h2>
                    </div>
                    <div class="col-md-3 text-center">
                        <h2>Timer:
                            <span class="badge bg-primary" id="skill-timer" style="display: inline;">
                                00:00
                            </span>
                            <span class="badge bg-primary" id="speaking-skill-timer" style="display: none;">
                                00:00
                            </span>
                        </h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge bg-info" id="answered-count"><span style="font-size: 15px"></span></div>
                        <button class="btn btn-success" id="submitTestButton">Nộp bài</button>
                    </div>
                </div>
                <div class="m-2">
                    <div class="row" id="content-row">
                        <div class="col-md-6 overflow-auto border-style" style="height: 100%;" id="content-area">
                            @foreach ($test->testSkills as $skill)
                                @foreach ($skill->readingsAudios as $readingAudio)
                                    <div class="mb-3 content-block skill-{{ $skill->id }}-part-{{ $readingAudio->part_name }}"
                                        style="display: none;">
                                        @if ($skill->skill_name == 'Writing')
                                            @php
                                                $questionForPart = $skill->questions->firstWhere(
                                                    'part_name',
                                                    $readingAudio->part_name,
                                                );
                                            @endphp
                                            @if ($questionForPart)
                                                <strong>
                                                    <p>Question {{ $questionForPart->question_number }}:
                                                        {{ $questionForPart->question_text }}</p>
                                                </strong>
                                            @endif
                                        @endif
                                        @if ($readingAudio->isAudio())
                                            <audio controls controlsList="nodownload" id="audioPlayer">
                                                <source src="{{ asset('storage/' . $readingAudio->reading_audio_file) }}"
                                                    type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @elseif ($readingAudio->isImage())
                                            <img src="{{ asset('storage/' . $readingAudio->reading_audio_file) }}"
                                                alt="Skill Image" class="img-fluid">
                                        @elseif ($readingAudio->isText())
                                            <p>{!! $readingAudio->reading_audio_file !!}</p>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                            <div class="card notification text-bg-danger mb-3" id="notification"
                                style="display: none; max-width: 40rem;">
                                <div class="card-header">
                                    CHÚ Ý:
                                </div>
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        <h5>BÀI NÓI ĐANG ĐƯỢC THU ÂM TRỰC TIẾP, TRONG QUÁ TRÌNH THU ÂM KHÔNG ĐƯỢC
                                            TƯƠNG TÁC VỚI HỆ THỐNG</h5>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 overflow-auto border-style" style="height: 30vw;" id="form-area">
                            @foreach ($skills as $skill)
                                <form
                                    @if ($skill->skill_name == 'Listening') action="/saveListening"
                                    @elseif ($skill->skill_name == 'Speaking') action="/saveSpeaking"
                                    @elseif ($skill->skill_name == 'Reading') action="/saveReading"
                                    @elseif ($skill->skill_name == 'Writing') action="/saveWriting" @endif
                                    method="post" id="testForm-{{ $skill->id }}" class="testForm">
                                    @csrf
                                    @php
                                        $recordedParts = []; // Initialize outside the inner foreach loop
                                    @endphp
                                    @foreach ($skill->questions as $index => $question)
                                        <div class="mb-3 question-block skill-{{ $skill->id }}-part-{{ $question->part_name }}"
                                            style="display: none;">
                                            @if ($skill->skill_name != 'Writing')
                                                <strong>
                                                    <p>Question {{ $question->question_number }}:
                                                        {{ $question->question_text }}</p>
                                                </strong>
                                            @endif

                                            @if ($skill->skill_name == 'Writing')
                                                <div class="showCount d-flex justify-content-end">
                                                    <strong>
                                                        <div id="wordCount_{{ $question->id }}" class="countWord">0 words
                                                        </div>
                                                    </strong>
                                                </div>
                                                <textarea name="responses[{{ $question->id }}]" id="response_{{ $question->id }}" class="form-control" rows="17"
                                                    placeholder="Type your response here..."></textarea>
                                            @elseif ($skill->skill_name == 'Speaking')
                                                @foreach ($question->options as $optionIndex => $option)
                                                    <div class="form-check">
                                                        <label class="form-check-label" for="option_{{ $option->id }}">
                                                            {{ $optionIndex + 1 }}. {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @if ($index == 1 && !in_array($question->part_name, $recordedParts))
                                                    @php
                                                        $recordedParts[] = $question->part_name;
                                                    @endphp
                                                    <button type="button" onclick="startRecording(this);"
                                                        data-part-id="{{ $question->part_name }}">Start Recording</button>
                                                    <button type="button" onclick="stopRecording(this);"
                                                        data-part-id="{{ $question->part_name }}" disabled>Stop
                                                        Recording</button>
                                                    <audio id="audio_{{ $question->part_name }}" controls></audio>
                                                @endif
                                            @else
                                                @foreach ($question->options as $optionIndex => $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="responses[{{ $question->id }}]"
                                                            id="option_{{ $option->id }}"
                                                            value="{{ $option->option_text }}">
                                                        <label class="form-check-label" for="option_{{ $option->id }}">
                                                            {{ chr(65 + $optionIndex) }}. {{ $option->option_text }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Model for PopUp --}}
    <!-- Bootstrap Modal for Speaking Preparation -->
    <div class="modal fade" id="speakingPrepModal" tabindex="-1" role="dialog" aria-labelledby="speakingPrepModalLabel"
        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="speakingPrepModalLabel">Chuẩn bị cho phần Speaking</h5>
                </div>
                <div class="modal-body">
                    <div class="bound d-flex justify-content-center">
                        <div class="image-avatar d-flex justify-content-center">
                            <img src="{{ asset('students/assets/images/boy.png') }}" alt="Boy with headphone">
                        </div>
                    </div>
                    <div class="caution">
                        <h3>BẠN ĐEO TAI NGHE ĐỂ LÀM BÀI THI NÓI</h3>
                    </div>
                    <div class="caution">
                        <h5>Bạn có 60 giây để chuẩn bị</h5>
                    </div>
                    <h4 id="prepTimer">60</h4>
                    <div class="note">
                        <h5>BẠN SẼ ĐƯỢC THU ÂM TRỰC TIẾP</h5>
                    </div>
                    <div class="note">
                        <h5>TRONG LÚC THU ÂM KHÔNG TƯƠNG TÁC VỚI HỆ THỐNG</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Start -->
    <footer class="footer">
        @foreach ($skills as $skill)
            <div class="skill-section">
                <div class="btn-group">
                    @php
                        $usedParts = [];
                        $buttonIndex = 0;
                    @endphp
                    @foreach ($skill->questions as $part)
                        <!-- Assuming each skill has parts -->
                        @if (!in_array($part->part_name, $usedParts))
                            <button class="btn btn-secondary btn-sm skill-part-btn"
                                data-skill-part="skill-{{ $skill->id }}-part-{{ $part->part_name }}"
                                data-part-index="{{ $buttonIndex }}"
                                data-time-limit="{{ $skill->time_limit }}" data-skill-id="{{ $skill->id }}">
                                {{ str_replace('_', ' ', $part->part_name) }}
                            </button>
                            @php
                                $usedParts[] = $part->part_name;
                                $buttonIndex++; 
                            @endphp
                        @endif
                    @endforeach
                </div>
                <div class="skill-timer badge {{ $badgeColors[$skill->skill_name] ?? 'bg-primary' }}">
                    {{ $skill->skill_name }} -
                    {{ $skill->time_limit == '01:00:00' ? '60' : explode(':', $skill->time_limit)[1] }}
                </div>
            </div>
        @endforeach

        <!-- Controls Column -->
        <div class="skill-section">
            <div class="btn-group">
                <button class="btn btn-info mb-2" id="next-skill-btn">Tiếp tục</button>
                <button class="btn btn-primary mb-2" id="save-btn">Lưu bài</button>
                <button class="btn btn-danger mb-2" id="reset-btn">Làm mới</button>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        var audioElements;
        var skillIds = @json($skillIds);
        var currentSkillIndex = 0; // Chỉ số của kỹ năng hiện tại
        var initialSkillPart = skillIds[currentSkillIndex];
        var skillPartIdentifier = 'skill-' + initialSkillPart + '-part-Part_1';
        var answeredCount = {};
        var partAnswered = {};
        var countdownTimer;
        var timeRemaining;
        var currentSkillTimeLimit;

        @if ($skills->isNotEmpty())
            window.currentSkillPart =
                "skill-{{ $skills->first()->id }}-part-{{ $skills->first()->questions->first()->part_name }}";
            window.currentSkillId = "{{ $skills->first()->id }}";
        @endif
    </script>
    <script src="{{ asset('students/assets/js/test_page.js') }}"></script>
    <script src="{{ asset('students/assets/js/record_speaking.js') }}"></script>
@endsection
