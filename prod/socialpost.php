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
    DATE_FORMAT( postdate, '%m/%d/%y %h:%i %p') as fpostdate
    from shareposts where shareid='$share' order by postdate asc
    ");
$comments = "
    
<table class='comments gridstdborder' style='width:100%;margin:auto;'> 
            <tr style='background-color:whitesmoke'>
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
            data-postid='$row2[postid]'>
            Delete</div>";
    $poster = $row2[name];
    if( $poster == "")
        $poster = "$row2[ip]";
    $comments .=
            "<tr>
                <td class='commentline gridstdborder'
                    style='text-align:left;width:100%;background-color:white;padding:5px;'>
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