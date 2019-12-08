<?php
session_start();
include("config.php");
include("crypt.inc.php");
include("aws.php");

$uniqid = uniqid();

$share = @mysql_safe_string( $_GET['p'] );
$view = @mysql_safe_string( $_GET['v'] );
$redisplay = @mysql_safe_string( $_GET['r'] );

$exposedtitle = "Private Audio Share";
$iconlock = "$rootserver/img/logo-b1.png";
$sharelink = "$rootserver/$installfolder/soundplayer.php?p=$share";
$proxyphotolink = "$rootserver/img/musicpost.png";

$icon = "$rootserver/img/privatepost.jpg";
$iconlock = "$rootserver/img/logo.png";

$result = do_mysqli_query("1","
    select filelib.filename, filelib.folder, filelib.origfilename, 
    filelib.title, filelib.providerid, filelib.encoding, filelib.filesize,
    provider.blockdownload
    from filelib
    left join provider on filelib.providerid = provider.providerid
    where filelib.alias='$share' and filelib.status='Y' and provider.blockdownload!='Y'
    ");
if($row = do_mysqli_fetch("1",$result)){

    $mp3 = "$rootserver/$installfolder/$row[folder]$row[filename]";
    $origfilename = DecryptText($row['origfilename'],$row['encoding'],$row['filename']);
    $filename = $origfilename;
    $title = DecryptText($row['title'],$row['encoding'],$row['filename']);
    if($title=='')
        $title = $filename;
    $titlebase64 = base64_encode($title);

    $musicUrl = getAWSObjectUrlShortTerm( $row['filename'] );
    
    
} else {
    exit();
}
    do_mysqli_query("1","
        update filelib set views=views+1 where filename='$row[filename]' and providerid=$row[providerid]
        ");
    do_mysqli_query("1","
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
<link rel='apple-touch-icon' href='<?=$rootserver?>/img/logo-b1.png'>
<title>Private Audio Share</title>
<link rel='stylesheet' href='app.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='../libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='../libs/jquery-1.10.2/jquery.min.js'></script>
<script src="../libs/audio/audiojs/audio.min.js"></script>
<script> 
var shareid = '<?=$share?>';
var redisplay = '<?=$redisplay?>';
var smallsize= 300;
var bigsize = 480;

audiojs.events.ready(function() 
{
    var as = audiojs.createAll();
});
</script>
<script src="printsc.js?<?=$uniqid?>"></script>
<script>
$(document).ready(function(){
});
</script>
<title><?=$exposedtitle?></title>
</head>
<body style='background-color:whitesmoke' class="cx">
    <div id="banner" class="bannerflush" 
         style="height:40px;position:relative;top:0;left:0;padding:0 0 0 0;
         width:100%;margin:0;text-align:left;background-color:white">
        <img src='<?=$rootserver?>/img/logo-b1.png' id='homepage' 
             style='cursor:pointer;position:relative;top:5px;height:25px;padding-left:10px;float:left' />
    </div>
    
<span style='font-size:5px;float:left;display:none'>My Private Audio</span>
<center>
    <br>
<img src='<?=$proxyphotolink?>' class='margined' style='float:center;height:250px;width:auto;' />
<br>
<div class="pagetitle"><?=$title?></div>
<table
       style='font-size:13px;font-family:helvetica;padding:10px;float:center'>
    <tr>
        <td style='margin:auto' >
            <br><br>
            <audio src="<?=$musicUrl?>" preload="auto" />            
            <br><br>
        </td>
    </tr>
    <tr>
        <td  style='text-align:center'  >
            <span class='youare' style='display:none'></span>
        </td>
    </tr>
    
</table>

</span>
</center>
<br><br>
    <div class='sitearea' style='background-color:#666666;color:white;text-align:center;margin:0;padding:0;font-size:13px;font-family:helvetica'>
            <center>
                <br>
            <p>This audio is private 
                and is not stored at any public web site.</p>
            <a href='<?=$rootserver?>'><img src='<?=$iconlock?>' class='margined' style='height:30px;width:auto' /></a>
            <br>
            <br>Content Protected by <?=$appname?>
            <br><br>
            <a style='text-decoration:none;text-decoration-color:white;color:white;font-weight:bold' href='<?=$rootserver?>'><?=$rootserver?></a>
            <br><br>
            </center>
    </div>

</body>

</html>
 