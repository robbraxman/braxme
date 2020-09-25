<?php
session_start();
require("config-pdo.php");
require("colorscheme.php");
require_once 'authenticator/GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $ga->createSecret();

$auth_hash = '';
$result = pdo_query("1","
    select auth_hash from staff 
    where loginid = '$_SESSION[loginid]' and providerid = $_SESSION[pid] ",null);
if( $row = pdo_fetch($result)){
    $auth_hash = $row['auth_hash'];
}

/*
pdo_query("1","
    update staff set auth_hash='$secret' 
    where loginid = '$_SESSION[loginid]' and providerid = $_SESSION[pid] ");
*/  
 
$handle = substr($_SESSION['handle'],1);
$qrCodeUrl = $ga->getQRCodeGoogleUrl("Brax.Me-$handle", $secret);

echo "      
    <div class='gridnoborder' 
        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
        <img class='icon20 settingsbutton mainbutton' Title='Back to Settings'  src='../img/Arrow-Left-in-Circle-White_120px.png' 
            style='' />
        &nbsp;
        <span style='opacity:.5'>
        $icon_braxroom2
        </span>    
        <span class='pagetitle2a' style='color:white'>Set Up an Authenticator App for Login</span> 
    </div>
   ";

echo "
    <div class='pagetitle3' style='margin:auto;max-width:600px;padding:20px;color:$global_textcolor' >
    This provides your account with an alternate means of logging in in case you lose your password.
    Use Google Authenticator, Authy or a compatible TOTP App. You use the Authenticator app to give
    you a One Time Password.
    </div>
    <div style='margin:auto;width:200px' >
        <br>
        <b>Scan QR code</b><br>
    <iframe src='$qrCodeUrl' height=200 width=200 frameborder=0 >
    </iframe>
    <br><br>
    <b>Or enter secret manually</b>
    <div id='chgtotpsecret'>$secret</div>
    <br>";
if($auth_hash!=''){
    echo "<div class='tipbubble' style='color:$global_activetextcolor'>WARNING! You have already registered an Authenticator App. This will override prior settings</div><br>";
}
echo "    
    Enter Verification Code<br>
    <input id='chgtotpcode' class='dataentry totp' type=text maxlen=6 style='width:100px' />
    <img class='icon30 chgtotpvalidate mainbutton' Title='Verify Authenticator'  src='../img/Arrow-Right-in-Circle-White_120px.png' 
        style='' />
    </div>
    <br>
    <br>
    ";
if($auth_hash!=''){
echo "    
    <div class='pagetitle3 chgtotpdelete' style='cursor:pointer;width:200px;margin:auto;color:$global_activetextcolor'>Delete Authenticator</div>
    ";
}
