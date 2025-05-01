<?php
// Replace with your actual AssemblyAI API key
$API_KEY = 'afcad3491c7440298539755eac94093a';

// Base URL for AssemblyAI API
$BASE_URL = 'https://api.assemblyai.com/v2';

// Function to upload a local file to AssemblyAI and get the upload URL
function uploadFile($filePath, $API_KEY, $BASE_URL) {
    $handle = fopen($filePath, 'rb');
    $ch = curl_init("$BASE_URL/upload");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "authorization: $API_KEY",
        "transfer-encoding: chunked"
    ]);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_INFILE, $handle);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    fclose($handle);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['upload_url'] ?? null;
}

// Function to request transcription
function requestTranscription($audio_url, $API_KEY, $BASE_URL) {
    $ch = curl_init("$BASE_URL/transcript");
    $data = [
        'audio_url' => $audio_url,
        'speaker_labels' => true
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "authorization: $API_KEY",
        "content-type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($response, true);
    return $res['id'] ?? null;
}

// Function to poll for transcription result
function pollTranscription($transcript_id, $API_KEY, $BASE_URL) {
    $polling_url = "$BASE_URL/transcript/$transcript_id";
    while (true) {
        $ch = curl_init($polling_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "authorization: $API_KEY"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, true);
        if ($res['status'] === 'completed') {
            return $res;
        } elseif ($res['status'] === 'error') {
            throw new Exception("Transcription failed: " . $res['error']);
        } else {
            sleep(3); // Wait before polling again
        }
    }
}

// Main execution
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_FILES['audio_file']['tmp_name'])) {
            // Handle file upload
            $filePath = $_FILES['audio_file']['tmp_name'];
            $upload_url = uploadFile($filePath, $API_KEY, $BASE_URL);
            if (!$upload_url) {
                throw new Exception("File upload failed.");
            }
        } elseif (!empty($_POST['audio_url'])) {
            // Handle URL input
            $upload_url = $_POST['audio_url'];
        } else {
            throw new Exception("No audio file or URL provided.");
        }

        // Request transcription
        $transcript_id = requestTranscription($upload_url, $API_KEY, $BASE_URL);
        if (!$transcript_id) {
            throw new Exception("Transcription request failed.");
        }

        // Poll for result
        $result = pollTranscription($transcript_id, $API_KEY, $BASE_URL);

        // Display the transcription
        echo "<h2>Transcription Result:</h2>";
        echo "<p>" . htmlspecialchars($result['text']) . "</p>";

        // Display speaker labels if available
        if (!empty($result['utterances'])) {
            echo "<h3>Speaker Segmentation:</h3>";
            foreach ($result['utterances'] as $utterance) {
                echo "<p><strong>Speaker {$utterance['speaker']}:</strong> " . htmlspecialchars($utterance['text']) . "</p>";
            }
        }
    }
} catch (Exception $e) {
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
