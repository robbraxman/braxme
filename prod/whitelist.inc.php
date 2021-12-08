<?php
function WhiteListCheck($cleaned)
{
    $ip = tvalidator("PURIFY",$_SERVER['REMOTE_ADDR']);
    
    $torexits = file_get_contents("/var/tmp/torexitnodes");
    if(strstr($torexits, $ip)!==FALSE){
        return "tornode";
    }
    
    $ipList = array();
    $ipList[] = "66.165.236.90";
    $ipList[] = "54.189.223.125";
    $ipList[] = "54.165.146.66";
    $ipList[] = "66.165.228.138";
    $ipList[] = "66.165.233.42";
    
    
    
    if(in_array($ip, $ipList)){
        return "whitelist";
    }
    
    $ipList = array();
    $ipList[] = "104.174.149.174";
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
