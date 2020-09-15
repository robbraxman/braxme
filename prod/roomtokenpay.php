<?php
session_start();
include("config-pdo.php");
require("validsession.inc.php");
require("htmlhead.inc.php");
require("roommanage.inc.php");

$paycode = @tvalidator("PURIFY", $_POST['paycode'] );
$mode = @tvalidator("PURIFY", $_GET['mode'] );
$roomid = @tvalidator("PURIFY", $_GET['roomid'] );



    if($mode == 'cancel1' || $mode == 'cancel2' || $mode == 'cancel3'){
        
        $message = 'Paypal Transaction Canceled';
        
    }
    if(strtoupper($mode) == 'P' ){

        
       $message = Pay( $_SESSION['pid'], $roomid, strtoupper($mode) );
       
    }
    
 

    function Pay( $providerid, $roomid, $mode)
    {
            if($roomid == '' || $providerid == ''){
                return "Error Detected Token Pay Room Id $roomid ";    
            }
        
            $days = 0;
            $interval = "";
            $result = pdo_query("1"," 
                select statusroom.owner, roominfo.subscription, roominfo.subscriptiondays
                from roominfo
                left join statusroom on roominfo.roomid = statusroom.roomid and statusroom.owner = statusroom.providerid
                where roominfo.roomid=$roomid and statusroom.owner = statusroom.providerid
                ");
            if($row = pdo_fetch($result)){
                $ownerid = $row['owner'];
                $tokens = $row['subscription'];
                $days = $row['subscriptiondays'];
                
                
                $month ="";
                $year = "";
                $interval = "";
                
                if($days < 30){
                    $interval = "interval $days day";
                } else 
                if($days == 30){
                    $month = 1;
                    $interval = "interval $month month";
                } else 
                if($days == 365){
                    $year = 1;
                    $interval = "interval $year year";
                } else {
                    $interval = "interval $days day";
                }
                
            } else {
                
                return "Error Detected Paid $tokens Room $roomid Owner $ownerid";    
            }
            
            $sendername='';
            $receivername='';
            $result = pdo_query("1","select providername from provider where providerid=$providerid ");
            if($row = pdo_fetch($result)){
                $sendername = $row['providername'];
            }
            $result = pdo_query("1","select providername from provider where providerid=$owner ");
            if($row = pdo_fetch($result)){
                $receivername = $row['providername'];
            }
            
            
            
            $tokenbalance = 0;
            $tokensspent = 0;
            $tokensavailable = 0;
            $result = pdo_query("1"," 
                select sum(tokens) as tokensspent from tokens where providerid = $providerid and DC='C' 
                ");
            if($row = pdo_fetch($result)){
                $tokensspent = $row['tokensspent'];
            }
            
            $result = pdo_query("1"," 
                select sum(tokens) as tokensavailable from tokens where providerid = $providerid and dc='D' and method!='TEST'
                ");
            if($row = pdo_fetch($result)){
                $tokensavailable = $row['tokensavailable'];
            }
            
            if($tokens < 0){
                $tokens = 0;
            }
            $tokenbalance = $tokensavailable - $tokensspent;
            
            //Temporarily Turned off for testing
            if($tokensavailable < $tokensspent + $tokens){
                return "Insufficent Tokens available - Your balance is $tokenbalance.";    
                
            }
        
            pdo_query("1"," 
                insert into tokens 
                ( xacdate, providerid, tokens, roomid, owner, method, dc  )
                values 
                ( now(), $providerid, $tokens, $roomid, $ownerid, 'SUBSCRIPTION','C' ) 
                ");
            
            
            AddMember($ownerid, $providerid, $roomid );
            
            
            pdo_query("1"," 
                update statusroom set subscribedate=now() where roomid = $roomid and providerid = $providerid
                ");
            if($interval!=''){
                pdo_query("1"," 
                update statusroom set expiredate=date_add(now(),$interval) where roomid = $roomid and providerid = $providerid
                ");
            }
            
        //return "Thank you for your Tokens! $tokensavailable $tokensspent $tokens $roomid $ownerid";    
        if($mode == 'D'){
            return "Thank you for your donation!";    
            
        }    
        return "Thank you for your subscription!";    

    }
    

?>
</head>
<body>
    <div style='font-family:helvetica;margin:auto;padding:20px;width:100%;text-align:center'>
        <img class='icon50' src='../img/logo-b2a.png' />
        <br><br>
        <b><?=$appname?></b>
        <br><br>
        <div class='pagetitle' style='margin:auto;text-align:center'><b><?=$message?></b></div>
        <br><br>
        <div class='pagetitle2a' style='margin:auto;text-align:center'>
            <a href='<?=$rootserver?>/l.php'>Continue</a>
        </div>
    </div>
</body>
</html>