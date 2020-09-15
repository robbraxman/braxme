<?php
session_start();
require_once("config.php");


$timestamp = time();


$result = do_mysqli_query("1",
    "select id, providerid, requestdate, requesttype, status from batchrequest where status='N' "
);

while( $row = do_mysqli_fetch("1",$result))
{
    if($row['requesttype']=='SHARECONTACTSROOM'){
        ShareContactsRoom($row['providerid']);
    }
    do_mysqli_query("1","update batchrequest set status='Y' where id = $row[id]");
}

do_mysqli_query("1","delete from batchrequest where status='Y' ");


function ShareContactsRoom($providerid)
{
        $result = do_mysqli_query("1","
                select providerid, providername as contactname, 
                replyemail as email, '' as sms, handle, '' as friend, 
                null as imapbox, 'R' as source, '' as blocked
                from provider 
                where active='Y' and 
                (
                    providerid in 
                    (   
                        select providerid from statusroom
                        where roomid in (
                            select statusroom.roomid from statusroom 
                            left join roominfo on statusroom.roomid = roominfo.roomid
                             where statusroom.providerid = $providerid and roominfo.private = 'Y'
                             and roominfo.contactexchange = 'Y' and roominfo.anonymousflag !='Y'
                        )
                    )
                )
            ");
        while( $row = do_mysqli_fetch("1",$result))
        {
            if($row['handle']!='')
            {
                //Delete prior entries that have email if user now has handle
                do_mysqli_query("1","
                    delete from contacts where providerid = $providerid
                    and email = '$row[email]' and source='R'
                    ");
                $row['email']='';
            }
            $targetproviderid = $row['providerid'];
            do_mysqli_query("1","
                insert into contacts (providerid, contactname, email, sms, handle, friend, imapbox, source, blocked, targetproviderid, createdate ) values 
                ($providerid, '$row[contactname]','$row[email]','','$row[handle]','',null,'R','', $targetproviderid, now() ) 
                ");
        }    
}

?>