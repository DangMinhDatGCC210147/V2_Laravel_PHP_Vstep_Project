import { createFFmpeg, fetchFile } from '@ffmpeg/ffmpeg';

const ffmpeg = createFFmpeg({ log: true });

async function convertWebmToMp3(inputFile) {
    await ffmpeg.load();
    ffmpeg.FS('writeFile', 'input.webm', await fetchFile(inputFile));
    await ffmpeg.run('-i', 'input.webm', 'output.mp3');
    const data = ffmpeg.FS('readFile', 'output.mp3');
    return new Blob([data.buffer], { type: 'audio/mp3' });
}

export { convertWebmToMp3 }; // Xuất hàm convertWebmToMp3

