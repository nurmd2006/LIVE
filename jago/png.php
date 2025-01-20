<?php

// Get the URL parameter
if (!isset($_GET['url']) || empty($_GET['url'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "URL parameter missing!";
    exit;
}

$url = $_GET['url'];
$referer = "https://livess.ncare.live/";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Referer: $referer"
]);

// Execute the GET request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Get content type of the fetched resource
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

// Close cURL
curl_close($ch);

// Set appropriate content type header
header("Content-Type: $content_type");

// Output the response
echo $response;

?>