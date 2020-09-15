<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("room.inc.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $url = @tvalidator("PURIFY",$_POST['url']);
    $backgroundcolor = @tvalidator("PURIFY",$_POST['backgroundcolor']);
    $color = @tvalidator("PURIFY",$_POST['color']);
    $trimcolor = @tvalidator("PURIFY",$_POST['trimcolor']);

    $content = html_entity_decode(file_get_contents($url));    
    $sizing = RoomSizing();
    $mainwidth = $sizing->mainwidth;
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img src='../img/arrow-stem-circle-left-128.png' style='position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $calendaricon= "<img src='../img/calendar-128.png' style='position:relative;top:3px;height:20px;width:auto;padding:0' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    
    
    
    
?>    
    <br>
    &nbsp;&nbsp;
    <center>
    <div class='divbuttontextonly feed tapped' 
        id='feed' data-roomid='<?=$roomid?>' style='color:<?=$trimcolor?>'>
                Home
    </div>
    </center>
    <div class='mainfont' style='width:<?=$mainwidth?>;margin:auto;padding:0px'>
        <div class='mainfont' style='width:auto;margin:0;padding:20px'>
            <?=$content?>
        </div>
    </div>
                

