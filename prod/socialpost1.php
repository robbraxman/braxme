<?php
session_start();
require_once("config.php");

$share = tvalidator("PURIFY", $_GET[p] );
$ip = tvalidator("PURIFY", $_GET[ip] );
$c = tvalidator("PURIFY", $_GET[c] );
$n = tvalidator("PURIFY", $_GET[n] );
$d = tvalidator("PURIFY", $_GET[d] );
$i = tvalidator("PURIFY", $_GET[i] );
$action = tvalidator("PURIFY", $_GET[a] );

$result = do_mysqli_query("1","
    select shareto, platform from shares where shareid='$share'
    ");
$row = do_mysqli_fetch("1",$result);
$shareto = $row[shareto];
if( $shareto == "Unspecified")
    $shareto = "$row[platform]";

if( $action == 'D')
{
    $result = do_mysqli_query("1","
        delete from shareposts where shareid='$share' and ip='$ip' and postid='$i'
        ");
    
    
}
else
{
    $c = strip_tags($c, "<img><a><br><ul><li><iframe><b><u><i>");

    if( $c !="")
    {
        do_mysqli_query("1","
            insert into shareposts (shareid, ip, postdate, comment,name, device ) values
            ('$share','$ip',now(), '$c','$n','$d' )
            ");
    }
}
//***********************************************
//***********************************************
//***********************************************


$result2 = do_mysqli_query("1","
    select name, ip,comment, postid,
    DATE_FORMAT( postdate, '%Y-%m-%d %H:%i') as postdate,
    DATE_FORMAT( postdate, '%m/%d/%y %h:%i %p') as fpostdate,
    (select likes from shares where shareid='$share') as liketotal,
    from shareposts where shareid='$share' order by postdate asc
    ");

$comments = "
        <table class='comments share'> 
            <tr>
                <td class='picexpand' 
                    background='$rootserver/$installfolder/sharebase.php?p=$share'
                </td>
            </tr>
            <tr>
                <td style='text-align:center;font-size:15px'>
                    <b>$piccomments</b>            
                </td>
            </tr>
            <tr>
                <td  style='text-align:center;background-color:white'  >
                    <br>
                    <div class='like divbutton3 divbutton3_unsel' style='color:steelblue;cursor:pointer' >
                        <img src='../img/thumbs-up-128.png' style='height:12px;margin:0;padding:0' />
                        +<span class='likescore'>$likes</span>
                    </div>&nbsp;&nbsp;&nbsp;&nbsp; Views: $views
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href='http://www.facebook.com/sharer.php?u=$sharelink' target=_blank style='text-decoration:none;color:steelblue'>
                        FB Share 
                        </a>                
                        <br><br>


                </td>
            </tr>
            ";

while( $row2 = do_mysqli_fetch("1",$result2))
{
    $action = "&nbsp;&nbsp;&nbsp;<div 
            class='delete' 
            style='display:inline;cursor:pointer;color:steelblue;font-weight:bold'
            data-ip='$row2[ip]'
            data-postid='$row2[postid]'>
            Delete</div>";
    $poster = $row2[name];
    if( $poster == "")
        $poster = "$row2[ip]";
    $comments .= "
            
            <tr>
                <td class='commentline'
                    style='text-align:left;width:100%;background-color:white;padding:5px;border-style:solid;border-width:5px 5px 5px 5px;border-color:whitesmoke'>
                       <span style='font-weight:bold;color:steelblue'>$poster</span>
                       $row2[comment]<br>
                       <span style='font-weight:normal;color:gray'>$row2[fpostdate]</span>
                       <span class='action' style='display:none;font-weight:normal;color:gray'>$action</span>
                </td>
             </tr>";
}
$comments .= "</table>";


//***********************************************
//***********************************************
//***********************************************

echo $comments;

?>