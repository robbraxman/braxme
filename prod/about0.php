<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
$welcome = "";
$sponsor = ucfirst($_SESSION['sponsor']);

pdo_query("1","update provider set lasttip=1 where providerid=$_SESSION[pid]");

$result = pdo_query("1","select welcome from sponsor where sponsor='$_SESSION[sponsor]' ");
if($row = pdo_fetch($result)){
    $message = $row['welcome'];
    //$message = str_replace("\\n","<br>",$message);
    //$message = str_replace("\\r","<br>",$message);
}
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div class='pagetitle2a' style='padding:10px;color:white'>
    Message
    </div>
    <div class='abouttext pagetitle2a' style='text-align:center;backround-color:<?=$global_backgroundcolor?>'>
        <center>
            <br>
            <div class='tilebutton' style='color:<?=$global_activetextcolor?>;cursor:pointer'>Exit Tour</div>
            <br>
        <div class='circular3' style=';overflow:hidden'>
            <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
        </div>
        </center>
        <div class='pagetitle3' style='max-width:500px;margin:auto'>
            <?=$message?>
        </div>
        <br>
        <span class='pagetitle'  style='color:gray'>Tour</span>
        <img class='icon25 about tapped'     src='../img/Arrow-Right-in-Circle_120px.png' style='margin-left:10px;margin-right:20px' >
        <br><br>
        
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
    </div>
</div>    

       
                   

