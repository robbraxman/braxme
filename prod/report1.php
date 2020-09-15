<?php
session_start();


require("validsession.inc.php");
require_once("config.php");
require_once("crypt.inc.php");
if( $_POST['pid']!='' && @$_POST['loginid']!='' )
{
    require("password.inc.php");
}
$date = date('Y_m_d_hisa', time());


if( $_SESSION['superadmin']=='N' || $_SESSION['superadmin']=='' ){
    exit();
}

?>
<html>
    <head>
        <title>Stats Report</title>
        <meta charset='utf-8'>
    </head>
    <body style='font-family:helvetica;font-size:12px;'>

        <h1>Enterprise Access Activity</h1>    
        <table class='' style="font-size:13px;border-width:1;border-color:gray;border-collapse:false;">
            <tr style='padding:5px;border-width:1;border-color:gray;border-collapse:false;'>
                <td>#</td>
                <td><b>Handle</b></td>
                <td><b>User Name</b></td>
                <td><b>Elapsed Days</b></td>
            </tr>

<?php
    $rownum = 0;

    $enterprise = 'dte'; //$_SESSION['sponsor'];//"DTE";
    
    $result = do_mysqli_query("1","
            SELECT staff.providerid, providername, handle, lastfunc, staff.lastaccess, 
            datediff( now(), staff.lastaccess ) as diff
            FROM braxproduction.staff

            left join provider on staff.providerid = provider.providerid
            where time_to_sec(timediff(now(),staff.lastaccess))<60*60*24*720
            and provider.sponsor = '$enterprise' and provider.active='Y'
            order by staff.lastaccess desc
            limit 1000
        ");

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
        
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[handle]</b></td>";
            echo "<td>$row[providername]</td>";
            echo "<td>$row[diff]</td>";


        echo "</tr>";
        
    }
?>
        
    </tbody></table><br>    
    
    
    
</body>
</html>
<?php        
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";

exit;
?>