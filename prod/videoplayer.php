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
$iconlock = "$rootserver/img/logo-b2.png";
$sharelink = "$rootserver/$installfolder/videoplayer.php?p=$share";
$proxyphotolink = "$rootserver/img/videostream.png";

$icon = "$rootserver/img/privatepost.jpg";
$iconlock = "$rootserver/img/logo-b2.png";

$result = pdo_query("1","
    select filelib.filename, filelib.folder, filelib.origfilename, 
    filelib.title, filelib.providerid, filelib.encoding, filelib.filesize,
    provider.blockdownload
    from filelib
    left join provider on filelib.providerid = provider.providerid
    where filelib.alias='$share' and filelib.status='Y' and provider.blockdownload!='Y'
    ");
if($row = pdo_fetch($result)){

    $mp3 = "$rootserver/$installfolder/$row[folder]$row[filename]";
    $origfilename = DecryptText($row['origfilename'],$row['encoding'],$row['filename']);
    $filename = $origfilename;
    $title = DecryptText($row['title'],$row['encoding'],$row['filename']);
    if($title==''){
        $title = $filename;
    }
    $titlebase64 = base64_encode($title);

    $musicUrl = getAWSObjectUrlShortTerm( $row['filename'] );
    
    
} else { 
    exit();
}
    pdo_query("1","
        update filelib set views=views+1 where filename='$row[filename]' and providerid=$row[providerid]
        ");
    pdo_query("1","
        insert into fileviews (filename, providerid, viewdate, filesize, views, status )
        values ('$row[filename]', $row[providerid], now(), $row[filesize], 1, 'Y' )
        ");


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
<link rel='shortcut icon' href='<?=$rootserver?>/img/logo-b1.ico' type='image/x-icon'>
<link rel='apple-touch-icon' href='<?=$rootserver?>/img/logo-b1a.png'>
<title>Private Video Share</title>
<link rel='stylesheet' href='app.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='../libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='../libs/jquery-1.10.2/jquery.min.js'></script>
<link href="//vjs.zencdn.net/4.11/video-js.css" rel="stylesheet">
<script src="//vjs.zencdn.net/4.11/video.js" integrity=1 ></script>
<script>
var shareid = '<?=$share?>';
var redisplay = '<?=$redisplay?>';
var smallsize= 300;
var bigsize = 480;

</script>
<script src="printsc.js?<?=$uniqid?>"></script>
<title><?=$exposedtitle?></title>
</head>
<body style='background-color:whitesmoke' class="cx">
    <div id="banner" class="bannerflush" 
         style="height:40px;position:relative;top:0;left:0;padding:0 0 0 0;
         width:100%;margin:0;text-align:left;background-color:white">
        <img src='<?=$rootserver?>/img/logo-b1.png' id='homepage' 
             style='cursor:pointer;position:relative;top:5px;height:25px;padding-left:10px;float:left' />
    </div>
    
<span style='font-size:5px;float:left;display:none'>My Private Video</span>
<center>
    <br><br>
    <div class='pagetitle'><?=$title?></div>
<table
       style='font-size:13px;font-family:helvetica;padding:10px;float:center'>
    <tr class='formobile'>
        <td style='margin:auto' >
            <div style="border: solid 1px #ccc; padding: 10px; text-align: center;">
                <video id='video2' src="<?=$musicUrl?>" controls
                width=300 height="250" 
                style='min-width:50%;max-height:50%' 
                poster="<?=$proxyphotolink?>"
                />            
                <br>
            </div>

            
        </td>
    </tr>
    <tr class='nonmobile'>
        <td style='margin:auto' >
                <video src="<?=$musicUrl?>" controls
                width=640 height="450" 
                style='min-width:50%;max-height:50%' 
                poster="<?=$proxyphotolink?>"
                />            
                <br>

            
        </td>
    </tr>
    
</table>

</span>
</center>
<br><br>
<br><br>
    <div class='sitearea smalltext' style='background-color:black;color:white;text-align:center;margin:0;padding:0;font-size:13px;font-family:helvetica'>
            <center>
                <br>
            <p>This video is private 
                and protected.</p>
            <a href='<?=$rootserver?>'><img src='<?=$iconlock?>' class='margined' style='height:30px;width:auto' /></a>
            <br>
            <br>Privacy enhanced by <?=$appname?>
            <br><br>
            <a style='text-decoration:none;text-decoration-color:white;color:white;font-weight:bold' href='https://brax.me'>https://brax.me</a>
            <br><br>
            </center>
    </div>
</body>
</html>
 