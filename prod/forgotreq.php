<?php
session_start();
require_once("config.php");
require_once("htmlhead.inc.php");

$_SESSION['returnurl']="<a href='login.php'>Login to your Acccount</a>";
$_SESSION['mobile']='N';
$_SESSION['pid']='';
$_SESSION['pwd_hash']='';
$timezone = "";

$mobile = '';
$source = '';
$gcm = '';
$apn = '';
$_SESSION['gcm']='';
$_SESSION['apn']='';

$mobile = @tvalidator("PURIFY",$_GET['mobile']);
$landing = @tvalidator("PURIFY",$_GET['l']);
$source = @tvalidator("PURIFY",$_GET['s']);
$gcm = @tvalidator("PURIFY",$_GET['gcm']);
$_SESSION['gcm']=$gcm;
$apn = @tvalidator("PURIFY",$_GET['apn']);
$_SESSION['apn']=$apn;
$version = @tvalidator("PURIFY",$_GET['v']);


$language = @tvalidator("PURIFY",$_GET['lang']);

if($language!=''){
    $_SESSION['language']=$language;
}
require_once("internationalization.php");


/*
 * Implementation Notes
 * Step 1 is forgotreq.php which prompts for username or handle
 * Step 2 is forgotemail which then sends email or text for reset request
 * Step 3 is frset which is the actual reset to the password (temporary password)
 */

$login = "$rootserver/$installfolder/login.php?apn=$apn&gcm=$gcm&mobile=$mobile&s=$source&v=$version";

$validmobiletoken = false;
$forgotcall = "$rootserver/$installfolder/forgotemail.php";
$forgotmode = "One-Time-Use Password Request";
$forgotsig = "";

$result = do_mysqli_query("1"," 
        select * from notifytokens where token='$apn$gcm' and status='Y'
        ");
if( $row = do_mysqli_fetch("1",$result) ){
    $validmobiletoken = true;
    $forgotsig = session_id();
}
$loginid = 'admin';



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
        
        
    } 
    catch (err) 
    {
        //alertify.alert(err.message);
    }
            

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
            if( $('#pid').val()===''){
                return;
            }
            if($('#loginid').val()==''){
                $('#loginid').val("admin");
            };
            
            $('.forgot').hide();
            
            
            $.ajax({
                url: "<?=$forgotcall?>",
                context: document.body,
                type: 'POST',
                data: 
                 { 'pid': $('#pid').val(), 
                   'loginid': $('#loginid').val(),
                   's': '<?=$forgotsig?>'
                 }
            }).done(function(data,status){ 
                    if( status === "success"){
                        setTimeout(function(){
                            alertify.alert( data, function(){
                                localStorage.removeItem('password');
                                localStorage.removeItem('pw');
                                localStorage.removeItem('swt');
                                window.location = '<?=$login?>';
                            });
                        },500);
                    }
                
            }).fail(function(data,status){
                alert('<?=$forgotcall?>'+'fail'+data+status);
            });            
            
        });

        
        $('body').show();
        $('input.login').on('keypress', function (event) {
            
            var regex = new RegExp("^[,]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (regex.test(key)) {
               event.preventDefault();
               return false;
            }
            return true;
        });       



       
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
        <a href='<?=$login?>'>
        <img src="../img/logo-b2.png" style="width:auto;height:35px;">
        <br>
        <img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' style='cursor:pointer;' />
        <?=$menu_back?>
        </a>
        
        <!--
        <span style="font-family:'Courier New';font-size:50px">Brax.ME</span>
        -->
        <br>
        <br>
        </center>
    <div class="subcontainer1 mainfont" style='padding:15px;font-family:"Helvetica Nueue",Helvetica;font-weight:100' >
        <center>
        <FORM id="Login" ACTION="console.php" class="form1" METHOD="POST">
        <div id="debugmsg" class="debugmsg"></div>
        <INPUT id="timezone" TYPE="hidden" NAME="timezone" value="">
        <INPUT id="loginid" TYPE="hidden" NAME="loginid" value="<?=$loginid?>">
        <INPUT id="source" TYPE="hidden" NAME="source" value="<?=$source?>">
        <INPUT id="gcm" TYPE="hidden" NAME="gcm" value="<?=$gcm?>">
        <INPUT id="apn" TYPE="hidden" NAME="apn" value="<?=$apn?>">
        <INPUT id="enterprise" TYPE="hidden" NAME="enterprise" value="N">
        </center>
            <table style='margin:auto'>
                <tr>
                    <td>
                        <p>
                            <!--
                            <div class='pagetitle2'>Reset Password Request</div>
                            -->

                            <INPUT class='login' id="pid" TYPE="email" NAME="pid" size='30' placeholder="@<?=$menu_handle?> <?=$menu_or?> <?=$menu_email?>" autocomplete="false" autocapitalize='off' style='font-size:16px;width:250px;border-color:lightgray;padding:5px;margin-top:3px' value=''>
                        <div class="forgot">
                            <?=$forgotmode?>
                            <img class='icon20' src='../img/Arrow-Right-in-Circle_120px.png' style='cursor:pointer;' />
                        </div>                        
                        <br><br>
                        <center>
                        If you set up an Authenticator app (Google Authenticator or Authy) for <?=$appname?>, use the 6 digit code from 
                        that as your password (One Time Password).
                            
                        </center>
                    </td>
                </tr>
            </table>
            <br>

<?php
    if($source!='')
    {
?>
        <br><br>
<?php
    }
?>
        <br>
        <br>
        
    </FORM>
    </div>
</center>
</div>
</BODY>
</HTML>
