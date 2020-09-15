<?php 
session_start();
include("config-pdo.php");

$share = tvalidator("PURIFY", $_POST['share'] );
$shareHtml = htmlentities(stripslashes($share),ENT_QUOTES);

$page = @tvalidator("PURIFY", $_POST['page'] );
$proxy = @tvalidator("PURIFY", $_POST['proxy'] );
$sharetype = @tvalidator("PURIFY", $_POST['sharetype'] );
$shareto = @tvalidator("PURIFY", $_POST['shareto'] );
$sharetitle = @tvalidator("PURIFY", $_POST['sharetitle'] );
$shareopentitle = @tvalidator("PURIFY", $_POST['shareopentitle'] );
$expire = @tvalidator("PURIFY", $_POST['expire'] );
$platform = @tvalidator("PURIFY", $_POST['platform'] );
$mode = @tvalidator("PURIFY", $_POST['mode'] );
$providerid = @tvalidator("ID",$_SESSION['pid']);


if( $mode == 'P' )
{
    $proxydefault = @tvalidator("PURIFY", $_POST['proxydefault'] );
    if($proxydefault!='Y')
    {
        $proxydefault = 'N';
    }
    $result = pdo_query("1",
            "update provider set proxy = '$proxydefault' where providerid= $providerid "
            );
    exit();
}

    $share = stripslashes( $_POST['share'] );
    $shareForSql = @tvalidator("PURIFY",$share);
    
    
    $proxy = @tvalidator("PURIFY", $_POST['proxy'] );
    $linkid1 = uniqid("X7");
    $setid = uniqid("$providerid");
    $securetype='C';

    if( $sharetype=='P')
    {
        $sharelink  = "https://bytz.io/prod/so.php?p=$linkid1";
        //$sharedirectopen = "$rootserver/$installfolder/sharedirect.php?p=$share";
    }
    else
    if( $sharetype=='A')
    {
        $sharelink  = "https://bytz.io/prod/soa.php?p=$linkid1";
        //$sharedirectopen = "$rootserver/$installfolder/sharedirect.php?p=$share";
    }
    
    $result = pdo_query("1", "
        select filename from photolib where alias='$proxy' 
            ");
    if($row = pdo_fetch($result))
    {
        $sharedirectopen = "https://bytz.io/prod/sharedirect.php?p=$row[filename]";
        $proxyfilename = $row[filename];
        
        $result = pdo_query("1", "
            delete from photoproxy where providerid=$providerid
            ");
        
        
        $result = pdo_query("1", "
            insert into photoproxy (providerid, filename, folder, section ) values ($providerid, '$proxyfilename','','')
            ");
        
        
    }
    
    
    
    $iconlock = "$rootserver/img/lock.png";
    
    

    
    if($expire == "")
        $expire = "1095";

    
    $result = pdo_query("1","
            insert into shares 
            (setid, providerid, sharedate, sharetype, sharelocal, 
            shareid, shareto, shareexpire, sharetitle, shareopentitle, platform, 
            securetype, proxyfilename, collection )
            values
            ('$setid',$providerid, now(), '$sharetype', '$shareForSql', '$linkid1', 
                '$shareto', date_add( now(), INTERVAL $expire DAY), 
                '$sharetitle','$shareopentitle','$platformname','$securetype','$proxy','' )
         ");

    
    
    $urlencoded = urlencode($sharelink);
    $urlencoded .= "&text=".urlencode(stripslashes($shareopentitle));
    
    $return = "
        <div class='divbuttontextonly divbuttontext_unsel photolibrary tapped' 
            id='shareitbuttonclose'
            data-deletefilename='' 
            data-filename=''  
            data-sharetype='' 
        >
            <img src='../img/arrow-stem-circle-left-128.png' style='height:25px;position:relative;top:8px;opacity:0.7' >
            My Photos &nbsp;&nbsp;
        </div>
        ";
    
    if( $platform == 'F')
    {
            
            if($_SESSION['mobilesize']=='Y')
            {
                
                $urlencoded = urlencode($sharelink);
                if($_SESSION['apn']!='')
                {
                    $urlencoded .= "&text=".stripslashes($shareopentitle);
                }  else {
                    //This works on Android!
                    $urlencoded .= "&text=".htmlentities(stripslashes($shareopentitle), ENT_QUOTES);
                    
                }
                
                //This works on Android!
                //$urlencoded .= "&text=".htmlentities(stripslashes($shareopentitle), ENT_QUOTES);
                
                $fbshare = "braxme://sharefb?u=$urlencoded";
                
                echo "$return
                <br><br>
                <div style='text-align:center'>
                <img class='socialshare tapped' data-share='$fbshare' src='../img/facebook-red-128.png' style='cursor:pointer;position:relative;top:0px;height:100px;width:auto;margin:0;padding:20;text-align:center' />
                <br>
                                    <span class=smalltext>
                                    Launch FB Share
                                    </span>
                </div>
                                    <br>
                ";
                exit();
                
                $arr = array('mobile'=> "$_SESSION[mobilesize]",
                     'url'=> "$fbshare");


                echo json_encode($arr);
                exit();
            }
            else 
            {
                $urlencoded = urlencode($sharelink);
                $urlencoded .= "&text=".htmlentities(stripslashes($shareopentitle), ENT_QUOTES);
                
                $fbshare = "http://www.facebook.com/sharer.php?u=$urlencoded";

                
                echo "$return
                <br><br>
                <div style='text-align:center'>
                <img class='socialshare' data-share='$fbshare' src='../img/facebook-red-128.png' style='cursor:pointer;position:relative;top:0px;height:100px;width:auto;margin:0;padding:20;text-align:center' />
                <br>
                                    <span class=smalltext>
                                    Launch FB Share
                                    </span>
                </div>
                                    <br>
                ";
                exit();
     
            }
        
        
        
    }
    
    echo "$return";
    
    if( $platform == 'G')
    {
        
        //echo "$sharelink";
        echo "      <br><br>
                    <a href='https://plus.google.com/share?url=$urlencoded' target=_blank style='text-decoration:none;color:blue' >
                        <div class='divbuttonshare divbutton3_unsel tooltip' title='Step 3 - Send the Photo to Google+'> 
                            <img class=blackandwhite src='../img/googleplus.jpg' style='position:relative;top:5px;height:25px;width:auto;margin:0;padding:0' /> 
                            Launch Google+ abd Share
                            <img src='../img/arrowhead-right-128.png' style='height:25px;position:relative;top:8px;opacity:0.7' />
                        </div>
                    </a>
                    ";
        //echo "http://www.facebook.com/sharer.php?u=$urlencoded";
        exit();
    }
    if( $platform == 'T')
    {
        
        //echo "$sharelink";
        echo "      <br><br>
                    <a href='https://www.twitter.com/intent/tweet?url=$urlencoded' target=_blank style='text-decoration:none;color:blue' >
                        <div class='divbuttonshare divbutton3_unsel tooltip' title='Step 3 - Send the Photo to Twitter'> 
                            <img class=blackandwhite src='../img/twitter.png' style='position:relative;top:5px;height:25px;width:auto;margin:0;padding:0' /> 
                            Launch Twitter and Tweet
                            <img src='../img/arrowhead-right-128.png' style='height:25px;position:relative;top:8px;opacity:0.7' />
                        </div>
                    </a>
                    ";
        //echo "http://www.facebook.com/sharer.php?u=$urlencoded";
        exit();
    }
    if( $platform == 'E')
    {
        $share = htmlentities( $_POST['share'], ENT_QUOTES );
        $shareForSql = tvalidator("PURIFY",$share);
        
    
        echo "
           <table style='margin:auto'>
           <tr><td>
           <div style='padding:20px'>
            <span style='font-size:25px;color:gray'>External Share Prepared <img src='../img/checkbox-green-128.png' style='position:relative;top:5px;height:30px' /></span><br>
            <br>
            <br>
            ";
        if( !isset($_SESSION['imap_name']) ){
        echo "
            <b>Share in Brax Email</b><br><br>
            Click this to launch your Brax.Me Email account<br><br>

            <div class='divbutton3 divbutton3_unsel newemailshare' id='newemailshare' 
                data-filename='$share' data-page='$page' data-album=''  
                data-platform='Email' 
                data-publicshare ='$sharelink'
                data-directshare ='$sharedirectopen'
                data-title='$shareopentitle' 
                data-sharetype='$sharetype' 
                >
                <img class='icon20' src='../img/brax-mail-round-greatlake-128.png'  />
                Share via Brax.Me Email
                <img class='icon20' src='../img/arrow-stem-circle-right-128.png'  />
                
                </div>
                <br>
                <br><br>
                ";
        }
        echo "
                <b>Share as Link</b>
                <br><br>

                    <textarea class='smalltext autoselect' readonly='readonly' cols=80 rows=3 style='width:400px;max-width:80%'>$sharelink</textarea>
                    <br>
                    <span class='smalltext2'>Copy/paste this web link</span><br>
                <br><br>
                <b>Share as HTML (with Preview)</b>
                <br><br>
                    <textarea class='smalltext autoselect' readonly='readonly' cols=80 rows=5 style='width:400px;height:auto;max-width:80%'><img src='$sharedirectopen'  ><p><a href='$sharelink'>$shareopentitle</a></p>
                    </textarea>
                    <br>
                    <span class='smalltext2'>Copy/paste this HTML</span><br>
            </div>
           
           </td></tr>
           </table>


        ";
    }
    else
    echo "
           <table style='margin:auto'>
           <tr><td>
            <div style='padding:20px'>
            <span style='font-size:25px;color:gray'>Social Media Share Created <img src='../img/checkbox-green-128.png' style='position:relative;top:5px;height:30px' /></span><br>
            <br>
            <b>Share Photo Link Manually to a Website</b>
            <br>
                You can manually cut/paste this web link.<br>
                
                <textarea cols=80 rows=2>$sharelink</textarea>
            </div>
            <br>
            <br>
        
            <b>Share HTML Manually to a Website (Shows Preview Photo)</b>
            <br>
                You can manually cut/paste this HTML link to a website that accepts embedded HTML.<br>
                
                <textarea cols=80 rows=6><img src='$sharedirectopen' style='width:400px;height:auto' /><p><a href='$sharelink'>$shareopentitle</a></p></textarea>
            <br><br>

           </td></tr>
           </table>


        ";

    exit();
    
?>