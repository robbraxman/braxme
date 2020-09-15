<?php
session_start();
require_once("config.php");
require_once("htmlhead.inc.php");

$safegreen = "<img src='../img/safe-green-128.png' title='HIPAA Safe - Fully Encrypted Communication' style='height:16px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
$safeyellow = "<img src='../img/safe-yellow-128.png' title='HIPAA Safe - Fully Encrypted Communication' style='height:16px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
$safeorange = "<img src='../img/safe-orange-128.png' title='HIPAA Safe - Fully Encrypted Communication' style='height:16px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

?>
<div style='font-size:13px;font-family:helvetica;padding-left:40px;padding-right:40px'>
                        <br>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <img src='../img/teacher-128.png' style='height:50px;width:auto' />
                        <br><br>
                        <div class="bubblemsg shadow" >
                            <br><br>
                            &nbsp;&nbsp;&nbsp;<b>Tips</b>
                        <ul style="padding-left:20px">
                        <li class='margined'> Access your email accounts through <b>BraxMail</b> regularly to make
                            use of auto-encryption. You can add multiple accounts.
                        <br><br>
                        </li>
                        <li class='margined'> Start putting your photos on <b>BraxSocial Photos</b> instead of Facebook
                            for protected sharing.
                            <br><br>
                        </li>
                        <li class='margined'> Instead of posting comments directly on Facebook, use the <b>Create Text Image</b> feature (in Photos)
                            to convert text to an image with handwriting. This cannot be used for behavioral profiling by Facebook.
                            <br><br>
                        </li>
                        <li class='margined'> Where possible, use <b>BraxChat</b> instead of email or text to communicate with your
                            friends with encryption.
                            <br><br>
                        </li>
                        <li class='margined'> For a more private Social Media experience, share privately using the <b>Private Rooms</b>
                            in BraxSocial.<br><br>
                        </li>
                        <li class='margined'> If you need to privately communicate with someone not on <?=$appname?>, use the <b>BraxSecure</b>
                            feature.<br><br>
                        </li>
                        <li class='margined'> The more friends and family join you on <?=$appname?> the more secure your environment is. Invite them all
                            to join.<br><br>
                        </li>
                        </ul>
                        </div>
</div>