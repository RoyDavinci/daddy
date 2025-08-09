<?php
// Cloudinary settings
$cloud_name = 'mysogi';
$upload_preset = 'mysogi';

// File path on server (same directory as script)
$filename = './Adding Mysogi as Advertiser.mp4'; // or 'upload.jpg'
$filepath = __DIR__ . '/' . $filename;

if (!file_exists($filepath)) {
    http_response_code(400);
    echo json_encode(['error' => 'File does not exist']);
    exit;
}

// Determine file type
$file_type = mime_content_type($filepath);
$is_video = strpos($file_type, 'video') !== false;

// Select Cloudinary endpoint
$upload_url = $is_video
    ? "https://api.cloudinary.com/v1_1/$cloud_name/video/upload"
    : "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";

// Prepare POST fields
$post_fields = [
    'file' => new CURLFile($filepath, $file_type, $filename),
    'upload_preset' => $upload_preset,
];

// cURL upload
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $upload_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $post_fields,
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Output response
if ($error) {
    echo json_encode(['error' => $error]);
} else {
    echo $response;
}
