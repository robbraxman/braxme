<?php
session_start();
require_once("config.php");
require_once("htmlhead-open.inc.php");
?>
<BODY class="loginbody" style="text-align:center;position:relative;width:100%;background-color:#666666; color:white;
      ">
    <div class='bannerflushfixed' style='background-color:whitesmoke;padding-right:20px;text-align:right;width:100%'>
        <img src="../img/bigstock-woman-using-mobile-phone.jpg" alt='' title='' style="float:right;height:0px;width:0px;display:none">
        <img src="../img/logo.png" alt='Brax.Me' title='Brax.Me' style="float:left;width:auto;height:35px;padding-top:0;padding-left:40px;">
        <div style='color:black;padding-top:10px'>
            <a href='<?=$rootserver?>' style='text-decoration:none'>
                <span style='color:black;padding-right:40px;font-size:16px;font-weight:bold;font-family:Helvetica;'>Home</span>
            </a>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
<?php

    
    $result = do_mysqli_query("1","
            select roomhandle.handle, roomhandle.roomdesc, roomhandle.roomid, roomhandle.name
            from roomhandle 
            where public = 'Y' and
            roomhandle.handle not in ('#braxme')
            order by rank desc, handle asc limit 500
            ");
    
    while($row = do_mysqli_fetch("1",$result))
    {
        $handleshort = substr($row['handle'],1);
        echo "
              <a href='$rootserver/room-view/$handleshort' style='text-decoration:none;color:white'>
              <div 
                style='display:inline-block;cursor:pointer;border:0px solid lightgray;
                background-color:#666666;
                width:250px;height:60px;padding:10px;margin-bottom:10px'>
                    <div class=pagetitle2 
                    style='display:inline-block;color:white;
                    height:25px;font-weight:300'>
                        $row[name]
                            <br>
                    </div>
                <span class=mainfont style='color:white'><br>$row[handle]<br></span>
                <span class='mainfont'>
                    $row[roomdesc]
                </span>
              </div>
              </a>
             ";
    }

    
echo "
    </div></div>
    </body>
    </html>
    ";    
    
    
    
?>
