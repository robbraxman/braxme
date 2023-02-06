<?php

$date = date(DATE_RFC2822);   
?>
<div style='background-color:<?=$global_background?>;color:<?=$global_textcolor?>;font-size:18px;padding:40px;font-family:helvetica'>
   <img src='../img/logo-b2.png' style='height:40px' /><br><br>
   <span class='pagetitle2' style='color:<?=$global_textcolor?>'>
   <?=$appname?> went to sleep for your protection.
   </span>
   <br><br>
   <a id='' href='<?=$rootserver?>/<?=$startupphp?>&s=<?=$source?>&v=<?=$version?>&apn=<?=$apn?>&gcm=<?=$gcm?>' style='text-decoration:none;color:<?=$global_textcolor?>'>
       <div class='divbuttontext divbuttontext_unsel' >
           Restart
       </div>
   </a>
   <br><br>
</div>
    

