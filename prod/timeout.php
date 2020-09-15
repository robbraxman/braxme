<?php
session_start();
require_once("config.php");
require_once("htmlhead.inc.php");

///$providerid = tvalidator("PURIFY",$_POST['providerid']);
//$loginid = tvalidator("PURIFY",$_POST['loginid']);

$date = date(DATE_RFC2822);   
?>
<body>
     <div style='background-color:<?=$global_background?>;color:<?=$global_textcolor?>;font-size:18px;padding:40px;font-family:helvetica'>
        <img src='../img/lock.png' style='height:30px' /><br><br>
        <span class='pagetitle2' style='color:<?=$global_textcolor?>'>
        <?=$appname?> went to sleep for your protection.
        </span>
        <br><br>
        <a id='timeoutreturn' href='<?=$rootserver?>/<?=$startupphp?>&s=<?=$_SESSION['source']?>&v=<?=$_SESSION['version']?>&apn=<?=$_SESSION['apn']?>&gcm=<?=$_SESSION['gcm']?>' style='text-decoration:none;color:<?=$global_textcolor?>'>
            <div class='divbuttontext divbuttontext_unsel' >
                Restart
            </div>
        </a>
        <br><br>
        <br><?=$date?>
     </div>
     <script>
        if(window.navigator.standalone === true) {
            $('#timeoutreturn').hide();
        }
     </script>
</body>
</html>
    

