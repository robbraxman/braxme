<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div style='padding:10px'>
        Brax.Me
    </div>
    <div class='abouttext pagetitle2a feedphoto' style='margin:auto;text-align:center'>
        <img class='icon30 info_final tapped' src='../img/Arrow-Left-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle' style='color:gray'>Tour</span>
        <img class='icon30 info_radio tapped'     src='../img/Arrow-Right-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <br><br>
        <center>
            <span class='pagetitle2' style='color:black;'>
                <b>Interact with others in...</b>
            </span>
            <br><br>
            <div class='rounded chatlistbox gridstdborder' style='margin-bottom:10px;display:inline-block;vertical-align:top;padding:20px;background-color:whitesmoke'>
                <img  class='icon50' src='../img/brax-live-round-white-128.png' style='top:5px;padding-bottom:5px' />
                <br><br><span class='pagetitle2a'><b>Live</b><br><br>Streaming Broadcasts with Group Chat</span>
            </div>
            <div class='rounded chatlistbox gridstdborder' style='margin-bottom:10px;display:inline-block;vertical-align:top;padding:20px;background-color:whitesmoke'>
                <img  class='icon50'src='../img/brax-room-round-white-128.png' style='top:5px;padding-bottom:5px' />
                <br><br><span class='pagetitle2a'><b>Rooms</b><br><br>Group Talk by Subject</span>
            </div>
            <div class='rounded chatlistbox gridstdborder' style='margin-bottom:10px;display:inline-block;vertical-align:top;padding:20px;background-color:whitesmoke'>
                <img  class='icon50' src='../img/brax-chat-round-white-128.png' style='top:5px;padding-bottom:5px' />
                <br><br><span class='pagetitle2a' style='display:inline-block'><b>Chat</b><br><br>Direct Messaging</span>
            </div>
            
            <br><br>
            <span class='pagetitle2' style='color:black;'>
                <b>Find Contacts in...</b>
            </span>
            <br><br>
            <div class='rounded chatlistbox gridstdborder' style='margin-bottom:10px;display:inline-block;vertical-align:top;padding:20px;background-color:whitesmoke'>
                <img  class='icon50' src='../img/brax-people-round-white-128.png' style='top:5px;padding-bottom:5px' />
                <br><br><span class='pagetitle2a'><b>People</b><br><br>Public and Community Lists</span>
            </div>
            
            <br><br>
            <span class='pagetitle2' style='color:black;'>
                <b>Manage your personal data in...</b>
            </span>
            <br><br>
            <div class='rounded chatlistbox gridstdborder' style='margin-bottom:10px;display:inline-block;vertical-align:top;padding:20px;background-color:whitesmoke'>
                <img  class='icon50' src='../img/brax-photo-round-white-128.png' style='top:5px;padding-bottom:5px' />
                <br><br><span class='pagetitle2a'><b>My Photos</b><br><br>Your Personal Photo Storage</span>
            </div>
            <div class='rounded chatlistbox gridstdborder' style='margin-bottom:10px;display:inline-block;vertical-align:top;padding:20px;background-color:whitesmoke'>
                <img  class='icon50' src='../img/brax-doc-round-white-128.png' style='top:5px;padding-bottom:5px' />
                <br><br><span class='pagetitle2a'><b>My Files</b><br><br>Your Personal File Storage</span>
            </div>

            
            <br><br>
            <!--
            <img src='../img/braxmail-square.png' style='height:50px' />
            <br><span class='pagetitle3'>Encrypted Email</span>
            <br><br>
            -->
        <br><br>
        </center> 
    </div>
</div>    

       
                   

