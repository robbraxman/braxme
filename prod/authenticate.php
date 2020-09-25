<?php
session_start();
require("config-pdo.php");
require("colorscheme.php");
require("crypt-pdo.inc.php");
require_once 'authenticator/GoogleAuthenticator.php';

    $providerid = tvalidator("PURIFY","$_SESSION[pid]");
    $code = tvalidator("PURIFY","$_POST[code]");
    $secret = tvalidator("PURIFY","$_POST[secret]");
    
    if($secret == 'delete'){

        pdo_query("1","
            update staff set auth_hash=null, encoding=null 
            where loginid = '$_SESSION[loginid]' and providerid = $_SESSION[pid] ",null);
        
        $arr = array('msg'=> "Deleted Authenticator",
                     'error'=> "N",
                    );

        echo json_encode($arr);
        exit();
        
    }
    if($code == ''){
        $arr = array('msg'=> "",
                     'error'=> "N",
                    );

        echo json_encode($arr);
        exit();
        
    }

    $ga = new PHPGangsta_GoogleAuthenticator();
    $checkResult = $ga->verifyCode($secret, $code, 2);    // 2 = 2*30sec clock tolerance
    if ($checkResult) {
        
            $secret_encrypted = EncryptText($secret, $providerid);
        
            pdo_query("1","
                update staff set auth_hash=?, encoding='$_SESSION[responseencoding]' 
                where loginid = '$_SESSION[loginid]' and providerid = $_SESSION[pid] ",array($secret_encrypted));
            
            //echo "Successfully added Authenticator";
            $arr = array('msg'=> "Successfully added Authenticator",
                         'error'=> "N",
                        );


            echo json_encode($arr);
            exit();

        
    } else {
        
        //echo "Failed to authenticate";
            $arr = array('msg'=> "Failed to Authenticate. Authenticator app is not activated.",
                         'error'=> "Y",
                        );


            echo json_encode($arr);
            exit();

    }
