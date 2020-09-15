<?php
session_start();

require("validsession.inc.php");
require_once("config-pdo.php");
require_once("room.inc.php");
require_once("groupmanage.inc.php");
require_once("internationalization.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_SESSION['pid']);
    
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $groupid = stripslashes(@tvalidator("PURIFY",$_POST['groupid']));
    $roomid = stripslashes(@tvalidator("PURIFY",$_POST['roomid']));
    $friendproviderid = @tvalidator("PURIFY",$_POST['friendproviderid']);
    $newgroupname = @tvalidator("PURIFY",stripslashes($_POST['groupname']));
    $filter = @tvalidator("PURIFY",stripslashes($_POST['filter']));
    
    $groupname = @tvalidator("PURIFY",stripslashes($_POST['groupname']));
    $groupnameForSql = @tvalidator("PURIFY",stripslashes($groupname));
    $groupnameHtml = htmlentities(stripslashes($groupname),ENT_QUOTES);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $organization = stripslashes(@tvalidator("PURIFY",$_POST['organization']));
    $groupdescription = stripslashes(@tvalidator("PURIFY",$_POST['groupdesc']));
    $groupdesc = htmlentities(stripslashes($groupdescription),ENT_QUOTES);
    $photourl = stripslashes(@tvalidator("PURIFY",$_POST['photourl']));

    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $help = "<img class='helpinfo' src='../img/help-gray-128.png' 
            style='height:20px;width:auto;position:relative;top:3px;padding-left:10px;padding-right:10px;cursor:pointer' 
            data-help='#RoomHashTag<br><br>".
            "Unique alphanumeric name starting with #. No spaces. Case insensitive.<br><br>If a #RoomHashTag is supplied, users can use it to join the groupname on their own. If not provided, only individual invitation is allowed.' />";
    $braxsocial = "<img class='settingsbutton mainbutton icon20' src='../img/Arrow-Left-in-Circle-White_120px.png' style='padding-top:0;padding-left:10px;padding-right:2px;padding-bottom:0px;' />";
   //$braxsocial = "<img src='../img/braxgroupname-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    /***********************************************
     * 
     * 
     * 
     * 
     *   MODES
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    if( $mode == 'DR'){
        DeleteGroup($groupid);
        //echo "<div class='tipbubble groupsetup mainbutton pagetitle3' style='padding:10px;cursor:pointer;background-color:#72b6e4;color:white'>Community and all members and posts have been deleted. Click to continue.</div>";
        $mode = '';
        $groupid = 0;
    }
    
    if( $mode == 'D'){
        DeleteGroupMember($groupid, $providerid, $friendproviderid);
            $mode = 'V';
            $groupid = 0;
        
    }
    if( $mode == 'M'){ //Adding Member
        if(!AddGroupMember($providerid, $friendproviderid, $groupid )){
            $groupname = "";
            $groupid = "";
        }
        $mode = "V";
    }
    if( $mode == 'A'){
    
        
        $groupname = $newgroupname;
        if( $groupname!=''){
        
            $groupnameForSql = tvalidator("PURIFY",stripslashes($newgroupname));
            $groupnameHtml = htmlentities(stripslashes($newgroupname),ENT_QUOTES);

            $groupid = 0;
            //Find if the Room already exists
            $result = pdo_query("1","
                select groupid from groups where groupname='$groupnameForSql' and creator=$providerid
                ");
            if( $row = pdo_fetch($result)){
            
                $groupid = intval($row['groupid']);
            }
        }            
        if($groupid == 0){
        
            $groupid = GetNewGroupID( "GROUP", "ID" );
        }
        
        if( $groupname!='' && $groupid > 0){
        
            
            pdo_query("1","
                insert into groups ( groupid, groupname, creator, createdate ) values
                ( $groupid, '$groupnameForSql',$providerid, now() )
                ");
            
            $error = SaveGroup($groupid, $groupname, $groupdesc, $organization, $photourl, $roomid );
            $mode = '';
            $groupid = 0;
        } else {
            $groupname = '';
            $groupid = 0;
            $mode = '';
        }
    }
    
    if( $mode == 'R'){

 
        if( $newgroupname !== '' && intval($groupid)>0){
        
            
            $groupname = stripslashes($newgroupname);
            $groupnameForSql = tvalidator("PURIFY",stripslashes($newgroupname));
            $groupnameHtml = htmlentities(stripslashes($newgroupname),ENT_QUOTES);
            
            $error = SaveGroup($groupid, $groupname, $groupdesc, $organization, $photourl, $roomid );
            if( $error!=''){
            
                $mode = 'E';
            } else {
                $mode = '';
                $groupid = 0;
            }
        }
        
        
    }

    /***********************************************
     * 
     * 
     * 
     * 
     *     Create a New Room
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    
    
    /***********************************************
     * 
     * 
     * 
     * 
     *   Get Room Data 
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    
    $groupdata = GetGroupData($groupid, $providerid);
    
    /***********************************************
     * 
     * 
     * 
     * 
     *   Main Room Setup Header 
     * 
     * 
     * 
     * 
     * 
     ************************************************/
    
    
    if($mode == 'E' ){
    
        echo "  
                <span class='groupnamecontent'>
                    <div class='gridnoborder' 
                        data-groupname='All' data-groupid='All'                
                        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        $braxsocial
                            &nbsp;&nbsp;
                        $icon_braxroom2
                        <span class='pagetitle2a' style='color:white'>$menu_communitylists</span> 
                    </div>
                </span>
                    <!--
                    &nbsp;&nbsp;
                    <div class='mainfont groupmanage tapped'  style='color:black'
                        id='feed' data-groupid='$groupid'  data-mode=''>
                            $braxsocial
                            Back&nbsp;&nbsp;
                    </div>
                    -->

           ";
    } else
    if($mode == 'V'  ){
    
        echo "  
                <span class='groupnamecontent'>
                    <div class='gridnoborder' 
                        data-groupname='All' data-groupid='All'                
                        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        $braxsocial
                            &nbsp;&nbsp;
                        $icon_braxroom2
                        <span class='pagetitle2a' style='color:white'>$menu_communitylists</span> 
                    </div>
                </span>
                    <!--
                    &nbsp;&nbsp;
                    <div class='mainfont tapped'  style='color:black'
                        id='roommanage' data-groupid='' data-mode=''>
                            $braxsocial
                            Back&nbsp;&nbsp;
                    </div>
                    -->

           ";
    } 
    else {
    
        echo "  
                <span class='groupnamecontent'>
                    <div class='gridnoborder' 
                        data-groupname='All' data-groupid='All'                
                        style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                        $braxsocial
                            &nbsp;&nbsp;
                        $icon_braxroom2
                        <span class='pagetitle2a' style='color:white'>Manage Group Lists</span> 
                    </div>
                </span>
                    

           ";
    } 
     /***********************************************
     * 
     * 
     * 
     * 
     *   Room Edit Mode
     * 
     * 
     * 
     * 
     * 
     ************************************************/

    
        if($mode == 'E'){
            $groupmode = 'R';
        } else {
            $groupmode = 'A';
        }
     /***********************************************
     * 
     * 
     * 
     * 
     *   Add/Edit Room Window
     * 
     * 
     * 
     * 
     * 
     ************************************************/
        
        
        if( $mode == 'N' || $mode == 'E'){
        
        
            $groupedit = 
                    GroupEdit($mode, $groupid, $groupdata->groupHtml,$groupdata->groupdesc, 
                            $groupdata->organization, 
                            $groupdata->photourl, $groupmode, $groupdata->roomid
                    );

            echo $groupedit;
            exit();
        }
        
        /*
         * 
         * 
         *    MAIN WINDOW with ROOM LIST
         * 
         * 
         * 
         */
        
        
    
 
    $select = "";
    if($mode == ''){
        $select = ConstructGroupSelect($providerid, $groupid);
    }
    if($select != ''){
    
        echo "
                <div style='color:$global_textcolor;background-color:$global_background;width:90%text-align:center;margin:auto;'>
                    <input id='creategroupname' name='groupname' type='hidden' value=''>

                    <center>
                    <br>
                    <div class=pagetitle2  style='color:$global_textcolor'>
                        Select Group List to Manage
                    </div>
                    <br>    
                    <div class=formobile></div>
                    <br>
                    <div class='groupmanage tapped'
                         id='groupnameedit' data-groupname='' data-groupid='' data-mode='N'
                         style='color:$global_activetextcolor;cursor:pointer;display:inline'>
                         Create a New Group List
                    </div>
                    <br><br>
                    $select
                    </center>

                    <center>
                    <br>
                    </center>
                </div>
                ";
    }

    if(intval($groupid)==0){
        echo "<div style='color:$global_textcolor;background-color:$global_background;text-align:center;margin:auto'><br><br>";
        $result = pdo_query("1","select groupname from groups where groupid in (select groupid from groupmembers where providerid = $providerid ) ");
        while($row = pdo_fetch($result)){
            echo "Member of $row[groupname]<br>";
        }
        echo "</div><br><br><br><br>";
    
        exit();
    }
    $groupname1 = htmlentities($groupname,ENT_QUOTES);

    
    $groupname = stripslashes($groupdata->groupname);
    echo "
                        <br>
                        <div class='pagetitle3' style='text-align:center;margin:auto' style='color:black'> 
                            <span class='pagetitle2a' style='color:$global_textcolor'>
                            <b>$groupname</b>
                            </span>
                            <br><br>
                            <div class='groupmanage' data-groupid='$groupid' data-mode='E' style='display:inline;color:$global_activetextcolor;cursor:pointer'  >Edit</div>
                            &nbsp;&nbsp;&nbsp;
                            <div class='groupmanage' data-groupid='$groupid' data-mode='DR'  style='display:inline;color:$global_activetextcolor;cursor:pointer'>Delete</div>
                            <br><br>
                        </div>
                        <div style='text-align:center'>
                        ";

    
    echo "
                        <hr>
                        <br>
                        <div style='margin:auto;min-width:300px;text-align:center'>
                            <span class='pagetitle2a' style='color:$global_textcolor'>
                                <b>Add Members - Search Contact List</b>
                            </span>
                        </div>
                        <table style='margin:auto;text-align:left;'  style='color:$global_textcolor'>
                        <tr style='min-width:70%'>
                        <td>
                            <input type='text' class='friendsearchfilter' placeholder='Contact Search' size=20 style='width:150px;min-width:50%' />
                            <img class='friendsearch icon20' src='$iconsource_braxarrowright_common' data-groupid='$groupid' data-caller='groupmanage'  />
                                <br>
                            <div class='smalltext2'>Leave blank to search newest contacts</div>
                        </td>
                        <td>
                        </td>
                        
                        </tr>
                        </table>
                        
                        <br>
                        <div class='friendsource' style='text-align:center'></div>
            ";
    $memberlist = "";
    if($mode == 'V'){
        
        echo "<br><hr><div class='pagetitle2' style='color:$global_textcolor;padding:20px'>Group Members</div>";
        echo "<input id='groupmanagefilter' type='text' value='$filter' size=30 placeholder='Search Members' /> ";
        echo "<img class='groupmanage icon25'  src='$iconsource_braxrefresh_common' style='' data-groupid='$groupid' data-mode='V' /><br> ";
        echo "<br>";
    
        $memberlist = GroupMemberList($providerid, $groupid, $filter);
    }
    
    if($IsOwner){
    echo "<br><br><br><div class='divbutton6 mainfont groupnameedit' data-mode='DR' data-groupid='$groupid' >
                Delete Group and All Members
                <img class='icon15' src='../img/arrowhead-right-white-01-128.png' style='top:3px;' />
                </div>";
    }
    
    echo "      </div>";
    echo "<br><br><br><span class='smalltext2' style='color:$global_textcolor'>Groupid: 6788020$groupid</span>";
    



?>