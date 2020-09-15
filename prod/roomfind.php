<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_POST['providerid']);

    $mode = '';
    if(isset($_POST['mode'])){
        $mode = tvalidator("PURIFY",$_POST['mode']);
    }
    $roomid = '';
    if(isset($_POST['roomid'])){
        $roomid = tvalidator("PURIFY",$_POST['roomid']);
    }
    
    
    $call = "feed";
    if($mode == 'P')
    {
        $call = "feedphoto";
    }
    //Find Room Owner
    $result = do_mysqli_query("1","
        select roomid, room, owner
        from statusroom 
        where roomid='$roomid' 
            ");
    if($row = do_mysqli_fetch("1",$result))
    {
        $roomid = $row['roomid'];
        $room = $row['room'];
        $owner = $row['owner'];
    }
    

    echo "
        <div style='background-color:#E5E5E5;padding:20px'>
        <br>
        <span class='pagetitle'>Find Room Posts</span> 
        <br>
                <div class='mainfont feed showtop tapped' 
                    id='feed' data-roomid='$roomid'>
                            <img class='icon20' src='../img/arrow-stem-circle-left-128.png' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />
                            All Rooms&nbsp;&nbsp;
                </div>
         <br><br>
         <div class='mainfont' style='text-align:center;background-color:transparent'>
                        <br><br>
                        <br>
                        <div style='margin:auto;text-align:center'>
                            <span class='pagetitle2'>
                                Room Post Search Criteria
                            </span>
                        </div>
                        <table style='margin:auto;text-align:left'>
                        
                        <tr>
                            <td>
                                Poster Name<br>
                                <input type='text' class='roomfiltername' size=30 style='width:200px;' placeholder='Poster Name' />
                            </td>
                        
                        </tr>
                        <tr>
                            <td>
                                Search Term<br>
                                <input type='text' class='roomfilterterm' size=30 style='width:200px;' placeholder='Search Term' />
                            </td>
                        
                        </tr>
                        <tr>
                            <td>
                                Starting Post Date<br>
                                <input type='date' class='roomfilterdate' size=30 style='width:200px;' />
                            </td>
                        
                        </tr>
                        <tr>
                            <td>
                                <br>
                                <div class='divbuttontext divbuttontext_unsel feed tapped' data-roomid='$roomid' >Search</div>
                                    &nbsp;
                            </td>
                        
                        </tr>
                        </table>
                        
                        <br>
        </div>
        ";
    
    

    
    echo "
        </div></div>
        ";    
    
    
    
?>
