<?php
session_start();
include("config-pdo.php");
$logged_in_user = false;
if(@$_SESSION['pid']!=''){
    
    include("password.inc.php");
    $logged_in_user = true;

}
$uniqid = uniqid();

$trackerid = @tvalidator("PURIFY",$_GET['tracker']);
$store = @tvalidator("PURIFY",$_GET['store']);

//echo "$trackerid, $store";
$portal = @tvalidator("PURIFY", $_GET['portal'] );
$r = @tvalidator("PURIFY", $_GET['r'] );
$i = @tvalidator("PURIFY", $_GET['i'] );
$k = @tvalidator("PURIFY", $_GET['k'] );
$language = @tvalidator("PURIFY", $_GET['lang'] );
$share = strtolower("#$r");


//echo "$r, $trackerid, $store";
//exit();

$inviteemail = "";
$invitename = "";
$diff = "";
$roomhandle = '';
//$share = "";

if($language!=''){
    $_SESSION['language'] = $language;
} else {
    $_SESSION['language'] ='english';
}
include("internationalization.php");


$expiredLink = false;

//Authorized Private Share Check
if($k !==''){

    
    $title = "You're Invited to a Private Group";
    $subtitle = "A privacy enhanced social group on the $appname platform";
    $share = '';
    $result = pdo_query("1",
            "select roomid, timestampdiff(HOUR, now(), expires) as diff from roominvite where inviteid = ?
            ",array($k)
            );
    $diffval = -1;
    //$diffval = 0;
    
    if($row = pdo_fetch($result)){
    
        $i = $row['roomid'];
        $roomid = $row['roomid'];
        $diff = "<br><span style='color:#8dc63f'><b>This invitation expires in $row[diff] hours</b></span>";
        $diffval = $row['diff'];
        if(intval($diffval)< 0 ){
        
            echo "This link has expired. Contact your room moderator for a new invite.";
            $expiredLink = true;
            exit();
        }
        
    } else {
    
        //If not found it means K=Invalid
        $expiredLink = true;
        echo "This link has expired. Contact your room moderator for a new invite.";
        exit();
    }
}


//Hashtag Open Room Share
if($r !==''){
    
    $ownername='';
    $result = pdo_query("1","
        select providername from provider where
        providerid in (select owner from statusroom where roomid in
           (select roomid from roomhandle where handle=?) and owner = providerid )
            ",array("#$r"));
    if($row = pdo_fetch($result)){
        $ownername = ucfirst($row['providername']);
    }

    if($logged_in_user){
        $pre = $_SESSION['providername'].", ";
        
    }
    $title = "You're Invited by $ownername to Join";
    $subtitle = "";//<a href='$rootserver/blog/$r' target='_blank' style='text-decoration:none;color:white'>An open membership room on the Brax.Me platform</a>";
}

//Catch All to avoid errors
if($i == ''){
    $i = 0;
}


/******************************************************
 * CHECK FOR EXISTENCE OF ROOM HASHTAG
 ******************************************************/
$sharelink = "$rootserver/join.php?r=$r";
$shareimg = "";//https://brax.me/img/hanging-out-with-friends.jpg";
$shareimgcover = "$rootserver/img/hanging-out-with-friends.jpg";
$iconlock = "$rootserver/img/logo-b2.png";

$privateQuery = " and roominfo.private = 'N' ";
if($k != ''){
    $privateQuery = "";
}

//If portal accept PRIVATE rooms
if($portal == 'Y'){
    $privateQuery = " and roominfo.groupid is null and roominfo.private = 'N' ";
}

$result = pdo_query("1","
    select statusroom.roomid, roomhandle.handle, roomhandle.roomdesc,
           roominfo.photourl, roominfo.private, roominfo.groupid, 
           roominfo.sponsor, provider.enterprise, roominfo.external,
           roominfo.room as name, sponsor.needemail, 
           sponsor.colorscheme, sponsor.colorschemeinvite,
           sponsor.partitioned, sponsor.enterprisetype, sponsor.logo,
           roominfo.photourl2, roominfo.webcolorscheme
           from 
           statusroom
           left join roomhandle on roomhandle.roomid = statusroom.roomid
           left join roominfo on roominfo.roomid = statusroom.roomid
           left join provider on provider.providerid = statusroom.owner
           left join sponsor on sponsor.sponsor = roominfo.sponsor
           where (roomhandle.handle=? or statusroom.roomid=?)
           and statusroom.owner=statusroom.providerid
           $privateQuery

        ",array($share,$i));


if( !$row = pdo_fetch($result)){

    
    echo "<!DOCTYPE html>
          <head>
          <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
          <META HTTP-EQUIV='Expires' CONTENT='-1'>
          <meta property='og:title' content='Expired Content' />
          <meta property='og:url' content='$rootserver/img/expired.jpg' />
          <meta property='og:image' content='$rootserver/img/expired.jpg' />        
          <meta name='viewport' content='width=device-width, initial-scale=1'>
          <title>Content Not Found</title>
          </head>
          <body>
          <center>
            <img src='$rootserver/img/expired.jpg' style='height:200;width:auto;float:center;margin:auto' >
                <br>
                <span style='font-family:helvetica;font-size:13px'>
                <a href='$rootserver'><img src='$iconlock' class='margined' style='height:60px;width:auto' /></a>
                <br>Secure Enterprise Portals
                <br>
                <a href='$rootserver'>$rootserver</a>
                </span>
          </center>    
          </body>
          </html>
         ";    
    exit();
}

$originallink = "";
if($portal == 'Y'){
    $originallink = "$rootserver/s/$r";
}

$roomdesc = $row['roomdesc'];
$roomname = $row['name'];
$roomhandle = substr($row['handle'],1);
$roomdescHtml = htmlentities($row['roomdesc'], ENT_QUOTES);
$roomnameHtml = htmlentities($row['name'], ENT_QUOTES);
$photourl = "";
$roomid = $row['roomid'];
$sponsor = $row['sponsor'];
$enterprise = $row['enterprise'];
$needemail = $row['needemail'];  
$external = $row['external'];
$partitioned = $row['partitioned'];
$enterprisetype = $row['enterprisetype'];
$enterpriselogo = $row['logo'];

$webcolorscheme = $row['webcolorscheme'];
require("colorscheme.php");




if($row['photourl2']!=''){

    $shareimgcover = "$row[photourl2]";
    $photourl = "
               <img class='gridnoborder' src='$row[photourl2]' 
                   style='max-width:100%;max-height:400px;height:auto'/>
                   <br>
               ";
    
} else {
    
    if($row['photourl']!=''){

        $shareimgcover = "$row[photourl]";
        $photourl = "
                   <img class='gridnoborder' src='$row[photourl]' 
                       style='max-width:100%;max-height:400px;height:auto'/>
                       <br>
                   ";

    }
    if(strstr($row['photourl'],"$rootserver/img/slideshow.png")!==false){

        $photourl = '';
        $shareimgcover = "";
    }
}



pdo_query("1","insert into landing (createdate, landingcode, mobile, target) values (now(), ?, 'X','share' ) ",array($share));

$shareopentitle = "Invitation to $appname";
$sharelink = "$rootserver/$installfolder/roominvite.php?i=$i&r=$r";
$urlencoded = urlencode($sharelink);
$urlencoded .= "&text=".htmlentities(stripslashes($shareopentitle), ENT_QUOTES);

$fbshare = "http://www.facebook.com/sharer.php?u=$urlencoded";

$pid = @$_SESSION['pid'];

if($portal=='Y'){
    $windowtitle = "$sponsor Signup";    
    $fbshare = "";
    $title = $menu_signup;
    $subtitle = "";
    
    if($partitioned == 'Y' && strstr($enterprisetype,'personal')!==false ){
        $title = 'Sign Up to My Private App';
    }
    if($partitioned == 'Y' && strstr($enterprisetype,'personal')===false ){
        $title = 'Sign Up to Our App';
    }
    
} else {
    $windowtitle = "Invitation - $appname";    
}

/*
 * END - CHECK FOR EXISTENCE
 ******************************************************/


//**************************************************
//**************************************************
//**************************************************
/*
$backgroundcolor = "#68809f";
$backgroundcolor = "#c3c3c3";
$textcolor = 'black';
$titletextcolor = "#eaaa20";
$titletextcolor = "black";
 * 
 */
$backgroundcolor = $global_background;
$textcolor = $global_textcolor;
$titletextcolor = $global_textcolor;
$bottomcolor = $global_bottombar_color;
$deviceid = uniqid();
$ico = "https://brax.me/img/logo-b2a.ico";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta property='og:title' content='Join our <?=$appname?> group <?=$share?>' />
<meta property='og:description' content='<?=$roomdescHtml?> - Click for Community Joining Info' />
<meta property='og:url' content='<?=$sharelink?>' />
<meta property='og:type' content='Website' />
<meta property='og:image' content='<?=$shareimgcover?>' />        
<meta http-equiv='Pragma' content='no-cache' />
<meta http-equiv='Expires' content='-1' />
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='shortcut icon' href='<?=$ico?>' type='image/x-icon'>
<link rel='apple-touch-icon' href='<?=$ico?>'>
<title><?=$windowtitle?></title>
<link rel='stylesheet' href='<?=$rootserver?>/<?=$installfolder?>/public.css?<?=$uniqid?>' />
<link rel='stylesheet' href='<?=$rootserver?>/libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='<?=$rootserver?>/libs/alertify.js-0.3.10/themes/alertify.default.css' />
<link rel='stylesheet' href='<?=$rootserver?>/<?=$installfolder?>/app.css' />

<script src='<?=$rootserver?>/libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='<?=$rootserver?>/libs/jquery-1.10.2/jquery.min.js'></script>
<script src='<?=$rootserver?>/<?=$installfolder?>/passwordcheck.js'></script>
<script>
$(document).ready( function() {
    var MobileCapable = false;
    var mobileDevice = '';
    var MobileType = '';
    var Needemail = '<?=$needemail?>';
    var Portal = '<?=$portal?>';
    
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
    if( MobileType === 'A'){
    
        $('.googleapp').show();
        $('.iosapp').hide();
        $('.desktopapp').hide();
    }
    else
    if( MobileType === 'I'){
    
        $('.googleapp').hide();
        $('.iosapp').show();
        $('.desktopapp').hide();
    } else {
        $('.googleapp').show();
        $('.iosapp').show();
        $('.desktopapp').show();
    }

    if( mobileDevice === 'P'){
    
        $('.roomphoto').css({'width' : '100%'});
    } else {
        $('.roomphoto').css({'width' : '50%'});
        
    }
    if( MobileCapable){
    
        $('.confirmpassword').hide();
        //$('#password').attr('type','text');
        $('#mobiletype').val(MobileType);
        
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
        
        $(document).on('click', '.membersignup', function(){
            
            $.ajax({
                url: "<?=$rootserver?>/<?=$installfolder?>/roomjoin.php",
                context: document.body,
                type: 'POST',
                data: 
                 { 
                     'providerid' : '<?=$pid?>',
                     'handle' : '<?=$share?>',
                     'roomid' : '<?=$i?>',
                     'inviteid' : '<?=$k?>',
                     'mode' : 'J'
                 }
            }).done(function(data, status){
                var msg = jQuery.parseJSON(data);
                if( msg.msg!== "")
                {
                    $('#handle').val("").focus();
                    alertify.alert( msg.msg );
                    return;
                }
                window.location.href= "<?=$rootserver?>/<?=$startupphp?>";
                return;
                /*
                var msg = jQuery.parseJSON(data);
                if( msg.msg!== "")
                {
                    if( msg.msg === "You already joined this Room"){
                        window.location.href= "https://brax.me/l.php";
                        return;
                    }
                    $('#handle').val("").focus();
                    alertify.alert( msg.msg );
                    return;
                }
                      

                //alert('Successfully Joined');
                alertify.alert('Successfully joined');
                */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
            
            });
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

        $(document).on('click', '#saveprofilebutton', function(){
            if(Needemail==='Y'){
                if( ProcessSignup() ){
                }
            } else {
                SubmitCheck();
            }
        });

        $(document).on('click', '.signupopen', function(){
            $('.signuparea').show();
            $('.signupopen').hide();
            $('.signupclose').hide(800);
        });
        $(document).on('click', '.joinopen', function(){
            $('.joinarea').show();
            $('.joinopen').hide();
        });

        $(document).on('click', '#saveroombutton', function(){
        
            $('.postsubmit2').show();
            $('.presubmit2').hide();
            $('#saveroomform').submit();
        });

        $(document).on('focus', '#password', function(){
            $('.passwordhint').show();
            $('.handlehint').hide();
        });
        $(document).on('focus', '#handle', function(){
            $('.handlehint').show();
            $('.passwordhint').hide();
        });
        
        $('#providername').keyup(function(e)
        {
            var username = $('#providername').val();
            $('#providername').val(username);
            username = username.replace(/[^a-z0-9 ]/gi, "");  
            
            $('#handle').val('@'+username.replace(" ",""));
            
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
        function ProcessSignup()
        {
            //First Check Email
            $.ajax({
                  url: "<?=$rootserver?>/<?=$installfolder?>/checkexisting.php",
                  context: document.body,
                  type: 'POST',
                  data: 
                   { 'email': $('#replyemail').val()
                   }

              }).done(function( data, status ) {
                var msg = jQuery.parseJSON(data);
                if( msg.error === "handletaken" || msg.error==='emailtaken')
                {
                    $('body').scrollTop(0);
                    alertify.alert( msg.msg );
                    return;
                }
                if( msg.msg !== "")
                {
                    //alertify.alert( msg.msg );
                }
                SubmitCheck();

              });
        }


        function SubmitCheck()
        {
            
            var roomhandle = $('#roomhandle').val();
            if( roomhandle.substring(0,1)!=='#' && roomhandle.length > 0 )
            {
                alertify.alert('RoomHandles begin with a #');
                return false;
            }
            if($('#loginid').val()==='')
            {   
                alertify.alert('Missing Login ID');
                return false;
            }
            if($('#providername').val()==='')
            {
                alertify.alert('Missing Subscriber Name');
                return false;
            }
           
            if((Needemail ==='Y' || Needemail ==='B') && Portal ==='Y' && $('#replyemail').val()==='')
            {
                alertify.alert('Missing Email');
                return false;
            }
            if((Needemail ==='S' || Needemail ==='B') && Portal ==='Y' && $('#replysms').val().length < 10)
            {
                alertify.alert('Missing or Invalid Mobile Phone ');
                return false;
            }
            if(!MobileCapable && $('#password').val()!== $('#password2').val())
            {
                alertify.alert('Passwords do not match.');
                return false;
                    
            }
            if( scorePassword( $('#password').val() ) < 30 )
            {
                alertify.alert('Please enter a more secure password. Read the suggested rules.');
                return false;
                    
            }
            if(!$('#termsofuse').prop('checked') )
            {
                alertify.alert('Terms of Use not accepted');
                return false;
            }
            
            $('.postsubmit').show();
            $('.presubmit').hide();
            $('#profileedit').hide();
            $('#status').show();
            $('#profileedit').submit();
            
            return true;
        }
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

<title><?=$share?></title>
</head>

<body style='text-align:center;font-family:helvetica Neue,Helvetica, arial;font-weight:100;background-color:<?=$backgroundcolor?>;'>
    <div style='background-color:white;height:40px;text-align:center;display:none'>
        <img src='<?=$shareimgcover?>' style='float:left;height:40px;' />
        <img src='<?=$rootserver?>/img/logo-b2.png' style='height:30px' />
    </div>
    <div style='padding:0px;text-align:center'>
<?php
if($fbshare!=''){
?>
        <a href='<?=$fbshare?>'>
            <img src='../img/facebook-white-128.png' style='height:50px;cursor:pointer;float:right' />
        </a>
<?php
}
if($portal=='Y'){
?>
        <div class='signuparea pagetitle' style=';padding:10px;text-align:center;background-color:<?=$global_titlebar_color?>;color:white'>
            <a href='<?=$originallink?>' style='text-decoration:none;color:white'>
            <b><?=$roomname?></b>
            </a>
        </div>
        
        <span class='signupclose' style='display:none'>
            <?=$photourl?>
        </span>
        <span class='signupclose' style='display:none;color:<?=$textcolor?>'>
            <div class='pagetitle2a' style='padding-left:20px;padding-right:20px;margin:auto;text-align:center;color:<?=$textcolor?>;max-width:500px'>
                <b><?=$roomname?></b>
            </div>
        </span>
        <span class='signupclose' style='display:none'>
            <div class='pagetitle3' style='padding-left:20px;padding-right:20px;margin:auto;text-align:center;color:<?=$textcolor?>;max-width:500px'>
                <?=$roomdesc?> 
            </div>
        </span>
<?php
} else {
?>
        <br>
        <div class='pagetitle2a' style='text-align:center;color:<?=$textcolor?>;'>
        <?=$title?> 
        </div>
        <?=$inviteemail?>
        <div class='pagetitle3' style='text-align:center;color:<?=$textcolor?>;font-weight:100'>
            <?=$subtitle?>
            <?=$diff?> 
        </div>
        <br>
        <div class='pagetitle' style='padding-left:20px;padding-right:20px;text-align:center;color:<?=$titletextcolor?>'>
            <b><?=$share?></b> <b style='color:<?=$global_activetextcolor?>'><?=$roomname?></b>
        </div>
        <span class='signupclose'>
            <?=$photourl?>
            <div style='padding-left:20px;padding-right:20px;font-size:14px;margin:auto;text-align:center;color:<?=$textcolor?>;max-width:500px'>
                <?=$roomdesc?> 
            </div>
        </span>

<?php
}
if($logged_in_user && $portal != 'Y' && $external!='Y'){
?>
        <br><br>
        <div class='membersignup gridnoborder rounded' style='
             cursor:pointer;
             padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;
             background-color:<?=$global_titlebar_color?>;color:white;width:100px;margin:auto'>
            Enter Room
        </div>
        <br><br>
<?php
} else {
?>
        <br>
        <!--
        <span class='smalltext' style='color:white'>
        You can participate in this group on a mobile app or browser.
        </span>
        <br><br><br>
        -->
        <FORM id='profileedit'  ACTION='<?=$rootserver?>/<?=$installfolder?>/signupproc.php' METHOD='POST' target='subscribeframe'>
            <div class='signupopen gridnoborder rounded' style='
                 cursor:pointer;display:none;
                 padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;
                 background-color:<?=$global_titlebar_color?>;color:white;width:100px;margin:auto'>
                Join
            </div>
            
            <span class='signuparea' style=''>
                <br>
                <div style='font-size:20px;text-align:center;color:<?=$textcolor?>;font-weight:100'><?=$title?></div>
                <br>

                <input id=tranmode type=hidden name=tranmode value='edit' />
                <input id=buttonclicked class=buttonclicked type=hidden name=buttonclicked value='' />
                <input id=apn class=hidden type=hidden name=apn value='' />
                <input id=gcm class=hidden type=hidden name=gcm value='' />
                <input id=providerid class=providerid type=hidden name=providerid value='' />
                <input id=sponsor class=sponsor type=hidden name=sponsor value='<?=$sponsor?>' />
                <input id=confirmemail name=confirmemail  type=hidden value=''  size=30 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                <input id=subscriber1 type=hidden value=''  />
                <input id=invited class=invited name=invited type=hidden value='H'  />
                <input id=roomhandle name=roomhandle  type=hidden value='<?=$share?>'  size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                <input id=roomid name=roomid  type=hidden value='<?=$roomid?>'  size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                <INPUT id="enterprise" TYPE="hidden" NAME="enterprise" value="N">
                <INPUT id="termsofuseagree" TYPE="hidden" NAME="termsofuse" value="Y">
                <INPUT id="timezone" TYPE="hidden" NAME="timezone" value="">
                <INPUT id="deviceid" TYPE="hidden" NAME="deviceid" value="">
                <INPUT id="lastuser" TYPE="hidden" NAME="lastuser" value="">
                <INPUT id="innerwidth" TYPE="hidden" NAME="innerwidth" value="">
                <INPUT id="innerheight" TYPE="hidden" NAME="innerheight" value="">
                <INPUT id="trackerid" TYPE="hidden" NAME="trackerid" value="<?=$trackerid?>">
                <INPUT id="store" TYPE="hidden" NAME="store" value="<?=$store?>">
                <INPUT id="mobiletype" TYPE="hidden" NAME="mobiletype" value="">

                <table  class='mainfont' style='background-color:transparent;border-collapse:collapse;width:250px;margin:auto;font-size:15px;font-family:"Helvetica Neue",Helvetica,Arial,san-serif;font-weight:200;color:<?=$textcolor?>'>

                    <tr class=accountinforow>
                        <td class=dataarea>
                        <?=$menu_name?><br>
                        <input id=providername class='dataentry' name=providername type=text placeholder="<?=$menu_name?>" value='<?=$invitename?>' size=35 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>


                    <tr class=accountinforow>
                        <td class=dataarea>
                        <p class='handlehint' style="font-size:12px;display:none;font-weight:300;letter-spacing:0.5px">
                           Create a unique @username. This becomes your login ID.
                           It must start with @ and is alphanumeric only.
                           <br>
                        </p>
                        <?=$menu_handle?><span class='smalltext2'></span><br>
                        <input id=handle name=handle  type=text placeholder="<?=$menu_handle?>" value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>

                    <tr class=accountinforow>
                        <td class=dataarea>
                        <p class='passwordhint' style="display:none;font-size:12px;font-weight:300;letter-spacing:0.5px">
                            Use a password that has a minimum of 8<br>
                            characters, utilizes upper/lower case,<br>
                            numbers, and special characters. <br>
                            Repeating values lowers password strength.
                        </p>
                            <?=$menu_password?><br>
                        <input id=password class='dataentry' name=password  type=password placeholder="<?=$menu_password?>" autocomplete="off" value=''  size=35 maxlength=255 autocorrect='off' autocapitalize='off' style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px'/><br>
                        <span class='passwordscore'></span>
                        </td>
                    </tr>

                    <tr class='passwordhint accountinforow confirmpassword' style='display:none'>
                        <td class=dataarea>
                        <?=$menu_confirmpassword?><br>
                        <input id=password2 class='dataentry' name=password2  type=password  autocomplete="off" value=''  size=35 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
<?php
if($sponsor!='' && $portal=='Y' && $needemail == 'Y'){
?>
                    <tr class=accountinforow>
                        <td class=dataarea>
                            <br>
                        <?=$menu_email?><br>
                        <input id=replyemail name=replyemail  type=email placeholder='Email' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
                    <tr class=accountinforow style='display:none'>
                        <td class=dataarea>
                        <input id=replysms name=replysms  type=hidden placeholder='Mobile Phone' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
<?php
} else
if($sponsor!='' && $portal=='Y' && $needemail == 'S'){
?>
                    <tr class=accountinforow style='display:none'>
                        <td class=dataarea>
                        <input id=replyemail name=replyemail  type=hidden placeholder='Email' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
                    <tr class=accountinforow>
                        <td class=dataarea>
                        <br>
                        <?=$menu_sms?><br>
                        <input id=replysms name=replysms  type=text placeholder='Mobile Phone' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        <span class='smalltext2' style='color:<?=$global_textcolor?>'>+Country Code required if Non-US</span> 
                        </td>
                    </tr>
<?php
} else
if($sponsor!='' && $portal=='Y' && $needemail == 'B'){
?>
                    <tr class=accountinforow>
                        <td class=dataarea>
                            <br>
                        <?=$menu_email?><br>
                        <input id=replyemail name=replyemail  type=email placeholder='Email' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
                    <tr class=accountinforow>
                        <td class=dataarea>
                        <?=$menu_sms?><br>
                        <input id=replysms name=replysms  type=text placeholder='Mobile Phone' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        <span class='smalltext2' style='color:<?=$global_textcolor?>'>+Country Code required if Non-US</span> 
                        </td>
                    </tr>
<?php
} else {
?>
                    <tr class=accountinforow style='display:none'>
                        <td class=dataarea>
                        <input id=replyemail name=replyemail  type=hidden placeholder='Email' value='<?=$inviteemail?>' size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
                    <tr class=accountinforow style='display:none'>
                        <td class=dataarea>
                        <input id=replysms name=replysms  type=hidden placeholder='Mobile Phone' value='' size=30 maxlength=255 style='padding:10px;font-size:18px;width:250px;height:20px;margin-top:3px' />
                        </td>
                    </tr>
                    
<?php
} 
?>
                    
                    
                </table>
                <br>
                <span class='mainfont' style='color:<?=$global_textcolor?>' >
                    <input type='checkbox' id='termsofuse' style='position:relative;top:8px' value='agree' />
                <?=$menu_accept?>
                <a class='mainfont' href='<?=$rootserver?>/prod/license-v1.php?i=N&s=web' style='text-decoration:none'>
                    <span class='mainfont' style='color:<?=$global_activetextcolor?>'><?=$menu_termsofuse?></span>
                </a>.
                </span>
                <br>
                <br>
                <br>
                
                <div class='divbuttontext saveprofile' id='saveprofilebutton' style='background-color:<?=$global_titlebar_color?>;color:white'><?=$menu_createaccount?></div>
                <br>
                <br>
                <br>
                <br>
                <span style='color:<?=$global_textcolor?>'><?=$menu_existingaccount?></span>
                <br><br>
                <div class='divbuttontext' style='background-color:white'><a href='<?=$rootserver?>/<?=$startupphp?>?h=<?=$roomhandle?>' style='text-decoration:none;color:black'><?=$menu_login?></a></div>
            </span>

        </form>
        <span class='signuparea' style=''>
            <br><br>
            <center>
            <iframe class='postsubmit' name='subscribeframe' height='400px' width='300px' style='display:none;border: 4px solid #FFFFF;border-radius: 15px;'></iframe>
            <span class='presubmit'>
            <br>
            <br>
            <br>
            </span>
            <br>
            </center>
        </span>
        <br>
        <br>
        
<?php
}
?>
            
    </div>
    
<?php
if($portal!='Y'){
?>
        <br>
            <a href='<?=$rootserver?>/<?=$installfolder?>/license-v1.php' target='_blank' style='text-decoration:none;color:<?=$global_activetextcolor?>'><?=$menu_termsofuse?></a>    
            <br>
            <br>
            <a href='<?=$rootserver?>/<?=$installfolder?>/privacy.php' target='_blank' style='text-decoration:none;color:<?=$global_activetextcolor?>'><?=$menu_privacypolicy?></a>    
        <br>
        <br>
    <div  class='smalltext2' style='background-color:<?=$bottomcolor?>;color:white;'>
            <center>
                <br>
            <a href='<?=$homepage?>'><img src='<?=$applogo?>' class='margined' style='height:30px;width:auto' /></a>
            <br>
            <br>Powered by <?=$appname?> <?=$enterpriseapp?>
            <br><br>
            <br><br>
            <br><br>
            <br><br>
<?php
} else {

?>
    <div  class='smalltext2' style='background-color:<?=$bottomcolor?>;color:white;'>
            <center>
                <br>
            <a href='<?=$homepage?>'><img src='<?=$applogo?>' class='margined' style='height:30px;width:auto' /></a>
            <br>
            <br>Powered by <?=$appname?> <?=$enterpriseapp?>
            <br><br>
            <br><br>
            <br><br>
            <br><br>
<?php
} 
?>
            </center>
    </div>
</body>

</html>
 