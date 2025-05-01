<?php
$targetDir = "voice-uploads/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true); // create folder if it doesn't exist
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['audio'])) {
    $filename = uniqid("voice_", true) . ".webm";
    $targetFile = $targetDir . basename($filename);

    if (move_uploaded_file($_FILES['audio']['tmp_name'], $targetFile)) {
        echo "Voice saved as <strong>$filename</strong>.";
    } else {
        echo "Failed to save voice.";
    }
} else {
    echo "No audio received.";
}
?>
