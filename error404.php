<?php
require_once("prod/config.php");
$file = mysql_safe_string(@$_GET['f']);
$randomid = uniqid();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>


<meta name='description' content='Building Private Communities'>
<meta property='og:title' content='Brax.Me - Building Private Communities' />
<meta property='og:url' content='<?=$rootserver?>/index.php' />
<meta property='og:image' content='<?=$rootserver?>/img/bigstock-friendship-leisure-summer.jpg' />
<meta name='viewport' content='width=device-width, height=device-height, initial-scale=1, user-scalable=0, maximum-scale=1'>
<meta name='apple-mobile-web-app-capable' content='yes'>
<meta name='mobile-web-app-capable' content='yes'>
<link rel='apple-touch-startup-image' href='../img/logo-b1a.png'>
    





<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 

<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='<?=$rootserver?>/libs/alertify.js-0.3.10/src/alertify.js'></script>

<link rel='stylesheet' href='https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css'>
<script src='https://code.jquery.com/jquery-1.11.1.min.js' integrity=1 ></script>
<script src='https://code.jquery.com/ui/1.11.1/jquery-ui.js' integrity=1 ></script>

<link rel='icon' href='img/logo-b1a.png'>
<link id=favicon rel='shortcut icon' href='../img/logo-b1a.ico'>

<link rel='apple-touch-icon' href='img/logo-b1a.png'>
<link rel='apple-touch-icon-precomposed' href='../img/logo-b1a.png'>
<link rel='apple-touch-startup-image' href='img/logo-b1a.png' />

<link rel='styleSheet' href='<?=$rootserver?>/webv2.css?<?=$randomid?>' type='text/css'/>

</head>
<body style='background-color:#35485e;color:white;font-family:helvetica, arial;font-size:normal'>
    <div style="margin:auto;text-align:center">
        <div>Session Timeout.</div>
        <div><?=$file?></div>
        <br><br>
        <a class='startup' href='<?=$rootserver?>/<?=$startupphp?>' style='text-decoration:none;color:firebrick;font-size:20px'>
            <div class='divbuttontext' style="width:100px;margin:auto;border-radius:5px;height:30px;padding:10px;background-color:white;color:firebrick"><b>Restart-0</b> </div>
        </a>
    </div>
    <script>
    var MobileZoomLevel = 1;
    var MobileCapable = false;
    var MobileDivide = false;
    var DeviceCode = 'Unk';
    var mobileDevice = "P";
    var MobileType = "I";
    
    
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
        if( MobileCapable === false){
            $('.browseronly').addClass('hidemobile');
        } else {
            $('.browseronly').removeClass('hidemobile');
        }
    }    
    UserAgentMatching();
    if(MobileType==='A' || MobileType==='I'){
        window.location("https://brax.me/command/restart");
    }
    $('.startup').attr('href', '<?=$rootserver?>/<?=$startupphp?>?v='+localStorage.mobileversion );
    
</script>    
</body>
</html>
