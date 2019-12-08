<?php
session_start();
require_once("config.php");
require_once("room.inc.php");
require_once("sidebar.inc.php");


           

        if( (!isset($_SESSION['pid']) || $_SESSION['pid']=='') && 
            (!isset($_SESSION['reset']) )
          ){ //Invalid Session
        

            $arr = array('notification'=>"Y"
                    );

            echo json_encode($arr);
            exit();

        }
        if(!isset($_POST['providerid']) || !isset($_SESSION['pid'])){
        
            $arr = array('notification'=>"Y"
                    );
            

            echo json_encode($arr);
            exit();
            
        }
        
        
        $startup = @mysql_safe_string($_POST['startup']);
        $providerid = mysql_safe_string($_POST['providerid']);
        
        if( TimeOutCheck()){
            $arr = array('notification'=>"T");
            echo json_encode($arr);

            exit();
            
        }
        
        
        $notificationstatus = NotificationStatus($providerid, false);
    
        $arr = array('notification'=>"$notificationstatus");


        echo json_encode($arr);
        exit();
    ?>