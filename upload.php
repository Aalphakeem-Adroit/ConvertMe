<?php
$targetDir = "uploads/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media'])) {
    $filename = uniqid("media_", true) . ".webm";
    $targetFile = $targetDir . basename($filename);

    if (move_uploaded_file($_FILES['media']['tmp_name'], $targetFile)) {
        // File saved successfully — get public URL
        $mediaURL = "https://yourdomain.com/uploads/$filename"; // UPDATE TO YOUR REAL DOMAIN

        // Call transcribe.php with the media URL
        $ch = curl_init("https://yourdomain.com/transcribe.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['media_url' => $mediaURL]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        // Show transcription result or redirect
        echo $response; // OR: header("Location: result.php?id=xyz");
    } else {
        echo "Failed to upload file.";
    }
}
?>
z