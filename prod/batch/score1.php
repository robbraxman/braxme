<?php
session_start();
set_time_limit ( 30 );
require_once("config.php");

$providerid = mysql_safe_string($_GET['providerid']);


$timestamp = time();

        $score1 = 0;

        $result = do_mysqli_query("1","
            SELECT count(*) as score1 from broadcastlog 
            where providerid = $providerid and mode ='B'
        ");
        if($row = do_mysqli_fetch("1",$result)){
            $score1 += intval($row['score1'])*200;
        }
        $result = do_mysqli_query("1","
            SELECT count(*) as score1, sum(chatcount) as chatcount from broadcastlog 
            where providerid = $providerid and mode in ('V','R')
        ");
        if($row = do_mysqli_fetch("1",$result)){
            //Cap the credit of a chat count
            //There was a bug in chat count - will need to reaaddress
            $chatcount = intval($row['chatcount']);
            if($chatcount > 20){
                $chatcount = 20;
            }
            $score1 += intval($row['score1'])*10*$chatmultiplier;
            $score1 += $chatcount*2;
        }


        $result = do_mysqli_query("1","
            SELECT count(*) as score1, 
            (select count(*) FROM braxproduction.statuspost where statuspost.roomid = statusroom.roomid) as postcount
            where
            statusroom.owner = statusroom.providerid
            and statusroom.providerid = $providerid
        ");
        if($row = do_mysqli_fetch("1",$result)){
            if($row['postcount']>2){
                $score1 += intval($row['score1'])*100;
            }

        }

        $result = do_mysqli_query("1","
            SELECT count(*) as score1 FROM braxproduction.statuspost 
            left join statusreads on statuspost.postid = statusreads.postid
            where statusreads.xaccode ='L' and statuspost.providerid = $providerid
            and statusreads.providerid != $providerid
        ");
        if($row = do_mysqli_fetch("1",$result)){
            $score1 += intval($row['score1'])*10;
        }
        $result = do_mysqli_query("1","
            SELECT count(*) as score1 FROM braxproduction.statuspost 
            left join statusreads on statuspost.postid = statusreads.postid
            where statusreads.xaccode in ('P','R') and statuspost.providerid = $providerid
        ");
        if($row = do_mysqli_fetch("1",$result)){
            $score1 += intval($row['score1'])*10;
        }
        $result = do_mysqli_query("1","
            SELECT count(*) as score1 FROM braxproduction.statuspost 
            left join statusreads on statuspost.postid = statusreads.postid
            where statusreads.xaccode ='L' and statusreads.providerid = $providerid
            and statuspost.providerid != $providerid
        ");
        if($row = do_mysqli_fetch("1",$result)){
            $score1 += intval($row['score1'])*2;
        }
        
        do_mysqli_query("1","update provider set score = $score1 where providerid = $providerid ");
        
    


?>