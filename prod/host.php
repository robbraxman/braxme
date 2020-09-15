<?php
session_start();

require_once("config.php");
require_once("crypt.inc.php");
require_once("aws.php");
require_once("room.inc.php");
require_once("lib_autolink.php");
require_once("store.inc.php");

$language = @mysql_safe_string($_GET['lang']);
$version = @mysql_safe_string($_GET['version']);
$providerid = @mysql_safe_string($_GET['p']);
if($providerid == '' && isset($_SESSION['pid'])){
    $providerid = $_SESSION['pid'];
}
$trackerid = '';
if($providerid!=''){
    $result = do_mysqli_query("1","
            select providername, trackerid from provider where providerid = $providerid and active='Y'
            ");
    if($row = do_mysqli_fetch("1",$result)){

        $customername = $row['providername'];
        $trackerid = $row['trackerid'];
    } else {
        echo "<h1 class='pagetitle' style='font-family:helvetica'>$appname Invalid User</h1>";
        exit();
    }
}


if($language!=''){
    $_SESSION['language'] = $language;
} else {
    $_SESSION['language'] ='english';
}
include("internationalization.php");


$timezoneoffset = -7;
$_SESSION['innerwidth']=320;
$hashtag = @mysql_safe_string($_GET['h']);
$folderid = @mysql_safe_string($_GET['f']);
$trackerid = @mysql_safe_string($_GET['tracker']);

$roomid = '0';
$webcolorscheme = 'std';
$result = do_mysqli_query("1","
        select roomhandle.roomid, roominfo.webcolorscheme, statusroom.owner
        from roomhandle
        left join roominfo on roomhandle.roomid = roominfo.roomid
        left join statusroom on roomhandle.roomid = statusroom.roomid and statusroom.owner = statusroom.providerid
        where roomhandle.handle='#$hashtag' 
        and roominfo.external='Y'
        and statusroom.owner is not null
        and statusroom.owner in 
        (select providerid from provider where provider.providerid = statusroom.owner and provider.active='Y' and provider.enterprise='Y' )
        ");
if($row = do_mysqli_fetch("1",$result)){

    $roomid = $row['roomid'];
    $webcolorscheme = $row['webcolorscheme'];
} else {
    echo "<h1 class='pagetitle' style='font-family:helvetica'>$appname $enterpriseapp Website Not Found</h1>";
    exit();
}
require("colorscheme.php");

/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
//Customizations

$webheader = "";
$result = do_mysqli_query("1","
        select filename, fileencoding, filesize from filelib where filename in (
        select filename from roomfiles where roomid = $roomid
        and title='webheader.txt') 
        ");
if($row = do_mysqli_fetch("1",$result)){
    $filesize = $row['filesize'];
    $webheader =  substr(getAWSObjectStreamEncryptedContent( $row['filename'], $row['fileencoding'], 0xFFFFF, $filesize ),0, $filesize);
}

$webnavbar = "";
$result = do_mysqli_query("1","
        select filename, fileencoding, filesize from filelib where filename in (
        select filename from roomfiles where roomid = $roomid
        and title='webnavbar.txt') 
        ");
if($row = do_mysqli_fetch("1",$result)){
    $filesize = $row['filesize'];
    $webnavbar =  substr(getAWSObjectStreamEncryptedContent( $row['filename'], $row['fileencoding'], 0xFFFFF, $filesize ),0, $filesize);
}



$webbody = "";
$result = do_mysqli_query("1","
        select filename, fileencoding, filesize from filelib where filename in (
        select filename from roomfiles where roomid = $roomid
        and title='webbody.txt') 
        ");
if($row = do_mysqli_fetch("1",$result)){
    $filesize = $row['filesize'];
    $webbody =  substr(getAWSObjectStreamEncryptedContent( $row['filename'], $row['fileencoding'], 0xFFFFF, $filesize ),0, $filesize);
}

$webfolders = "";
$result = do_mysqli_query("1","

        select distinct foldername, folderid from roomfilefolders where roomid = $roomid
            order by foldername asc
        ");
while($row = do_mysqli_fetch("1",$result)){
    $webfolders .= "
            <a href='$rootserver/prod/host.php?f=$row[folderid]&h=$hashtag' 
                    style='text-decoration:none;color:$global_activetextcolor_reverse'>
                <div class='pagetitle2a' style='wbite-space:nowrap;padding:15px;cursor:pointer;display:inline-block;padding:10px;margin-right:20px;margin-left:20px;color:$global_activetextcolor_reverse' data-roomid='$roomid' data-folderid='$row[folderid]'>$row[foldername]</div>
            </a>";   
}
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/


$uniqid = uniqid();


$sizing = RoomSizing();
$memberinfo = MemberCheck( 0, 0);
$roominfo = RoomInfo( 0, $roomid, $sizing->mainwidth, 0, $memberinfo);
$nonmobilehtml = RoomNonMobile( $roominfo, $memberinfo, $webheader, $providerid, $hashtag);
$mobilehtml = RoomMobile( $roominfo, $memberinfo, $webheader, $providerid, $hashtag);

$roomtitle = htmlentities($roominfo->room, ENT_COMPAT);
$roomdesc =  htmlentities($roominfo->roomdesc, ENT_COMPAT);

if($hashtag == 'app'){
    $roominfo->ownerid = 0;
    $roominfo->ownername2 = $appname;
    $roominfo->room = $appname." Store";
    $roominfo->roomdesc = $appname." Store";
}

$foldername = '';
if($folderid =='_store'){
    if($hashtag == 'app'){
        $roominfo->ownerid = 0;
        $roominfo->ownername2 = $appname;
        $roominfo->room = $appname." Store";
    }
    
    $folderfiles = "
                    <a href='$rootserver/$installfolder/host.php?h=$hashtag&store=$hashtag&p=$providerid&version=$version' style='text-decoration:none;color:$global_textcolor'>
                    <div class='pagetitle' style='text-align:center;width:100%;margin:auto;color:$global_textcolor'>$roominfo->ownername2 Store</div>
                    </a><br>
                    ";
    if($version == '' || $version == '000'){
        $folderfiles .= "
                    <center>
                    <a href='$rootserver/$startupphp?&h=$hashtag&store=$hashtag&version=$version' style='text-decoration:none;color:$global_textcolor'>
                    <div class='pagetitle3' style='margin:auto;background-color:transparent;color:$global_activetextcolor;cursor:pointer'>
                    Go to $appname
                    </div>
                    </a>
                    </center>
                    <br>
                    
                ";
    } else {
        $folderfiles .= "
                    <center>
                    <a href='javascript:history.back()' style='text-decoration:none;color:$global_textcolor'>
                    <div class='pagetitle3' style='margin:auto;background-color:transparent;color:$global_activetextcolor;cursor:pointer'>
                    Go to $appname
                    </div>
                    </a>
                    </center>
                    <br>
                    
                ";
    }
    if($customsite && $hashtag == 'app'){
        $folderfiles = "
                    <div class='pagetitle' style='text-align:center;width:100%;margin:auto;color:$global_textcolor'>$roominfo->ownername2 Store</div>
                        <br>
                    <br>
                ";
    }
    
    
    $folderfiles .= "<hr style='border:1px solid  $global_separator_color'><br>";

    $join =  "
                    <a href='$rootserver/signup/$hashtag&tracker=$trackerid&store=Y&version=$version' style='text-decoration:none;color:$global_textcolor'>
                    <span class='pagetitle2a' style='color:$global_activetextcolor;background:$global_background;'>Create account on $appname to use the Store</span>
                    </a>
             ";
    
    if($hashtag == 'app'){
        $folderfiles .=  StoreCoupon( $providerid );
    }
    if(!$customsite ){
        $folderfiles .= StoreDetail('', $providerid, $roominfo->ownerid, '', 1, $join );
    }
    
    $nonmobilehtml = "";
    $mobilehtml = "";
    
} else
if($folderid !=''){
    
    $result = do_mysqli_query("1","

        select distinct foldername, folderid from roomfilefolders where roomid = $roomid
            and folderid = $folderid
            order by foldername asc
        ");
    if($row = do_mysqli_fetch("1",$result)){
        $foldername = $row['foldername'];
    }
    
    $folderfiles = RoomFolders( $roomid, $roominfo, $memberinfo, $folderid);
    $nonmobilehtml = "";
    $mobilehtml = "";
}
if($folderid ==''){
    
    $folderfiles = RoomFolders( $roomid, $roominfo, $memberinfo, 0);
}

if($webnavbar == ''){
    $webnavbar = "
        <a href='$rootserver/$installfolder/host.php?h=$hashtag&p=$providerid&version=$version' style='text-decoration:none;color:$global_textcolor'>
        <div class='pagetitle2' style='float:left;color:white;background-color:$global_titlebar_color;cursor:pointer'>
        $roominfo->room
        </div>
        </a>
        
        ";
    if($providerid==''){
        $webnavbar .= "
    
                        <a class='pagetitle3' href='$rootserver/signup/$roominfo->roominvitehandle' style='margin-right:30px;float:right;text-decoration:none;color:$global_activetextcolor_reverse'>
                            Join
                         </a>
                ";
    }
    if($customsite && $hashtag == 'app'){
        $webnavbar = '';
    }
}

/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/

function RoomNonMobile( $roominfo, $memberinfo, $webheader, $providerid, $hashtag)
{
    global $rootserver;
    global $global_titlebar_color;
    global $global_textcolor;
    global $global_bottombar_color;
    global $appname;
    global $iconsource_braxarrowright_common;
    global $global_background;
    global $sizing;
    global $menu_join;
    global $menu_login;
    global $language;
    global $trackerid;
    global $global_store_color;
    global $applogo;
    global $version;
    
    $roomhandle = $roominfo->roominvitehandle;
    $photourl2 =  $roominfo->photourl2;
    $roomtitle = $roominfo->room;
    $roomdesc =  $roominfo->roomdesc;
    $avatarurl = $roominfo->avatarurl;
    $webtextcolor = $roominfo->webtextcolor;
    $storeurl = $roominfo->storeurl;
    $store = $roominfo->store;
    $publishprofile = $roominfo->publishprofile;
    $ownername = $roominfo->ownername2;
    $analytics = $roominfo->analytics;
    $ownerhandle = $roominfo->ownerhandle." on $appname";
    $avatarhtml = "
                    <div class='circular2 gridnoborder' 
                        style='margin-left:auto;margin-right:auto;margin-top:20px;height:150px;
                        width:150px;overflow:hidden;background-color:transparent;color:$global_textcolor;'
                        >
                        <img class='' src='$avatarurl' style='cursor:pointer;min-height:100%;max-width:100%' />
                     </div>
                     ";
    
    
    $logo = "";
    $webpublishprofile = $roominfo->webpublishprofile;
    if( $webpublishprofile!='Y'){
        $ownername = "";
        $ownerhandle = "";
        //$publishprofile = "";
        $avatarhtml = "";
        if($roominfo->logo!=''){
            $logo = "<img src='$roominfo->logo' style='padding-top:20px;padding-bottom:20px;max-width:100%;width:auto;max-height:200px' />";
        }
    }
    $hidejoin = '';
    if($providerid!=''){
        $hidejoin = 'display:none;';
    }
    if($hashtag == 'app'){
        $hidejoin = 'display:none;';
        $ownername = "";
        $ownerhandle = "";
        //$publishprofile = "";
        $avatarhtml = "";
        $logo = "<img src='$applogo' style='padding-top:20px;padding-bottom:20px;max-width:100%;width:auto;max-height:200px' />";
        $publishprofile = "";
        $store = 'Y';
        
    }
    
    if($roominfo->radiostation=='Y'){
        $publishprofile = "";
    }
    
    $webflags = $roominfo->webflags;
    
    if($photourl2!=''){
        
        $darken = "";
        if(strstr($webflags,"darken")!==false){
            $darken = "
                linear-gradient(
                rgba(0,0,0,0.5),
                rgba(0,0,0,0.5)
                ),
             ";           
        }
        
        //$contain = "contain";
        //$contain = "100% auto";
        $contain = "auto 100%";
        if(strstr($webflags,"xpriority")!==false){
            $contain = "100% auto";
        }
        if(strstr($webflags,"ypriority")!==false){
            $contain = "auto 100%";
        }
        if(strstr($webflags,"contain")!==false){
            $contain = "contain";
        }
        

    $storebutton = "";
    if($store=='Y'){
        
        $storebutton = "
                        <a href='$rootserver/prod/host.php?f=_store&h=$hashtag&p=$providerid&version=$version' 
                                style='text-decoration:none;color:$global_textcolor'>
                            <div class=''  style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white;font-family:helvetica'>
                                <img class='icon30' src='../img/store-128.png'>
                                Visit Online Store 
                            </div>  
                        </a>
                        <br><br>
                        <br><br><br>
                        ";
    } else
    if($storeurl!=''){
        $storebutton = "
                        <div class='divbuttontext'
                            style='border:0;width:150px;
                            background-color:$global_titlebar_color;color:white;'
                            title='Visit Store'
                        >
                            <a href='$storeurl' style='text-decoration:none;color:white' target='_blank'>
                                &nbsp;&nbsp;Visit the Store&nbsp;&nbsp;&nbsp;
                             </a>
                        </div>
                        <br><br><br>
                        ";
    }

        

    return "
        <span class='nonmobile'>
            <table class='gridnoborder' style='padding:0;margin:0;vertical-align:top;float:left:width:100%' >
            <tr>
            <td style='vertical-align:top;overflow:hidden;width:100%;max-width:800px'>

                <div 
                   style='padding:0;margin:0;overflow:hidden;
                    width:100%;height:800px;
                    background:$darken
                    url($photourl2);
                    background-size:cover;background-repeat:no-repeat;background-color:transparent' >


                    <div style='width:800px;max-width:80%;position:relative;top:0px;left:0px;
                        padding-left:40px;padding-right:40px;padding-top:20px;padding-bottom:20px;'>
                        
                        <div class='pagetitle' style='width:700px;float:left;color:$webtextcolor;font-size:60px;'>$roomtitle</div>
                        <div class='pagetitle' style='width:700px;float:left;color:$webtextcolor;font-size:20px;'>
                           $roomdesc 
                        </div>
                    </div>    

                </div>

            
            </td>
            <td style='vertical-align:top;text-align:center'>

                <div  style='width:300px;height:100%;background-color:transparent;
                        opacity:1;padding-left:20px;padding-right:20px;text-align:center'>
                    $logo    
                        <br><br>
                    $storebutton

                    <span style='$hidejoin'>
                    <div class='divbuttontext'
                        style='border:0;width:200px;margin-bottom:30px;
                        background-color:$global_titlebar_color;color:white;'
                        title='Join group on $appname'
                    >
                        <a href='$rootserver/signup/$roomhandle/&lang=$language&tracker=$trackerid&version=$version' style='text-decoration:none;color:white'>
                            $menu_join
                         </a>
                    </div>
                    &nbsp;&nbsp;
                    <div class='divbuttontext'
                        style='border:0;width:200px;
                        background-color:$global_titlebar_color;color:white;'
                        title='Login'
                    >
                        <a href='$rootserver/l.php?source=web&h=$roomhandle&version=$version' style='text-decoration:none;color:white'>
                            $menu_login
                         </a>
                    </div>
                    </span>

                    $avatarhtml
                    <div class='mainfont gridnoborder' 
                        style='color:$global_textcolor;position:relative;
                          background-color:transparent;margin-top:20px;
                          padding-left:20px;padding-right:20px;
                          text-align:center'
                        >
                        $ownername<br>
                        $ownerhandle<br><br>
                        $publishprofile<br><br>
                        $webheader<br><br>
                    </div>            
                </div>    


            </td>
            </tr>
            </table>
        </span>
            ";
        
    }
}
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/

function RoomMobile( $roominfo, $memberinfo, $webheader, $providerid, $hashtag)
{
    global $rootserver;
    global $global_titlebar_color;
    global $global_textcolor;
    global $global_bottombar_color;
    global $global_menu_color;
    global $appname;
    global $iconsource_braxarrowright_common;
    global $global_background;
    global $sizing;
    global $menu_join;
    global $menu_login;
    global $language;
    global $trackerid;
    global $global_store_color;
    global $applogo;
    global $version;
    
    $roomhandle = $roominfo->roominvitehandle;
    $photourl2 =  $roominfo->photourl2;
    $roomtitle = $roominfo->room;
    $roomdesc =  $roominfo->roomdesc;
    $avatarurl = $roominfo->avatarurl;
    $webtextcolor = $roominfo->webtextcolor;
    $storeurl = $roominfo->storeurl;
    $store = $roominfo->store;
    $publishprofile = $roominfo->publishprofile;
    $ownername = $roominfo->ownername2;
    $analytics = $roominfo->analytics;
    $ownerhandle = $roominfo->ownerhandle." on $appname";
    $avatarhtml = "
                    <div class='circular2 gridnoborder' 
                        style='margin-left:auto;margin-right:auto;margin-top:20px;height:150px;
                        width:150px;overflow:hidden;background-color:transparent;color:$global_textcolor;'
                        >
                        <img class='' src='$avatarurl' style='cursor:pointer;min-height:100%;max-width:100%' />
                     </div>
                     ";
    
    $logo = "";
    
    $webpublishprofile = $roominfo->webpublishprofile;
    if( $webpublishprofile!='Y'){
        $ownername = "";
        $ownerhandle = "";
        //$publishprofile = "";
        $avatarhtml = "";
        if($roominfo->logo!=''){
            $logo = "<img src='$roominfo->logo' style='padding-top:20px;padding-bottom:20px;max-width:100%;width:auto;max-height:200px' />";
        }
    }
    $hidejoin = '';
    if($providerid!=''){
        $hidejoin = 'display:none;';
    }
    if($hashtag == 'app'){
        $ownername = "";
        $ownerhandle = "";
        //$publishprofile = "";
        $avatarhtml = "";
        $logo = "<img src='$applogo' style='padding-top:20px;padding-bottom:20px;max-width:100%;width:auto;max-height:200px' />";
        $publishprofile = "";
        $hidejoin = 'display:none;';
        
    }
    if($roominfo->radiostation=='Y'){
        $publishprofile = "";
    }
    $webflags = $roominfo->webflags;
    
    if($photourl2!=''){
        
        $darken = "";
        if(strstr($webflags,"darken")!==false){
            $darken = "
                linear-gradient(
                rgba(0,0,0,0.5),
                rgba(0,0,0,0.5)
                ),
             ";           
        }
        
        //$contain = "contain";
        //$contain = "100% auto";
        $contain = "auto 100%";
        if(strstr($webflags,"xpriority")!==false){
            $contain = "100% auto";
        }
        if(strstr($webflags,"ypriority")!==false){
            $contain = "auto 100%";
        }
        if(strstr($webflags,"contain")!==false){
            $contain = "contain";
        }
        

    $storebutton = "";
    if($store=='Y'){
        
        $storebutton = "
                        <center>
                        <a href='$rootserver/prod/host.php?f=_store&h=$hashtag&p=$providerid&version=$version' 
                                style='text-decoration:none;color:$global_textcolor'>
                            <div class=''  style='width:250px;cursor:pointer;padding-left:10px;background-color:$global_store_color;color:white;font-family:helvetica'>
                                <img class='icon30' src='../img/store-128.png'>
                                Visit Online Store 
                            </div>  
                        </a>
                        </center>
                        <br><br>
                        <br><br><br>
                        ";
    } else
    if($storeurl!=''){
        $storebutton = "
                        <div class='divbuttontext'
                            style='border:0;width:150px;
                            background-color:$global_titlebar_color;color:white;'
                            title='Visit Store'
                        >
                            <a href='$storeurl' style='text-decoration:none;color:white' target='_blank'>
                                &nbsp;&nbsp;Visit the Store&nbsp;&nbsp;&nbsp;
                             </a>
                        </div>
                        <br><br><br>
                        ";
    }

        

    return "
        <span class='formobile'>
            <div class='mainfont gridnoborder' 
                style='width:100%;margin:0;padding-bottom:0;padding-top:0;
                 padding-right:20px;background-color:transparent;text-align:center'
                >
                <a href='$rootserver/signup/$roomhandle/&lang=$language&version=$version' style='text-decoration:none'>
                    <div class='' 
                        style='border:0;width:170px;margin:auto;
                        padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:5px;text-align:center;
                        background-color:$global_titlebar_color;color:white;'
                            
                        title='Join group on $appname'
                    >
                       <span class='pagetitle2a' style='color:white'>Join</span><img class='icon15' 
                          src='../img/Arrow-Right-in-Circle-White_120px.png' 
                          style='padding-left:10px;position:relative;top:5px' />
                    </div>
                </a>
                <div style='background-color:transparent;width:80%;padding-left:20px;
                      padding-right:20px;padding-top:20px;padding-bottom:20px;
                      margin:auto;text-align:center;margin:auto'>
                    <div class='pagetitle' style='background-color:transparent;color:$global_textcolor;font-size:40px;width:500px;max-width:100%;text-align:center;margin:auto'><b>$roomtitle</b></div>
                    <div class='pagetitle2a' style='background-color:transparent;color:$global_textcolor;text-align:center;width:500px;;max-width:100%;margin:auto'>
                       $roomdesc 
                    </div>
                </div>
                <div class='gridnoborder' style='width:100%;padding:0px;margin:0;text-align:center;position:relative;top:0px;left:0px'>
                <img src='$photourl2' style='width:100%' />
                </div>
            </div>
            <div  style='width:100%;background-color:$global_menu_color;
                    opacity:1;text-align:center;margin:0'>
                <div style='width:90%;text-align:center;margin:auto'>
                    $logo    
                        <br><br>
                    $storebutton

                    <span style='$hidejoin'>
                    <div class='divbuttontext'
                        style='border:0;width:200px;margin-bottom:30px;
                        background-color:$global_titlebar_color;color:white;'
                        title='Join group on $appname'
                    >
                        <a href='$rootserver/signup/$roomhandle/&lang=$language&tracker=$trackerid&version=$version' style='text-decoration:none;color:white'>
                            $menu_join
                         </a>
                    </div>
                    &nbsp;&nbsp;
                    <div class='divbuttontext'
                        style='border:0;width:200px;
                        background-color:$global_titlebar_color;color:white;'
                        title='Login'
                    >
                        <a href='$rootserver/l.php?source=web&h=$roomhandle&version=$version' style='text-decoration:none;color:white'>
                            $menu_login
                         </a>
                    </div>
                    </span>

                    $avatarhtml
                    <div class='mainfont gridnoborder' 
                        style='color:white;position:relative;
                          background-color:transparent;margin-top:20px;
                          padding-left:20px;padding-right:20px;
                          text-align:center'
                        >
                        $ownername<br>
                        $ownerhandle<br><br>
                        $publishprofile<br><br> 
                        $webheader
                        <br><br>
                    </div>            
                </div>
            </div>    
            
        </span>
            ";
        
    }
}
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/
/************************************************************************************************************/

function RoomFolders( $roomid, $roominfo, $memberinfo, $folderid)
{
    global $rootserver;
    global $installfolder;
    global $global_titlebar_color;
    global $global_textcolor;
    global $global_activetextcolor;
    global $global_activetextcolor_reverse;
    global $global_bottombar_color;
    global $global_menu_color;
    global $appname;
    global $iconsource_braxarrowright_common;
    global $global_background;
    global $sizing;
    global $timezoneoffset;
    global $customsite;
    
    $roomhandle = $roominfo->roominvitehandle;
    $photourl2 =  $roominfo->photourl2;
    $roomtitle = $roominfo->room;
    $roomdesc =  $roominfo->roomdesc;
    $avatarurl = $roominfo->avatarurl;
    $webtextcolor = $roominfo->webtextcolor;
    $storeurl = $roominfo->storeurl;
    $publishprofile = $roominfo->publishprofile;
    $ownername = $roominfo->ownername2;
    $analytics = $roominfo->analytics;
    $ownerhandle = $roominfo->ownerhandle." on $appname";
        
    $webbody = "";
    $result = do_mysqli_query("1","
            select filename, fileencoding, filesize from filelib where filename in (
            select filename from roomfiles where roomid = $roomid 
            and title like 'webbody%.txt' and  folderid = $folderid and '$folderid'!='0' ) 
            ");
    $count = 0;
    while($row = do_mysqli_fetch("1",$result)){
        $filesize = $row['filesize'];
        if($count == 0){
            $webbody = "<div class='pagetitle3' style='margin:auto;padding:20px;text-align:left;color:$global_textcolor'>";
        }
        $webbody .=  substr(getAWSObjectStreamEncryptedContent( $row['filename'], $row['fileencoding'], 0xFFFFF, $filesize ),0, $filesize);
    }
    if($count>0){
        $webbody .= "</div>";
    }

    
        $filelist = $webbody;
    
        $filecount = 0;
    
        $result = do_mysqli_query("1",
        "
            select filelib.origfilename, filelib.filename, filelib.folder, 
            filelib.alias, filelib.views, filelib.filetype, filelib.filesize, filelib.title,
            date_format( date_add(roomfiles.createdate,INTERVAL ($timezoneoffset)*60 MINUTE),'%b %d, %y %h:%i%p') as createdate,
            filelib.createdate as createdate2, filelib.encoding, filelib.providerid
            from filelib 
            left join roomfiles on roomfiles.filename = filelib.filename 
                and roomfiles.folderid=$folderid
            where roomfiles.roomid = $roomid and filelib.status = 'Y' and 
            filelib.title not like 'webbody%.txt' and 
            filelib.title not like 'webheader%.txt' 
            order by filename asc
        ");
        
        while($row = do_mysqli_fetch("1",$result)){
            $filecount++;
            
            if( $row['filetype']=='mov' ||  $row['filetype']=='mp4' )
            {
                $href = "$rootserver/$installfolder/videoplayer.php?p=$row[alias]&f=$row[origfilename]&t=$row[title]";
                $filelist .= "<a class='mainfont' href='$href' style='text-decoration:none;color:$global_activetextcolor'>$row[title]</a><br><br>";
                
            } else 
            if( $row['filetype']=='mp3' ||  $row['filetype']=='wav' )
            {
                if($row['title']==''){
                    $row['title'] = $row['origfilename'];
                }
                $musicUrl = getAWSObjectUrlShortTerm( $row['filename'] );
                $href = "<center><audio src='$musicUrl' preload='none' ></audio></center>";            
                $filelist .= "<div style='margin-auto;padding:20px;text-align:center'>$row[title]<br>$href</div>";
                //$href = "$rootserver/$installfolder/soundplayer.php?p=$row[alias]&f=$row[origfilename]&t=$row[title]";

            } else {
                $href = "$rootserver/$installfolder/doc.php?p=$row[alias]";
                $filelist .= "<a class='mainfont' href='$href' style='text-decoration:none;color:$global_activetextcolor'>$row[title]</a><br><br>";
            }
            
        }
        if($filecount > 0){
            $filelist .= "<br><br>";
        }


    return "$filelist";
        
    
}
$randomid = uniqid();


echo "<!DOCTYPE html>\r\n";
echo "<html>\r\n";
echo "<head>\r\n";
echo "<meta charset='utf-8'>";

echo "<meta name='description' content='$roomdesc'>";
echo "<meta property='og:title' content='$roomtitle' />";
echo "<meta property='og:url' content='$rootserver/home/$hashtag' />";
echo "<meta property='og:image' content='$roominfo->photourl2' />";      

echo "<meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=no,maximum-scale=1'>";
echo "<meta name='mobile-web-app-capable' content='yes'>";

echo "<link rel='apple-touch-startup-image' href='$rootserver/img/logo-b1a.png'>";
echo "<link rel='icon' href='$rootserver/img/logo-b1a.png'>";
echo "<link id=favicon rel='shortcut icon' href='$rootserver/img/logo-b1a.ico'>";
echo "<link rel='apple-touch-icon' href='$rootserver/img/logo-b1a.png'>";
echo "<link rel='apple-touch-icon-precomposed' href='$rootserver/img/logo-b1a.png'>";
echo "<link rel='apple-touch-startup-image' href='$rootserver/img/logo-b1a.png' />";

echo "<link href='https://$rootserver/fonts/font-raleway.css' rel='stylesheet'>";

echo "<link rel='stylesheet' href='$rootserver/libs/alertify.js-0.3.10/themes/alertify.core.css' />\r\n";
echo "<link rel='stylesheet' href='$rootserver/libs/alertify.js-0.3.10/themes/alertify.default.css' />\r\n";
echo "<script src='$rootserver/libs/alertify.js-0.3.10/src/alertify.js'></script>\r\n";

echo "<link rel='stylesheet' href='$rootserver/libs/jquery-1.11.1/jquery-ui.css'>";
echo "<script src='$rootserver/libs/jquery-1.11.1/jquery.min.js'  ></script>";
echo "<script src='$rootserver/libs/jquery-1.11.1/jquery-ui.js'  ></script>";

echo "<link rel='styleSheet' href='$rootserver/$installfolder/app.css?$randomid' type='text/css'/>\r\n";
echo "<link rel='styleSheet' href='$rootserver/$installfolder/animate.css' type='text/css'/>\r\n";
echo "\r\n";
echo "\r\n";
echo "<script type='text/javascript' src='$rootserver/$installfolder/base64v1_0.js'></script>\r\n";

echo "<script type='text/javascript' src='$rootserver/libs/jquery.visible/jquery.visible.js'></script>\r\n";
echo "<script type='text/javascript' src='$rootserver/libs/audio/audiojs/audio.js?$randomid'></script>\r\n";

echo "<script type='text/javascript' src='$rootserver/libs/fastclick/fastclick.js'></script>\r\n";


echo "<script type='text/javascript' src='$rootserver/libs/imagesloaded-master/imagesloaded.pkgd.min.js?$randomid'></script>\r\n";



//echo "<script type='text/javascript' src='$rootserver/$installfolder/animation/js/main.js'></script>\r\n";
echo "<script type='text/javascript' src='$rootserver/$installfolder/animation/js/prefixfree.min.js'></script>\r\n";



?>
<script type='text/javascript' src='<?=$rootserver?>/<?=$installfolder?>/animation/js/prefixfree.min.js'></script>
<script>
audiojs.events.ready(function() 
{
    var as = audiojs.createAll();
});
</script>
</head>
<style>
#navbar {
  overflow: hidden;
  background-color: <?=$global_titlebar_color?>;
  color:white;
}

#navbar a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

#navbar a:hover {
  background-color: #ddd;
  color: black;
}

#navbar a.active {
  background-color: #4CAF50;
  color: white;
}

.divbutton div:hover {
  background-color: #ddd;
  color: black;
}

.content {
}

.sticky {
  position: fixed;
  top: 0;
  width: 100%
}
.stickyundo {
  display:none;
  position: relative;
  top: 0;
  left:0;
  width: 100%
}

.hidearrow {
    display:none;
}

.sticky + .content {
  padding-top: 60px;
}    
</style>
<body style='width:100%;background-color:transparent;color:<?=$global_textcolor?>;position:absolute;top:0;left:0;margin:0;padding:0'>
<div id='arrowmarker' class='nonmobile header' title='Scroll to view more content' alt='Scroll to view more content' style="z-index:5;cursor:pointer;color:white; position:fixed; bottom:0; right:0; margin:auto;width:100%; text-align:center; height:50px;opacity:1;margin-bottom:30px">
    <img src='<?=$rootserver?>/img/Arrow-Down-in-Circle-White_120px.png' style='height:50px;' />
</div>
<div id='header fixedbackground' class='' style='
    background-color:<?=$global_background?>;
    display:block;
    background-size:cover;
    position:fixed;
    left:0px;
    top:0px;
    width:100%;
    height:100%;
    margin:0;
    padding:0;
    z-index:2;
    '>
    <?=$display_background_image?>
</div>
<div class='content topheadline' style='width:100%;z-index:2;background-color:transparent;position:relative;top:0px;padding:0;margin:0'>
    <?=$nonmobilehtml?>
    <?=$mobilehtml?>
</div>    
<div style='text-align:center;width:100%;z-index:2;background-color:transparent;position:relative;top:0px'>
    <div id='navbar' class='navbar' style='z-index:100;width:100%;padding:0;margin:0;background-color:<?=$global_titlebar_color?>;' >
        <?=$webnavbar?>
    </div>        
<?php

if($folderid == ''){
    require_once("hostcontentnew.php");
    
    if($webbody!=''){
        echo "
        <div class='mainfont' style='z-index:2;color:$global_textcolor;background-color:transparent;width:100%;text-align:center;position:relative;top:0px;left:0px'>
        $folderfiles
        $webbody
        </div>";
    }
    if($webfolders!=''){
        echo "
        <div class='mainfont' style='z-index:2;color:$global_textcolor;background-color:$global_menu_color;width:100%;text-align:center;position:relative;top:0px;left:0px;padding-top:20px;padding-bottom:20px'>
        $webfolders
        </div>";
    }
}
if($folderid !== ''){
        echo "
        <div class='mainfont' style='z-index:2;color:white;background-color:$global_menu_color;width:100%;text-align:center;position:relative;top:0px;left:0px;padding-top:20px;padding-bottom:20px'>
        $foldername
        </div>
        <div class='mainfont' style='min-height:100%;z-index:2;color:$global_textcolor;background-color:transparent;width:100%;text-align:center;position:relative;top:0px;left:0px;padding-top:20px;padding-bottom:20px'>
            $folderfiles
        </div>";

        
}

echo "<br><br>";


?>
    <div class='smalltext' style='z-index:2;color:white;background-color:<?=$global_bottombar_color?>;width:100%;text-align:center;position:relative;top:0px;left:0px'>
<?php
echo "<div class='smalltext' style='position:relative;max-width:100%;float:right;text-align:center;margin:auto;padding:30px'>";
echo LanguageLinks("$rootserver/$installfolder/host.php?h=$hashtag&p=$providerid","float:right","$global_activetextcolor_reverse");
echo "</div>";
?>
        <div style='z-index:100;width:500px;max-width:80%;margin:auto;padding:20px;color:white;text-align:center'>
            <table class="gridnoborder" style="vertical-align:top;max-width:80%;margin:auto">
                <tr>
                    <td>
                        <a href='<?=$homepage?>'>
                        <img src='<?=$applogo?>' style='height:40px;width:auto' />
                        </a>
                        
                    </td>
                    <td style='text-align:left;padding-left:10px'>
                        <br>Powered by <?=$appname?> <?=$enterpriseapp?> 
                    </td>
                </tr>
                
            </table>
        </div>
    </div>
</div>   
<?=$roominfo->analytics?>
<script>
    
$(document).ready( function() {
    
    $('body').on('click','.slideshow', function()
    {
        $('#slideshowform').find('#slideshow_album').val( $(this).data('album') );
        $('#slideshowform').find('#slideshow_pid').val( $(this).data('providerid') );
        $('#slideshowform').find('#slideshow_innerwidth').val( window.innerWidth );

        $('#slideshowform').submit();
    });

    var shrunkmode = true;
    $('body').on('click','.commentitem',function(){
        var postid = $(this).data('postid');
        var myid = '#l'+postid;
        if(shrunkmode === true){
            $('.commentitem').attr('background-color','transparent');
            shrunkmode = false;
            $(this).find('.commentlong').show();
            $(this).find('.commentlongphoto').show();
            $(this).find('.commentshort').hide();
            $(this).find('.commentshortphoto').hide();
            $(this).attr('background-color','<?=$global_menu_color?>');
            $(this).height("auto");
            
        } else {
            shrunkmode = true;
            $('.commentshort').show();
            $('.commentlongphoto').hide();
            $('.commentshortphoto').show();
            $('.commentlong').hide();
            $('.commentitem').height(400);
            
        }
    });
        $('body').on('click', '.showhidden', function(e){
            $('.showhiddenarea').show();
        });
        $('body').on('click', '.showhidden2', function(e){
            $('.showhiddenarea2').show();
        });
        $('body').on('click', '.showhidden3', function(e){
            $('.showhiddenarea3').show();
        });
        $('body').on('click', '.hidehidden', function(e){
            $('.showhiddenarea').hide();
            $('.showhidden').show();
        });
        $('body').on('click', '.hidehidden2', function(e){
            $('.showhiddenarea2').hide();
            $('.showhidden2').show();
        });
        $('body').on('click', '.hidehidden3', function(e){
            $('.showhiddenarea3').hide();
            $('.showhidden3').show();
        });
        
        $('body').on('click', '.hideshow', function(e){
            $('.showhidden').hide();
        });
        $('body').on('click', '.hideshow2', function(e){
            $('.showhidden2').hide();
        });
        $('body').on('click', '.hideshow3', function(e){
            $('.showhidden3').hide();
        });

});
    
    
innerHeight = window.innerHeight;
innerWidth = window.innerWidth;
    
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = 400;
var topheadlineoffset = 50;

function myFunction() {
  if (window.pageYOffset >= sticky) {
      
    navbar.classList.remove("stickyundo");
    navbar.classList.add("sticky");
    $('#header2').show();
    
  } else {
      
    navbar.classList.remove("sticky");
    navbar.classList.add("stickyundo");
    $('#header2').hide();
    
  }
  if (window.pageYOffset >= topheadlineoffset) {
    arrowmarker.classList.add("hidearrow")
  } else {
    arrowmarker.classList.remove("hidearrow");
  }
  
}



</script>
<span style="display:none">
    <FORM id='slideshowform' name='slideshow'  ACTION='<?=$rootserver?>/<?=$installfolder?>/slideshow.php' METHOD='POST' target='_blank'  >
    <INPUT TYPE='hidden' NAME='pid' id='slideshow_pid' value='' >
    <INPUT TYPE='hidden' NAME='album' id='slideshow_album' value='All' >
    <INPUT TYPE='hidden' NAME='loginid' value='admin' >
    <INPUT TYPE='hidden' NAME='innerwidth' id='slideshow_innerwidth' value='' >
    </FORM>
</span>
</body>
</html>