# Voice and Video to Text Web App

## Stack
- Frontend: HTML, CSS, JavaScript
- Backend: Node.js (Express)
- Transcription: Python (Vosk)

## Run
1. Install Node.js packages:

2. Install Python packages:

3. Download Vosk Model:
Place Vosk English model inside `backend/models/`

4. Start backend server:


5. Visit: http://localhost:3000/


## Features
- Record or Upload Audio/Video
- Transcribe using Vosk (Offline)
- Download text in .txt, .pdf, .doc


<!-- ====================================== -->

# Media Transcriber (Audio/Video to Text App)

This app records video or audio from browser, transcribes them using Python + Vosk, and lets you copy/download the generated text.

---

## 🔥 Features
- Record Video or Audio directly.
- Upload existing media.
- Live transcription using Vosk speech recognition.
- Copy, Rename, Download (.txt) transcriptions.
- View Uploaded Files (Audios, Videos, Texts).
- Mobile-friendly (designed for future .apk conversion).

---

## 📁 Project Structure

project-root/
├── backend/
│   ├── app.js
│   ├── python/
│   │   └── transcribe.py
│   └── uploads/
│       ├── audios/
│       ├── videos/
│       └── texts/
├── frontend/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   ├── main.js
│   │   └── display.js
│   ├── index.html
│   ├── display.html
│   └── uploads.html
├── package.json
├── README.md

---

## 🚀 How to Run Locally

### 1. Install Node.js Packages
```bash
npm install

pip install -r backend/python/requirements.txt

cd backend/python/
wget https://alphacephei.com/vosk/models/vosk-model-small-en-us-0.15.zip
unzip vosk-model-small-en-us-0.15.zip

npm start

Server runs at:

http://localhost:3000


📸 Screenshots
Home Page with Record Buttons

Recording Interface

Loading Spinner

Transcription Display Page

Uploads Manager

✨ Future Improvements
Longer recording time

Video Thumbnails with ffmpeg

Support for .pdf, .docx download

Multi-language support

User accounts

👨‍💻 Developer
Made with ❤️ by a Full Stack Developer.

