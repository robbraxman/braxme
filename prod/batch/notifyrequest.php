<?php
session_start();
set_time_limit ( 60 );
require_once("config.php");
require_once("crypt.inc.php");
require_once("notify.inc.php");
require_once("sendmail.php");
require ("SmsInterface.inc");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require ("aws.php");

    if($batchruns !='Y') {
        exit();
    }
    
    $notifygroup = '';
    try {
        if(sizeof($argv)>1) {
            $notifygroup =  $argv[1];
        }
    }
    catch( Exception $e) {
    }
    

    try {
        $maxruns = 30;
        $time_start = microtime(true);
        for($i=0;$i < $maxruns;$i++){

            $time_check = microtime(true);
            if( ($time_check - $time_start) > 55 ){

                exit();
            }
            echo "Loop<br>";
            NotificationRequestLoop();
            sleep(1);

            BatchSendText();

            sleep(3);
        }
    }
    catch( Exception $e) {
        error_log("NotifyRequest Crashed", 0);        
        exit();
    }
    /******************************************
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */
    
        
?>