<?php

//Color Specifics
$icon_braxmenu =    "<img class='icon25'  title='Main Menu' src='../img/brax-menu-round-white-128.png' />";
$icon_braxlive =    "<img class='icon25'  title='Live' src='../img/brax-live-round-white-128.png' />";
$icon_braxchat =    "<img class='icon25'  title='Chat' src='../img/brax-chat-round-white-128.png' />";
$icon_braxroom =    "<img class='icon25'  title='Rooms' src='../img/brax-room-round-white-128.png'  />";
$icon_braxphoto =  "<img class='icon25'  title='My Photos' src='../img/brax-photo-round-white-128.png' />";
$icon_braxdoc =    "<img class='icon25'  title='My Files' src='../img/brax-doc-round-white-128.png'  />";
$icon_braxpeople =    "<img class='icon25'  title='Find People' src='../img/brax-people-round-white-128.png'  />";
$icon_braxsettings =    "<img class='icon25'  title='Settings' src='../img/brax-settings-round-white-128.png' />";

$icon_braxstop =    "<img class='icon25' src='../img/Stop-Music-White_120px.png'  />";
$icon_braxlogout =    "<img class='icon25' src='../img/logout-circle-128.png' />";

$global_menu_color = '#3e4749';//gray
$global_banner_color = 'black';//gray
$global_titlebar_color = '#a1a1a4';//gray

if($_SESSION['superadmin']=='Y'){
    $global_menu_color = '#00aeef';//azure
    $global_banner_color = '#004990';//michigan
    
}
?>