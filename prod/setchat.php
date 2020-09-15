<?php
session_start();
//$inviteid = uniqid('',true);
require_once("config.php");
$_SESSION[returnurl]="<a href='index.htm'>Login</a>";

require_once("htmlhead.inc.php");

$inviteemail = mysql_safe_string($_GET['e']);
$email = mysql_safe_string($_POST['pid']);
if($email!='')
    $inviteemail = $email;
$invitename = mysql_safe_string($_GET['n']);
$invitesms = mysql_safe_string($_POST['s']);

$providerid = 0;
$result = do_mysqli_query("1", "select max(val1)+1 as maxid from parms where parmkey='SUBSCRIBER' AND PARMCODE='ID' ");
if( $row = do_mysqli_fetch("1",$result))
{
    $providerid =$row['maxid'];
}


$result = do_mysqli_query("1", "select max(providerid)+1 as providerid from provider ");
if( $row = do_mysqli_fetch("1",$result))
{
    $highid = $row['providerid'];
}

if( $providerid == 0 )
{
    $result = do_mysqli_query("1", "insert into parms (parmkey, parmcode, val1, val2 ) values ('SUBSCRIBER','ID', $highid, 0 )");
}

if( $highid > $providerid)
{
    $providerid = $highid;
}

$result = do_mysqli_query("1", "update parms set val1 = $providerid where parmkey='SUBSCRIBER' and parmcode='ID' ");

$result = do_mysqli_query("1", "
        select providername, companyname from provider where providerid =
        ( select providerid from invites where email='$email' 
            and status='Y' 
            and chatid is not null and chatid > 0 
            order by invitedate desc limit 1
        ) 
        and active='Y'
        ");
if( $row = do_mysqli_fetch("1",$result))
{
    $inviter = $row['providername'];
    if($row['companyname']!='')
        $companyname = " - ".$row['companyname'];
    $chatpending = "Chat Pending with $providername$companyname";
}


?>
<script>
        
$(document).ready( function() 
{
    var MobileDevice = 'N';
    
        if( navigator.userAgent.match(/iPhone/i) ||
            navigator.userAgent.match(/iPad Mini/i) ||
            navigator.userAgent.match(/iPad/i) ||
            navigator.userAgent.match(/Android/i) )
        {
            MobileDevice = 'Y';
            $('.confirmpassword').hide();
            $('#password').attr('type','text');
            $('.mobile').show();
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
        
        $(document).on('click', '#confirmemail', function(){
            
            if( $('#confirmemail').val()=='')
            {
                    $.ajax({
                          url: "checkexisting.php",
                          context: document.body,
                          type: 'POST',
                          data: 
                           { 'email': $('#replyemail').val()
                           }

                      }).done(function( html, status ) {
                        if( html != "")
                        {
                            
                            alertify.alert(" \
                                Your email address already exists. If you continue setting up this account, \
                                the old account will be inactivated. This primarily affects your social posts, \
                                photos, chat messages, text messages, and BraxSecure. \
                                          ");
                        }
                      
                      });
            }
        });
        

        $(document).on('click', '#saveprofilebutton', function(){
            
            if( scorePassword( $('#password').val() ) < 30 )
            {
                alertify.alert('Please enter a more secure password. Read the suggested rules.');
                return false;
                    
            }
        
            if( SubmitCheck() )
            {
                $('#profileedit').submit();
            }
        });
    
 
        $('#tranmode').val('edit');
        
        $('#tranmode').val('<?php echo "$_POST[tranmode]"; ?>');
        


        function SubmitCheck()
        {
            
            /*
            if( scorePassword( $('#password').val() ) < 30 )
            {
                    
            }
            */
            /*
            if($('#password').val().length < 8 )
            {
                alert('Password must be at least 8 digits and a combination of letters and numbers');
                return false;
            }
            */
            if($('#loginid').val()=='')
            {   
                alertify.alert('Missing Login ID');
                return false;
            }
            if($('#providername').val()=='')
            {
                alertify.alert('Missing Subscriber Name');
                return false;
            }
           
            if($('#replyemail').val()=='')
            {
                alertify.alert('Missing Reply Email (Subscriber Email Address)');
                return false;
            }
            if($('#replyemail').val()!== $('#confirmemail').val())
            {
                //alertify.alert('Email Addresses do not match.');
                //return false;
                    
            }
            if(MobileDevice === 'N' && $('#password').val()!== $('#password2').val())
            {
                alertify.alert('Passwords do not match.');
                return false;
                    
            }
            return true;
        }
       function htmlUnEscape(html)
       {
            html = html.replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">");           
            return html;
       }
        
});
</script>   
<title>Brax.Me - Secure Group Talk</title>
</head>
<body style="background-color:transparent;font-size:15px;font-family:Helvetica;
      background-image:url(../img/blurred-background-10.jpg);background-repeat:no-repeat;background-size:cover">
    <center>
        <br><br>
    <span style='font-size:45px;font-family:"Helvetica Neue", helvetica;font-weight:100'>
            <a href='<?=$rootserver?>' style='text-decoration:none'>
            <img src="../img/logo.png" style="width:auto;height:45px;">
            </a>

    </span>
        <br>
    <span style='color:firebrick;font-size:16px;font-family:"helvetica neue" helvetica;font-weight:100'>    
    </span>
    <br><br>
    <span style='font-size:30px;font-family:"Helvetica Neue" helvetica;font-weight:100'>    
        Set Up Password<br>
        to Access Chat
    </span>
    <br>
    <?=$chatpending?>
    <br>
    </center>
    <FORM id='profileedit'  ACTION='subscribesavefree.php' METHOD='POST'>
 
    
        <input id=tranmode type=hidden name=tranmode value=edit />
        <input id=buttonclicked class=buttonclicked type=hidden name=buttonclicked value='' />
        <input id=dealer class=hidden type=hidden name=dealer value='<?=$_POST[dealer]?>' />
        <br>
        
        <input id=providerid class=providerid type=hidden name=providerid value='<?=$providerid?>' />
        
        <table  class='' style='background-color:transparent;border-collapse:collapse;width:300px;margin:auto;font-size:15px;font-family:"Helvetica Neue",Helvetica,Arial,san-serif;font-weight:200'>
            
            <tr class='accountinforow mobile' style='display:none'>
            <td class=dataarea>
                <br>
                <b>Please download the free mobile app for an enhanced experience.</b>
                <br><br>
                <a class='ios' href='http://itunes.com/apps/braxme' style='text-decoration:none'>
                <img class='appstore' src='../img/appStore.png' style='height:50px' >
                </a>
                &nbsp;&nbsp;
                <a class='android' href='https://play.google.com/store/apps/details?id=me.brax.app1' style='text-decoration:none'>
                <img class='appstore' src='../img/androidplay.png' style='height:50px' >
                </a>
                <br><br>
            </td>
            </tr>
            
            <tr class=accountinforow>
            <td class=dataarea>
            Your Full Name<br>
            <input id=providername class=providername name=providername type=text value='<?=$invitename?>' size=35 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
            </td>
            </tr>
            
            
            <tr class=accountinforow>
            <td class=dataarea>
            Your Email Address<br>
            <input id=replyemail name=replyemail  type=text value='<?=$inviteemail?>' size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
            <input id=replysms name=replysms type=hidden value='<?=$invitesms?>' size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
            <input id=confirmemail name=confirmemail  type=hidden value='<?=$inviteemail?>'  size=30 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
            <input id=subscriber1 type=hidden value='<?=$providerid?>'  />
            <input id=loginid class=loginid name=loginid type=hidden value='admin'  />
            <input id=avatarurl class=avatarurl name=avatarurl type=hidden value='<?=$rootserver?>/img/faceless.png' />
            <input id=invited class=invited name=invited type=hidden value='Y'  />
            </td>
            </tr>

            
            <tr class=accountinforow>
            <td class=dataarea>
            <p style="font-size:12px">
                Use a password that has a minimum of 8<br>
                characters, utilizes upper/lower case,<br>
                numbers, and special characters. <br>
                Repeating values lowers password strength.
            </p>
                <br>New Password<br>
            <input id=password name=password  type=password value=''  size=35 maxlength=255 autocorrect='off' autocapitalize='off' style='font-size:16px;width:250px;height:20px;margin-top:3px'/><br>
            <span class='passwordscore'></span>
            </td>
            </tr>

            <tr class='accountinforow confirmpassword'>
            <td class=dataarea>
            Confirm Password<br>
            <input id=password2 name=password2  type=password value=''  size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
            </td>
            </tr>
            
        </table>
            
            
        <br>
        
            
        
        
        
        
       
        <center>
        <div class='divbutton divbutton_unsel saveprofile' id='saveprofilebutton'>
            &nbsp;&nbsp;<b>Start</b>&nbsp;
            <img src='../img/arrowhead-right-128.png' style='height:15px;position:relative;top:3px;opacity:0.7' />
        </div>
        </center>
        <input id=replyemail name=dealeremail  type=hidden  />
            
    
        </form><br><br><br>
        
        
<?php require("htmlfoot.inc"); ?>
