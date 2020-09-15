<?php
session_start();
include("config.php");
$uniqid = uniqid();

$view = tvalidator("PURIFY", $_GET['v'] );
$redisplay = tvalidator("PURIFY", $_GET['r'] );


$share = tvalidator("PURIFY", $_GET['p'] );
$ip = tvalidator("PURIFY", $_GET['ip'] );
$c = tvalidator("PURIFY", $_GET['c'] );
$n = tvalidator("PURIFY", $_GET['n'] );
$d = tvalidator("PURIFY", $_GET['d'] );
$i = tvalidator("PURIFY", $_GET['i'] );
$a = tvalidator("PURIFY", $_GET['a'] );
$e = tvalidator("PURIFY", $_GET['e'] );

$result = do_mysqli_query("1","
    select shareto, platform from shares where shareid='$share'
    ");
$row = pdo_fetch($result);
$shareto = $row['shareto'];
if( $shareto == "Unspecified")
    $shareto = "$row[platform]";

/******************************************************
 * ACTION PROCESSING
 ******************************************************/

    if( $a == 'D')
    {
        $result = do_mysqli_query("1","
            delete from shareposts where shareid='$share' and ip='$ip' and postid='$i'
            ");


    }
    else
    if( $a == 'C')
    {
        $c = strip_tags($c, "<img><a><br><ul><li><iframe><b><u><i>");

        if( $c !="")
        {
            do_mysqli_query("1","
                insert into shareposts (shareid, ip, postdate, comment,name, device, email ) values
                ('$share','$ip',now(), '$c','$n','$d','$e' )
                ");
        }
    }
    else
    if( $a == L )
    {
        do_mysqli_query("1","
            update shares set likes=likes+1 where shareid='$share'
        ");
    }
/******************************************************
 * END - ACTION PROCESSING
 ******************************************************/



    $iconlock = "$rootserver/img/logo.png";
    $sharelink = "$rootserver/$installfolder/so.php?p=$share";
    if( $view!=='N' )
    {
        $result2 = do_mysqli_query("1","
            update shares set views=views+1 where shareid='$share'
            ");
        $displaylink = "";
    }
    else 
    {
        $displaylink  = "<input type='text' size='100' 
                       style='font-size:12px;border:0;background-color:whitesmoke' 
                       value='$sharelink' /><br><br>
                         ";
    }



/******************************************************
 * CHECK FOR EXPIRATION
 ******************************************************/


$result = do_mysqli_query("1","
        select aws_url, filename, folder, comment, 
        (select sharetitle from shares where shareid='$share') as title,
        (select shareopentitle from shares where shareid='$share') as opentitle,
        (select views from shares where shareid='$share') as views,
        (select likes from shares where shareid='$share') as liketotal,
        (select proxyfilename from shares where shareid='$share') as proxyfilename
        from photolib where filename=
         (select sharelocal from shares where shareid = '$share' )
        ");


if( !$row = pdo_fetch($result))
{
    
    
    echo "<!DOCTYPE html>
          <head>
          <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
          <META HTTP-EQUIV='Expires' CONTENT='-1'>
          <meta property='og:title' content='Expired Content' />
          <meta property='og:url' content='$rootserver/img/expired.jpg' />
          <meta property='og:image' content='$rootserver/img/expired.jpg' />        
          <meta name='viewport' content='width=device-width, initial-scale=1'>
          <title>Expired Content</title>
          </head>
          <body>
          <img src='$rootserver/img/expired.jpg' style='height:200;width:auto;float:center;margin:auto' >
          <center>
                <span style='font-family:helvetica;font-size:13px'>
                <p>This post was hosted by Brax.Me.</p>
                <a href='https://brax.me'><img src='$iconlock' class='margined' style='height:60px;width:auto' /></a>
                <br>Safe Social Sharing
                <br>
                <a href='https://brax.me'>https://brax.me</a>
                </span>
          </center>    
          </body>
          </html>
         ";    
    exit();
}
/******************************************************
 * END - CHECK FOR EXPIRATION
 ******************************************************/


    //$filename = "$row[aws_url]";
    $shareimagelink = "$rootserver/$installfolder/sharedirect.php?p=$row[filename]";
    $proxyphotolink = "$rootserver/$installfolder/sharedirect.php?a=$row[proxyfilename]";
    $exposedtitle = htmlentities($row[opentitle],ENT_QUOTES);
    if( $row[opentitle]=='')
        $exposedtitle = "Brax.Me Private Photo";
    $titlebase64 = base64_encode ($row['title']);
    if( $row['title']=='')
        $titlebase64 = base64_encode("Photos");
    if( $row['proxyfilename']=='')
    {
        $proxyphotolink = $shareimagelink;
    }

    $icon = "$rootserver/img/privatepost.jpg";
    $views = intval($row['views']);
    $likes = "$row[liketotal]";
    $piccomments = $row['comment'];
    if($piccomments != "")
        $piccomments .= "<br><br>";
    
    $iconlock = "$rootserver/img/logo.png";
    $likeimage = "../img/thumbs-up-128.png";
    //Keep Crawler from reading photos
    if($views <= 1)
    {
        $iconlock = $proxyphotolink;
        $likeimage = $proxyphotolink;
    }



//**************************************************
//**************************************************
//**************************************************
// BUILD COMMENT AREA

    if(intval($likes)==0)
    {
            $likebutton = "
                <div class='like divbuttonlike divbuttonlike0 divbuttonlike_unsel' data-function='so.php'>
                    <img src='$likeimage'  style='height:12px;margin:0;padding:0'  />
                    $likes</div>
                           ";

    }
    else
    {
            $likebutton = "
                <div class='like divbuttonlike divbuttonlike1 divbuttonlike_unsel' data-function='so.php'>
                    <img src='$likeimage'  style='height:12px;margin:0;padding:0'  />
                    $likes</div>
                           ";

    }


    $comments = "
        <table class='comments share'> 
            <tr>
                <td class='picexpand' 
                    background='$rootserver/$installfolder/sharebase.php?p=$share'
                </td>
            </tr>
            <tr>
                <td style='text-align:center;'>
                    <b>$piccomments</b>            
                </td>
            </tr>
            <tr>
                <td  style='text-align:center;background-color:white'  >
                <br>
                    $likebutton
                    &nbsp;&nbsp;&nbsp;&nbsp; Views: $views
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href='http://www.facebook.com/sharer.php?u=$sharelink' target=_blank style='text-decoration:none;color:steelblue'>
                    FB Re-Share 
                    </a>                
                    <br><br><b>Let's Chat Here! It's More Private</b><br>

                </td>
            </tr>
            ";

    $result2 = do_mysqli_query("1","
        select name, ip,comment, postid,
        DATE_FORMAT( postdate, '%Y-%m-%d %H:%i') as postdate,
        DATE_FORMAT( postdate, '%m/%d/%y %h:%i %p') as fpostdate
        from shareposts where shareid='$share' order by postdate asc
    ");
    while( $row2 = pdo_fetch($result))
    {
        $action = "&nbsp;&nbsp;&nbsp;<div 
                class='delete' 
                style='display:inline;cursor:pointer;color:steelblue;font-weight:bold;float:right'
                data-ip='$row2[ip]'
                data-function='so.php'
                data-postid='$row2[postid]'><img src='../img/garbage-closed-128.png' class='cicon' />
                </div>";
        $poster = $row2['name'];
        if( $poster == "") {
            $poster = "$row2[ip]";
        }
        $comments .= "

                <tr>
                    <td class='commentline'
                        style='text-align:left;width:100%;background-color:white;padding-left:20px;padding-right:20px;padding-top:5px;padding-bottom:5px'>
                           <span style='font-weight:bold;color:steelblue'>$poster</span>
                           $row2[comment]<br>
                           <div style='padding-bottom:5px;padding:top:5px'>
                           <span style='font-weight:normal;color:lightgray'>$row2[fpostdate]</span>
                           <span class='action' style='display:inline;font-weight:normal;color:gray'>$action</span>
                           </div>
                    </td>
                 </tr>";
    }
    $comments .= "</table>";

    //These actions just return the comment area via Ajax
    if( $a == 'C' || $a == 'D' || $a == 'L')
    {
        echo $comments;
        exit();
    }

//**************************************************
//**************************************************
//**************************************************

?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta property='og:title' content='<?=$exposedtitle?>' />
<meta property='og:description' content="Click to View and Leave Comments in Private" />
<meta property='og:url' content='<?=$sharelink?>' />
<meta property='og:type' content='Website' />
<meta property='og:image' content='<?=$proxyphotolink?>' />        
<meta http-equiv='Pragma' content='no-cache' />
<meta http-equiv='Expires' content='-1' />
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link rel='shortcut icon' href='<?=$rootserver?>/img/favicon.ico' type='image/x-icon'>
<link rel='apple-touch-icon' href='<?=$rootserver?>/img/logo.png'>
<title>Private Social Share</title>
<link rel='stylesheet' href='public.css?<?=$uniqid?>' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='../libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='../libs/jquery-1.10.2/jquery.min.js'></script>
<script> 
var shareid = '<?=$share?>';
var redisplay = '<?=$redisplay?>';
var smallsize= 300;
var bigsize = 480;
</script>
<script src="printsc.js?<?=$uniqid?>"></script>
<script>
$(document).ready(function(){
});
</script>
<title><?=$exposedtitle?></title>
</head>
<body style='background-color:whitesmoke' class="cx">
<span style='font-size:5px;float:left;display:none'>My Private Picture</span>
<center>
<?=$displaylink?>
<br>
<img src='<?=$proxyphotolink?>' class='margined' style='position:relative;top:-4px;height:25px;width:auto;' />
<img style='float:center;width:auto;height:30px'
    src="<?=$rootserver?>/<?=$installfolder?>/gdtext2image.php?t=<?=$titlebase64?>">
<span class='clicktoshow' style='cursor:pointer'><br>Click here to Redisplay</span>
<table class="cimg"
       >
    <tr>
        <td style='margin:auto;text-align:center'>
            <div class='commentarea' style="width:100%;float:center;margin:auto"><?=$comments?></div>
                <br>
            <div class='pre-entercomment' style='cursor:pointer;margin:auto;font-weight:bold;color:steelblue'>Post a Comment...</div>
            <div class='entercomment' style='display:none;margin:auto'>
                <br><br><br>
                <span style='color:steelblue;font-weight:bold'>Comment</span><br>
                <textarea class='socialpost' style='border-width:1px;border-color:gray;' cols='70' rows='2' ></textarea>             
                <br>
                <span class='linkhere' style='cursor:pointer;color:steelblue;font-weight:bold'>Here's a Link...&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
                <span class='linkinput'>
                    <br>
                    <input class='sociallink' type='text' style='border-width:1px;border-color:gray;height:20px' size='70' />             
                    <br>
                </span>
                <span class='imghere' style='cursor:pointer;color:steelblue;font-weight:bold'>Here's a Pic...</span>
                <span class='imginput'>
                    <br>
                    <input class='socialimg imginput' type='text' style='border-width:1px;border-color:gray;height:20px' size='70' />             
                </span>
                <br>
                <br>
                <span style='color:steelblue;font-weight:bold'>Poster Name</span> 

                <input class='socialname' type='text' style='border-width:1px;border-color:gray;height:20px' size='30' />             
                <br>
                <span style='color:steelblue;font-weight:bold'>Poster Email</span> 
                <input class='socialemail' type='text' style='border-width:1px;border-color:gray;height:20px' size='30' />             
                <br><br>
                <div class='postcomment divbutton3 divbutton3_unsel' data-function="so.php" style='display:inline;cursor:pointer;'>&nbsp;&nbsp; Post &nbsp;&nbsp;</div>
                <br><br><br>
            </div>
            <hr>
        </td>
    </tr>
    <tr>
        <td  style='text-align:center'  >
            <span class='youare'></span>
        </td>
    </tr>
    
</table>

</span>
</center>
<br><br>
    <div class='sitearea' style='background-color:#666666;color:white;'>
            <center>
                <br>
            <p>This photo post and all associated comments are private<br> 
                and are not stored on Facebook, or at any public web site.</p>
            <a href='https://brax.me'><img src='<?=$iconlock?>' class='margined' style='height:60px;width:auto' /></a>
            <br>
            <br>Protected by Brax.Me - Say and Show What You Want.
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
                    
    
    $result =  do_mysqli_query("1","
        select ip, device, views, 
        DATE_FORMAT( lastread, '%m/%d/%y %h:%i %p') as lastread
        from sharereads where shareid='$share'
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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55697704-1', 'auto');
  ga('send', 'pageview');

</script>	
</body>

</html>
 