<?php
session_start();
require_once("validsession.inc.php");
require( "config.php");
require( "htmlhead.inc.php")

?>
</head>
<title>Privacy Tips</title>
</head>
<BODY class="appbody" style="padding:0;margin:0;background-image:url('https://brax.me/prod/img/Blurred Backgrounds (12).jpg');background-size:cover">
    <div style='background-color:white;padding:10%;max-width:90%;margin:auto'>
    <span style='font-family:"helvetica nueue", helvetica;font-weight:100'>
            <img src="../img/logo-b2.png" style="width:auto;height:45px;">
            </span>
        <br><br>
        <center><h1><?=$appname?> Privacy Tips</h1></center>
    <div class='mainfont'>
                    <strong>Hiding your Email</strong> <br /><br />
                    If you provided an email, it will be automatically hidden from the entire App when you 
                    give yourself a @handle in My Identity. Your handle will be shown 
                    instead and this is retroactive, meaning once you've created a handle,
                    all prior interactions will show the new handle.
                    <br><br>
                    <strong>Mobile Phone Number</strong> <br /><br />
                    Your mobile number is stored with encryption. It is never displayed anywhere in the app
                    It is safe to enter. It will provide an extra layer of security if someone 
                    attempts to change your password. But unlike other companies that do 2-factor authentication 
                    we treat your mobile number as privately as a password.
                    <br><br>
                    <strong>Private vs. Open Rooms</strong> <br /><br />
                    Rooms you join may be labeled as 'Private' or 'Open Membership' 
                    rooms. This is clearly stated at the top of each room. This means anyone can join as 
                    long as they are <?=$appname?> subscriber. Be aware of where you are posting.
                    <br><br>
                    <strong>Meta Data</strong> <br /><br />
                    All your actual posts and contents are stored with high level encryption and are hack 
                    resistant. However note that some metadata will be visible in our database and are in plaintext. 
                    These include:
                    <br><br>
                    <ul>
                        <li>Email Addresses and @Handles</li>
                        <li>File Names and File Descriptions in My Files</li>
                        <li>Album Names in My Photos</li>
                        <li>Manually entered Contacts</li>
                        <li>Room Names and Hashtags</li>
                        <li>Timestamps of General Activity</li>
                    </ul>
                    Thus, use caution with these data items. You can always change these at any time.
                    <br><br>
                    <strong>End to End (E2E) Encrypted Chat</strong> <br /><br />
                    A higher level of encryption is available in Chat and requires that you create a secret 
                    key for all participants. It is automatically distributed. If this level of encryption is used, it eliminates 
                    the possibility of 'backdoor' decryption of a previous conversation without possession of the key, even 
                    when required by government agencies. It also hides conversations even if your 
                    password is compromised.
                    <br><br>
                    <strong>Delete Old Chat</strong> <br /><br />
                    If a conversation in chat has been completed, it is a good idea to delete the conversation. 
                    Once deleted, it can never be restored and encryption keys will disappear.
                    <br><br>
                    <strong>Room Posts</strong> <br /><br />
                    You can always delete any of your room posts at any time. Also, if a Room is deleted, 
                    or if you leave a room the 
                    posts are likewise permanently deleted.
                    <br><br>
                    <strong>Non-Searchable Content</strong> <br /><br />
                    Because of the encryption, your posts and messages are not searchable. Thus you cannot be 
                    profiled.
                    <br><br>
                    <strong>Location and IP Address</strong> <br /><br />
                    We do not store or track your IP address with the following exception. If someone attempts 
                    to login and reaches the retry limit, or if someone uses Forgot Password, 
                    we will log and block the IP hash to 
                    guard against hackers. We do not track your location or use MAC address crowd-sourced Wifi locations.
                    <br><br>
                    <strong>Database Backups</strong> <br /><br />
                    We have a short window for database backups. After 7 days, we no longer keep the backups so 
                    we will be unable to recreate data beyond that point. We do not have backups of files and photos.
                    <br><br>
                    <strong>External and HTTP Links</strong> <br /><br />
                    All interactions inside the app use HTTPS. External content shared as links in Rooms and Chat 
                    may be trackable HTTP links. In addition, external links may also store cookies for Ad tracking.
                    Note that these are outside the <?=$appname?>  app and you have the option of not clicking on any. For 
                    additional protection related to this, you can run <?=$appname?>  over a TOR Browser.
                    <br><br>
                    To protect against HTTPS spoofing, connect to <?=$appname?>  using a VPN (Virtual Private Network).
                    Also, check our Certificate provider. It should be 'Let's Encrypt'.
                    <br><br>
    </div>


    </div>
    
    </div>
    
</body></html>

<?php
exit();
?>
