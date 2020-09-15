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

$df = disk_free_space("/");

if( $_SESSION['superadmin']!='Y'){
    exit();
}

//    $credentials = RetrieveDatabaseKeysAll();
?>
<html>
    <head>
        <title>Super Stats</title>
        <meta charset='utf-8'>
    </head>
<body style='font-family:helvetica;font-size:12px;'>
<?php


    echo "Timezone Offset $_SESSION[timezoneoffset]<br>";
    echo "Disk Free Space  $df<br>";

    
?>
    <h1>Access Activity</h1>    
    <table style="font-size:13px;border-width:2;border-color:black;border-collapse:false;">
        <tr>
            <td>#</td>
            <td><b>Provider ID</b></td>
            <td><b>Provider Name</b></td>
            <td><b>Last Func</b></td>
            <td><b>Elapsed</b></td>
        </tr>

<?php
    $rownum = 0;

    
    $result = do_mysqli_query("1","
            SELECT staff.providerid, providername, lastfunc, staff.lastaccess, 
            timediff( now(), staff.lastaccess ) as diff
            FROM braxproduction.staff

            left join provider on staff.providerid = provider.providerid
            where time_to_sec(timediff(now(),staff.lastaccess))<60*60*24*30
            and provider.active='Y'
            order by staff.lastaccess desc
            limit 200
        ");

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
        
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[providerid]</b></td>";
            echo "<td>$row[providername]</td>";
            echo "<td>$row[lastfunc]</td>";
            echo "<td>$row[diff]</td>";


        echo "</tr>";
        
    }
    echo "  
        </tbody></table><br>
        ";

?>
    
    <h1>Token Purchases</h1>    
    <table style="font-size:13px;border-width:2;border-color:black;border-collapse:false;">
        <tr>
            <td>#</td>
            <td><b>Provider ID</b></td>
            <td><b>Provider Name</b></td>
            <td><b>Date</b></td>
            <td><b>Tokens</b></td>
            <td><b>Method</b></td>
        </tr>

<?php
    $rownum = 0;

    
    $result = do_mysqli_query("1","
            SELECT tokens.providerid, provider.providername, date_format(tokens.xacdate,'%m/%d/%y') as xacdate, 
            tokens.tokens, tokens.method 
            from tokens
            
            left join provider on tokens.providerid = provider.providerid
            where time_to_sec(timediff(now(),tokens.xacdate))<60*60*24*30
            order by tokens.xacdate desc
            limit 1000
        ");

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
        
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[providerid]</b></td>";
            echo "<td>$row[providername]</td>";
            echo "<td>$row[xacdate]</td>";
            echo "<td>$row[tokens]</td>";
            echo "<td>$row[method]</td>";


        echo "</tr>";
        
    }
    echo "  
        </tbody></table><br>
        ";
    
    
    echo "<br><h1>Room Joins</h1>";
    echo "
    <table style='font-size:13px;border-width:2;border-color:black;border-collapse:false;'>
        <tr>
            <td>#</td>
            <td><b>CreateDate</b></td>
            <td><b>RoomID</b></td>
            <td><b>Provider</b></td>
            <td><b>ProvID</b></td>
            <td><b>OwnerID</b></td>
        </tr>
        ";
    
    $rownum = 0;

    $sqllimit  = "";
    
    $result = do_mysqli_query("1", 
        "SELECT date_add(statusroom.createdate,INTERVAL $_SESSION[timezoneoffset] HOUR) as createdate, 
         roominfo.room, provider.providername, statusroom.owner, statusroom.providerid
         from statusroom 
         left join roominfo on statusroom.roomid = roominfo.roomid
         left join provider on statusroom.providerid = provider.providerid 
         where provider.active='Y' and provider.sponsor = '' and roominfo.profileflag!='Y'
         
         order by createdate desc limit 200
         ");

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        $createtime = "$row[createdate]";
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[createdate]</b></td>";
            echo "<td><b>$row[room]</b></td>";
            echo "<td>$row[providername]</td>";
            echo "<td>$row[providerid]</td>";
            echo "<td>$row[owner]</td>";


        echo "</tr>";
        
    }
    echo "</table>";
    
    
    echo "<br><h1>Sign Ups</h1>";
    
?>
    
    <table style='font-size:13px;border-width:2;border-color:black;border-collapse:false;'>
        <tr>
            <td>#</td>
            <td><b>CreateDate</b></td>
            <td><b>Ver</b></td>
            <td><b>Provider</b></td>
            <td><b>ProvID</b></td>
            <td><b>Email</b></td>
            <td><b>Industry</b></td>
            <td><b>Dealer</b></td>
        </tr>

<?php
    $rownum = 0;

        $sqllimit  = "";
    
    $result = do_mysqli_query("1", 
        "SELECT date_add(createdate,INTERVAL $_SESSION[timezoneoffset] HOUR) as createdate, 
         providername, providerid, replyemail, verified, industry, dealeremail from provider ".
        " where timestampdiff( day, createdate, now() ) < 30 and active='Y' and sponsor = '' ".
        "order by createdate desc limit 2000"
        );

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        $createtime = "$row[createdate]";
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[createdate]</b></td>";
            echo "<td><b>$row[verified]</b></td>";
            echo "<td>$row[providername]</td>";
            echo "<td>$row[providerid]</td>";
            echo "<td>$row[replyemail]</td>";
            echo "<td>$row[industry]</td>";
            echo "<td>$row[dealeremail]</td>";


        echo "</tr>";
        
    }
        echo "</table>";
?>    
        
        
    
    
<?php        
        
    echo "<br><br> ";
    
?>
    
<?php        
        
    echo "<br><br> ";
    
    
    

    $rownum = 0;

    echo "<h1>Blocked List</h1>";
    echo "<table class='smalltext' style='font-size:12px'>";
    
    $result = do_mysqli_query("1", "
            select 
            provider.providername as blockedname, provider.createdate,
            (select providername from provider where blocked.blocker = provider.providerid) as blockername,
            blocked.created as blockdate
            from blocked 
            left join provider on blockee = provider.providerid
            where provider.active = 'Y'
            order by blockedname, blockername
        ");

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        
        echo "<tr class=\"messages\">";
            
        
            echo "<td>$rownum</td>";
            echo "<td>$row[blockedname]</td>";
            echo "<td>$row[createdate]</td>";
            echo "<td>$row[blockername]</td>";
            echo "<td>$row[blockdate]</td>";


        echo "</tr>";
        
    }
    echo "</table>";
?>    
        
    
    
    
</body>
</html>
<?php        

exit;
?>