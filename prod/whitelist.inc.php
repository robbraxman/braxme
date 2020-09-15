<?php
function WhiteListCheck($cleaned)
{
    $ip = tvalidator("PURIFY",$_SERVER['REMOTE_ADDR']);
    
    $torexits = file_get_contents("/var/tmp/torexitnodes");
    if(strstr($torexits, $ip)!==FALSE){
        return "tornode";
    }
    
    $ipList = array();
    //$ipList[] = "11.11.11.11";
    
    
    
    if(in_array($ip, $ipList)){
        return "whitelist";
    }
    
    $ipList = array();
    //$ipList[] = "11.11.11.11";
    if(in_array($ip, $ipList)){
        return "internal";
    }
    //We won't return the actual IP address for User safety
    if($cleaned == 1){
        if( strstr($ip,".")!==false ){
            return "normal";
        }
    }
    
    return $ip;
    
}
