<?php
session_start();
require_once("config.php");

$providerid = $_SESSION['pid'];

$mode = '';
if(isset($_POST['mode'])){
    $mode = tvalidator("PURIFY",$_POST['mode']);
}
$sort = '';
if(isset($_POST['sort'])){
    $sort = tvalidator("PURIFY",$_POST['sort']);
}
$active = '';
if(isset($_POST['active'])){
    $active = tvalidator("PURIFY",$_POST['active']);
}
$page = 0;
if(isset($_POST['page'])){
    $page = intval(tvalidator("PURIFY",$_POST['page']));
}

$checked1='';
$checked2='';
$checked3='';
if( $sort == "" || $sort == "createdate")
{
    $sort_text = "order by createdate desc";
    $checked1 = "checked=checked";
}
if( $sort == "lastpost")
{
    $sort_text = "and (views > 1 )
             order by lastpost desc, views desc";
    $checked2 = "checked=checked";
}
if( $sort == "shareexpire")
{
    $sort_text = "order by shareexpire2 asc";
    $checked3 = "checked=checked";
}

if( $mode == 'D')
{
    $filename = tvalidator("PURIFY",$_POST['filename']);
    $collection = "";
    $result = do_mysqli_query("1", "
    select sharelocal from shares where shareid = '$filename' and sharetype='W'
    ");
    if( $row = do_mysqli_fetch("1",$result))
    {
        $collection = $row['sharelocal'];
    }
    if( $collection == "")
    {
        do_mysqli_query("1", "
            delete from shares where shareid = '$filename'
            ");
        do_mysqli_query("1", "
            delete from shareposts where shareid = '$filename'
            ");
    }
    else
    {
        do_mysqli_query("1", "
            delete from shares where providerid=$providerid 
                and sharelocal = '$collection' and sharetype='W'
            ");
        do_mysqli_query("1", "
            delete from shares where providerid=$providerid 
                and collection = '$collection' and sharetype='A'
            ");
        
    }
    
}

    
if( $page == 0)
    $page = 1;
$pagenext = intval($page)+1;
$pageprev = intval($page)-1;
if( intval($pageprev)< 1 )
    $pageprev = 1;

$max = 50;
$pagestart = ($page-1) * $max;
$pagestartdisplay = $pagestart+1;
$pageenddisplay = $pagestart+$max;

$braxsocial = "<img class='icon20' src='../img/brax-photo-round-oj-128.png' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />";

echo " 
        <span class=''>
            <div class='gridstdborder roomselect' 
                data-room='All' data-roomid='All'                
                style='background-color:#a1a1a4;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                $braxsocial
                <span class='pagetitle2a' style='color:white'>Manage My Shares</span> 
            </div>
        </span>

        <div style='padding:10px;background-color:white'>
        <div class='divbuttontextonly divbuttontext_unsel photolibrary tapped' data-deletefilename=''>
            <img class='icon20' src='../img/arrow-stem-circle-left-128.png' style='position:relative;top:8px;opacity:0.7' >
            My Photos &nbsp;&nbsp;
        </div>        [$pagestartdisplay - $pageenddisplay]
        <br><br>
        <input id='photolibpage' class='photolibpage' type=hidden size='3' value='$page' />
        <div class=formobile></div>
        <img class='icon20 manageshares tapped' src='../img/arrow-circle-up-128.png' style='height:25px;position:relative;top:8px;opacity:0.7'  id='prevphotopage' data-page='$pageprev' data-sort='$sort' />
        &nbsp;&nbsp;
        <img class='icon20 manageshares tapped' src='../img/arrow-circle-down-128.png' style='height:25px;position:relative;top:8px;opacity:0.7'  id='prevphotopage' data-page='$pagenext' data-sort='$sort' />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <br><br>
                <input type='radio' name='sharesort' class='manageshares' data-page='1' data-mode='' data-filename='' data-sort='createdate' $checked1 style='position:relative;top:5px'> Create Date
                &nbsp;&nbsp;
                <input type='radio' name='sharesort' class='manageshares' data-page='1' data-mode='' data-filename='' data-sort='lastpost' $checked2 style='position:relative;top:5px'> Activity
                &nbsp;&nbsp;
                <input type='radio' name='sharesort' class='manageshares' data-page='1' data-mode='' data-filename='' data-sort='shareexpire' $checked3 style='position:relative;top:5px'> Expiration
                &nbsp;&nbsp;
     ";




$result = do_mysqli_query("1","
    select 
    sharelocal, shareid, sharetitle, sharetype, shareto, sharedate, collection,
    (select postdate from shareposts where shares.shareid = shareposts.shareid
     order by postdate desc limit 1) as lastpost,
    DATE_FORMAT(
        (select postdate from shareposts where shares.shareid = shareposts.shareid
         order by postdate desc limit 1),
     '%m/%d/%y %h:%i %p') as lastpost2,         
    DATE_FORMAT(shareexpire, '%m/%d/%y') as shareexpire,
    DATE_FORMAT(sharedate, '%m/%d/%y %h:%i %p') as createdate, 
    platform, securetype, views, likes, shareexpire as shareexpire2,
    (select count(*) from shareposts where shares.shareid = shareposts.shareid ) as commentcount
    from shares where providerid=$providerid and sharetype in ('A','P','W'  ) and collection='' and views > 0 
    $sort_text
        
    limit $pagestart, $max
    ");


echo "
    <br><br>
     <table class='gridstdborder' style='font-family:helvetica;font-size:13px;padding:0;margin:0'>
     <tr class=messagestitle style='border-width:0px;border-style:solid;padding:0;margin:0;border-spacing:0;'>
        <td class='gridstdborder gridcelltitle'>Image</td>
        <td class='gridstdborder gridcelltitle'>Share Info</td>
        <td class='gridstdborder gridcelltitle'>Kill</td>
     </tr>
     ";

while($row = do_mysqli_fetch("1",$result))
{
    $securetype = 'Private';
    if( $row['securetype']=='O')
        $securetype = 'Open';
    $image = "";

    if( $row['sharetype']=='P' )
        $externallink = "$rootserver/$installfolder/so.php?p=$row[shareid]&v=N";
    if( $row['sharetype']=='A' )
        $externallink = "$rootserver/$installfolder/soa.php?p=$row[shareid]&v=N";
    if( $row['sharetype']=='W' )
        $externallink = "$rootserver/$installfolder/sharew.php?p=$row[shareid]&v=N";
    if( $row['sharetype']=='T' )
        $externallink = "$rootserver/$installfolder/sharefbp.php?s=$row[shareid]&v=N";

    //$image = "$rootserver/$installfolder/photolib/$row[sharelocal]";
    
    if ($row['sharetype']=='P')
    {
        $image = "";
        $result2 = do_mysqli_query("1","select aws_url from photolib where filename='$row[sharelocal]' ");
        if($row2=do_mysqli_fetch("1",$result2)){
            $aws_url = $row2['aws_url'];
            $image = "<img src='$aws_url' style='height:100px;width:100px' />";
        }
    }
    
    if ($row['sharetype']=='A')
    {
        $image = "Album<br><img class='desaturate' src='$rootserver/img/lock2.png' style='height:30px;width:auto' />";

    }
    if ($row['sharetype']=='W')
    {
        $image = "Website<br><img class='desaturate' src='$rootserver/img/home1.png' style='height:30px;width:auto' />";

    }
    if ($row['sharetype']=='T')
    {
        $image = "Post<br><img class='desaturate' src='$rootserver/img/lock2.png' style='height:30px;width:auto' />";

    }
    
    $platform = $row['platform'];
    if($row['platform']=='Facebook')
    {
        $platform = "<img src='../img/facebook-flat.png' style='height:15px;width:auto;' />";
    }
    if($row['platform']=='Google+')
    {
        $platform = "<img src='../img/googleplus.jpg' style='height:15px;width:auto;' />";
    }
    
    if($image !=''){
    echo "
       <tr>
       <td  class='smalltext gridstdborder' style='background-color:whitesmoke;max-width:100px;overflow:hidden'>
       <a class='openshare' href='$externallink' style='text-decoration:none' target='functioniframe'>
       $image
       </a>
       </td>
       <td class='smalltext gridstdborder gridcell' style='background-color:white' >$row[createdate]
       <br>    
       $platform $row[shareto]
       <br>    
       $row[sharetitle]
       <br>    
       <br>    
       Views: $row[views]
       <br>    
       Likes: $row[likes]
       <br>    
       Comments: $row[commentcount]
       <br>    
       <span class=smalltext>
       Last Post:<br>$row[lastpost2]<br>
       Expires: $row[shareexpire]
       </span>
       </td>
       <td class='gridstdborder gridcell' style='background-color:white'>
       <div class='divbuttontextonly divbutton2_unsel manageshares' data-mode='D' data-deletefilename='$row[shareid]' data-page='$page' data-sort='$sort' >
       <img src='../img/delete-gray-128.png' style='height:11px;width:auto;margin:0;padding:0'  />
       </div>
           

        </td>
    </tr>
    <tr>
        ";
    }
    
}

echo "
     </table></div>
     ";

?>