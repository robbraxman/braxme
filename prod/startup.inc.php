<?php
require_once("config-pdo.php");
require_once("room.inc.php");
require_once("crypt-pdo.inc.php");
require_once("notify.inc.php");
require_once("roommanage.inc.php");
require_once("advertising.inc.php");

    $_SESSION['validsession']=uniqid();
    
    //just a flag that means troll restricted
    if($_SESSION['hgtx']=='1'){
        echo "App Crashed.<br>";
        exit();
    }
    
    //Enforcing only for enterprise
   if($_SESSION['enterprise']=='Y' && $_SESSION['superadmin']!='Y'){
        //if(!AccountStatusCheck($_SESSION['pid'])){
        //
        //    exit();
        //}
    }
    $_SESSION['needsms']="";

    $recentrooms = RecentRooms($providerid,"2", TRUE);
    $roommanagemenu = RoomManageMenu();
    $_SESSION['iscore']=5;
    $_SESSION['innerwidth']=0;


    $_SESSION['source']=@tvalidator("PURIFY",$_POST['source']);
    if( $_SESSION['source']=='app' || $_SESSION['source']=='android'){
    
        $source = 'Y';
        pdo_query("1","update provider set mobile=? where providerid=? ",array($source,$_SESSION['pid']));
    }

    $timezone = @tvalidator("PURIFY", $_POST['timezone']);
    if($timezone!==''){
        $_SESSION['timezone']=$timezone;
        $_SESSION['timezoneoffset'] = floatval($_SESSION['timezone']) - floatval($_SESSION['servertimezone']);
    } else {
        $_SESSION['timezone']="0";
        $_SESSION['timezoneoffset'] = "0";
    }
    $today = date("M-d-y",time()+$_SESSION['timezone']*60*60);

    $useragent=@tvalidator("PURIFY",$_POST['useragent']);
    if( $useragent!='' ){ 
    
        pdo_query("1","update provider set useragent=? where providerid=? ",array($useragent,$_SESSION['pid']));
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
    
        
        pdo_query("1","
            insert into statusroom (providerid, roomid, room, owner, createdate, creatorid )

            select distinct ? as providerid, invites.roomid, roominfo.room, statusroom.owner, 
                 now(), invites.providerid from invites
            left join statusroom on invites.roomid = statusroom.roomid
            and statusroom.owner = invites.providerid
            left join roominfo on invites.roomid = roominfo.roomid
            where invites.email in 
            (select replyemail from provider where providerid=?)
            and invites.status = 'Y' and invites.roomid > 0 
            and invites.roomid not in (select roomid from statusroom
            where providerid=?)

            ",array($providerid,$providerid,$providerid));
        
          
         
        //Automatically Connect to Chat Session from Invite
        $result = pdo_query("1","
            select chatid, providerid 
            from invites 
            where exists 
            (select *
                from provider where providerid = ?
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
            ",array($providerid));
        
        while( $row = pdo_fetch($result) ){
        
            $invitechatid = $row['chatid'];
            $inviteownerid = "$row[providerid]";
            
            //Automatically Connect to Chat Session from Invite
            pdo_query("1","
                insert into chatmembers 
                ( chatid, providerid, status, lastactive, techsupport ) 
                values 
                ( ?, ?, 'Y', now(), '' )
                ",array($invitechatid,$providerid));
            
            $encodeshort = EncryptChat("Please read your chat message",$invitechatid, "");
            ChatNotificationRequest($inviteownerid, $invitechatid, $encodeshort, $_SESSION['responseencoding'],'');
            
        }    
        //Remove prior Chat Invites
        pdo_query("1","
            delete from invites where chatid > 0 and exists (
                select * from provider where
                (
                    (provider.replyemail= invites.email and invites.email!='')
                    or
                    (provider.handle = invites.handle and invites.handle!='')
                )
                and provider.providerid = ?
            )
            ",array($providerid));

    
        //Figure Out User Level of Use
        $_SESSION['photouser'] = '';
        $_SESSION['roomuser'] = '';
        $_SESSION['roommember'] = '';
        $_SESSION['chatuser'] = '';
        $_SESSION['fbshared'] = '';
        $_SESSION['contacts'] = '';
        
        
        //Automatically Share Room Contacts
        /*
        pdo_query("1","
                    insert into batchrequest ( providerid, requestdate, requesttype, status ) 
                    values ($providerid, now(), 'SHARECONTACTSROOM', 'N' )

            ");
         * 
         */
        


        
    $_SESSION['inforequest']= ActiveInformationRequest($providerid);
        
        
        
    $apn = @tvalidator("PURIFYHANDLE", $_POST['apn'] );
    $gcm = @tvalidator("PURIFYHANDLE", $_POST['gcm'] );
    $uuid = @tvalidator("PURIFYHANDLE", $_POST['uuid'] );
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
        
            @pdo_query("1"," 
                delete from notifytokens where token = ? and providerid= ? and status='E' and app=?
                    ",array($token,$providerid,$appname));
            @pdo_query("1"," 
                insert ignore into notifytokens 
                (providerid, token, platform, registered, status, arn, app) values
                (?, ?, ?,now(), 'Y','',?)
                    ",array($providerid,$token,$platform,$appname));
            @pdo_query("1"," 
                update notifytokens set registered=now() where providerid=? and token=? and app=?
                    ",array($providerid,$token, $appname));

            @pdo_query("1"," 
                delete from notifytokens where token = ? and providerid != ?  and app=?
                    ",array($token,$providerid,$appname));
            
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
        global $icon_braxdoc2;
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
        global $menu_myemailinfo;
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
        $settingsmenu .= "<div class='mainfont restarthome' data-caller='none' style='cursor:pointer;color:$global_activetextcolor;padding-left:20px;float:left'><img class='icon30' src='$iconsource_braxrestart_common' style='position:relative' /><br><br>$menu_restart</div>";
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
        
                    $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
        
        //$settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxphoto2 My Photos", "photolibrary mainbutton", "","","text-align:left;" , "#3b3b3b", $buttoncolor2);
        //$settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxdoc2 My Files", "doclib mainbutton", "","","text-align:left;" , "#3b3b3b", $buttoncolor2);
        if($_SESSION['email_service']=='Y'){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxidentity2 $menu_myemailinfo", "emailmanage mainbutton", "","data-providerid=$_SESSION[pid] data-mode='U' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        }
        //if($_SESSION['vpn_service']=='Y'){
        if($_SESSION['vpnaccountid']!=''){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxidentity2 My VPN Service", "vpnmanage mainbutton", "","data-accountid=$_SESSION[vpnaccountid] data-mode='U' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        }
        /*
        $settingsmenu .= SettingsMenuButton("$icon_braxdoc2 My Files", "doclib mainbutton", "","","text-align:left;" , "#3b3b3b", $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("$icon_braxsettings2 My Rooms", "friends mainbutton", "","","text-align:left;" , "#3b3b3b", $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("$icon_braxsettings2 My Contacts", "mainbutton", "contactbookbutton","","text-align:left;" , "#3b3b3b", $buttoncolor2);
         * */
        if($_SESSION['roomcreator']=='Y' ){
            //$settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_managerooms", "friends mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            //$settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_communitylist", "groupmanage mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            if($_SESSION['superadmin']=='Y'){
                //$settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 Brax.Live Restream", "restreambutton mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            }
        }

        if($_SESSION['superadmin']=='Y'){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 $menu_managesocialvision", "sponsormanage mainbutton", "","data-mode='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
        } else
        if( $_SESSION['web']=='Y'){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 My $enterpriseapp Domain", "sponsormanage mainbutton", "","data-mode='E' data-sponsor='$_SESSION[sponsor]' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            
        };
        if($_SESSION['enterprise']=='C'){
            if($_SESSION['sponsor']==''){
                $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 Join $entepriseapp", "sponsormanage mainbutton", "","data-mode='JOIN' data-sponsor='' ","text-align:left;" , "#3b3b3b", $buttoncolor2);
            }
            
        };
        if($_SESSION['enterprise']=='Y' && $_SESSION['store']=='Y'){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 My Store Setup", "productlist mainbutton", "","data-providerid='$_SESSION[pid]'","text-align:left;" , "#3b3b3b", $buttoncolor2);
        }
        if($_SESSION['allowiot']=='Y'  ){
            $settingsmenu .= SettingsMenuButton("&nbsp; $icon_braxsettings2 Secure Net", "homeiot mainbutton", "","","text-align:left;" , "#3b3b3b", $buttoncolor2);
        }
        
        

        $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
        
        //$settingsmenu .= SettingsMenuButton("Restart", "restart", "restart","","" , $buttonbackgroundcolor, $buttoncolor);
        //$settingsmenu .= SettingsMenuButton("Logout", "logoutbutton", "","","" , $buttonbackgroundcolor, $buttoncolor);
        //$settingsmenu .= SettingsMenuButton("Stop Audio", "audiokillsound tilebutton", "","","" , $buttonbackgroundcolor, $buttoncolor);
        

        $settingsmenu .= SettingsMenuButton("$menu_changepassword", "chgpasswordbutton settingsaction", "changepasswordbutton","","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= SettingsMenuButton("Set Up TOTP 2FA", "chgtotp settingsaction", "changetotp","","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= SettingsMenuButton("$menu_techsupport", "selectchattech mainbutton", "","","" , $buttonbackgroundcolor, $buttoncolor);
        $settingsmenu .= SettingsMenuButton("$menu_techsupportfaq", "roomselect mainbutton", "","data-mode='FAQ' data-handle='#techsupport' ","" , $buttonbackgroundcolor, $buttoncolor);


        $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";

        
        if( $_SESSION['handle']!='@appdemo'){

            if($_SESSION['roomdiscovery']!='N' || $_SESSION['enterprise']=='Y'){

                //$settingsmenu .= SettingsMenuButton("$menu_manageupgrade", "userstore mainbutton", "","data-roomid='' data-owner='0'  ","" , $buttonbackgroundcolor2, $buttoncolor2);
                //$settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";

            }
        } else {
            //ADVERTISING
        }
        
        
        
        if($_SESSION['enterprise']=='C' || $_SESSION['enterprise']=='Y' )//|| $_SESSION['superadmin']=='Y' )
        {
            if($_SESSION['sponsor']!=='' && ($_SESSION['industry']!=='' || $customsite ) ){
                $settingsmenu .= SettingsMenuButton("Manage eForms", "credentialformsetup mainbutton","", "data-mode='MANAGEFORMSMENU'","" , $buttonbackgroundcolor3, $buttoncolor3);
                $settingsmenu .= SettingsMenuButton("Direct Enterprise Member Signup", "signup mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
                $settingsmenu .= SettingsMenuButton("Staff Additional Logins", "stafflist mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
                $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
            }

            //$settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
            
            //$settingsmenu .= SettingsMenuButton("Export eForms Data", "credentialformsetup settingsbutton","", "data-mode='EXPORTFORM'","" , $buttonbackgroundcolor3, $buttoncolor3);
            //$settingsmenu .= SettingsMenuButton("Clear eForms Data", "credentialformsetup mainbutton","", "data-mode='CLEARFORMDATA'","" , $buttonbackgroundcolor3, $buttoncolor3);
        }


        
        
        $settingsmenu .= SettingsMenuButton("$menu_termsofuse", "termsofusedisplay mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("$menu_privacy", "privacydisplay mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        
        
        
        //$settingsmenu .= SettingsMenuButton("App Privacy Tips", "privacytip mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        $settingsmenu .= SettingsMenuButton("$menu_deviceinfo", "statsuser mainbutton", "","","" , $buttonbackgroundcolor2, $buttoncolor2);
        if($_SESSION['superadmin']=='Y'){
            $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
        }
            
        $settingsmenu .= BytzVPNAd();


        if($appstorenew){
            if(!$customsite){
                //$settingsmenu .= SettingsMenuButton("Cert MITM Check", "certcheck mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
            }
        }
        if($_SESSION['superadmin']=='Y'){
            $settingsmenu .= SettingsMenuButton("Manage VPN", "vpnlist mainbutton", "","data-providerid='$_SESSION[pid]'","" , $buttonbackgroundcolor2, $buttoncolor3);
            $settingsmenu .= SettingsMenuButton("Manage Email", "emaillist mainbutton", "","data-providerid='$_SESSION[pid]'","" , $buttonbackgroundcolor2, $buttoncolor3);
            $settingsmenu .= SettingsMenuButton("&nbsp; Brax.Me Store Setup", "productlist mainbutton", "","data-providerid='0'","" , $buttonbackgroundcolor3, $buttoncolor2);
        }
        if($_SESSION['superadmin']=='Y' || $_SESSION['superadmin']=='A' || $_SESSION['superadmin']=='E' ){
            $settingsmenu .= SettingsMenuButton("Access Report", "report1 mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
        }
        if($_SESSION['superadmin']=='Y'  )
        {
            $settingsmenu .= SettingsMenuButton("Admin - Upload Batch Signup File", "uploadsignupcsv mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
            $settingsmenu .= SettingsMenuButton("Upload Text CSV", "uploadtextcsv mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
        }
        
        if($_SESSION['superadmin']=='Y' ){

            $settingsmenu .= "<hr style='border:1px solid  $global_separator_color'>";
            $settingsmenu .= SettingsMenuButton("Test Zone", "pinentry mainbutton", "","data-providerid='$_SESSION[pid]'","" , $buttonbackgroundcolor3, $buttoncolor3);
            $settingsmenu .= SettingsMenuButton("Test Zone Restream", "restreambutton mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
            $settingsmenu .= SettingsMenuButton("Test Link", "testlink mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
            $settingsmenu .= "
                            <a href='https://brax.me/rss/admin/index.php'>
                            <div class='pagetitle3 divbuttontilebar2 stats mainbutton tapped2'  style='background-color:$buttonbackgroundcolor3;color:$buttoncolor3'>
                            RSS    
                            </div>
                            </a>

                    ";
            $settingsmenu .= SettingsMenuButton("Super Stats", "statsplus mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
        }
        if($_SESSION['superadmin']=='Y' ){
            $settingsmenu .= SettingsMenuButton("Executive Stats", "stats mainbutton", "","","" , $buttonbackgroundcolor3, $buttoncolor3);
        }
        $settingsmenu .= "<br></div>";

        if($_SESSION['superadmin']=='Y' ){
            $settingsmenu .=
                    "<br><br><br><a href='https://brax.me/error.php'>Error Test</a><br><br>".
                    "<br><div class='admintrace'>Admin Trace</div>".
                    "<br><div class='admintrace2'>Admin Trace2</div>".
                    "<br><div class='admintrace3'>Admin Trace3</div>";
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
