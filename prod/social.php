    <?php
session_start();
//session_unset();
//session_destroy();
require_once("prod/config.php");
//session_destroy();
//$_SESSION = array();
//$_SESSION['mobile']='N';
//$_SESSION['pid']='';
//$_SESSION['password']='';
require("htmlhead-open.inc.php");
$timezone = "";

$mobile = @mysql_safe_string($_GET['mobile']);
$source = @mysql_safe_string($_GET['s']);
$landing = @mysql_safe_string($_GET['l']);

if( $landing!='')
{
    $mobileflag = 'X';
    do_mysqli_query("1","insert into landing (createdate, landingcode, mobile, target) values (now(), '$landing', '$mobileflag','home' ) ");
}

$maxwidth = 'width:1000px;max-width:80%;margin:auto';
$backgroundcolor = 'whitesmoke';

?>
<script>
$(document).ready( function() {
    try {
        var pid =  localStorage.pid;
        var swt = localStorage.swt;
        if( pid!=='' && swt!=='')
        {
            window.location("<?=$rootserver?>/l.php?s=web");
        }
    } 
    catch (err) 
    {
        //alertify.alert(err.message);
    }
    $('.tellmemore').hide();
    $('.showmore').on('click',function(){
        $('.tellmemore').show();
    });

       
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
    if( navigator.userAgent.match(/SM-G900/i)) {
        mobileDevice = "P";
        MobileCapable = true;
        MobileType = "A";
    }
    else
    if( navigator.userAgent.match(/Android/i)) {
        mobileDevice = "T";
        MobileCapable = true;
        MobileType = "A";
    }

    if( MobileType==='A')
    {
        $('.android').show();
        $('.ios').hide();
    }
    if( MobileType==='I')
    {
        $('.android').hide();
        $('.ios').show();
    }
    if( MobileType==='')
    {
        $('.android').show();
        $('.ios').show();
    }



});





</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55697704-1', 'auto');
  ga('send', 'pageview');

</script><title><?=$appname?></title>
</head>
<!--#5989baa-->
<BODY class="loginbody" style="text-align:center;position:absolute;width:100%;background-color:<?=$backgroundcolor?>; color:black;">

<div style='position:absolute;top:0;left:0;width:100%'>
    <div class='bannerflushfixed' style='background-color:whitesmoke;text-align:right;width:100%;padding-top:20px'>
        <img src="../img/bigstock-woman-using-mobile-phone.jpg" alt='Brax.Me Take Me Private' title='' style="float:right;height:0px;width:0px;display:none">
        <a href='<?=$rootserver?>'>
        <img src="../img/logo.png" alt='Brax.Me' title='Brax.Me' style="float:left;width:auto;height:35px;">
        </a>
        <span class='nonmobile'>
            <div class='divbutton3' style='margin-right:20px;margin-top:20px;background-color:#587493'>
                <a href='<?=$rootserver?>/prod/login.php?s=web' rel='nofollow'  style='text-decoration:none'>
                    <span style='color:white;;font-size:14px;font-weight:bold;font-family:Helvetica;'>Login</span>
                </a>
            </div>
            <div class='divbutton3' style='margin-right:40px;margin-top:20px;background-color:#587493'>
                <a href='<?=$rootserver?>/prod/invite.php?s=web' rel='nofollow'  style='text-decoration:none'>
                    <span style='color:white;;font-size:14px;font-weight:light;font-family:Helvetica;'>Sign Up</span>
                </a>
            </div>
        </span>
    </div>
</div>
    
    
<div class='formobile'>
    <br><br><br><br>
    <div style='color:black;padding-top:20px;padding-right:20px;display:inline-block;'>
        <a href='<?=$rootserver?>/prod/login.php?s=web' rel='nofollow'  style='text-decoration:none'>
            <span style='color:black;;font-size:14px;font-weight:bold;font-family:Helvetica;'>Login</span>
        </a>
    </div
    <div style='color:black;padding-top:20px;padding-right:20px;display:inline-block;'>
        <a href='<?=$rootserver?>/prod/invite.php?s=web' rel='nofollow' style='text-decoration:none'>
            <span style='color:black;;font-size:14px;font-weight:light;font-family:Helvetica;'>Sign Up</span>
        </a>
    </div>
</div>
<!--
        linear-gradient(
        rgba(0,0,0,0.5),
        rgba(0,0,0,0.5)
        ),
-->
<div style='padding:0;margin:0;width:100%'>    
    <div class='blurredbackground nonmobile' style='
        background: 
        url(img/bigstock-mobile-phone-mobility-wireless.jpg);
        background-size:100% auto;
        background-repeat:no-repeat;
        padding:0;margin:auto;background-color:#587493;text-align:center;height:2000px'>
        <br><br><br><br>
        <div style='font-weight:700;font-size:50px;font-family:Helvetica Neue, Helvetica, arial;padding-top:20px;padding-bottom:10px;padding-left:20px;padding-right:20px;color:white;text-align:left'>
            Building Private Communities
        </div>
        <div style='font-weight:200;font-size:20px;font-family:Helvetica Neue, Helvetica, arial;padding-top:0px;padding-bottom:40%;padding-left:20px;padding-right:20px;color:white;text-align:left'>
            Private Alt-Media Zone
        </div>
        <div class='gridstdborder' style='<?=$maxwidth?>;padding-top:0;border-radius:20px;background-color:whitesmoke;color:black;opacity:1;text-align:center'>
            <div class='pagetitle2' style='font-weight:600;text-align:center;padding:30px;color:black;opacity:1'>
                            Exclusive Private and Anonymous Communities
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            Create lively communities for discourse. Exchange data with privacy and anonymity. A Perfect safe-zone 
                            for alt-media.
                            <br><br>
                            </p>

                            Preserve Freedom of Speech
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            We don’t censor anyone and never will. 
                            No matter what your beliefs are, we absolutely welcome you to discuss them here! 
                            <br><br>
                            </p>

                            Complete and Total Privacy
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            Keep all of your conversations a secret with our advanced end-to-end encryption for groups. 
                            Never let mega corporations, zuckbook, or 3 letter government agencies spy on you ever again.                            
                            <br><br>
                            </p>

                            Eliminate Identity Theft 
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            Keep all of your personal info (financial or otherwise) in our digital vaults. 
                            We cannot and will not share it with anyone else.                            
                            <br><br>
                            </p>
                            
            </div>
        </div>
        <span class='pagetitle2a' style='color:white'>
            <br><br>
        Download the free app right now.
        </span>
        <br><br>
        <div style='display:inline;background-color:transparent;padding:0px;width:auto;color:white'>

            <a class='ios' href='http://itunes.com/apps/braxme' style='text-decoration:none'>
            <img class='appstore' src='../img/appStore.png' style='height:50px' >
            </a>
            &nbsp;&nbsp;
            <div class='formobile'><br></div>
            <a class='android' href='https://play.google.com/store/apps/details?id=me.brax.app1' style='text-decoration:none'>
            <img class='appstore' src='../img/androidplay.png' style='height:50px' >
            </a>
            <br><br>
            It's also available free for users on the web.
            <a href='https://brax.me/prod/invite.php?s=web' style='text-decoration:none;color:#eaaa20'>
                Join for Free
            </a>
            <br>
            <div style='color:white;padding-top:10px;padding-right:20px;display:inline-block;'>
                I have an existing account. 
                <a href='<?=$rootserver?>/prod/login.php?s=web' style='text-decoration:none'>
                    <span style='color:#eaaa20;font-size:14px;font-weight:normal;font-family:Helvetica;'>Login</span>
                </a>
            </div>
                    
        </div>
        <br><br><br><br>
        <br><br><br><br><br><br>
    </div>    
    <div class='nonmobile' style='background-color:black'>
        <br><br>
        
            <br>
            <div style='color:#eaaa20;padding-top:10px;display:inline-block'>
                <a href='<?=$rootserver?>/prod/privacy.php?s=web' style='text-decoration:none'>
                    <span style='color:#eaaa20;padding-right:40px;font-size:14px;font-weight:light;font-family:Helvetica;'>Privacy Policy</span>
                </a>
            </div>
        <br><br>
            <div style='color:#eaaa20;padding-top:10px;display:inline-block'>
                <a href='<?=$rootserver?>/prod/license-v1.php?i=Y&s=web' style='text-decoration:none'>
                    <span style='color:#eaaa20;padding-right:40px;font-size:14px;font-weight:light;font-family:Helvetica;'>Terms of Use</span>
                </a>
            </div>
        <br><br
    </div>
</div>    


    
    <!---
    
    
    
    MOBILE
    
    
    
    
    -->
    
    
    
    
    
    <div class='blurredbackground formobile' style='padding:0;margin:auto;background-color:<?=$backgroundcolor?>;text-align:center;height:1300px'>
        <br><br>
        <div style='<?=$maxwidth?>;padding:0'>
            <table class='gridstdborder' style='padding:0;margin:auto;background-color:white'>
                <tr class='gridnoborder' >
                    <td class='gridnoborder' style='<?=$backgroundcolor?>'>
                        <br>
                        <div class='headline'>
                            Building Private Communities<br>
                            <div class='pagetitle2'>Private Alt-Media Zone
                            </div>
                        </div>
                        <br>
                        <img src='img/bigstock-mobile-phone-mobility-wireless.jpg'  style='width:100%;margin:0;padding:0' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class='headtext' style='text-align:left;padding:40px;color:black;'>
                            Exclusive Private and Anonymous Communities
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            Create lively communities for discourse. Exchange data with privacy and anonymity. A Perfect safe-zone 
                            for alt-media.
                            <br><br>
                            </p>

                            Preserve Freedom of Speech
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            We don’t censor anyone and never will. 
                            No matter what your beliefs are, we absolutely welcome you to discuss them here! 
                            <br><br>
                            </p>

                            Complete and Total Privacy
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            Keep all of your conversations a secret with our advanced end-to-end encryption for groups. 
                            Never let mega corporations, zuckbook, or 3 letter government agencies spy on you ever again.                            
                            <br><br>
                            </p>

                            Eliminate Identity Theft 
                            <p class='pagetitle3' style='color:black;max-width:400px;margin:auto'>
                            <br>
                            Keep all of your personal info (financial or otherwise) in our digital vaults. 
                            We cannot and will not share it with anyone else.                            
                            <br><br>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>        
    <div class='blurredbackground formobile' style='padding:0;margin:auto;background-color:<?=$backgroundcolor?>;text-align:center;height:500px'>
        <div style='<?=$maxwidth?>;padding:0'>

                
        
            <a href='prod/invite.php' style='text-decoration:none'>
                <div class='divbuttontext' style='background-color:whitesmoke;color:black'>
                        <img src="../img/arrowhead-right-128.png" style="position:relative;top:8px;height:25px"/>
                        GET STARTED
                </div>
            </a>
            <br><br>
            <br><br>
            <span class='pagetitle2a' style='color:black'>
            Download the free app right now.
            </span>
            <br><br>
            <div style='display:inline;background-color:transparent;padding:0px;width:auto'>

                <a class='ios' href='http://itunes.com/apps/braxme' style='text-decoration:none'>
                <img class='appstore' src='../img/appStore.png' style='height:50px' >
                </a>
                &nbsp;&nbsp;
                <div class='formobile'><br></div>
                <a class='android' href='https://play.google.com/store/apps/details?id=me.brax.app1' style='text-decoration:none'>
                <img class='appstore' src='../img/androidplay.png' style='height:50px' >
                </a>
                <br>
                <div style='color:black;padding-top:10px;padding-right:20px;display:inline-block;'>
                    I have an existing account. 
                    <a href='<?=$rootserver?>/prod/login.php?s=web' style='text-decoration:none'>
                        <span style='color:firebrick;font-size:14px;font-weight:normal;font-family:Helvetica;'>Login</span>
                    </a>
                </div>
                <br>
            </div>
            <br><br>
        </div>
        <div style='color:#eaaa20;padding-top:10px;display:inline-block'>
            <a href='<?=$rootserver?>/prod/privacy.php?s=web' style='text-decoration:none'>
                <span style='color:#eaaa20;padding-right:40px;font-size:14px;font-weight:light;font-family:Helvetica;'>Privacy Policy</span>
            </a>
        </div>
        <br><br>
        <div style='color:#eaaa20;padding-top:10px;display:inline-block'>
            <a href='<?=$rootserver?>/prod/license-v1.php?i=Y&s=web' style='text-decoration:none'>
                <span style='color:#eaaa20;padding-right:40px;font-size:14px;font-weight:light;font-family:Helvetica;'>Terms of Use</span>
            </a>
        </div>
    <br>

    </div>    
    
    

    <div class='smalltext' style='font-size:12px;color:white;background-color:black'>
        <br>
        (C) Copyright 2016 Brax.Me<br>
            415 Detroit Street, Ann Arbor, MI 48104
        <br>
        <br>
    </div>
<script type="text/javascript">
  (function() {
    var sa = document.createElement('script'); sa.type = 'text/javascript'; sa.async = true;
    sa.src = ('https:' == document.location.protocol ? 'https://cdn' : 'http://cdn') + '.ywxi.net/js/1.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sa, s);
  })();
</script>
</div>
</BODY>
</HTML>
