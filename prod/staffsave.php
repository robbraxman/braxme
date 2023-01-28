<?php
session_start();
require_once("config-pdo.php");

require_once("htmlhead.inc.php");
require_once("password.inc.php");
?>
<script>
        $(document).ready( function() {
        });
</script>
</head>
<?php

    $loginid = @tvalidator("PURIFY","$_SESSION[loginid]");
    $providerid = @tvalidator("PURIFY","$_SESSION[pid]");

    $staffloginid = @tvalidator("PURIFY","$_POST[staffloginid]");
    $staffloginid = strtoupper( $staffloginid );
    $staffpassword = @tvalidator("PURIFY","$_POST[staffpassword]");
    $staffname = @tvalidator("PURIFY","$_POST[staffname]");
    $adminright = @tvalidator("PURIFY","$_POST[adminright]");
    $emailalert = @tvalidator("PURIFY","$_POST[emailalert]");
    $email = @tvalidator("PURIFY","$_POST[email]");

    
    $staffpassword = html_entity_decode(purifytext( $staffpassword ),ENT_NOQUOTES);
    $sessionid = session_id();

    $xaccode='STAFFEDIT';
    if($_POST['loginid']!='' && $_POST['mode']=='P'){
    
        $xaccode='PWDCHANGE';
    }

    $result = pdo_query("1", 
      "insert into activitylog " .
      "(loginid, providerid, xacdate, xaccode, sessionid, usertype ) ".
      " values ".
      "('$loginid', $providerid, now(), '$xaccode', '$sessionid','S' ) ",null
    );
        

    if(  $_POST['mode']=='A' || $_POST['mode']=='P' ||
         ($_POST['mode']=='S' && strlen($_POST['staffpassword']) > 0 )   ){
    
        if( strlen($_POST['staffpassword']) <8  ){
        
            echo "&nbsp;&nbsp;Invalid Password Length - Must be 8 or more Characters";
            exit();
        }
    
    }
    if(  $_POST['mode']=='A' || $_POST['mode']=='S' ){
    
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ 
        
             echo "&nbsp;&nbsp;<br>Invalid Email\n<br>";
             exit();
        }        
    }
    
    if( $_POST['mode']=='P'){
    
        $_SESSION['onetimeflag']='';
        $_SESSION['chgpassword']='';
        $pwd_hash = password_hash($staffpassword, PASSWORD_DEFAULT);
        $result = pdo_query("1", 
         " 
            update staff set 
            pwd_ver = 3, 
            pwd_hash = '$pwd_hash',
            fails = 0,
            onetimeflag=''
            where providerid='$providerid' and loginid='$staffloginid' 
            
         ",null
         );
        $result = pdo_query("1", 
         " 
            update provider set chgpassword = ''
            where providerid = $providerid
            
         ",null
         );
        

        if($result){
        
            
            $jspassword = OpenSSLEncrypt($staffpassword,$serverencryptionkey);
            echo "
            <script>
            localStorage.hswt = '$jspassword';
            </script>
            ";
            echo "&nbsp;&nbsp;<b>$appname Password Successfully Changed</b><br><br>";
            $_SESSION['pwd_hash']="$pwd_hash";
            
            
            
        } else {
            echo "SQL Error";
        }
                    
    }
    else
    if( $_POST['mode']=='D')
    {
        $result = pdo_query("1", 
                " update staff set active='N' " .
                " where providerid=$providerid and " .
                " loginid='$staffloginid' ",null);

        if($result)
            echo "&nbsp;&nbsp;Staff Member Inactivated";
        else
            echo "&nbsp;&nbsp;SQL Error";
                    
    }
    else
    if( $_POST['mode']=='A'){

            $result = pdo_query("1", "
                    delete from staff where loginid = '$staffloginid' and providerid = $providerid 
                    and active ='N' 
                    ",null);
        
        
           $result = pdo_query("1", 
                " select * from staff " .
                " where providerid=$providerid and " .
                " loginid='$staffloginid' ",null);
           
           $row = do_mysql_fetch_row($result);
           if( !$row ){
           
                $pwd_hash = password_hash($staffpassword, PASSWORD_DEFAULT);
               
                $result = pdo_query("1", 
                        " insert into staff 
                        (providerid, loginid, staffname, 
                        pwd_ver, pwd_hash,
                        adminright, 
                        emailalert, workgroup, active, email )
                        values 
                        ( $providerid, '$staffloginid','$staffname', 3, '$pwd_hash',
                        '$adminright','$emailalert','MSGSTAFF','Y','$email' ) ",null);
                
                if($result){
                    echo "&nbsp;&nbsp;Login Added";
                } else {
                    echo "&nbsp;&nbsp;SQL Error";
                }
           } else { 
           
                  echo "&nbsp;&nbsp;Error: Duplicate Login, or Previously Used by an Inactive User";
               
           }
        
    
    } else {
        
           $result = pdo_query("1", 
                " select * from staff " .
                " where providerid=$providerid and " .
                " loginid='$staffloginid' ",null);
           
           $row = do_mysqli_fetch_row("1",$result);
           if( $row )
           {

               if( $staffpassword!=''){
               
                    $_SESSION['onetimeflag']='';
                    $pwd_hash = password_hash($staffpassword, PASSWORD_DEFAULT);
                    $result = pdo_query("1", 
                        " update staff set
                          pwd_ver = 3,
                          pwd_hash = '$pwd_hash',
                          onetimeflag=''
                              
                        where providerid=$providerid and 
                        loginid='$staffloginid' 
                        ",null);
               }

                $result = pdo_query("1", 
                    " update staff set" .
                    " active='Y', ".
                    " staffname='$staffname', ".
                    " adminright='$adminright', " .
                    " emailalert='$emailalert', " .
                    " email = '$email' ".
                    " where providerid=$providerid and " .
                    " loginid='$staffloginid' ",null);
                

                if($result){
                    echo "&nbsp;&nbsp;Login Saved";
                } else {
                    echo "&nbsp;&nbsp;SQL Error";
                }
           } else {
               
                echo "&nbsp;&nbsp;Error: Staff Login Not Found";
               
           }
    }

?>
