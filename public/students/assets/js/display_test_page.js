$(document).ready(function () {

    var audioElements = $('audio');
    var countdownInterval;
    var preparationCountdownStarted = false;
    var skillTimers = {
        'Listening': 47 * 60,  // Adjusted for testing purposes
        'Reading': 60 * 60,   // 60 minutes
        'Writing': 60 * 60,   // Adjusted for testing purposes
        'Speaking': 12  // Initial 12 seconds for Speaking
    };
    var currentSkillName = null; // Track the current skill name
    var speakingPart = 0; // Track the current part of the Speaking skill

    // Set CSRF token for all Ajax requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showPart(skillId, partId, shouldResetTimer = false) {
        audioElements.each(function () {
            this.pause();
            this.currentTime = 0;
        });
    
        $('.skill-content').hide();
        var partSelector = $('[data-skill-id="' + skillId + '"][data-part-id="' + partId + '"]');
        partSelector.show();
    
        $('.skill-part-btn').removeClass('btn-warning').addClass('btn-secondary');
        partSelector.removeClass('btn-secondary').addClass('btn-warning');
    
        var skillName = partSelector.closest('.skill-section').find('.skill-part-btn[data-skill-id="' + skillId + '"]').data('skill-name');
        if (currentSkillName !== skillName || shouldResetTimer) {
            startTimer(skillName, shouldResetTimer);
            currentSkillName = skillName;
            currentSkillId = skillId; // Set currentSkillId
        }
    
        adjustLayout(partSelector);
    
        // Kiểm tra nếu kỹ năng là "Speaking" và part là "Part_1"
        if (skillName === "Speaking" && partId === "Part_1") {
            // Vô hiệu hóa các nút part khác
            $('#notification-take-note').show();
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_1"]').prop('disabled', false);
        }
    
        // Save the current skillId and partId to localStorage
        localStorage.setItem('currentSkillId', skillId);
        localStorage.setItem('currentPartId', partId);
        localStorage.setItem('currentSkillName', skillName);
    
        // Enable current skill parts and disable others
        $('.skill-part-btn').each(function () {
            var btnSkillName = $(this).data('skill-name');
            if (btnSkillName === skillName && partId === "Part_1") {
                $(this).prop('disabled', false);
            } else if (btnSkillName === skillName && skillName !== "Speaking") {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    }    

    function adjustLayout(partSelector) {
        var skillText = partSelector.closest('.skill-section').find('.skill-timer').text().trim();
        if (skillText.startsWith('Reading') || skillText.startsWith('Speaking')) {
            $('.content-area, .form-area').removeClass('col-md-12').addClass('col-md-6').css('height', '36vw');
        } else {
            $('.content-area, .form-area').removeClass('col-md-6').addClass('col-md-12').css('height', '100%');
        }

        if (skillText.startsWith('Listening')) {
            $('.form-area').css({ 'overflow': 'scroll', 'max-height': '33vw' });
        } else {
            $('.form-area').css({ 'overflow': 'hidden', 'max-height': '100%' });
        }

        $('#content-row, .content-area, .form-area').scrollTop(0);
    }

    function startTimer(skillName, shouldResetTimer = false) {
        clearInterval(countdownInterval);

        var duration;
        if (shouldResetTimer || !localStorage.getItem(`timer-${skillName}`)) {
            duration = skillTimers[skillName];
        } else {
            duration = parseInt(localStorage.getItem(`timer-${skillName}`), 10);
        }

        var display = $('#skill-timer');
        var timer = duration, minutes, seconds;
        countdownInterval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.text(minutes + ":" + seconds);
            localStorage.setItem(`timer-${skillName}`, timer);

            if (--timer < 0) {
                clearInterval(countdownInterval);
                display.text("00:00");
                localStorage.removeItem(`timer-${skillName}`);
                if (currentSkillName === "Writing") {
                    if (!preparationCountdownStarted) {
                        $('#speakingPrepModal').modal('show');
                        startPreparationCountdown();
                        preparationCountdownStarted = true;
                    }
                } else if (currentSkillName === "Speaking") {
                    handleSpeakingCountdownTransition();
                } else if(currentSkillName === "Listening" || currentSkillName === "Reading") {
                    Swal.fire({
                        title: 'Transitioning!',
                        text: 'Moving to the next skill.',
                        icon: 'info',
                        timer: 2000,
                        timerProgressBar: true,
                        didClose: () => {
                            enableNextSkillButtons(currentSkillName);
                        }
                    });
                }
            }
        }, 1000);
    }

    function handleSpeakingCountdownTransition() {
        speakingPart++;
        $('#notification').hide();
        $('#notification-take-note').hide();
        const testId = $('input[name="test_id"]').val();
        if (speakingPart === 1) {
            const recordingControls = document.querySelector('.recording-controls[data-part-id="Part_1"]');
            skillTimers['Speaking'] = 3 * 5;
            $('#notification').show();
            startRecording(3 * 5 + 1, recordingControls, speakingPart, testId); // Start recording for 3 minutes
        } else if (speakingPart === 2) {
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_2"]').prop('disabled', false);
            skillTimers['Speaking'] = 5;
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_2"]').click();
            $('#notification-take-note').show();
        } else if (speakingPart === 3) {
            const recordingControls = document.querySelector('.recording-controls[data-part-id="Part_2"]');
            skillTimers['Speaking'] = 3 * 5;
            $('#notification').show();
            startRecording(3 * 5 + 1, recordingControls, speakingPart, testId); // Start recording for 3 minutes
        } else if (speakingPart === 4) {
            skillTimers['Speaking'] = 5;
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_3"]').prop('disabled', false);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_3"]').click();
            $('#notification-take-note').show();
        } else if (speakingPart === 5) {
            const recordingControls = document.querySelector('.recording-controls[data-part-id="Part_3"]');
            skillTimers['Speaking'] = 4 * 5;
            $('#notification').show();
            startRecording(4 * 5 + 1, recordingControls, speakingPart, testId); // Start recording for 4 minutes
        }
    
        if (speakingPart <= 5) {
            startTimer('Speaking');
        }
    }      

    function startRecording(duration, questionElement, speakingPart, testId) {
        const questionId = questionElement.getAttribute('data-question-id');
        const startButton = questionElement.querySelector('.startRecording');
        const stopButton = questionElement.querySelector('.stopRecording');
        const audioPlayback = questionElement.querySelector('.audioPlayback');
        const skillId = questionElement.getAttribute('data-skill-id');
        // const testId = $(this).closest('form').find('input[name="test_id"]').val();

        console.log("Start Recording for Question ID: " + questionId + ", Skill ID: " + skillId);
        console.log("Start Recording for Speaking Part: " + speakingPart);
        console.log("Start Recording for Test ID: " + testId);

        let recorder;

        navigator.mediaDevices.getUserMedia({
            audio: true
        })
            .then(stream => {
                const options = {
                    mimeType: 'audio/mp3',
                    recorderType: RecordRTC.StereoAudioRecorder,
                    desiredSampRate: 16000
                };
                recorder = new RecordRTC(stream, options);
                recorder.startRecording();

                startButton.disabled = true;
                stopButton.disabled = false;

                setTimeout(() => {
                    recorder.stopRecording(() => {
                        const audioBlob = recorder.getBlob();
                        const audioUrl = URL.createObjectURL(audioBlob);
                        audioPlayback.src = audioUrl;
                        audioPlayback.hidden = false;

                        let formData = new FormData();
                        formData.append('recording', new File([audioBlob], "recording.mp3", {
                            type: 'audio/mp3'
                        }));
                        formData.append('skill_id', skillId);
                        formData.append('question_id', questionId);
                        formData.append('test_id', testId);
                        fetch('/saveRecording', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Success:', data);
                                // Kiểm tra nếu đang ở Part_3 của Speaking thì tự động nộp bài
                                if (speakingPart === 5) {
                                    $(document).ready(function() {
                                        Swal.fire({
                                            title: 'Bạn đã hoàn thành bài kiểm tra',
                                            text: "Hệ thống sẽ nộp bài tự động",
                                            icon: 'info',
                                            showCancelButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            willClose: () => {
                                                setTimeout(function() {
                                                    $("#submitTestButton").click();
                                                }, 500);
                                            }
                                        });
                                    }); 
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });
                    stopButton.disabled = true;
                }, duration * 1000); // Convert seconds to milliseconds
            }).catch(error => console.error('Error:', error));
    }

    function enableNextSkillButtons(currentSkillName) {
        var skillNames = $('.skill-part-btn').map(function () {
            return $(this).data('skill-name');
        }).get();
        var uniqueSkillNames = [...new Set(skillNames)];
        var nextSkillName = uniqueSkillNames[uniqueSkillNames.indexOf(currentSkillName) + 1];
        if (nextSkillName) {
            $('.skill-part-btn[data-skill-name="' + nextSkillName + '"]').prop('disabled', false);
            $('.skill-part-btn[data-skill-name="' + currentSkillName + '"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="' + nextSkillName + '"]:first').click();
        }
    }

    function startPreparationCountdown() {
        var timer = 5;
        var interval = setInterval(function () {
            $('#prepTimer').text(--timer);
            if (timer <= 0) {
                clearInterval(interval);
                $('#speakingPrepModal').modal('hide');
                enableNextSkillButtons("Writing");
                initializeFunctions();
            }
        }, 1000);
    }

    function initializeFunctions() {
        // Kiểm tra xem có skillId và partId nào được lưu trong localStorage không
        var savedSkillId = localStorage.getItem('currentSkillId');
        var savedPartId = localStorage.getItem('currentPartId');
        var savedSkillName = localStorage.getItem('currentSkillName');
    
        if (savedSkillId && savedPartId) {
            showPart(savedSkillId, savedPartId, false);
        } else {
            var initialSkillPartBtn = $('.skill-part-btn').first();
            showPart(initialSkillPartBtn.data('skill-id'), initialSkillPartBtn.data('part-id'), true);
            savedSkillName = initialSkillPartBtn.data('skill-name');  // Set skill name for the first load
        }
    
        // Kích hoạt các nút part của skill hiện tại, vô hiệu hóa các skill khác
        $('.skill-part-btn').each(function () {
            var btnSkillName = $(this).data('skill-name');
            if (btnSkillName === savedSkillName) {
                $(this).prop('disabled', false);
            } else {
                $(this).prop('disabled', true);
            }
        });
    
        // Vô hiệu hóa các phần của kỹ năng Speaking khi ở Part_1
        if (localStorage.getItem('currentSkillName') === "Speaking" && localStorage.getItem('currentPartId') === "Part_1") {
            $('.skill-part-btn[data-skill-name="Speaking"]').prop('disabled', true);
            $('.skill-part-btn[data-skill-name="Speaking"][data-part-id="Part_1"]').prop('disabled', false);
        }
    
        $('.skill-part-btn').on('click', function () {
            showPart($(this).data('skill-id'), $(this).data('part-id'), false);
        });
    
        updateAnsweredCount(savedSkillId, savedPartId);
    
        $('input[type="radio"]').on('change', function () {
            var skillContent = $(this).closest('.skill-content');
            updateAnsweredCount(skillContent.data('skill-id'), skillContent.data('part-id'));
        });
    
        $('.skill-part-btn').on('click', function () {
            updateAnsweredCount($(this).data('skill-id'), $(this).data('part-id'));
        });
    
        $('textarea').on('input', function () {
            var questionId = $(this).attr('id').split('_')[1];
            $(`#wordCount_${questionId}`).text(`${countWords($(this).val())} words`);
        });
    
        $('#save-btn').click(function (e) {
            e.preventDefault();
            saveForms();
        });
    
        $('#next-skill-btn').click(function () {
            handleNextSkillButtonClick();
        });
    
        $('input[type="radio"]').each(function () {
            restoreRadioSelection($(this));
        });
    
        $('textarea').each(function () {
            restoreTextareaContent($(this));
        });
    
        $('textarea').on('input', function () {
            saveTextareaContent($(this));
        });
    
        $('input[type="radio"]').change(function () {
            saveRadioSelection($(this));
        });
    }       

    function updateAnsweredCount(skillId, partId) {
        var questions = $(`.skill-content[data-skill-id="${skillId}"][data-part-id="${partId}"] .options-container`);
        var answeredCount = questions.filter(function () {
            return $(this).find('input[type="radio"]:checked').length > 0;
        }).length;
        $('#answered-count').text(`Số câu đã hoàn thành: ${answeredCount}/${questions.length}`);
    }

    function countWords(text) {
        return text.trim().split(/\s+/).filter(word => word.length > 0).length;
    }

    function saveForms() {
        var totalForms = $('.testForm').length;
        var completedForms = 0;
        var popupShown = false;

        $('.testForm').each(function () {
            var form = $(this);
            $.post(form.attr('action'), form.serialize())
                .done(function () {
                    completedForms++;
                    if ((completedForms === totalForms - 1 && !popupShown) || completedForms === 1) {
                        popupShown = true;
                        Swal.fire({
                            title: 'Success!',
                            text: 'All responses have been saved successfully.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                })
                .fail(function () {
                    console.error('Error saving data for form with action: ' + form.attr('action'));
                });
        });
    }

    function handleNextSkillButtonClick() {
        var currentSkillName = $('.skill-part-btn:not(:disabled)').first().data('skill-name');
        var skillNames = $('.skill-part-btn').map(function () {
            return $(this).data('skill-name');
        }).get();
        var uniqueSkillNames = [...new Set(skillNames)];
        var nextSkillName = uniqueSkillNames[uniqueSkillNames.indexOf(currentSkillName) + 1];
    
        if (nextSkillName) {
            var confirmationText = null;
    
            if ((currentSkillName === "Listening" && nextSkillName === "Reading") || (currentSkillName === "Reading" && nextSkillName === "Writing")) {
                confirmationText = "You won't be able to go back to the previous skill!";
            } else if (currentSkillName === "Writing" && nextSkillName === "Speaking") {
                confirmationText = "You will have 60 seconds to prepare after you click OK.";
            }
    
            if (confirmationText) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: confirmationText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, continue!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (currentSkillName === "Writing" && nextSkillName === "Speaking") {
                            if (!preparationCountdownStarted) {
                                $('#speakingPrepModal').modal('show');
                                startPreparationCountdown();
                                preparationCountdownStarted = true;
                            }
                        } else {
                            enableNextSkillButtons(currentSkillName);
                        }
                    }
                });
            } else {
                enableNextSkillButtons(currentSkillName);
            }
        } else if (currentSkillName === "Speaking") {
            // Nếu currentSkillName là "Speaking" và không có kỹ năng tiếp theo
            Swal.fire({
                title: 'Final Skill',
                text: 'This is the final skill of the test.',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    }    

    function restoreRadioSelection(radioButton) {
        var questionId = radioButton.attr('name');
        var savedValue = localStorage.getItem(questionId);

        if (savedValue && radioButton.val() === savedValue) {
            radioButton.prop('checked', true);
        }
    }

    function restoreTextareaContent(textarea) {
        var questionId = textarea.attr('id');
        var savedContent = localStorage.getItem(questionId);
        if (savedContent !== null) {
            textarea.val(savedContent);
        }
    }

    function saveTextareaContent(textarea) {
        var questionId = textarea.attr('id');
        var content = textarea.val();
        localStorage.setItem(questionId, content);
    }

    function saveRadioSelection(radioButton) {
        var questionId = radioButton.attr('name');
        var selectedValue = radioButton.val();
        localStorage.setItem(questionId, selectedValue);
    }

    initializeFunctions();

    $('#reset-btn').click(function () {
        localStorage.clear();
        location.reload();
    });

    // window.onbeforeunload = function() {
    //     return "Bạn có chắc chắn muốn tải lại trang? Mọi thay đổi chưa được lưu có thể sẽ mất.";
    // };
});
