<html>
<head>
<title>fredflix Bangla TV Play Script Player</title>
<link rel="stylesheet" type="text/css" href="clapp.css">
<script src="//cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/@clappr/hlsjs-playback@1.2.0/dist/hlsjs-playback.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/@c3voc/clappr-audio-track-selector@0.2.4/dist/audio-track-selector.min.js"></script>
</head>  
<body> 
<div id="player" style="height: 100%; width: 100%;"></div>
<script>
    // Function to get query string parameter
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Get the 'ferdos' parameter from the URL
    var ferdos = getQueryParam('ferdos');

    // Check if 'ferdos' has a value and construct the source URL
    var sourceURL = ferdos ? `ferdos.php?ferdos=${ferdos}&e=.m3u8` : '';

    if (sourceURL) {
        var player = new Clappr.Player({
            source: sourceURL,
            width: '100%',
            height: '100%',
            autoPlay: true,
            plugins: [HlsjsPlayback, LevelSelector, AudioTrackSelector],
            mimeType: "application/x-mpegURL",
            mediacontrol: { seekbar: "#ff0000", buttons: "#eee" },
            parentId: "#player",
        });
    } else {
        document.getElementById("player").innerHTML = "Invalid or missing 'ferdos' parameter!";
    }
</script>
</body>
</html>