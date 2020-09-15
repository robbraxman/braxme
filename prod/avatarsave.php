<?php
session_start();
require_once("config-pdo.php");

require_once("htmlhead.inc.php");
require_once("password.inc.php");
require_once("signupfunc.php");


?>
<script>
        $(document).ready( function() {
        });
</script>
</head>
<?php

    $providerid = tvalidator("PURIFY","$_SESSION[pid]");

    $avatarurl = tvalidator("PURIFY","$_POST[avatarurl]");
    
    $avatarurl = HttpsWrapper($avatarurl);

    if( $avatarurl!='')
    {
        $result = do_mysqli_query("1", 
                " update provider set avatarurl='$avatarurl' where providerid=$providerid "
                );
        echo "Saved";
    }
        

?>
