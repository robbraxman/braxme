<?php
session_start();
require("validsession.inc.php");
require("config.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
    $braxchat = "<img class='icon20' src='../img/brax-chat-round-white-128.png' style='' />";
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div class='selectchatlist' data-mode='CHAT' style='padding:10px'>
    <?=$braxchat?> Chat
    </div>
    <div class='abouttext pagetitle2a feedphoto' style='text-align:center'>
        <img class='icon30 info_room tapped' src='../img/Arrow-Left-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle' style='color:gray'>Tour</span>
        <img class='icon30 info_photo tapped'     src='../img/Arrow-Right-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >

        <br><br>
        <div style='text-align:center;max-width:600px;margin:auto'>

        <b class='startchatbutton' style='cursor:pointer;color:<?=$global_activetextcolor?>'>Chat</b> 
        is direct messaging between two or more parties. Conversations are always encrypted.
        Safe for medical, financial, and private exchange.
        <br><br>        
        Start a chat by finding a party in PEOPLE. You start a chat with one person and you can add 
        as many people to the chat as you want.
        <br><br>        
        When you use Chat, no message trace appears on your phone or computer. Your messages are 
        accessible from any device.
        <br><br>
        Instant notification just like Texting.
        <br><br>
        Exchange files and photos instantly.
        <br><br>
        Recall your messages with ease.
        <br><br>
        </div>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
    </div>
</div>    

       
                   

