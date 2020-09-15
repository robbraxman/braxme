<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");
require_once("room.inc.php");


    $providerid = $_SESSION['pid'];

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("PURIFY",$_POST['roomid']);
    $room = htmlentities(stripslashes(@tvalidator("PURIFY",$_POST['room'])),ENT_COMPAT);
    $postid = @tvalidator("PURIFY",$_POST['postid']);
    $articleid = @tvalidator("PURIFY",$_POST['articleid']);
    
    
    if( $mode == 'P')
    {
        if( intval($roomid) <2 ){
            exit();
        }
        SharePost( $providerid, $articleid, $roomid, $room );
        exit();
    }
    if($mode !=''){
        exit();
    }
        
    

    echo "
        <div  style='background-color:$global_background'>
        <div style='padding:10px'>
        <span class='pagetitle2a' style='color:$global_textcolor'><b>Share Article to a Room</b></span> 
        <br><br>
                <div class='mainfont feed showtop tapped' 
                    id='feed' data-roomid='$roomid' style='display:inline'>
                            <img class='icon20' src='$iconsource_braxarrowleft_common' style='padding-top:0;padding-right:2px;padding-bottom:0px;' />
                            Back&nbsp;&nbsp;
                </div>
        <br>
        <div class='' style='text-align:center;background-color:$global_background'>
        ";
    
    

    
    /*************************************************************
     * 
     * 
     * 
     * 
     *  ALPHABETICAL LIST
     * 
     * 
     * 
     */
$time2 = microtime(true);
    
    
    
    $result = pdo_query("1","
        select 
        distinct roominfo.roomid, 
        roominfo.room, owner,
        lastaccess,
        ( select 'Y' from publicrooms where 
            statusroom.roomid = publicrooms.roomid 
        ) as publicadmin,
        roomhandle.handle,
        roomhandle.public,
        ( select case  
            when roomhandle.category is null then 'Private' 
            else roomhandle.category end
        ) as category,
        roominfo.organization,
        ( select case 
            when roominfo.organization is null then ''
            when roominfo.organization !='' then 'Y' 
            else '' end
        ) as org,
        datediff( now(), 
        roominfo.lastactive) as active
        from statusroom
        left join roomhandle on roomhandle.roomid = statusroom.roomid 
        left join roominfo on roominfo.roomid = statusroom.roomid 
        where 
        ( statusroom.providerid=$providerid or 
          statusroom.roomid in (select roomid from publicrooms) 
        )
        and roominfo.profileflag !='Y'
        order by roominfo.room asc
    ");

    
    
    $lastroomid = '';
    $lastcategory = 'Unspecified';
    while($row = pdo_fetch($result)){
        
        $activecolor = 'gray';
        $public = '';
        if( $row['publicadmin']=='Y') {
            $public = "<span style='color:firebrick'> (Public Room)</span>";
            $activecolor = 'transparent';
        }
        if( $row['lastaccess']!=''){
            $activecolor = 'steelblue';
        }
        if( $row['roomid']==$lastroomid)
            continue;
        
        if( $row['org']=='Y' ){
            $row['category']="$row[organization]";
        }
        if( $row['org']!='Y' && $row['category']=='' ){
            $row['category']='Private';
        }
        
        if(rtrim($row['organization'])==''){
            $row['organization']=$row['category'];
        }
        $dot = "";
        if("$row[owner]"=="$providerid"){
            $dot = "<img src='$rootserver/img/dot.png' style='height:6px;width:auto;position:relative;top:0px' />";
        }

        $active = "";
        if($row['active']!= '' && $row['active'] < 8){
            //$active = "<img src='../img/checkbox-blue-128.png' style='height:15px' />";
        }
        
        echo "
            <div class='roomsharepost tapped2' data-roomid='$row[roomid]' 
              data-articleid='$articleid'
              data-mode='P'
              style='display:inline-block;cursor:pointer;border:0px solid lightgray;
              background-color:$global_background;
              width:250px;min-width:25%;padding-left:10px;padding-right:10px;padding-bottom:0px;margin:0'>
                  <div class=pagetitle3 
                  style='display:inline-block;color:$global_activetextcolor;
                  '>
                      $row[room] $dot 
                  </div>
            </div>
             ";
        
    }
    

    
    
echo "<br><br><br>
    </div></div>
    ";    



    
    
?>
