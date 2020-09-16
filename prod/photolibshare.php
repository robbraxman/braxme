<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("password.inc.php");
require_once("aws.php");
require_once("internationalization.php");


    //savelastfunc ( "P" );

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_SESSION['pid']);
    $userid = tvalidator("PURIFY",$_POST['userid']);
    
    SaveLastFunction($providerid,"V", "$userid"); //View Shared Photos
    
    $mode = tvalidator("PURIFY",$_POST['mode']);
    if($mode == 'F'){
        pdo_query("1","update provider set photosharelevel = 'F' where providerid = ? ",array($providerid));
    }
    if($mode == 'A'){
        pdo_query("1","update provider set photosharelevel = 'A' where providerid = ? ",array($providerid));
    }
    
    
    //Common Level to all people
    $result = pdo_query("1",
    "
        select providername, avatarurl, profileroomid,handle,
        (select level from friends where friends.providerid = provider.providerid and friendid = ?) as level    
        from provider where providerid = ?
    ",array($providerid,$userid));
    $sharelevel = 'A';
    $providername = '';
    $profileroomid = "";
    $avatarurl = '';
    $handle = '';
    if($row = pdo_fetch($result)){
        $providername = $row['providername'];
        $avatarurl = $row['avatarurl'];
        $profileroomid = $row['profileroomid'];
        $handle = $row['handle'];
        $sharelevel = $row['level'];
        if($row['level'] == ''){
            $sharelevel = "'A'";
        }
        if($row['level'] == 'FAMILY'){
            $sharelevel = "'C','F','A'";
        }
        if($row['level'] == 'FRIEND'){
            $sharelevel = "'F','A'";
        }
    }
    if($providerid == $userid){
        $sharelevel = "'C','F','A'";
    }
        
    //Check for Friend Level display    
    /*
    if($sharelevel !='A'){
        $result = pdo_query("1","select friendid from friends where (providerid = $userid and friendid = $providerid) or '$userid'='$providerid' ");
        if(!$row = pdo_fetch($result)){
            echo "<div class='tilebutton mainfont' style='padding:20px;cursor:pointer;color:$global_activetextcolor'>No Photos Shared</div> ";
            exit();
        }
    }
     * 
     */
    
    $showfilename = '';
    if(isset($_POST['filename'])){
    
        $showfilename = tvalidator("PURIFY",$_POST['filename']);
    }
    
    
    //ForSQL
    $selectedalbum = "";
    $selectedalbumHtml = "";
    $selectedalbumSql = "";
    $origalbum = "";
    if(isset($_POST['album'])){
    
        $selectedalbum = DeconvertHTML(tvalidator("PURIFY",$_POST['album']));
        $selectedalbumHtml = ConvertHTML($selectedalbum);
        $selectedalbumSql = tvalidator("PURIFY",$selectedalbum);
        $origalbum = DeconvertHTML(tvalidator("PURIFY",$_POST['origalbum']));
        //echo $selectedalbum;
    }
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //*************************************************************
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //*************************************************************


    //*************************************************************
    //*************************************************************
    //*************************************************************

    $result2 = pdo_query("1",
        "
            select count(*) as count
            from photolib where 
            providerid = ?
            and  album in (select album from photolibshare where providerid = ? and sharetype in (?)
            )
            and (album = ? or ? = '')
        ",array($userid,$userid,$sharelevel,$selectedalbumSql,$selectedalbumSql));
         
    
    $row2 = pdo_fetch($result);
    $total = $row2['count'];
    
    
    $page = 1;
    if(isset($_POST['page'])){
        $page = intval(tvalidator("PURIFY",$_POST['page']));
    }
    if( $page == 0){
        $page = 1;
    }
    $pagenext = intval($page)+1;
    $pageprev = intval($page)-1;
    if( intval($pageprev)< 1 ){
        $pageprev = 1;
    }
    
    require_once("sizingphoto.php");    
    
    $pagestart = ($page-1) * $max;
    $pagestartdisplay = $pagestart+1;
    $pageenddisplay = $pagestart+$max;
    $pagedisplay = "$pagestartdisplay - $pageenddisplay /$total";
    if($pageenddisplay > $total){
    
        $pagedisplay = "$pagestartdisplay - $total";
        if( $pagestartdisplay > $total ){
        
            $pagedisplay = "$pagestartdisplay - End";
        }
    }
    

    
    
    if( $showfilename!=''){
    
        $result = pdo_query("1",
        "
            select folder, title, filename, comment, album, alias, aws_url, public, owner,
            datediff(aws_expire,now()) as expire, 
            (select providername from provider where provider.providerid = photolib.owner) as ownername
            from photolib where filename =? 
        ",array($showfilename));
        $row = pdo_fetch($result);
        $folder = $row['folder'];
        $title = htmlentities($row['title'], ENT_QUOTES);
        $comment = str_replace("<br />","\r\n",html_entity_decode($row['comment']));
        $filename = $row['filename'];
        $showfilenameUrl = $row['aws_url'];
        $ownername = $row['ownername'];
        $resizeshare = "$rootserver/$installfolder/showimg.php?a=$row[alias]";
        
        $showfilenameUrl = getAWSObjectUrlShortTerm($row['filename']);
        
        
        $alias = $row['alias'];
        $album = ConvertHTML($row['album']);
        $public = $row['public'];
        $owner = $row['owner'];

    
    }
    
    
        
        
    $braxsocial = "<img class='icon20' src='../img/brax-photo-round-white-128.png' style='top:3px;padding-left:10px;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    $safe = "";// "<img src='../img/safe-orange-128.png' title='No Encryption - Photos are Not Accessible without Your Share - Protected by SSL' style='height:25px;width:auto;padding-top:0;padding-right:2px;display:inline;padding-bottom:0px;float:right' />";
    $braxinfo = "";//<img class='info_photo' src='../img/braxinfo.png' style='float:right;cursor:pointer;position:relative;top:3px;height:25px;width:auto;padding-left:20px;padding-top:5px;padding-right:10px;padding-bottom:0px;' />";
          
    /****************************************************
     * 
     *  PHOTO LIB TOP AREA
     * 
     *****************************************************/
    $action = "feed";
    if(intval($_SESSION['profileroomid'])==0){
        $action = "userview";
    }
    
    
    
    echo "
        <div class='photoalbumarea' style='
        background-color:$global_background;
        color:$global_textcolor;position:relative;
        '>
            <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:10px;padding-right:20px;padding-bottom:3px;margin:0px;' >
                &nbsp;
                <span style='opacity:.5'>
                $icon_braxphoto2
                </span>    
                <span class='pagetitle2a' style='color:white'>$providername - $menu_photos</span> 
            </div>
            
        </div>
            ";
    
    echo 
        "<table>
            <tr>
                <td>
                            <div class='circular' style='height:30px;width:30px;overflow:hidden;background-color:white;margin:20px'>
                            <img class='feed' src='$avatarurl' style='cursor:pointer;min-height:100%;max-width:100%'
                                data-providerid='$userid' data-name='$providername'    
                                data-roomid='$profileroomid' data-caller='none'
                                data-profile='Y'
                                    
                                data-mode ='S' data-passkey64=''
                             />
                             </div>
                </td>
                <td class='pagetitle2' style='color:$global_textcolor'>
                    $handle
                </td>
            </tr>
        </table>
        ";
    
    if($total == 0 && $page == 1 && $selectedalbum == '' ){
        if($providerid == $userid){
        echo
                 "
                    <div class='pagetitle3' 
                        style='position:relative;padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            To share photos, set the sharing permission for each of your albums in My Photos.
                            You can share to all, or with people you designate as friend or family.
                        </div>
                        <br>
                    </div>
                    <br><br><br>
                    
                ";
        }
        if($providerid != $userid){
        echo
                 "
                    <div class='pagetitle3' 
                        style='position:relative;padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                        <div class='circular3' style=';overflow:hidden;margin:auto'>
                            <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                        </div>
                        <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                            This user has not shared any photos that you can view.
                        </div>
                        <br>
                    </div>
                    <br><br><br>
                    
                ";
        }
        exit();
    }


    //*************************************************************
    //*************************************************************
    //*************************************************************
    echo "
        <div class='panelhost' style='display:block;position:relative;background-color:$global_background;width:100%;margin:0;padding:0'>";

    /****************************************************
     * 
     *  PHOTO SELECT AREA
     * 
     *****************************************************/
    

  
    if( $showfilename !== "" ){
    
        //Photo Display
        /*
         * *******************************
         */

        echo "
            <table class='gridnoborder' style='display:block;position:relative;padding:0;border:0;margin:0;width:100%'>
            <tr style='position:relative;background-color:$global_background;max-width:100%;padding:0;margin:0'>
                <td style='position:relative;background-color:white;max-width:100%;padding:0;margin:0'>


                    <img class='gridnoborder photolibshare tapped2' src='$showfilenameUrl' 
                        data-userid='$userid'
                        title='Tap on photo to return to album'
                        style='z-index:1;cursor:pointer;position:absolute;top;0;left:0px;max-width:$picwidth1;height:auto;padding:0;margin:0;display:block' 
                        data-album='$selectedalbumHtml' data-page='$page' />
                    <img class='icon30 photolibshare' data-userid='$userid' data-album='$selectedalbumHtml' data-page='$page' data src='../img/arrow-stem-left-gray-128.png' style='z-index:100;padding:20px;position:absolute;top:0;left:0' />        
                    <a href='$resizeshare' target='_blank' style='position:absolute;top:150px;left:0'>
                    <img class='icon30' title='Zoom' src='$iconsource_braxzoom_common' style='position:absolute;top:150px;left:0;z-index:200;padding:20px' />
                    </a>
                </td>
            </tr>
            <tr style='position:relative;background-color:$global_background;width:100%;padding:0;margin:0'>
                <td style='position:relative;bottom:0;left:0;background-color:$global_background;max-width:100%;padding:0;margin:0'>
                    
                </td>
            </tr>
            ";
        //End of Photo Display
        /*
         * *******************************
         */
        

        echo "
            </table>
            <br>
            </div>
             ";
        
        echo "
            ";
        
        exit();
    }
    
    //Photounselectarea end
    /****************************************************
     * 
     *  PHOTO ALBUM AREA
     * 
     *****************************************************/
    echo "<span class='photoselectarea'>";
    echo " </span>
            <span class='photoalbumarea'>
         ";

        

    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //$albumitem2 = stripslashes($selectedalbum);
    
    echo "
            <div class='gridnoborder' style='position:relative;background-color:$$global_background;padding-left:10px;padding-right:10px;padding-bottom:10px'>
         ";
    


    echo "  
            </div>
             <table  class='gridnoborder' style='background-color:$global_background;border-collapse:collapse;border:0'>
         ";
    
    $result = pdo_query("1",
        "
            select filename, folder, album, title, createdate, alias, filesize,
            aws_url, aws_expire, datediff(aws_expire,now()) as expire
            from photolib where 
            providerid = ?
            and  album in (select album from photolibshare where providerid = ? and sharetype in (?)
            )
            and (album=? or ?='')
            order by album, createdate desc limit $pagestart, $max
        ",array($userid,$userid,$sharelevel,$selectedalbumSql,$selectedalbumSql));
    
    
    $col=1;
    $items = 0;
    $closed = false;
    while($row = pdo_fetch($result)){
        
        if($items == 0){
            echo GetSharedAlbums($sharelevel, $userid);
            PageButtons( $selectedalbumHtml, $sharelevel, $providerid, $userid, $pageprev, $pagenext, $pagedisplay);
            //echo "ShareLevel Test $sharelevel";
            
        }
    
        $items+=1;
        
        $filename = $row['aws_url'];
        
        $filename = "$rootserver/$installfolder/sharedirect.php?p=$row[filename]";
        
        //The above will filter but massively slow
        $album = ConvertHTML($row['album']);
        $title = htmlentities($row['album']);
        if($filename == '' || $row['expire'] <= 1 ){
            $filename = getAWSObjectUrl($row['filename']);
        }
        

        
        if( $col==1){
        
            echo "
                <tr>
                ";
            $closed = false;
        }
        echo "
            <td class='gridnoborder' style='width:$picwidth;max-width:$picwidth;overflow:hidden;background-color:transparent;padding:0px;margin:0px'>
            <div style='background-color:transparent;margin:0;padding:0'>
            <img class='photoitem photolibshare tooltip tapped2' src='$filename' 
                title='photo $items $title'
                style='position:relative;top;0;left:0;max-width:$picwidthIn;height:$picheightIn;cursor:pointer;border-width:0px;padding:0px;margin:0' 
                data-userid='$userid'    
                data-filename='$row[filename]' data-album='$album' data-page='$page'  />
            </div>
              ";
        


        echo "</td>";

        if( $col==$maxperline){
        
            echo "
                </tr>
                ";
            $col = 0;
            $closed = true;
        }
        
        $col+=1;
    }
    if($closed == false){
    
            echo "
                </tr>
                ";
    }
    echo "
        </table>
        ";
      
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    //*************************************************************
    //*************************************************************
    //*************************************************************

    //*************************************************************
    //*************************************************************
    //*************************************************************

       
    //*************************************************************
    //*************************************************************
    //*************************************************************

    echo "
                <br class='nonmobile'>
                
         ";
    if($items == 0){
        echo
        "&nbsp;&nbsp;No more photos";
    }
    PageButtons( $selectedalbumHtml, $sharelevel, $providerid, $userid, $pageprev, $pagenext, $pagedisplay);
    
    echo "
                    <br>
                <br>

            </span>
        </div>
         ";
    


function PageButtons( $selectedalbumHtml, $sharelevel, $providerid, $userid, $pageprev, $pagenext, $pagedisplay)
{
    global $global_activetextcolor;
    global $iconsource_braxcheck_common;
    global $iconsource_braxarrowup_common;
    global $iconsource_braxarrowdown_common;
    global $global_textcolor;
    echo "
                <br>
                <div style='padding-right:10px'>
                    <img class='icon25 photolibshare tapped' id='nextfilepage' 
                        title='Next Page'
                        src='$iconsource_braxarrowdown_common' 
                        data-page='$pagenext'  
                        data-filename='' 
                        data-userid='$userid'
                        data-album='$selectedalbumHtml'
                        style='cursor:pointer;float:right;
                        position:relative:top:0px;
                        padding-left:5px;
                        padding-right:15px;
                        padding-bottom:10px;
                        margin-left:20px;
                        background-color:transparent;'
                    />
                    <img class='icon25 photolibshare tapped' id='prevfilepage' 
                        title='Previous Page'
                        src='$iconsource_braxarrowup_common' 
                        data-page='$pageprev'   
                        data-filename='' 
                        data-userid='$userid'
                        data-album='$selectedalbumHtml'
                        style='cursor:pointer;float:right;
                        position:relative:top:0px;
                        padding-left:5px;
                        padding-right:5px;
                        padding-bottom:10px;
                        margin-left:10px;
                        background-color:transparent;'
                    />
                </div>
                <div style='float:right;color:$global_textcolor'>$pagedisplay&nbsp;</div>
                <br><br><br><br>
                ";
    
}  
    



function ConvertHTML( $text )
{
        return rawurlencode(stripslashes($text));
}
function DeconvertHTML( $text )
{
        return stripslashes(rawurldecode($text));
}
function GetSharedAlbums($sharelevel, $userid )
{
    global $global_activetextcolor;
    
    $albumlist .= "<div class='photolibshare'
                    data-filename=''
                    data-album=''
                    data-userid='$userid'
                    style='padding-left:20px;padding-right:0px;padding:top:10px;padding-bottom:10px;margin-right:10px;display:inline-block;cursor:pointer;color:$global_activetextcolor'>
                 All
                 </div>";
    $result = pdo_query("1",
        "
            select distinct album
            from photolib where 
            providerid = ?
            and  album in (select album from photolibshare where providerid = ? and sharetype in (?)
            )
            order by album
        ",array($userid,$userid,$sharelevel));
    while($row = pdo_fetch($result)){
        $album = $row['album'];
        $albumHtml = ConvertHtml($album);
        $albumlist .= "<div class='photolibshare'
                        data-filename=''
                        data-album='$albumHtml'
                        data-userid='$userid'
                        style='padding-left:20px;padding-right:0px;padding:top:10px;padding-bottom:10px;margin-right:10px;display:inline-block;cursor:pointer;color:$global_activetextcolor'>
                     $album
                     </div>";
    }
    
    return $albumlist;
}


?>
