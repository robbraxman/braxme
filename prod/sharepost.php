<?php
session_start();
include("config-pdo.php");

$share = tvalidator("PURIFY", $_POST[share] );
$proxy = tvalidator("PURIFY", $_POST[proxy] );
$providerid = $_SESSION[pid];

$linkid1 = uniqid("X7", true);
$setid = uniqid("$providerid");
$sharetype = 'T'; //Text content

$iconlock = "$rootserver/img/lock.png";



//*************************
//Read Proxy Filename
//*************************
$result2 =pdo_query("1","
    select filename, folder from photoproxy where providerid = $providerid and section='T'
    ");
$proxyphotolink = "$rootserver/img/privatepost.jpg";
if( $row2 = pdo_fetch($result))
{
    $proxyphotolink = "$rootserver/$installfolder/$row2[folder]$row2[filename]";
    $proxyfilename = "$row2[filename]";
}
if( $proxy == 'N')
    $proxyfilename = "";
//*************************

$result2 =pdo_query("1","
    select comment from statuspostpublic where shareid = '$share'
    ");
$comment = "$proxyphotolink";
if( $row2 = pdo_fetch($result))
    $comment = html_entity_decode($row2[comment], ENT_QUOTES);



$iconlock = "$rootserver/img/lock.png";
$sharelinkopen = "$rootserver/$installfolder/sharefbp.php?s=$linkid1";

if( $proxy == 'Y')
{
    $securetype = 'C';
    $exposedtitle = "Brax.Me Private Post";
    $sharedirectopen = $proxyphotolink;
}
else
{    
    $securetype = 'O';
    $exposedtitle = "$title";
}
$result = pdo_query("1","
            insert into shares 
            (setid, providerid, sharedate, sharetype, sharelocal, 
            shareid, shareto, shareexpire, sharetitle, platform, 
            securetype, proxyfilename, collection )
            values
            ('$setid',$providerid, now(), '$sharetype', '$share', '$linkid1', 
                'Unspecified', date_add( now(), INTERVAL 1095 DAY), 
                '$title','','$securetype','$proxyfilename','' )
         ");

if($proxy=='Y')
{
    $proxyimage = "Private Share<br>Proxy Picture<br>
                <img src='$proxyphotolink' class='margined' 
                style='height:100px;width:auto' />";
}
else
    $proxyimage = "";


?>
    <center>
<span style='font-size:25px'>New Share Created</span><br>
<br>
<br>
<table>
    <tr>
        <td>
            <?=$comment?>
        </td>
    </tr>    
</table>
<table>
    <tr>
        <td class="label">
            Share To
        </td>
        <td class="dataarea">
            <input class="shareto" name="shareto" type="text" size="50" value="Unspecified" />
        </td>
    </tr>    
    <tr>
        <td class="label">
            Share Title
        </td>
        <td class="dataarea">
            <input class="sharetitle" name="sharetitle" type="text" size="50" value="<?=$title?>" />
        </td>
    </tr>    
    <tr>
        <td class="label">
            Share Expire
        </td>
        <td class="dataarea">
            <input class="shareexpire" name="shareexpire" type=text size="4" value="365" /> Days
        </td>
    </tr>    
    <tr>
        <td class="label">
            Share Platform
        </td>
        <td class="dataarea">
            <a href='http://www.facebook.com/sharer.php?u=<?=$sharelinkopen?>' target=_blank style='text-decoration:none;color:blue' >
                <div class='divbutton divbutton_unsel savesharepost' 
                     data-filename='<?=$share?>' 
                     data-page='<?=$page?>' 
                     data-album='<?=$album?>' 
                     data-setid='<?=$setid?>' 
                     data-platform='Facebook' 
                     data-title='<?=$title?>' 
                     data-sharetype='<?=$sharetype?>' 
                     data-secure='<?=$securetype?>' >
                    <img src='../img/facebook-flat.png' style='height:12px;width:auto;margin:0;padding:0' /> 
                    Share
                </div>
            </a>
            <br>
            <br>
            <div class='divbutton divbutton_unsel newemailshare savesharepost' id='newemailshare' 
                data-filename='<?=$share?>' data-page='<?=$page?>' data-album='<?=$album?>'  
                data-platform='Email' 
                data-setid='<?=$setid?>' 
                data-publicshare ='<?=$sharelinkopen?>'
                data-directshare ='<?=$sharedirectopen?>'
                data-title='<?=$exposedtitle?>' 
                data-sharetype='<?=$sharetype?>' 
                data-secure='<?=$securetype?>' >
                <img src='../img/braxmail-128.png' style='height:12px;width:auto;margin:0;padding:0' />
                Email Share</div>
            <br>
            <br>
            <div class='divbutton divbutton_unsel saveshare' id='savesharepost' 
                data-filename='<?=$share?>' 
                data-setid='<?=$setid?>' 
                data-page='<?=$page?>' 
                data-album='<?=$album?>'  
                data-platform='Manual' 
                data-title='<?=$exposedtitle?>' 
                data-sharetype='<?=$sharetype?>' 
                data-secure='<?=$securetype?>'>I'll Share Myself </div>
                &nbsp;&nbsp;
                <input class="sharepubliclink" name="sharepubliclink"  style='border:0;font-size:11px;background-color:whitesmoke' readonly="readonly" type=text size="100" value="<?=$sharelinkopen?>" />
            
            <br><br>
            
            <div class="divbutton divbutton_unsel sharepostdeletebutton"
                 data-save='U'
                 data-setid ='<?=$setid?>'
                 >
                Delete Share
            </div>
            
            
        </td>
    </tr>    
</table>
</center>


</body>

</html>
 