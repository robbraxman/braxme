<?php
session_start();
set_time_limit ( 30 );
require_once("config.php");


$timestamp = time();

    //Process Accounts active within last x days
    $days = 5;
    $result2 = do_mysqli_query("1","
        SELECT provider.providerid FROM braxproduction.provider 
        where provider.active='Y' and score > 10000
     ");   
    while($row2 = do_mysqli_fetch("1",$result2)){
        
        $result = do_mysqli_query("1",'select chatid, broadcastid from broadcastlog order by chatid, broadcastid');
        $count=0;
        while($row = do_mysqli_fetch("1",$result)){
            
        }
        
        

        
    }


?>