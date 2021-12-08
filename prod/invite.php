<?php
session_start();
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type: nosniff');
header('X-XSS-Protection: 1; mode=block');

//$inviteid = uniqid('',true);
require_once("config-pdo.php");
require_once("htmlhead.inc.php");

$deviceid = uniqid();


$lang = @tvalidator("PURIFY",$_GET['lang']);
if($lang==''){
    $_SESSION['language']='english';
} else {
    $_SESSION['language']=strtolower($lang);
}

require_once("internationalization.php");

$landing = @tvalidator("PURIFY",$_GET['l']);
//if($inviteemail[0]=='@'){
//    $inviteemail = "";
//}
$invitename = @tvalidator("PURIFY",$_REQUEST['name']);
$inviteemail = @tvalidator("PURIFY",$_REQUEST['invite']);
$invitesms = @tvalidator("PURIFY",$_REQUEST['invitesms']);
$invitehandle = @tvalidator("PURIFY",$_REQUEST['handle']);
$source = @tvalidator("PURIFY",$_REQUEST['s']);
$version = @tvalidator("PURIFY",$_REQUEST['v']);
$trackerid = @tvalidator("PURIFY",$_REQUEST['tracker']);
$type = @tvalidator("PURIFY",$_REQUEST['type']);
if($invitehandle!='' && substr($invitehandle,0,1)!='#'){
    $invitehandle = '#'.$invitehandle;
}

$gcm = @tvalidator("PURIFY",$_REQUEST['gcm']);
$apn = @tvalidator("PURIFY",$_REQUEST['apn']);
$loginlink = "$rootserver/$installfolder/login.php?apn=$apn&gcm=$gcm&s=$source&lang=$lang&v-$version";
$signuplink = "$rootserver/$installfolder/signupproc.php";

$email = @tvalidator("PURIFY",$_POST['pid']);
if($email!='' && $email[0]!='@'){

    $inviteemail = $email;
}
$providerid = "";
$invitername = "";
$inviteid = @tvalidator("PURIFY",$_REQUEST['i']);
if($inviteid!=''){
    
    $result = pdo_query("1", "
            select * from invites 
            left join provider on invites.providerid = provider.providerid
            where
            inviteid = ? 
            ",array($inviteid));
    if( $row = pdo_fetch($result)){
    
        $invitename =$row['name'];
        $inviteemail =$row['email'];
        $invitesms =$row['sms'];
        if( intval($row['chatid'])>0){
            $invitername = "<br><br><div style='color:firebrick;max-width:300px'>You were invited by ".$row['providername']." on $inviteemail.<br>You have a message pending in chat.</div>";
        } else {
            $invitername = "<br><br><span style='color:firebrick'>$inviteemail You were invited by ".$row['providername'].".</span>";
            
        }
    }
    
}
//$inviteid = "";

$check = "<img src='../img/check-red-128.png' style='position:relative;top:1px;height:10px'/> ";


$mobileflag='';
if( "$gcm$apn" != "" || ($version != '' && $version!='000') ) 
{
    $mobileflag='Y';
}
if($landing == '')
{
    $landing = 'Unk';
}
//pdo_query("1","insert into landing (createdate, landingcode, mobile, target ) values (now(), '$landing','$mobileflag','signup' ) ");

$appstorehide = "";
if($version!='' && $version!='000'){
    $appstorehide = 'display:none';
}

?>
<script>
        
$(document).ready( function() 
{

    var mobileDevice = '';
    var DeviceCode = '';
    var MobileCapable = false;
    var MobileType = '';
    var MobileDivide = false;
    var MobileZoomLevel = 1;
    var Browser = '';
    var MobileFlag = '<?=$mobileflag?>';
    
    
    
    
    function UserAgentMatching()
    {
          
       
        MobileZoomLevel = 1;
        MobileCapable = false;
        MobileDivide = false;
        DeviceCode = 'Unk';
        if( navigator.userAgent.match(/iPhone/i)) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "I";
            MobileDivide = false;
            DeviceCode = 'iphone';
        }
        else
        if( navigator.userAgent.match(/iPad Mini/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
            MobileDivide = false;
            DeviceCode = 'ipad1';
        } else
        if( navigator.userAgent.match(/iPad/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
            MobileDivide = false;
            DeviceCode = 'ipad2';
        } else
        //Amazon Kindle HDX
        if( navigator.userAgent.match(/KFAPWI/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            MobileZoomLevel = 2;
            DeviceCode = 'kindlehdx';
        } else
        //Nexus
        if( navigator.userAgent.match(/Nexus/i) && navigator.userAgent.match(/Version/i) ) {
            mobileDevice = "T";
            DeviceCode = 'nexust'
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'nexusp';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;

        } else
        //Special Case SM-N910P Samsung NOTE
        if( 
                (
                navigator.userAgent.match(/SM-N9/i) 
                )
                &&  
                (
                navigator.userAgent.match(/Android 6/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'samtnote';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'sampnote';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;

        } else
        //Old Samsung
        if( 
                (
                navigator.userAgent.match(/Samsung/i) ||
                navigator.userAgent.match(/SCH/i) ||
                navigator.userAgent.match(/SM-/i) 
                )
                &&  
                (
                navigator.userAgent.match(/Android 4.0/i) ||
                navigator.userAgent.match(/Android 4.1/i) ||
                navigator.userAgent.match(/Android 4.2/i) ||
                navigator.userAgent.match(/Android 4.3/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'samtold';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'sampold';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;

        } else
        //New Samsung
        if( 
                (
                navigator.userAgent.match(/Samsung/i) ||
                navigator.userAgent.match(/SCH/i) ||
                navigator.userAgent.match(/SM-/i) 
                )
                &&  
                (
                navigator.userAgent.match(/Android 4.4/i) ||
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'samt2';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'samp2';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            //Browser not app
            //if(navigator.userAgent.match(/SamsungBrowser/i)){
            //    MobileDivide = false;
            //}
        } else
        //New Samsung
        if( 
                (
                navigator.userAgent.match(/Pixel/i)
                )
                &&  
                (
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'pixelt2';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'pixel2';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
            //Browser not app
            //if(navigator.userAgent.match(/SamsungBrowser/i)){
            //    MobileDivide = false;
            //}
        } else
        //ASUS Zenfone
        if( 
            navigator.userAgent.match(/Android/i)  && 
            //ASUS Zenfone 
            navigator.userAgent.match(/ASUS_/i) 
            ) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = true;
            DeviceCode = 'asus';
        } else    
        //Motorala Droid 
        if( navigator.userAgent.match(/Android/i)  && 
                (
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) 
                )
                &&
                (
                    navigator.userAgent.match(/ XT/i) || 
                    navigator.userAgent.match(/Motorola/i) 
                )
                /*
                        &&
                        navigator.userAgent.match(/Version/i)
                */
            ) {
            //alert('Droid');
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "A";
            //MobileDivide = false;
            MobileDivide = false;
            DeviceCode = 'droid';
        }
        else
        //Catch All for Other Android
        if( 
                (
                navigator.userAgent.match(/Android 4.0/i) ||
                navigator.userAgent.match(/Android 4.1/i) ||
                navigator.userAgent.match(/Android 4.2/i) ||
                navigator.userAgent.match(/Android 4.3/i) 
                )
            ){
            mobileDevice = "T";
            DeviceCode = 'androidt';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'androidp';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
        }
        else
        if(
                (
                navigator.userAgent.match(/Android 4.4/i) ||
                navigator.userAgent.match(/Android 5/i) ||
                navigator.userAgent.match(/Android 6/i) ||
                navigator.userAgent.match(/Android 7/i) ||
                navigator.userAgent.match(/Android 8/i) 
                )
             
            ){
            mobileDevice = "T";
            DeviceCode = 'androidt2';
            if( navigator.userAgent.match(/Mobile/i)){
                mobileDevice = 'P';
                DeviceCode = 'androidp2';
            }
            MobileCapable = true;
            MobileType = "A";
            MobileDivide = false;
        }

        if( MobileCapable){

            $('.poweruser').hide();
            if(window.navigator.standalone !== true) {
            }    
            $('.bannerflush').addClass('touch').removeClass('nontouch');
            $('.changeavatar').addClass('touch').removeClass('nontouch');
            $('#mobiletype').val(MobileType);
        }
        Browser = '';
        if(navigator.userAgent.match(/Firefox/) && DeviceCode==='Unk') {
            Browser = 'firefox';
            DeviceCode = 'firefox';
            $('.camera').hide();
        } else
        if(navigator.userAgent.match(/Chrome/) && !navigator.userAgent.match(/Android/i)  && DeviceCode ==='Unk'  ) {

                Browser = 'chrome';
                DeviceCode = 'chrome';
                MobileDivide = false;
                MobileCapable = false;
                
        } else
        if( (navigator.userAgent.match(/Safari/) && !navigator.userAgent.match(/Mobile Safari/) ) && (DeviceCode==='Unk' || DeviceCode==='iphone') ) {
            Browser = 'safari';
            DeviceCode = 'safari';
        } else
        if(navigator.userAgent.match(/Chrome/) && navigator.userAgent.match(/Android/i) && DeviceCode ==='Unk' ) {

                Browser = 'chrome';
                DeviceCode = 'chromemobile';
                MobileDivide = false;
                MobileCapable = true;
                
        } else
        if(navigator.userAgent.match(/Windows NT/)  && DeviceCode ==='Unk' ) {
            Browser = 'windows';
            DeviceCode = 'windows';
        }
    }
        
    UserAgentMatching();
    if(window.navigator.standalone === true) {
        Browser = 'Standalone';
    }    
    
    if( Browser !== ''){
        $('.browseronly').show();
    } else {
        $('.browseronly').hide();
    }
    if( MobileFlag === 'Y'){
        $('.browseronly').hide();
    } else {
        $('.browseronly').show();
    }
        
    
    
        MobileDevice = 'N';
        $('.formobile').hide();
        if( MobileType === 'A' || MobileType === 'I' ){
        
            MobileDevice = 'Y';
            $('.confirmpassword').hide();
            //$('#password').attr('type','text');
            $('.mobile').show();
            $('.formobile').show();
            $('.nonmobile').hide();
        }

        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });

        $('#password').keyup(function(){
            var n = checkPassStrength( $('#password').val());
            $('.passwordscore').html(n);
            
        });
        $(document).on('blur', '#providername', function(){
        });
        
        $(document).on('blur', '#replyemail', function(){
            if( $('#replyemail').val()!==''){
            
                    $.ajax({
                          url: "<?=$rootserver?>/<?=$installfolder?>/checkexisting.php",
                          context: document.body,
                          type: 'POST',
                          data: 
                           { 
                               'email': $('#replyemail').val()
                           }

                      }).done(function( data, status ) {
                        var msg = jQuery.parseJSON(data);
                        if( msg.error === "handletaken" || msg.error==='emailtaken'){
                        
                            alertify.alert( msg.msg );
                            $('#replyemail').val("");
                            return;
                        }
                      
                      });
            }
        });
        
        $('#handle').keyup(function(e)
        {
            var handle = $('#handle').val();
            handle = handle.replace(/[^a-z0-9@]/gi, "");  
            if( handle.charAt(0)!=='@' && handle!==''){
                handle = '@'+handle;
            }
            $('#handle').val(handle);
        });        
        
        $('#providername').keyup(function(e)
        {
            var username = $('#providername').val();
            $('#providername').val(username);
            username = username.replace(/[^a-z0-9 ]/gi, "");  
            $('#handle').val('@'+username.replace(" ",""));
            
        });        
        
        
        
        $(document).on('blur', '#handle', function(){
            CheckExistingHandle();
        });
        $(document).on('blur', '#providername', function(){
            CheckExistingHandle();
        });
        function CheckExistingHandle()
        {
           $('.handlehint').hide();
            if( $('#handle').val()!=='' && $('#handle').val()!=='@')
            {
                    var handle = $('#handle').val();
                    handle = handle.replace(/[^a-z0-9@]/gi, "");            
                    $('#handle').val(handle);
                
                
                    $.ajax({
                          url: "<?=$rootserver?>/<?=$installfolder?>/checkexisting.php",
                          context: document.body,
                          type: 'POST',
                          data: 
                           { 
                               'email': $('#handle').val()
                           }

                      }).done(function( data, status ) {
                        var msg = jQuery.parseJSON(data);
                        if( msg.error === "handletaken" || msg.error==='emailtaken')
                        {
                            $('body').scrollTop(0);
                            $('#handle').val(msg.alt).focus();
                            alertify.alert( msg.msg );
                            return;
                        }
                      
                      });
            }
        }        
        $(document).on('click', '.catchshow', function(){
            $('.catch').show();
        });
        $(document).on('focus', '#password', function(){
            if( MobileDevice === 'N'){
            
                $('.passwordhint').show();
            }
           $('.handlehint').hide();
        });
        $(document).on('focus', '#handle', function(){
            $('.handlehint').show();
            $('.passwordhint').hide();
        });
        
        $(document).on('click', '.industrygroup', function(){
            if($(this).val()==='enterprise'){
                $('.enterprise').show();
            } 
            if($(this).val()==='personal'){
                $('.enterprise').hide();
            } 
            if($(this).val()==='commercial'){
                $('.enterprise').hide();
            } 
        });

        $(document).on('click', '#saveprofilebutton', function(){
            
            if( scorePassword( $('#password').val() ) < 30 ){
            
                alertify.alert('Please enter a more secure password. Read the suggested rules.');
                return false;
                    
            }
        
            if( SubmitCheck() ){
            
                //this will submit if passed
                handleCheck();
            }
        });
    
 
        $('#tranmode').val('edit');
        $('.enterprise').hide();
        
        function handleCheck()
        {
        
            if( $('#handle').val()!=='' && $('#handle').val()!=='@')
            {
                    var handle = $('#handle').val();
                    handle = handle.replace(/[^a-z0-9@]/gi, "");            
                    $('#handle').val(handle);
                
                
                    $.ajax({
                          url: "<?=$rootserver?>/<?=$installfolder?>/checkexisting.php",
                          context: document.body,
                          type: 'POST',
                          data: 
                           { 
                               'email': $('#handle').val()
                           }

                      }).done(function( data, status ) {
                        if(status!=='success'){
                            alertify.alert(status);
                        }
                        if(data!==''){
                            var msg = jQuery.parseJSON(data);
                            if( msg.error === ""){

                                $('#profileedit').submit();
                                return;
                            }
                        }
                      
                      });
            }
            else {
                $('#profileedit').submit();
                
            }
        }


        function SubmitCheck()
        {
            if( $('#providername').val() === ''){
                alertify.alert('Please provide a name');
                return false;
            }
            
            var handle = $('#handle').val();
            handle = handle.replace(/[^a-z0-9@]/gi, "");            
            $('#handle').val(handle);

            if( handle === ''){
                alertify.alert('Please create a @handle');
                return false;
            }

            var roomhandle = $('#roomhandle').val();
            if( roomhandle.substring(0,1)!=='#' && roomhandle.length > 0 )
            {
                alertify.alert('RoomHandles begin with a #');
                return false;
            }
            if( handle.substring(0,1)!=='@' && handle.length > 1 )
            {
                alertify.alert('@Usernames begin with a @');
                return false;
            }
            
            if($('#loginid').val()=='')
            {   
                alertify.alert('Missing Login ID');
                return false;
            }
            if($('#providername').val()=='')
            {
                alertify.alert('Missing Name');
                return false;
            }
            
            
            if($('#industry3').is(':checked') && $('#replyemail').val()==='' ){
                alertify.alert('An Email is required for Premium Accounts');
                return false;
            }
           
            if($('#replyemail').val()=='')
            {
                //alertify.alert('Missing Email');
                //return false;
            }
            if($('#replyemail').val()!== $('#confirmemail').val())
            {
                //alertify.alert('Email Addresses do not match.');
                //return false;
                    
            }
            if(MobileType !=='A' && MobileType !=='I' && $('#password').val()!== $('#password2').val())
            {
                alertify.alert('Passwords do not match.');
                return false;
                    
            }
            if(!$('#termsofuse').prop('checked') )
            {
                alertify.alert('Terms of Use not accepted');
                return false;
            }
            
            return true;
        }
       function htmlUnEscape(html)
       {
            html = html.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");           
            return html;
       }
       $('.passwordhint').hide();
       $('.handlehint').hide();
       
       if(typeof localStorage.deviceid === 'undefined'){
           localStorage.deviceid = '<?=$deviceid?>';
       }
       
        var visitortime = new Date();
        $('#timezone').val(-visitortime.getTimezoneOffset()/60);
        $('#deviceid').val(localStorage.deviceid);;
        $('#lastuser').val(localStorage.pidl);;
        $('#innerwidth').val(window.innerWidth);
        $('#innerheight').val(window.innerHeight);
       
        
});
</script>   
<title><?=$appname?></title>
</head>
<body style="background-color:whitesmoke;font-size:15px;font-family:Helvetica;padding:0;margin:0">
    <center>
    
        <br>
    <span style='font-size:45px;font-family:"Helvetica Neue", helvetica;font-weight:100'>
            <a href='<?=$loginlink?>' style='text-decoration:none'>
            <img src="<?=$applogo?>" style="width:auto;height:80px;">
            </a>
            <div class='catchshow' style='cursor:pointer;font-size:30px;font-family:"Helvetica Neue" helvetica;font-weight:100'>    
                <?=$menu_signup?>
            </div>

    </span>
    <span class='catchshow mainfont' style='cursor:pointer;font-family:"Helvetica Neue" helvetica;font-weight:100'>    
        <?=$invitername?>
    </span>
    </center>
    <FORM id='profileedit'  ACTION='<?=$signuplink?>' METHOD='POST'>
 
    
        <input id=tranmode type=hidden name=tranmode value=edit />
        <input id=buttonclicked class=buttonclicked type=hidden name=buttonclicked value='' />
        <input id=dealer class=hidden type=hidden name=dealer value='' />
        <input id=apn class=hidden type=hidden name=apn value='<?=$apn?>' />
        <input id=gcm class=hidden type=hidden name=gcm value='<?=$gcm?>' />
        <input id=inviteid class=hidden type=hidden name=inviteid value='<?=$inviteid?>' />
        <input id=version class=hidden type=hidden name=version value='<?=$version?>' />
        <INPUT id="termsofuseagree" TYPE="hidden" NAME="termsofuse" value="Y">
        <INPUT id="timezone" TYPE="hidden" NAME="timezone" value="">
        <INPUT id="deviceid" TYPE="hidden" NAME="deviceid" value="">
        <INPUT id="lastuser" TYPE="hidden" NAME="lastuser" value="">
        <INPUT id="innerwidth" TYPE="hidden" NAME="innerwidth" value="">
        <INPUT id="innerheight" TYPE="hidden" NAME="innerheight" value="">
        <INPUT id="trackerid" TYPE="hidden" NAME="trackerid" value="<?=$trackerid?>">
        <INPUT id="mobiletype" TYPE="hidden" NAME="mobiletype" value="">
        
        <input id=providerid class=providerid type=hidden name=providerid value='' />

        <table style='margin:auto'>
            <tr>
            <td>
                
            
                <table  class='rounded' style='border-size:1px;border-color:gray;background-color:whitesmoke;;width:300px;padding:20px;margin:auto;font-size:13px;font-family:"Helvetica",Helvetica,Arial,san-serif;font-weight:200'  autocomplete='false'>


                    <tr class=accountinforow>
                    <td class=dataarea>
                    <?=$menu_name?><br>
                    <input id=providername class='providername' name=providername type=text placeholder="<?=$menu_name?>" value='<?=$invitename?>' size=35 maxlength='35' style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
                    <br>
                    </td>
                    </tr>

                    <tr class='accountinforow'>
                        <td class=dataarea>
                        <p class='handlehint' style="font-size:12px;display:none">
                            Create a unique @Username.<br>Example: @myidentity.<br>
                            This becomes your log in ID to the app.
                           <br>
                        </p>
                        @<?=$menu_handle?><br>
                        <input id=handle name=handle  type=text placeholder="<?=$menu_handle?>" value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' autocomplete='false' />
                        <br>
                        </td>
                    </tr>
                    <tr class='accountinforow'  style='<?=$appstorehide?>' >
                    <td class=dataarea>
<?php
if($type==''){
?>
                        <span style='display:none'>
                        <input id='industry1' type='radio' name='industry' class='industrygroup'  style='cursor:pointer;position:relative;top:5px' checked=checked value='personal'> Personal
                            <br><br>
                        </span>
<?php
} else
if($type=='premium'){
?>
                        <span class='browseronly' style='display:none'>
                            <br>
                            <input id='industry3' type='radio' name='industry' class='industrygroup'  style='cursor:pointer;position:relative;top:5px' checked=checked value='enterprise'> <?=$enterpriseapp?>
                            <!--
                            <br>
                            <input id='industry2' type='radio' name='industry' class='industrygroup'  style='cursor:pointer;position:relative;top:5px' value='commercial'> Commercial
                            -->
                            <br><br>
                        </span>
<?php
}
?>
                    </td>
                    </tr>

                    <tr class='accountinforow'>
                    <td class=dataarea>
<?php
if($type=='premium'){
?>
                    <?=$menu_email?><br>
                    <input id=replyemail name=replyemail  type=email placeholder='Email' value='<?=$inviteemail?>' size=35 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
<?php
} else {
?>
                    <input id=replyemail name=replyemail  type=hidden placeholder='Email' value='<?=$inviteemail?>' size=35 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
<?php
} 
?>
                    <input id=confirmemail name=confirmemail  type=hidden value='<?=$inviteemail?>'  size=30 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    <input id=subscriber1 type=hidden value='<?=$providerid?>'  />
                    <input id=loginid class=loginid name=loginid type=hidden value='admin'  />
                    <input id=avatarurl class=avatarurl name=avatarurl type=hidden value='<?=$rootserver?>/img/faceless.png' />
                    <input id=invited class=invited name=invited type=hidden value=''  />
                    <input id=dealermail name=dealeremail  type=hidden  />
                    </td>
                    </tr>



                    <tr class='accountinforow enterprise'>
                        <td class=dataarea>
                        <span class='browseronly'>
                        Mobile Phone (optional)<br>
                        <input id=replysms name=replysms  type=tel placeholder='Mobile Phone' value='<?=$invitesms?>' size=35 maxlength=30 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        <br>
                        <span class='smalltext2'>+CountryCode required if non-US. For account password recovery only.</span>
                        <br><br>
                        </span>
                        </td>
                    </tr>



                    <tr class='accountinforow'>
                    <td class=dataarea>
                    <p class='passwordhint' style="font-size:12px;display:none">
                        Use a password that has a minimum of 8<br>
                        characters, utilizes upper/lower case,<br>
                        numbers, and special characters. <br>
                        Repeating values lowers password strength.
                    </p>
                    <?=$menu_password?><br>
                    <input id=password name=password  type=password placeholder='<?=$menu_password?>' value='' autocomplete='off' size=35 maxlength=255 autocorrect='off' autocapitalize='off' style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px'/><br>
                    <span class='passwordscore'></span>
                    </td>
                    </tr>

                    <tr class='accountinforow confirmpassword passwordhint' style='display:none'>
                    <td class=dataarea>
                    <?=$menu_confirmpassword?><br>
                    <input id=password2 name=password2  type=password value='' autocomplete='off'   size=35 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                    <input id=roomhandle name=roomhandle  type=hidden value='<?=$invitehandle?>'  size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    </td>
                    </tr>



                    <tr class='accountinforow' >
                    <td class=dataarea>
                            <span class='mainfont' style='color:black' >
                                <input type='checkbox' id='termsofuse' style='position:relative;top:8px' value='agree' />
                            <?=$menu_accept?>
                            <a class='mainfont' href='<?=$rootserver?>/<?=$installfolder?>/license-v1.php?i=Y&s=web' style='text-decoration:none'>
                                <span class='mainfont' style='color:firebrick'><?=$menu_termsofuse?></span>
                            </a>.
                            </span>
                            <br>
                            <br>
                        <br><br>
                        <div class='divbuttontext divbutton_unsel saveprofile' id='saveprofilebutton' style="background-color:#A32F52;color:white">
                            &nbsp;&nbsp;<b><?=$menu_createaccount?></b>&nbsp;
                            <!--
                            <img class='icon15' src='../img/Arrow-Right-White_120px.png' style='position:relative;top:8px;' />
                            -->
                        </div>
                    </td>
                    </tr>

                </table>
            </td>
<?php
if($type == 'premiumetc'){
?>
            <td class='nonmobile' style=''>
                <div class='gridstdborder rounded' style='max-width:300px;padding:30px;background-color:#A32F52;color:white'>
                    <div class='pagetitle2' style='color:white'>Account Signup Options</div>
                    <br><br>
                    <b>Personal</b>
                    <br>
                    FREE account. Join our active public communities. Private encrypted messaging.                   
                    For social, non-commercial use, up to 4GB storage and bandwidth, 
                    and 60 day chat display. Upgradeable. 
                    <br>
                    <br>
                    <b><?=$enterpriseapp?></b>
                    <br>
                    Websites, mobile app and a private membership-based secure community. 
                    Private blogging. Encrypted cloud storage 100GB. Private live streaming.
                    Additional industry specific functionality.
                    FREE 7 day demo.
                    <br>
                    <br>
                    <!--
                    <b>Commercial</b>
                    <br>
                    Additional enterprise account. Up to 100GB storage and bandwidth, Unlimited chat. 
                    Standalone account $4.95 / month.
                    <br>
                    <br>
                    -->
                    <br>
                </div>
                
            </td>
<?php
}
?>
            </tr>
<?php
if($type == 'premium'){
?>
            
            <!--
            <tr class='formobile'>
            <td  style=''>
                <div class='gridstdborder rounded' style='max-width:300px;padding:30px;background-color:#A32F52;color:white'>
                    <div class='pagetitle2' style='color:white'>Account Signup Options</div>
                    <br><br>
                    <b>Personal</b>
                    <br>
                    FREE account. Join our active public communities. Private encrypted messaging.                   
                    For social, non-commercial use, up to 4GB storage and bandwidth, 
                    and 60 day chat display. Upgradeable. 
                    <br>
                    <br>
                    <b>Enterprise SocialVision</b>
                    <br>
                    Websites, mobile app and a private membership-based secure community. 
                    Private blogging. Encrypted cloud storage 100GB. Private live streaming.
                    Additional industry specific functionality.
                    FREE 30 day demo.
                    <br>
                    <br>
                    <br>
                </div>
                
            </td>
            </tr>
            -->
<?php
}
?>
            
        </table>
            
        <div style='width:100%;background-color:black;color:white;text-align:center'>
            
        
<?php
echo "<div style='position:relative;max-width:100%;float:right;text-align:center;margin:auto;padding:30px'>";
echo LanguageLinks("$rootserver/$installfolder/invite.php?gcm=$gcm&apn=$apn&v=$version","float:right","$global_activetextcolor");
echo "</div>";
?>
            
        
        
        
       
            <br><br><br><br>
            <div style='float:left;width:100%;text-align:center'>
                <a href='<?=$rootserver?>/<?=$installfolder?>/privacy.php?i=Y&apn=<?=$apn?>&gcm=<?=$gcm?>&s=<?=$source?>' target='_blank' style='text-decoration:none;color:white'><?=$menu_privacy?></a>    
                <br><br>
                <a href='<?=$rootserver?>/<?=$installfolder?>/license-v1.php?i=Y&apn=<?=$apn?>&gcm=<?=$gcm?>&s=<?=$source?>' target='_blank' style='text-decoration:none;color:white'><?=$menu_termsofuse?></a>    
                <br><br><br>
            </div>
            <br><br><br>
            <div class='status'></div>
            <br><br><br>
            <br><br><br>
            <br><br><br>
        </div>
            
    
        </form>
        
<?php require("htmlfoot.inc"); ?>
