<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("aws.php");

    if($_SESSION['superadmin']!='Y'){
        exit();
    }
    $handle = mysql_safe_string($_REQUEST['handle']);
        

    
    
    /*************************************************************
     * 
     * 
     * 
     * 
     *  ALPHABETICAL LIST
     * 
     * 
     * 
     */
    $time2 = microtime(true);

    ActivePhotos($handle);
    

function ActivePhotos($handle)
{
    
    $result = do_mysqli_query("1","
            select filename, aws_url from photolib where providerid in 
            (select providerid from provider where handle='$handle' )
            
            ");

    $count = 0;
              
    while($row = do_mysqli_fetch("1",$result)){
        

        $photourl = "
                <div class='circular2 icon50' style='float:left;text-align:center;vertical-align:top;;overflow:hidden;top:0px;margin-right:10px'>
                    <img src='$row[aws_url]' style='height:100%;width:auto;max-width:100%' />
                </div>
                ";
        
        echo "
            <div class='rounded roomselectbutton tapped2 shadow' data-roomid='$row[roomid]' 
              style='display:inline-block;cursor:pointer;border:1px solid lightgray;
              text-align:left;
              background-color:white;
              min-height:120px;
              min-width:12%;
              padding:10px;margin:10px'>
                  <div class=mainfont 
                  style='float:left;display:inline-block;color:black;
                  '>
                  </div><br>
                    $photourl  
            </div>
             ";
            
        
        
        
    }

}

?>
