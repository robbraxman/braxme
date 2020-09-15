<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
if($_SESSION['sponsor']==''){
    $sponsor = "$appname";
} else {
    $sponsor = ucfirst($_SESSION['sponsor']);
}
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div style='padding:10px'>
        <?=$sponsor?>
    </div>
    <div class='abouttext pagetitle2a feedphoto' style='margin:auto;text-align:center'>
        <img class='icon30 about tapped' src='../img/Arrow-Left-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle' style='color:gray'>Tour</span>
        <img class='icon30 info_radio tapped' src='../img/Arrow-Right-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <br>
    <center>
<?php
if($_SESSION['sponsor']==''){
?>
        <img class='icon50' src='../img/logo-b2.png' style='' />
        <br>
        <br>
        <div class='pagetitle2a feedphoto' style='text-align:center;margin:auto;max-width:300px'>
            <?=$appname?> is a privacy focused social media
            platform used by businesses to connect
            with customers. It features:
            <br><br>
            <b>CHAT</b> for direct encrypted conversations.
            <br><br>
            <b>ROOMS</b> for blogging content by subject or creating websites.
            <br><br>
            <b>LIVE</b> for audio/video streaming to private groups.
            <br><br>
            <b>FILES</b> for encrypted cloud storage of files and photos.
            <br><br>
<?php
} else {
?>
        <br>
        <br>
        <div class='pagetitle2a feedphoto' style='text-align:center;margin:auto;max-width:300px'>
            <br><br>
            This platform features:
            <br><br>
            <b>CHAT</b> for direct encrypted conversations.
            <br><br>
            <b>ROOMS</b> for blogging content by subject.
            <br><br>
            <b>LIVE</b> for audio/video streaming to private groups.
            <br><br>
            <b>FILES</b> for encrypted cloud storage of files and photos.
            <br><br>
<?php
}
?>
        <div class='pagetitle2a feedphoto' style='text-align:center;margin:auto;max-width:300px'>
            <br><br>
            
            <div class='smalltext'>
                <img class='icon20' src='../img/logo-b2.png' style='float:left' />
                
                <b>Powered by <?=$appname?> - Secure Enterprise Engine</b></div>
            <br><br>
            <br><br>
            <br><br>
            <br><br>
            
        </div>

    </div>
</div>    

       
                   

