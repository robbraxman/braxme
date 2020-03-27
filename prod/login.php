<?php
session_start();
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type: nosniff');
header('X-XSS-Protection: 1; mode=block');
require_once("config.php");

//session_unset();
//session_destroy();
//$_SESSION['returnurl']="<a href='login.php'>Login to your Acccount</a>";

session_destroy();
$_SESSION = array();
$_SESSION['mobile']='N';
$_SESSION['pid']='';
$_SESSION['pwd_hash']='';
$_SESSION['password']='';
require("htmlhead.inc.php");
$timezone = "";

$activetextcolor = 'steelblue';

$mobile = '';
$source = '';
$gcm = '';
$apn = '';
$_SESSION['gcm']='';
$_SESSION['apn']='';

$mobile = @mysql_safe_string($_GET['mobile']);
$landing = @mysql_safe_string($_GET['l']);
$source = @mysql_safe_string($_GET['s']);
$version = @mysql_safe_string($_GET['v']);
$gcm = @mysql_safe_string($_GET['gcm']);
$_SESSION['gcm']=$gcm;
$apn = @mysql_safe_string($_GET['apn']);
$_SESSION['apn']=$apn;
$init = @mysql_safe_string($_GET['init']);
$language = @mysql_safe_string($_GET['lang']);
$roomhandle = @mysql_safe_string($_GET['h']);
$roomstorehandle = @mysql_safe_string($_GET['store']);



if($language!=''){
    $_SESSION['language']=$language;
}
require_once("internationalization.php");


$enterprise = @mysql_safe_string($_GET['e']);
$forgotlink = "$rootserver/$installfolder/forgotreq.php?apn=$apn&gcm=$gcm&s=$source&mobile=$mobile&lang=$language";
$enterprisehidden = 'hidden';
if(strtoupper($enterprise) =='E'){
    $enterprisehidden = 'text';
}

?>
<script>
$(document).ready( function() {
    
        
    var visitortime = new Date();
    $('#timezone').val(-visitortime.getTimezoneOffset()/60);
    

    try {
        
            
        $('#useragent').val(navigator.userAgent);
            
        $('.stafflogin').click( function()
        {
            if($('#loginid').is(":visible")){
                $('#loginid').val('admin');
                $('.stafflogin').html("<img class='icon15' src='../img/arrowhead-left-128.png' style='top:3px;'/> Staff Login Id");
                $('#loginid').hide();
            } else {
                $('#loginid').show();
                $('.stafflogin').html("<img class='icon15' src='../img/arrowhead-left-128.png' style='top:3px;'/>  Standard User Id");
            }
        });

        $('.divbuttontext').mouseenter( function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
        }).mouseleave( function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
        });
        
        
        $(document).on('click','.forgot', function(e){
            window.location = "<?=$forgotlink?>";
        });

        $('#pid').keyup(function(e)
        {
            var username = $('#pid').val();
            username = username.replace(/[^a-z0-9 ]/gi, "");  
            if(username !=''){
                $('#pid').val('@'+username.replace(" ",""));
            }
            
        });        


        $('body').on('keyup','#password',function(e) {
            
            if ((e.keyCode === 10 || e.keyCode === 13) && !e.shiftKey){
                $("#loginbutton").click();
            }
            if( fieldlen===0 && e.keyCode === 8){
            }
            //if(enlarge === 1) {
            if(e.which === 13) {
            }
        });        


        $(document).on('click','#loginbutton', function(e){
            
            $('form').prop('action',"console.php");
            $('.loginstatus').html('<b>Connecting...</b>');

            var providerid = $('#pid').val();
            if( providerid.indexOf("@") >= 0 ){
            } else {
                $('#pid').val('@'+providerid);
                //alertify.alert("Missing @ in handle or email. Now added.");
                //return;
            }
                
            
            
            if( 
                    $('#pid').val()!=="" &&
                    $('#password').val()!=="" 
            ){
            
                try {
                    localStorage.pid = $('#pid').val();
                    localStorage.logintype = 'C';
                    
                } catch(err) {
                
                    alertify.alert(err);
                    
                }
                $('form').submit();
                
            } else {
                alertify.alert("Invalid Credentials");
            }
            
            
        });

        $(document).on('click','.signup', function(e){
            $('form').prop('action',"invite.php?s=<?=$source?>&apn=<?=$apn?>&gcm=<?=$gcm?>&lang=<?=$language?>&v=<?=$version?>&handle=<?=$roomhandle?>");
            $('form').submit();
        });
        

        
        $('input.login').on('keypress', function (event) {
            
            var regex = new RegExp("^[,]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (regex.test(key)) {
               event.preventDefault();
               return false;
            }
            return true;
        });       
        //Initialize_LoadedForm();
         $('#password').keyup(function(e){
             
            var code = e.keyCode || e.which;
            if(code === 13) { //Enter keycode
                $('#loginbutton').trigger('click');
              return;
            }            
        });
        if(window.navigator.standalone === true ){
        
            $('#standalone').val('Y');
        }

        $('.logoimage').fadeIn("1500");

    } catch (err) {
    
        alertify.alert(err.message);
    }
    
    errorStatus = false;
    try {
        
        $('#pid').val( localStorage.pid );
        $('#loginid').val(localStorage.loginid);
        $('#stored').val(localStorage.swt);
        $('#deviceid').val(localStorage.deviceid);
        
        
        if($('#loginid').val()===''){
            $('#loginid').val("admin");
        };
        if($('#loginid').val()==='admin'){
            //$('#loginid').hide();
        };
        localStorage.removeItem('pw');
        localStorage.removeItem('password');
        

    } catch (err) {
    
        $('#loginid').val("admin");
        $('#loginid').hide();
        $('#stored').val("");
        $('#deviceid').val("");
        
        /* This gives a SEVERE error on Firefox and will stop javascript.
         * The JS script stops. So this cannot be allowed.
         * No errors on Safari so this will continue but quietly not 
         * read cookies.
         */
        alertify.alert("We value your privacy. However, we need to use Cookies, but only for validating your session identity. It is not used to track you in any way. <br><br>Please enable cookies on your browser settings to continue.")
        errorStatus = true;
        //alertify.alert(err.message);
    }
    
    try {
        if( errorStatus === false){
            localStorage.logintype = "C";
            if( localStorage.logintype !== 'C'){
                //alertify.alert("Cookies have been blocked. You will have to re-login each time.")
            }
        }
    } catch (err) {
    
        alertify.alert("Cookies have been blocked. You will have to re-login each time.")
    }
    //Auto Start if Logged in Before
    if( 
            $('#pid').val()!=="" &&
            $('#login').val()!=="" &&
            (
                $('#stored').val()!=="" 
            )
    ){
        $('body').hide();
        visitortime = new Date();
        $('#timezone').val(-visitortime.getTimezoneOffset()/60);
        $('form#Login').submit();
        
    } else {
        $('body').show();
        
    }
       
});
</script>
<title><?=$appname?></title>
</head>
<BODY class="loginbody" style="display:block;background-color:whitesmoke;">
    <center>
    <div class='maincontainer1head' style='background-color:whitesmoke' >
        <center>
        <br>
        <img class='logoimage' src="<?=$applogo?>" style=";width:auto;height:70px;">
        
        <br>
        <div class="mainfont"><b>Brax.Me. Privacy Focused Social Media. Open Source.</b></div>
        </center>
    <div class="subcontainer1 mainfont" style='padding:15px;font-family:"Helvetica Nueue",Helvetica;font-weight:100' >
        <center>
        <FORM id="Login" ACTION="<?=$rootserver?>/<?=$installfolder?>/console.php" class="form1" METHOD="POST">
        <div id="debugmsg" class="debugmsg"></div>
        <INPUT id="timezone" TYPE="hidden" NAME="timezone" value="">
        <INPUT id="source" TYPE="hidden" NAME="source" value="<?=$source?>">
        <INPUT id="deviceid" TYPE="hidden" NAME="deviceid" value="">
        <INPUT id="gcm" TYPE="hidden" NAME="gcm" value="<?=$gcm?>">
        <INPUT id="apn" TYPE="hidden" NAME="apn" value="<?=$apn?>">
        <INPUT id="version" TYPE="hidden" NAME="version" value="<?=$version?>">
        <INPUT id="init" TYPE="hidden" NAME="init" value="<?=$init?>">
        <INPUT id="language" TYPE="hidden" NAME="language" value="<?=$language?>">
        <INPUT id="enterprise" TYPE="hidden" NAME="enterprise" value="N">
        <INPUT id="useragent" TYPE="hidden" NAME="useragent" value="">
        <INPUT id="roomhandle" TYPE="hidden" NAME="roomhandle" value="<?=$roomhandle?>">
        <INPUT id="roomstorehandle" TYPE="hidden" NAME="roomstorehandle" value="<?=$roomstorehandle?>">
        </center>
            <table style='margin:auto'>
                <tr>
                    <td>
                        <p>

                        <INPUT class='login' id="pid" TYPE="email" NAME="pid" size='30' placeholder="<?='@'.$menu_handle?>" autocomplete="false" autocapitalize='off' style='font-size:18px;width:250px;border-color:lightgray;padding:10px;margin-top:3px' value=''>
                        <INPUT id="standalone" TYPE="hidden" NAME="mobile"  value=""  >
                        <INPUT id="uuid" TYPE="hidden" NAME="uuid"  value=""  >
                        <INPUT id="stored" TYPE="hidden" NAME="stored"  value=""  >
                        <!--
                        <div class='stafflogin' style='float:right;cursor:pointer'>
                            <img class='icon15' src='../img/arrowhead-left-128.png' style='top:3px;'/> Staff Login Id</div>
                        -->
                        <INPUT id="loginid" TYPE="<?=$enterprisehidden?>" NAME="loginid"  value="" size=30 placeholder='Staff Login ID' autocomplete="false" autocapitalize='off' style='font-size:16px;width:250px;border-color:lightgray;padding:5px;margin-top:3px' >
                        <br>
                        <INPUT class='password' id="password" placeholder="<?=$menu_password?>" TYPE="password" NAME="password" SIZE="30"  style='font-size:18px;width:200px;border-color:lightgray;padding:10px;margin-top:3px' autocomplete="off" autocapitalize='off' >
                        <img id='loginbutton' title='Continue' src='../img/Arrow-Right-in-Circle_120px.png' style='cursor:pointer;height:35px;position:relative;top:12px;' />
                        <div class='loginstatus'></div>
                        <noscript>
                            <div class="noscriptmsg">
                                <br>
                                <b>Please enable Javascript to run this app.</b>
                            </div>
                        </noscript>                        
                        <br>
                        <!--
                        <INPUT id="rememberme" TYPE="checkbox" value='Y' NAME="rememberme" style='position:relative;top:5px;border:0;margin:0;font-size:30px'>&nbsp;&nbsp;Remember Me<br>
                        <br> 
                        -->
                    </td>
                </tr>
            </table>
            <hr>
            <br>

<?php
    if($source!=''){
    
?>
        <div class="signup mainfont" style='color:<?=$activetextcolor?>;cursor:pointer;;width:200px'><?=$menu_createaccount?></div>
        <br>
<?php
    }
?>
        
        <div class="forgot mainfont" style='cursor:pointer;color:<?=$activetextcolor?>;;'><?=$menu_forgotpassword?></div>
    </FORM>
    </div>
<?php
if(!$customsite){
echo "<div style='position:relative;max-width:100%;float:right;text-align:center;margin:auto;padding:20px'>";
echo LanguageLinks("$rootserver/$installfolder/login.php?gcm=$gcm&apn=$apn&s=$source&v=$version","float:right","steelblue");
echo "</div>";
}
echo "<br><br><br>";
?>
</center>
</div>
</BODY>
</HTML>
