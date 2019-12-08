<?php
session_start();
set_time_limit ( 30 );
require_once("config.php");
require_once("aws.php");


$timestamp = time();



$result = do_mysqli_query("1",

    "update attachments set archive='Y' where sessionid not in ".
    "(select sessionid from msgmain ) "
);


$result = do_mysqli_query("1",

    "select attachfilename, providerid from attachments where archive='Y' "
);

//$path = "c:/inetpub/vhosts/braxmobile.com/braxsecure.com/prod/";
$path = "/var/www/html/$installfolder/upload";
//    c:\\inetpub\\vhosts\\brax1.secureserver.net\\braxsecure\\prod\\upload\\"; // change the path to fit your websites document structure

//echo "Test = $path";

while( $row = do_mysqli_fetch("1",$result))
{
    echo "$fullpath";
    $fullPath = $path."/".$row['attachfilename'];
    if( file_exists( $fullpath ))
    {
        echo "$fullpath";
    }
    
    unlink( $fullPath );
    
}

$result = do_mysqli_query("1",

    "delete from attachments where archive='Y' "
);


$result = do_mysqli_query("1",

    "delete from shares where shareexpire < now() "
);



$result = do_mysqli_query("1",

    "delete from shares where views = 0 and datediff(now(), sharedate ) > 1 "
);



$result = do_mysqli_query("1",

    "
    delete from notification where status='Y' and
    notifydate < curdate()-7
    "
);

$result = do_mysqli_query("1",

    "
    update notification set status = 'Y' where status='N' and
    datediff( curdate(), notifydate ) > 1
    "
);


$result = do_mysqli_query("1",

    "
    delete from statuspost where roomid not in (select distinct roomid from statusroom )
    "
);


$result = do_mysqli_query("1",

    "
    delete from statuspost where roomid not in (select distinct roomid from statusroom )
    "
);


$result = do_mysqli_query("1",

    "
    delete from roominvite where now() > expires
    "
);

$result = do_mysqli_query("1",

    "
    delete from statusroom where providerid not in (select providerid from provider where active='Y')
    "
);


$result = do_mysqli_query("1",

    "
    delete from notification where 
    datediff(now(), notifydate ) > 3
    "
);

$result = do_mysqli_query("1",

    "
    delete from notifyrequest where status = 'Y' and 
    datediff(now(), requestdate ) > 1
    "
);

$result = do_mysqli_query("1",

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



$result = do_mysqli_query("1",
"
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
while( $row = do_mysqli_fetch("1",$result))
{
    //  delete from chatmessage where chatid = $row[chatid]
    do_mysqli_query("1",
      "
        delete from chatmessage where chatid = $row[chatid] and status = 'Y'
      "
    );
    
}

//Delete from chatmaster where there are no active chats after 2 days
$result = do_mysqli_query("1",
    "
    delete from chatmaster 
    where (select count(*) 
    from chatmessage
    where chatmaster.chatid = chatmessage.chatid ) =0 and chatmaster.status = 'Y'
    and datediff(curdate(), chatmaster.created ) > 2
");

//Delete from chatmaster where status='N'
$result = do_mysqli_query("1",
"
    delete from chatmaster where status='N'
");


$result = do_mysqli_query("1",
"
    delete from chatmembers where chatid not in
    (select chatid from chatmaster where chatmembers.chatid = chatmaster.chatid and chatmaster.status='Y')
");

$result = do_mysqli_query("1",
"
    update contacts 
    set targetproviderid = (select providerid from provider where provider.active='Y' 
     and provider.handle = contacts.handle )
    where targetproviderid is null and handle!='' and handle!='@'
");

$result = do_mysqli_query("1",
"
    delete from keysend where 
    timestampdiff(HOUR, now(), expiration) > 24
    and providerid > 0 and chatid > 0
");

$result = do_mysqli_query("1",
"
    delete FROM braxproduction.activitylog where datediff(now(),xacdate) > 30
");

$result = do_mysqli_query("1",
"
    delete FROM braxproduction.notifyrequest where datediff(now(),requestdate) > 3 and status='Y'
");

$result = do_mysqli_query("1",
"
    delete from statuspost where articleid is not null and providerid = 0 and articleid > 0  and
    datediff(now(), postdate > 100 
");

//Set Delinquent Account Status based on Expired Plan
$result = do_mysqli_query("1",
"
    update provider 
	set accountstatus='D' where providerid in (
            select msgplan.providerid 
            from msgplan
            where 
            timestampdiff( DAY, now(), msgplan.dateend ) < 0 
            and msgplan.active!='F' 
            )
            and active='Y' and providerid > 0;
            select * from provider where accountstatus='D'
");


$result = do_mysqli_query("1",
"
    delete from notifytokens where datediff(now(), registered ) > 120 and providerid > 0
");
        
$result = do_mysqli_query("1",
"
    delete from groupmembers where groupid in (
    select groups.groupid from groups
    left join provider on provider.providerid = groups.creator
    where provider.active='N' ) and providerid > 0 and groupid > 0
");

$result = do_mysqli_query("1",
"
    delete from groups where creator not in (
    select providerid from provider where active='Y'
    ) and creator > 0 and groupid > 0
");

$result = do_mysqli_query("1",
"
    delete from sponsor where creator not in (
    select providerid from provider where active='Y'
    ) and creator > 0 and sponsor !=''
");


$result = do_mysqli_query("1",
"
    select roomhandle.roomid
    from roomhandle 
    left join roominfo on roomhandle.roomid = roominfo.roomid 
    left join statusroom on roomhandle.roomid = statusroom.roomid and statusroom.owner = statusroom.providerid
    left join provider on provider.providerid = statusroom.owner
    where roominfo.external='Y'  
    and curdate() > (select dateend from msgplan where statusroom.owner = msgplan.providerid order by dateend desc limit 1)
");
while( $row = do_mysqli_fetch("1",$result))
{
    //  delete from chatmessage where chatid = $row[chatid]
    do_mysqli_query("1",
      "
        update roominfo set external = 'N' where roomid = $row[roomid]
      "
    );
    
}

$result = do_mysqli_query("1",

    "
    delete from statuspost where datediff(now(), postdate )> 30 and providerid = 0 and articleid > 0       
    "
);
$result = do_mysqli_query("1",

    "
    delete from statusreads where datediff(now(), actiontime )> 30 and providerid = 0        
    "
);


$result = do_mysqli_query("1",
"
    select filename from iotphotos where date_diff(now(), createdate) > 2 
    and providerid =
    


    from roomhandle 
    left join roominfo on roomhandle.roomid = roominfo.roomid 
    left join statusroom on roomhandle.roomid = statusroom.roomid and statusroom.owner = statusroom.providerid
    left join provider on provider.providerid = statusroom.owner
    where roominfo.external='Y'  
    and curdate() > (select dateend from msgplan where statusroom.owner = msgplan.providerid order by dateend desc limit 1)
");
while( $row = do_mysqli_fetch("1",$result))
{
    //  delete from chatmessage where chatid = $row[chatid]
    do_mysqli_query("1",
      "
        update roominfo set external = 'N' where roomid = $row[roomid]
      "
    );
    
}

$result = do_mysqli_query("1",
"
    select filename from iotphotos where datediff(now(), createdate) > 1 and status='Y'
");
while( $row = do_mysqli_fetch("1",$result))
{
    echo "Deleting ".$row['filename']."<br>";
    //  delete from chatmessage where chatid = $row[chatid]
    deleteAWSObject( $row['filename'] );
    $result2 = do_mysqli_query("1",
    "
        update iotphotos set status='N' where filename='$row[filename]'    
    ");
    
}
$result = do_mysqli_query("1",
"
    delete from iotdata where datediff(now(), msgdate) > 1 
");



?>