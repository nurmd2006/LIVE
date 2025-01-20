<?php

include("_inc.configs.php");

$route = "";
if (isset($_REQUEST['route'])) {
    $route = trim($_REQUEST['route']);
}

$tvchannels = getChannelList();

if ($route == "getChannels") {
    $tist = array();
    if (isset($tvchannels[0])) {
        foreach ($tvchannels as $utv) {
            $tist[] = array("id" => $utv['id'],
                            "title" => $utv['title'],
                            "logo" => $utv['logo']);
        }
    }
    if (!empty($tist)) {
        response("success", 200, "Total " . count($tist) . " Channels Found", array("count" => count($tist), "list" => $tist));
    } else {
        response("error", 404, "No Channels Found", "");
    }
} elseif ($route == "searchChannels") {
    $query = ""; $tist = array();
    if (isset($_REQUEST['query'])) {
        $query = trim(strip_tags($_REQUEST['query']));
    }
    if (empty($query)) {
        response("error", 400, "Enter Something To Search", "");
    }
    if (isset($tvchannels[0])) {
        foreach ($tvchannels as $utv) {
            if (stripos($utv['title'], $query) !== false) {
                $tist[] = array("id" => $utv['id'],
                                "title" => $utv['title'],
                                "logo" => $utv['logo']);
            }
        }
    }
    if (!empty($tist)) {
        response("success", 200, "Total " . count($tist) . " Search Results Found", array("count" => count($tist), "list" => $tist));
    } else {
        response("error", 404, "No Relevant Search Result Found", "");
    }
} elseif ($route == "getChannelDetail") {
    $id = "";
    if (isset($_REQUEST['id'])) { $id = trim($_REQUEST['id']); }
    if (empty($id)) { response("error", 400, "Channel ID Required", ""); }
    $detail = getChannelDetail($id);
    if (!empty($detail)) {
        $vdetail['id'] = $detail['id'];
        $vdetail['title'] = $detail['title'];
        $vdetail['logo'] = $detail['logo'];

        // Prepare the URL for cURL request without 'Referer' header
        $streamUrl = $streamenvproto . "://" . $plhoth . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF'])) . "live.php?id=" . $id . "&e=.m3u8";

        // Initialize cURL for stream fetching (optional)
        $ch = curl_init($streamUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);  // Don't send any Referer or other headers
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);  // Timeout in seconds
        $streamData = curl_exec($ch);
        curl_close($ch);

        if ($streamData) {
            $vdetail['playurl'] = $streamUrl;  // Set playurl without Referer
            response("success", 200, "Channel Details Loaded", $vdetail);
        } else {
            response("error", 404, "Stream Not Found", "");
        }
    } else {
        response("error", 404, "Channel Does Not Exist", "");
    }
} elseif ($route == "getIPTVList") {
    if (isset($tvchannels[0])) {
        $playlistData = "#EXTM3U\n";
        foreach ($tvchannels as $otv) {
            $playlistData .= '#EXTINF:-1 tvg-id="' . $otv['id'] . '" tvg-name="' . $otv['title'] . '" tvg-country="IN" tvg-logo="' . $otv['logo'] . '" tvg-chno="' . $otv['id'] . '" group-title="", ' . $otv['title'] . "\n";
            if ($_SERVER['SERVER_PORT'] !== "80" && $_SERVER['SERVER_PORT'] !== "443") {
                $playUrlBase = $streamenvproto . "://" . $plhoth . ":" . $_SERVER['SERVER_PORT'] . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));
            } else {
                $playUrlBase = $streamenvproto . "://" . $plhoth . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));
            }
            $playlistData .= $playUrlBase . "live.php?id=" . $otv['id'] . "&e=.m3u8\n";
        }
        $file = str_replace(" ", "", $KAYA_APP['APP_NAME']) . "_(tengofuskdby:ferdos)-" . time() . ".m3u";
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header("Content-Type: application/vnd.apple.mpegurl");
        exit($playlistData);
    } else {
        http_response_code(404);
        exit();
    }
} else {
    response("error", 404, "Route Not Found", "");
}
?>
