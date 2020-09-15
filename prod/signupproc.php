<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
session_start();
require_once("config-pdo.php");
require_once("colorscheme.php");
require_once("aws.php");
require_once("SmsInterface.inc");
require_once("sendmail.php");
require_once("crypt-pdo.inc.php");
require_once("signupfunc.php");
require_once("internationalization.php");


    $gcm = @tvalidator("PURIFY",$_POST['gcm']);
    $apn = @tvalidator("PURIFY",$_POST['apn']);
    $mobile = $gcm.$apn;
    $version = @tvalidator("PURIFY",$_POST['version']);

    if($gcm!=''){
        $source="s=android&gcm=$gcm";
    }  else
    if($apn!='') {
        $source="s=ios&apn=$apn";
    } else {
        $source="s=web";
    }
    
    
    $store = @tvalidator("PURIFY",$_POST['store']);
    $roomhandle = @tvalidator("PURIFY",$_POST['roomhandle']);
    if($store == 'Y'){
        $website = substr($roomhandle, 1);
        $loginlink = $rootserver."/$installfolder/host.php?f=_store&h=$website&store=$website&s=$source&v=$version";
    } else {
        $loginlink = $rootserver."/$startupphp?$source&v=$version";
        
    }
    
    if(true){
        //echo "Sign Up is Temporarily Suspended for Security Reasons.";
        //exit();
    }

    $providerid = @tvalidator("PURIFY","$_POST[providerid]");
    $providername = ucwords(stripslashes(@tvalidator("PURIFY",$_POST['providername'])));
    $password = @tvalidator("PURIFY","$_POST[password]");
    $replysms = @tvalidator("PURIFY",$_POST['replysms']);
    $replyemail = strtolower(@tvalidator("PURIFY",$_POST['replyemail']));
    $handle = @tvalidator("PURIFY",$_POST['handle']);
    $loginid = @tvalidator("PURIFY",$_POST['loginid']);

    if($providerid == ''){
        //exit();
    }
    if($password == ''){
        exit();
    }
    
    $companyname = ucwords(stripslashes(@tvalidator("PURIFY",$_POST['companyname'])));
    $invited = @tvalidator("PURIFY","$_POST[invited]");
    $invitesource = @tvalidator("PURIFY","$_POST[emailinvite]");
    $inviteid = @tvalidator("PURIFY","$_POST[inviteid]");
    
    $accountnote = @tvalidator("PURIFY",$_POST['accountnote']);

    $industry = @tvalidator("PURIFY",$_POST['industry']);
    $enterprise = @tvalidator("PURIFY",$_POST['enterprise']);
    $avatarurl = @tvalidator("PURIFY",$_POST['avatarurl']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $sponsor = @tvalidator("PURIFY",$_POST['sponsor']);
    $onetimeflag = @tvalidator("PURIFY",$_POST['onetimeflag']);
    $termsofuse = @tvalidator("PURIFY",$_POST['termsofuse']);
    $language = @tvalidator("PURIFY",$_POST['language']);
    $timezone = @tvalidator("PURIFY",$_POST['timezone']);
    $deviceid = @tvalidator("PURIFY",$_POST['deviceid']);
    $lastuser = @tvalidator("PURIFY",$_POST['lastuser']);
    $innerwidth = @tvalidator("PURIFY",$_POST['innerwidth']);
    $innerheight = @tvalidator("PURIFY",$_POST['innerheight']);
    $trackerid = @tvalidator("PURIFY",$_POST['trackerid']);
    $mobiletype = @tvalidator("PURIFY",$_POST['mobiletype']);
    
    
    if(!isset($_COOKIE['signup'])) {
        $uniqid = uniqid();
        setcookie("signup", "$uniqid", time() + (86400 * 30), '/','', TRUE, TRUE); // 86400 = 1 day   
        $signupcookie = "";
    } else {
        $signupcookie = $_COOKIE['signup'];
    }   
    
    $ownerid = "";
    if($onetimeflag=='Y'){
        $ownerid = $_SESSION['pid'];
    }
    
    $password = html_entity_decode($password, ENT_NOQUOTES);
    
    $signup = new SignUp;

    $lastuserdecrypted = '';
    if($lastuser!=''){
        $lastuserdecrypted = DecryptJs($lastuser,'');
    }

    $icount = $signup->IPHashCheck($timezone, $deviceid, $lastuserdecrypted,$signupcookie, $innerwidth, $innerheight);
    
    if(!$customsite){
        if($icount > 2 && $trackerid == ''){
            echo "<h2 style='padding:20px;font-family:helvetica'><img src='$applogo' style='height:50px' /><br><br>What are you up to?<br>You're acting suspiciously.<br>Fingerprinted ($icount).</h2>";
            exit();
        }
    }
    //
    
    //Password Single Use Only
    //$onetimeflag = "";
    //if($sponsor!=''){
    //    $onetimeflag = "Y";
    //}
    
    $signup->InitVars(
            $providerid, $providername, $replyemail, $replysms, $handle, $password );
    
    
    //$signup->InactivateDuplicateAccount();
    
    
    if($signup->DuplicateAccountCheck() ){
        //Duplicate Account Message
        DuplicateError($providerid, $replyemail, $invited );
        exit();
    }
    if(!$signup->ValidateHandle(null)){
    
        echo "Duplicate Handle";
        exit();
    }
    
    
    //Create Account
    $signup->CreateAccount(
        $providerid,  //Providerid
        $providername, $replyemail, $replysms,
        $handle, $password,
        $loginid, //LoginID
        $enterprise, //enterprise
        $industry, //industry
        $companyname, //company name
        $sponsor, //$sponsor
        $roomhandle, //Roomhandle
        $roomid, //roomid
        '', //Mobile
        $invited, //invited
        $avatarurl,  //Avatarurl
        'N', //Overwite? (inactivate Dups)
        "$onetimeflag",
        $ownerid,
        $termsofuse,
        $language,
        $timezone,
        $trackerid
        );

    
    if( $signup->GetErrorCount()>0){
        //Error Messages
        echo $signup->DisplayErrors();
        exit();
    }
    if( $signup->VerifyAccountCheck()){
        
        if(!$signup->InviteProcess($inviteid)){
        }
        
        if($store == 'Y'){
            $providerid = $signup->GetNewProviderId();
            $loginlink .= "&p=$providerid";
        }
        
        
        //Message For Success
        if($onetimeflag != 'Y'){
            $_SESSION['signupproviderid'] = "$replyemail";
            $_SESSION['signuploginid'] = "$loginid";

            if($store == 'Y'){
                $_SESSION['returnurl'] = "<br><a href='$loginlink'>Login to Your Account</a>";
            } else {
                $_SESSION['returnurl'] = "<br><a href='$loginlink'>Back to Store</a>";
            }
        
            
    
            if( $invited == "H" ){

                ShowStatus( $replyemail, $loginid, $password, $mobile, $invited, $handle, $mobiletype );
            } else {

                ShowStatus( $replyemail, $loginid, $password, $mobile, $invited, $handle, $mobiletype );
            }
        } else {
                echo 
                "<div style='font-family:Helvetica Neue,helvetica;font-size:15px;margin-auto;text-align:center'>
                    Sign up successful.
                </div>
                ";
        }
        
    } else {
        echo 
                "<div style='font-family:helvetica;font-size:15px;margin-auto;text-align:center'>
                    Sign up failed.
                </div>
                ";
        echo $signup->GetErrorCount();
        echo "
        CreateAccount
        $providerid, 
        $providername, $replyemail, $replysms,
        $handle, $password,
        $loginid, 
        $enterprise, 
        $industry, 
        $companyname, 
        $sponsor, 
        $roomhandle, 
        $roomid, 
        '', 
        $invited, 
        $avatarurl,  
        'N', 
        $onetimeflag,
        $ownerid
        ";
    }
    
    exit();
    
    
    
    function DuplicateError($providerid, $replyemail, $invited )
    {
        global $rootserver;
        global $installfolder;
        global $source;
        global $loginlink;
        $loginscript = "login.php";
            
        if( $invited == 'Y'){
        
            echo "
            <span style='font-size:16px'>
            <center>
                $replyemail<br>
             is a duplicate address.<br><br>
             This email address already<br> 
             belongs to an existing subscriber.
             <br><br>
             <a href='$rootserver/$installfolder/forgotreset.php?pid=$providerid&loginid=admin'>
             Tap/Click here to get a temporary password.
             </a><br>
             Change your password as soon as possible.<br>
             <br><br>
             <a href='$loginlink'>
             Tap/Click here to return to Login.
             </a><br>


             </center>
             </span>
             <script>
             try {
             localStorage.pid = '$replyemail';
             }
             catch(err)
             {}
             </script>
             ";
            return;
            
        } else {
            
            echo "
                <html>
                <head>
                <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=no,maximum-scale=1'>
                </head>
                <body style='background-color:white;color:black;font-family:Helvetica Neue, Helvetica, san-serif;font-weight:200'>
                <center><h2>Sorry!</h2>
                <p class=margined>This email address is a duplicate account.<br>A new account was not established.<br>
                You can reset your password using 'Forgot Password' on the Login page.
                </p>
                <br><br>
                <a href='invite.php'>Back to Sign Up</a>
                </center>
                </body></html>
                ";
            return;

        }
        return;
    }
    


    function ShowStatus( $replyemail, $loginid, $password, $mobile, $invited, $handle, $mobiletype )
    {
        global $rootserver;
        global $installfolder;
        global $global_activetextcolor;
        global $global_background;
        global $global_textcolor;
        global $menu_login;
        global $menu_signupsuccess;
        global $menu_download;
        global $loginlink;
        global $applogo;
        global $appname;
        global $startupphp;
        global $store;
        
        $loginscript = "$startupphp";

        $storelink = "
                        <img class='appstore' src='../img/appStore.png' style='height:50px' >
                        <br><br>
                        <img class='appstore' src='../img/androidplay.png' style='height:50px' >
                        <br><br>
                    ";
        if($appname == 'Brax.Me'){
            if($mobiletype == 'I'){
                $storelink = "
                            <a href='https://itunes.apple.com/us/app/brax-me-private-communities/id939163309?mt=8'  target='_blank' 
                            style='display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/assets/shared/badges/en-us/appstore-lrg.svg) no-repeat;width:135px;height:40px;'>
                            </a>            
                            ";

            }
            if($mobiletype == 'A'){
                $storelink = "
                            <a href='https://play.google.com/store/apps/details?id=me.brax.app1&pcampaignid=MKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1' target='_blank'>
                                <img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png' style='width:135px'/>
                            </a>
                            ";

            }
        }
                    

        $jspassword = EncryptJs($password,"");
        $script =  "
                <script>
                localStorage.pid = '$replyemail';
                localStorage.loginid = '$loginid';
                localStorage.swt = '$jspassword';
                localStorage.pid = '$handle';
                if( '$mobile'!=''){

                    localStorage.changepassword = 1;
                }
                </script>
                ";
        
            
        //Signed up from #Handle Invite
        if( $invited == 'H'){

            if($mobiletype == ''){
                echo "
                <html>
                <head>
                <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=no,maximum-scale=1'>
                </head>
                <body 
                style='text-align:center;background-color:$global_background;color:$global_textcolor;
                font-family:Helvetica, san-serif;
                font-weight:100;max-width:100%'>
                <center><h2>$menu_signupsuccess</h2>
                <p class=margined>
                Username: $handle
                </p>
                ";
            } else {
                echo "
                <html>
                <head>
                <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=no,maximum-scale=1'>
                </head>
                <body 
                style='text-align:center;background-color:$global_background;color:$global_textcolor;
                font-family:Helvetica, san-serif;
                font-weight:100;max-width:100%'>
                <center><h2>Account is Active. Download the mobile App $appname</h2>
                <p class=margined>
                Username: $handle
                </p>
                ";
                
            }
            
            if($mobiletype == ''){
                echo "
                <p class='margined pagetitle2'><a href='$loginlink' target='_blank' style=';text-decoration:none;color:$global_activetextcolor'>$menu_login via Browser </a></p>
                ";
            }
            echo "
                <div class='pagetitle2a' style='text-align:center;color:white'>Please download the $appname mobile app to access all the features. Use the same login credentials</duv>
                <br><br>
                <div style='text-align:center;background-color:transparent;padding:0px;width:auto;color:$global_textcolor;margin:auto'>
                    $storelink
                </div>
                $script
                </body></html>
                ";
            
        }  else {
                

            echo "
                <html>
                <head>
                <meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=no,maximum-scale=1'>
                </head>
                <title></title>
                <body class='newmsgbody margined' 
                style='font-family:Helvetica Neue,helvetica,san-serif;background-color:$global_background;color:$global_textcolor'>
                <br><br><br><br>
                <center>
                    <img src='$applogo' style='height:45px' />
                </center>
                <br>
               
                ";
            


            echo " 
                    <center><h2>$menu_signupsuccess</h2>
                    <a href='$loginlink' 
                    style='font-family:helvetica;text-decoration:none;color:$global_activetextcolor'><h2>$menu_login</h2>
                    </a>
                    ";
            if($mobiletype == ''){
                echo " 
                    <div class='' style='
                         background-color:transparent;color:$global_textcolor;margin:0;padding:30px;opacity:.8;
                            '>
                        <table class='gridnoborder' style='margin:auto'>
                            <tr>
                                <td>

                                    <span class='pagetitle2a' style='color:white'>
                                        $menu_signupsuccess2
                                    </span>
                                    <br><br>
                                    <div class='pagetitle2a' style='color:white;max-width:300px'>
                                        Please download the mobile app to access all the features. Use the same login credentials
                                    </div>
                                    <br>
                                    <div style='text-align:center;background-color:transparent;padding:0px;width:auto;color:$global_textcolor;margin:auto'>
                                        $storelink
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    ";
            }
                
            echo "
                $script
                </body>
                </html>
            ";

        }

        
        
    }

?>
