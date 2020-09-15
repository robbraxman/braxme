<?php
session_start();
require_once("config-pdo.php");
require_once("sendmail.php");
require_once ("SmsInterface.inc");
require_once ("crypt-pdo.inc.php");
require_once ("room.inc.php");
require_once ("notify.inc.php");
include("lib_autolink.php");

    $providerid = $_SESSION['pid'];//@tvalidator("PURIFY",$_POST['providerid']);
    $smstext = @tvalidator("PURIFY",$_POST['smstext']);
    $text = @tvalidator("PURIFY",$_POST['text']);
    $texttitle = @tvalidator("PURIFY",$_POST['texttitle']);
    $roomid = @tvalidator("PURIFY",$_POST['roomid']);
    $textgroup = @tvalidator("PURIFY",rtrim($_POST['textgroup']));
    $excludesms = @tvalidator("PURIFY",rtrim($_POST['excludesms']));
    $test = @tvalidator("PURIFY",rtrim($_POST['test']));
    $photo = @tvalidator("PURIFY",rtrim($_POST['photo']));
    
    $text_htmlentities = htmlentities(strip_tags(stripslashes($text)), ENT_QUOTES);
    $texttitle_htmlentities = htmlentities(strip_tags(stripslashes($texttitle)), ENT_QUOTES);
    $smstext_htmlentities = htmlentities(strip_tags(stripslashes($smstext)), ENT_QUOTES);
    
    //Textcredentialfilter
    $credentialgroup = explode(',',$textgroup);
    $credentialfilter = "";
    $credentialquery = "";
    if($textgroup!='')
    {
        foreach($credentialgroup as $credentialpair)
        {
            $credentialpair1 = explode('=',$credentialpair);
            if(rtrim($credentialpair1[1])!='*'){ //Non-WildCard
                $credentialfilter .= " and ".$credentialpair1[0]."='".$credentialpair1[1]."' ";
            }
        }
    }
    echo "<div style='background-color:#E5E5E5;font-size:15px;font-family:helvetica;padding:20px'>";
    echo "<img class='grouptext' src='../img/arrow-stem-circle-left-128.png' style='cursor:pointer;height:20px;position:relative;top:3px;'> Back to Group Messaging<br><br>";

    echo "
        <script>
        if( typeof localStorage.grouptext != 'undefined' ){
            localStorage.grouptext = $('<div/>').html('$text_htmlentities').text();
            localStorage.grouptexttitle = $('<div/>').html('$texttitle_htmlentities').text();
            localStorage.groupsmstext = $('<div/>').html('$smstext_htmlentities').text()    ;
            localStorage.grouptextphoto = '$photo';
            localStorage.grouptextroomid = '$roomid';
         }
        </script>";

    /*
    echo "
        title: $texttitle<br>
        text: $text<br>
        smstext: $smstext<br>
        roomid: $roomid<br>
        textgroup: $textgroup<br>
        excludesms: $excludesms<br>
            
        ";
    */
    
    if($text ==='' && $smstext === '')
    {
        echo "No message</div>";
        exit();
    }
    if($roomid ==='')
    {
        echo "No Room selected</div>";
        exit();
    }
    if($providerid ==='')
    {
        echo "No account</div>";
        exit();
    }
    
    
    /*
     * 
     *   Mobile Portion
     * 
     * 
     */
    
    
    $result = pdo_query("1","
        select 
        replyemail, providername, providerid as recipientid,
        (select room from statusroom where roomid=$roomid and owner=$providerid limit 1) as roomname
        from provider where providerid in
        (select providerid from statusroom 
            where roomid = $roomid and
            owner = $providerid 
        )
            ");
        //    and providerid!=$providerid

    $count = 0;
    while($row = pdo_fetch($result))
    {
        if( $count == 0)
        {
            $roomname = htmlspecialchars( $row['roomname']);
            if( $text!='' && $test!='Y')
            {
                RoomPost( 'P', $providerid, '',$roomid, $roomname, $texttitle,  $text,    '',     $photo,     '',    'N',0);
            }
            
            $textclean = stripslashes($smstext);
            echo "<b>Group Message to Room</b><br>$roomname<br><br>";
            echo "<b>SMS/Notification Message</b><div style='max-width:80%;color:firebrick'>$textclean</div><br>";
            echo "<b>Room Message</b><div style='max-width:80%;color:firebrick'>$texttitle_htmlentities<br>$text_htmlentities</div><br>";
            echo "Sending Mobile/Text to:<br>";
            if($excludesms == 'Y')
            {
                echo "(Exclude SMS)<br>";
            }
            echo "<hr>";
        }
        $count++;
        echo "<div class='smalltext'>$count - $row[providername]</div>";
        
        $encodeshort = EncryptChat ($smstext,"$row[recipientid]","" );
        $encode = EncryptChat ($text,"$row[recipientid]","" );
        if($smstext == '')
        {
            $encodeshort = "$roomname - Post";
        }
        
        if($test == 'Y')
            continue;

        GenerateNotification( 
            $providerid, 
            $row['recipientid'], 
            'T', null, 
            $roomid, null, 
            $encodeshort, $encodeshort,
            $_SESSION['responseencoding'],'','' );

            
    }
    
    
    /*
     * 
     *   SMS Portion
     * 
     */
    
    if( $smstext !== '' && $excludesms!='Y')
    {
        $result = pdo_query("1","
            select sms, email, name from csvtemp
                where roomid = $roomid and
                ownerid = $providerid 
                and email not in
            (
                select replyemail
                from provider where providerid in
                (select providerid from statusroom 
                    where roomid = $roomid and
                    owner = $providerid 
                )
                $credentialquery
            )
                ");

        $smscount = 0;
        while($row = pdo_fetch($result))
        {
            if( $smscount == 0)
            {
            }
            $count++;
            if($row['sms']!='')
            {
                $smscount++;
                $smsflag = "(SMS)";
            }
            else 
            {
                $smsflag = "(Email)";
            }
            echo "<div class='smalltext'>$count - $row[name] $smsflag</div>";
            
            if($row['sms']=='+13105551212')
            {
                continue;
            }
            if(strstr( $row['email'],"@test.test"))
            {
                continue;
            }
            
            if($test == 'Y')
                continue;

            //
            $encodeshort = base64_encode($smstext );
            $encoding = "BASE64";

            GenerateNotificationSms( 
                $providerid, 
                $row['name'], $row['email'], $row['sms'],
                'T', 
                $roomid,  
                $encodeshort,
                $encoding,'','' );


        }
    }
    else
    {
        echo "<br>No SMS Message Sent -- Room Post Only";
    }
    
    
    
    echo "<hr><br>$count app messages sent";
    echo "<br>$smscount SMS messages sent</div>";
    exit();
    
    

?>