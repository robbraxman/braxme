<?php
session_start();
require_once("config-pdo.php");


        
$result = pdo_query("1",

    "select providername from provider where providerid = $_SESSION[pid]  "
);
$row = do_mysqli_fetch_row("1",$result);
if( $row )
{
    $name1 = "$row[0]";
   echo "$row[0]";
}
$result = pdo_query("1",

    "select staffname from staff where providerid = $_SESSION[pid] and loginid='$_SESSION[loginid]' "
);
  $row = do_mysqli_fetch_row("1", $result );
  $name2 = "$row[0]";
  if( $name1 != $name2 && $row[0]!="")
       echo "   / $row[0]";
        
    
?>