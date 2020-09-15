<?php
session_start();
include("config.php");

$share = @tvalidator("PURIFY", $_GET['p'] );
$view = @tvalidator("PURIFY", $_GET['v'] );
$page = intval(@tvalidator("PURIFY",$_GET['page']));
$uniqid = uniqid();
$redisplay = @tvalidator("PURIFY", $_GET['r'] );

$ip = @tvalidator("PURIFY", $_GET['ip'] );
$c = @tvalidator("PURIFY", $_GET['c'] );
$n = @tvalidator("PURIFY", $_GET['n'] );
$d = @tvalidator("PURIFY", $_GET['d'] );
$i = @tvalidator("PURIFY", $_GET['i'] );
$a = @tvalidator("PURIFY", $_GET['a'] );
$e = @tvalidator("PURIFY", $_GET['e'] );


function BotDetected() {

  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
    return TRUE;
  }
  else {
    return FALSE;
  }

}

$bot = BotDetected();


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
    if( $a == "L" )
    {
        do_mysqli_query("1","
            update shares set likes=likes+1 where shareid='$share'
        ");
    }
/******************************************************
 * END - ACTION PROCESSING
 ******************************************************/


$iconlock = "https://bytz.io/img/logo.png";
$shareimagelink = "https://bytz.io/img/logo.png";
$sharelink = "https://bytz.io/$installfolder/soa.php?p=$share";
if( $view!=='N')
{
    $displaylink = "";
    if(($page == 1 or $page==0) && $redisplay!='Y' )
    {
        $result2 = do_mysqli_query("1","
            update shares set views=views+1 where shareid='$share' 
            ");
    }
}
else 
{
    $displaylink  = "<input type='text' size='100' 
                   style='font-size:12px;border:0;background-color:whitesmoke' 
                   value='$sharelink' /><br><br>
                     ";
}


$result = do_mysqli_query("1","
        select count(*) as count from shares where shareid='$share'
        ");
$row = do_mysqli_fetch("1",$result);
if(intval($row['count']) == 0)
{
    
    
    echo "<!DOCTYPE html>
          <head>
          <META HTTP-EQUIV='Pragma' CONTENT='no-cache'>
          <META HTTP-EQUIV='Expires' CONTENT='-1'>
          <meta property='og:title' content='Expired Content' />
          <meta property='og:url' content='https://bytz.io/img/expired.jpg' />
          <meta property='og:image' content='https://bytz.io/img/expired.jpg' />        
          <meta name='viewport' content='width=device-width, initial-scale=1'>
          <title>Expired Content</title>
          </head>
          <body>
          <img src='https://bytz.io/img/expired.jpg' style='height:200;width:auto;float:center;margin:auto' >
          <center>
                <span style='font-family:helvetica;font-size:13px'>
                <p>This post was hosted by Brax.Me.</p>
                <a href='https://brax.me'><img src='$iconlock' class='margined' style='height:60px;width:auto' /></a>
                <br>
                <a href='https://brax.me'>https://brax.me</a>
                </span>
          </center>    
          </body>
          </html>
         ";    
    exit();
}

if( $page == 0)
    $page = 1;
$pagenext = intval($page)+1;
$pageprev = intval($page)-1;
if( intval($pageprev)< 1 )
    $pageprev = 1;

$max = 10;
$split = 5;
$picwidth = "1200px";
$picheight = "200px";
$pagestart = ($page-1) * $max;
$pagestartdisplay = $pagestart+1;
$pageenddisplay = $pagestart+$max;


//*******************************************************************
//*******************************************************************
//*******************************************************************
/*
$result2 = do_mysqli_query("1","
    select name, ip,comment, postid,
    DATE_FORMAT( postdate, '%Y-%m-%d %H:%i') as postdate,
    DATE_FORMAT( postdate, '%m/%d/%y %h:%i %p') as fpostdate
    from shareposts where shareid='$share' order by postdate asc
    ");
$comments = "<table class='comments gridstdborder' style='width:100%;margin:auto;'> 
            <tr style='background-color:steelblue'>
                <td style='text-align:center;background-color:darkgray;color:white;padding:10px'>
                Private Comments
                </td>
             </tr>";
while( $row2 = do_mysqli_fetch("1",$result2))
{
    $action = "&nbsp;&nbsp;&nbsp;<div 
            class='delete' 
            style='display:inline;cursor:pointer;color:steelblue;font-weight:bold'
            data-ip='$row2[ip]'
            data-function='socialpost.php'
            data-postid='$row2[postid]'>
            Delete</div>";
    $poster = $row2[name];
    if( $poster == "")
        $poster = "$row2[ip]";
    $comments .=
            "<tr>
                <td class='commentline'
                    style='overflow:scroll;text-align:left;width:100%;background-color:white;padding:5px;border-style:solid;border-width:5px 5px 5px 5px;border-color:whitesmoke'>
                       <span style='font-weight:bold;color:steelblue'>$poster</span>
                       $row2[comment]<br>
                       <span style='font-weight:normal;color:gray'>$row2[fpostdate]</span>
                       <span class='action' style='display:none;font-weight:normal;color:gray'>$action</span>
                </td>
             </tr>";
}
$comments .= "</table>";
*/
//*******************************************************************
//*******************************************************************
//*******************************************************************
$result = do_mysqli_query("1","
        select providerid, views, likes, sharetitle, shareopentitle,
        proxyfilename, 'photolib/' as folder,
        sharelocal as album, 
        (select count(*) from photolib where providerid=shares.providerid 
        and album = shares.sharelocal ) as count
        from shares where
        shareid ='$share'
            order by shareid desc
            ");



if( $row = do_mysqli_fetch("1",$result))
{
    $exposedtitle = $row['shareopentitle'];
    if( $row['sharetitle']=='')
        $exposedtitle = "Brax.Me Private Post";

    $proxyphotolink = "https://bytz.io/$installfolder/$row[folder]$row[proxyfilename]?$uniqid";
    //$proxyphotolink = "$rootserver/$installfolder/sharebase.php?p=$row[proxyfilename]";
    //if( $row[proxyfilename]=='')
    //{
        //$proxyphotolink = $shareimagelink;
        
    //}
    //$proxyphotolink = "$rootserver/$installfolder/sharedirect.php?p=$row[proxyfilename]";
    
 
    //This works - for albums - displays one of the photos (1st in sort)
    //however this is not the proxy photo
    $proxyphotolink = "https://bytz.io/$installfolder/sharebase.php?p=$share&n=1&$uniqid";
    
    if($row['proxyfilename']!='')
    {
        $proxyphotolink = "https://bytz.io/$installfolder/sharedirect.php?a=$row[proxyfilename]";
    }
    
    
    $icon = "https://bytz.io/img/logo.png";
    $iconlock = "https://bytz.io/img/logo.png";
    $likeimage = "../img/heart-circle-128.png";
    $views = intval($row['views']);
    $likes = "$row[liketotal]";
    
    //Keep Crawler from reading photos
    if($views <= 1)
    {
        $iconlock = $proxyphotolink;
        $likeimage = $proxyphotolink;
    }
    
    
    
    
    //$titlebase64 = base64_encode ($row[sharetitle]);
    //if( $row[sharetitle]=='')
        $titlebase64 = base64_encode("$row[album]");
    $sharelocal = tvalidator("PURIFY",$row['album']);
    $providerid = "$row[providerid]";
    $total = $row['count'];
    
    if($pagestart+$max > $total )
        $max = $total - $pagestart;
    
    if($pageenddisplay > $total)
        $pageenddisplay = $total;
    if( $max < 0)
        $max = 0;

}

$result = do_mysqli_query("1","
        select filename, folder, title, comment
        from photolib where album = '$sharelocal'
        and providerid = $providerid 
        order by filename asc limit $pagestart, $max
        ");


$arraycount=0;
while( $row = do_mysqli_fetch("1",$result))
{
    $filename[$arraycount] = "https://bytz.io/$installfolder/$row[folder]$row[filename]";
    $shareimagelink[$arraycount] = "https://bytz.io/$installfolder/sharedirect.php?p=$row[filename]";

    $piccomments[$arraycount] = $row['comment'];
    if($piccomments[$arraycount] != "")
        $piccomments[$arraycount] .= "<br>";

    $arraycount++;
}

if( $arraycount < $max)
{
    $pagenext = $pageprev;
    $pageprev = $pageprev -1;
    if( intval($pageprev)< 1 )
        $pageprev = 1;
}

$paginginfo = "$pagestartdisplay - $pageenddisplay of $total";
$previous = "<a href='$sharelink&page=$pageprev&v=$view&r=Y' style='cursor:pointer;text-decoration:none;display:inline;'>".
            "<img src='../img/arrow-circle-up-128.png' style='height:25px' />".
            "</a>";
if( intval($pagestartdisplay) < $total )
{
    $next = "<a href='$sharelink&page=$pagenext&v=$view&r=Y' style='cursor:pointer;text-decoration:none;display:inline;'> ".
            "<img src='../img/arrow-circle-down-128.png' style='height:25px' />".
            "</a>";
    if($pageprev == 1)
        $previous = "";
}
else
{
    $paginginfo = "&nbsp; End of Album";
}

$result2 = do_mysqli_query("1","
           select likes from shares where shareid='$share'
        ");
$row2 = do_mysqli_fetch("1",$result2);
$likes = $row2['likes'];

//**************************************************
//**************************************************
//**************************************************
// BUILD COMMENT AREA

    if(intval($likes)==0)
    {
            $likebutton = "
                <div class='like divbuttonlike divbuttonlike0 divbuttonlike_unsel' data-function='soa.php'>
                    <img src='$likeimage' style='height:12px;margin:0;padding:0;position:relative;top:5px' />
                    $likes</div>
                           ";

    }
    else
    {
            $likebutton = "
                <div class='like divbuttonlike divbuttonlike1 divbuttonlike_unsel' data-function='soa.php'>
                    <img src='$likeimage' style='height:12px;margin:0;padding:0;position:relative;top:5px' />
                    $likes</div>
                           ";

    }


    $comments = "
            <table class='comments share' style='width:100%;color:black'> 
                <tr>
                    <td  style='text-align:center;background-color:white'  >
                    <br>
                        $likebutton
                        &nbsp;&nbsp;&nbsp;&nbsp; Views: $views
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href='http://www.facebook.com/sharer.php?u=$sharelink' target=_blank style='text-decoration:none;color:steelblue'>
                        FB Re-Share 
                        </a>                
                        <br><br><b><span class='smalltext'>Let's Chat Here! It's More Private</span></b><br>

                    </td>
                </tr>
            ";

    $result2 = do_mysqli_query("1","
        select name, ip,comment, postid,
        DATE_FORMAT( postdate, '%Y-%m-%d %H:%i') as postdate,
        DATE_FORMAT( postdate, '%m/%d/%y %h:%i %p') as fpostdate
        from shareposts where shareid='$share' order by postdate asc
    ");
    while( $row2 = do_mysqli_fetch("1",$result2))
    {
        $action = "&nbsp;&nbsp;&nbsp;<div 
                class='delete' 
                style='display:inline;cursor:pointer;color:steelblue;font-weight:bold;float:right'
                data-ip='$row2[ip]'
                data-function='soa.php'
                data-postid='$row2[postid]'><img src='../img/delete-circle-128.png' class='cicon' />
                </div>";
        $poster = $row2['name'];
        if( $poster == "")
            $poster = "$row2[ip]";
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
<meta name='viewport' content='width=device-width, initial-scale=1, target-density=hi-dpi'>
<link rel='shortcut icon' href='https://bytz.io/img/favicon.ico' type='image/x-icon'>
<link rel='apple-touch-icon' href='https://bytz.io/img/lock.png'>
<link rel='stylesheet' href='public.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.core.css' />
<link rel='stylesheet' href='../libs/alertify.js-0.3.10/themes/alertify.default.css' />
<script src='../libs/alertify.js-0.3.10/src/alertify.js'></script>
<script src='../libs/jquery-1.10.2/jquery.min.js'></script>
<script> 
var shareid = '<?=$share?>';
var redisplay = '<?=$redisplay?>';
var smallsize= 100;
var bigsize = 480;
</script>
<script src="printsc.js?<?=$uniqid?>"></script>
<title><?=$exposedtitle?></title>
</head>
<body style='background-color:#E5E5E5' class="cx">
<span style='font-size:5px;color:whitesmoke;float:left'>My Private Album</span>
<center>
<?=$displaylink?>
<br>
<img style='float:center;width:auto;height:30px;display:none'
    src="<?=$proxyphotolink?>" />

<span class='clicktoshow' style='cursor:pointer'>Click here to Redisplay</span>
<table class='cimg'  style='background-color:white' >
    <tr>
        <td>
    <img style='position:relative;top:10px;width:auto;height:30px;margin-bottom:10px;text-align:center'
        src="<?=$rootserver?>/<?=$installfolder?>/gdtext2image.php?c=1&t=<?=$titlebase64?>"><br>
        </td>
    </tr>
    <tr>
        <td>
        <table style="margin:auto;width:200px">
            <tr>
                <td>
                    <br>
                    <center>
                        <?=$paginginfo?>
                        <br><br>
                        <?=$previous?>
                    </center>
                </td>
            </tr>
        </table>
            <table style='margin:auto;width:400px'>
<?php
/*
//                style='width:<?=$picwidth?>;border-collapse:collapse;border-width:1px;border-color:whitesmoke;background-color:whitesmoke;margin:auto'>
 * *
 */
    $count = $pagestart;
    $currentcount = 0;
    if(count($filename) > 0)
    {
        foreach($filename as $filename1)
        {
            
/*            
                <td class='picexpandalbum_temp'  
                    style='cursor:pointer;padding-top:5px;padding-bottom:5px;background-position:center;background-repeat:no-repeat;background-size:auto 100%;background-color:whitesmoke;width:400px;height:200px'
                    background="<?=$rootserver?>/<?=$installfolder?>/sharebase.php?p=<?=$share?>&n=<?=$count?>">
                    <?=$piccomments[$count]?>            
*/            
            
                $img = "$rootserver/$installfolder/sharebase.php?p=$share&n=$count";   
                //$img = hex_encode($img);
?>
            <tr>
                <td
                         style='background-color:whitesmoke;
                         background-image:url("<?=$img?>");
                         background-size:contain;
                         background-repeat:no-repeat;
                         background-position:center;
                         width:600px;
                         height:400px;'
                    >
                    <div
                         style='background-color:transparent;
                         width:600px;
                         height:400px;'
                    >
                    </div>

                </td>
            </tr>
            <tr style="background-color:white">
                <td>
                    <br>
                </td>
            </tr>
<?php
        $count++;
        $currentcount++;
        }
    }
?>
            <tr>
                <td>
                    <center>
                        <?=$next?>
                    </center>
                </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td style='text-align:center;width:500px' >
            <div class='commentarea' style="margin:auto;width:100%"><?=$comments?></div>
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
                <!--
                <span style='color:steelblue;font-weight:bold'>Poster Email</span> 
                <input class='socialemail' type='text' style='border-width:1px;border-color:gray;height:20px' size='30' />             
                <br><br>
                -->
                <div class='postcomment divbutton3 divbutton3_unsel' data-function="soa.php" style='display:inline;cursor:pointer;'>&nbsp;&nbsp; Post &nbsp;&nbsp;</div>
                <br><br><br>
            </div>
            <hr>
        </td>
    </tr>
    <tr>
        <td  style='text-align:center'  >
            <span class='youare'></span>
            <br><br><br>
        </td>
    </tr>
    
</table>

</center>
                <br>
                <br>
                <br>
                <br>
    <div class='sitearea smalltext' style='max-width:100%;background-color:white;color:black;margin:auto'>
            <center>
                <br>
            <p>This photo post and all associated comments are private<br> 
                and are not stored on Facebook, or at any public web site.<br>Protect your public profile.</p>
            <a href='https://brax.me'><img src='<?=$iconlock?>' class='margined' style='height:60px;width:auto' /></a>
            <br>
            <br>Protected by Brax.Me - Privacy Enhanced Social Sharing.
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
        <table class='smalltext' style='margin:auto;font-size:13px;font-family:helvetica;border-style:solid;border-width:1px'>
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
    
    
    while($row= do_mysqli_fetch("1",$result))
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

function hex_encode ($str)    { 
        $encoded = bin2hex("$str"); 
        $encoded = chunk_split($encoded, 2, '%'); 
        $encoded = '%' . substr($encoded, 0, strlen($encoded) - 1); 
        return $encoded;    
} 
?>

</body>

</html>
 