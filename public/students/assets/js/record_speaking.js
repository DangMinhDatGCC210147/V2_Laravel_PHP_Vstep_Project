// Ngăn chặn các tổ hợp phím phổ biến mở Developer Tools
// document.addEventListener('keydown', function (event) {
//     if (event.keyCode == 123) { // F12
//         event.preventDefault();
//     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Ctrl+Shift+I
//         event.preventDefault();
//     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 74) { // Ctrl+Shift+J
//         event.preventDefault();
//     } else if (event.ctrlKey && event.keyCode == 85) { // Ctrl+U
//         event.preventDefault();
//     } else if (event.ctrlKey && event.keyCode == 83) { // Ctrl+S
//         event.preventDefault();
//     } else if (event.ctrlKey && event.keyCode == 80) { // Ctrl+P
//         event.preventDefault();
//     }
// });
//Not allow to hightlight
document.addEventListener('selectstart', function (e) {
    if (e.target.classList.contains('body')) {
        e.preventDefault(); // Ngăn chặn việc chọn văn bản cho các phần tử có class 'no-select'
    }
});

// Ngăn chặn chuột phải
document.addEventListener('contextmenu', function (event) {
    event.preventDefault();
});

// Ngăn chặn các hành động copy, cut, paste
document.addEventListener('copy', function (event) {
    event.preventDefault();
});

document.addEventListener('cut', function (event) {
    event.preventDefault();
});

document.addEventListener('paste', function (event) {
    event.preventDefault();
});

// Ngăn chặn chọn văn bản
document.addEventListener('selectstart', function (event) {
    event.preventDefault();
});

// Phương pháp phát hiện Developer Tools
function detectDevTools() {
    const element = new Image();
    Object.defineProperty(element, 'id', {
        get: function () {
            alert('Developer Tools are not allowed.');
            window.location.reload();
        }
    });
    // console.log(element);
}

setInterval(detectDevTools, 1000);

// Ngăn chặn menu chuột phải bổ sung
document.addEventListener('mousedown', function (event) {
    if (event.button === 2 || event.button === 3) {
        event.preventDefault();
    }
});

// Disable text selection CSS
document.documentElement.style.userSelect = 'none';
document.documentElement.style.msUserSelect = 'none';
document.documentElement.style.mozUserSelect = 'none';

document.addEventListener('DOMContentLoaded', function () {
    var audio = document.getElementById('audioPlayer');

    audio.addEventListener('play', function () {
        // Vô hiệu hóa thanh tiến trình khi âm thanh đang phát
        disableSeekBar();
    });

    function disableSeekBar() {
        audio.addEventListener('seeking', preventSeeking);
    }

    function preventSeeking(event) {
        // Ngăn chặn tua tới lui khi âm thanh đang phát
        if (!audio.paused) {
            event.preventDefault();
            audio.currentTime = audio.currentTime; // Giữ nguyên thời gian hiện tại
        }
    }

    // Xóa sự kiện ngăn chặn tua khi âm thanh bị tạm dừng
    audio.addEventListener('pause', function () {
        audio.removeEventListener('seeking', preventSeeking);
    });
});