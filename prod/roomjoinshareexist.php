<?php
session_start();
require_once("config-pdo.php");

$roomid = intval(@tvalidator("ID",$_POST['roomid']));
$handle = @tvalidator("PURIFY",$_POST['roomhandle']);

$providerid = "";

    if($_SESSION['pid']!=''){
    //Logged In User

        include("password.inc.php");
        $providerid = $_SESSION['pid'];

    } else {
    //Not Logged in User

        $userid = tvalidator("PURIFY",$_POST['userid']);
        $password = tvalidator("PURIFY",$_POST['password']);

        //validate user
        $result = pdo_query("1", 
           " select providerid, pwd_ver, pwd_hash from staff where providerid in 
                (    
                    select providerid from provider where active='Y' and
                    (replyemail = '$userid' or (handle = '$userid' and handle!='') )
                )
                and active='Y' 
            ");
        if( $row = pdo_fetch($result)){

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
    
        $result = pdo_query("1","
        select roomid from roomhandle where handle='$handle' 
        ");
        if($row = pdo_fetch($result))
        {
            $roomid = $row['roomid'];
        }
        
    }
    
    if(intval($roomid)<=1){
        echo "<div style='font-family:helvetica;font-size:15px;padding:20px;margin:auto;text-align:center;background-color:white;color:black'>Invalid Room ($roomid).</div>";
        exit();
    }

    
    

    $result = pdo_query("1","
        select roomid, room, owner from statusroom where owner=providerid and roomid=$roomid
        ");
    if( $row = pdo_fetch($result))
    {
        //$inviteroomname='';
        $inviteroom = $row['roomid'];
        $owner = $row['owner'];
    }
        
        

    pdo_query("1","
        insert into statusroom ( roomid, owner, providerid,status, createdate, creatorid ) values
        ( $roomid, $owner, $providerid,'Y',now(),$providerid )
        ");


    echo "<div style='font-family:helvetica;font-size:15px;padding:20px;margin:auto;text-align:center;background-color:white;color:black'><b>You have been added to the Room.</b></div>";
        
?>