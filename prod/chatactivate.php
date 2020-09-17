<?php
session_start();
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("ID",$_POST['c']);
    $callingid = @tvalidator("PURIFY",$_POST['a']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    
    
    $result = pdo_query("1",
        "
            insert into chatmaster ( owner, created, status, archive, keyhash )
            values
            ( ?, now(), 'Y',(select archivechat from provider where providerid=?),'' );
        ",array($providerid,$providerid));
    
    
    $result = pdo_query("1",
        "
            select chatid from chatmaster where owner = ? and status='Y' order by created desc
        ",array($providerid));
    
    if( $row = pdo_fetch($result))
    {
        $chatid = $row[chatid];
        
        $result2 = pdo_query("1",
            "
                select chatid from chatmaster where 
                chatid in (select chatid from chatmembers where providerid=?) and
                chatid in (select chatid from chatmembers where providerid=?) 
            ",array($providerid,$callingid));
        
        while( $row2 = pdo_fetch($result2))
        {
            //Delete old chat with same person
            $result3 = pdo_query("1",
                "
                    delete from chatmembers where chatid = $row2[chatid]
                ");
        }
        
        
        $result = pdo_query("1",
            "
                insert into chatmembers ( chatid, providerid, status, lastactive ) 
                values
                ( $row[chatid], ?, 'Y', now() );
            ",array($providerid));
        
        
        if( $mode == 'A')
        {
            $result = pdo_query("1",
                "
                    insert into chatmembers ( chatid, providerid, status, lastactive ) 
                    values
                    ( $row[chatid], ?, 'Y', 0 );
                ",array($callingid));
            if( $result )
            {

                $result = pdo_query("1", " select providername from provider where providerid = ? ",array($providerid));
                $row = pdo_fetch($result);
                $name1 = $row[providername];
                $result = pdo_query("1", " select providername from provider where providerid = ? ",array($callingid));
                $row = pdo_fetch($result);
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

