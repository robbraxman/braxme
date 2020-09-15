<?php
session_start();
require_once("config-pdo.php");


$timestamp = time();


$result = pdo_query("1",

    "select id, providerid, requestdate, requesttype, status from batchrequest where status='N' "
);

while( $row = pdo_fetch($result))
{
    if($row['requesttype']=='SHARECONTACTSROOM'){
        ShareContactsRoom($row['providerid']);
    }
    pdo_query("1","update batchrequest set status='Y' where id = $row[id]");
}

pdo_query("1","delete from batchrequest where status='Y' ");


function ShareContactsRoom($providerid)
{
        $result = pdo_query("1","
                select providername as contactname, 
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
                             where statusroom.providerid = ? and roominfo.private = 'Y'
                             and roominfo.contactexchange = 'Y' and roominfo.anonymousflag !='Y'
                        )
                    )
                )
            ",array($providerid));
        while( $row = pdo_fetch($result))
        {
            if($row['handle']!='')
            {
                //Delete prior entries that have email if user now has handle
                pdo_query("1","
                    delete from contacts where providerid = ?
                    and email = '$row[email]' and source='R'
                    ",array($providerid));
                $row['email']='';
            }
            pdo_query("1","
                insert into contacts (providerid, contactname, email, sms, handle, friend, imapbox, source, blocked ) values 
                (?, '$row[contactname]','$row[email]','','$row[handle]','',null,'R','') 
                ",array($providerid));
        }    
}

?>