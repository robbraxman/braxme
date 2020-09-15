<?php
session_start();
include("config-pdo.php");

$share = @tvalidator("PURIFY", $_GET['p'] );
$view = @tvalidator("PURIFY", $_GET['v'] );

$iconlock = "$rootserver/img/lock.png";
$sharelink = "$rootserver/$installfolder/sharew.php?p=$share";

if( $view!=='N')
{
    $displaylink = "";
    $result2 = pdo_query("1","
        update shares set views=views+1 where shareid='$share' 
        ");
}
else 
{
    $displaylink  = "<input type='text' size='100' 
                   style='font-size:12px;border:0;background-color:whitesmoke' 
                   value='$sharelink' /><br><br>
                     ";
}


$result = pdo_query("1","
        select providerid, sharelocal from shares
        where shareid='$share'
            ");
if( $row = pdo_fetch($result))
{
    $providerid = $row['providerid'];
    $collection = $row['sharelocal'];
}
else 
{
    exit();
}


$result = pdo_query("1","
        select avatarurl from provider where providerid= $providerid
            ");
if($row = pdo_fetch($result))
    $avatarurl = $row['avatarurl'];


$result = pdo_query("1","
        select url, album, url1, seq, description from sharecollection
        where providerid = $providerid and collection='$collection'
            ");

while( $row = pdo_fetch($result))
{
    $album[$row[seq]] = "$row[album]";
    $url1[$row[seq]] = "$row[url1]";
    $link[$row[seq]] = "
        <a class='viewing' 
            style='text-decoration:none;white-space:nowrap;' 
            href='$row[url1]&v=$view' target='viewer'>
        <div style='display:inline;height:25px;cursor:pointer;margin:5px;font-size:13px' class='divbuttoncolor3 divbuttoncolor3_unsel'>
        <img src='../img/dot.png' style='height:10px' />
            $row[album]
        </div> 
                
        </a>
            ";
    $description = $row['description'];
    
}



?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta property='og:title' content='<?=$collection?>' />
<meta property='og:description' content="Click to View" />
<meta property='og:url' content='<?=$sharelink?>' />
<meta property='og:type' content='Website' />
<meta property='og:image' content='<?=$avatarurl?>' />        
<meta http-equiv='Pragma' content='no-cache' />
<meta http-equiv='Expires' content='-1' />
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='shortcut icon' href='<?=$rootserver?>/home_files/favicon.ico' type='image/x-icon'>
<link rel='apple-touch-icon' href='<?=$rootserver?>/img/lock.png'>
<title>Private Photo Album</title>
<link rel='stylesheet' href='app.css?2' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='../libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='../libs/jquery-1.10.2/jquery.min.js'></script>
<script>
$(document).ready(function(){
    $('body').on("mouseenter", ".divbuttoncolor3", function(){
        $(this).removeClass('divbuttoncolor3_unsel').addClass('divbuttoncolor3_sel');

    });
    $('body').on("mouseleave", ".divbuttoncolor3", function(){
        $(this).removeClass('divbuttoncolor3_sel').addClass('divbuttoncolor3_unsel');

    });
    $('body').on('click', '.viewing', function(){
        $('.avatararea').hide();
        $('.sitearea').hide();
        $('.viewerarea').show();
        
    });
    $('body').on('click', '.home', function(){
        $('.avatararea').show();
        $('.sitearea').show();
        $('.viewerarea').hide();
        
    });
    $('.viewerarea').hide();
    
    $('.viewer').width( $('body').width() );
    $('.viewertd').width( $('body').width() );
   
});
</script>
<title>Brax.Me Private Photo Album</title>
</head>
<body style='background-color:whitesmoke;width:100%;margin:0;padding:0' class="cx">
    <center><?=$displaylink?></center>
    <div style='background-color:#E5E5E5;color:white;padding:10px;margin:0;text-align:center'>
                <span style='font-size:18px;font-family:helvetica;font-weight:bold;padding:10px;'><?=$collection?></span>
    </div>
<table
       style='font-size:15px;font-family:helvetica;width:100%;margin:0;padding:0;border:0  none'>
    
    <tr>
        <td style='white-space:normal;line-height:120%'>
            <center>
            <div class='home' style='display:inline;cursor:pointer;padding:10px;margin-bottom:5px'><b>Home</b></div>&nbsp;&nbsp;&nbsp;
            <hr>
            <b>My Albums</b>&nbsp;&nbsp;&nbsp;
                <?php
                foreach($link as $link1)
                {
                    if( $link1!='')
                        echo "$link1 ";
                }
                ?>
            </center>
            <hr>
        </td>
    </tr>
    
    <tr class='avatararea'>
        <td>
            <div style="margin:auto;text-align:center;padding-left:20px;padding-right:20px;width:300px">
            <center>
            <img style='float:center;width:auto;height:120px'
                src="<?=$avatarurl?>"><br><br>
                    <?=$description?>
            </center>
            </div>
                    
        </td>
    </tr>
    
    
    <tr class='viewerarea'>
        <td>
        <table>
            <tr>
                <td class="viewertd"> 
                    <iframe name='viewer' style='width:100%;height:800px;border:0 none transparent;background-color:transparent;padding:0px;margin:0' seamless>
                    </iframe>
                </td>
            </tr>
        </table>
        </td>
    </tr>
    
</table>

</span>
<br><br>
    <div class='sitearea' style='background-color:#666666;color:white;text-align:center;margin:0;padding:0;font-size:13px;font-family:helvetica'>
            <center>
                <br>
            <p>This album post and all associated comments are private<br> 
                and are not stored on Facebook, or any public web site.</p>
            <a href='https://brax.me'><img src='<?=$iconlock?>' class='margined' style='height:60px;width:auto' /></a>
            <br>
            <br>Protected by Brax.Me - Sign up Free
            <br><br>
            <a style='text-decoration:none;text-decoration-color:white;color:white;font-weight:bold' href='https://brax.me'>https://brax.me</a>
            <br><br>
            </center>
    </div>
<?php
if( $view == 'N')
{
    echo "
        <br><br>
        <table style='margin:auto;font-size:13px;font-family:helvetica;border-style:solid;border-width:1px'>
        <tr style='background-color:gray;color:white'>
        <td>IP</td>
        <td>Device</td>
        <td>Views</td>
        <td>Last Read</td>
        </tr>
        ";
                    
    
    $result =  pdo_query("1","
        select ip, device, views, 
        DATE_FORMAT( lastread, '%m/%d/%y %h:%i %p') as lastread
        from sharereads where shareid in
        (select shareid from shares where providerid=$providerid
            and collection ='$share')
        ");
    
    
    while($row= pdo_fetch($result))
    {
        echo "
            <tr>
            <td>$row[ip]</td>
            <td>$row[device]</td>
            <td>$row[views]</td>
            <td>$row[lastread]</td>
            </tr>
            ";
        
    }
    echo "
        </table>
        ";
}
?>

</body>

</html>
 