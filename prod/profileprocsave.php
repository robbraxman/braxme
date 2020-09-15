<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("signupfunc.php");


    $providerid = @tvalidator("PURIFY","$_POST[providerid]");
    $providername = ucwords(@tvalidator("PURIFY",$_POST['providername']));
    $name2 = ucwords(@tvalidator("PURIFY",$_POST['name2']));
    $alias = ucwords(@tvalidator("PURIFY",$_POST['alias']));
    $positiontitle = ucwords(@tvalidator("PURIFY",$_POST['positiontitle']));
    $password = @tvalidator("PURIFY","$_POST[password]");
    $replysms = @tvalidator("PURIFY",$_POST['replysms']);
    $replyemail = strtolower(@tvalidator("PURIFY",$_POST['replyemail']));
    $handle = @tvalidator("PURIFY",$_POST['handle']);
    $welcome = @tvalidator("PURIFY",$_POST['welcome']);
    $inactivitytimeout = intval(@tvalidator("PURIFY",$_POST['inactivitytimeout']))*60;
    $pin = @tvalidator("PURIFY",$_POST['pin']);
    if(strlen($pin)!=4){
        $pin = "";
    }
    $colorscheme = @tvalidator("PURIFY",$_POST['colorscheme']);
    $hardenter = @tvalidator("PURIFY",$_POST['hardenter']);
    
    
    $companyname = ucwords(@tvalidator("PURIFY",$_POST['companyname']));

    $industry = @tvalidator("PURIFY",$_POST['industry']);
    $enterprise = @tvalidator("PURIFY",$_POST['enterprise']);
    $sponsor = @tvalidator("PURIFY",$_POST['sponsor']);
    $sponsorlist = @tvalidator("PURIFY",$_POST['sponsorlist']);
    $streamplatform = @tvalidator("PURIFY",$_POST['streamplatform']);
    $streamingaccount = "";
    if($streamplatform!=''){
        $streamplatform.="/";
        $streamingaccount = @tvalidator("PURIFY",$_POST['streamingaccount']);
    }
    $enable_email = @tvalidator("PURIFY",$_POST['enable_email']);
    if( $enable_email != 'Y'){
        $enable_email = 'N';
    }
    $publish = @tvalidator("PURIFY",$_POST['publish']);
    if( $publish != 'Y'){
        $publish = 'N';
    }
    $publishprofile = @tvalidator("PURIFY",$_POST['publishprofile']);
    
    $roomdiscovery = @tvalidator("PURIFY",$_POST['roomdiscovery']);
    if( $roomdiscovery == ''){
        $roomdiscovery = 'N';
    }
    
    
    $notifications = @tvalidator("PURIFY","$_POST[notifications]");
    $notificationflags = @tvalidator("PURIFY","$_POST[notificationflags]");

    $active = 'Y';
    $terminateaccount = @tvalidator("PURIFY",$_POST['terminateaccount']) ;
    if($terminateaccount == 'Y'){
        $active = 'N';
    }
    
    $gift = @tvalidator("PURIFY",$_POST['gift']);
    if( $gift == ''){
        $gift = 'N';
    }
    $wallpaper = @tvalidator("PURIFY",$_POST['wallpaper']);
    
    
    
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
