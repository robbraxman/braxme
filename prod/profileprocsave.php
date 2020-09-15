<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("signupfunc.php");


    $providerid = @mysql_safe_string("$_POST[providerid]");
    $providername = ucwords(@mysql_safe_string($_POST['providername']));
    $name2 = ucwords(@mysql_safe_string($_POST['name2']));
    $alias = ucwords(@mysql_safe_string($_POST['alias']));
    $positiontitle = ucwords(@mysql_safe_string($_POST['positiontitle']));
    $password = @mysql_safe_string("$_POST[password]");
    $replysms = @mysql_safe_string($_POST['replysms']);
    $replyemail = strtolower(@mysql_safe_string($_POST['replyemail']));
    $handle = @mysql_safe_string($_POST['handle']);
    $welcome = @mysql_safe_string($_POST['welcome']);
    $inactivitytimeout = intval(@mysql_safe_string($_POST['inactivitytimeout']))*60;
    $pin = @mysql_safe_string($_POST['pin']);
    if(strlen($pin)!=4){
        $pin = "";
    }
    $colorscheme = @mysql_safe_string($_POST['colorscheme']);
    $hardenter = @mysql_safe_string($_POST['hardenter']);
    
    
    $companyname = ucwords(@mysql_safe_string($_POST['companyname']));

    $industry = @mysql_safe_string($_POST['industry']);
    $enterprise = @mysql_safe_string($_POST['enterprise']);
    $sponsor = @mysql_safe_string($_POST['sponsor']);
    $sponsorlist = @mysql_safe_string($_POST['sponsorlist']);
    $streamplatform = @mysql_safe_string($_POST['streamplatform']);
    $streamingaccount = "";
    if($streamplatform!=''){
        $streamplatform.="/";
        $streamingaccount = @mysql_safe_string($_POST['streamingaccount']);
    }
    $enable_email = @mysql_safe_string($_POST['enable_email']);
    if( $enable_email != 'Y'){
        $enable_email = 'N';
    }
    $publish = @mysql_safe_string($_POST['publish']);
    if( $publish != 'Y'){
        $publish = 'N';
    }
    $publishprofile = @mysql_safe_string($_POST['publishprofile']);
    
    $roomdiscovery = @mysql_safe_string($_POST['roomdiscovery']);
    if( $roomdiscovery == ''){
        $roomdiscovery = 'N';
    }
    
    
    $notifications = @mysql_safe_string("$_POST[notifications]");
    $notificationflags = @mysql_safe_string("$_POST[notificationflags]");

    $active = 'Y';
    $terminateaccount = @mysql_safe_string($_POST['terminateaccount']) ;
    if($terminateaccount == 'Y'){
        $active = 'N';
    }
    
    $gift = @mysql_safe_string($_POST['gift']);
    if( $gift == ''){
        $gift = 'N';
    }
    $wallpaper = @mysql_safe_string($_POST['wallpaper']);
    
    
    
    $signup = new SignUp;
    
    
    //Create Account
    $signup->EditAccount(
        $providerid,  //Providerid
        $providername, 
        $name2, 
        $replyemail, 
        $replysms,
        $handle, 
        $alias,
        $positiontitle,
        $active,
        $sponsor,
        $enterprise, //enterprise
        $industry, //industry
        $companyname, //company name
        $enable_email,
        $notifications,
        $notificationflags,
        $publish,
        $publishprofile,
        $roomdiscovery,
        $streamplatform.$streamingaccount,
        $welcome,
        $sponsorlist,
        $inactivitytimeout,
        $pin,
        $colorscheme,
        $gift,
        $wallpaper,
        $hardenter
        );

    
    
    /*
    function EditAccount(
            $providerid, $providername, $replyemail, $replysms, $handle, $password, 
            $alias, $active, $sponsor,
            $enterprise, $industry, $companyname, $enable_email, $notifications )
    */
    
    echo "<div style='padding:20px;font-family:helvetica, arial, san-serif' >";
    if( $signup->GetErrorCount()>0){
        //Error Messages
        echo $signup->DisplayErrors();
        echo "<br>Changes not saved.<br>";
        exit();
    }
    if( $terminateaccount == 'Y'){
        echo "<br>Account will be closed.<br>";

    } else {
        echo $signup->DisplayMessages();
        echo "<br>Subscriber info saved<br>";
    }
    echo "</div";
    exit();
    
    

?>
