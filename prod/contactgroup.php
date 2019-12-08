<?php
session_start();
require_once("config.php");

$providerid = @mysql_safe_string( $_POST[providerid]);
$contactgroup = @mysql_safe_string( $_POST[contactgroup]);
$mode = @mysql_safe_string( $_POST[mode]);

if( $contactgroup!='' && $mode=='D')
{
    $result = do_mysqli_query("1","
        delete from contactgroups
        where providerid=$providerid and groupname='$contactgroup'
        ");
    $result = do_mysqli_query("1","
        select distinct groupname from contactgroups
        where providerid=$providerid 
        ");
    $emaillist = "";
    while( $row = do_mysqli_fetch("1",$result))
    {
        $emaillist .= "<span class='group' style='cursor:pointer;font-size:12px;font-family:helvetica;color:steelblue' data-groupname='$row[groupname]'><b>$row[groupname]</b> </span> ";
        $emaillist .= "&nbsp;<img class='groupdelete' style='cursor:pointer;height:12px;width:auto;margin:0;padding:0' 
            data-groupname='$row[groupname]'
            src='../img/delete-gray-128.png'
            />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    if( $emaillist!='')
        echo "<br>".$emaillist."<br><br>";
    
    exit();
}

if( $contactgroup=='')
{
    $result = do_mysqli_query("1","
        select distinct groupname from contactgroups
        where providerid=$providerid 
        ");
    $emaillist = "";
    while( $row = do_mysqli_fetch("1",$result))
    {
        $emaillist .= "<span class='group' style='cursor:pointer;font-size:12px;font-family:helvetica;color:steelblue' data-groupname='$row[groupname]'><b>$row[groupname]</b> </span> ";
        $emaillist .= "&nbsp;<img class='groupdelete' style='cursor:pointer;height:12px;width:auto;margin:0;padding:0' 
            data-groupname='$row[groupname]'
            src='../img/delete-gray-128.png'
            />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    if( $emaillist!='')
        echo "<br>".$emaillist."<br><br>";
    
    exit();
}

$result = do_mysqli_query("1","
    select contactname, email from contactgroups
    where providerid=$providerid and groupname = '$contactgroup'
    ");
$emaillist = "";
while( $row = do_mysqli_fetch("1",$result))
{
    if( $emaillist !='')
    {
        $emaillist .= ",";
    }
    $emaillist .= "$row[contactname] <$row[email]>";
}
if( $emaillist!='')
    echo $emaillist;
exit();
?>