<?php
$media_url = $_POST['media_url'] ?? null;

if (!$media_url) {
    die("No media URL received.");
}

$apiKey = "afcad3491c7440298539755eac94093a";

// 1. Submit the transcription request
$ch = curl_init("https://api.assemblyai.com/v2/transcript");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "authorization: $apiKey",
    "content-type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "audio_url" => $media_url
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$transcript_id = $data['id'] ?? null;

if (!$transcript_id) {
    die("Failed to start transcription.");
}

// 2. Poll for completion
$poll_url = "https://api.assemblyai.com/v2/transcript/$transcript_id";

do {
    sleep(3);
    $ch = curl_init($poll_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["authorization: $apiKey"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $poll_response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($poll_response, true);
    $status = $result['status'] ?? '';
} while ($status !== 'completed' && $status !== 'error');

if ($status === 'completed') {
    $text = htmlspecialchars($result['text']);
    echo "<h2>Transcription Result</h2>";
    echo "<textarea id='transcription' rows='10' cols='80'>$text</textarea><br><br>";
    echo "<button onclick='copyText()'>Copy</button> ";
    echo "<button onclick='downloadPDF()'>Download PDF</button>";

    echo "
    <script>
    function copyText() {
        const text = document.getElementById('transcription');
        text.select();
        document.execCommand('copy');
        alert('Copied!');
    }

    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const text = document.getElementById('transcription').value;
        doc.text(text, 10, 10);
        doc.save('transcription.pdf');
    }
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'></script>
    ";
} else {
    echo "Transcription failed: " . htmlspecialchars($result['error'] ?? 'Unknown error');
}
?>
