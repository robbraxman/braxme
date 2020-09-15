<?php
session_start();
require("validsession.inc.php");
require("config-pdo.php");
$checkbox = '';//"<img src='../img/checkbox-green-128.png' style='height:25px;position:relative;top:5px' />";
?>
 
<div class='aboutarea pagetitle2' style='background-color:<?=$global_titlebar_color?>;color:white;text-align:center'>
    <div style='padding:10px'>
    Security Primer
    </div>
    <div class='abouttext pagetitle2a feedphoto' style='text-align:center'>
        <img class='icon30 info_file tapped' src='../img/arrow-stem-circle-left-128.png' style='margin-left:20px;margin-right:20px' >
        <span class='pagetitle'  style='color:gray'>Tour</span>
        <img class='icon30 tilebutton tapped'     src='../img/arrow-stem-circle-right-128.png' style='margin-left:20px;margin-right:20px' >
        <br><br>
        <div style='text-align:center;max-width:600px;margin:auto'>
        This platform is completely safe
        for exchanging personally identifiable information (PII), financial data, and medical data (HIPAA compliant). (See privacy policy).
        <br><br>
        Your data is unreadable to anyone else but the sender and the receiver.
        <br><br>
        For additional protection, use End-to-End (E2E) encryption on chat messages. Our E2E chat is superior
        to other solutions like Signal because we can provide the E2E layer on a multicast (many users at once).
        <br><br>
        We cannot profile your conversations or track you for advertising purposes. We have 
        no ads.
            
        <!--
        Brax.Me is a secure platform by design. It is completely safe
        for personally identifiable information (PII), financial data, and medical (HIPAA compliant).
        <br><br>
        Your communications on Brax.Me are encrypted in transit (between your device and our cloud servers) 
        using TLS 1.2.
        All of your data is then encrypted before being processed using AES-256 CBC with Forward Secrecy 
        Keys (unique key per message). The data is encrypted another time at rest once again using AES-256.
        <br><br>
        Our key generation/authentication process is unique. Keys are computed on the fly on separate systems
        so only 
        authorized devices can access them. This intense real time key exchange makes our 
        database hack resistant and your content unsearchable.
        <br><br>
        This makes your data unreadable to anyone else but the sender and the receiver.
        <br><br>
        Passwords are not stored. We only keep an SHA2-512 irreversible hash.
        The level of encryption we provide is rated at the level of Top Secret by the US government.
        <br><br>
        For additional protection, use End-to-End (E2E) encryption on chat messages. Our E2E chat is different
        from other solutions like Signal because we can provide the E2E layer on a multicast (many users at once).
        <br><br>
        We cannot profile your conversations or track you for advertising purposes. We have 
        no ads.
        -->
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        <br><br>
        </div>
    </div>
</div>    

       
                   

