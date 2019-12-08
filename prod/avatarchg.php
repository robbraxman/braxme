<?php
session_start();
require("config.php");
require_once("internationalization.php");
$mode = @mysql_safe_string($_POST['mode']);
$chatid = @mysql_safe_string($_POST['chatid']);
$buttonback = "<img class='selectchatlist tapped icon20' src='../img/arrow-stem-circle-left-128.png' style='padding-top:0;padding-left:10px;padding-bottom:0px;' />";
$bio = @mysql_safe_string($_POST['bio']);
$publish = @mysql_safe_string($_POST['publish']);
$providerid = $_SESSION['pid'];
$loginid = $_SESSION['loginid'];

if( $mode == 'S'){
    
    if($publish!='Y'){
        $publish = "N";
    }
    $bio = StripEmojis($bio);
    
    if($publish == 'Y'){
        do_mysqli_query("1","
            update provider set publishprofile = '$bio', 
            publish='Y', 
            lastactive=now() where providerid = $_SESSION[pid]
        ");
    } else {
        do_mysqli_query("1","
            update provider set publishprofile = '$bio', 
            publish='N' 
            where providerid = $_SESSION[pid]
        ");
        
    }
    $bio = stripslashes($bio);
    $mode = "";
}

if($mode == ''){
    
    $publishchecked = "";
    $result = do_mysqli_query("1"," 
        select publishprofile, publish from provider where providerid = $_SESSION[pid]
            ");
    if($row = do_mysqli_fetch("1",$result)){
        $bio = $row['publishprofile'];
        $publish = $row['publish'];
        if($publish == 'Y'){
            $publishchecked = "checked=checked";
        }
    }
}

$action = "feed";
if(intval($_SESSION['profileroomid'])==0){
    $action = "userview";
}
$uploadavatarcamera='uploadavatarcamera';
SaveLastFunction($providerid,"A","");

?>
 
<div class='aboutarea pagetitle2' >
    <div class='pagetitle3' style='background-color:<?=$global_menu2_color?>;color:white;text-align:left;padding:5px'>
        <?=$menu_setprofilephoto?>
    </div>
    <div class='abouttext pagetitle2a' style='background-color:<?=$global_background?>;color:<?=$global_textcolor?>;margin:auto;text-align:center;padding:20px'>
        <br>
        
        <span class='formobile'>
            <div class='uploadavatarcamera circular2 gridnoborder' style='width:150px;height:150px;margin:auto'>
                <img class='avatarimage <?=$action?>' src="<?=$_SESSION['avatarurl']?>" data-providerid='<?=$providerid?>' data-roomid='<?=$_SESSION['profileroomid']?>' data-caller='none' title='Back to user profile' data-caller='none' style='cursor:pointer;width:100%' />
            </div>
            <br><br>
            <div class='divbuttontext <?=$uploadavatarcamera?>'> <?=$menu_uploadphoto?></div>
        </span>
        <span class='nonmobile'>
                <div class='circular2' style='width:150px;height:150px;margin:auto'>
                    <img class='avatarimage <?=$action?>' src="<?=$_SESSION['avatarurl']?>" data-providerid='<?=$providerid?>' data-roomid='<?=$_SESSION['profileroomid']?>' data-caller='none' title='Back to user profile' data-caller='none' style='cursor:pointer;width:100%' />
                </div>
                <br>
                <label for="fileupload"> <?=$menu_uploadphoto?></label><br>
                <form id="uploadavatar" method="POST" action="photouploadproc.php" enctype="multipart/form-data" >
                    <input id='fileupload' class='fileupload' type='file' name='file[]' accept='image/*' multiple="multiple" size='20' style='height:40px;border-width:1px'>        
                    <input type="hidden" name="MAX_FILE_SIZE" value="20480000">&nbsp;&nbsp;
                    <span class="formobile"><br></span>
                    <input type='submit' value='<?=$menu_uploadphoto?>' style='padding:5px'/>
                    <span class="formobile"><br></span>
                    <INPUT TYPE="hidden" name="subject" value="Profile Photo" >
                    <INPUT TYPE="hidden" name="album" value="Profile Photo" >
                    <INPUT TYPE="hidden" name="uploadtype" value="A" >

                    <INPUT class="loginid" TYPE="hidden" value="<?=$loginid?>">
                    <INPUT TYPE="hidden" name="password" >
                    <br>

                </form>
                
                <br><br>

        </span>
        <br>
<?php
if($_SESSION['language']=='english'){
?>
        <span class='mainfont'>You can also use any photo in My Photos and click on 'Use as Profile Photo'.</span>
        <span class='formobile mainfont'>Or use the Camera icon to take a new photo.</span>
        <br><br>
        <div class='photolibrary' style='cursor:pointer;color:<?=$global_activetextcolor?>'>My Photos</div>
<?php
}
?>
        <br>
        <br>
    </div>
    <div class='pagetitle3' style='background-color:<?=$global_menu2_color?>;color:white;text-align:left;padding:5px'>
        <?=$menu_biography?>
    </div>
    <div class='abouttext pagetitle2a' style='background-color:<?=$global_background?>;color:<?=$global_textcolor?>;margin:auto;text-align:center;vertical-align:top;padding:0'>
            <br>
        
            <input class='publish' name='publish' value='Y' <?=$publishchecked?> type='checkbox' style=';position:relative;top:5px' /> <?=$menu_public?>
            <br><br>
            <textarea  class='mainfont publicbio' id=publicbio name='publicbio' placeholder="<?=$menu_biography?>" style='max-width:500px;width:90%;height:200px'><?=$bio?></textarea>
            <br><br>

            <div class="divbutton3 divbutton3_unsel savebio" id="upload"><?=$menu_save?></div>

            <INPUT class="loginid" TYPE="hidden" value="<?=$loginid?>">

            <br><br><br>
        
        
        
    </center>    
    </div>
</div>    

       
                   

