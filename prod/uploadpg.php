<?php
session_start();
//require("validsession.inc.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");

require_once("room.inc.php");


require_once("notify.inc.php");
require_once("photouploadprocpg.inc.php");

/*
$uniqid = uniqid('');
$new_image_name = "pgtest_$uniqid_$_SESSION[pid].jpg";
move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/prod/upload/server/php/files/".$new_image_name);
*/


$chatid = 0;
$providerid = '';
if(isset($_SESSION['pid'])){
    $providerid = rtrim(tvalidator("PURIFY", "$_SESSION[pid]"));
}
if($providerid == ''){
    $tmp = @tvalidator("PURIFY", "$_GET[pid]");
    $tmp = explode("-",$tmp);
    $providerid = $tmp[0];
    $chatid = $tmp[1];
    LogDebug($providerid,"UploadPG($chatid)$providerid");
    //$providerid = substr($providerid,1);
}
//LogDebug($providerid,"UploadPG");
$loginid = 'admin';
$mode = @tvalidator("PURIFY", "$_GET[c]");
$subject = "Mobile Upload";
//$album = "MobileUpload";
$uploadtype = "";

//LogDebug($providerid, "0-photo: Got Here");
   // LogDebug(4,"UploadPG-$providerid");
if(isset($_SESSION['timezone'])){
    $_SESSION['timezoneoffset'] = floatval($_SESSION['timezone']) - floatval($_SESSION['servertimezone']);
} else {
    $_SESSION['timezone'] = 0;
    $_SESSION['timezoneoffset'] = -8;
}
$today = date("M-d-y",time()+$_SESSION['timezone']*60*60);
$album = "Upload-".$today;

$upload_hdr = "upload-zone/files";
    


/*********************************************************************
 *                       UPLOAD
 *********************************************************************/

    $img = ProcessUpload("$providerid","", $subject, $album, $upload_hdr, $uploadtype );
    if( $img == ''){
        exit();
    }
    
    //exit();
    
    $lastfunc = GetLastFunction($providerid, 0);
    //$chatid = intval($lastfunc->parm1);    
    
    //if($lastfunc->lastfunc==='C' && $mode == 'photos'){
    if(intval($chatid) > 0 && $mode == 'photos'){
        LogDebug($providerid,"UploadPG / $chatid $mode");
    
        //  exit();
        
        if( $img!=''){
        
            $imgurl = "<img class='feedphotochat' src='$rootserver/$installfolder/sharedirect.php?a=$img' alt='Loading Image...'/>";
        }
        
        if($chatid == 0){
            exit();
        }
        
        $message = "$imgurl";
        $encode = EncryptChat ($message,"$chatid","" );
        $encodeshort = EncryptChat ("Photo Taken","$chatid","" );
        
        $result = pdo_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                values
                ( $chatid, $providerid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
            ");
        $result = pdo_query("1",
            "
            update chatmembers set lastmessage=now(), lastread=now() where providerid= $providerid and chatid=$chatid and status='Y'
            ");
        $result = pdo_query("1",
            "
            update chatmaster set lastmessage=now() where chatid=$chatid 
            ");
        ChatNotificationRequest($providerid, $chatid, $encodeshort, $_SESSION['responseencoding'],'P');

        SaveLastFunction($providerid,"C", "$chatid");
    }
    else
    if($lastfunc->lastfunc==='R' && intval($lastfunc->parm1)>0 && $mode == 'photos'){
    
        exit();
        //disabled
        
        
        $roomid = intval($lastfunc->parm1);    
        $result = pdo_query("1",
            "
                select room from statusroom
                where roomid = $roomid limit 1
            ");
        if( $row = pdo_fetch($result)){
        
            $roomForSql = addslashes($row['room']);
        }
        if( $img!=''){
        
            $imgurl = "$rootserver/$installfolder/sharedirect.php?a=$img";
        }
        
        $result = pdo_query("1",
            "
                select providerid,
                (select anonymousflag from roominfo where roominfo.roomid = statusroom.roomid ) as anonymousflag
                from statusroom where roomid = $roomid 
            ");
        while( $row = pdo_fetch($result)){
        
            $notifytype = 'RP';
            if(intval($roomid) > 0){
            
                $anonymousflag = $row['anonymousflag'];
                $poster = $providerid;
                if( $anonymousflag == 'Y'){
                
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
        
        RoomPost( "P", $providerid, "",       $roomid, "", "", "",       "",     "$imgurl", "", 0);
        SaveLastFunction($providerid,"R", "$roomid");
    } else
    if($lastfunc->lastfunc==='U' && intval($lastfunc->parm1)>0 && $mode == 'camera'){
    
        $roomid = intval($lastfunc->parm1);    
        if( $img!=''){
        
            $imgurl = "$rootserver/$installfolder/sharedirect.php?a=$img";
            pdo_query("1","update provider set avatarurl='$imgurl', lastactive=now() where providerid=$providerid ");
        }
        
        SaveLastFunction($providerid,"A", "");
        
    } else
    if($lastfunc->lastfunc==='A' ){
        
        SaveLastFunction($providerid,"A", "");
        
    } else {
        
        SaveLastFunction($providerid,"P", "$album");
    }
            
        
    //ReturnToMessageEntry();
    //require("htmlfoot.inc");
    exit();

?>