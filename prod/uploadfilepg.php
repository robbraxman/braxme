<?php
session_start();
require_once("config.php");
require_once("crypt.inc.php");
require_once("room.inc.php");
require_once("notify.inc.php");

//require ("password.inc.php");
//require ("accountcheck.inc");
require ("fileprocpg.inc.php");

/*
$uniqid = uniqid('');
$new_image_name = "pgtest_$uniqid_$_SESSION[pid].jpg";
move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/prod/upload/server/php/files/".$new_image_name);
*/



$providerid = '';
if(isset($_SESSION['pid'])){
    $providerid = rtrim(mysql_safe_string( "$_SESSION[pid]"));
}
if($providerid == ''){
    $tmp = @mysql_safe_string( "$_GET[pid]");
    $tmp = explode("-",$tmp);
    $providerid = $tmp[0];
    $chatid = $tmp[1];
    
    //$providerid = @mysql_safe_string( "$_GET[pid]");
    //$providerid = substr($providerid,1);
}

$loginid = mysql_safe_string( "$_SESSION[loginid]");
$album = "";
$uploadtype = "";

if(isset($_SESSION['timezone'])){
    $_SESSION['timezoneoffset'] = floatval($_SESSION['timezone']) - floatval($_SESSION['servertimezone']);
} else {
    $_SESSION['timezone'] = 0;
    $_SESSION['timezoneoffset'] = -8;
}
$today = date("M-d-y",time()+$_SESSION['timezone']*60*60);


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/
    LogDebug(0,"UploadFilePG-".$providerid);
    $img = ProcessUpload( $providerid, "", $uploadtype );
    //$img = ProcessUpload("$providerid","", $subject, $album, $upload_hdr, $uploadtype );
    if( $img == ''){
        LogDebug($providerid,"UploadPG Save Last Func");
        SaveLastFunction($providerid,"F", "$album");
        exit();
    }
    
    $lastfunc = GetLastFunction($providerid, 0);
    if($lastfunc->lastfunc==='C')
    {
        $chatid = intval($lastfunc->parm1);    
        //$message = "$imgurl";
            //echo "<br>Chat ID Ref:$chatid";
        if( $img!='')
        {
            $imgurl = "<img class='feedphotochat' src='$rootserver/$installfolder/doc.php?p=$img&f=*.jpg' alt='Loading Image...'/>";
        }
        $message = "$imgurl";
        $encode = EncryptChat ($message,"$chatid","" );
        $encodeshort = EncryptChat ("Photo Uploaded","$chatid","" );
        
        
        $result = do_mysqli_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( $chatid, $providerid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ");
        $result = do_mysqli_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= $providerid and chatid=$chatid and status='Y'
            ");
        $result = do_mysqli_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=$chatid 
            ");
        
        ChatNotificationRequest($providerid, $chatid, $encodeshort, $_SESSION[responseencoding],'');
        SaveLastFunction($providerid,"C", "$chatid");
    }
    else
    if($lastfunc->lastfunc==='R')
    {
        $roomid = intval($lastfunc->parm1);    
        $result = do_mysqli_query("1",
            "
                select room from statusroom
                where roomid = $roomid limit 1
            ");
        if( $row = do_mysqli_fetch("1",$result))
        {
            $roomForSql = addslashes($row['room']);
        }
        if( $img!='')
        {
            $imgurl = "$rootserver/$installfolder/sharedirect.php?a=$img";
        }
        
        $result = do_mysqli_query("1",
            "
                select providerid,
                (select anonymousflag from roominfo where roominfo.roomid = statusroom.roomid ) as anonymousflag
                from statusroom where roomid = $roomid 
            ");
        while( $row = do_mysqli_fetch("1",$result))
        {
            $notifytype = 'RP';
            if(intval($roomid) > 0)
            {
                $anonymousflag = $row['anonymousflag'];
                $poster = $providerid;
                if( $anonymousflag == 'Y')
                {
                    $poster = 0;
                }
                
                GenerateNotification( 
                    $poster, 
                    $row['providerid'], 
                    'RP', null, 
                    $roomid, null, 
                    null, null,
                    null,'','');
                
            }


        }
        RoomPost( "P", $providerid, "",       $roomid, "", "", "",       "",     "$imgurl", "",0);
        SaveLastFunction($providerid,"R", "$roomid");
    }
    else
    {
        SaveLastFunction($providerid,"F", "$album");
    }
            
        
    //ReturnToMessageEntry();
    //require("htmlfoot.inc");
    exit();

?>