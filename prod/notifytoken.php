    <?php
session_start();
require_once("config-pdo.php");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require_once("aws.php");
//require_once("htmlhead.inc.php");


$result = pdo_query("1","
        select provider.providername, notifytokens.token, notifytokens.platform, notifytokens.registered, notifytokens.providerid from notifytokens 
        left join provider on notifytokens.providerid = provider.providerid
        where notifytokens.arn='' and (notifytokens.status='Y' or notifytokens.status='E')
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
    echo "$providerid $row[providername]<br>";
    try {
        $arn = createSnsPlatformEndpoint( "$apn", "$gcm" );
        echo "arn=$arn";
        if( $arn!=''){
            pdo_query("1","update notifytokens set arn='$arn', status='Y' where providerid=$providerid and token='$token' ");
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
        $errsql = htmlentities($e->getMessage(), ENT_COMPAT);
        pdo_query("1","update notifytokens set status='E', error = '$errsql' where providerid=$providerid and token='$token' ");
    } 
    echo "<br>";
}
//echo "<br>End";
?>