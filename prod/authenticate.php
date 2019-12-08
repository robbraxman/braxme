<?php
session_start();
require("config.php");
require("colorscheme.php");
require("crypt.inc.php");
require_once 'authenticator/GoogleAuthenticator.php';

    $providerid = mysql_safe_string("$_SESSION[pid]");
    $code = mysql_safe_string("$_POST[code]");
    $secret = mysql_safe_string("$_POST[secret]");
    
    if($secret == 'delete'){

        do_mysqli_query("1","
            update staff set auth_hash=null, encoding=null 
            where loginid = '$_SESSION[loginid]' and providerid = $_SESSION[pid] ");
        
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
        
            do_mysqli_query("1","
                update staff set auth_hash='$secret_encrypted', encoding='$_SESSION[responseencoding]' 
                where loginid = '$_SESSION[loginid]' and providerid = $_SESSION[pid] ");
            
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
