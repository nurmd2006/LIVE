<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $m3u8_url = $_POST['m3u8_url'];
    $referer = $_POST['referer'];
    $cookies = $_POST['cookies'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $m3u8_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Referer: $referer",
        "Cookie: $cookies"
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $response = 'Error: ' . curl_error($ch);
    }
    curl_close($ch);

    echo "<h3>Response:</h3><textarea rows='15' cols='100'>" . htmlspecialchars($response) . "</textarea>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M3U8 Link Tester</title>
</head>
<body>
    <form method="POST">
        <label for="m3u8_url">M3U8 Link:</label><br>
        <input type="text" id="m3u8_url" name="m3u8_url" required style="width: 100%;"><br><br>

        <label for="referer">Referer:</label><br>
        <input type="text" id="referer" name="referer" style="width: 100%;"><br><br>

        <label for="cookies">Cookies:</label><br>
        <input type="text" id="cookies" name="cookies" style="width: 100%;"><br><br>

        <button type="submit">Get Response</button>
    </form>
</body>
</html>