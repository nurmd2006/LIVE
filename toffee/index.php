<?php

include("_inc.configs.php");

$channelID = "";
if(isset($_REQUEST['play'])) { $channelID = trim($_REQUEST['play']); }
if(isset($_REQUEST['watch'])) { $channelID = trim($_REQUEST['watch']); }

if(!empty($channelID))
{
$tvplayurl = "live.php?id=".$channelID."&e=.m3u8";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FREDFLIX Player</title>
    <script src="https://content.jwplatform.com/libraries/SAHhwvZq.js"></script>
    <style>
        html, body {
            font-family: 'Poppins', sans-serif;
            background-color: #000;
            color: #FFF;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 5px 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            overflow: hidden;
        }

        .header .logo {
            font-size: 20px;
            font-weight: 800;
            color: #FFF;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .header img {
            height: 25px;
            margin-right: 5px;
        }

        #player {
            height: calc(100vh - 50px); 
            width: 100%;
            background-color: black;
        }

        .footer {
            background-color: #333;
            padding: 5px;
            text-align: center;
            font-size: 14px;
            color: #FFF;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>

<body>
    
    <!-- Video Player -->
    <div id="player"></div>

    

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const channelId = urlParams.get('id');
        const apiUrl = `<?php print($tvplayurl); ?>`;

        jwplayer("player").setup({
            file: apiUrl,
            width: "100%",
            height: "100%",
            autostart: true,
            type: "hls",
        });
    </script>
</body>

</html>
<?php

//=============================================================================//

}
else
{
?>
<!doctype html>
<html>
<head>
<title>Home - <?php print($FERDOS_APP['APP_NAME']); ?> | <?php print($FERDOS_APP['APP_POWEREDBY']); ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<link rel="shortcut icon" href="<?php print($FERDOS_APP['APP_FAVICON']); ?>"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<style>
    body {
        font-family: "Montserrat", sans-serif;
        background-color: black;
        color: #fff;
        margin: 0;
        padding: 0;
    }

    .header {
        background-color: #e50914;
        padding: 20px;
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        letter-spacing: 2px;
    }

    .search-bar {
        position: sticky;
        top: 20px;
        z-index: 100;
        background: rgba(30, 30, 30, 0.8);
        backdrop-filter: blur(15px);
        border-radius: 50px;
        padding: 0.5rem;
        margin: 1rem auto 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 600px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-bar input {
        background: transparent;
        border: none;
        color: white;
        font-size: 1rem;
        padding: 0.5rem 1rem;
        width: 100%;
        outline: none;
    }

    .search-bar button {
        background: #FF1493;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-left: 0.5rem;
    }

    .search-bar button:hover {
        background: white;
        color: #e50914;
    }

    .channel-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin: 20px auto;
    }

    .channel {
        background-color: #202020;
        color: white;
        text-align: center;
        border-radius: 20px 8px;
        overflow: hidden;
        padding: 10px;
        width: 150px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .channel img {
        width: 100%;
        border-radius: 15px;
        margin-bottom: 10px;
    }

    .channel:hover {
        background-color: #FF1493;
        border: 2px solid white;
    }

    .channel-name {
        font-size: 14px;
        font-weight: 500;
    }

    .spinner {
        text-align: center;
        margin-top: 50px;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>
</head>
<body>
<div class="container">
        <!-- Logo Holder -->
        <div class="mt-3 mb-4">
            <img src="https://toffeelive.com/logo.svg" alt=""/>
        </div>

<div class="search-bar">
    <input type="text" placeholder="Enter Something To Search" id="inpSearchTV" autocomplete="off">
    
</div>

<div class="spinner" id="tvsGrid">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function(){
    loadTVlist();
});
$("#btnIPTVlist").on("click", function(){
    window.location = "kaya_app.php?route=getIPTVList";
});
$("#inpSearchTV").keyup(function() {
    searchTVlist();
});

function loadTVlist()
{
    $.ajax({
        "url": "kaya_app.php",
        "type": "POST",
        "data": "route=getChannels",
        "success":function(data)
        {
            try { data = JSON.parse(data); }catch(err){}
            if(data.status == "success")
            {
                let lmtl = '';
                $.each(data.data.list, function(k, v) {
                    lmtl += '<div class="channel" data-tvid="' + v.id + '" onclick="playlivetv(this)">';
                    lmtl += '<img src="' + v.logo + '" alt=""/>';
                    lmtl += '<div class="channel-name">' + v.title + '</div>';
                    lmtl += '</div>';
                });
                $("#tvsGrid").html('<div class="channel-list">' + lmtl + '</div>');
            }
        },
        "error":function() {
            alert("Error: Server Error or Network Failed");
        }
    });
}

function playlivetv(e)
{
    let tv_id = $(e).attr("data-tvid");
    window.location = "?play=" + tv_id;
}

function searchTVlist()
{
    let query = $("#inpSearchTV").val();
    $.ajax({
        "url": "kaya_app.php",
        "type": "POST",
        "data": "route=searchChannels&query=" + query,
        "success":function(data)
        {
            try { data = JSON.parse(data); }catch(err){}
            if(data.status == "success")
            {
                let lmtl = '';
                $.each(data.data.list, function(k, v) {
                    lmtl += '<div class="channel" data-tvid="' + v.id + '" onclick="playlivetv(this)">';
                    lmtl += '<img src="' + v.logo + '" alt=""/>';
                    lmtl += '<div class="channel-name">' + v.title + '</div>';
                    lmtl += '</div>';
                });
                $("#tvsGrid").html('<div class="channel-list">' + lmtl + '</div>');
            }
        },
        "error":function() {
            alert("Error: Server Error or Network Failed");
        }
    });
}
</script>
</body>
</html>
<?php
}
?>