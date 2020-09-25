<?php
session_start();
//$inviteid = uniqid('',true);
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("htmlhead.inc.php");

$landing = @tvalidator("PURIFY",$_GET['l']);
//if($inviteemail[0]=='@'){
//    $inviteemail = "";
//}
$invitename = "";//@tvalidator("PURIFY",$_REQUEST['name']);
$inviteemail = "";//@tvalidator("PURIFY",$_REQUEST['invite']);
$invitesms = "";//@tvalidator("PURIFY",$_REQUEST['invitesms']);

$invitehandle = "";//@tvalidator("PURIFY",$_REQUEST['handle']);
$source = "";//@tvalidator("PURIFY",$_REQUEST['s']);

$gcm = "";//@tvalidator("PURIFY",$_REQUEST['gcm']);
$apn = "";//@tvalidator("PURIFY",$_REQUEST['apn']);
$loginlink = "";//"$rootserver/$installfolder/login.php?apn=$apn&gcm=$gcm&s=$source";
$signuplink = "$rootserver/$installfolder/signupproc.php";
$inactivatelink = "$rootserver/$installfolder/memberinactivate.php";
$resetlink = "$rootserver/$installfolder/memberreset.php";

$email = "";//@tvalidator("PURIFY",$_POST['pid']);
$providerid = "";
$invitername = "";
$inviteid = "";//@tvalidator("PURIFY",$_REQUEST['i']);
$inviteid = "";

$check = "<img src='../img/check-red-128.png' style='position:relative;top:1px;height:10px'/> ";


$mobileflag='';
if( "$gcm$apn" != "")
{
    $mobileflag='Y';
}
if($landing == '')
{
    $landing = 'Unk';
}

$selectroom = "<select class='grouproom' id='roomid' name='roomid'  style='width:250px'>";
$selectroom .= "<option value=''>(None)</option>";
$result = pdo_query("1","
        select distinct room, roomid
        from roominfo where roomid in (select roomid from statusroom where owner = $_SESSION[pid] )
        and room!=''    
        order by room
        ",null);
while($row = pdo_fetch($result))
{
    $roomname = htmlentities($row['room']);
    //$selectroom .= "<option value='$row[roomid]'>$roomname ($row[count])</option>";
    $selectroom .= "<option value='$row[roomid]'>$roomname </option>";
}
$selectroom .= "</select>";



?>
<script>
        
$(document).ready( function() 
{
    var MobileDevice = 'N';
    
        if( navigator.userAgent.match(/iPhone/i) ||
            navigator.userAgent.match(/iPad Mini/i) ||
            navigator.userAgent.match(/iPad/i) ||
            navigator.userAgent.match(/Android/i) ){
        
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
        
        $('.clearentries').click(function(){
            $('#handle').val("");
            $('#replymail').val("");
            $('#providername').val("");
            $('#password').val("");
            $('#password2').val("");
            $('#confirm').val("");
            $('#replysms').val("");
            $('.passwordhint').hide();
            $('.passwordscore').html("" );
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
        $('#deletehandle').keyup(function(e)
        {
            var handle = $('#deletehandle').val();
            handle = handle.replace(/[^a-z0-9@]/gi, "");  
            if( handle.charAt(0)!=='@' && handle!==''){
                handle = '@'+handle;
            }
            $('#deletehandle').val(handle);
        });        
        
        $('#providername').keyup(function(e)
        {
            var username = $('#providername').val();
            username = username.replace(/[^a-z0-9 ]/gi, "");  
            $('#providername').val(username);
        });        
        
        
        
        $(document).on('blur', '#handle', function(){
           $('.handlehint').hide();
            if( $('#handle').val()!=='' && $('#handle').val()!=='@'){
                    
                    //alertify.alert($('#handle').val());
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
                        if( msg.error === "handletaken" || msg.error==='emailtaken'){
                        
                            alertify.alert( msg.msg );
                            $('#handle').val("");
                            return;
                        }
                      
                      });
            }
        });

        $(document).on('click', '.signupareabutton', function(){
            $('.signuparea').show();
            $('.resetarea').hide();
            $('.inactivatearea').hide();
        });
        $(document).on('click', '.resetareabutton', function(){
            $('.signuparea').hide();
            $('.resetarea').show();
            $('.inactivatearea').hide();
        });
        $(document).on('click', '.inactivateareabutton', function(){
            $('.signuparea').hide();
            $('.resetarea').hide();
            $('.inactivatearea').show();
        });



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
            if($(this).val()=='enterprise'){
                $('.enterprise').show();
            } 
            if($(this).val()=='personal'){
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
        $(document).on('click', '#inactivateprofilebutton', function(){
            $('#inactivate').submit();
        });
        $(document).on('click', '#resetprofilebutton', function(){
            $('#reset').submit();
        });
    
 
        $('#tranmode').val('edit');
        $('.enterprise').show();
        
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
                alertify.alert('@Handles begin with a @');
                return false;
            }
            
            if($('#loginid').val()=='')
            {   
                alertify.alert('Missing Login ID');
                return false;
            }
            if($('#providername').val()=='')
            {
                alertify.alert('Missing User Name');
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
       $('.passwordhint').hide();
       $('.handlehint').hide();
       $('.accountchoice').hide(); 
});
</script>   
<title>Brax.Me</title>
</head>
<body class='mainfont' style="background-color:white">
    <br>
    <center>
    <div class='signupareabutton pagetitle2a' style='display:inline;color:firebrick;cursor:pointer'>Sign Up</div>
    &nbsp;&nbsp;&nbsp;
    <div class='inactivateareabutton pagetitle2a' style='display:inline;color:firebrick;cursor:pointer'>Inactivate</div>
    &nbsp;&nbsp;&nbsp;
    <div class='resetareabutton pagetitle2a' style='display:inline;color:firebrick;cursor:pointer'>Reset Password</div>
    <br>
    <hr style="border-color:#eaaa20;">
    </center>
    <div class='signuparea'>
        <center>

            <br>
            <span style='color:firebrick;font-size:16px;font-family:"helvetica neue" helvetica;font-weight:100'>    
            </span>
            <br>
            <span class='catchshow pagetitle2' style='cursor:pointer;'>    
                Member Sign Up
            </span>
            <span class='catchshow mainfont' style='cursor:pointer;font-family:"Helvetica Neue" helvetica;font-weight:100'>    
                <?=$invitername?>
            </span>
            <!--
            <span class='catchshow' style='cursor:pointer;font-size:12px;font-family:"Helvetica Neue" helvetica;font-weight:100'>    
                <br>
                For Personal Use
            </span>
            -->
            <br>
            <br>
            <div class='clearentries mainfont' style='color:firebrick;cursor:pointer'>Clear Entries</div>
        </center>
        <FORM id='profileedit'  ACTION='<?=$signuplink?>' METHOD='POST' target='status' style="margin:0;padding:0">


            <input id=tranmode type=hidden name=tranmode value=edit />
            <input id=buttonclicked class=buttonclicked type=hidden name=buttonclicked value='' />
            <input id=dealer class=hidden type=hidden name=dealer value='' />
            <input id=apn class=hidden type=hidden name=apn value='<?=$apn?>' />
            <input id=gcm class=hidden type=hidden name=gcm value='<?=$gcm?>' />
            <br>

            <input id=providerid class=providerid type=hidden name=providerid value='' />

            <table  class='rounded' style='border-size:1px;border-color:gray;background-color:white;;width:300px;padding-left:10px;margin:auto;font-size:13px;font-family:"Helvetica",Helvetica,Arial,san-serif;font-weight:200'  autocomplete='false'>

                <tr class='accountinforow catch' style='display:none'>
                    <td class=dataarea>
                    </td>
                </tr>

                <tr class=accountinforow>
                    <td class=dataarea>
                        <br>
                    <?=$check?> User Name<br>
                    <input id=providername class=providername name=providername type=text placeholder='User Name' value='<?=$invitename?>' size=35 maxlength='35' style='font-size:16px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
                    <br>
                    </td>
                </tr>

                <tr class='accountinforow'>
                    <td class=dataarea>
                    <?=$check?> Handle<br>
                    <input id=handle name=handle  type=text placeholder='@handle' value='' size=30 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' autocomplete='false' />
                    <br>
                    </td>
                </tr>

                <tr class='accountinforow accountchoice'>
                    <td class=dataarea>
                        <input type='radio' name='industry' class='industrygroup'  style='cursor:pointer;position:relative;top:5px' checked=checked value='personal'> Personal (Free)
                        &nbsp;&nbsp;
                        <input type='radio' name='industry' class='industrygroup'  style='cursor:pointer;position:relative;top:5px' value='enterprise'> Enterprise
                        <br><br>
                    </td>
                </tr>



                <tr class='accountinforow enterprise'>
                    <td class=dataarea>
                    Email Address (optional)<br>
                    <input id=replyemail name=replyemail  type=email placeholder='Email' value='<?=$inviteemail?>' size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
                    <input id=confirmemail name=confirmemail  type=hidden value='<?=$inviteemail?>'  size=30 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    <input id=subscriber1 type=hidden value='<?=$providerid?>'  />
                    <input id=loginid class=loginid name=loginid type=hidden value='admin'  />
                    <input id=avatarurl class=avatarurl name=avatarurl type=hidden value='<?=$rootserver?>/img/faceless.png' />
                    <input id=invited class=invited name=invited type=hidden value=''  />
                    <input id=onetimeflag class=onetimeflag name=onetimeflag type=hidden value='Y'  />
                    <input id=dealermail name=dealeremail  type=hidden  />
                    <br>
                    </td>
                </tr>



                <tr class='accountinforow enterprise'>
                    <td class=dataarea>
                    Mobile Phone No. (optional)<br>
                    <input id=replysms name=replysms  type=tel placeholder='Mobile Phone' value='<?=$invitesms?>' size=35 maxlength=30 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    <br>
                    <span class='smalltext2'>+CountryCode required if non-US. For password recovery.</span>
                    </td>
                </tr>

                <tr class='accountinforow'>
                    <td class=dataarea>
                        Room 
                        <br>
                        <?=$selectroom?>
                        <br><br>
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
                        <?=$check?> New Password<br>
                    <input id=password name=password  type=password placeholder='Password' value='' autocomplete='false' size=35 maxlength=255 autocorrect='off' autocapitalize='off' style='font-size:16px;width:250px;height:20px;margin-top:3px'/><br>
                    <span class='passwordscore'></span>
                    </td>
                </tr>

                <tr class='accountinforow confirmpassword passwordhint' style='display:none'>
                    <td class=dataarea>
                    <?=$check?> Confirm Password<br>
                    <input id=password2 name=password2  type=password value='' autocomplete='false'   size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    <input id=roomhandle name=roomhandle  type=hidden value='<?=$invitehandle?>'  size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    </td>
                </tr>



                <tr class='accountinforow'>
                    <td class=dataarea>
                    Enterprise Sponsor Code<br>
                    <input id=sponsor name=sponsor  type=text readonly="readonly" value='<?=$_SESSION['sponsor']?>' autocomplete='false'   size=35 maxlength=255 style='font-size:16px;width:250px;height:20px;margin-top:3px' />
                    </td>
                </tr>


            </table>

            <br><br>

            <center>
            <div class='divbuttontext divbutton_unsel saveprofile' id='saveprofilebutton'>
                &nbsp;&nbsp;<b>Sign Up</b>&nbsp;
                <img class='icon20' src='../img/arrow-stem-circle-right-128.png' style='position:relative;top:5px;' />
            </div>
                <br><br><br>
                <?=$check?> <span class='smalltext'>Required</span>
            </center>
            <iframe id='status' name='status' rows=1 style='margin:auto'></iframe>

        </form>
    </div>
    <div class='inactivatearea' style='display:none'>
    
        <FORM id='inactivate'  ACTION='<?=$inactivatelink?>' METHOD='POST' target='status2' style="padding:0;margin:0">

            <table  class='rounded' style='border-size:1px;border-color:gray;background-color:white;;width:300px;padding-left:10px;margin:auto;font-size:13px;font-family:"Helvetica",Helvetica,Arial,san-serif;font-weight:200'  autocomplete='false'>

                <tr class=accountinforow>
                    <td class=dataarea>
                        <div class="pagetitle2">Account Inactivation</div>
                        <br>
                        Handle of User<br>
                        <input id=deletehandle class=deletehandle name=handle type=text placeholder='Handle' value='' size=35 maxlength='35' style='font-size:16px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
                        <br><br>
                        <center>
                            <div class='divbuttontext divbutton_unsel inactivateprofile' id='inactivateprofilebutton'>
                                &nbsp;&nbsp;<b>Inactivate Member</b>&nbsp;
                                <img class='icon20' src='../img/arrow-stem-circle-right-128.png' style='hposition:relative;top:5px;' />
                            </div>
                            <br><br><br>
                        </center>
                    </td>
                </tr>
            </table>
            <iframe id='status2' name='status2' style='margin:auto'></iframe>
        </form>
    </div>
    
    <div class='resetarea' style='display:none'>
    
        <FORM id='reset'  ACTION='<?=$resetlink?>' METHOD='POST' target='status3' style="padding:0;margin:0">

            <table  class='rounded' style='border-size:1px;border-color:gray;background-color:white;;width:300px;padding-left:10px;margin:auto;font-size:13px;font-family:"Helvetica",Helvetica,Arial,san-serif;font-weight:200'  autocomplete='false'>

                <tr class=accountinforow>
                    <td class=dataarea>
                        <div class="pagetitle2">Account Password Reset</div>
                        <br>
                        Handle of User<br>
                        <input id=resethandle class=resethandle name=handle type=text placeholder='Handle' value='' size=35 maxlength='35' style='font-size:16px;width:250px;height:20px;margin-top:3px'  autocomplete='false'/>
                        <br><br>
                        <center>
                            <div class='divbuttontext divbutton_unsel resetprofile' id='resetprofilebutton'>
                                &nbsp;&nbsp;<b>Reset Password of Member</b>&nbsp;
                                <img class='icon20' src='../img/arrow-stem-circle-right-128.png' style='hposition:relative;top:5px;' />
                            </div>
                            <br><br><br>
                        </center>
                    </td>
                </tr>
            </table>
            <iframe id='status3' name='status3' style='margin:auto'></iframe>
        </form>
    </div>
    
    
    
    <br><br><br>
        
<?php require("htmlfoot.inc"); ?>
