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
    if( $source==""){
        echo "<img class='hidecontactbook icon20' src='../img/minus-gray-01-128.png' />";
        echo "
        <table id='addressbook' class='addressbook gridstdborder' style='margin:auto'>
        <tr class='messagestitle gridstdborder'>
        <td class='addressbookrow gridcelltitle  gridstdborder'>#</td>
        <td class='addressbook1 gridcelltitle  gridstdborder'>
            <b>Contact Name</b><br>
            <span class='smalltext2'>Select an existing contact</span>
        </td>
        </tr>
        ";
    }
    if( $source=="1"){
        echo "
        <b><center>Select Contact to Modify</center></b><br>
            
        <table id='addressbook' class='addressbook gridstdborder' style='margin:auto'>
        <tr class='messagestitle gridstdborder'>
        <td class='addressbookrow gridcelltitle  gridstdborder'>#</td>
        <td class='addressbook1 gridcelltitle  gridstdborder'><b>Contact Name</b></td>
        <td class='addressbook5 gridcelltitle  gridstdborder'><b>X</b></td>
        <td class='addressbook7 gridcelltitle  gridstdborder'><b>Block</b></td>
        </tr>
        ";
    }


    
    $limit = 20;
    if($source=='1'){
        $limit = 20;
    }
    $sqllimit = " LIMIT $limit";
    if( $page==''){
        if( $page == ""){
            $page = 1;
        }
        $priorpage = ((intval($page)-1)*$limit);
        $priorpagedisplay = ((intval($page)-1)*$limit)+1;
        $currentpage = $limit;//intval($page)*20;
        $sqllimit  = " LIMIT $priorpage, $currentpage ";
    } else {
        $priorpage = ((intval($page)-1)*$limit);
        $priorpagedisplay = ((intval($page)-1)*$limit)+1;
        $currentpage = $limit;//intval($page)*20;
        $sqllimit  = " LIMIT $priorpage, $currentpage ";
        
    }
    
    $noblock = "";
    if($source==''){
        $noblock = " and (blocked!='Y' or blocked is null)  ";
    }
    
    $notme = "
        and not exists 
            ( select * from provider 
                where providerid=$providerid and 
                ( provider.replyemail=contacts.email or (provider.handle = contacts.handle and provider.handle!='')  ) 
            ) 
        ";
    $notme = "";
    
    
    $result = do_mysqli_query("1", 
        " 
        SELECT contactname, email,sms, handle,blocked, source from contacts 
        where providerid=$providerid 
        $notme
        and (contactname like '%$searchfilter%' or email like '%$searchfilter%' or handle like '%$searchfilter%' ) 
        and contactname not like '%donotreply%' and email not like '%donotreply%' $noblock 
        order by contactname  $sqllimit 
        "
        );
    
    

    $rownum=$priorpagedisplay-1;
    while ($row = do_mysqli_fetch("1",$result)){
        $rownum=$rownum+1;
        if($row['source']=='R' && $row['handle']!=''){
            $row['email']='';
        }

        $sms = $row['sms'];
        if( $sms == '+1')
            $sms = "";
        $blocked= '';
        if($row['blocked']=='Y'){
            $blocked="<b style='color:firebrick'>Blocked</b>";
        }
        echo "<tr class='stdlistrow addressbook unsel  gridstdborder mainfont'>
              <td class='addressbookrow gridcell gridstdborder'><span class='smalltext2'>$rownum</span></td>
              <td class='addressbookrow gridcell gridstdborder mainfont'>
                <div class='addressbook1'>".ucwords($row['contactname'])."</div>
                <div class=addressbook3><span class='smalltext2'>".$row['email']."</span></div>
                <div class=addressbook4>".$sms."</div>
                <div class=addressbook6>".$row['handle']."</div>
                <div class=addressbook99>".$blocked."</div>
              </td>
              ";
        if( $source == '1'){
            echo "<td class=\"addressbook5 gridcell  gridstdborder\">
                    <div class='divbuttontextonly deleterowbutton' data-name='$row[contactname]' data-email='$row[email]'>
                      <img src='../img/delete-gray-128.png' style='height:10px;position:relative;top:2px' />
                    </div>
                 </td>
                 ";
            if($row['blocked']!='Y'){
                echo 
                
                "<td class=\"addressbook7 gridcell  gridstdborder\">
                    <div class='divbuttontextonly blockbutton' data-name='$row[contactname]' data-email='$row[email]' data-handle='$row[handle]'>
                      <img src='../img/block-128.png' style='height:10px;position:relative;top:2px' />
                    </div>
                 </td>
                 ";
            } else {
                echo 
                "<td class=\"addressbook7 gridcell  gridstdborder\">
                     <div class='divbuttontextonly unblockbutton' data-name='$row[contactname]' data-email='$row[email]' data-handle='$row[handle]'>
                       <img src='../img/unblock-128.png' style='height:10px;position:relative;top:2px' />
                     </div>
                </td>
                     ";
            }
        }
        
        echo "</tr>";
        
    }
?>
        </table><br>
        
        

<?php        
        if( $page!=''){
            echo "<center><span class='smalltext'>Displaying Row $priorpagedisplay - $currentpage</span></center>";
        }
?>
        <br>
        <br>
        <br><span id='inactivityseconds' style='display:none'></span>
    </body>
</html>
