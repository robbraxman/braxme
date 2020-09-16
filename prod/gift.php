<?php
session_start();
include("config-pdo.php");
require("validsession.inc.php");
require("htmlhead.inc.php");
require_once("crypt-pdo.inc.php");
require_once("notify.inc.php");

//$mode = @tvalidator("PURIFY", $_GET['mode'] );
$owner = @tvalidator("PURIFY", $_POST['account'] );
$mode = @tvalidator("PURIFY", $_POST['mode'] );
//$tokens = 1;

        
    $message = Gift( $_SESSION['pid'], strtoupper($mode), $owner );
       
 

    function Gift( $providerid, $mode, $owner )
    {
            if($providerid == '' || $owner ==''){
                return "Gift Processing Error $providerid $owner ";    
            }
            if($providerid == $owner){
                return "Error Detected - You cannot gift yourself $tokens $providerid  ";    
            }
            $sendername='';
            $receivername='';
            $result = pdo_query("1","select providername from provider where providerid=? ",array($providerid));
            if($row = pdo_fetch($result)){
                $sendername = $row['providername'];
            }
            $result = pdo_query("1","select providername from provider where providerid=? ",array($owner));
            if($row = pdo_fetch($result)){
                $receivername = $row['providername'];
            }
            
            if($mode == 'K'){
                $method = 'kudos';
                $msg = "Kudos!";
            } else {
                $method = 'thanks';
                $msg = "Thank You!";
            }
            
            pdo_query("1"," 
                insert into gifts 
                ( xacdate, providerid, method, owner  )
                values 
                ( now(), ?, ?, ? ) 
                ",array($providerid,$method,$owner));
        
            
            
        
        GenerateNotificationV2( 
        $providerid, 
        $owner, //recipient 
        "RP", "TK", 
        0, 0, 
        "$msg", "",
        "PLAINTEXT", "", "", "" );

        return "$sendername, thank you for giving $receivername a $msg";    
        

    }
    

?>
</head>
<body>
    <div style='font-family:helvetica;margin:auto;;width:100%;text-align:center'>
        <img class='icon50' src='../img/logo-b2a.png' />
        <br><br>
        <b><?=$appname?></b>
        <br><br>
        <div class='pagetitle' style='padding:20px;margin:auto;text-align:center'><b><?=$message?></b></div>
        <br><br>
        <div class='pagetitle2a' style='margin:auto;text-align:center'>
            <a href='<?=$rootserver?>/<?=$startupphp?>'>Continue</a>
        </div>
    </div>
</body>
</html>