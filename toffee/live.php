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


include("_inc.configs.php");

header("Access-Control-Allow-Origin: *");

$id = ""; $cid = ""; $key = ""; $chunks = ""; $segment = "";
if (isset($_REQUEST['id'])) { $id = trim($_REQUEST['id']); }
if (isset($_REQUEST['cid'])) { $cid = trim($_REQUEST['cid']); }
if (isset($_REQUEST['key'])) { $key = trim($_REQUEST['key']); }
if (isset($_REQUEST['chunks'])) { $chunks = trim($_REQUEST['chunks']); }
if (isset($_REQUEST['segment'])) { $segment = trim($_REQUEST['segment']); }

//=============================================================================//

$streamURL = ""; $streamHeader = array();
if (!empty($id)) { $tv_id = $id; } elseif (!empty($cid)) { $tv_id = $cid; } else { $tv_id = ""; }
if (!empty($tv_id)) {
    $detail = getChannelDetail($tv_id);
    if (empty($detail)) {
        http_response_code(404);
        exit();
    }
    $streamURL = $detail['url'];
    $streamHeader = array(
        "User-Agent: " . $detail['heads']['user-agent'],
        "Cookie: " . $detail['heads']['cookie'],
        "Client-Api-Header: " . $detail['heads']['client-api-header']
    );
}
if (empty($streamHeader)) {
    http_response_code(410);
    exit();
}

if (stripos($_SERVER['REQUEST_URI'], ".m3u8?") !== false) {
    $TS_EXT = ".ts";
    $KEY_EXT = ".key";
    $HLS_EXT = ".m3u8";
} else {
    $TS_EXT = $KEY_EXT = $HLS_EXT = ".php";
}

//=============================================================================//

// Use cURL to get stream data
function fetchUrl($url, $headers) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

if (!empty($id)) {
    $response = fetchUrl($streamURL, $streamHeader);
    if (stripos($response, "#EXTM3U") !== false) {
        $fine = "";
        $line = explode("\n", $response);
        foreach ($line as $vine) {
            if (stripos($vine, ".m3u8") !== false) {
                $fine .= "live" . $HLS_EXT . "?cid=" . $id . "&chunks=" . tvhide("encrypt", $vine) . "\n";
            } else {
                $fine .= $vine . "\n";
            }
        }
        exit(trim($fine));
    }
} elseif (!empty($chunks)) {
    $playURL = tvhide("decrypt", $chunks);
    if (stripos($playURL, ".m3u8") === false) {
        http_response_code(400);
        exit();
    }
    if ($playURL[0] == "/") {
        $iBaseURL = getRootBase($streamURL);
    } elseif (stripos($playURL, "../") !== false) {
        $iBaseURL = getRelBasedot($streamURL);
        $playURL = str_replace("../", "", $playURL);
    } else {
        $iBaseURL = getRelBase($streamURL);
    }
    $chunkURL = $iBaseURL . $playURL;
    $response = fetchUrl($chunkURL, $streamHeader);
    if (stripos($response, "#EXTM3U") !== false) {
        $fine = "";
        $line = explode("\n", $response);
        foreach ($line as $vine) {
            if (stripos($vine, ".ts") !== false) {
                $fine .= "live" . $TS_EXT . "?cid=" . $cid . "&segment=" . tvhide("encrypt", $vine) . "\n";
            } elseif (stripos($vine, 'URI="') !== false) {
                $orgURL = getXPURI($vine);
                $norgURL = "live" . $KEY_EXT . "?cid=" . $cid . "&key=" . tvhide("encrypt", $orgURL);
                $fine .= str_replace($orgURL, $norgURL, $vine) . "\n";
            } else {
                $fine .= $vine . "\n";
            }
        }
        exit(trim($fine));
    }
} elseif (!empty($segment)) {
    $playURL = tvhide("decrypt", $segment);
    if (stripos($playURL, ".ts") === false) {
        http_response_code(400);
        exit();
    }
    if (substr($playURL, 0, 1) === "/") {
        $iBaseURL = getRootBase($streamURL);
    } elseif (stripos($playURL, "../") !== false) {
        $iBaseURL = getRelBasedot($streamURL);
        $playURL = str_replace("../", "", $playURL);
    } else {
        $iBaseURL = getRelBase($streamURL);
    }
    $chunkURL = $iBaseURL . $playURL;
    $response = fetchUrl($chunkURL, $streamHeader);
    header("Content-Type: video/m2ts");
    exit($response);
} elseif (!empty($key)) {
    $playURL = tvhide("decrypt", $key);
    if (stripos($playURL, ".key") === false) {
        http_response_code(400);
        exit();
    }
    if (substr($playURL, 0, 1) === "h") {
        $iBaseURL = "";
    } elseif (substr($playURL, 0, 1) === "/") {
        $iBaseURL = getRootBase($streamURL);
    } elseif (stripos($playURL, "../") !== false) {
        $iBaseURL = getRelBasedot($streamURL);
        $playURL = str_replace("../", "", $playURL);
    } else {
        $iBaseURL = getRelBase($streamURL);
    }
    $chunkURL = $iBaseURL . $playURL;
    $response = fetchUrl($chunkURL, $streamHeader);
    header("Content-Type: application/octet-stream");
    exit($response);
} else {
    http_response_code(503);
    exit();
}

?>