<?php
session_cache_expire(10);
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require("password.inc.php");
require_once("admin.inc.php");
require_once("crypt-pdo.inc.php");
require_once("internationalization.php");
require("htmlhead.inc.php");

    if(!isset($_POST['pid'])){
        echo "Profile Error";
        exit();
    }
    $providerid = tvalidator("ID",$_POST['pid']);
    $loginid = tvalidator("PURIFY",$_POST['loginid']);



    $help0 = "&nbsp;&nbsp;<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='<b>Email Address</b><br><br>Your email address is shown only to people you know.".
            " You will need to verify your email. If you wish to change your email address, you should ".
            "establish a new account. This is for your own safety since your email is our primary way of identifying you.'
            />";

    $help1 = "&nbsp;&nbsp;<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='<b>Full Name</b><br><br>This name is visible only to your contacts and if no alternate identity".
            " is provided using the open room name and alias.<br><br>".
            "Your room posts can be made anonymous or use an alias ".
            "regardless of this name setting.<br><br>".
            "Use a name here that your contacts know so they can be certain of your identity.".
            " This name is always used in Chat since it is a conversation with a known party.'
            />";
    $help2 = "&nbsp;&nbsp;<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='<b>Open Room Name</b><br><br>".
            "This name is used only in open membership Rooms. As with all other room posts, ".
            " you can still post anonymously or use an alias in addition to this name.<br><br>".
            " Since this name can be seen by members you do not know, decide how you wish to be identified. ".
            "You can change this at any time and it will immediately alter the name an all prior posts.'
            />";
    $help3 = "&nbsp;&nbsp;<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='<b>External Alias</b><br><br>This alias is used in notifications and texts. If specified, it is the only ".
            "name seen outside of the app. When an alias is used, Notifications do not show any actual message body. ".
            "An alias can also be a position title in an organization, to allow contact with a generic identity, like tech support'
            />";
    $help4 = "<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='Your mobile phone number allows you to easily perform password resets and receive notifications ".
            " if you don&#39;t have a supported mobile device. If not provided, all alerts will go to email instead which may not be timely. This is never shown to anyone else and is stored in an encrypted form.'
            />";
    
    $help5 = "&nbsp;&nbsp;<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='This $appname @handle (short name starts with a @) is an alternate identitier instead of an email address.  ".
            "When used, your email address is not shown to your contacts.' ".
            "/>";
    
    $help6 = "&nbsp;&nbsp;<img class='helpinfo icon15' src='../img/help-gray-128.png' 
            data-help='Open Rooms may have age minimums. This is required for filtering age-appropriate content.'
            />";

        $deviceid = substr($_SESSION['deviceid'],-10,10);
?>
<script>
        
$(document).ready( function() 
{
        var MobileCapable;
        var MobileType;
        var pin = '';

    
        
        MobileCapable = false;
        if( navigator.userAgent.match(/iPhone/i)) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "I";
        }
        else
        if( navigator.userAgent.match(/iPad Mini/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
        }
        else
        if( navigator.userAgent.match(/iPad/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
        }
        else
        if( navigator.userAgent.match(/Android/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "A";
        }

        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });


        $(document).on('click', '#saveprofilebutton', function(){
            window.top.ScrollMainTop();
            parent.ScrollMainTop();
            $('body').scrollTop(0);
            
            if($('#terminateaccount').is(":checked")){
                alertify.confirm('Are you sure you want to close your account?',function(ok){
                    if(ok){
                        if( SubmitCheck() ){
                            $('#profileedit').submit();
                            return;
                        }
                    }
                });
                return;
            } else 
            if($('#replyemail').val() != $('#newemail').val() ){
                alertify.confirm('Are you sure you want to change your registered email address?',function(ok){
                    if(ok){
                        if( SubmitCheck() ){
                            $('#profileedit').submit();
                            return;
                        }
                    }
                });
                return;
            
            } else {
                if( SubmitCheck() ){
                    $('#profileedit').submit();
                    return;
                } else {
                }
            }
        });
        
        
        
        $('div.label').show();
        $('div.labelrequired').show();
        $('td.label').hide();
        $('td.labelrequired').hide();
       
 
        $('#tranmode').val('edit');
        
        $('#tranmode').val('');
        $('#paymentinfo').click(function() {
            $('#buttonclicked').val('paymentinfo');
        });
        
        
        $('body').on('click','.helpinfo', function()
        {
            var help = $(this).data('help');
            alertify.alert(help);
        });
        
        $('body').on('click','.verificationrequest', function() 
        {
                $.ajax({
                    url: 'verifyrequest.php',
                    context: document.body,
                    type: 'POST',
                    data: 
                     { 
                     }

                }).done(function( data, status ) {
                    alertify.alert('Email Verification Resent ');
                });
        });
                   
         /*
        $('#upload').click( function() 
        {
            //alertify.alert('Test0');
            window.parent.uploadprofilephoto();
    
    
            //$('#uploadavatar').submit();
        });
        $('body').on('click','.inputfocus', function() 
        {
            if( MobileType === 'I' || MobileType=== 'A'){
            
                $('body').animate({ scrollTop: $(this).offset().top - 800 }, 'slow');          
            }
        });
        */
        $('#handle').keyup(function(e)
        {
            var handle = $('#handle').val();
            handle = handle.replace(/[^a-z0-9@]/gi, "");  
            if( handle.charAt(0)!=='@' && handle!==''){
                handle = '@'+handle;
            }
            $('#handle').val(handle);
        });        
        $('#sponsor').keyup(function(e)
        {
            var sponsor = $('#sponsor').val();
            sponsor = sponsor.replace(/[^a-z0-9-_.\/@]/gi, "");  
            $('#sponsor').val(sponsor);
        });        
        $('body').on('click','.colorselect', function() 
        {
            var color = $(this).data('color');
            $('.colorscheme1').val(color);
        });
        $('body').on('click','.showhidden', function() 
        {
            $('.showhidden').hide();
            $('.showhiddenarea').show();
        });
        
       
        function SubmitCheck()
        {
            window.top.ScrollMainTop();
            parent.ScrollMainTop();
            var handle = $('#handle').val();
            if( handle.substring(0,1)!=='@' && handle.length > 1){
            
                alertify.alert('Handles need to start with an @');
                return false;
                
            }
            if( handle.length === 0){
            
                alertify.alert('Missing Handle');
                return false;
                
            }
            var name = $('#providername').val();
            if( name.length === 0){
            
                alertify.alert('Missing Name');
                return false;
                
            }
            
            var pin = $('#pin').val();
            if(pin!=='' && pin.length !== 4){
                alertify.alert('Timeout PIN must b4 4 digit numeric.');
                return false;
            }

            var email = $('#replyemail').val();
            if( 
               (
                email.indexOf(".account@brax.me")!== -1 
                    ||
                $('#newemail').val() === '' 
               )
               &&
               $('#handle').val() === ''
            ){
                alertify.alert('You must have a @handle to use as your user ID if you do not have an email address.');
                return false;
            
            }
            var notificationflags='';
            if($('#notificationflags1').is(':checked')){
                if(pin!==''){
                    notificationflags += 'M';
                }
            } else {
                notificationflags += 'M';
            }

            if($('#notificationflags2').is(':checked')){
            } else {
                notificationflags += 'S';
            }
            
            if($('#notificationflags3').is(':checked')){
            } else {
                notificationflags += 'L';
            }
            
            if($('#notificationflags4').is(':checked')){
            } else {
                notificationflags += 'R';
            }
            
            if($('#notificationflags5').is(':checked')){
            } else {
                notificationflags += 'C';
            }
            
            if($('#notificationflags6').is(':checked')){
                notificationflags += 'F';
            } else {
            }
            
            if($('#notificationflags7').is(':checked')){
            } else {
                notificationflags += 'B';
            }
            
            if($('#notificationflags8').is(':checked')){
                notificationflags += 'E';
            } else {
            }
            
            $('#notificationflags').val(notificationflags);
            
            
            
            
            
            var notifications='N';
            if($('#notifications1').is(':checked')){
                notifications = 'Y';
            } 
            $('#notifications').val(notifications);

            return true;
        }
         
});
</script>   
<title>Profile</title>
</head>
<BODY class="mainfont" style='width:90%;padding:0;margin-top:0;margin-bottom:0;margin-left:auto;margin-right:auto;background-color:white'>
    
<?php
    //$_SESSION['providerid'] = "$_POST[pid]";
    $expiredays = '';
    $expiredate = "";
    $bandwidthplan = "";
    $subscriptiontext = "";
    $result = pdo_query("1","
            SELECT date_format(msgplan.dateend,'%m/%d/%Y') as expiredate, 
            date_add( msgplan.dateend, INTERVAL -365 DAY) as datestart,
            timestampdiff(DAY, now(), msgplan.dateend )+1 as expiredays, 
            bandwidthplan, active,
            storage
            from msgplan
            where providerid=? 
            order by dateend desc limit 1
    ",array($providerid));
    $startdate = '1900-01-01';
    if($row = pdo_fetch($result)){
        $expiredays = $row['expiredays'];
        $expiredate = InternationalizeDate($row['expiredate']);
        $startdate = $row['datestart'];
        $bandwidthplan = rtrim($row['bandwidthplan']);
        $storage = intval($row['storage']);
        $active = $row['active'];
        
        if($storage == 0){
            $bandwidthplan = '100GB Bandwidth and Storage';
        } else
        if($storage == 1){
            $bandwidthplan = '200GB Bandwidth and Storage';
        } else
        if($storage == 2){
            $bandwidthplan = '600GB Bandwidth and Storage';
        } else
        if($storage == 3){
            $bandwidthplan = '850GB Bandwidth and Storage';
        } else
        if($storage == 4){
            $bandwidthplan = '1100GB Bandwidth and Storage';
        } else
        if($storage == 5){
            $bandwidthplan = '1350GB Bandwidth and Storage';
        } else
        if($storage == 6){
            $bandwidthplan = '1600GB Bandwidth and Storage';
        } else
        if($storage == 7){
            $bandwidthplan = '1850GB Bandwidth and Storage';
        } else
        if($storage == 8){
            $bandwidthplan = '2100GB Bandwidth and Storage';
        }
        if($active == 'F'){
            $bandwidthplan = "4GB Bandwidth and Storage Free";
        }
    }
    $filesize = 0;
    $bandwidth = 0;
    $result3 = pdo_query("1","select round(sum(filesize)/1000000000,2) as filesize from filelib 
            where providerid = ? ",array($providerid));
    if($row3 = pdo_fetch($result3)){
        $filesize = $row3['filesize'];
    }

    $result = pdo_query("1","select round(sum(views*filesize)/1000000000,2) as bandwidth from fileviews 
            where providerid = ? and viewdate >= ? ",array($providerid,$startdate));
    if($row3 = pdo_fetch($result)){
        $bandwidth = $row3['bandwidth'];
    }

    $bytzvpn = '';
    $bytzbraxwifi = '';
    //BYTZ VPN Subscription info
    $result3 = pdo_query("1"," 
        select username, password, datediff( date_add(startdate, interval 1 year), now() ) as expiredays, ip 
        from bytzvpn where providerid = ? and status='Y' 
        order by startdate desc limit 1
        ",array($providerid));
    if($row3 = pdo_fetch($result3)){
        $bytzvpnusername= $row3['username'];
        $bytzvpnpassword = $row3['password'];
        $bytzvpnexpiredays = $row3['expiredays'];
        if($bytzvpnexpiredays<0){
            $bytzvpnexpiredays = 0;
        }
        $tmp = explode('/',$row3['ip']);
        $bytzvpnip = $tmp[0];   
        if($bytzvpnip!==''){
            $bytzbraxwifi = "BraxWifi Local IP Address: $bytzvpnip";
        }

        $bytzvpn = "$global_icon_check BytzVPN expires in $bytzvpnexpiredays days. 
            <span class='showhidden' style='cursor:pointer;color:$global_activetextcolor'>Show VPN Credentials</span>  
            <div class='pagetitle2a showhiddenarea' style='display:none'>
                <br>
                Username: $bytzvpnusername<br>
                Password: $bytzvpnpassword<br>
                $bytzbraxwifi
            </div>
            <br><br>";
    }

    
    $result = pdo_query("1","
            select auth_hash from staff where loginid = '$_SESSION[loginid]' and providerid = ? 
                ",array($providerid));
      
    $row = pdo_fetch($result);
    if(!$row) {
         echo "Profile Error(2)";
         exit();
    }
    $auth_hash = $row['auth_hash'];
    
    
    
    $result = pdo_query("1",
            "SELECT provider.providerid, provider.providername, provider.name2, provider.companyname, provider.handle, age," .
            "(select sms from sms where provider.providerid = sms.providerid ) as smsencrypted, ".
            "(select encoding from sms where provider.providerid = sms.providerid ) as smsencoding, ".
            "provider.replyemail, provider.avatarurl, provider.industry, provider.member, " .
            "provider.verified, provider.roomdiscovery, streamingaccount, provider.colorscheme, " .
            "provider.wallpaper, provider.active, provider.autosendkey, ".
            "provider.msglifespan, provider.alias, provider.member, provider.enterprise, provider.positiontitle, " .
            "(select pin from timeout where provider.providerid = timeout.providerid ) as pin,".
            "(select welcome from sponsor where provider.sponsor = sponsor.sponsor and  ".
            " sponsor.roomid in (select roomid from statusroom where statusroom.owner = provider.providerid) ) as welcome, ".
            "(select enterprise from sponsor where provider.sponsor = sponsor.sponsor ) as sponsorenterprise,".
            "provider.cookies_recipient, provider.cookies_sender, provider.featureemail, ".
            "provider.notifications, provider.notificationflags, provider.sponsor, provider.sponsorlist, " .
            "truncate(inactivitytimeout/60,0) as inactivitytimeout, ".
            "defaultsmtp, provider.publish, provider.publishprofile, provider.gift, ".
            "(select sum(tokens) from tokens where tokens.providerid = provider.providerid and dc='C') as tokenspaid, " .
            "(select sum(tokens) from tokens where tokens.providerid = provider.providerid and dc='D' and tokens.method!='TEST') as tokensbought, " .
            "store, web, roomcreator, broadcaster, allowiot, hardenter, email_service ".
            "from provider where providerid=? order by providerid desc ",array($providerid));

  
      
    $row = pdo_fetch($result);
    if(!$row) {
         echo "SQL Error";
    }
    
    //Compute Level
    $planlevel = '';
    if($row['roomcreator']=='Y' ){
        $planlevel = "You're on $enterpriseapp Level 1";
    }
    if($row['roomcreator']=='Y' && $row['web']=='Y' && $row['store']=='Y'){
        $planlevel = "You're on $enterpriseapp Level 3";
    }
    if($row['roomcreator']=='Y' && $row['web']=='Y' && $row['store']=='N'){
        $planlevel = "You're on $enterpriseapp Level 2";
    }
    
    
    $row['welcome'] = str_replace("<br>","",$row['welcome']);
    if(substr($row['handle'],0,1)!=='@'){
    
        $row['handle']='@'.$row['handle'];
    }
    $sms = "";
    if($row['smsencrypted']!=''){
        $sms = DecryptText($row['smsencrypted'], $row['smsencoding'], $row['providerid']);
    }
    if($row['industry']=='personal'){
        $row['industry']='';
    }
    
    $pin = $row['pin'];

    $eowner_disabled = "";
    $eowner_readonly = "";
    if($row['member']=='Y'){
        $eowner_disabled = "disabled";
        $eowner_readonly = "readonly=readonly";
    }
    
    $temp = explode("/",$row['streamingaccount']);
    $streamchannel = "";
    $streamplatform = $temp[0];
    if(isset($temp[1])){
        $streamchannel = $temp[1];
    }
    
    $streamplatformoptions = "
        Live Platform<br>
        <select name='streamplatform' class='streamplatform dataentry' placeholder='Platform' style='width:100px;'>
            <option value='$streamplatform' selected='selected'>$streamplatform</option>
            <option value='twitch'>Twitch</option>
            <option value='youtube'>Youtube</option>
            <option value='youtubevideo'>Youtubevideo</option>
            <option value='braxlive'>BraxLive</option>
            <option value=''>None</option>
        </select>
        ";
    
    $verifiedtext = "";
    if( $auth_hash=='' && (strstr($row['replyemail'],".account@brax.me")!==false ||
        strstr($row['replyemail'],".accounts@brax.me")!==false )
                    ){ 
        $verifiedtext = "
            <div class='pagetitle3' style='color:black;max-width:500px'>
            <hr style='border-color:$global_separator_color'>
            <span class='pagetitle3'><img class='icon15' src='../img/info-128.png' > 
            <b>Warning: You have no method for password recovery. You will not be able to reset your password if you
            forget it.
            <br><br>
            You can use an email address, and you must verify the email using the message we will send you.
            <br><br>
            You may also enter a mobile number to use for password resets via text. Your mobile
            number is encrypted for your safety and is never revealed.
            <br><br>
            You can use a TOTP authenticator app such as Google Authenticator, or Authy to give you a
            One Time Password (OTP). Go to SETTINGS - Set up TOTP 2FA to utilize this feature.
            </div>
            <br><br>
            <hr style='border-color:$global_separator_color'>
            <br><br>
            </div>
             ";
        $row['replyemail']='';
    
    } else
    if($auth_hash=='' &&  $row['verified']!='Y' && strlen($sms)<= 2 ){
            
            $verifiedtext = "
            <div class='pagetitle3' style='color:black;max-width:500px'>
            <hr style='border-color:$global_separator_color'>
            <span class='pagetitle3'>
            <span class='pagetitle3'><img class='icon15' src='../img/info-128.png' >             
            <b>Warning: Your email is unverified.</b></span>
            This is permissible. However, you will not be able to reset your password if you forget it.
            <br><br>
            If <span style='color:black'>$row[replyemail]</span> is valid, you can reverify it. You can also change your 
            email address below.
            <br><br><br>
            <div class='verificationrequest divbuttontext'>Resend Email Verification</div>
            <br><br><br>
            Instead of an email, you may also enter a mobile number to use for password resets via text. Your Mobile
            number is encrypted for your safety and is never revealed.
            <br><br>
            Additionally, you can use an authenticator app such as Google Authenticator, or Authy to give you a
            One Time Password (OTP). Go to SETTINGS - Set up an Authenticator App to utilize this feature.
            <br><br>
            If you do not have a verified email address, some features such as sending email notifications and invites 
            will be disabled.
            </div>
            <br><br>
            <hr style='border-color:$global_separator_color'>
            <br><br>
            </div>
             ";
    }
    if(intval($expiredays)>0){
        $subscriptiontext = "
            <br>
            <div class='pagetitle3' style='color:black;max-width:500px'>
            $global_icon_check
            <b>$planlevel</b>
            </div>
            <br>
            <div class='pagetitle3' style='color:black;max-width:500px'>
            $global_icon_check
            <b>Your subscription is active for $expiredays days (expires $expiredate) $bandwidthplan</b>.
            </div>
            <br>
            $global_icon_check
            <b>Your current storage used is $filesize GB, Bandwidth used this period is $bandwidth GB
            <br>
             ";
        
    }
    if($row['email_service']=='Y'){
        $subscriptiontext .= "$global_icon_check
            <b>You're Subscribed to Braxmail.Net.
            <br>
            ";
        
    }
    $tokenbalance = intval($row['tokensbought'])-intval($row['tokenspaid']);
    
    $tokenstext = "";
    if($tokenbalance > 0){
        $tokenstext = "
            <br>
            <div class='pagetitle3' style='color:black;max-width:500px'>
            $global_icon_check
            <b>Your token balance is $tokenbalance</b>.
            </div>
            <br>
             ";
        
    }
    $auth_text = "";
    if($auth_hash!=''){
        $auth_text = "
            <div class='pagetitle3' style='color:black;max-width:500px'>
            $global_icon_check
            <b>You are using an Authenticator App for One Time Passwords</b>.
            </div>
            <br>
             ";
        
    }
    
    
    
    $sponsorheading = "Your Sponsor";
    if($_SESSION['enterprise']=='Y'){
        $sponsorheading = "My Sponsor Code";
    }
    
    if($row['wallpaper']==''){
        $row['wallpaper']='default';
    }
    


    
    
?>
<form id='profileedit'  ACTION='profileprocsave.php' METHOD='POST' style='padding:0px;margin:0px'>
    <div class='gridstdnoborder' style='width:90%;max-width:400px;;margin:0;padding-top:0px;padding-left:10px;padding-right:10px;padding-bottom:0px' >
        
        <div class='pagetitle'>
            <?=$menu_myaccountinfo?>
        </div>
        
        <img class='changeavatar tooltip dataentry' alt='profile picture' 
          title='profile picture' 
          style='width:auto;max-width:100px;padding:0;margin:0' src='<?=$_SESSION['avatarurl']?>' />
        <br>
        <br>
        <?=$bytzvpn?>
        <?=$verifiedtext?>
        <?=$subscriptiontext?>
        <?=$tokenstext?>
        <?=$auth_text?>
        <br><br>
        
        <p class='divbuttontext saveprofile pagetitle2a' style='background-color:<?=$global_titlebar_color?>;color:white;cursor:pointer' id='saveprofilebutton'><?=$menu_save?></p>
        <br><br>
        <input id=tranmode type=hidden name=tranmode value=edit />
        <input id=buttonclicked class='buttonclicked dataentry' type=hidden name=buttonclicked value='' />
        <input id=dealer class=hidden type=hidden name=dealer value='' />
        <input id=loginid class=hidden type=hidden name=loginid value='<?=$loginid?>' />
        <input id=providerid class='hidden' type=hidden name=providerid value='<?php echo "$row[providerid]"; ?>'  />
        <input id=providerid class='hidden' type=hidden name=providerid value='<?php echo "$row[providerid]"; ?>'  />
        
        
        
        <br>
        <hr style='border-color:<?=$global_separator_color?>'>
        <div class='pagetitle2' style='font-weight:bold'><?=$menu_myidentity?></div>
        <br>
        <?=$menu_email?><br>
        <input id=newemail name=replyemail class='dataentry inputfocus' autocomplete="off" type=text placeholder='No Email' value='<?php echo "$row[replyemail]"; ?>'  />
        <input id=replyemail name=origemail class='' type=hidden value='<?php echo "$row[replyemail]"; ?>'  />
        <br><br>
        
        
        
        
        <div class=label><?=$menu_name?><?=$help1?></div>
        <input id=providername class='providername dataentry inputfocus' name=providername type=text value='<?php echo "$row[providername]"; ?>' size=30 maxlength='30' />
        
        <br><br>
        <div class=label><?=$menu_handle?><?=$help5?></div>
        <input id=handle class='handle dataentry inputfocus' <?=$eowner_readonly?> name=handle type=text placeholder='@username' value='<?php echo "$row[handle]"; ?>' size=30 maxlength='30' />
        
        <br><br>
        <div class=label><?=$menu_alias?> <?=$help3?></div>
        <input id=alias class='alias dataentry inputfocus' name=alias type=text placeholder='External Alias' value='<?php echo "$row[alias]"; ?>' size=30 maxlength='30' />
<?php
if($_SESSION['enterprise']=='Y' || $_SESSION['superadmin']=='Y' ){
?>

        <br><br>
        <div class=label>Position Title <?=$help3?></div>
        <input id=positiontitle class='positiontitle dataentry inputfocus' name=positiontitle type=text placeholder='Position Title' value='<?php echo "$row[positiontitle]"; ?>' size=30 maxlength='30' />
<?php
} else {
?>
        <input id=positiontitle class='positiontitle dataentry inputfocus' name=positiontitle type=hidden placeholder='Position Title' value='<?php echo "$row[positiontitle]"; ?>' size=30 maxlength='30' />
<?php
} 
?>

        <br><br>
        <div class=label>Mobile Phone <?=$help4?></div>
        <input id=replysms name=replysms class='replysms dataentry inputfocus' type=text placeholder='Texting Phone Number' value='<?php echo "$sms"; ?>'  size=30 />
        <br>
        <span class='smalltext'>+Country Code required if Non-US</span>
        <br><br><br>
<?php 

            echo "<label for='hardenter'>ENTER will SEND in Chat</label><br>";
            if( $row['hardenter'] == '' || $row['hardenter'] == 'Y' ) {       
                
                echo "<input id=hardentergroup name=hardenter  title='ENTER will SEND in Chat' checked='checked'  type=radio value='Y' style='position:relative;top:7px'/> Enable&nbsp;&nbsp;&nbsp;";
                echo "<input id=hardentergroup name=hardenter  title='ENTER will SEND in Chat' type=radio value='N' style='position:relative;top:7px'/> Disable";
            } else {
                echo "<input id=hardentergroup name=hardenter  title='ENTER will SEND in Chat' type=radio value='Y' style='position:relative;top:7px'/> Enable&nbsp;&nbsp;&nbsp;";
                echo "<input id=hardentergroup name=hardenter  title='ENTER will SEND in Chat' checked='checked' type=radio value='N' style='position:relative;top:7px'/> Disable";
            }
?>        

        <br><br><br>
        <hr style='border-color:<?=$global_separator_color?>'>
        <div class='pagetitle2' style='font-weight:bold'><?=$menu_mynotifications?></div>
        <br>
        
<?php 

        if( $row['notifications']!='N') {       

            echo "<input id=notifications1 name=notifications1 title='Enable Notifications' data-theme='c' type=checkbox value='Y' checked=checked style='display:none;position:relative;top:5px' />";
        } else {
            echo "<input id=notifications1 name=notifications1 title='Enable Notifications' data-theme='c' type=checkbox value='Y'  style='position:relative;top:5px' />";
            echo "<label for='notifications'>Enable Notifications Globally</label>";
        }
        
?>        
        <input id=notifications name=notifications  type=hidden value="<?=$row['notifications']?>" style=''/>
        <input id=notificationflags name=notificationflags  type=hidden value="<?=$row['notificationflags']?>" style=''/>
        
        
        <div class=label></div>

<?php 
            if( strstr($row['notificationflags'],"S") !== false) {       

                echo "<input id=notificationflags2 name=notificationflags2  title='Enable Sound in Notification' type=checkbox value='S' style='position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags2 name=notificationflags2  title='Enable Sound in Notification' checked='checked'  type=checkbox value='S' style='position:relative;top:7px'/>";
            }
            echo "<label for='notificationflags2'>$menu_sound</label>";
            echo "<br><br>";

            if( strstr($row['notificationflags'],"M") !== false) {       

                echo "<input id=notificationflags1 name=notificationflags1  title='Show Message in Notification' type=checkbox value='M' style='position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags1 name=notificationflags1  title='Show Message in Notification' checked='checked'  type=checkbox value='M' style='position:relative;top:7px'/>";
            }
            echo "<label for='notificationflags1'>Display Message in Notification (incoming and outgoing)</label>";
            echo "<br><br>";


            //Hide this. No longer in use
            if( strstr($row['notificationflags'],"L") !== false) {       

                echo "<input id=notificationflags3 name=notificationflags3  title='Enable Live Stream Notifications' type=checkbox value='L' style='display:none;position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags3 name=notificationflags3  title='Enable Live Stream Notifications' checked='checked'  type=checkbox value='L' style='display:none;position:relative;top:7px'/>";
            }
            /*
            echo "<label for='notificationflags3'>$menu_live</label>";
            echo "<br><br>";
             * 
             */

            if( strstr($row['notificationflags'],"R") !== false) {       

                echo "<input id=notificationflags4 name=notificationflags4  title='Enable Room Notifications' type=checkbox value='R' style='position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags4 name=notificationflags4  title='Enable Room Notifications' checked='checked'  type=checkbox value='R' style='position:relative;top:7px'/>";
            }
            echo "<label for='notificationflags4'>$menu_room</label>";
            echo "<br><br>";

            if( strstr($row['notificationflags'],"C") !== false) {       

                echo "<input id=notificationflags5 name=notificationflags5  title='Enable Chat Notifications' type=checkbox value='C' style='position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags5 name=notificationflags5  title='Enable Chat Notifications' checked='checked'  type=checkbox value='C' style='position:relative;top:7px'/>";
            }
            echo "<label for='notificationflags5'>$menu_chat</label>";
            echo "<br><br>";
            
            if($row['allowiot']=='Y'){
                
                if( strstr($row['notificationflags'],"B") !== false) {       

                    echo "<input id=notificationflags7 name=notificationflags7  title='Enable SecureNet Notifications'  type=checkbox value='C' style='position:relative;top:7px'/>";
                } else {
                    echo "<input id=notificationflags7 name=notificationflags7  title='Enable SecureNet Notifications'  checked='checked' type=checkbox value='C' style='position:relative;top:7px'/>";
                }
                echo "<label for='notificationflags7'>SecureNet</label>";
                echo "<br><br>";
            
            } else {
                
                    echo "<input id=notificationflags7 name=notificationflags7  title='Enable SecureNet Notifications'  type=hidden value='' style='display:hidden'/>";
                
            }
            
            
            if( strstr($row['notificationflags'],"F") !== false) {       

                echo "<input id=notificationflags6 name=notificationflags6  title='Enable Chat Notifications'  checked='checked' type=checkbox value='C' style='position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags6 name=notificationflags6  title='Enable Chat Notifications'  type=checkbox value='C' style='position:relative;top:7px'/>";
            }
            echo "<label for='notificationflags6'>$menu_postsfollowing</label>";
            echo "<br><br>";

            if( strstr($row['notificationflags'],"E") !== false) {       

                echo "<input id=notificationflags8 name=notificationflags8  title='Enable Notifications via Email'  checked='checked' type=checkbox value='C' style='position:relative;top:7px'/>";
            } else {
                echo "<input id=notificationflags8 name=notificationflags8  title='Enable Notifications via Email'  type=checkbox value='C' style='position:relative;top:7px'/>";
            }
            echo "<label for='notificationflags8'>Enable Notifications via Email</label>";
            echo "<br><br>";
            
        
        
?>        
        
        <br>
        <hr style='border-color:<?=$global_separator_color?>'>
        <div class='pagetitle2' style='font-weight:bold'>Timeout Lock</div>
        <br>
        <div class=label>Inactivity Timeout (Minutes - 0 = disabled)</div>
        <input id=inactivitytimeout class='handle dataentry inputfocus' name=inactivitytimeout type=number placeholder='' value='<?php echo "$row[inactivitytimeout]"; ?>' size=2 maxlength="2" />
        
        <div class=label>Timeout Unlock PIN (4 digits)</div>
        <input id=pin class='handle dataentry inputfocus' name=pin type=password placeholder='' value='<?php echo "$pin"; ?>' size=4 maxlength="4" />
        
        
        <br>
        <br>
        <br>
        <!--HIDDEN VALUES-->
        <input id=publish name=publish  type=hidden value='<?=$row['publish']?>' title='Show Bio in Public List' style='display:none;position:relative;top:5px' /> 
        <textarea title='Biography' cols="50" rows="4" id=publishprofile class='publishprofile dataentry' name=publishprofile  placeholder='Public Profile Description' style='display:none'><?php echo "$row[publishprofile]"; ?></textarea>
        <input id=streamingaccount name=streamingaccount class='streamingaccount dataentry inputfocus' type=hidden placeholder='Channel Identifier' value='<?php echo "$streamchannel"; ?>'  size=30 style='width:150px' />
<?php

if($row['enterprise']=='Y' || $row['sponsor']!=='' ){
    
?>

        <hr style='border-color:<?=$global_separator_color?>'>
        <div class='pagetitle2' style='font-weight:bold'><?=$menu_advancedsettings?></div>

        <br>
        <div class=label><?=$sponsorheading?></div>
        <input id=sponsor class='handle dataentry inputfocus' name=sponsor type=text placeholder='Sponsor Code' value='<?php echo "$row[sponsor]"; ?>' size=30 maxlength="30" />
        
        
<?php
}
if($row['enterprise']=='Y' ){
    
?>
            
        <div class=label></div>

        <label>Display my name in Enterprise List?</label>&nbsp
<?php 

            if( $row['sponsorlist']=='Y'){        
                echo "<input id=sponsorlist name=sponsorlist data-theme='c' type=checkbox value='Y' checked=checked style='position:relative;top:5px' /> Listed";
            } else {
                echo "<input id=sponsorlist name=sponsorlist data-theme='c' type=checkbox value='Y'  style='position:relative;top:5px'/> Listed";
            }
?>
        <br><br>
<?php
} 


if($row['sponsor']!='' || $row['enterprise']=='Y'){ //&& $row['enterprisesponsor']=='Yx' ){
?>
        
        <div class=label></div>
        <br>


        <label for='publish'>View Public Social Media Space in <?=$appname?>?</label>
        <br>
<?php
            if( $row['roomdiscovery']=='Y' || $row['roomdiscovery']=='' ){        
?>        
                <input id=roomdiscovery name=roomdiscovery  type=checkbox value='Y' checked='checked' style='position:relative;top:5px' /> Enable Social Media
<?php
            } else {
?>        
                <input id=roomdiscovery name=roomdiscovery  type=checkbox value='Y'  style='position:relative;top:5px' />  Enable Social Media
<?php
             }
?>
        <div class='smalltext' style='width:300px'>
            <?=$enterpriseapp?> accounts and their members are in a partitioned space.
            You can opt to open yourself to all of <?=$appname?> by enabling Social Media.
        </div>
<?php
} else {
?>
        <input id=roomdiscovery name=roomdiscovery  type=hidden value='<?=$row['roomdiscovery']?>' />  
<?php    
}
?>
                
                <br><br>
        <!--
        <label for='publish'>Accept Brax Tokens?</label>
        <br>
        -->
<?php
            if( $row['gift']=='Y' || $row['gift']=='' ){        
?>        
                <input id=gift name=gift  type=hidden value='Y' checked='checked' style='position:relative;top:5px' /> 
<?php
            } else {
?>        
                <input id=gift name=gift  type=hidden value='Y'  style='position:relative;top:5px' /> 
<?php
             }



if( $_SESSION['superadmin']=='Y'){

?>

        <br><br><hr style='border-color:<?=$global_separator_color?>'>
        <br><br>
        <div class=label>Admin Only - Industry Feature</div>
        <input id=industry class='industry dataentry inputfocus' name=industry type=text placeholder='Industry Code' value='<?php echo "$row[industry]"; ?>' size=30 />
        
        
<?php
} else {
?>
        <input id=industry class='industry dataentry inputfocus' name=industry type=hidden value='<?php echo "$row[industry]"; ?>' size=30 />
        
<?php
}

if($row['member']!='Y'){
?>
        
        
        <br><br>
        <hr style="border-color:<?=$global_separator_color?>">
        <div class=label></div>


        <input id=terminateaccount name=terminateaccount title='Close Account' data-theme='c' type=checkbox value='Y'  style='position:relative;top:5px' /> 
        <label for='terminateaccount'>Close Account?</label>
        <br><span class="smalltext">Warning: You will lose your stored data!</span>
        <br><br><br><br>
        <br><br><br><br>
        
        
        <p class='divbuttontext saveprofile pagetitle2a' style='background-color:<?=$global_titlebar_color?>;color:white;cursor:pointer' id='saveprofilebutton'><?=$menu_save?></p>
        <br><br><br><br>
        <div class=label><b>Account Number</b></div>
        <span class='smalltext'><?=$providerid?></span>

        <br><br><b>Current usage:</b> <br>Storage = <?=$filesize?> GB
        <br>Bandwidth = <?=$bandwidth?> GB

        
<?php
}
?>
        
    <br><br>
    <br><br>
    </div>
    <br><br>
    <br><br>
</form>
<br><br><br>
<?php require("htmlfoot.inc"); ?>
