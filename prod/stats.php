<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

if( $_POST['pid']!='' )
{
    require("password.inc.php");
}
require_once("crypt.inc.php");

if( $_SESSION['superadmin']!='Y' && $_SESSION['superadmin']!='A')
    exit();
//var_dump($_SESSION);
?>
<html>
    <head>
        <title>Confidential App Stats</title>
        <meta charset='utf-8'>
    </head>
<body style="font-family:helvetica;font-size:10px">
    <h1>Confidential App Stats</h1>
<?php

    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM provider    "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Users (Includes Inactive and Enterprise):  $row[count]</h3>";
    }


    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM provider where active='Y'   "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Active Users:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1", 
        "SELECT count(*) as count
        from provider where timestampdiff( day, createdate, now() ) <= 30 and active='Y' 
        order by createdate desc"
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>30 Day Signups:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1", 
        "SELECT count(*) as count
        from provider where timestampdiff( day, createdate, now() ) <= 90 and active='Y' 
        order by createdate desc"
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>90 Day Signups:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1", 
        "SELECT count(*) as count
        from provider where timestampdiff( day, createdate, now() ) <= 365 and active='Y' 
        order by createdate desc"
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>1 Year Signups:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.staff

            left join provider on staff.providerid = provider.providerid
            where time_to_sec(timediff(now(),staff.lastaccess))<60*60*24*1
            and provider.active='Y'
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Last 24 Hours Active Users:  $row[count]</h3>";
    }
    
    
    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.staff

            left join provider on staff.providerid = provider.providerid
            where time_to_sec(timediff(now(),staff.lastaccess))<60*60*24*30
            and provider.active='Y'
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>30 Day Active Users:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.staff

            left join provider on staff.providerid = provider.providerid
            where time_to_sec(timediff(now(),staff.lastaccess))<60*60*24*90
            and provider.active='Y'
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>90 Day Active Users:  $row[count]</h3>";
    }
    


    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.staff

            left join provider on staff.providerid = provider.providerid
            where time_to_sec(timediff(now(),staff.lastaccess))<60*60*24*30
            and provider.active='Y'
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>30 Day Active Users:  $row[count]</h3>";
    }
    
    
    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.provider where active='Y' and score is not null and score > 0
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Active Users with a Reputation Score:  $row[count] </h3><span class='smalltext'>Reputation score is based on Room and Broadcast Participation</span>";
        
    }
    
    $result = do_mysqli_query("1","
        select count(*) as count from provider where providerid in
        (select distinct (statusroom.providerid) from statusroom 
        left join provider on statusroom.owner = provider.providerid
        left join roominfo on roominfo.roomid = statusroom.roomid
        where statusroom.owner = statusroom.providerid
        and provider.active='Y' and roominfo.profileflag != 'Y')    
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Users with Rooms Owned:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.chatmessage
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Open Chat Messages:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
    
        select count(*) as count from provider where provider.providerid in
        (select distinct (provider.providerid) from chatmembers 
        left join provider on chatmembers.providerid = provider.providerid
        where 
        provider.active='Y' )    
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Users who are Members of an Open Chat:  $row[count]</h3>";
    }

    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.roominfo
            where room !='About Me'
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Rooms:  $row[count]</h3>";
    }

    
    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM photolib "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Photos:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM filelib "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Files:  $row[count]</h3>";
    }
    
    
    $result = do_mysqli_query("1","
        select count(*) as count from notification
            where time_to_sec(timediff(now(),notifydate))<60*60*24*1
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total 24 Hour Notifications Sent:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
            SELECT count(*) as count
            FROM braxproduction.statuspost
            where time_to_sec(timediff(now(),postdate))<60*60*24*30
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Room Posts in 30 days:  $row[count]</h3>";
    }
    
    
    $result = do_mysqli_query("1","
            select count(*) as count from statuspost where roomid = 573;
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Posts in #politics:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
            select count(*) as count from statuspost where roomid = 378;
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Posts in #areyousheep:  $row[count]</h3>";
    }
    
    $result = do_mysqli_query("1","
            select count(*) as count from statuspost where roomid = 139;
        ");
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Posts in #foodies:  $row[count]</h3>";
    }
    
    
    /*
    
    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM chatmessage "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Open Chat Messages:  $row[count]</h3>";
    }
    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM statuspost "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Room Posts:  $row[count]</h3>";
    }
    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM photolib "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Photos:  $row[count]</h3>";
    }
    $result = do_mysqli_query("1", 
        " SELECT sum(filesize)/1000000 as count FROM photolib "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Photos Size:  $row[count] mb</h3>";
    }

    $result = do_mysqli_query("1", 
        " SELECT count(*) as count FROM filelib "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Files:  $row[count]</h3>";
    }
    $result = do_mysqli_query("1", 
        " SELECT sum(filesize)/1000000 as count FROM filelib "
        );
    if ($row = do_mysqli_fetch("1",$result)) 
    {
        echo "<h3>Total Files Size:  $row[count] mb</h3>";
    }
     * 
     */
    
?>    
    
    
    <h1>30 Day Sign Ups</h1>
    
    <table style="border-width:2;border-color:black;border-collapse:false;font-size:11px">
        <tr>
            <td>#</td>
            <td><b>CreateDate</b></td>
            <td><b>Name/Email</b></td>
        </tr>

<?php
    $rownum = 0;

        $sqllimit  = "";
    
    $result = do_mysqli_query("1", 
        "SELECT date_format(date_add(createdate,INTERVAL $_SESSION[timezoneoffset] HOUR), '%m/%d/%y %H:%i') as createdate2,  
         providername, replyemail, handle, sponsor, enterprise, joinedvia, iphash, iphash2, appname
         from provider 
         where timestampdiff( day, createdate, now() ) < 30 and active='Y' 
         order by createdate desc limit 50"
        );

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        $entepriseflag = "[E-".$row['enterprise']."]";
    if($row['enterprise']!='Y'){
        $enterpriseflag = "";
    }
    $app = '';
    if($row['appname']=='Powa'){
        $app = 'Powa';
    }
        
        $rownum=$rownum+1;
        $noroom = '';
        //if($row['room']=='')
        //{
            $noroom = '----';
        //}

        echo "<tr class=\"messages\">";
            
        
        //$createtime = "$row[createdate]";
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[createdate2]</b></td>";
            echo "<td>$row[providername] $row[joinedvia]<br>$row[handle] $app $enterpriseflag<br>$noroom<b></b></td>";


        echo "</tr>";
        
    }
        echo "</table>";
?>        
<?php
if($superadmin == 'Yx')
{
?>
        
    
    <h1>Recent Activity</h1>
    
    <table style="border-width:2;border-color:black;border-collapse:false;font-size:11px">
        <tr>
            <td>#</td>
            <td><b>LastAccess</b></td>
            <td><b>Name/Email</b></td>
        </tr>

<?php
    $rownum = 0;

        $sqllimit  = "";
    
    $result = do_mysqli_query("1", 
        "SELECT date_format(date_add(staff.lastaccess,INTERVAL $_SESSION[timezoneoffset] HOUR), '%m/%d/%y %H:%i') as lastaccess2,  
            staff.lastaccess, provider.iphash, provider.iphash2, provider.joinedvia,
         provider.providername, provider.replyemail from staff 
         left join provider on provider.providerid = staff.providerid
         where provider.providerid != 690001027 and active='Y'
         order by lastaccess desc limit 200"  
        );

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        $createtime = "$row[lastaccess2]";
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[lastaccess2]</b></td>";
            echo "<td>$row[providername]<br>$row[joinedvia]</td>";


        echo "</tr>";
        
    }
        echo "</table>";
?>                
<?php
}

/*

if($superadmin == 'Yx')
{
?>
        
    
    <h1>Room Members</h1>
    
    <table style="border-width:2;border-color:black;border-collapse:false;font-size:11px">
        <tr>
            <td>#</td>
            <td><b>Room</b></td>
            <td><b>Count</b></td>
        </tr>

<?php
    $rownum = 0;

        $sqllimit  = "";
    
    $result = do_mysqli_query("1", "
        select room, roomid,
         ( select count(*) from statusroom s2 where statusroom.roomid = s2.roomid) as membercount
         from statusroom where owner=providerid 
         order by membercount desc limit 10         "
        );

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[room]</b></td>";
            echo "<td>$row[membercount]</td>";


        echo "</tr>";
        
    }
        echo "</table>";

             
 * 
 */   
?>         

        
    <br><h1>Enterprise 30 Day Demo Signups</h1>
    
    <table class='smalltext' style="border-width:2;border-color:black;border-collapse:false;">
        <tr>
            <td>#</td>
            <td><b>SignupDate</b></td>
            <td><b>Enterprise</b></td>
            <td><b>Username</b></td>
            <td><b>Handle</b></td>
        </tr>
        
        
<?php



    $rownum = 0;

    $sqllimit  = "";
    
    $result = do_mysqli_query("1", "
        select date_format(createdate, '%y/%m/%d %H:%i') as createdate, handle, sponsor, providername from provider where providerid in (
        select providerid from msgplan where providerid in (
        SELECT providerid FROM braxproduction.payments where coupon = 'demo101'  )
        ) and active='Y' and enterprise ='Y'
        order by createdate desc
        ");

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        $createtime = "$row[createdate]";
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[createdate]</b></td>";
            echo "<td>$row[sponsor]</td>";
            echo "<td>$row[providername]</td>";
            echo "<td>$row[handle]</td>";


        echo "</tr>";
        
    }
    echo "</table>";
?>        

        
    <br><h1>Landing Page Activity</h1>
    
    <table class='smalltext' style="border-width:2;border-color:black;border-collapse:false;">
        <tr>
            <td>#</td>
            <td><b>Date</b></td>
            <td><b>Landing</b></td>
            <td><b>Mobile</b></td>
        </tr>

<?php
    $rownum = 0;

        $sqllimit  = "";
    
  $result = do_mysqli_query("1", 
        "SELECT date_format(date_add(createdate,INTERVAL $_SESSION[timezoneoffset] HOUR), '%m/%d/%y %h:%i<br>%a') as createdate,  
         landingcode, mobile from landing ".
        " where timestampdiff( day, createdate, now() ) < 30  ".
        "order by createdate desc"
        );

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        $createtime = "$row[createdate]";
        
        echo "<td>$rownum</td>";
        
            echo "<td><b>$row[createdate]</b></td>";
            echo "<td>$row[landingcode]</td>";
            echo "<td>$row[mobile]</td>";


        echo "</tr>";
        
    }
        echo "</table>";
?>        
        
        
</body>
</html>
<?php 
/*

$rownum = 0;

echo "
    <br><h1>Bandwidth Use</h1>
    
    <table class='smalltext' style='border-width:2;border-color:black;border-collapse:false;'>
        <tr>
            <td><b>#</b></td>
            <td><b>Name</b></td>
            <td><b>Bandwidth Use GB</b></td>
            <td><b>Filesize GB</b></td>
            <td><b>Restricted</b></td>
        </tr>
";

  $result = do_mysqli_query("1", 
        "
        select provider.providerid, provider.createdate, providername, provider.blockdownload,
        sum(views * filesize)/(1000000*1000) as bandwidth, 
        sum(filesize/(1000000*1000)) as filesize from filelib
        left join provider on provider.providerid = filelib.providerid
        group by provider.providerid
        order by bandwidth desc limit 50
        "
        );

    
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        
        $rownum=$rownum+1;

        echo "<tr class=\"messages\">";
            
        
        $createtime = "$row[createdate]";
        
        echo "<td>$rownum</td>";
        
        echo "<td><b>$row[providername]</b><br>$row[providerid]</td>";
        echo "<td>$row[bandwidth]</td>";
        echo "<td>$row[filesize]</td>";
        echo "<td>$row[blockdownload]</td>";
 

        echo "</tr>";
        
    }
        echo "</table>";
?>        
        
        
</body>
</html>
