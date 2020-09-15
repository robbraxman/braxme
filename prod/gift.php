<?php
session_start();
include("config.php");
require("validsession.inc.php");
require("htmlhead.inc.php");
require_once("crypt.inc.php");
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
            $result = do_mysqli_query("1","select providername from provider where providerid=$providerid ");
            if($row = do_mysqli_fetch("1",$result)){
                $sendername = $row['providername'];
            }
            $result = do_mysqli_query("1","select providername from provider where providerid=$owner ");
            if($row = do_mysqli_fetch("1",$result)){
                $receivername = $row['providername'];
            }
            
            if($mode == 'K'){
                $method = 'kudos';
                $msg = "Kudos!";
            } else {
                $method = 'thanks';
                $msg = "Thank You!";
            }
            
            do_mysqli_query("1"," 
                insert into gifts 
                ( xacdate, providerid, method, owner  )
                values 
                ( now(), $providerid, '$method', $owner ) 
                ");
        
            
            
        
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