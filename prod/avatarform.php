<?php
session_start();
require_once("validsession.inc.php");
require_once("config-pdo.php");
require_once("password.inc.php");

require_once("htmlhead.inc.php");
$providerid = @tvalidator("PURIFY",$_SESSION['pid']);
$loginid = @tvalidator("PURIFY",$_SESSION['loginid']);
$devicetype = @tvalidator("PURIFY",$_POST['devicetype']);

$result = pdo_query("1","select publish, publishprofile from provider where providerid=?",array($providerid));
if($row = pdo_fetch($result)){
    $publishprofile = $row['publishprofile'];
    if($row['publish']=='Y'){
        $publishchecked = "checked=checked";
    }
}

?>
<script>
   $(document).ready( function() {
        $('#pid').val( '<?=$providerid?>' ); 
        $('#loginid').val( '<?=$loginid?>' ); 
        providerid = '<?=$providerid?>'; 
        loginid = '<?=$loginid?>'; 
        
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
        
        
        $('#testavatar').click( function() 
        {
            
            if($('#avatarurl').val()=="")
            {
                alertify.alert("No URL Link provided");
                return;
            }
            
            $('#avatarimage').prop("src", $('#avatarurl').val());
            
        });

        $('#save').click( function() 
        {
            $('#status').load("avatarsave.php", { 
                'mode':'S', 
                'pid': $('#pid').val(),
                'loginid':  loginid, 
                'avatarurl': $('#avatarurl').val()
            }, function(data, status){
            });
        });
        $('#upload').click( function() 
        {
            $('#uploadavatar').submit();
        });
        $('.upload_an_avatar').click( function() 
        {
            window.open("braxme://avatar","_self");
            alert('test1');
        });
   
 
       
    });
       </script>
    <title>Profile Picture</title>
</head>    
<?php
if($devicetype == ''){
?>
<BODY class="appbody mainfont" >

    <div class="showtable" style='margin:auto'>
        <table style='margin:auto'>

        <tr>
        <td class='dataarea'>
        <span class='pagetitle'>My Profile</span>
        </td>
        </tr>

        <tr>
        <td class='dataarea'>
            <img id='avatarimage' name='avatarimage' src='<?=$_SESSION['avatarurl']?>' width='200' height='auto'></img>
        </td>
        </tr>
       
            
        
        <tr>
        <td class='feedphoto dataarea'>
        <label for="fileupload">Upload Profile Photo
            </label><br>
            <form id="uploadavatar" method="POST" action="photouploadproc.php" enctype="multipart/form-data" >
                <input id='fileupload' class='fileupload' type='file' name='file[]' accept='image/*' multiple="multiple" size='20'>        
                <input type="hidden" name="MAX_FILE_SIZE" value="20480000">&nbsp;&nbsp;
                <span class="formobile"><br></span>
                <div class="divbutton3 divbutton3_unsel" id="upload">Upload</div>
                <span class="formobile"><br></span>
                <INPUT TYPE="hidden" name="subject" value="Profile Photo" >
                <INPUT TYPE="hidden" name="album" value="Profile Photo" >
                <INPUT TYPE="hidden" name="uploadtype" value="A" >
                
                <INPUT class="loginid" TYPE="hidden" value="<?=$loginid?>">
                <INPUT TYPE="hidden" name="password" >
                
            </form>
            
            <br><span class='smalltext2' style='font-size:10px'>You can also set a profile photo from My Photos.</span>
        </td>
        </tr>
<?php
if($_SESSION['superadmin']=='Y'){
?>    

        
        <tr>
        <td class='dataarea'>
            <br><br>
        <label for="bio">Biography
            </label><br>
            <form id="bio" method="POST" action="bio.php"  >
                <input name='publish' value='Y' <?=$publishchecked?> type='checkbox' style=';position:relative;top:5px' /> Show in Public List
                <br><br>
                <textarea name='publishprofile' style='width:100%;height:200px'><?=$publishprofile?></textarea>
                <br><br>
                
                <div class="divbutton3 divbutton3_unsel" id="upload">Save Changes</div>
                
                <INPUT class="loginid" TYPE="hidden" value="<?=$loginid?>">
                
            </form>
        </td>
        </tr>
<?php
}
?>    
        
        <!--
        <tr>
        <td class='dataarea'>
        <span class="nonmobile">
            or<br>
            Image Url Link<br>
            <INPUT id="avatarurl" TYPE="avatarurl" NAME="avatarurl" SIZE="35" >
            <div class="divbutton3 divbutton3_unsel" id="testavatar">Test Picture</div>
            <div class="divbutton3 divbutton3_unsel" id="save">Save Link</div>
            &nbsp;&nbsp;
        </span>
        
        <INPUT id="loginid" class="loginid" TYPE="hidden" value="<?=$loginid?>">
        <INPUT id="password" TYPE="hidden" name="password" >
        <INPUT id="pid" class="pid" TYPE="hidden" >
        </td>
        </tr>
        -->
        
        </table>
    </div>
        <br>
        <br>
        <br>
    <div id="status" class="status" ></div>
</BODY>
</HTML>
<?php
    exit();
}
?>
<BODY class="appbody mainfont" >

    <div class="showtable">
        <table>

        <tr>
        <td class='dataarea'>
        <span class='pagetitle'>Change Profile Picture</span>
        </td>
        </tr>

        <tr>
        <td class='dataarea'>
            <img id='avatarimage' name='avatarimage' src='<?=$_SESSION['avatarurl']?>' width='200' height='auto'></img>
        </td>
        </tr>
       
            
            
        

        <tr>
            <td class='dataarea' feedphoto>
            <br><br>
            Upload a Photo from Your Mobile Device
            <br><br>
            <div class="upload_an_avatar divbutton3">Choose Photo to Upload</div>

            </td>
        </tr>
        
        
        </table>
    </div>
    <br>
    <br>
    <br>
</BODY>
</HTML>
