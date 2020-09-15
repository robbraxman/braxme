<?php
session_start();
require_once("config.php");

$roomid = intval(@mysql_safe_string($_POST['roomid']));
$handle = @mysql_safe_string($_POST['roomhandle']);

$providerid = "";

    if($_SESSION['pid']!=''){
    //Logged In User

        include("password.inc.php");
        $providerid = $_SESSION['pid'];

    } else {
    //Not Logged in User

        $userid = mysql_safe_string($_POST['userid']);
        $password = mysql_safe_string($_POST['password']);

        //validate user
        $result = do_mysqli_query("1", 
           " select providerid, pwd_ver, pwd_hash from staff where providerid in 
                (    
                    select providerid from provider where active='Y' and
                    (replyemail = '$userid' or (handle = '$userid' and handle!='') )
                )
                and active='Y' 
            ");
        if( $row = do_mysqli_fetch("1",$result)){

            $providerid = $row['providerid'];
            if($row['pwd_ver']>=3){
                $password = strtolower($password);
                if( !password_verify("$password", $row['pwd_hash'])){
                    $providerid = "";
                }
            }
        }
        if($providerid==''){

            echo "<div style='font-family:helvetica;font-size:15px;padding:20px;margin:auto;text-align:center;background-color:white;color:black'>Invalid User $userid.</div>";
            exit();
        }

    }
    
    if($handle!=''){
    
        $result = do_mysqli_query("1","
        select roomid from roomhandle where handle='$handle' 
        ");
        if($row = do_mysqli_fetch("1",$result))
        {
            $roomid = $row['roomid'];
        }
        
    }
    
    if(intval($roomid)<=1){
        echo "<div style='font-family:helvetica;font-size:15px;padding:20px;margin:auto;text-align:center;background-color:white;color:black'>Invalid Room ($roomid).</div>";
        exit();
    }

    
    

    $result = do_mysqli_query("1","
        select roomid, room, owner from statusroom where owner=providerid and roomid=$roomid
        ");
    if( $row = do_mysqli_fetch("1",$result))
    {
        //$inviteroomname='';
        $inviteroom = $row['roomid'];
        $owner = $row['owner'];
    }
        
        

    do_mysqli_query("1","
        insert into statusroom ( roomid, owner, providerid,status, createdate, creatorid ) values
        ( $roomid, $owner, $providerid,'Y',now(),$providerid )
        ");


    echo "<div style='font-family:helvetica;font-size:15px;padding:20px;margin:auto;text-align:center;background-color:white;color:black'><b>You have been added to the Room.</b></div>";
        
?>