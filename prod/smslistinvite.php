<?php
session_start();
require_once("config.php");
require ("SmsInterface.inc");
require ("sendmail.php");

if($batchruns!='Y')
    exit();

    $result = do_mysqli_query("1",
        "
        delete from csvtemp
        where 
            email in
            (
                select replyemail
                from provider where provider.replyemail = csvtemp.email 
            )
        and 
        email not like '%@test.test' and sms!='+13105551212'
        "
        );


    $result = do_mysqli_query("1",
        /*
        "
        select sms, email, name, roomid from csvtemp
        where 
        email not like '%@test.test' and sms!='+13105551212'
        "
         * 
         */
        "
        select sms, email, name, roomid from csvtemp
        where 
            email not in
            (
                select replyemail
                from provider where provider.replyemail = csvtemp.email 
            )
        and 
        email not like '%@test.test' and sms!='+13105551212'
        "
        );
    
     while( $row = do_mysqli_fetch("1",$result))
     {
        $name = $row['name'];
        $email = $row['email'];
        $roomid = $row['roomid'];

        $err = false;
         
        $e = explode("@", $email);
        if (!checkdnsrr($e[1], 'MX')) {
            echo "<br>$email domain $e[1] not valid<br>";
            $err = true;
        }         
        if( filter_var($email, FILTER_VALIDATE_EMAIL))
        {

           SendReminderEmail($name, $email, $roomid );
           //echo "OK<br>";
        }
        
     }
    
    function SendReminderEmail($name, $email, $roomid )
    {
        global $appname;
        global $rootserver;
        global $installfolder;
        global $app_smtp_email;
            
        $result = do_mysqli_query("1", 
                "
                select roominfo.roomid, roominfo.private, roominfo.room, 
                roomhandle.handle 
                from roominfo
                left outer join roomhandle on roomhandle.roomid = roominfo.roomid 
                where roominfo.roomid = $roomid
                ");
        if($row = do_mysqli_fetch("1",$result))
        {
            $private = $row['private'];
            $room = $row['room'];
            $handle = $row['handle'];
        }
        
        
        $message = "
        <div style='width:600px;background-color:whitesmoke;color:black;padding:40px;font-family:helvetica;border:1px solid gray'>
            <h2 style='color:steelblue'>Hi $name,
            </h2>
            <h3>
            <span style='color:firebrick'>$room</span> has an invitation waiting for you on Brax.Me.
            </h3>
            <p style='color:black'>
                You have received this message because <b>$room</b> has been sending you 
                alerts and messages via text and/or email. This organization uses the Brax.Me 
                platform for its communications.
                <br><br>
                You will get messages faster and your
                experience will be enhanced if you
                use the mobile app instead. 
                All you have to do is install the Brax.Me mobile app and sign up to the <u>free</u> app.
                The mobile app is available on the iOS Appstore or Android Google Play.
                <br><br>
                If you don't have a smart phone, you can also access the website at <a href='https://brax.me'>Brax.Me</a>.
                You can also benefit from instant notifications if you use Chrome as your browser.
                <br><br>
                You will automatically be joined to the room of the group and have more information
                available to you, as well as real time chat with everyone in the group.
                <br><br>
                One nice thing about communicating through Brax.Me is that all your messages 
                are completely private. Only the group can see it.
                <br><br>
                Please sign up now and join everyone else! Thank you.
                <br>
                <br>
            </p>
            <a href='https://brax.me'>
            <img src='https://brax.me/img/lock.png' style='height:30px;width:auto' /><br>
            </a>
            Secure Group Communications https://brax.me
            <br>
            <br>
            <img src='https://brax.me/img/appStore.png' style='height:50px;width:auto' /><br><br>
            <img src='https://brax.me/img/androidplay.png' style='height:50px;width:auto' /><br>


        </div>
        ";
        SendMail("0", "Invitation to  $room", "$message", "$message", "$name", "$email" );

            echo "$app_smtp_email<br>$message<br>";
        
        
    }
    
    
    
    
    
        
        
    

        


     

    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        
        return $phone;
    }
    function FormatPhone( $phone )
    {
        $area = substr( $phone, 0, 3);
        $num1 = substr( $phone, 3, 3);
        $num2 = substr( $phone, 6, 4);
        
        if( $area == '')
            return "";
        
        return "(".$area.") ".$num1."-".$num2;
    }


?>