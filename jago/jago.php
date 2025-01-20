<?php
// Allowed referers
$allowedReferers = [
    "http://www.smtv.wuaze.com/",
    "shounwifi.net", // Duplicate entry, remove if unintentional
];

// Get the referer from the request
$referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : '';

// Check if the referer is allowed
if (!in_array($referer, $allowedReferers)) {
    // Redirect to a specific m3u8 URL if the referer is not allowed
    header("Location: http://cdn.adultiptv.net/bigtits.m3u8");
    exit;
}
// Get ferdos-id from query parameter
$ferdos_id = isset($_GET['ferdos-id']) ? $_GET['ferdos-id'] : null;

// Validate ferdos-id
if (empty($ferdos_id)) {
    echo "Error: 'ferdos-id' is required.";
    exit;
}

// Dynamically construct the base URL and .m3u8 link using ferdos-id
$base_url = "https://livess.ncare.live/c3VydmVyX8RpbEU9Mi8xNy8yMDE0GIDU6RgzQ6NTAgdEoaeFzbF92YWxIZTO0U0ezN1IzMyfvcGVMZEJCTEFWeVN3PTOmdFsaWRtaW51aiPhnPTI2/{$ferdos_id}/live-orgin/{$ferdos_id}/";
$m3u8_url = $base_url . "chunks.m3u8";
$referer = "https://livess.ncare.live/";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $m3u8_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Referer: $referer"
]);

// Execute the GET request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Close cURL
curl_close($ch);

// Process the .m3u8 content
$lines = explode("\n", $response);
$output = [];

foreach ($lines as $line) {
    $line = trim($line);
    if (!empty($line) && !str_starts_with($line, "#")) {
        // Check if the line is a relative URL and join it with the base URL
        if (!str_starts_with($line, "http")) {
            $line = $base_url . $line;
        }
        // Replace original URL with png.php?url=
        $output[] = "png.php?url=" . $line;
    } else {
        // Keep metadata as is
        $output[] = $line;
    }
}

// Output the modified .m3u8 content
header("Content-Type: application/vnd.apple.mpegurl");
echo implode("\n", $output);

?>