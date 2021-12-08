<?php
exit();
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

$replyflag = tvalidator("PURIFY",$_POST['replyflag']);
$createtime = tvalidator("PURIFY",$_POST['createtime']);
$providerid = tvalidator("ID",$_POST['providerid']);


echo "<span class='NoAlert'><img class='buttonicon' src='../img/check-box-128.png' style='height:25px;width:auto;padding-top:0px;padding-bottom:0px;padding-right:10px;'  alt='No Alert' title='No Alert'/></span>";

exit();
if( $createtime=='Deletions Made')
{
        echo "<span class='AlertFired'>Refresh Needed - Deletions</span>";
        exit();
}

$result = pdo_query("1",
    "select count(*) from msgmain where providerid = ? and createtime > ? and replyflag=? "
,array($providerid,$createtime,$replyflag));
$row = pdo_fetch_row($result);
if( $row )
{
    if( $row[0] > 0)
    {
        echo "<span class='AlertFired'><img class='buttonicon' src='../img/alert-128.png' style='height:18px;width:auto;padding-bottom:5px;padding-right:10px;margin:0px' alt='Alert' title='Alert' />New Secure Messages</span>($row[0])";
    }
    else
        echo "<span class='NoAlert'><img class='buttonicon' src='../img/check-box-128.png' style='height:15px;width:auto;padding-bottom:5px;padding-right:10px;'  alt='No Alert' title='No Alert'/></span>";
        //echo "<span class='NoAlert'>No New Messages</span>";
}
        
    
?>