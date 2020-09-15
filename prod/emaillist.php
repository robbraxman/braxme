<?php
session_start();
require_once("config.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = @mysql_safe_string($_POST['providerid']);
    $lasttime = @mysql_safe_string($_POST['lasttime']);
    
    $flag = "<img src='../img/check-yellow-128.png' style='height:15px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    
        $i = 1;
        $imapitem = 0;
        $imaphtml_tile = "";
        $imaphtml1 = "";
        $imaphtml2 = "";
        $unsel = "";
        if( !isset($_SESSION['imapname']) || count($_SESSION['imap_name'])==0)
        {
            $imaphtml1 = " <div class='divbuttonsidebar divbuttonsidebar_unsel $unsel tilebutton' id='imapsetupbutton1'>Set Up Account</div><br>";
        }
        else
            $imaphtml2 = " <div class='divbuttonsidebar divbuttonsidebar_unsel $unsel tilebutton'  id='imapsetupbutton'>Add New</div><br>";
        if( is_array($_SESSION['imap_name']))
        {
            foreach( $_SESSION['imap_name'] as $imapname)
            {
                $result2 = do_mysqli_query("2","
                        select * from imap_msgqueue 
                        left join imap_xacqueue on imap_msgqueue.providerid = imap_xacqueue.providerid and
                        imap_msgqueue.uid = imap_xacqueue.uid and imap_msgqueue.imapbox = imap_xacqueue.imapbox and
                        xaccode = 'R'
                        where imap_msgqueue.providerid=$providerid and imap_msgqueue.imapbox=$imapitem
                        and imap_msgqueue.seen=0 and imap_msgqueue.deleted!=1 and xaccode is null and datediff(now(),imap_msgqueue.msgdate) <= 0 
                        and imap_msgqueue.folder='INBOX' and imap_msgqueue.syncflag!='U'
                        ");
                $alert = "";
                if($row2 = do_mysqli_fetch("2",$result2))
                {
                    $alert = "&nbsp;".$flag;
                }
                //$unsel = "divitem_unsel";
                //if( $imap_item == $imapitem)
                //    $unsel = "divitem_sel";


                $imaphtml_tile .= "
                <span style='white-space:nowrap'>
                <div class='imapbutton mainbutton tapped2 pagetitle2a' style='display:inline;width:100px;cursor:pointer' id='imap$i'
                data-imap='$i' data-folder='INBOX' 
                data-name='
                ".$_SESSION['imap_name'][$imapitem]."'>
                ".$_SESSION['imap_name'][$imapitem]."$alert
                </div>
                &nbsp;
                <!--
                <div class='imapbutton mainbutton tapped2 pagetitle3' style='display:inline;width:100px;cursor:pointer' id='imap$i'
                data-imap='$i' data-folder='INBOX' data-subfolders='Y'
                data-name='
                ".$_SESSION['imap_name'][$imapitem]."'>
                    <img src='../img/folder-add-128.png' style='height:12px;position:relative;top:0px;cursor:pointer' />
                </div>
                -->
                </span><br><br>
                 ";



                $i++;
                $imapitem++;
            }
        }
   
 
    $add = "<img class='unreadicon' src='../img/add-new-128.png' style='height:12px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $dot = "<img class='unreadicon' src='../img/graydot-128.png' style='height:3px;width:auto;padding-left:10px; padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $flag = "<img class='chatalert' src='../img/check-yellow-128.png' style='height:15px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    $braxmail = "<img src='../img/brax-mail-round2-oj-128.png' style='position:relative;top:3px;height:30px;width:auto;padding-top:0;padding-left:20px;padding-right:2px;padding-bottom:0px;margin:0' />";

    //<div class='divbutton3 divbutton_unsel textsend'>SMS Poke - Hey, Testing Only!</div>
    
    echo "
        $braxmail <span class='pagetitle'>Choose Email Account</span><br><br>

        <div class='pagetitle3' style='padding:40px;font-weight:200'>
        $imaphtml_tile
        </div>
            
        ";
    
     
?>