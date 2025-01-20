<body>
<link rel="stylesheet" type="text/css" href="fdfl.css">
<script src="https://cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/level-selector@0.2.0/dist/level-selector.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@clappr/hlsjs-playback@1.0.1/dist/hlsjs-playback.min.js"></script>

<div id="player" style="height: 100%; width: 100%;"></div>

<script>
  // Prevent right-click
  document.addEventListener("contextmenu", (event) => {
    event.preventDefault();
  });

  // Function to fetch the 'id' parameter from the URL
  function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  function createPlayer(sourceUrl) {
      var player = new Clappr.Player({ 
        source: sourceUrl,
        width: '100%', 
        height: '100%', 
        autoPlay: true, 
        plugins: [HlsjsPlayback, LevelSelector],
        mimeType: "application/x-mpegURL", 
        mediacontrol: { seekbar: "#ff0000", buttons: "#eee" },  
        parentId: "#player"
      }); 

      player.on(Clappr.Events.PLAYER_ERROR, function() {
        setTimeout(function() {
          player.load(player.options.source);
          player.play();
        }, 100); // Retry after 100ms
      });

      player.on(Clappr.Events.PLAYER_STOP, function() {
        player.play();
      });

      return player;
  }

  // Get the 'id' parameter from the URL
  const channelId = getQueryParam('id');

  if (channelId) {
    // Construct the source URL dynamically
    const sourceUrl = `jago.php?ferdos-id=${channelId}&e=.m3u8`;
    createPlayer(sourceUrl);
  } else {
    console.error("No 'id' parameter found in the URL");
  }
</script>

</body>