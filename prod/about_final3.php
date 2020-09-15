<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div style='padding:10px'>
    Quick Tips
    </div>
    <div class='abouttext pagetitle3 feedphoto' style='text-align:center'>
        <img class='icon30 info_final2 tapped' src='../img/arrow-stem-circle-left-128.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle'  style='color:gray'>Tour</span>
        <img class='icon30 tilebutton tapped'     src='../img/arrow-stem-circle-right-128.png' style='margin-left:20px;margin-right:20px' >
        <br><br>
        <div style='text-align:center;max-width:600px;margin:auto'>
        Wherever you see a Profile photo, you can tap on that photo to start a private chat with that person.
        <br><br>
        Check out your profile in SETTINGS - MY IDENTITY. Decide if you will be listed in the Public List. This 
        helps your community and friends to find you.
        <br><br>
        You can chat with non-members by sending them an email invitation. They will then be able to see your 
        message as soon as they sign up. You can use this for both personal and business communications.
        <br><br>
        You should register an email address in MY IDENTITY to protect your account when you forget your password.
        Otherwise you cannot do a password reset.
        </div>
    </div>
</div>    

       
                   

