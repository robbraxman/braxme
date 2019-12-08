<?php
session_start();
require_once("config.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = @mysql_safe_string($_POST['c']);
    $callingid = @mysql_safe_string($_POST['a']);
    $mode = @mysql_safe_string($_POST['mode']);
    
    
    $result = do_mysqli_query("1",
        "
            insert into chatmaster ( owner, created, status, archive, keyhash )
            values
            ( $providerid, now(), 'Y',(select archivechat from provider where providerid=$providerid),'' );
        ");
    
    
    $result = do_mysqli_query("1",
        "
            select chatid from chatmaster where owner = $providerid and status='Y' order by created desc
        ");
    
    if( $row = do_mysqli_fetch("1",$result))
    {
        $chatid = $row[chatid];
        
        $result2 = do_mysqli_query("1",
            "
                select chatid from chatmaster where 
                chatid in (select chatid from chatmembers where providerid=$providerid) and
                chatid in (select chatid from chatmembers where providerid=$callingid) 
            ");
        
        while( $row2 = do_mysqli_fetch("1",$result2))
        {
            //Delete old chat with same person
            $result3 = do_mysqli_query("1",
                "
                    delete from chatmembers where chatid = $row2[chatid]
                ");
        }
        
        
        $result = do_mysqli_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastactive ) 
                values
                ( $row[chatid], $providerid, 'Y', now() );
            ");
        
        
        if( $mode == 'A')
        {
            $result = do_mysqli_query("1",
                "
                    insert into chatmembers ( chatid, providerid, status, lastactive ) 
                    values
                    ( $row[chatid], $callingid, 'Y', 0 );
                ");
            if( $result )
            {

                $result = do_mysqli_query("1", " select providername from provider where providerid = $providerid ");
                $row = do_mysqli_fetch("1",$result);
                $name1 = $row[providername];
                $result = do_mysqli_query("1", " select providername from provider where providerid = $callingid ");
                $row = do_mysqli_fetch("1",$result);
                $name2 = $row[providername];

                $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";

                echo "<br><center>Chat is now active between $name1 and $name2</center><br><br>";
            }
            else
            {
                echo "Chat Failed";
                exit();
            }
        }

    }
    if( $mode == 'T')
    {
        echo "<div style='padding:20px'><div class='pagetitle2' style='color:white'>Temporary Chat Started</div>
              <span style='color:white'>Invitation has been sent. Chat session will resume when other party joins</span>
              </span>
              <br>
              ";
    }
    echo 
        "  <center> <span class='pagetitle' style='white-space:nowrap'>
            $dot <div class='divbutton4 divbutton4_unsel' id='setchatsession' data-chatid='$chatid' data-keyhash='' >
            Start Chat
            </div></span></center><br>
        ";

    exit();

    
    
?>

