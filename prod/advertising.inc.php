<?php
require_once("config.php");

    function SocialVisionUpgradeAd()
    {
        global $appname;
        global $customsite;
        global $menu_upgrade;
        global $enterpriseapp;
        global $backgroundcolor;
        global $global_textcolor;
        global $global_separator_color;
        global $global_titlebar_color;
        global $rootserver;
        global $installfolder;
        if($_SESSION['handle']=='@appdemo'){
            return "";
        }
        //ALL PLANS - No Advertising
        if($_SESSION['superadmin']!='Y' &&  $_SESSION['store']=='Y' ){
            return "";
        }
        if($_SESSION['roomdiscovery']=='N'){
            return "";
        }
        if( //$_SESSION['superadmin']=='Y' || 
            ($_SESSION['roomcreator']=='Y' && $_SESSION['web']!='Y')){
            $ad = "
                    <div class='pagetitle3' 
                        style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/techsupport2.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            <b>Upgrade your Socialvision</b>!
                            <br><br>
                            Create your own communities, broadcast, multicast, create rooms, websites and your own store in minutes!
                            <br><br>
                            FREE trial.
                            <br><br>
                            <div class='userstore pagetitle3 tapped2  divbuttontilebar2 ' id=''  data-roomid='' data-owner='0'
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            <!--
                            <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]&version=$_SESSION[version]' target=_blank >
                            <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            </a>
                            -->
                        </div>
                        <br>
                    </div>
                    <hr style='border:1px solid  $global_separator_color'>
                    ";
            return $ad;
        }
        if( //$_SESSION['superadmin']=='Y' || 
            ($_SESSION['enterprise']!='Y' && $_SESSION['web']=='Y')){
            return "";
        }
        if( //$_SESSION['superadmin']=='Y' || 
            ($_SESSION['enterprise']=='Y' && $_SESSION['store']=='Y')){
            return "";
            
        }
        
        if( //$_SESSION['superadmin']=='Y' || 
            ($_SESSION['enterprise']=='Y' && $_SESSION['store']!='Y')){
            $ad = "
                    <div class='pagetitle3' 
                        style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/techsupport2.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            <b>Upgrade Socialvision</b>!
                            <br><br>
                            Upgrade your account to include an Online Store!
                            <br><br>
                            <div class='userstore pagetitle3 tapped2  divbuttontilebar2 ' id=''  data-roomid='' data-owner='0'
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            <!--
                            <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]&version=$_SESSION[version]' target=_blank >
                            <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            </a>
                            -->
                        </div>
                        <br>
                    </div>
                    <hr style='border:1px solid  $global_separator_color'>
                    ";
            return $ad;
        }

        
        $ad = "
                    <div class='pagetitle3' 
                        style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/techsupport2.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            <b>$menu_upgrade</b>!
                            <br><br>
                            You have a FREE user account.
                            <br><br>
                            Be a content creator!
                            <br><br>
                            Use $enterpriseapp to build a private community. Broadcast, blog, build websites and an online store.
                            <br><br>
                            FREE trial.
                            <br><br>
                            <div class='userstore pagetitle3 tapped2  divbuttontilebar2 ' id=''  data-roomid='' data-owner='0'
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            <!--
                            <a href='$rootserver/$installfolder/host.php?f=_store&h=app&p=$_SESSION[pid]&version=$_SESSION[version]' target=_blank >
                            <div class='pagetitle3 divbuttontilebar2 tapped2' id='' 
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            </a>
                            -->
                        </div>
                        <br>
                    </div>
                    <hr style='border:1px solid  $global_separator_color'>
                    ";
        return $ad;
        
        
    }
    function BytzVPNAd()
    {
        global $customsite;
        global $menu_upgrade;
        global $enterpriseapp;
        global $backgroundcolor;
        global $global_textcolor;
        global $global_separator_color;
        global $global_titlebar_color;
        return "";
        if($_SESSION['handle']=='@appdemo'){
            return "";
        }
        
        if($customsite){
            return "";
        }
        if($_SERVER['REMOTE_ADDR']=='66.165.236.90' || $_SERVER['REMOTE_ADDR']=='54.189.223.125' || $_SERVER['REMOTE_ADDR']=='54.165.146.66' ){
            if($_SESSION['superadmin']!='Y'){
                return "";
            }
        }
        
        
        $ad = "
                    <div class='pagetitle3' 
                        style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/techsupport3.png' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            <b>A VPN is no longer optional</b>!<br><br> 
                            Your Ip Address is $_SERVER[REMOTE_ADDR].<br><br>
                            You can have safety and privacy on the Internet 
                            only if you have a VPN. Your email shows your exact 
                            location.
                            <br><br>
                            <a href='https://bytzvpn.com/ovpn/needvpn.php?p=$_SESSION[pid]&version=$_SESSION[version]' style='text-decoration:none' target=_blank >
                            <div class='pagetitle3 divbuttontilebar2 tapped2' 
                                style='background-color:$global_titlebar_color;color:white'>
                                                        Learn More
                            </div>
                            </a>
                        </div>
                        <br>
                    </div>
                    <hr style='border:1px solid  $global_separator_color'>
                    ";
        return $ad;
        
        
    }
