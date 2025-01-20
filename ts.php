<?php
if (isset($_GET['url'])) {
    $ts_url = $_GET['url'];
    $referer = "https://banglatv.tv/live/";

    // cURL সেশন শুরু
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ts_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Referer: $referer"
    ]);

    // রেসপন্স ফেচ করা
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        // TS সেগমেন্ট ডেটা সরাসরি প্রিন্ট করা
        header("Content-Type: video/mp2t");
        echo $response;
    }

    // cURL সেশন বন্ধ
    curl_close($ch);
} else {
    echo "Error: TS URL not provided!";
}
?>