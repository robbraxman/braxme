<?php
session_start();
require_once("config.php");

require_once("htmlhead.inc.php");
require_once("crypt.inc.php");
?>
<script>
        $(document).ready( function() {
        });
</script>
</head>
<body class="appbody">

<?php


//$providerid = mysql_safe_string("$_POST[providerid]");
$providerid = @$_SESSION[pid];
$recipientname = ucwords( mysql_safe_string("$_POST[recipientname]") );

$recipientemail = @mysql_safe_string("$_POST[recipientemail]");
$sms = @mysql_safe_string("$_POST[sms]");
$sms = InternationalizePhone(CleanPhone($sms));
$handle = @mysql_safe_string("$_POST[handle]");
$mode = @mysql_safe_string($_POST['mode']);


if( $mode=='B'){

    $result = do_mysqli_query("1","
            select providerid, banid from provider where 
            active='Y' and
            (
            (replyemail='$recipientemail' and '$recipientemail'!='')
            or ( handle ='$handle' and '$handle'!='')
            )");
    if($row = do_mysqli_fetch("1",$result)){
        $targetproviderid = $row['providerid'];
        $banid = $row['banid'];
    } else {
        exit();
    }
    
    $result2 = do_mysqli_query("1","select providerid from provider where (providerid=$targetproviderid or (banid='$banid'  and banid!=''  and banid is not null)) and providerid!= $providerid ");
    while($row2 = do_mysqli_fetch("1",$result2)){
        
        $banproviderid = $row2['providerid'];
        
        $result = do_mysqli_query("1","
                update contacts set blocked='Y' where providerid=$providerid and targetproviderid =$banproviderid 

                ");
        $result = do_mysqli_query("1","
                delete from blocked where blocker = $providerid and blockee = $banproviderid    
                ");
        $result = do_mysqli_query("1","
                insert into blocked (blocker, blockee, created ) values ($providerid, $banproviderid, now() )

                ");
    }
    
    

    exit();
}
if( $mode=='U'){

    $result = do_mysqli_query("1","
            select providerid, banid from provider where 
            active='Y' and
            (
            (replyemail='$recipientemail' and '$recipientemail'!='')
            or ( handle ='$handle' and '$handle'!='')
            )");
    if($row = do_mysqli_fetch("1",$result)){
        $targetproviderid = $row['providerid'];
        $banid = $row['banid'];
    } else {
        exit();
    }
    
    $result2 = do_mysqli_query("1","select providerid from provider where (providerid=$targetproviderid or (banid='$banid'  and banid!=''  and banid is not null)) and providerid!= $providerid ");
    while($row2 = do_mysqli_fetch("1",$result2)){
        
        $banproviderid = $row2['providerid'];
        
        $result = do_mysqli_query("1","
                update contacts set blocked='' where providerid=$providerid and targetproviderid =$banproviderid 

                ");
        $result = do_mysqli_query("1","
                delete from blocked where blocker = $providerid and blockee = $banproviderid    
                ");
    }
    
    

    exit();
}




if( $mode!='D'){

    if ( !filter_var($recipientemail, FILTER_VALIDATE_EMAIL) && $handle==''){
    
        echo "Contact not Saved - Invalid Email";
        exit();
    }        
    
}

    
    $result = do_mysqli_query("1", 
            " delete from contacts " .
            " where providerid=$providerid and " .
            " contactname='$recipientname' " .
            " and email='$recipientemail'  ");
    
    
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
            
        $result = do_mysqli_query("1", 
                " update contacts " .
                " set blocked='' ".
                " where providerid=$providerid  " .
                " and ( (email='$recipientemail' and '$recipientemail'!='')  ".
                " or (handle='$handle' and '$handle'!='')) "
               );
        $result = do_mysqli_query("1", 
                " select providerid from provider where (
                    (handle = '$handle' and '$handle'!='') or
                    (replyemail = '$recipientemail' and '$recipientemail'!='' )
                  )
                "
                );
        $targetproviderid = "null";
        if($row = do_mysqli_fetch("1",$result)){
            $targetproviderid = $row['providerid'];
        }
        
        
        $result = do_mysqli_query("1", 
                " insert into contacts " .
                " (providerid, contactname, email, sms, handle, blocked, targetproviderid, createdate ) ".
                " values ".
                " ( $providerid,'$recipientname','$recipientemail', '$sms', '$handle','',$targetproviderid, now() " .
                " )");
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
