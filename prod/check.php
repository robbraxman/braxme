<?php
session_start();
require("validsession.inc.php");
require("config.php");
require("sidebar.inc.php");



    if(@intval($_POST['sizing'])>0 ){
        $_SESSION['sizing']=mysql_safe_string($_POST['sizing']);
    }
    
    
    if(isset($_POST['active']) && mysql_safe_string($_POST['active'])=='Y'){
        $_SESSION['timeoutcheck'] = time();
    }
    if(!isset($_POST['innerwidth']) ){
        exit();
    }
    
    $_SESSION['innerwidth']=@mysql_safe_string($_POST['innerwidth']);
    $_SESSION['innerheight']=@mysql_safe_string($_POST['innerheight']);
    $_SESSION['pixelratio']=@mysql_safe_string($_POST['pixelratio']);
    if(isset($_POST['timezoneoffset']) && @mysql_safe_string($_POST['timezoneoffset']!='')){
        $_SESSION['timezoneoffset']=mysql_safe_string($_POST['timezoneoffset']);
    }
    $chatid =@mysql_safe_string($_POST['chatid']);
    
    if(isset($_POST['mobile'])){
        $mobiletype=mysql_safe_string($_POST['mobile']);
        $_SESSION['mobiletype']=$mobiletype;
    }
    if(isset($_POST['device'])){
        
        $mobiledevice=mysql_safe_string($_POST['device']);
        $_SESSION['mobiledevice']=$mobiledevice;
        if( $mobiledevice === 'P'){
        
            $_SESSION['mobilesize']='Y';
        } else
        if( $mobiletype == 'A' || $mobiletype == 'I'){
        
            //if(intval($_SESSION['innerwidth']) < 1024 )
            //{
                $_SESSION['mobilesize']='Y';
            //}
        } else
        if(intval($_SESSION['innerwidth']) < 415 ){
        
                $_SESSION['mobilesize']='Y';

        } else {
        
            $_SESSION['mobilesize']='N';
        }
        
    }
    
    if(intval($chatid) > 0 && intval($_SESSION['pid']) > 0 ){
        
        $result = do_mysqli_query("1","
            select chatmaster.lastmessage        
            
        from chatmembers 
            left join chatmaster on chatmembers.chatid = chatmaster.chatid
            where chatmembers.chatid = $chatid
            and chatmembers.providerid = $_SESSION[pid]
            and chatmaster.lastmessage > chatmembers.lastread
                ");
        if($row = do_mysqli_fetch("1",$result)){
                echo "chat";
                exit();
        } else {
            exit();
        }
    }
    if(@intval($_POST['sizing'])==0 ){
        exit();
    }
    
    
    //echo $_SESSION['sizing'];
    if(!isset($_SESSION['pid']) || $_SESSION['pid']=='') //Invalid Session
    {
        if(!array_key_exists('reset', $_SESSION)){
            echo "timeout";
        }
        $_SESSION['reset']='Y';
        exit();
    }

    if( TimeOutCheck()){
        echo "timeout";
        exit();
    }
    $notificationstatus =  NotificationStatus ($_SESSION['pid'], true);
    if( $notificationstatus =='Y'){
        echo "sidebar";
        exit();
    }
