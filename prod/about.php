<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
//Test
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div style='padding:10px'>
    Quick Tips
    </div>
    <div class='abouttext pagetitle2a' style='text-align:center'>
        <img class='icon30 tilebutton tapped' src='../img/Arrow-Left-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle'  style='color:gray'>Tour</span>
        <img class='icon30 info_final tapped'     src='../img/Arrow-Right-in-Circle_120px.png' style='margin-left:20px;margin-right:20px' >
        <br><br>
        <center>
        <div class='circular3' style=';overflow:hidden'>
            <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
        </div>
            <b>Hi! Some quick tips...</b>
        </center>
        
        <div class='pagetitle3' style='text-align:center;max-width:250px;margin:auto'>
            <img class='icon35 rounded' src='../img/newbie2.jpg' /><br><br>
            Update your user profile by tapping this photo on the top left.
            <br>
            <img class='icon35' src='../img/menu-circle-black-128.png' /><br><br>Return to the Main Menu with this button. 
            <br>
            <img class='icon35' src='../img/ellipsis-128.png' /><br><br>This means there are more menu options. 
            <br>
            <img class='icon35 rounded' src='../img/userpic.png' /><br><br>
                You can chat with someone by tapping on their photo.
        
        </div>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
    </div>
</div>    

       
                   

