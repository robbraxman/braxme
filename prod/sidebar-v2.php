<?php
session_start();
require_once("config-pdo.php");
require_once("room.inc.php");
require_once("sidebar.inc.php");
require_once("internationalization.php");
require_once("roomselect.inc.php");
require_once("sponsorhome.inc.php");
require("nohost.php");


        $alertstatus = "";
        $meetupstatus = "";
        $chatblink = "";
        $roomblink = "";
        $confirmage = "";
        $avatarhtml = "";
        $chathtml = "";
        $chathtml_tile = "";
        $imaphtml = "";
        $actionitems = "";
        $tilemailalert = "";
        $tileroomalert = "";
        $meetupalert = "";
        $roomalert = "";
        $unsel = "";
        $alarms=0;
        $posthtml = "";
        $adminstuff = "";
        $phototask = "photolibrary";
        $homeroomid = "";
        $sponsorlive = "1";
        $homepage = "";
        $about = "about";
        $tourtitle = "$menu_platformtour";
        $braxtips = 'N';
        $roomdiscovery = "";
        $joinedvia = "";
        $sponsorlogo = '';
        $sponsorroomhashtag = "";
        $sponsorformat = "";
        $sizing = "";
        $lasttip = "";
        $notifypretext = "";
        $notifytext = "";
        $tileview2 = '';
        $tileview = "";
        $sidebar = "";
        $sidebarmenu = "";
        $tour = "";
        $notifytitle = "";
        

        $flag = $global_icon_check;//"<img class='chatalert icon15' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";
        $flagblink = $global_icon_check_blink;//"<img class='chatalert icon15' title='Checked' src='../img/check-yellow-128.png' style='padding-top:2px;padding-right:2px;padding-bottom:0px;' />";

        
        $backgroundcolorside2 = "$global_menu2_color";//$global_color_menu;
        $backgroundcolorside = $global_menu_color;

        
        
        if(isset($_SESSION['sponsorname']) ){
            $homepage = ucfirst("$_SESSION[sponsorname]");
        }

           

        if( (!isset($_SESSION['pid']) || $_SESSION['pid']=='') && 
            (!isset($_SESSION['reset']) )
          ){ //Invalid Session
        

            $arr = array('sidebar'=> "Timeout",
                        'tileview'=> "Timeout",
                        'settingsview'=> "",
                        'roomsview'=> "",
                        'notification'=>"",
                         'alarm'=> "T",
                         'status'=> "Invalid Session"
                        );


            echo json_encode($arr);
            exit();

        }
        if(!isset($_POST['providerid']) || !isset($_SESSION['pid'])){
        
            $arr = array('sidebar'=> "No Data",
                        'tileview'=> "Timeout",
                        'settingsview'=> "",
                        'roomsview'=> "",
                        'notification'=>"",
                         'alarm'=> "T",
                         'status'=> "Invalid Session"
                        );

            echo json_encode($arr);
            exit();
            
        }
        
        if( (!isset($_SESSION['enterprise']) || $_SESSION['handle']=='')){
            //exit();
        }
        $loginuser = substr("$_SESSION[providername]",0,30);
        $companyname = @substr($_SESSION['companyname'],0,30);
        if($_SESSION['loginid']!='admin'){

            $loginuser .= "<br>$_SESSION[staff]";
            $loginuser .= "<br>$_SESSION[handle]";
        } else {

            $loginuser .= "<br>$_SESSION[handle]";
            if($companyname!=''){
                //$loginuser .= "<br>$companyname";
            }
            
        }
        
        $startup = @tvalidator("PURIFY",$_POST['startup']);
        $providerid = tvalidator("ID",$_SESSION['pid']);
        $devicecode = tvalidator("PURIFY",$_POST['devicecode']);
        $chatid = tvalidator("ID",$_POST['chatid']);
        $_SESSION['iscore'] = @tvalidator("PURIFY",$_POST['iscore']);
        
        $_SESSION['devicecode'] = $devicecode;
        
        if( $_SESSION['mobilesize']=='Y' ){
            
            $innerheight = @tvalidator("PURIFY",$_SESSION['innerheight']);
            $innerwidth = @tvalidator("PURIFY",$_SESSION['innerwidth']);
            $pixelratio = @tvalidator("PURIFY",$_SESSION['pixelratio']);
            $devicecode = @tvalidator("PURIFY",$_SESSION['devicecode']);

            if($innerheight!='' && $innerwidth!=''){
                
                pdo_query("1"," 
                    update provider set 
                    deviceheight=?, 
                    devicewidth= ?,  
                    pixelratio= ?,
                    devicecode = ?
                    where providerid =$_SESSION[pid]
                    ",array($innerheight,$innerwidth,$pixelratio,$devicecode));
            }
        }
        
        

        if( $_SESSION['pid']!= $providerid ){
        
            $arr = array('sidebar'=> "Timeout",
                        'tileview'=> "Timeout",
                        'settingsview'=> "",
                        'roomsview'=> "",
                        'notification'=>"",
                         'alarm'=> "T",
                         'status'=> "Invalid Session"
                        );

            echo json_encode($arr);
            exit();
            
        }
        
        
        
        if(isset($_SESSION['sizing'])){
            $sizing = $_SESSION['sizing'];
        }
    



        //Don't Flag lastnotified if chatid active
        if(intval($chatid) == 0){
            $notificationstatus = NotificationStatus($providerid, true);
        } else {
            $notificationstatus = NotificationStatus($providerid, false);
        }
        if($startup=='true'){
            $notificationstatus = 'Y';
        }
        if( $customsite == false){ 
            $radiostatus = RadioStatus($providerid);
            $alertlive = "";
            if($radiostatus!=''){
                $alertlive = $flag;
            }
        }
        $chatalert = false;
        $alertchat = "";
        if(ChatStatus($providerid)=='Y'){
            $chatblink ="blink";
            $chatalert = true;
            $alertchat = "$flagblink";
        }
        $alertroom = "";
        if(RoomStatus($providerid)=='Y'){
            $alertroom = "$flag";
        }        

        
        $id = $_SESSION['handle'];
        if($id =='') {
            $id = $_SESSION['replyemail'];
        }

        
        
        $result2 = pdo_query("1","
                select roomdiscovery, joinedvia, lasttip,
                (select 'Y' from statusroom where roomid=12802 and statusroom.providerid = provider.providerid ) as braxtips 
                from provider where providerid = ?
                ",array($providerid));
        if( $row2 = pdo_fetch($result2)){
            //$logo = "Sponsored by<br><img src='../img/dteenergy-logo.png' style='height:80px;max-width:80%'/>";
            $braxtips = $row2['braxtips'];
            $roomdiscovery = $row2['roomdiscovery'];
            $joinedvia = $row2['joinedvia'];
            $lasttip = $row2['lasttip'];
        }
        
        if( !$customsite && $_SESSION['enterprise']=='Y' ){
            $logo = "";
            if($_SESSION['companyname']!=''){
                $promo = "<p class='smalltext2'>$enterpriseapp - $_SESSION[companyname]</p>";
            } else { 
                $promo = "<p class='smalltext2'>$enterpriseapp  $_SESSION[sponsor]</p>";
                $enterprisetitle = "$enterpriseapp";
            }
            
            
            
            
        } else
        if( $_SESSION['enterprise']=='C' ){
            $logo = "";
            
            $promo = "<p class='smalltext2'>Commercial Account</p>";
            $enterprisetitle = "Commercial";
            
        } else {
            $enterprisetitle = "";
            $logo = "";
            $promo = "";//<p class='smalltext2'>Proudly made and encrypted in the USA by US Citizens</p>";
        }


        
        if(strtolower($_SESSION['sponsor'])!=''){
            
            $result2 = pdo_query("1","
                    select logo, boxcolor, partitioned, roomid, roomhashtag, format,
                    live  from sponsor where sponsor = '$_SESSION[sponsor]' ",null);
            if( $row2 = pdo_fetch($result2)){
                //$logo = "Sponsored by<br><img src='../img/dteenergy-logo.png' style='height:80px;max-width:80%'/>";
                $boxcolor = "$row2[boxcolor]";      
                $homeroomid = $row2['roomid'];
                if($row2['partitioned']=='Y'){
                    $sponsorlogo = $row2['logo'];
                    $logo = "<div class='smalltext2 gridnoborder' style='text-align:center;vertical-align:center;overflow:hidden;width:100%;background-color:$boxcolor'>".
                                "<div class='blink smalltext' style='float:right;padding-top:5px;padding-right:10px;color:white;width:100%;text-align:right' ></div>".
                                "<img src='$row2[logo]' style='max-height:50px;padding-bottom:10px' />".
                            "</div>";
                    if($homeroomid!=''){
                        //$logo = "<span class='feed mainbutton' data-readonly='N' data-mode='HOME' data-roomid='$homeroomid' style='cursor:pointer' >".$logo."</span>";
                    }
                }
                $sponsorlive = "$row2[live]";
                $promo = "";
                $sponsorformat = $row2['format'];
                if($sponsorformat!=''){
                    $sponsorroomhashtag = $row2['roomhashtag'];
                }
            }
                          
        }
       
        if($sponsorroomhashtag=='' && !$customsite && $_SESSION['newbie']=='Y'){
            if($lasttip == '0' || $lasttip == ''){
                //$sponsorroomhashtag = '#userbasics';
            }
            if($lasttip == '1'){
                //$sponsorroomhashtag = '#userbasics2';
            }
            if($lasttip == '2'){
                //$sponsorroomhashtag = '#userbasics3';
            }
        }
        
        
        $tour = "";
        $touragent = "";
        if(false){//$braxtips !='Y'){
            $tour .= "
                   <br>
                   <div class='$_SESSION[profileaction] pagetitle2 mainbutton' 
                    data-roomid='$_SESSION[profileroomid]'
                    data-providerid=$providerid data-caller='home'
                    data-profile='Y'
                    style='cursor:pointer;color:$global_textcolor;margin-bottom:10px'>
                    $global_icon_check $menu_myprofile 
                   </div>
                  ";
            /*
            if(!$customsite){
            $tour .= "
                   <div class='roomjoin pagetitle2 mainbutton' 
                    data-handle='#userbasics' data-mode='J' data-caller='home'
                    style='cursor:pointer;color:$global_textcolor;margin-bottom:10px'>
                    $global_icon_check User Tips
                   </div>
                  ";
            }
            */
            
        }
        if($homeroomid=='' && $_SESSION['enterprise']=='Y'){
            

            if($_SESSION['sponsorcount']==0 && $_SESSION['web']=='Y'){
                $tour .= "<div class='pagetitle2 sponsormanage mainbutton' 
                        data-mode='E' data-sponsor=''
                        style='cursor:pointer;color:$global_textcolor'>
                        $global_icon_check Create $enterpriseapp Domain
                      </div>";
            }
            
            
        }
        
        if($tour!=''){
            $tour .= "<br><br>";
        }
        
        $touragent = "
            <br><br>
            <div class='mainbutton roomjoin pagetitle2 tapped' data-mode='J' data-handle='#userbasics' data-roomid='12802' style='width:100%;cursor:pointer;color:$global_activetextcolor' title='Quick Tour for New Users'>
                <table class='gridnoborder' style='vertical-align:top;text-align:left;width:100%'>
                    <tr>
                        <td>
                        </td>
                        <td class='pagetitle' style='color:$global_activetextcolor;text-align:center'>
                            $tourtitle
                            <div class='circular3 gridnoborder' style='margin:auto;overflow:hidden' >
                                <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
         * ";
 
        $footer = "     
                <div style='float:left;width:100%'>
                <br>
                <br>
                ";
        $footer_platformtour = $menu_platformtour;
        $footer_termsofuse = $menu_termsofuse;
        $footer_privacy = $menu_privacy;
        $footer_techsupport = $menu_techsupport;
        $footer_language = $menu_language;
        
        $footer .= "     
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                ";
        if($_SESSION['web']=='Y'){
            $footer .= "     
                <div class='mainbutton sponsormanage pagetitle2 tapped' data-mode='CHECKLIST' style='padding-left:20px;cursor:pointer;color:$global_activetextcolor'>$enterpriseapp Check List</div>
                <br>
                    ";
        }
        if($customsite == false){
            $footer .= "     
                <div class='mainbutton languagechoice pagetitle2 tapped' data-mode=''  style='padding-left:20px;cursor:pointer;color:$global_activetextcolor'>$footer_language</div>
                <br>
                ";
            $footer .= "     
                    <div class='mainbutton roomjoin pagetitle2 tapped' data-mode='J' data-handle='#userbasics' data-roomid='12802' data-caller='home' style='padding-left:20px;cursor:pointer;color:$global_activetextcolor'>$footer_platformtour</div>
                    <br>
                    ";
            $footer .= "     
                    <div class='mainbutton selectchattech pagetitle2 tapped' data-handle='@robbraxman' data-mode='' style='padding-left:20px;cursor:pointer;color:$global_activetextcolor'>$footer_techsupport</div>
                    <br>
                    ";
        }
        /*
        $footer .= "     
                <div class='mainbutton termsofusedisplay pagetitle3 tapped' style='cursor:pointer;color:$global_activetextcolor'>$footer_termsofuse</div>
                <br>
                <div class='mainbutton privacydisplay pagetitle3 tapped' style='cursor:pointer;color:$global_activetextcolor'>$footer_privacy</div>
                <br>
                <br>
                ";
         * 
         */
        $footer .= "</div>";
        
        $notifytitle = "    <span class='formobile'>
                                <div class='pagetitle' style='color:$global_textcolor'>$menu_activity
                                    &nbsp;&nbsp;
                                    <img class='icon20 notifyclear' src='$iconsource_braxclose_common' style='cursor:pointer;padding-top:10px;' title='Clear Notifications' />
                                </div>
                                <hr style='border:1px solid $global_separator_color;margin-top:10px'>
                            </span>   
                            <span class='nonmobile'>                            
                                <img class='icon20 notifyclear' src='$iconsource_braxclose_common' style='cursor:pointer;padding-top:10px;' title='Clear Notifications' />
                                <br><br>
                            </span>
                            
                        ";        
        
        $swipemsg = "<div class='pagetitle3 gridnoborder rounded shadow' 
                        style='background-color:$global_background;padding-top:20px;max-width:600px;
                        padding-left:20px;padding-right:20px;padding-bottom:20px;
                        text-align:left;color:$global_textcolor;margin-left:auto;margin-right:auto;margin-bottom:10px;max-width:90%'> 
                        <b>Swipe Right to see the Menu from anywhere. Try it now!</b>
                    </div>";

        
        $notifytext = GetNotifications($providerid);
        if($notifytext!==''){
            $swipemsg = "";
        }
        /*
        if($sponsorroomhashtag == '' ){
            $tileview2 = '';
        } else {
            
            $tour = "";
            $notifytitle = "";
            //$tileview2 = GetSponsorHome($sponsorroomhashtag);
            //$notifytext .= $tileview2;
        }
        if($notifytext == ""){
            if($roomdiscovery !='N'){
                $touragent = '';
            }
            $notifytext = $notifypretext.$tour.$touragent;
            $notifytitle = '';
        } else {
            $notifytext = $notifypretext.$tour.$notifytext."";
        }
         * 
         */
        if( $_SESSION['enterprise']!='Y' ){
            //New User
            $notifytext .= SetProfileReminder($providerid,"<br>","");
        }
        $notifytext .= $footer;
        
        

        $sidemenu = GenerateMenu();

        
        $displayedlogo = $applogo;
        if($sponsorlogo!=''){
            $displayedlogo = $sponsorlogo;
        }
        $logoarea = "<center class='smalltext restarthome' style='cursor:pointer'><img src='$displayedlogo' style='max-height:50px;max-width:200px;padding-top:20px;padding-bottom:0;margin-bottom:0' /><br><span class='smalltext' style='color:$global_menu_text_color'>Restart</span></center> ";
        
        $sidebar = "
                    <div class='sidebarfont' style='width:250px;'>
                        $sidemenu
                        <br><br>
                    </div>


                </div>
            ";
        
        
        
        
        
        $tileview =
            "
            <span class='formobile'>
                <table class='sidebarfont gridnoborder'  style='border:0px;min-width:320px;width:100%;overflow:auto;background-color:transparent'>
                    <tbody class='gridnoborder'>
                    <tr class='opensidemenu gridnoborder' style='border:0;overflow:hidden'>
                        <td class='gridnoborder sidebararea2' style='opacity:0.8;vertical-align:top;color:white;background-color:transparent;padding:0;margin:0px;width:100%' valign='top'>
                            $logo
                            <div class='sidebar2 mainfont gridnoborder'  style=''>
                                <div class='sidebaralerts2' style=''>
                                    <span class='sidebarfont' >
                                        <div class='pagetitle2a' style='background-color:transparent;color:$global_textcolor;;padding-top:5px;'>
                                            $enterprisetitle
                                        </div>
                                    </span>
                                    <br>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class='gridnoborder' style='background-color:transparent;color:$global_textcolor;margin:0;padding:0;max-width:100%;overflow:hidden'>
                        <td style='background-color:transparent;vertical-align:top;max-width:100%;overflow:hidden;padding:0'>
                            <div class='' style='background-color:transparent;padding:10px;margin-right:0px'>
                                $notifytitle
                                $promo
                                $swipemsg
                                <div class='sidebarmessage' style=''></div>    
                                $notifytext
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class='smalltext2' style='padding:20px;color:$global_textcolor'><b>Powered by $appname $enterpriseapp</b></div>
                <!--
                <span class=smalltext>$_SESSION[deviceid]/$_SESSION[mobilesize]/$_SESSION[mobiletype] - $sizing $_SESSION[uuid] tz: $_SESSION[timezoneoffset] $_SESSION[loginid] info: $_SESSION[inforequest] </span>
                 <br><div class='smalltext devicelevel' style='float:right;margin-right:10px'>InternetSpeedScore=$_SESSION[iscore]</div><br><br><br>
               -->
            </span>
            ";

        
        $tileview .="
            <span class='nonmobile'>
                
                <table class='sidebarfont gridnoborder'  style='background-color:transparent;color:$global_textcolor;height:100%;margin:0;max-width:100%;overflow:hidden'>
                    <tr style='max-width:100%;overflow:hidden'>
                        <td class='sidebararea2 gridnoborder' style='color:white;background-color:$backgroundcolorside;padding:0px;margin:0;width:250px;max-width:100%' valign='top'>
                            <div class='sidebar2 mainfont'  style='padding-top:10px;'>
                                <center>
                                    <img class='$_SESSION[profileaction] mainbutton circular3 gridnoborder' alt='Change profile picture' 
                                       title='Click to change profile picture' 
                                       data-roomid='$_SESSION[profileroomid]'
                                       data-providerid=$providerid data-caller='none'
                                       data-profile='Y'
                                           
                                       style='cursor:pointer;margin-left:10px;margin-top:0px;display:block' src='$_SESSION[avatarurl]' />

                                    <div class='smalltext2' style='color:$global_menu_text_color;padding-left:10px;padding-top:3px'>$loginuser</div>
                                </center>
                                <div class='sidebaralerts2 sidebarfont' style=''>
                                    <br>
                                    $sidemenu
                                    <br><br>
                                    
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    
                                </div>
                            </div>
                        </td>
                        <td class='sidebararea2 gridnoborder' style='vertical-align:top;margin-right:0px;width:100%;padding:0'>
                            $logo
                            <div class='pagetitle2 gridnoborder' 
                                style='background-color:transparent;padding-top:10px;
                                padding-left:20px;padding-bottom:5px;
                                text-align:left;color:$global_textcolor;margin:0'> 
                                $menu_activity
                            </div>
                            
                            
                            <div class='' style='position:relative;background-color:transparent;padding-left:20px;padding-right:20px;margin-right:10px'>
                                <img class='icon20 roomjoin mainbutton' 
                                                data-handle='#userbasics' data-mode='J'
                                                src='$iconsource_braxhelp_common' style='cursor:pointer;float:right;margin-right:20px'/>
                                <img class='audiokillsound tilebutton icon20' src='$iconsource_braxstopmusic_common' style='cursor:pointer;float:right;margin-right:20px' title='Stop Audio' />
                                    $notifytitle
                                    $promo
                                <div class='sidebarmessage' style=''></div>        
                                $notifytext
                            </div>
                        </td>
                    </tr>
                </table>
                <div class='smalltext2' style='padding-left:20px;padding-top:20px;color:$global_textcolor'><b>Powered by $appname $enterpriseapp</b></div>
                <div class='smalltext2' style='padding-left:20px;padding-bottom:20px;color:$global_textcolor'>HTTPS Certificate is issued by Let's Encrypt Authority X3. If this is different then there may be a Man-in-the-Middle-- $lasttip</div> 
            </span>
            ";
     
        $settingsview = "";
    
        
        $alarmstatus = "";
        if( $alarms > 0) {
            $alarmstatus='Y';
        }
        $roomalertflag = false;
        if($roomalert=='Y'){
            $roomalertflag =true;
        }

        
        
        if($startup=='true'){
            $notificationstatus = "Y";
        }
        if($notificationstatus==''){
                $sidebar = "";
                $tileview = "";
            if($_SESSION['mobilesize']=='Y'){
            } else {
                
            }
        }
        $notificationstatus='Y';
        $alertmessage = "";
        
    
        $arr = array('logo'=> "$logoarea",
                     'sidebar'=> "$sidebar",
                     'tileview'=> "$tileview",
                     'settingsview'=> "",
                     'roomsview'=> "",
                     'executescript' => "",
                     'alarm'=> "$alarmstatus",
                     'status'=> "$alertstatus",
                     'room'=> "$roomalertflag",
                     'notification'=>"$notificationstatus",
                     'alertlive' =>"$alertlive",
                     'alertchat' =>"$alertchat",
                     'alertroom' =>"$alertroom",
                     'alertmessage' =>"$alertmessage"
                     
                    );


        echo json_encode($arr);
        exit();

        function MenuItem( $style, $icon, $title, $alert, $class, $datastring, $seq, $bold  )
        {
            global $global_menu_text_color;
            
            $prebold = '';
            $postbold = '';
            if($bold){
                $prebold = "<b>";
                $postbold = "</b>";
            }
            if($style=='T'){
                if($seq=='1'){
                    $width='width=40%';
                } else {
                    $width = '';
                }
                $menu = "
                <div class='$class tapped smalltext noselect' title='$title' $datastring 
                        style='margin-bottom:5px;display:inline-block;$width;white-space:nowrap'>
                    <!--
                    <span class='featureheadsidebar' ></span>
                    -->
                    <span class='featureheadsidebar noselect'> 
                        <div class='mainfont  divbuttonsidebar divbuttonsidebar_unsel rounded noselect' style='padding-left:10px;padding-right:20px;color:$global_menu_text_color' > 
                            $icon&nbsp;&nbsp;
                            $title
                            <span class='alertlive'> 
                                $alert
                            </span>
                        </div>
                    </span>
                </div>
                ";
            }
            
            
            
            if($style=='S'){
                $menu = "
                <div class='$class tapped closesidemenu' title='$title' $datastring style='width:200px;white-space:nowrap'>
                    <span class='featureheadsidebar'> 
                        <div class='divbuttonsidebar divbuttonsidebar_unsel rounded' > 
                            $icon&nbsp;&nbsp;
                            <span class='mainfont' style='color:$global_menu_text_color'>
                            $prebold$title$postbold $alert
                            </span>
                            &nbsp;&nbsp;&nbsp;
                        </div>
                    </span>
                </div>
                ";
            }
            return $menu;
            
        }

        
        function GenerateMenu()
        {
            global $icon_braxlive;
            global $icon_braxchat;
            global $icon_braxroom;
            global $icon_braxphoto;
            global $icon_braxdoc;
            global $icon_braxpeople;
            global $icon_braxsettings;
            global $icon_braxidentity;
            global $icon_braxmenu;
            global $icon_braxsecurity;
            global $icon_braxstore;
            global $icon_braxfaq;
            

            $braxdoctor =    "<img class='icon30' src='../img/brax-doctor-round-white-128.png'  />";
            $braxreports =    "<img class='icon30' src='../img/brax-reports-round-white-128.png'  />";

            $braxnotifications =    $icon_braxmenu; //"<img class='icon30' src='../img/Bullets-128.png'  />";

            $braxlive =    $icon_braxlive;
            $braxchat =    $icon_braxchat;
            $braxrooms =   $icon_braxroom;
            $braxphotos =  $icon_braxphoto;
            $braxdocs =    $icon_braxdoc;
            $braxmeetup =   $icon_braxpeople;
            $braxsettings =   $icon_braxsettings;
            $braxidentity =   $icon_braxidentity;
            $braxsecurity =   $icon_braxsecurity;
            $braxfaq =   $icon_braxfaq;
            $braxstore = $icon_braxstore;
            $bold = true;

            global $customsite;
            global $sponsorlive;
            global $menu_home;
            global $menu_people;
            global $menu_live;
            global $menu_chats;
            global $menu_rooms;
            global $menu_settings;
            global $menu_myfiles;
            global $menu_myphotos;
            global $menu_store;
            global $menu_faq;
            global $alertlive;
            global $alertchat;
            global $alertroom;
            

            $sidemenu = MenuItem( "S", $braxnotifications, "$menu_home", "", "tilebutton", "", 2, false );
            $sidemenu .= "<br>";

            $sidemenu .= MenuItem( "S", $braxmeetup, "$menu_people", "", "meetuplist mainbutton", "", 2, $bold );
            $sidemenu .= MenuItem( "S", $braxchat, "$menu_chats", "$alertchat", "selectchatlist mainbutton", "data-mode='CHAT'", 3, $bold );
            $sidemenu .= MenuItem( "S", $braxrooms, "$menu_rooms", "$alertroom", "roomselect mainbutton", "data-mode='FEED' data-roomid='0'", 3, $bold );
            if($_SESSION['roomdiscovery']=='Y'){
                $sidemenu .= MenuItem( "S", $braxfaq, "$menu_faq", "", "roomselect mainbutton", "data-mode='FAQ' ", 3, false );
            }


            //$sidemenu .= "<br><br>";
            $sidemenu .= MenuItem( "S", $braxphotos, "$menu_myphotos", "", "photolibrary mainbutton", "", 2, false );
            $sidemenu .= MenuItem( "S", $braxdocs, "$menu_myfiles", "", "doclib mainbutton", "", 2, false );
            if($_SESSION['sponsor']==''){
                if( $customsite == false){ 
                //$sidemenu .= MenuItem( "S", $braxlive, "$menu_live", "$alertlive", "selectchatlist mainbutton", "data-mode='LIVE'", 1, $bold );
                }
            } else {
                if($sponsorlive == '1'){
                    if( $customsite == false){ 
                        //$sidemenu .= MenuItem( "S", $braxlive, "$menu_live", "$alertlive", "selectchatlist mainbutton", "data-mode='LIVE'", 1, $bold );
                    }
                }
                if($sponsorlive == '2'){
                    if( $customsite == false){ 
                        //$sidemenu .= MenuItem( "S", $braxlive, "$menu_live", "$alertlive", "selectchatlist mainbutton", "data-mode='LIVE'", 1, $bold );
                    }
                }

            }
            $sidemenu .= "<br><br>";
            if($_SESSION['roomdiscovery']=='Y' &&
              ( $_SESSION['sponsor']=='' || $_SESSION['sponsor']=='rob') ){
                $sidemenu .= MenuItem( "S", $braxstore, "$menu_store", "", "userstore mainbutton", "data-owner='690001027'", 1, $bold );
            } 
            
            

            $sidemenu .= MenuItem( "S", $braxsettings, "$menu_settings", "", "settingsbutton", "", 2, false );
            
            return $sidemenu;
        }
function SetProfileReminder($providerid, $preformat, $postformat)
{
    global $lock;
    global $rootserver;
    global $prodserver;
    global $global_textcolor;
    global $global_background;
    global $global_activetextcolor;
    global $iconsource_braxglobe_common;
    global $menu_trending;
    global $installfolder;
    global $customsite;
    global $global_icon_check;
    
    $list = "<br><br><br><br>";
    
    
    
    $list .=
   "<div class='pagetitle2' style='display:inline-block;margin-auto;width:90%;padding-left:20px;padding-right:20px;text-align:left;color:$global_textcolor;'>
        $preformat
        <div class='$_SESSION[profileaction] gridnoborder rounded mainfont mainbutton' 
          data-roomid='$_SESSION[profileroomid]' data-provider='$providerid' data-caller='none'
          style='display:inline-block;cursor:pointer;
          text-align:left;vertical-align:top;
          background-color:$global_background;
          min-width:80%;max-width:300px;padding-left:10px;padding:10px;margin:5px'>
            $global_icon_check Create Your Personal Profile!<br>
        </div>";
    
    if($_SESSION['avatarurl']!=="$prodserver/img/faceless.png" && $_SESSION['avatarurl']!==""  ){
        $list = "<br><br><br><br>";
    }
    
    if($_SESSION['roomdiscovery']=='N'){
        return "";
    }
    
    $list .="
        <div class='selectchatlist mainbutton gridnoborder rounded mainfont mainbutton' 
          style='display:inline-block;cursor:pointer;
          text-align:left;vertical-align:top;
          background-color:$global_background;
          min-width:80%;max-width:300px;padding-left:10px;padding:10px;margin:5px'>
            $global_icon_check Join a Community Chat<br>
        </div>

        <br>
        
       $postformat
     </div>
     ";
    

    $list .= "<br><br><br>";    

    return $list;


    
}        