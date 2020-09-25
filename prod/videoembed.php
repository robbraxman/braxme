<?php
session_start();
include("config-pdo.php");
include("crypt-pdo.inc.php");
include("aws.php");

$uniqid = uniqid();

$share = @tvalidator("PURIFY", $_GET['p'] );
$view = @tvalidator("PURIFY", $_GET['v'] );
$redisplay = @tvalidator("PURIFY", $_GET['r'] );

$exposedtitle = "Private Video Share";
$iconlock = "$rootserver/img/logo2.png";
$sharelink = "$rootserver/$installfolder/videoplayer.php?p=$share";
$proxyphotolink = "$rootserver/img/videostream.png";

$icon = "$rootserver/img/privatepost.jpg";
$iconlock = "$rootserver/img/logo.png";

$result = pdo_query("1","
    select filename, folder, origfilename, title, providerid, encoding from filelib where alias=? and status='Y'
    ",array($share));
if($row = pdo_fetch($result))
{
    $mp3 = "$rootserver/$installfolder/$row[folder]$row[filename]";
    $origfilename = DecryptText($row['origfilename'],$row['encoding'],$row['filename']);
    $filename = $origfilename;
    $title = DecryptText($row['title'],$row['encoding'],$row['filename']);
    if($title=='')
        $title = $filename;
    $titlebase64 = base64_encode($title);

    $musicUrl = getAWSObjectUrlShortTerm( $row['filename'] );
    
    
}
else 
    exit();

pdo_query("1","
    update filelib set views=views+1 where filename='$row[filename]' and providerid=$row[providerid]
    ",null);


//**************************************************
//**************************************************
//**************************************************

?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta property='og:title' content='<?=$exposedtitle?>' />
<meta property='og:description' content="Click to View" />
<meta property='og:url' content='<?=$sharelink?>' />
<meta property='og:type' content='Website' />
<meta property='og:image' content='<?=$proxyphotolink?>' />        
<meta http-equiv='Pragma' content='no-cache' />
<meta http-equiv='Expires' content='-1' />
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='shortcut icon' href='<?=$rootserver?>/img/favicon.ico' type='image/x-icon'>
<link rel='apple-touch-icon' href='<?=$rootserver?>/img/icon2.png'>
<title>Private Video Share</title>
<link rel='stylesheet' href='app.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='../libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='../libs/jquery-1.10.2/jquery.min.js'></script>
<link href="//vjs.zencdn.net/4.11/video-js.css" rel="stylesheet">
<script src="//vjs.zencdn.net/4.11/video.js"></script>
<script>
var shareid = '<?=$share?>';
var redisplay = '<?=$redisplay?>';
var smallsize= 300;
var bigsize = 480;

</script>
<script src="printsc.js?<?=$uniqid?>"></script>
<script>
$(document).ready(function(){
});
</script>
<title><?=$exposedtitle?></title>
</head>
<body style='background-color:whitesmoke' class="cx">
<table
       style='font-size:13px;font-family:helvetica;padding:10px;float:center'>
    <tr>
        <td style='margin:auto' >
            <video src="<?=$musicUrl?>" controls
            preload="none" width=560 height="315" style='min-width:50%;max-height:80%' poster="<?=$proxyphotolink?>"
            />            
        </td>
    </tr>
    
</table>
</body>

</html>
 