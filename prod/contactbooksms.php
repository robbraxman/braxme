<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: X-Requested-With');
require_once("config.php");

require_once("crypt.inc.php");

$providerid = mysql_safe_string($_SESSION['pid'] );
$searchfilter = mysql_safe_string($_POST['searchfilter'] );
$page = mysql_safe_string(isset($_POST['page'])? $_POST['page'] : ''  );
$source = mysql_safe_string(isset($_POST['source'])? $_POST['source'] : '' );


?>
<html>
<script>
$(document).ready( function() {
    
});

    

</script>
</head>
<body class='mainfont'>
<?php
    if( $source=="")
    {
        echo "<div class='divbutton divbutton_unsel hidecontactbook'>-</div>";
        echo "
        <table id='addressbook' class='addressbook gridstdborder' style='margin:auto'>
        <tr class='messagestitle gridstdborder'>
        <td class='addressbookrow gridcelltitle  gridstdborder'>#</td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>Contact Name</b></td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>Room</b></td>
        </tr>
        ";
    }
    /*
        <td class='addressbook3 gridcelltitle  gridstdborder'><b>Email</b></td>
        <td class='addressbook4 gridcelltitle  gridstdborder'><b>Text</b></td>
        <td class='addressbook6 gridcelltitle  gridstdborder'><b>Handle</b></td>
     * 
     */
    if( $source=="1")
    {
        echo "
            
        <table id='addressbook' class='addressbook gridstdborder' style='margin:auto'>
        <tr class='messagestitle gridstdborder'>
        <td class='addressbookrow gridcelltitle  gridstdborder'>#</td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>Contact Name</b></td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>SMS</b></td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>Room</b></td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>X</b></td>
        </tr>
        ";
    }


    
    $sqllimit = " LIMIT 20";
    if( $page=='')
    {
        if( $page == "")
            $page = 1;
        $priorpage = ((intval($page)-1)*20);
        $priorpagedisplay = ((intval($page)-1)*20)+1;
        $currentpage = 20;//intval($page)*20;
        $sqllimit  = " LIMIT $priorpage, $currentpage ";
    }
    else 
    {     
        $priorpage = ((intval($page)-1)*20);
        $priorpagedisplay = ((intval($page)-1)*20)+1;
        $currentpage = 20;//intval($page)*20;
        $sqllimit  = " LIMIT $priorpage, $currentpage ";
        
    }
    
    
    
    $result = do_mysqli_query("1", 
        "
        SELECT csvtemp.name, csvtemp.email, csvtemp.sms, csvtemp.ownerid, csvtemp.roomid,
        roominfo.room
        from csvtemp 
        left join statusroom 
            on statusroom.roomid = csvtemp.roomid and statusroom.owner = csvtemp.ownerid
            and statusroom.owner = statusroom.providerid
        left join roominfo 
            on statusroom.roomid = csvtemp.roomid
        where
        (csvtemp.name like '%$searchfilter%' or 
        csvtemp.email like '%$searchfilter%'  ) 
        and csvtemp.ownerid = $providerid
        order by csvtemp.name  $sqllimit 
        "
        );
    

    $rownum=$priorpagedisplay-1;
    while ($row = do_mysqli_fetch("1",$result)) 
    {
        $rownum=$rownum+1;
        

        $sms = $row['sms'];
        if( rtrim($sms) == '+1')
            $sms = "";
        $name = htmlentities($row['name'], ENT_QUOTES);
        echo "<tr class=\"stdlistrow addressbook unsel  gridstdborder\">";
        echo "
              <td class='addressbookrow gridcell gridstdborder'>$rownum</td>
              <td class='addressbookrow gridcell gridstdborder'>
                <div class=addressbook1>".ucwords($row['name'])."</div>
                <div class=addressbook3>".$row['email']."</div>
                <div class=addressbook4 style='display:none'>".$sms."</div>
                <div class=addressbook6 style='display:none'>".$row['roomid']."</div>
              </td>
              <td class='addressbookrow addressbookrow7 gridcell gridstdborder'>$sms</td>
              <td class='addressbookrow addressbookrow5 gridcell gridstdborder'>$row[room]</td>
              <td class='addressbookrow9 gridcell gridstdborder'>
                <div class='divbuttontextonly deleterowbutton' data-roomid='$row[roomid]' data-name='$name' data-email='$row[email]'>
                  <img src='../img/delete-gray-128.png' style='height:10px;position:relative;top:2px' />
                </div>
               </td>
              ";
        echo "</tr>";
        
    }
?>
        </table><br>
        
        

<?php        
        if( $page!='')
            echo "<center>Displaying Row $priorpagedisplay - $currentpage</center>";
?>
        <br>
        <br>
        <br><span id='inactivityseconds' style='display:none'></span>
    </body>
</html>
