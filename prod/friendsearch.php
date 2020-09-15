<?php
session_start();
require("validsession.inc.php");
require_once("config.php");

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = @mysql_safe_string($_POST['providerid']);
    $roomid = @mysql_safe_string($_POST['roomid']);
    $groupid = @mysql_safe_string($_POST['groupid']);
    $sponsor = @mysql_safe_string($_POST['sponsor']);
    $filter = @mysql_safe_string($_POST['filter']);
    $caller = @mysql_safe_string($_POST['caller']);
    $braxsocial = "<img src='../img/braxroom.png' style='position:relative;top:5px;height:20px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    if($filter !==''){
        $orderby = "order by provider.providername asc limit 50";
    } else
    {
        $orderby = "order by provider.createdate desc, provider.providername asc limit 20";
    }
    
 

    $search = "
            

        and (provider.providername like '$filter%' or provider.handle like '%$filter%' )
        and
        ( 
            ( (provider.handle = '$filter' or provider.handle = '@$filter') and trim('$filter')!='')

            or
            (provider.publish='Y' ) 

            or
            (trim('$filter') != '' and providerid in 

            (select providerid from groupmembers where groupid in

             (select groupid from groupmembers where providerid = $providerid )

                and groupmembers.providerid = provider.providerid
                )
            )
            or
            ( trim('$filter') != '' and providerid in 
               (select targetproviderid from contacts 
               where contacts.providerid = $providerid)
            )

        ) 
    "; 
    if(strlen($filter < 4) && strstr($filter,"@")==false){
        //$search = " and provider.providername like '$filter%' ";
    }
    
    if( strstr($filter,"@")!==false){
        $search = " and provider.handle = trim('$filter')  ";
    }
    
    $notincluded = "and provider.providerid != $providerid";
    if($caller == "sponsorlist"){
        $notincluded = "";
    }
    
    $result = do_mysqli_query("1",
       "
        select distinct 
                provider.providerid, provider.providername, 
                provider.replyemail, provider.handle, provider.avatarurl, 
		provider.providerid, provider.companyname, 
                date_format(provider.createdate,'%m/%d/%Y') as createdate
        from provider where active='Y' 
        $notincluded
            
        $search
        $orderby
        "
    );
    
    
    echo "<div class='pagetitle3' style='padding:20px;color:$global_textcolor'><b>Contacts</b></div>";
        
    while($row = do_mysqli_fetch("1",$result)){
    
        if($row['avatarurl'] == "$rootserver/img/faceless.png"){
            $row['avatarurl'] = "$rootserver/img/egg-blue.png";
        }
        if($row['handle']!=''){
            $row['replyemail']=$row['handle'];
        }
        
        $callclass = 'friends';
        if($caller=='groupmanage'){
            $callclass = 'groupmanage';
        }
        if($caller=='caseselect'){
            $callclass = 'caseselect';
        }
        if($caller=='sponsorlist'){
            $callclass = 'sponsorlist';
        }
        
        echo "          
                <div class='gridstdborder rounded' style='display:inline-block;width:170px;height:170px;text-align:center;padding-top:2px;padding-bottom:20px;
                    background-color:white;color:black;cursor:pointer;margin-bottom:10px;overflow:hidden'>
                    <div style='background-color:whitesmoke;height:110px' >
                    <img class=circular src='$row[avatarurl]' style='display:inline;height:100px;width:auto;max-width:100px' />

                        <img src='../img/add-gray-128.png' 
                            class='$callclass'
                            style='cursor:pointer;height:20px;background-color:transparent' 
                            id='friends'  
                            data-providerid='$row[providerid]' 
                            data-groupid='$groupid' 
                            data-sponsor='$sponsor'
                            data-roomid='$roomid' 
                            data-mode='M' 
                            data-caller='$caller' />

                    </div>
                    <div class='smalltext2' style='color:black;height:60px;width:180px;margin-bottom:2px;margin-left:5px;margin-right:5px;overflow:hidden'>
                        $row[providername]
                        <br>
                        $row[replyemail]
                        <br>
                        Joined $row[createdate]
                    </div>
                </div>

                ";
        
        
        
    }
    
    
?>