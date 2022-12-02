<?php
session_start();
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type: nosniff');
//header('X-XSS-Protection: 1; mode=block');
require_once("config-pdo.php");
require_once("htmlhead.inc.php");
require_once("password.inc.php");

require_once("internationalization.php");
require('colorscheme.php');
require_once("accountcheck.inc.php");
require_once("startup.inc.php");

$connecterror = "<div class='tilebutton restarthome' style='cursor:pointer;padding:20px;color:black;background:white'><b>Internet Connectivity Issue. Please try again.</b></div>";
$timeouterror = "<div class='tilebutton restarthome' style='cursor:pointer;padding:20px;color:black;background:white'><b>Session Timeout. Please tap to restart.</b></div>";

$uniqid = uniqid();

$tester = 'N';

if(
    $_SESSION['pid'] == $admintestaccount ) 
        //|| $_SESSION['pid'] == 690032821)
{
    $tester ='Y';
}


//Auto Launch Chat
$initmodule = "";
if($_SESSION['init']!=''){
    //$initmodule = substr(base64_decode($_SESSION['init'],8));
    //$initmodule = substr($_SESSION['init'],8);
    $initmodule = substr($_SESSION['init'],6);
    unset($_SESSION['init']);
}

$loginuser = "$_SESSION[providername]";
$companyname = @$_SESSION['companyname'];
if($_SESSION['loginid']!='admin'){

    $loginuser .= "<br>$_SESSION[staff]";
    $loginuser .= "<br>$_SESSION[handle]";
} else {
    
    $loginuser .= "<br>$_SESSION[handle]";
    if($companyname!=''){
        //$loginuser .= "<br>$companyname";
    }
}

$enterprisediv = "";
if($_SESSION['enterprise']=='Y'){
    $enterprisediv = "<div class='smalltext' style='background-color:gold;color:black;padding:5px'>Enterprise</div>";
}
    $beacon = "";
    if($_SESSION['daysactive']<2){
        $beacon = "
        <div class='beaconcontainer' style='cursor:pointer;z-index:100;position:absolute;'>
            <div class='beacon' style='color:$global_activetextcolor;border-color:$global_activetextcolor'></div>
        </div>
        ";
    }


?>



<title></title>
</head>
<body class='iosx-visible' style='z-index:3;max-height:100%;overflow-x:hidden;overflow-y:auto;background-color:#d9d9d9;position:absolute;left:0px;top:0px;width:100%;padding:0;'>
<div class='iosx-visible' id='fixedbackground'  style='
    background-color:<?=$global_web_background?>;
    border-image-width:0px;
    display:block;
    position:fixed;
    left:0px;
    top:0px;
    width:100%;
    height:100%;
    padding:0;
    z-index:1;
    '>
    <?=$display_background_image?>
</div>
<div class='iosx-visible' id='animationlayer' style='
    background-color:transparent;
    border-image-width:0px;
    display:block;
    position:fixed;
    left:0px;
    top:0px;
    width:100%;
    height:100%;
    margin:0;
    padding:0;
    z-index:2;
    color:<?=$global_activetextcolor?>;
    text-align:right;
    '>
    <div class='hearts' style='margin-left:20%;height:100%;width:300px;'></div>
</div>
<div class='consolebody' style='z-index:10;border:0;position:absolute;top:0;overflow-x:hidden;overflow-y:hidden;left:0;background-color:transparent;padding:0;margin:0;width:100%;max-width:100%;'>

    <div class='gridnoborder' style='display:none' id="loading-div-background">
        <div id="loading-div" class="ui-corner-all" >
          <img style="height:100px;margin:10px;" src="../img/loading-blue.gif" alt="Connecting.."/>
          <h2 style="color:gray;font-weight:normal;">Please wait....</h2>
         </div>
    </div>  
    <div class='notificationspopup' 
         style="display:none;margin:auto;
         background-color:white;color:black;
         ">
    </div>
    <table class='gridnoborder' style='padding:0;width:100%'>
        <tr class='gridnoborder' style='width:100%;padding:0;margin:0'>
            <td class='gridnoborder pagetitle2 sidemenuarea'  style='display:none;height:100%;background-color:<?=$global_menu_color?>;padding:10px;min-width:150px;color:<?=$global_textcolor?>;vertical-align:top;overflow-y:visible'>
            </td>
            <td class='gridnoborder'  style='width:100%;vertical-align:top;overflow-x:hidden;overflow-y:hidden'>
                <div id="banner" class="bannerflush bannerheight" 
                   style="position:relative;top:0;left:0;padding:0;overflow:hidden;
                   width:100%;margin:0;text-align:left;background-color:transparent">
                    <span class='formobile'>
                         <?=$blink?>
                        <img class='<?=$_SESSION['profileaction']?>  mainbutton tooltip bannerheight' alt='Change your profile and data' 
                           data-roomid='<?=$_SESSION['profileroomid']?>' data-providerid='<?=$providerid?>' data-caller='none'
                           title='Change your profile photo and data <?=$_SESSION['superadmin']?>/<?=$global_banner_color?>' 
                           data-mode='test'
                           style='float:right;cursor:pointer;width:auto;padding:0;margin:0;max-width:15%' src='<?=$_SESSION['avatarurl']?>' />
                    </span>
                    <span class='nonmobile'>
                         <div class="smalltext bannerheight" 
                              style='display:inline-block;color:<?=$global_menu_text_color?>;padding:0;
                              margin:0px;'>
                             &nbsp;&nbsp;<?=$loginuser?>
                         </div>
                    </span>
                    <div class='closesidemenu tapped menubutton formobile' 
                        style='display:inline;float:right;cursor:pointer;padding-right:20px;padding-left:5px;padding-bottom:0px;'>
                        <img class='tip1 icon30' src='../img/Arrow-Left-in-Circle_120px.png' 
                             style=";top:6px;;cursor:pointer;opacity:0.0"  title="Main Menu" />
                    </div>
                    <div class='opensidemenu menubutton formobile' 
                        style='display:inline;float:left;cursor:pointer;padding-right:20px;padding-left:20px;padding-bottom:0px;'>
                        <?=$beacon?>
                        <img class='tip1 icon20' src='<?=$iconsource_braxmenu?>' 
                             style=";top:10px;cursor:pointer;"  title="Main Menu" />
                    </div>
                    <div class='camera formobile tapped menubutton' 
                         style='display:inline;float:left;cursor:pointer;padding-right:5px;padding-left:5px;padding-bottom:0px;margin-right:10px' data-chatid=''>
                        <img class='icon20' src='<?=$iconsource_braxcamera?>' 
                             style=";top:11px;;cursor:pointer;" title="Camera" />
                    </div>
                </div>
                <div class='tileview gridnoborder' style='display:none;background-color:transparent;overflow-x:hidden;width:100%'>
                </div>


                <div class='mainview gridnoborder' 
                     style="max-height:100%;width:100%;overflow-x:hidden;overflow-y:scroll;position:relative;display:none;background-color:transparent;
                     padding:0;margin:0;z-index:10">
                    <table id='mainviewtop gridnoborder' class="panelhost mainfont" style="background-color:transparent;width:100%;height:auto;margin:0;padding:0px;border-spacing:0;border:0">
                        <tr class='gridnoborder' style="padding:0;margin:0;background-color:transparent;;width:100%">

                                <td class="sidebararea nonmobile gridnoborder noselect" style="color:white;background-color:<?=$global_menu_color?>;padding:0;width:250px;max-width:300px;margin:0px;max-height:100%;overflow:auto" valign="top">
                                    <div class='sidebar mainfont'  style='background-color:<?=$global_menu_color?>;width:250px;padding-top:10px;text-align:center'>
                                        <center>
                                            <div class="" style="display:inline-block;margin:auto;position:relative">
                                            </div>
                                            <img class='<?=$_SESSION['profileaction']?> mainbutton circular3 gridnoborder' alt='Change your profile and data' 
                                               title='Change your profile photo and data'
                                               data-roomid='<?=$_SESSION['profileroomid']?>'
                                               data-providerid='<?=$providerid?>' data-caller='none'
                                               data-profile='Y'
                                               style='cursor:pointer;margin-left:10px;display:block' src='<?=$_SESSION['avatarurl']?>' />
                                            <div class='smalltext2' style='padding-left:10px;padding-top:3px;color:<?=$global_menu_text_color?>'><?=$loginuser?></div>
                                        </center>
                                        <div class='sidebaralerts noselect' style='text-align:left;background-color:<?=$global_menu_color?>'></div>
                                    </div>
                                </td>

                                <td class='maincontentarea gridnoborder' style="background-color:transparent;padding:0;margin:0;width:100%;border:0;border-spacing:0" valign="top">
                                    <div class="commandzone" style="background-color:whitesmoke;color:black;padding-top:0px;padding-bottom:5px;margin:0;width:100%" >
                                            <div class="hidemessagearea" style='display:inline;background-color:whitesmoke;color:black'>
                                                &nbsp;<div class="hidemessage tapped"  style="display:inline;cursor:pointer" id="hidemessage" name="hidemessage">
                                                <img class='icon25' src='../img/arrow-stem-circle-left-128.png' style='' >
                                                   &nbsp;Close&nbsp;&nbsp;
                                                </div>
                                                &nbsp;<div class="actionarea" style="display:inline;"></div>    
                                                <div class='chatactionarea' style="display:inline;">
                                                </div>
                                                <br><br>
                                            </div>

                                    </div>
                                    <div id="firsttime" class="firsttime" style='background-color:<?=$global_background?>;width:60%;'  >
                                    </div>
                                    <div id="sharearea" class="sharearea" style="display:none" >
                                    </div>
                                    <div class='chatarea gridnoborder hearts' style="overflow-x:hidden;overflow-y:hidden;position:relative;padding:0;margin:0;background-color:transparent;width:100%;max-height:100%">
                                        <div class='mobilenoteareadiv gridnoborder' style='border-color:black;display:none;color:white;background-color:black;overflow:hidden;width:100%;height:250px'></div>
                                        <div class='chatheading gridnoborder smalltext' style='color:black;background-color:transparent;overflow:hidden;width:100%;'></div>
                                        <div id="chatwindow" name="chatwindow" class="chatwindow gridnoborder" style="overflow-x:hidden;overflow-y:auto;background-color:transparent;color:black;padding:0;margin:0;width:100%" >
                                            <img style="height:100px;margin:10px;" src="../img/loading-blue.gif" alt="Connecting.."/>
                                        </div>
                                        <div class='chatentry gridnoborder smalltext' style='position:absolute;bottom:0;height:0;left:0;color:black;background-color:<?=$global_bottombar_color?>;overflow:hidden;width:100%;'>
                                        </div>
                                    </div>
                                    <div class='settingsview' data-colorscheme='<?=$_SESSION['colorscheme']?>' data-sponsorcolorscheme='<?=$_SESSION['sponsorcolorscheme']?>'

                                         style="display:none;overflow:visible;background-color:transparent;text-align:left;color:<?=$global_textcolor?>;max-width:100%;">
                                        <!--
                                        <div class='pagetitle2a' style='background-color:<?=$global_titlebar_color?>;color:white;padding-left:20px;padding-right:20px;padding-top:0px;padding-bottom:3px'>
                                            !--
                                            <img class='icon20 tilebutton' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                                                style='' />
                                            &nbsp;
                                            --
                                            <span style='opacity:.5'>
                                            <?=$icon_braxsettings2?>
                                            </span>
                                            <?=ucfirst(strtolower($menu_settings));?>
                                        </div>
                                        -->
                                        <div 
                                         style="background-color:transparent;margin:auto;text-align:center;width:90%;min-width:70%;vertical-align:top">
                                                    <?=$settingsmenu?>
                                        </div>
                                    </div>                    
                                    <div id="popupwindow" name="popupwindow" class="popupwindow" 
                                         style="padding:0px;margin:0px;color:<?=$global_textcolor?>;background-color:<?=$global_background?>;width:100%;" ></div>
                                    <div id="socialwindow" name="socialwindow" class="socialwindow" 
                                         style="padding:0px;margin:0px;color:<?=$global_textcolor?>;border:0;border-spacing:0;background-color:transparent;width:100%;overflow-y:auto" ></div>
                                    <div id="shareitwindow" name="shareitwindow" class="shareitwindow" 
                                         style="padding:0px;margin:0px;color:<?=$global_textcolor?>;background-color:transparent;width:100%;" ></div>
                                    <div id="roomwindow" name="roomwindow" class="roomsview roomwindow feedpanel gridnoborder" style="
                                        padding:0px;margin:0px;color:<?=$global_textcolor?>;width:100%;max-width:100%;
                                        background-color:transparent;overflow-x:hidden;
                                         " >
                                        <div id="roominnerwindow" name="roominnerwindow" class="roominnerwindow gridnoborder" style="padding:0px;margin:0px;color:<?=$global_textcolor?>;background-color:transparent;width:100%;overflow-x:hidden;overflow-y:visible" >
                                            <img style="height:100px;margin:0px;" src="../img/loading-blue.gif" alt="Connecting.."/>
                                        </div>

                                    </div>
                                    <div id="prestart" name="prestart" class="prestart" 
                                         style="padding:0px;margin:0px;color:black;background-color:white;width:100%;" >
                                    </div>
                                    <div id="lastloaded" style="display:none"></div>



                                    <iframe id="showmessage1" name="showmessage1" class="showmessage gridstdborder mainfont" width='100%'></iframe>
                                    <iframe id="functioniframe" name="functioniframe" class="functioniframe gridstdborder mainfont" width="100%"></iframe>
                                    <iframe id="streamiframe" name="streamiframe" class="streamiframe gridstdborder mainfont" width="100%"></iframe>
                                    <textarea id="currentmessage" class="currentmessage" style="display:none" ></textarea>

                                </td>
                                <td class="notearea gridnoborder noselect" style="padding:0;margin:0px;overflow:hidden;background-color:black" valign="top">
                                    <div class='noteareadiv' style='vertical-align:top;overflow:hidden;width:100%;height:88%'></div>
                                    <div class='noteareadiv2' style='background-color:<?=$global_bottombar_color?>;vertical-align:top;color:white;overflow:hidden;width:100%;height:12%'></div>
                                </td>

                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
                
    <span class='nonmobile'>
        <div id="status" class="status" style='display:none;height:0px;background-color:transparent' ></div>
    </span>
    
    

    
    
    <span class="nonmobile">
        <span class='traffic smalltext' style='display:none'></span>
        
        <span class='sizingheight smalltext' style=''></span>
        <div class='alertrow alertcolumn' style='display:inline;padding:0;margin:0;color:black;background-color:transparent;height:11px'></div>
        <div id="alert" class="nonmobile" style='font-size:12px;color:black;background-color:transparent;display:inline;padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:0px;margin:0px;height:10px'></div>
    </span>
    <div id='timezone' style='display:none'></div>
    <span class='sizingplan' style='display:none'></span>
    
    <span class="nonmobile">
    <button class="browseronly js-push-button gridnoborder" disabled style='cursor:pointer;display:none;background-color:transparent;color:transparent'> 
      Enable Push Notifications 
    </button>          
    </span>
    
</div>
<div class='hidespace' style='overflow:hidden;display:inline;height:0;background-color:transparent'>
    
    
    <div id=trigger_about class='about0 mainbutton' style='display:none'></div>
    <div id=trigger_findpeople class='meetuplist mainbutton' style='display:none'></div>
    <div id=trigger_pin class='pinentry mainbutton' style='display:none'></div>
    <div id=trigger_tilebutton class='tilebutton menubutton ' style='display:none'></div>
    <div id=trigger_chgpassword class='settingsaction menubutton chgpasswordbutton' style='display:none'></div>
    <div id=trigger_settings class='settingsbutton mainbutton' style='display:none'></div>
    <div id=trigger_notificationpopup class='notificationpopup' style='display:none'></div>
    <div id=trigger_notifysubscribe class='notifysubscribe1 mainbutton' style='display:none'></div>
    <div id=trigger_room class='mainbutton feedload' data-roomid='' data-mode='' data-caller='' style='display:none'></div>
    <div id=trigger_userstore class='mainbutton userstore' data-roomid='' data-mode='' data-caller='' style='display:none'></div>
    <div id=trigger_roomselect class='mainbutton roomselect' data-roomid='' data-mode='' data-caller='' style='display:none'></div>
    <div id=trigger_roomselectlive class='mainbutton roomselect' data-roomid='' data-mode='S' style='display:none'></div>
    <div id=trigger_discoverroom class='mainbutton roomdiscover' data-roomid='' data-mode='' style='display:none'></div>
    <div id=trigger_chat class='mainbutton setchatsession' data-chatid='' data-keyhash=''  style='display:none'></div>
    <div id=trigger_restorechat class='mainbutton restorechatsession' data-chatid='' data-keyhash=''  style='display:none'></div>
    <div id=trigger_selectchat class='mainbutton selectchatlist' data-chatid='' data-mode='CHAT'  style='display:none'></div>
    <div id=trigger_selectlive class='mainbutton selectchatlist' data-chatid='' data-mode='LIVE'  style='display:none'></div>
    <div id=trigger_photo class='mainbutton photolibrary' data-save='' data-deletefilename='' data-filename='' data-rotate='' data-page='' data-album=''  style='display:none'></div>
    <div id=trigger_file class='mainbutton doclib'   style='display:none'></div>
    <div id=trigger_notification class='notification'   accesskey="" data-mode='' style='display:none'></div>
    <div id=trigger_credentialget class='mainbutton credentialget' data-datanew='Y' style='display:none'></div>
    <div id=trigger_case class='mainbutton casefiles' data-caseid=''  style='display:none'></div>
    <div id=trigger_audiopanel_desktop class='audiopanel' data-mode='' data-chatid='' style='display:none'></div>
    <div id=trigger_audiopanel_mobile class='audiopanel' data-mode='M' data-chatid='' style='display:none'></div>
    <div id=trigger_audiopanel_mobile2 class='audiopanel' data-mode='M2' data-chatid=''  style='display:none'></div>
    <div id=trigger_termsofusedisplay class='termsofusedisplay'  style='display:none'></div>
    <div id=trigger_photolibshare class='mainbutton photolibshare' data-userid=''  style='display:none'></div>
    <div id=trigger_members class='mainbutton togglememberson' data-userid=''  style='display:none'></div>
    <div id=trigger_uploadavatar class='mainbutton uploadavatar'   style='display:none'></div>
    <div id=trigger_iotview class='mainbutton homeiot'   style='display:none'></div>
    <div id=trigger_restart class='mainbutton restart'   style='display:none'></div>
    
    
    <div class='roommanagediv' style='display:none'><?=$roommanagemenu?></div>
    <iframe class="iframe_response nonmobile" name="statusframe" height='0' width='0' style="display:none"></iframe>
    <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" readonly=readonly size="10"  autocomplete="false" value='<?=$_SESSION['pid']?>' >
    <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="logind" readonly=readonly  value='admin' >
    <INPUT id="password" TYPE="hidden" name="password" >

    <div id='imapfoldermenu' style='position:absolute;top:0;left:1000;height:0;width:0;font-size:1px;'>
        <ul id="imapmovemenu" >
        </ul>
    </div>
    <div id='photoalbummenudiv' style='position:absolute;top:0;left:1000;overflow:hidden;height:0;width:0;font-size:1px;color:white;'>
        <ul id="menu1">
        </ul>
    </div>
    <div id='dialog' style='display:none;'>
        <p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>    
    </div>
    
    <form id="newnotearea" class="newnotearea" action="https://whatthezuck.net" method=POST target="noteareaiframe">
    </form>
    
    <form class="newemail" action="newemail.php" method=POST target="_blank">
    <INPUT id='pid1' TYPE='hidden' name='pid' value='<?=$_SESSION['pid']?>'>
    <INPUT id='loginid1' TYPE='hidden' name='loginid' value='admin'>
    <INPUT class="imapno" TYPE='hidden' name='imap'>
    <INPUT class="imaporiginaltext" TYPE='hidden' name='originaltext'>
    <INPUT id="sharetext" TYPE='hidden' name='sharetext'>
    <INPUT TYPE='hidden' NAME='timestamp' value='' >
    </form>


    <FORM id="profile" name='profile'  ACTION="profile.php" METHOD="POST" target=functioniframe >
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
     <INPUT TYPE="hidden" NAME="returnurl" value='<a href=login.php>Login</a>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>

    <FORM id="externaliframe" name='externaliframe'  ACTION="" METHOD="POST" target=functioniframe >
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>

    <FORM id="chgpassword" name='chgpassword'  ACTION="chgpassword.php" METHOD="POST" target=functioniframe >
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
     <INPUT TYPE="hidden" NAME="returnurl" value='<a href=login.php>Login</a>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>


    <form class='avatarform' id='avatarform' action='avatarform.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
     <INPUT TYPE="hidden" id='avatar_devicetype' NAME="devicetype" value='' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>
    
    <form class='techsupportform' id='techsupportform' action='techsupport.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>
    

    <form class='uploadphotoform' id='uploadphotoform' action='upload/upload-photo.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
    <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>

    <form class='artistuploadform' id='artistuploadform' action='photoupload_a.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
    <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>


    <form class='uploadfileform' id='uploadfileform' action='fileupload/upload-file.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
     <INPUT TYPE="hidden" NAME="folder" value=LastFolder >
     <INPUT TYPE="hidden" NAME="otherid" id="uploadfile_otherid" value='' >
     <INPUT TYPE="hidden" NAME="chatid" id="uploadfile_chatid" value='' >
     <INPUT TYPE="hidden" NAME="roomid" id="uploadfile_roomid" value='' >
     <INPUT TYPE="hidden" NAME="passkey64" id="uploadfile_passkey64" value='' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>

    <form class='uploadcsvform' id='uploadcsvform' action='csvupload.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>
    
    <form class='uploadsignupcsvform' id='uploadsignupcsvform' action='csvsignup.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>
    
    <form class='uploadtextcsvform' id='uploadtextcsvform' action='csvtext.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>
    

    <form class='textphotoform' id='textphotoform' action='textphoto.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
    <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>

    <form class='collectionform' id='collectionform' action='collection.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
    <INPUT TYPE="hidden" NAME="loginid" value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>


    <FORM id='phpinfoform' name='phpinfo'  ACTION='phpinfo.php' METHOD='POST' target=_blank  >
    <INPUT TYPE='hidden' NAME='pid' value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    

    <FORM id='stafflist' name='staff'  ACTION='stafflist.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid' value='<?=$_SESSION['pid']?>' >
    </FORM>
    
    <FORM id='signupform' name='signupform'  ACTION='signup.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid' value='<?=$_SESSION['pid']?>' >
    </FORM>
    
    
    <FORM id='slideshowform' name='slideshow'  ACTION='slideshow.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid' id='slideshow_pid' value='' >
    <INPUT TYPE='hidden' NAME='album' id='slideshow_album' value='All' >
    <INPUT TYPE='hidden' NAME='loginid' value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='photoviewform' name='photoview'  ACTION='photoview.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid' id='slideshow_pid' value='' >
    <INPUT TYPE='hidden' NAME='filename' id='photoview_filename' value='' >
    <INPUT TYPE='hidden' NAME='loginid' value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    
    
    <FORM id='videoviewform' name='videoview'  ACTION='videoview.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid' id='slideshow_pid' value='' >
    <INPUT TYPE='hidden' NAME='url' id='videoview_url' value='' >
    <INPUT TYPE='hidden' NAME='loginid' value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='videoiotform' name='videoiot'  ACTION='videoiot.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='url' id='videoiot_url' value='' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    

    <FORM id='wrapform' name='wrapview'  ACTION='wrap.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='' >
    <INPUT TYPE='hidden' NAME='url' id='wrap_url' value='' >
    <INPUT TYPE='hidden' NAME='loginid' value='admin' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='statsform' name='stats'  ACTION='stats.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>

    <FORM id='userstatsform' name='userstats'  ACTION='statsuser.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    
    <FORM id='superstatsform' name='stats'  ACTION='superstats.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='testzoneform' name='testzone'  ACTION='testzone.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>

    <FORM id='report1form' name='stats'  ACTION='report1.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='tokenreportform' name='tokenstats'  ACTION='tokenreport.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='tokenstoreform' name='tokenstore'  ACTION='tokenstore.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    
    <FORM id='restream' name='age'  ACTION='restream.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='credentialget' name='credential'  ACTION='credentialget.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT TYPE='hidden' NAME='datanew'  value='Y' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    
    <FORM id='privacyform' name='privacy'  ACTION='privacy.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    
    <FORM id='privacytipform' name='privacytip'  ACTION='privacytip.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>

    <FORM id='rssform' name='articleid'  ACTION='rssview.php' METHOD='POST' target='functioniframe'  >
    <INPUT TYPE='hidden' NAME='pid'  value='<?=$_SESSION['pid']?>' >
    <INPUT id='rssform_articleid' TYPE='hidden' NAME='articleid'  value='' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </FORM>
    

    <FORM class='fbform' id='fbform' name='fbform'  ACTION='' METHOD='POST' target='functioniframe'  >
    </FORM>

    <FORM class='profileartistform' id='profileartistform' name='profileartistform'  ACTION='profileartist.php' METHOD='POST' target='functioniframe'  >
    </FORM>
    
    <form class='grouptextform' id='grouptextform' action='grouptext.php' method=POST target='functioniframe'>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$_SESSION['pid']?>' >
    <INPUT class='timestamp' TYPE='hidden' NAME='timestamp' value='' >
    </form>
    
    <FORM id='audiostream' name='audiostream'  ACTION='audiostream.php' METHOD='POST' target='streamiframe'  >
    <INPUT TYPE='hidden' NAME='pid' value='<?=$_SESSION['pid']?>' >
    <INPUT id='audiostream_streamid' TYPE='hidden' NAME='streamid' value='' >
    <INPUT id='audiostream_chatid' TYPE='hidden' NAME='chatid' value='' >
    </FORM>
    
    
</div>
    
</body>
<script>
   
var hostedmode = false;
var hostedroomid = 0;
var hostedroomname = "";

var apn = "<?=$_SESSION['apn']?>";
var gcm = "<?=$_SESSION['gcm']?>";
var ping = new Audio("<?=$_SESSION['ping']?>");
var ping2 = new Audio("<?=$_SESSION['ping2']?>");
var ImapCount = 0;
var NewUser = "<?=$_SESSION['newuser']?>";
var AccountStatus = '<?=$_SESSION['accountstatus']?>';
var TimeZoneOffset = <?=$_SESSION['timezoneoffset']?>;
var TimeoutSeconds = <?=$_SESSION['timeout_seconds']?>;
var MenuStyle = '<?=$_SESSION['menustyle']?>';
var SessionMobile = '<?=$_SESSION['mobile']?>';
var UploadToday = "Upload-<?=$today?>";
var source = '<?=$_SESSION['source']?>';
var LastFunc = "<?=$lastfunc->lastfunc?>";
var LastFuncParm1 = "<?=$lastfunc->parm1?>";
var defaultRoomid = "<?=$lastroomid?>";
var rootserver = "<?=$rootserver?>/<?=$installfolder?>/";
var rootserver1 = "<?=$rootserver?>/";
var loginid = "<?=$_SESSION['loginid']?>";
var appname = "<?=$appname?>";
var enterprise = "<?=$_SESSION['enterprise']?>";
var inforequest = "<?=$_SESSION['inforequest']?>";
var needsms = "<?=$_SESSION['needsms']?>";
var tester = "<?=$tester?>";
var invitesource = "<?=$_SESSION['invitesource']?>";
var termsofuse = "<?=$_SESSION['termsofuse']?>";
var havecontacts = "<?=$_SESSION['contacts']?>";
var onetimeflag = "<?=$_SESSION['onetimeflag']?>";
var chgpasswordflag = "<?=$_SESSION['chgpassword']?>";
var sponsor = "<?=$_SESSION['sponsor']?>";
var initmodule = "<?=$initmodule?>";
var rclickenable = "Y";
var pin = "<?=$_SESSION['pin'];?>";
var pinlock = "<?=$_SESSION['pinlock'];?>";
var livesupport = "<?=$_SESSION['livesupport']?>";
var mobileversion = "<?=$_SESSION['version']?>";
var hardenter = "<?=$_SESSION['hardenter']?>";
var startupphp = "<?=$startupphp?>";
var ConnectError = "<?=$connecterror?>";
var TimeoutError = "<?=$timeouterror?>";
try {
        localStorage.mobilecommand = ''; 
        localStorage.mobilenotification = ''; 
        if(mobileversion!=='' && typeof mobileversion!=='undefined'){
            localStorage.mobileversion = mobileversion;
            localStorage.apn = apn;
            localStorage.gcm = gcm;
        } else {
            mobileversion = '';
            localStorage.mobileversion = mobileversion;
        }
} catch(err) {}
</script>
<?php



if( $tester == 'Y'){
?>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console0.js?i=<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console.js?i=<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/notifyweb.js?<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console_extra.js?<?=$uniqid?>'></script>

<?php

} else {
?>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console0.js?i=<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console.js?i=<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/notifyweb.js?<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console_extra.js?<?=$uniqid?>'></script>

<?php
/*
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console0-obf.js?i=<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/console-obf.js?i=<?=$uniqid?>'></script>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/notifywebtest.js'></script>
*/
} 
?>
</html>
