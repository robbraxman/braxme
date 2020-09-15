<?php
session_start();
require("validsession.inc.php");
require_once("config.php");


if( $_POST['pid']!='' )
{
    require("password.inc.php");
}
require_once("crypt.inc.php");

$gcm = "";
if($_SESSION['gcm']!=''){
    $gcm = "Yes";
}
$apn = "";
if($_SESSION['apn']!=''){
    $apn = "Yes";
}

$result = do_mysqli_query("1",
    "
        select sum(filesize) as totalsize, count(*) as filecount
        from filelib where providerid = $providerid 
    ");
$row = do_mysqli_fetch("1",$result);
$totalsize = round($row['totalsize']/1000000000,1);
$filecount = $row['filecount'];

$result = do_mysqli_query("1",
    "
        select sum(filesize*views) as bandwidth
        from fileviews where providerid = $providerid 
    ");
$row = do_mysqli_fetch("1",$result);

$bandwidth = round($row['bandwidth']/1000000000,1);


?>
<html>
    <head>
        <title>Device Info</title>
        <meta charset='utf-8'>
    </head>
<body class='mainfont' style="font-family:helvetica;">
    <h2>Device Details</h2>
<?php    
    if($_SESSION['superadmin']!='Y'){
        $_SESSION['banid']='';
        
    }
    echo "Plan Sizing: $_SESSION[sizing] Plan Inner Width: $_SESSION[innerwidth] Plan MobileSize: $_SESSION[mobilesize]<br>";
    echo "Device Code: $_SESSION[devicecode] $_SESSION[banid]<br>";
    echo "Pixel Ratio: <span class='statspixelratio'></span><br>";
    echo "Stat InnerWidth: <span class='statsinnerwidth'></span><br>";
    echo "Stat InnerHeight: <span class='statsinnerheight'></span><br>";

    
    echo "User Agent:<br><div class='statsuseragent'></div>";
    echo "<script src='$rootserver/libs/jquery-1.11.1/jquery.min.js' ></script>";
    echo "<script> 
         $('.statsuseragent').text( navigator.userAgent);
         $('.statspixelratio').text( window.devicePixelRatio);
         $('.statsinnerheight').text( screen.height);
         $('.statsinnerwidth').text( screen.width);
          </script> 
        ";
    echo "Internet Speed Score: $_SESSION[iscore]<br>";
    echo "Login ID: $_SESSION[loginid]<br>";
    echo "Timezone Offset: $_SESSION[timezoneoffset]<br>";
    echo "APN: $apn<br>";
    echo "GCM: $gcm<br>";
    echo "Total File Storage Space Used: $totalsize GB<br>";
    echo "Total Bandwidth Used: $bandwidth GB<br>";
    echo "App Version: $_SESSION[version]";
    
    
    echo "</body>";
exit;
?>