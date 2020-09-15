<?php
session_start();
ob_start();
require_once("config.php");

$providerid = tvalidator("PURIFY",$_POST['providerid']);
$version = tvalidator("PURIFY",$_POST['version']);
//$_SESSION['version']=$version;
if($version == ''){
    $version = @$_SESSION['version'];
}
$apn = tvalidator("PURIFY",$_POST['apn']);
$gcm = tvalidator("PURIFY",$_POST['gcm']);

do_mysqli_query("1","update provider set pinlock = 'Y' where providerid = $providerid");
$_SESSION['pinlock']='Y';

$start = $rootserver."/".$startupphp."?s=$_SESSION[source]&v=$version&apn=$apn&gcm=$gcm";
if(!isset($_SESSION['pid'])){
    header("location: $start");
    //exit();
}


?>
<center>
<img class='restart icon50' src='../img/logo-b2.png' style='margin:auto'/>
<br>
<div class='pintextview' style='color:<?=$global_textcolor?>;padding:5px;font-size:50px;height:50px;margin:auto;text-align:center'><span style='font-size:15px'>Restart</span></div>
</center>
<div class='keypadcontainer pagetitle2 noselect' style='max-width:300px;margin:auto;padding-top:20px;'>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='1' >
        <br>
        1
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='2' >
        <br>
        2
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='3' >
        <br>
        3
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='4' >
        <br>
        4
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='5' >
        <br>
        5
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='6' >
        <br>
        6
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='7' >
        <br>
        7
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='8' >
        <br>
        8
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='9' >
        <br>
        9
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='x' >
        <br>
        <img class='icon15' src='../img/delete-circle-128.png' />
        
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='0' >
        <br>
        0
    </div>
    <div class='keypadbutton icon50 gridstdborder rounded tapped smalltext2' 
        style='background-color:whitesmoke;float:left;text-align:center;vertical-align:center;margin:10px;height:70px;width:70px' 
        data-value='l' >
        <br><br><br>
        Logout
    </div>
</div>