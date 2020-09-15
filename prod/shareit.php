<?php
session_start();
include("config.php");

$share = @tvalidator("PURIFY", $_POST['share'] );
$shareHtml = htmlentities(stripslashes($share),ENT_QUOTES);

$page = @tvalidator("PURIFY", $_POST['page'] );
$proxy = @tvalidator("PURIFY", $_POST['proxy'] );
$sharetype = @tvalidator("PURIFY", $_POST['sharetype'] );
$providerid = $_SESSION['pid'];
$shareto = @tvalidator("PURIFY", $_POST['shareto'] );
$sharetitle = @tvalidator("PURIFY", $_POST['sharetitle'] );
$shareopentitle = @tvalidator("PURIFY", $_POST['shareopentitle'] );
$expire = @tvalidator("PURIFY", $_POST['expire'] );
$platform = @tvalidator("PURIFY", $_POST['platform'] );
$mode = rtrim(@tvalidator("PURIFY", $_POST['mode'] ));


$stdproxy='T4AZ543daed3439067.08655546';
$stdproxylink = "$rootserver/$installfolder/sharedirect.php?p=690001027_543daed31155c_1.png";
$result = do_mysqli_query("1", "
        select filename, alias from photolib where filename in (select filename from
        photoproxy where providerid=$providerid )
        ");
if($row = do_mysqli_fetch("1",$result))
{
    $stdproxy = "$row[alias]";
    $stdproxylink = "$rootserver/$installfolder/sharedirect.php?p=$row[filename]";
}


$iconlock = "$rootserver/img/lock.png";
$proxyorig = '';

if($mode == "")
{
    if( $sharetype == 'P')
    {
        $sharetext = "";
        $shareimagelink = "https://bytz.io/$installfolder/sharedirect.php?p=$share";
        $shareimageopen = "https://bytz.io/$installfolder/sharedirect.php?p=$share";

        $result = do_mysqli_query("1", "
            select filename, title, alias, public from photolib where filename='$share' 
                ");
        if($row = do_mysqli_fetch("1",$result))
        {
            $privatetitle = $row['title'];
            $proxy = $row['alias'];
            $proxyorig = $proxy;
            $proxyoriglink = "https://bytz.io/$installfolder/sharedirect.php?a=$proxy";
            $public = $row['public'];
            $publictitle = ("$appname Privacy Enhanced Post");
            if( $public == 'Y'){
                $publictitle = "$appname";
            }
        }
        $sharefilename = $share;
    }
    else
    {
        $share = stripslashes( $_POST['share'] );
        $shareForSql = tvalidator("PURIFY",$share);
        
        
        $sharetext = "<br>Album: $share<br>";
        $shareimagelink = $iconlock;
        $result = do_mysqli_query("1","
            select filename, alias, folder, title
            from photolib where 
            ( album = '$shareForSql' and providerid = $providerid and public!='Y' )
            order by filename asc limit 1
            ");

        
        
        if( $row = do_mysqli_fetch("1",$result))
        {
            $shareimageopen = "https://bytz.io/$installfolder/sharedirect.php?p=$row[filename]";
            //$shareimagelink = $iconlock;
            $shareimagelink = $shareimageopen;
            $proxy = $row['alias'];
            $proxyorig = $proxy;
            $proxyoriglink = "https://bytz.io/$installfolder/sharedirect.php?a=$proxy";
            $privatetitle = $row['title'];
            $publictitle = "Brax.Me Privacy Enhanced Post";
        }
        $sharefilename = "";
    }
}
$result2 = do_mysqli_query("1","
    select proxy from provider where providerid=$providerid
    ");
if( $row2 = do_mysqli_fetch("1",$result2))
{
    $proxyflag = $row2['proxy'];
    if( $proxyflag=='Y')
    {
        $proxy1selected = "checked=checked";
        $proxy2selected = "";
    }
    else
    {
        $proxyflag='N';
        $proxy1selected = "";
        $proxy2selected = "checked=checked";
    }   

    
    
    $proxychoice = "";
    if($platform == 'F' || $platform == 'G')
    {
        $proxychoice = "
        <input id='proxy1' name='proxydefault' type='radio' 
        value='Y' $proxy1selected
        data-target='#shareit_proxy' data-src='#proxyphotoimg' data-link='$stdproxylink' 
        data-alias='$stdproxy' data-mode='No-Proxy' 
        data-proxydefault='Y'    
            
        class='noproxy setproxy'
        style='cursor:pointer;position:relative;top:5px'
            >        
        <span style='font-size:11px'>
        Use Proxy Image   
        </span>

        &nbsp;&nbsp;<input id='proxy2' name='proxydefault' type='radio' 
        value='N' $proxy2selected
        data-target='#shareit_proxy' data-src='#proxyphotoimg' data-link='$proxyoriglink' 
        data-alias='$proxyorig' data-mode='No-Proxy' 
        data-proxydefault='N'    
        class='noproxy setproxy'
        style='cursor:pointer;position:relative;top:5px'
        >
        <span style='font-size:11px'>
        No Proxy Image   
        </span>
            ";
    }
}

//Twitter does not have Shared Image
if( $platform == 'T')
{
    $shareimageopen = "$rootserver/img/twitter.png";
    $publictitle = "";
}

$subtitle = "";
$directlink = "";
//****************************************************************
//****************************************************************
//****************************************************************
//****************************************************************
if( $platform == 'F')
{
    $proxy= $stdproxy;
    $shareimageopen = $stdproxylink;
    /*
    if( $public == 'Y' || $proxyflag == 'N' )
    {
        $proxy = $proxyorig;
        $shareimageopen = $proxyoriglink;
    }
     * 
     */
    $platformname = "Facebook";
    $title = "Facebook Post";
    $subtitle = "<b>Creates a trackable and expiring share for FB Timeline</b><br><br>";
    $hidden = 'display:none';
    $publicheading = "Facebook Timeline View"; 
    $publicsubheading = "
        <br>
        <span class='smalltext'>
        Use an optional proxy photo for your FB Timeline.
        </span>
        ";
    if($public == 'Y')
    {
        $publicsubheading = "
        <br>
        Non-Private Photo
        ";
        
    }
    $publicheading_title = "Proxy/Facebook Timeline Title"; 
    $shareto = "Facebook";
            
    if( $mode == "S"){
        $title = "Facebook Post";
    }
}
else
if( $platform == 'T')
{
    $platformname = "Twitter";
    $title = "Twitter Post";
    $subtitle = "<b>Creates a trackable and expiring share for Twitter</b><br>";
    $publicheading = "Twitter Post"; 
    $hidden = 'display:none';
    $publicsubheading = "
        ";
    $publicheading_title = "Tweet"; 
    $shareto = "Twitter";
            
    if( $mode == "S"){
        $title = "Twitter Post";
    }
}
else
if( $platform == 'E')
{
    $platformname = "Email";
    $title = "Social Photo Share";
    $subtitle = "<b>Creates a trackable external share for social media using a link or HTML</b><br>";
    $publicheading = "Photo to use in HTML"; 
    $publicsubheading = "
        <br>
        <div class='smalltext' >
        This is what will appear on the body of your share.
        It becomes a permanent part of your share and social media profiles. 
        We recommend that you substitute an alternate 'Proxy' photo here.
        </div>
        ";
    $publicheading_title = "Share Body Title"; 
    $hidden = '';
    $shareto = "Unspecified";
    
    
    if( $mode == "S"){
        $title = "Created External Share";
    }
    
}
else
{
    $platformname = "Other";
    $title = "Social Media Share";
    $publicheading = "Public View of Link"; 
    $publicsubheading = "
        <br>
        <div>
        This is what appears on a Social Media Post or Email Share.
        It becomes a permanent part of the post or email. We recommend
        substituting this with a Proxy photo.
        </div>
        ";
    $publicheading_title = "Public Title"; 
    $shareto = "Unspecified";
    
    
    if( $mode == "S")
        $title = "Created Social Media Share";
    
}



//****************************************************************
//****************************************************************
//****************************************************************
//****************************************************************



?>
<div
style='
background-color:whitesmoke;
color:black;padding:20px;
'>

<img src="../img/brax-photo-round-greatlake-128.png" style="position:relative;top:3px;margin:0;padding-left:0px;height:25px" />
<span class='pagetitle' style="color:black"><?=$title?></span>
<br>
        
        <div class='divbuttontextonly divbuttontext_unsel photolibrary tapped' 
            id='shareitbuttonclose'
            data-deletefilename='' 
            data-filename='<?=$sharefilename?>'  
            data-sharetype='<?=$sharetype?>' 
        >
            <img src='../img/arrow-stem-circle-left-128.png' style='height:25px;position:relative;top:8px;opacity:0.7' >
            My Photos &nbsp;&nbsp;
        </div>
        &nbsp;
        
        
        <div class='divbuttontextonly divbuttontext_unsel shareitbutton tooltip' 
                title="Step 2 - Create a Record of the Private Share for Tracking"
                id='shareitbutton2'  
                data-filename='<?=$shareHtml?>' 
                data-alias=''
                data-share='<?=$shareHtml?>'
                data-sharetype='<?=$sharetype?>'
                data-page='<?=$page?>'
                data-platform='<?=$platform?>'
                data-mode='S' 
                data-private='Y' >
                &nbsp;&nbsp; Create Share
                <img src='../img/arrow-stem-circle-right-128.png' style='height:25px;position:relative;top:8px;opacity:0.7' >
        </div>
        <br><br>
        <?=$subtitle?>
        
    <table class='gridstdborder mainfont' style='background-color:transparent;border-width:0px;max-width:500px'>
    <?php    
        if($platform == 'E')
        {
    ?>
        <tr>
            <td>
                <br><br>
                <span class='pagetitle2'>Internal Share Link</span><br>
                <input class='smalltext autoselect' type='text' readonly='readonly' value='<?=$proxyoriglink?>' style='width:100%' />
                <br><span class='smalltext'>
                Caution: This is a permanent link. Do not share this link to untrusted parties or social media. Use 'Create Share' above for
                external use.</span>
                <br><br><br><br>
            </td>
        </tr>    
    <?php    
        }
    ?>
        <tr>
            <td>
                <table class='gridstdborder' style='background-color:whitesmoke;width:100%'>
                    <tr>
                        <td class='gridstdborder' style='padding-left:10px;padding-right:10px'>
                            <span class='pagetitle2'>Photo(s) to Share</span>
                        </td>
                    </tr>
                    <tr>
                        <td class='gridstdborder' style='padding-left:10px;padding-right:10px'>
                            <br>
                            <img class="feedphoto shadow" src='<?=$shareimagelink?>' style='background-color:white;max-height:300px;max-width:100%' >

                            <?=$sharetext?>
                            <br><br>
                        </td>
                    </tr>
                    <tr>
                        <td class='' style='padding-left:10px;padding-right:10px'>
                                <br>
                                Photo Title<br>
                                <input id='shareit_sharetitle' class="sharetitle" name="sharetitle" type="text" size="35" value="<?=$privatetitle?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding-left:10px;padding-right:10px;<?=$hidden?>'>
                                <br>
                                Who it will be Shared With<br>
                                <input id='shareit_shareto' class="shareto" name="shareto" type="text" size="35" value="<?=$shareto?>" />
                        </td>
                    </tr>
                    <tr>
                        <td style='padding-left:10px;padding-right:10px;'>
                                Share Expire<br>
                                <input id='shareit_expire' class="shareexpire" name="shareexpire" type=text size="4" value="365" /> Days
                        </td>
                </table>
            </td>
        </tr>
        <tr class='useproxy'>
            <td>
                <table class='gridstdborder' style='background-color:white;width:100%'>
                    <tr>
                        <td style='background-color:whitesmoke;padding:10px'>
                            <br><br>
                            <span class='pagetitle2'><?=$publicheading?></span><br>
                            <div class='feedphoto'>
                            <?=$publicsubheading?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style='background-color:whitesmoke;padding:10px'>
    <?php            
        if($platform !='T')
        {
    ?>
                            <b>Timeline Photo</b><br>
                            <img class='feedphoto shadow' id="proxyphotoimg" src='<?=$shareimageopen?>' style='max-height:300px;max-width:100%' />
                            <input id="shareit_proxy" name="proxyphoto" type="text" size="35" value="<?=$proxy?>" style='display:none' /><br>
                                    <br>
                                    <div class='divbuttontext divbuttontext_unsel photoselect tapped' 
                                        id='photoselect' data-target='#shareit_proxy' data-src="#proxyphotoimg" data-filename='' data-mode=''  data-caller='share' >
                                        <img class='icon20' src='../img/brax-photo-round-lawn-128.png' />
                                            Select Alternate Timeline Photo
                                    </div>
                                    <br><br><br>
                                    <div class='divbuttontext divbuttontext_unsel noproxy tapped' 
                                        id='noproxy' data-target='#shareit_proxy' data-src="#proxyphotoimg" data-link='<?=$proxyoriglink?>' data-alias='<?=$proxyorig?>' data-mode='No-Proxy'   >
                                        <img class='icon20' src='../img/brax-photo-round-lawn-128.png' />
                                            Use Original Photo
                                    </div>
    <?php            
        }
    ?>
            </td>
                    </tr>
                    <tr>
                        <td style='background-color:whitesmoke;padding:10px'>
                            <?=$publicheading_title?><br>
                            <input id='shareit_shareopentitle' class="sharetitle" name="sharetitle" type="text" size="35" value="<?=$publictitle?>" />
                        </td>
                    </tr>
                    </tr>
                    <tr>
                        <td style='padding-left:10px;background-color:whitesmoke;padding-right:10px;'>
                            Default for Future Posts<br>
                            <?=$proxychoice?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>




</div>
