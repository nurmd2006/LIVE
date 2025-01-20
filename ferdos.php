<?php
$url = "http://fredflix.rf.gd/bd/ferdos.php?ferdos=BD&e=.m3u8";

// cURL initialize
$ch = curl_init($url);

// cURL options সেট করা
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Redirect ফলো করার জন্য

// রেসপন্স নেওয়া
$response = curl_exec($ch);

// যদি কোনো ত্রুটি থাকে
if(curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // রেসপন্স আউটপুট দেখানো
    header("Content-Type: text/plain");
    echo $response;
}

// cURL বন্ধ করা
curl_close($ch);
?>