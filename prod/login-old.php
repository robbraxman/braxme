<?php
session_start();
require_once("config.php");
/*
$_SESSION[uuid] = mysql_safe_string($_GET['uuid']);

if($_SESSION[uuid]==='') {
    $_SESSION[uuid]=session_id();
}
 * 
 */
//session_unset();
//session_destroy();
$_SESSION['returnurl']="<a href='login.php'>Login to your Acccount</a>";
session_destroy();
$_SESSION = array();
$_SESSION['mobile']='N';
$_SESSION['pid']='';
$_SESSION['pwd_hash']='';
$_SESSION['password']='';
require("htmlhead.inc.php");
$timezone = "";

$mobile = '';
$source = '';
$gcm = '';
$apn = '';
$_SESSION['gcm']='';
$_SESSION['apn']='';

$mobile = @mysql_safe_string($_GET['mobile']);
$landing = @mysql_safe_string($_GET['l']);
$source = @mysql_safe_string($_GET['s']);
$gcm = @mysql_safe_string($_GET['gcm']);
$_SESSION['gcm']=$gcm;
$apn = @mysql_safe_string($_GET['apn']);
$_SESSION['apn']=$apn;
//if($gcm!='' || $apn!=''){
//    $source='mobile';
//}
$forgotlink = "$rootserver/$installfolder/forgotreq.php?apn=$apn&gcm=$gcm&s=$source&mobile=$mobile";
?>
<script>
$(document).ready( function() {
    
        
    var visitortime = new Date();
    $('#timezone').val(-visitortime.getTimezoneOffset()/60);

    try {
        
        $('#pid').val( localStorage.pid );
        $('#loginid').val(localStorage.loginid);
        
        if($('#loginid').val()==''){
            $('#loginid').val("admin");
        };
        if($('#loginid').val()=='admin'){
            $('#loginid').hide();
        };
        
        if( typeof localStorage.pw !== "undefined"){
            $('#password').val(decrypt_string(localStorage.pw,"") );
        } else {
            $('#password').val(localStorage.password);
            localStorage.removeItem("password");
        }

    } 
    catch (err) 
    {
        //alertify.alert(err.message);
    }
            
        $('#useragent').val(navigator.userAgent);
            
            
        $('.stafflogin').click( function()
        {
            if($('#loginid').is(":visible")){
                $('#loginid').val('admin');
                $('.stafflogin').html('< Staff Login Id');
                $('#loginid').hide();
            }
            else {
                $('#loginid').show();
                $('.stafflogin').html('< Standard User Id');
            }
        });

        /*
        if( $('#password').val() != "")
        {
            $('#rememberme').prop("checked","checked");
        }
        */

        $('#pid').click( function()
        {
            $('#tranmode').val("edit");
        });

        $('.divbuttontext').mouseenter( function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
        }).mouseleave( function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
        });
        
        
        $(document).on('click','.forgot', function(e){
            window.location = "<?=$forgotlink?>";
            

        });

        $(document).on('click','#loginbutton', function(e){
            $('form').prop('action',"console.php");
            if( 
                    $('#pid').val()!=="" &&
                    $('#password').val()!=="" 
            )
            {
                try {
                    localStorage.pid = $('#pid').val();
                    localStorage.logintype = 'C';
                    //localStorage.password = $('#password').val();
                    localStorage.pw = encrypt_string( $('#password').val(),"");
                    localStorage.removeItem('password');
                    
                }
                catch(err)
                {
                    alertify.alert(err);
                    
                }
                $('form').submit();
                
            }
            
            
        });

        $(document).on('click','.signup', function(e){
            $('form').prop('action',"invite.php?s=<?=$source?>&apn=<?=$apn?>&gcm=<?=$gcm?>");
            $('form').submit();
        });
        
        $(document).on('click','#vpnkeyhelp', function(){
                    
        });
        
        
           
        function Initialize_LoadedForm()
        {
           
           $("#Login").validate({
                   rules: {
                       pid: { required: true, minlength: 9 },
                       login: { required: true, minlength: 1 },
                       password: { required: true, minlength: 1 } //,
                   }
           });
        }
        
        
        if( 
                $('#pid').val()!=="" &&
                $('#login').val()!=="" &&
                $('#password').val()!=="" 
        )
        {
            $('form#Login').submit();
        }
        else
        {
            $('body').show();

        }
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
        if(window.navigator.standalone === true )
        {
            $('#standalone').val('Y');
        }

        function encrypt_string( pass, key )
        {
                if( key === ""){
                    key = "#8sAfKaynMrxtdA927h"
                }
                var encrypted="";
                for(i=0;i<pass.length;++i)
                {
                        encrypted+=String.fromCharCode(key.charCodeAt(i)^pass.charCodeAt(i));
                }
                return B64.encode(encrypted);
        }

        function decrypt_string( encrypted, key )
        {
                if( key === ""){
                    key = "#8sAfKaynMrxtdA927h"
                }
                var pass="";
                source = B64.decode(encrypted);

                for(i=0;i<source.length;i++)
                {
                        pass+=String.fromCharCode(key.charCodeAt(i)^source.charCodeAt(i));
                }
                return pass;
        }
        


       
});
</script>
<title><?=$appname?></title>
</head>
<BODY class="loginbody" style=";display:none;background-color:#E5E5E5;">
    <!--
      background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url(../img/blurred-background-10.jpg);background-repeat:no-repeat;background-size:cover">
    -->
    <center>
    <div class='maincontainer1head' >
        <center>
        <br>
        <br>
        <br>
        <img src="../img/logo.png" style="width:auto;height:35px;">
        
        <br>
        </center>
    <div class="subcontainer1 mainfont" style='padding:15px;font-family:"Helvetica Nueue",Helvetica;font-weight:100' >
        <center>
        <FORM id="Login" ACTION="console.php" class="form1" METHOD="POST">
        <div id="debugmsg" class="debugmsg"></div>
        <INPUT id="timezone" TYPE="hidden" NAME="timezone" value="">
        <INPUT id="source" TYPE="hidden" NAME="source" value="<?=$source?>">
        <INPUT id="gcm" TYPE="hidden" NAME="gcm" value="<?=$gcm?>">
        <INPUT id="apn" TYPE="hidden" NAME="apn" value="<?=$apn?>">
        <INPUT id="enterprise" TYPE="hidden" NAME="enterprise" value="N">
        <INPUT id="useragent" TYPE="hidden" NAME="useragent" value="">
        </center>
            <table style='margin:auto'>
                <tr>
                    <td>
                        <p>

                        <INPUT class='login' id="pid" TYPE="email" NAME="pid" size='30' placeholder='Email or @handle' autocomplete="off" autocapitalize='off' style='font-size:16px;width:250px;border-color:lightgray;padding:5px;margin-top:3px' value=''>
                        <INPUT id="standalone" TYPE="hidden" NAME="mobile"  value=""  >
                        <INPUT id="uuid" TYPE="hidden" NAME="uuid"  value=""  >
                        <br>
                        <div class='stafflogin' style='float:right;cursor:pointer'>< Staff Login Id</div>
                        <INPUT id="loginid" TYPE="text" NAME="loginid"  value="" size=30 placeholder='Login ID' autocomplete="off" autocapitalize='off' style='font-size:16px;width:250px;border-color:lightgray;padding:5px;margin-top:3px' >
                        <INPUT class='password' id="password" placeholder='Password' TYPE="password" NAME="password" SIZE="30"  style='font-size:16px;width:200px;border-color:lightgray;padding:5px;margin-top:3px' autocomplete="off" autocapitalize='off' >
                        <img id='loginbutton' src='../img/arrow-stem-circle-right-128.png' style='cursor:pointer;height:35px;position:relative;top:12px;' />
                        
                        <br>
                        <!--
                        <INPUT id="rememberme" TYPE="checkbox" value='Y' NAME="rememberme" style='position:relative;top:5px;border:0;margin:0;font-size:30px'>&nbsp;&nbsp;Remember Me<br>
                        <br> 
                        -->
                        <center>
                            
                        </center>
                    </td>
                </tr>
            </table>
            <hr>
            <br>

<?php
    if($source!='')
    {
?>
        <br>
        <div class="signup" style='cursor:pointer;font-family:"Helvetica Neue",Helvetica;font-weight:500;width:200px'>New Account Sign Up</div>
        <br>
<?php
    }
?>
        <br>
        <div class="forgot" style='cursor:pointer;color:firebrick;font-family:"Helvetica Neue",Helvetica;font-weight:300'>Forgot Password</div>
        <br>
        <br>
        <br>
                        <span class="smalltext" style='color:gray'><?=$source?></span>
        
    </FORM>
    </div>
</center>
</div>
<script type="text/javascript">
  (function() {
    var sa = document.createElement('script'); sa.type = 'text/javascript'; sa.async = true;
    sa.src = ('https:' == document.location.protocol ? 'https://cdn' : 'http://cdn') + '.ywxi.net/js/1.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sa, s);
  })();
</script></BODY>
</HTML>
