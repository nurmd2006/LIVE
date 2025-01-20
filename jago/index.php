<?php
function parseM3U($file) {
    $channels = [];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $currentChannel = [];

    foreach ($lines as $line) {
        if (strpos($line, '#EXTINF:') === 0) {
            // Extract tvg-logo and name
            preg_match('/tvg-logo="([^"]+)"/', $line, $logoMatch);
            $currentChannel['logo'] = $logoMatch[1] ?? 'default-logo.png';

            // Extract channel name
            $nameParts = explode(',', $line, 2);
            $currentChannel['name'] = trim($nameParts[1]);
        } elseif (strpos($line, 'http') === 0) {
            // Extract the ID part from the URL
            $parsedUrl = trim($line);
            $basePart = substr($parsedUrl, 0, strrpos($parsedUrl, '/')); 
            $id = substr($basePart, strrpos($basePart, '/') + 1); 
            $currentChannel['id'] = $id;
            $currentChannel['url'] = $parsedUrl;
            $channels[] = $currentChannel;
            $currentChannel = [];
        }
    }

    return $channels;
}

// Load channels from the local M3U file
$m3uFile = 'playlist.m3u';
$channels = parseM3U($m3uFile);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FREDFLIX</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #e50914;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .channel-list {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin: 20px auto;
        }

        .channel {
            background-color: #222;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            width: 150px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .channel:hover {
            transform: scale(1.1);
            background-color: #444;
        }

        .channel img {
            width: 100%;
            border-radius: 8px;
        }

        .channel-name {
            margin-top: 10px;
            font-size: 14px;
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="header">FREDFLIX</div>
    <div class="channel-list">
        <?php if (!empty($channels)): ?>
            <?php foreach ($channels as $channel): ?>
                <div class="channel" onclick="playChannel('<?= $channel['id'] ?>')">
                    <img src="<?= htmlspecialchars($channel['logo']) ?>" alt="<?= htmlspecialchars($channel['name']) ?>">
                    <div class="channel-name"><?= htmlspecialchars($channel['name']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No channels found in the playlist.</p>
        <?php endif; ?>
    </div>
    <script>
        function playChannel(id) {
            window.location.href = `player.php?id=${id}`;
        }
    </script>
</body>
</html>