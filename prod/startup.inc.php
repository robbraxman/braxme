<?php
require_once("config.php");
require_once("room.inc.php");
require_once("crypt.inc.php");
require_once("notify.inc.php");
require_once("roommanage.inc.php");
require_once("advertising.inc.php");

    $_SESSION['validsession']=uniqid();
    
    $_SESSION['needsms']="";

    $roommanagemenu = RoomManageMenu();
    $_SESSION['iscore']=5;
    $_SESSION['innerwidth']=0;


    $_SESSION['source']=@mysql_safe_string($_POST['source']);
    if( $_SESSION['source']=='app' || $_SESSION['source']=='android'){
    
        $source = 'Y';
        do_mysqli_query("1","update provider set mobile='$source' where providerid=$_SESSION[pid] ");
    }

    $timezone = @mysql_safe_string( $_POST['timezone']);
    if($timezone!==''){
        $_SESSION['timezone']=$timezone;
        $_SESSION['timezoneoffset'] = floatval($_SESSION['timezone']) - floatval($_SESSION['servertimezone']);
    } else {
        $_SESSION['timezone']="0";
        $_SESSION['timezoneoffset'] = "0";
    }
    $today = date("M-d-y",time()+$_SESSION['timezone']*60*60);

    $useragent=@mysql_safe_string($_POST['useragent']);
    if( $useragent!='' ){ 
    
        do_mysqli_query("1","update provider set useragent='$useragent' where providerid=$_SESSION[pid] ");
    }

    $profileobj = FindProfileRoom($providerid, $providerid);
    $_SESSION['profileaction']=$profileobj->action;
    //$_SESSION['profileroomid']=$profileobj->roomid;

    $blink = "";
    if(($_SESSION['avatarurl']=="$prodserver/img/faceless.png" || $_SESSION['avatarurl']=="" ) && $_SESSION['roomdiscovery']=='Y'){
        $beacon = "
            <div class='beaconcontainer $_SESSION[profileaction]' style='cursor:pointer;z-index:100;position:absolute;'
                               data-roomid='$_SESSION[profileroomid]'
                               data-providerid='$providerid' data-caller='none'
                               data-profile='Y'
                        >
                <div class='beacon' style='color:$global_activetextcolor;border-color:$global_activetextcolor'></div>
            </div>
            ";
        $blink = "$beacon";
    }
    
        //Add myself to Room based on Invite
    
        
        do_mysqli_query("1","
            insert into statusroom (providerid, roomid, room, owner, createdate, creatorid )

            select distinct $providerid as providerid, invites.roomid, roominfo.room, statusroom.owner, 
                 now(), invites.providerid from invites
            left join statusroom on invites.roomid = statusroom.roomid
            and statusroom.owner = invites.providerid
            left join roominfo on invites.roomid = roominfo.roomid
            where invites.email in 
            (select replyemail from provider where providerid=$providerid)
            and invites.status = 'Y' and invites.roomid > 0 
            and invites.roomid not in (select roomid from statusroom
            where providerid=$providerid)

            ");
        
          
         
        //Automatically Connect to Chat Session from Invite
        $result = do_mysqli_query("1","
            select chatid, providerid 
            from invites 
            where exists 
            (select *
                from provider where providerid = $providerid
                and 
                (
                    (provider.replyemail= invites.email and invites.email!='')
                    or
                    (provider.handle = invites.handle and invites.handle!='')
                )
                and providerid not in (select providerid from chatmembers 
                where
                chatmembers.providerid and invites.chatid = chatmembers.chatid
                )
            ) 
            and status='Y' and chatid in (
                select chatid from chatmaster where invites.chatid = chatmaster.chatid and status='Y' )
            ");
        
        while( $row = do_mysqli_fetch("1",$result) ){
        
            $invitechatid = $row['chatid'];
            $inviteownerid = "$row[providerid]";
            
            //Automatically Connect to Chat Session from Invite
            do_mysqli_query("1","
                insert into chatmembers 
                ( chatid, providerid, status, lastactive, techsupport ) 
                values 
                ( $invitechatid, $providerid, 'Y', now(), '' )
                ");
            
            $encodeshort = EncryptChat("Please read your chat message",$invitechatid, "");
            ChatNotificationRequest($inviteownerid, $invitechatid, $encodeshort, $_SESSION['responseencoding'],'');
            
        }    
        //Remove prior Chat Invites
        do_mysqli_query("1","
            delete from invites where chatid > 0 and exists (
                select * from provider where
                (
                    (provider.replyemail= invites.email and invites.email!='')
                    or
                    (provider.handle = invites.handle and invites.handle!='')
                )
                and provider.providerid = $providerid
            )
            ");

    
        //Figure Out User Level of Use
        $_SESSION['photouser'] = '';
        $_SESSION['roomuser'] = '';
        $_SESSION['roommember'] = '';
        $_SESSION['chatuser'] = '';
        $_SESSION['fbshared'] = '';
        $_SESSION['contacts'] = '';
        
        
        //Automatically Share Room Contacts
        /*
        do_mysqli_query("1","
                    insert into batchrequest ( providerid, requestdate, requesttype, status ) 
                    values ($providerid, now(), 'SHARECONTACTSROOM', 'N' )

            ");
         * 
         */
        
        
        
    $apn = @mysql_safe_string( $_POST['apn'] );
    $gcm = @mysql_safe_string( $_POST['gcm'] );
    $uuid = @mysql_safe_string( $_POST['uuid'] );
    $_SESSION['gcm']=$gcm;
    $_SESSION['apn']=$apn;
    $_SESSION['uuid']=$uuid;
    $_SESSION['notifyid']=$apn.$gcm;
    $_SESSION['mobilesize']='N';
    if( $apn.$gcm != ''){
        $_SESSION['mobilesize']='Y';
    }
    //Find Last Function so we can go back on Startup
    $lastfunc = GetLastFunction("$_SESSION[pid]",120);
    
    $lastroomid = '0';
    
    storeNotificationToken( $_SESSION['pid'], $apn, $gcm );
    
    $bannercolor = $global_banner_color;//'#3e4749';//gray

    $settingsmenu = SettingsMenu($_SESSION['version']);
        
    function storeNotificationToken( $providerid, $apn, $gcm )
    {
        global $appname;
        
        $token = '';
        if($gcm!=''){
        
            $token = $gcm;
            $platform = "android";
        }
        if($apn!=''){
        
            $token = $apn;
            $platform = "ios";
        }
        
        
        //$arn = createSnsPlatformEndpoint( $apn, $gcm );
        if($token!=''){
        
            @do_mysqli_query("1"," 
                delete from notifytokens where token = '$token' and providerid= $providerid and status='E' and app='$appname'
                    ");
            @do_mysqli_query("1"," 
                insert into notifytokens 
                (providerid, token, platform, registered, status, arn, app) values
                ($providerid, '$token', '$platform',now(), 'Y','','$appname')
                    ");
            @do_mysqli_query("1"," 
                update notifytokens set registered=now() where providerid=$providerid and token='$token'  and app='$appname'
                    ");

            @do_mysqli_query("1"," 
                delete from notifytokens where token = '$token' and providerid != $providerid  and app='$appname'
                    ");
            
        }


    }

    function SettingsMenu($version)
    {
        global $global_separator_color;
        global $icon_braxidentity2;
        global $icon_braxsettings2;
        global $global_titlebar_color;
        global $global_background;
        global $icon_braxphoto2;
        global $global_activetextcolor;
        global $iconsource_braxstopmusic_common;
        global $iconsource_braxhelp_common;
        global $menu_myprofileandfiles;
        global $menu_restart;
        global $menu_logout;
        global $menu_changepassword;
        global $menu_techsupport;
        global $menu_techsupportfaq;
        global $menu_myaccountinfo;
        global $menu_colortheme;
        global $menu_language;
        global $menu_communitylist;
        global $menu_termsofuse;
        global $menu_privacy;
        global $menu_tokenactivity;
        global $menu_tokenstore;
        global $menu_deviceinfo;
        global $menu_managesocialvision;
        global $menu_upgrade;
        global $menu_manageupgrade;
        global $menu_myprofile;
        global $customsite;
        global $menu_myprofile;
        global $menu_managerooms;
        global $enterpriseapp;
        global $iconsource_braxrestart_common;
        global $iconsource_braxlogout_common;
        global $iconsource_braxlock_common;
        global $installfolder;
        global $rootserver;
        
        $appstore = false;
        $appstorenew = false;
        if($version!='' && $version !='000'){
            $appstore = true;
        }
        if($version>='200'){
            $appstorenew = true;
        }

        $buttonbackgroundcolor = "#a1a1a4"; 
        $buttoncolor = "white"; 

        $buttonbackgroundcolor2 = "$global_titlebar_color";//#df1463";
        $buttoncolor2 = "white";

        $buttonbackgroundcolor3 = "#a1a1a4";
        $buttoncolor3 = "white";
        
        $settingsmenu = "
                            <img class='nonmobile audiokillsound tilebutton icon30' src='$iconsource_braxstopmusic_common' style='float:right;margin-right:20px' title='Stop Audio' />
                            <div 
                             style='background-color:transparent;margin:auto;text-align:center;max-width:500px;width:90%;min-width:70%;vertical-align:top'>
                        ";

        //$settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
        
        $settingsmenu .= "<br>";
        $settingsmenu .= "<div class='mainfont restart' data-caller='none' style='cursor:pointer;color:$global_activetextcolor;padding-left:20px;float:left'><img class='icon30' src='$iconsource_braxrestart_common' style='position:relative' /><br><br>$menu_restart</div>";
        $settingsmenu .= "<div class='mainfont logoutbutton' style='margin-left:20px;cursor:pointer;color:$global_activetextcolor;padding-left:20px;float:left'><img class='icon30' src='$iconsource_braxlogout_common' style='position:relative'  /><br><br>$menu_logout</div>";
        if(isset($_SESSION['pin']) && $_SESSION['pin']!=''){
        $settingsmenu .= "<div class='mainfont pinlock closesidemenu' style='margin-left:20px;cursor:pointer;color:$global_activetextcolor;padding-left:20px;float:left'><img class='icon30' src='$iconsource_braxlock_common' style='position:relative'  /><br><br>Lock</div>";
        }
        $settingsmenu .= "<br><br><br><br><br>";
                
        //$settingsmenu .= SettingsMenuButton("$icon_braxlive2 Live Streams", "selectchatlist mainbutton", "","data-mode='LIVE'","text-align:left" , "#3b3b3b", $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxidentity2 $menu_myaccountinfo", "profilebutton mainbutton", "","","text-align:left;" , "#3b3b3b", $buttoncolor2);
        $action = "feed";
        if(intval($_SESSION['profileroomid'])==0){
            $action = "userview";
        }
        $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxphoto2 $menu_myprofile", "$action mainbutton", "","data-providerid='$_SESSION[pid]' data-roomid='$_SESSION[profileroomid]' data-caller='none' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        if(!$customsite){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_language", "languagechoice mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        }
        $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_colortheme", "colorchoice mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        
        if($_SESSION['roomcreator']=='Y' ){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_managerooms", "friends mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_communitylist", "groupmanage mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 Brax.Live Restream", "restreambutton mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        }

        if($_SESSION['superadmin']=='Y'){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_managesocialvision", "sponsormanage mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        } else
        if( $_SESSION['web']=='Y'){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 My $enterpriseapp Domain", "sponsormanage mainbutton", "","data-mode='E' data-sponsor='$_SESSION[sponsor]' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            
        };
        
        

        $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
        

        $settingsmenu .= SettingsMenuButton("$menu_changepassword", "chgpasswordbutton settingsaction", "changepasswordbutton","","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= SettingsMenuButton("Set Up an Authenticator App", "chgtotp settingsaction", "changetotp","","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= SettingsMenuButton("$menu_techsupport", "selectchattech mainbutton", "","","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= SettingsMenuButton("$menu_techsupportfaq", "roomjoin mainbutton", "","data-mode='J' data-handle='#techsupport' ","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";

        
        $settingsmenu .= SettingsMenuButton("$menu_termsofuse", "termsofusedisplay mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("$menu_privacy", "privacydisplay mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        
        
        
        //$settingsmenu .= SettingsMenuButton("App Privacy Tips", "privacytip mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("$menu_deviceinfo", "statsuser mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        if($_SESSION['superadmin']=='Y'){
            $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
        }

        if($_SESSION['superadmin']=='Y' || $_SESSION['superadmin']=='A' || $_SESSION['superadmin']=='E' ){
            $settingsmenu .= SettingsMenuButton("Access Report", "report1 mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
        }

        if($_SESSION['superadmin']=='Y' ){
            $settingsmenu .= SettingsMenuButton("Executive Stats", "stats mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
        }
        $settingsmenu .= "<br></div>";

        if($_SESSION['superadmin']=='Y' && !$customsite){
            $settingsmenu .=
                    "<br><br><br><a href='https://brax.me/error.php'>Error Test</a><br><br><br>";
        }

        
        return $settingsmenu;
    }
    function SettingsMenuButton($title, $class, $id, $data, $style, $backgroundcolor, $color)
    {
        $button = "
            <div class='pagetitle3 divbuttontilebar2 tapped2 $class' id='$id' $data
                style='background-color:$backgroundcolor;color:$color;$style'>
                                        $title
            </div>
            ";
        return $button;
    }
