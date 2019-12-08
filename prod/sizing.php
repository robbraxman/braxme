<?php
session_start();
require("config.php");
require("sidebar.inc.php");



    if(@intval($_POST['sizing'])==0){
        exit();
    }
    if(!isset($_SESSION['pid']) || $_SESSION['pid']=='') //Invalid Session
    {
        if(!array_key_exists('reset', $_SESSION)){
            echo "timeout";
        }
        $_SESSION['reset']='Y';
        exit();
    }
    
    
    
    if(isset($_POST['active']) && $_POST['active']=='Y'){
        $_SESSION['timeoutcheck'] = time();
    }
    
    $_SESSION['sizing']=$_POST['sizing'];
    $_SESSION['innerwidth']=$_POST['innerwidth'];
    $_SESSION['innerheight']=@$_POST['innerheight'];
    $_SESSION['pixelratio']=@$_POST['pixelratio'];
    $devicecode = @$_POST['devicecode'];

    $mobiletype=$_POST['mobile'];
    $_SESSION['mobiletype']=$mobiletype;
    $mobiledevice=$_POST['device'];
    $_SESSION['mobiledevice']=$mobiledevice;
    if( $mobiledevice === 'P')
    {
        $_SESSION['mobilesize']='Y';
    }
    else
    if( $mobiletype == 'A' || $mobiletype == 'I')
    {
        //if(intval($_SESSION['innerwidth']) < 1024 )
        //{
            $_SESSION['mobilesize']='Y';
        //}
    }
    else
    if(intval($_SESSION['innerwidth']) < 415 )
    {
            $_SESSION['mobilesize']='Y';

    }
    else
    {
        $_SESSION['mobilesize']='N';
    }
    //echo $_SESSION['sizing'];

    if( TimeOutCheck()){
        echo "timeout";
        exit();
    }
    $notificationstatus =  NotificationStatus ($_SESSION['pid'], false);
    if( $notificationstatus =='Y'){
        echo "sidebar";
        exit();
    }
