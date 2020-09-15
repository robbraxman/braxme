<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="https://www.braxsecure.com/smsg/libs/jquery-1.9.0/jquery-ui.css" type="text/css" />
    <link rel="styleSheet" href="https://www.braxsecure.com/smsg/local.css" type="text/css">
    
    <script src="https://www.braxsecure.com/smsg/libs/jquery-1.9.0/jquery.min.js"></script>    
    <script src="https://www.braxsecure.com/smsg/libs/jquery-1.9.0/jquery-ui.min.js"></script>
    
    
    <script src="https://www.braxsecure.com/smsg/libs/validation/dist/jquery.validate.js"></script>
    <script src="https://www.braxsecure.com/smsg/libs/validation/dist/jquery.validate.min.js"></script>
    <script src="https://www.braxsecure.com/smsg/libs/validation/dist/additional-methods.js"></script>
    <script src="https://www.braxsecure.com/smsg/libs/validation/dist/additional-methods.min.js"></script>
       </script>
<BODY class="newmsgbody">
    <img class="viewlogofullsize" src="https://www.braxsecure.com/smsg/logo1.png">
    <a href="http://www.braxsecure.com"><h3>Brax-Secure Message Broker and Patient Portal Service</h3></a>
        <p></p>
        <p></p>
            
         <p><b>BraxSecure</b> is a Cloud service and uses state-of-the-art equipment with network and hardware redundancy and 24/7 monitoring.</p>

         <p><h3>For Dealer Inquiries or the location of your nearest Reseller, please contact (310) 213-6900</h3></p>
<p>BraxSecure is a product of:<br>Maddison-Crosse Software Inc.<br>12424 Wilshire Blvd. Ninth Floor,<br>Los Angeles California, 90034</p>         
</div>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo "<br>";
echo "<br>";
echo "<h2>Service Demo</h2>";
echo "<p>This demonstration can show you how simple it is to send a message. At the mininum you just need the recipient's SMS (SmartPhone) and Name. " .
     "For this demonstration you are patient 'Barack Obama'. To fully appreciate the demo, " . 
     "you should try using a different phone and email for sender vs. receiver. If demonstrating only on a single smartphone, then you want to put the same " .
     "phone number in the Recipient Phone and Reply Phone so you can see both parts of the flow.</p>";
echo "<a href=" .
     "\"https://www.braxsecure.com/smsg/newmsg_v1.0.php?" .
     "replysms=3102136900&refid=R01&pid=590001024&password=demo" . 
     "&rlname=Obama&rfname=Barack&phone=" . 
     "&email=brax.tech.1@gmail.com&mrno=100000&pfname=John&plname=Doe" .
     "&replyemail=brax.tech.1@gmail.com" .
     "&dob=03-14-1936&challenge=Enter Your Last Name (hint: Your first name is Barack)&doctype=Test Result" . 
     "&msg=We have examined your Test Results from the Colonoscopy and found everything to be normal. Thank you for regularly having preventive care performed. Best regards, MyTestClinic. " .
     "\">Create a Message</a>";
echo "<br>";
echo "<br>";
echo "<p>This demonstration shows you what an actual recipient will see and how they interface with the message. You will see the concept of a response key. " .
     "You will also see that the recipients are able to reply that will go directly to the SMS of the sender.</p>";
echo "<a href=" .
     "\"https://www.braxsecure.com/smsg/vw_1_0.php?sid=5145eeb41fdb0" .
     "\">View a Message from Email/Phone</a>";
echo "<br>";
echo "<br>";
echo "<p>This demonstration shows you what a reply looks like when it goes back to the original sender.</p>";
echo "<a href=" .
     "\"https://www.braxsecure.com/smsg/vw_1_0.php?sid=51469bfcd001c" .
     "\">View a Reply from Email/Phone</a>"
?>
        <br>
        <br>
        <br>
        
</BODY>
</HTML>
