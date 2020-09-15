<?php
session_start();
require_once("config-pdo.php");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require_once("aws.php");
//require_once("htmlhead.inc.php");

$token = "";
$gcm = "";
$apn = "";
$providerid = "";

$result = pdo_query("1","
        select provider.providername, notifytokens.token, notifytokens.platform,  
        notifytokens.app,
        notifytokens.registered, notifytokens.providerid from notifytokens 
        left join provider on notifytokens.providerid = provider.providerid
        where notifytokens.arn='' and (notifytokens.status='Y' or notifytokens.status='E')
        and notifytokens.token!=''
        ");
while($row = pdo_fetch($result))
{
    $token = $row['token'];
    echo $token . "=>";
    //echo "$providerid<br>$row[token]<br>$row[platform]<br>$row[registered]<br><br>";
    if($row['platform']=='ios') {
        $apn = $token;
    }
    if($row['platform']=='android' || $row['platform']=='chrome' ) {
        $gcm = $token;
    }
    $providerid = $row['providerid'];
    echo "$providerid $row[providername]\n";
    try {
        $arn = createSnsPlatformEndpoint( "$apn", "$gcm", $row['app'] );
        echo "arn=$arn/";
        if( $arn!=''){
            pdo_query("1","update notifytokens set arn='$arn', status='Y', error='OK' where providerid=$providerid and token='$token' ");
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        
        $errsql = htmlentities($e->getMessage(), ENT_COMPAT);
        pdo_query("1","update notifytokens set status='E', arn='', error = '$errsql' where providerid=$providerid and token='$token' ");
    } 
    echo "\n";
}
//echo "<br>End";
?>