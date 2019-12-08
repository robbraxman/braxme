<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("password.inc.php");
require_once("internationalization.php");
require_once("internationalization2.php");

require_once("htmlhead.inc.php");
?>
<script>
   $(document).ready( function() {
        $('#pid').val( '<?=$_SESSION['pid']?>' ); 
        $('#loginid').val( '<?=$_SESSION['loginid']?>' ); 
        providerid = '<?=$_SESSION['pid']?>'; 
        loginid = '<?=$_SESSION['loginid']?>'; 
        
            $('td.label').hide();
            $('td.labelrequired').hide();
            $('div.label').show();
            $('div.labelrequired').show();
        $('body').on("mouseenter", ".divbutton3", function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
            
        });
        $('body').on("mouseleave", ".divbutton3", function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
            
        });
        
        $('#staffpassword').keyup(function(){
            var n = checkPassStrength( $('#staffpassword').val());
            $('.passwordscore').html(n);
            
        });
 
        
        $("form").submit(function() {

            if( $('#staffpassword').val() != $('#confirmpassword').val() )
            {
                alertify.alert("Passwords don't match. Please reenter.");
                return false;
            }
           
        });

        $('#save').click( function() 
        {
            if( scorePassword( $('#staffpassword').val() ) < 30 )
            {
                alertify.alert('Please enter a more secure password. Read the suggested rules.');
                return false;
                    
            }
            if( $('#staffpassword').val()!='' && $('#staffpassword').val() != $('#confirmpassword').val() )
            {
                alertify.alert("Passwords don't match. Please reenter.");
                return false;
            }
            $('#status').load("staffsave.php", { 
                'mode':'P', 
                'pid': $('#pid').val(),
                'loginid':  loginid, 
                'staffloginid':  loginid,
                'staffpassword' : $('#staffpassword').val()
            }, function(data, status){
                $('.showtable').hide();
            });
        });
   

       
    });
       </script>
    <title><?=$menu_changepassword?></title>
</head>    
<BODY class="appbody mainfont" >

    <div class="showtable">
        <table>

        <tr>
        <td class='dataarea'>
        <span class='pagetitle'><?=$menu_changepassword?></span><br><br>
        </td>
        </tr>

       
            
            
        <tr>
        <td class='dataarea'>
            <div class=smalltext style='width:250px'>
                <?=$lang_passwordtip?>
            </div>
            <br><br>
        <?=$menu_password?><br>
        <INPUT id="staffpassword" TYPE="password" NAME="staffpassword" SIZE="30" maxlength="30"><span class='passwordscore' style="white-space:nowrap"></span>
        <INPUT id="loginid" class="loginid" TYPE="hidden" value="<?=$_SESSION['loginid']?>">
        <INPUT id="password" TYPE="hidden" name="password" >
        <INPUT id="pid" class="pid" TYPE="hidden" >
        </td>
        </tr>
        
        <tr>
        <td class='dataarea'>
        <?=$menu_confirmpassword?><br>
        <INPUT id="confirmpassword" TYPE="password" NAME="confirmpassword" SIZE="30" maxlength="30">
        </td>
        </tr>

        <tr>
        <td class='dataarea'>
            <br>
        <div class="divbutton3 divbutton3_unsel" id="save"><?=$menu_save?></div>
        </td>
        </tr>
        
        </table>
    </div>
        <br>
        <br>
        <br>
    <div id="status" class="status" ></div>
</BODY>
</HTML>

