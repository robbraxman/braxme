<?php
session_start();
require_once("config.php");

$providerid = $_SESSION['pid'];
$contacts = tvalidator("PURIFY",$_POST['contacts']);

$contactArray = explode(";",$contacts);
foreach($contactArray as $contactItem)
{
    $splitContact = explode("=",$contactItem );
    $name = $splitContact[0];
    $email = $splitContact[1];
    
    if( $name!=='')
    {
        $result = do_mysqli_query("1", 
            "select * from contacts where providerid = $providerid  
             and email = '$email' 
            ");
        if( !$row = do_mysqli_fetch("1",$result))
        {
            $result = do_mysqli_query("1", "
                insert ignore into contacts
                 (providerid, contactname, email, sms, handle, friend, imapbox, source )
                 values
                 ($providerid, '$name', '$email', '', '','', null, 'M' )
                "
            );
            
        }
    }   
    $result = do_mysqli_query("1", "
        delete from contacts
        where providerid=$providerid and email like '%noreply%' 
        or email like '%no-reply%'    
        or email like '%no_reply%'    
        or email like '%donotreply%'    
        or email like '%do_not_reply%'    
        or (email like '%facebookmail.com%'  and source='M')  
        or email like '%UNEXPECTED_DATA_AFTER_ADDRESS%'    
        or contactname = ''
        
         "
     );
}


?>