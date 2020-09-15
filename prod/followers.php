<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    $providerid = @tvalidator("ID",$_POST['providerid']);
    $find = rtrim(@tvalidator("PURIFY",$_POST['find']));
    
    

    //$providerid = 690001027;
    //$chatid = 1217;

    /*****************************
     * 
     * 
     *    MAIN
     * 
     */

        
    $list = "
        <div class='gridnoborder suspendchatrefresh' style='padding:0px;margin:0;background-color:$global_background'>
            ";


    $list .= Title();
    $list .="   
            <div style='padding-left:30px;padding-right:30px;padding-top:0px;padding-bottom:50px;margin:0px;text-align:left;background-color:$global_background;color:$global_textcolor'>
            <br>
        ";
    
    $list .= MemberList($providerid, $find);    

    $list .="   
            </div>
        </div>";

    /* $mode is configured/reset in Buttons() */
    
    echo $list;
    exit();


    
    
    
    

    function Title()
    {
        global $appname;
        global $global_titlebar_color;
        global $icon_braxpeople2;
        global $chatid;
        global $icon_braxpeople2;
        global $providerid;
        
        $result = pdo_query("1","select providername, profileroomid from provider where providerid = $providerid");
        if($row = pdo_fetch($result)){
            $providername = $row['providername'];
            $profileroomid = $row['profileroomid'];
        }
        
        
        $backgroundcolor = $global_titlebar_color;
        $list = "
                <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                    <img class='icon20 feed' data-providerid='$providerid' data-name='$providername' data-roomid='$profileroomid' data-caller='none' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                        style='' />
                    &nbsp;
                    <span style='opacity:.5'>
                    $icon_braxpeople2
                    </span>
                    <span class='pagetitle2a' style='color:white'>Followers</span> 
                </div>
            ";
        return $list;
        
    }

    
    function MemberList($providerid, $find )
    {
        global $appname;
        global $rootserver;
        global $global_separator_color;
        global $icon_darkmode;
        
        $timezoneoffset = $_SESSION['timezoneoffset'];
        
        $list = "
            <span class='meetuppublicshow' style='display:none;background-color:white;color:black'>
                <div class='pagetitle2a' style='padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:5px'>
                    <b>CHAT MEMBERS</b>
                    <br>
                                
                    <input class='showhidden inputline dataentry mainfont chatmemberfind' id='chatmemberfind' name='chatmemberfind' type='text' size=20 value='$find'              
                        placeholder='Find Name or @handle'
                        style='max-width:200px;background-color:transparent;padding-left:5px;'/>
                        <img class='showhiddenarea icon20 meetuplist' data-mode='P1' src='../img/Arrow-Right-in-Circle_120px.png' title='Start Search'
                        style='display:none;top:8px' >
                </div>
            </span>
            ";
        $order = "order by followers.level asc, provider.providername asc ";
        if($_SESSION['pid']==$providerid){
            $order = "order by followers.followdate desc";
        }
        
        
        $result = pdo_query("1","
        select provider.providername, provider.providerid as otherid,  
        provider.replyemail as otheremail, provider.avatarurl, 
        provider.handle, 
        provider.profileroomid, 
        provider.techsupport,
        provider.publishprofile, followers.level,
        DATE_FORMAT(followers.followdate, '%b %d/%y') as followed        
        from followers
        left join provider on provider.providerid = followers.followerid
        where followers.providerid = $providerid 
        and provider.active = 'Y' and termsofuse is not null
        $order
        limit 1000
            
            
                ");
        $count = 0;
        $joined = "";
        $otherid = "";
        $techsupport = "";
        while($row = pdo_fetch($result)){
            if($row['techsupport']=='Y'){
                $techsupport = 'Y';
            }
            if($row['otherid']!=$providerid && $count < 3){
                $otherid = $row['otherid'];
            }
            
            if($count == 0){

            }
            $count++;
            $id = $row['handle'];
            if($id == ''){
                $id = $row['otheremail'];
            }

             $avatar = $row['avatarurl'];
             if($avatar == "$rootserver/img/faceless.png" || $avatar == ''){
                 $avatar = "$rootserver/img/newbie2.jpg";
             }
             
            $profileaction = 'feed';
            if(intval($row['profileroomid'])==0){
                $profileaction = 'userview';
            }

             if($row['level']=='I' && $_SESSION['pid']!=$providerid){
                 $avatar = "$rootserver/img/newbie2.jpg";
                 $row['otherid'] = '';
                 $row['providername']='Incognito';
                 $id='';
                 $profileaction = '';
             }
            
            $deleteaction = "";
                $deleteopacity = "opacity:0.3;";
            if($row['otherid']==$providerid){
                $deleteaction = "chatdeleteparty";
                $deleteopacity = "cursor:pointer;opacity:1.0;";
            }
            $shadow = "gridstdborder shadow";
            if($icon_darkmode){
                $shadow = "";
            }
            
            
            $list .= "
                <div class='rounded stdlistbox rounded $shadow' 
                    style='display:inline-block;vertical-align:top;text-align:left;background-color:white;margin-bottom:10px;
                    word-wrap:break-word;overflow:hidden;'>
                            <div class='gridnoborder $profileaction' style='cursor:pointer;color:black;padding:15px;;overflow:hidden'
                             data-providerid='$row[otherid]' data-name='$row[providername]'    
                             data-roomid='$row[profileroomid]'
                             data-caller='leave'
                             data-mode ='S' data-title='' data-passkey64='' 
                             >
                                <div class='circular2' style='overflow:hidden;background-color:#a3a3a3;max-height:90%'  title='User Photo'>
                                    <img class='' src='$avatar' style='height:auto;width:100%;'/>
                                </div>
                               $row[providername]<br><span class='smalltext' style='color:gray'>$id 
                                   <br>$row[followed]
                                </span>
                            </div>
                </div>  
                ";
        }    
        
        return $list;
    }    
