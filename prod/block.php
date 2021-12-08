<?php
session_start();
require_once("config-pdo.php");

require_once("htmlhead.inc.php");
require_once("crypt-pdo.inc.php");
?>
<script>
        $(document).ready( function() {
        });
</script>
</head>
<body class="appbody">

<?php


$providerid = @tvalidator("ID",$_SESSION[pid]);
$recipientname = ucwords( tvalidator("PURIFY","$_POST[recipientname]") );

$recipientemail = @tvalidator("PURIFY","$_POST[recipientemail]");
$sms = @tvalidator("PURIFY","$_POST[sms]");
$sms = InternationalizePhone(CleanPhone($sms));
$handle = @tvalidator("PURIFY","$_POST[handle]");
$mode = @tvalidator("PURIFY",$_POST['mode']);


if( $mode=='B'){

    $result = pdo_query("1","
            select providerid, banid from provider where 
            active='Y' and
            (
            (replyemail='$recipientemail' and '$recipientemail'!='')
            or ( handle ='$handle' and '$handle'!='')
            )",null);
    if($row = pdo_fetch($result)){
        $targetproviderid = $row['providerid'];
        $banid = $row['banid'];
    } else {
        exit();
    }
    
    $result2 = pdo_query("1","
        select * from roommoderator where roommoderator.roomid in 
          (select roominfo.roomid 
          from roominfo 
          left join roomhandle on roominfo.roomid = roomhandle.roomid
          where roominfo.roomid in (select roomid from roommoderator) and community ='Y')
          and providerid = $targetproviderid
        ",null);
    if($row2 = pdo_fetch($result2)){
        exit();
    }
    //can't block admin;
    if($targetproviderid == $admintestaccount){
        exit();
    }
    
    
    $result2 = pdo_query("1","select providerid from provider where (providerid=$targetproviderid or (banid='$banid'  and banid!=''  and banid is not null)) and providerid!= $providerid ",null);
    while($row2 = pdo_fetch($result2)){
        
        $banproviderid = $row2['providerid'];
        
        $result = pdo_query("1","
                update contacts set blocked='Y' where providerid=$providerid and targetproviderid =$banproviderid 

                ",null);
        $result = pdo_query("1","
                delete from blocked where blocker = $providerid and blockee = $banproviderid    
                ",null);
        $result = pdo_query("1","
                insert into blocked (blocker, blockee, created ) values ($providerid, $banproviderid, now() )

                ",null);
    }
    
    

    exit();
}
if( $mode=='U'){

    $result = pdo_query("1","
            select providerid, banid from provider where 
            active='Y' and
            (
            (replyemail='$recipientemail' and '$recipientemail'!='')
            or ( handle ='$handle' and '$handle'!='')
            )",null);
    if($row = pdo_fetch($result)){
        $targetproviderid = $row['providerid'];
        $banid = $row['banid'];
    } else {
        exit();
    }
    
    $result2 = pdo_query("1","select providerid from provider where (providerid=$targetproviderid or (banid='$banid'  and banid!=''  and banid is not null)) and providerid!= $providerid ",null);
    while($row2 = pdo_fetch($result2)){
        
        $banproviderid = $row2['providerid'];
        
        $result = pdo_query("1","
                update contacts set blocked='' where providerid=$providerid and targetproviderid =$banproviderid 

                ",null);
        $result = pdo_query("1","
                delete from blocked where blocker = $providerid and blockee = $banproviderid    
                ",null);
    }
    
    

    exit();
}




if( $mode!='D'){

    if ( !filter_var($recipientemail, FILTER_VALIDATE_EMAIL) && $handle==''){
    
        echo "Contact not Saved - Invalid Email";
        exit();
    }        
    
}

    
    $result = pdo_query("1", 
            " delete from contacts " .
            " where providerid=$providerid and " .
            " contactname='$recipientname' " .
            " and email='$recipientemail'  ",null);
    
    
    if( $mode=='D'){
    
        if($result) {
            echo "Contact Deleted";
        } else {
            echo "SQL Delete Error";
        }
                    
    } else {
        
        if($recipientname == ''){
        
            echo "Contact not saved - no recipient name";
            exit();
        }
            
        $result = pdo_query("1", 
                " update contacts " .
                " set blocked='' ".
                " where providerid=$providerid  " .
                " and ( (email='$recipientemail' and '$recipientemail'!='')  ".
                " or (handle='$handle' and '$handle'!='')) ",null
               );
        $result = pdo_query("1", 
                " select providerid from provider where (
                    (handle = '$handle' and '$handle'!='') or
                    (replyemail = '$recipientemail' and '$recipientemail'!='' )
                  )
                ",null
                );
        $targetproviderid = "null";
        if($row = pdo_fetch($result)){
            $targetproviderid = $row['providerid'];
        }
        
        
        $result = pdo_query("1", 
                " insert into contacts " .
                " (providerid, contactname, email, sms, handle, blocked, targetproviderid, createdate ) ".
                " values ".
                " ( $providerid,'$recipientname','$recipientemail', '$sms', '$handle','',$targetproviderid, now() " .
                " )",null);
        if($result){
            echo "Contact Saved";
        } else {
            echo "SQL Insert Error";
        }
        
            
    }
function CleanPhone( $phone )
{
    $phone = str_replace( "(", "", $phone );
    $phone = str_replace( "/", "", $phone );
    $phone = str_replace( ")", "", $phone );
    $phone = str_replace( " ", "", $phone );
    $phone = str_replace( "-", "", $phone );
    $phone = str_replace( ".", "", $phone );

    return rtrim($phone);
}
function InternationalizePhone ( $phone )
{
    if($phone == ''){
        return "";
    }
    if( $phone[0]!='+' && $phone !='')
        $phone = "+1".$phone;

    return $phone;
}

?>
    </body>
</html>
