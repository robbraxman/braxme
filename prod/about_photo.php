<?php
session_start();
require("validsession.inc.php");
require("config.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
$braxsocial = "<img class='icon20' src='../img/brax-photo-round-white-128.png' style='' />";
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div style='padding:10px'>
    <?=$braxsocial?> My Photos
    </div>
    <div class='abouttext pagetitle2a feedphoto' style='text-align:center'>
        <img class='icon30 info_chat tapped' src='../img/Arrow-Left-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle' style='color:gray'>Tour</span>
        <img class='icon30 info_file tapped'     src='../img/Arrow-Right-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <br><br>
        <div style='text-align:center;max-width:600px;margin:auto'>
<b class='photolibrary' data-deletefilename='' style='cursor:pointer;color:<?=$global_activetextcolor?>'>My Photos</b> is your safe photo 
library without metadata tracking.
<br><br>
           
Any photo taken in the app automatically goes to <b class='photolibrary' data-deletefilename='' style='cursor:pointer;color:<?=$global_activetextcolor?>'>My Photos</b>  
and is immediately available for sharing in Chat and Rooms.
<br><br>
Photos are automatically removed from your device and are stored in your cloud storage space.
<br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        </div>
    </div>
</div>    

       
                   

