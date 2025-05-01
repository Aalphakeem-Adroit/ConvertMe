<?php
$targetDir = "video-uploads/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true); // create folder if it doesn't exist
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $filename = uniqid("recording_", true) . ".webm";
    $targetFile = $targetDir . basename($filename);

    if (move_uploaded_file($_FILES['video']['tmp_name'], $targetFile)) {
        echo "Video uploaded successfully as <strong>$filename</strong>.";
    } else {
        echo "Failed to upload video.";
    }
} else {
    echo "No video received.";
}
?>
