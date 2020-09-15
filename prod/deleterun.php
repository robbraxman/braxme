<?php
session_start();
set_time_limit ( 30 );
require_once("config-pdo.php");


$timestamp = time();



$result = pdo_query("1", 
    "update attachments set archive='Y' where sessionid not in ".
    "(select sessionid from msgmain ) "
);


$result = pdo_query("1", 
    "select attachfilename, providerid from attachments where archive='Y' "
);

//$path = "c:/inetpub/vhosts/braxmobile.com/braxsecure.com/prod/";
$path = "/var/www/html/$installfolder/upload";
//    c:\\inetpub\\vhosts\\brax1.secureserver.net\\braxsecure\\prod\\upload\\"; // change the path to fit your websites document structure

//echo "Test = $path";

while( $row = pdo_fetch($result))
{
    echo "$fullpath";
    $fullPath = $path."/".$row['attachfilename'];
    if( file_exists( $fullpath ))
    {
        echo "$fullpath";
    }
    
    unlink( $fullPath );
    
}

$result = pdo_query("1", 
    "delete from attachments where archive='Y' "
);


$result = pdo_query("1", 
    "delete from shares where shareexpire < now() "
);



$result = pdo_query("1", 
    "delete from shares where views = 0 and datediff(now(), sharedate ) > 1 "
);



$result = pdo_query("1", 
    "
    delete from notification where status='Y' and
    notifydate < curdate()-7
    "
);

$result = pdo_query("1", 
    "
    update notification set status = 'Y' where status='N' and
    datediff( currdate(), notifydate ) > 1
    "
);


$result = pdo_query("1", 
    "
    delete from statuspost where roomid not in (select distinct roomid from statusroom )
    "
);


$result = pdo_query("1", 
    "
    delete from statuspost where roomid not in (select distinct roomid from statusroom )
    "
);


$result = pdo_query("1",
 
    "
    delete from roominvite where now() > expires
    "
);

$result = pdo_query("1", 
    "
    delete from statusroom where providerid not in (select providerid from provider where active='Y')
    "
);


$result = pdo_query("1", 
    "
    delete from notification where 
    datediff(now(), notifydate ) > 3
    "
);

$result = pdo_query("1", 
    "
    delete from notifyrequest where status = 'Y' and 
    datediff(now(), requestdate ) > 1
    "
);

$result = pdo_query("1", 
    "
    update contacts set targetproviderid = (select providerid
        from provider 
        where 
        (
        contacts.email = provider.replyemail and contacts.email!=''
        or 
        contacts.handle = provider.handle and contacts.handle!=''
        ) 
        and active='Y'
        limit 1
    
    "
);



$result = pdo_query("1", "
    select distinct chatmessage.chatid 
    from chatmessage
    left join chatmaster on chatmessage.chatid = chatmaster.chatid
    where chatmessage.chatid not in 
    (select chatid from chatmembers where chatmessage.chatid =  chatmembers.chatid 
    and chatmembers.lastread < chatmaster.lastmessage
    )
    and chatmessage.status = 'Y'
    and chatmaster.chatid is not null
    and chatmaster.lifespan > 0 and chatmaster.lifespan is not null
 
");
while( $row = pdo_fetch($result))
{
    //  delete from chatmessage where chatid = $row[chatid]
    pdo_query("1", "
        delete from chatmessage where chatid = $row[chatid] and status = 'Y'
      "
    );
    
}

//Delete from chatmaster where there are no active chats after 2 days
$result = pdo_query("1", "
    delete from chatmaster 
    where (select count(*) 
    from chatmessage
    where chatmaster.chatid = chatmessage.chatid ) =0 and chatmaster.status = 'Y'
    and datediff(curdate(), chatmaster.created ) > 2
");

//Delete from chatmaster where status='N'
$result = pdo_query("1", "
    delete from chatmaster where status='N'
");


$result = pdo_query("1", "
    delete from chatmembers where chatid not in
    (select chatid from chatmaster where chatmembers.chatid = chatmaster.chatid and chatmaster.status='Y')
");

$result = pdo_query("1", "
    update contacts 
    set targetproviderid = (select providerid from provider where provider.active='Y' 
     and provider.handle = contacts.handle )
    where targetproviderid is null and handle!='' and handle!='@'
");

$result = pdo_query("1", "
    delete from keysend where 
    timestampdiff(HOUR, now(), expiration) > 24
    and providerid > 0 and chatid > 0
");



?>