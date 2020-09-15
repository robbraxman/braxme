<?php
session_start();
require_once("config.php");
require_once("aws.php");
require_once("crypt.inc.php");
require_once("hl7funcs.php");

/**********************************************************************
 *    IMPORTANT - THIS IS RUN ON BATCH BOX!!!!
 **********************************************************************/


    $mac = @mysql_safe_string( "$_REQUEST[mac]");
    $ip = $_SERVER['REMOTE_ADDR'];

    $mode = @mysql_safe_string( "$_REQUEST[mode]");

    $msg = @mysql_safe_string("$_REQUEST[msg]");
    $sms = @mysql_safe_string("$_REQUEST[sms]");

    $sms = CleanPhone($sms);
    if(strlen($sms)<10 || $msg == ''){
        echo "Invalid Request";
    }
    /*
    $result = do_mysqli_query("6"," 
        select * from hl7device where macaddress='$mac' 
        and status in ('Y','B','U') and ipaddress='$ip'
            ");

    if(!$row = do_mysqli_fetch("1",$result)){
        echo "Unauthorized Device";
     
        $result = do_mysqli_query("6"," 
            update hl7device set checkin = now() where macaddress = '$mac')
                ");
        return false;
    }
    $providerid = $row['providerid'];
    */
    $providerid = 690001027;

    do_mysqli_query("1","
        insert into csvtext ( ownerid, message, sms, uploaded, status, error ) values
        ($providerid, '$msg','$sms', now(), 'N', '' )
        ");
    
    echo "Accepted";
    
    
    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );

        if( $phone!='' && $phone[0]!='+'){
            $phone = "+1".$phone;
        }

        return $phone;
    }
