<?php

error_reporting(0);

$FERDOS_APP['DATA_FOLDER'] = "_AppData_";
$FERDOS_APP['APP_NAME'] = "TOFFEE TV";
$FERDOS_APP['APP_LOGO'] = "static/logo.svg";
$FERDOS_APP['APP_FAVICON'] = "static/favicon.png";
$FERDOS_APP['APP_POWEREDBY'] = "Powered By FREDFLIX";
$FERDOS_APP['BASE_API'] = "";

//=============================================================//

if(!isset($FERDOS_APP['DATA_FOLDER']) || empty($FERDOS_APP['DATA_FOLDER'])){ $FERDOS_APP['DATA_FOLDER'] = "_AppData_"; }
if(!is_dir($FERDOS_APP['DATA_FOLDER'])){ mkdir($FERDOS_APP['DATA_FOLDER']); }
if(!file_exists($FERDOS_APP['DATA_FOLDER']."/.htaccess")){ @file_put_contents($FERDOS_APP['DATA_FOLDER']."/.htaccess", "deny from all"); }
if(!isset($FERDOS_APP['FORCE_HIGH_QUALITY_STREAM']) || empty($FERDOS_APP['FORCE_HIGH_QUALITY_STREAM'])){ $FERDOS_APP['FORCE_HIGH_QUALITY_STREAM'] = "NO"; }
if($FERDOS_APP['FORCE_HIGH_QUALITY_STREAM'] !== "YES" && $FERDOS_APP['FORCE_HIGH_QUALITY_STREAM'] !== "NO"){ $FERDOS_APP['FORCE_HIGH_QUALITY_STREAM'] = "NO"; }

$streamenvproto = "http";
if(isset($_SERVER['HTTPS'])){ if($_SERVER['HTTPS'] == "on"){ $streamenvproto = "https"; } }
if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])){ if($_SERVER['HTTP_X_FORWARDED_PROTO'] == "https"){ $streamenvproto = "https"; }}
if(stripos($_SERVER['HTTP_HOST'], ':') !== false) {
    $warl = explode(':', $_SERVER['HTTP_HOST']);
    if(isset($warl[0]) && !empty($warl[0])){ $_SERVER['HTTP_HOST'] = trim($warl[0]); }
}
if(stripos($_SERVER['HTTP_HOST'], 'localhost') !== false){ $_SERVER['HTTP_HOST'] = str_replace('localhost', '127.0.0.1', $_SERVER['HTTP_HOST']); }
$local_ip = getHostByName(php_uname('n'));
if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){ $plhoth = $_SERVER['HTTP_HOST'];  }else{ $plhoth = $local_ip;  }
$plhoth = str_replace(" ", "%20", $plhoth);

//=============================================================//

function response($status, $code, $message, $data)
{
    header("Content-Type: application/json");
    $respo = array("status" => $status, "code" => $code, "message" => $message, "data" => $data);
    exit(json_encode($respo));
}

function kayacrypkey($action)
{
    global $FERDOS_APP;
    $cryptoFile = $FERDOS_APP['DATA_FOLDER']."/cryptoKey";
    $svckey = @file_get_contents($cryptoFile);
    if($action == "update") {
        $nckey = substr(sha1(rand(000, 999).time()), 0, 16);
        if(file_put_contents($cryptoFile, $nckey)) {
            return $nckey;
        } else {
            return false;
        }
    }
    else
    {
        if(empty($svckey)) { 
            kayacrypkey("update");
            return @file_get_contents($cryptoFile);
        }
        else{ return $svckey; }
    }
}

function tvhide($action, $data)
{
    $output = "";
    $ky = kayacrypkey("get"); $iv = "{=.-1q1q1q1q-.=}";
    if($action == "encrypt")
    {
        $encrypted = openssl_encrypt($data, "AES-128-CBC", $ky, OPENSSL_RAW_DATA, $iv);
        if(!empty($encrypted)) { $output = bin2hex($encrypted); }
    }
    if($action == "decrypt")
    {
        $decrypted = openssl_decrypt(hex2bin($data), "AES-128-CBC", $ky, OPENSSL_RAW_DATA, $iv);
        if(!empty($decrypted)) { $output = $decrypted; }
    }
    return $output;
}

function getRootBase($url)
{
    $output = "";
    $purl = parse_url($url);
    if(isset($purl['host'])) {
        $output = $purl['scheme']."://".$purl['host'];
    }
    return $output;
}

function getRelBase($url)
{
    $output = "";
    if(stripos($url, "?") !== false) {
        $drl = explode("?", $url);
        if(isset($drl[0]) && !empty($drl[0])) {
            $url = trim($drl[0]);
        }
    }
    $output = str_replace(basename($url), "", $url);
    return $output;
}

function getRelBasedot($url)
{
    $output = "";
    if(stripos($url, "?") !== false) {
        $drl = explode("?", $url);
        if(isset($drl[0]) && !empty($drl[0])) {
            $url = trim($drl[0]);
        }
    }
    $output = str_replace(basename($url), "", $url);
    $output = str_replace(basename($output)."/", "", $output);
    return $output;
}

function getXPURI($string)
{
    $output = '';
    $pattern = '/URI="(.*?)"/';
    preg_match($pattern, $string, $matches);
    if (isset($matches[1])) {
        $output = $matches[1];
    }
    return $output;
}

//=============================================================//

include("_inc.upstrm.php");

?>