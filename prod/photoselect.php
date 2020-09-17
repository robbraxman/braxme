<?php
session_start();
require_once("config-pdo.php");
require_once("aws.php");
require_once("internationalization.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_SESSION['pid']);
    $target = tvalidator("PURIFY",$_POST['target']);
    $src = @tvalidator("PURIFY",$_POST['src']);
    $passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);
    
    $mode = tvalidator("PURIFY",$_POST['mode']);
    $caller = tvalidator("PURIFY",$_POST['caller']);
    $selectedalbum = "";
    $selectedalbumHtml = "";
    
    if(isset($_POST['album'])){
    
        $selectedalbum = DeconvertHTML(tvalidator("PURIFY",$_POST['album']));
        $selectedalbumHtml = ConvertHTML($selectedalbum);
        $selectedalbumSql = tvalidator("PURIFY",$selectedalbum);
    }
    
    if($selectedalbum == 'All'){
        $selectedalbum = "";
        $selectedalbumHtml = "";
    }
    
    //find First Album if Album is blank
    if( $selectedalbum == '' )
    {
        /*
        $result2 =pdo_query("1","
            select album from photolib where (providerid = $providerid )
                and album!='' and album not like '*%'
                order by createdate desc limit 1
            ");
        if( $row2 = pdo_fetch($result))
        {
            $selectedalbum = tvalidator("PURIFY",$row2['album']);
            $selectedalbumHtml = htmlentities(stripslashes($row2['album']),ENT_QUOTES);
        }
        else
        {
            $selectedalbum = "(Select Album)";
            $selectedalbumHtml = "(Select Album)";
        }
         * 
         */
    }
    
    
    
    
    $result2 =pdo_query("1","
        select count(*) as count from photolib where providerid = ?
            and (album=? or '' =?)
        ",array($providerid,$selectedalbum,$selectedalbum));
    $row2 = pdo_fetch($result);
    $total = $row2['count'];

    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    $page = intval(@tvalidator("PURIFY",$_POST['page']));
    if( $page == 0)
        $page = 1;
    $pagenext = intval($page)+1;
    $pageprev = intval($page)-1;
    if( intval($pageprev)< 1 )
        $pageprev = 1;
    
    require_once("sizingphoto.php");    
    
    
    $pagestart = ($page-1) * $max;
    $pagestartdisplay = $pagestart+1;
    $pageenddisplay = $pagestart+$max;
    $pagedisplay = "$pagestartdisplay - $pageenddisplay of $total";
    if($pageenddisplay > $total)
    {
        $pagedisplay = "$pagestartdisplay - $total";
        if( $pagestartdisplay > $total )
        {
            $pagedisplay = "$pagestartdisplay - End";
        }
    }
    
    

        
    $braxsocial = "<img class='icon20' src='../img/brax-photo-round-black-128.png' style=';width:auto;padding-top:0;padding-left:20px;padding-right:2px;padding-bottom:0px;' />";
          
    /*
    echo "
        <div style='background-color:white;width=100%'>
        <span class='pagetitle'>$braxsocial Select Photo</span>
            <br><br>
        <div class='innerpage' style='padding:0'>";
     * 
     */

    echo "
        <div class='innerpage' style='
        background-color:$global_background;
        color:$global_textcolor;padding:0;margin:0;overflow-x:hidden;
        '>

            <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:10px;padding-right:20px;padding-bottom:3px;margin:0;' >
                <span style='opacity:.5'>
                $icon_braxphoto2
                </span>    
                <span class='pagetitle2a' style='color:white'>$menu_selectphoto</span> 
            </div>
        </div>";
    
    

    //*************************************************************
    //*************************************************************
    //*************************************************************
    //$albumitem2 = stripslashes($selectedalbum);
    echo "  
            <br>
            <img class='icon20 photoselect tapped' src='$iconsource_braxarrowleft_common' 
                title='Back'
                style=';cursor:pointer;padding-left:15px' 
                data-filename=' ' data-alias='' 
                data-caller='$caller' data-target='$target' data-passkey64='$passkey64' data-src='$src' data-mode='$mode'   />
                &nbsp;$menu_back
                
                &nbsp;
                &nbsp;
                <!--
            <img src='$iconsource_braxrefresh_common' class='icon20 photoselect' id='refreshalbum'
              title='Refresh'
             id='refreshalbum' data-page='$page' data-mode='$mode' data-target='$target' data-src='$src'
             data-deletefilename='' data-filename='' data-caller='$caller' data-save='' data-rotate='' data-album='$selectedalbumHtml'  
            style='cursor:pointer;padding-left:15px' />
                -->
          <br><br>
        ";
    $album =  CreateAlbumList( $providerid, $selectedalbum, $selectedalbumHtml, $page, $target, $src, $mode, $caller  );
    echo $album->container;
    
    /*
    echo "
                <div class='formobile'><br></div>
                <div id='photolibalbum' class='photolibalbumselect divbuttontextonly'
                    style='
                    border-width:1px;border-style:solid;border-color:gray;
                    padding-left:10px;padding-right:10px;background-color:whitesmoke;'
                >
                $albumitem2
                    <img src='../img/arrowhead-down-gray-128.png' style='height:15px;position:relative;top:3px' />
                </div>
                &nbsp;&nbsp;
                ";
     * 
     */
    
    
    if( $mode == 'X' && $selectedalbum != '')
    {
        echo "
                &nbsp;&nbsp;
                <img class='icon20 photoselect tapped' 
                    src='$iconsource_braxadd_common' 
                    style=';cursor:pointer;padding-left:10px' 
                    data-alias='$rootserver/img/slideshow.png?a=$selectedalbumHtml' 
                    data-filename='$rootserver/img/slideshow.png?a=$selectedalbumHtml'
                    data-caller='$caller' data-target='$target'  data-passkey64='$passkey64' data-src='$src' data-mode='$mode'   />
                        Select Entire Album as Slideshow
        ";                
    }
    echo "
                
                <br class='formobile'>
                
                    <img class='icon25 photoselect tapped' id='nextfilepage' 
                        title='Next Page'
                         data-page='$pagenext' data-deletefilename='' data-filename='' data-target='$target' data-passkey64='$passkey64'  data-src='$src' 
                        data-mode='$mode' data-caller='$caller' data-album='$selectedalbumHtml' 
                        data-alias=''
                       src='$iconsource_braxarrowdown_common' 
                        data-page='$pagenext' data-deletefilename=''  
                        data-save='' data-filename='' 
                        data-album='$selectedalbumHtml' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:25px;
                        margin-left:10px;
                        background-color:transparent;
                        position:relative;top:13px;' 
                    />
                    <img class='icon25 photoselect tapped' id='prevfilepage' 
                        title='Previous Page'
                        data-page='$pageprev' data-deletefilename='' data-filename='' data-target='$target' data-passkey64='$passkey64'  data-src='$src' 
                        data-mode='$mode' data-caller='$caller' data-album='$selectedalbumHtml' 
                        data-alias=''
                        src='$iconsource_braxarrowup_common' 
                        data-page='$pageprev' data-deletefilename=''  
                        data-save='' data-filename='' 
                        data-album='$selectedalbumHtml' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:5px;
                        margin-left:10px;
                        background-color:transparent;
                        position:relative;top:13px;' 
                    />
                    <br><br>
                <br class='formobile'>
                <div style='color:$global_textcolor;display:inline;float:right;font-size:11px;margin-top:10px;padding-right:25px'>$pagedisplay&nbsp;&nbsp;&nbsp;</div>
            ";
    

    echo "
             <br>
             <table  class='gridstdborder' style='background-color:white'>
         ";
    
    $result = pdo_query("1",
        "
            select filename, alias, folder, album, title, createdate,
            aws_url, aws_expire, datediff(aws_expire, now()) as expire
            from photolib where (providerid = ? or public='Y')
            and (album = ? or (? = '' and public!='Y' and album like 'upload-%' )  )
            and (hide is null or hide = 'N')
            order by createdate desc limit $pagestart, $max
        ",array($providerid,$selectedalbum,$selectedalbum));
    
    
    
    $col=1;
    $items = 0;
    $closed = false;
    while($row = pdo_fetch($result))
    {
        $items+=1;
        //$filename = "$rootserver/$installfolder/$row[folder]$row[filename]";
        $filename = $row['aws_url'];
        //if($filename == '' || $row['expire'] <= 1 )
        //{
            $filename = getAWSObjectUrlShortTerm($row['filename']);
            //pdo_query("1","
            //    update photolib set aws_url = '$filename', aws_expire='2036-01-01' where providerid=$providerid and
            //        filename = '$row[filename]'
            //    ");
            // 'expires'          => gmdate(DATE_RFC2822, strtotime('1 January 1980'))        
        //}
        
        //create some album alias here but for now we'll just put the full identifier
        $album = htmlentities($row['album'],ENT_COMPAT);

        
        if( $col==1){
        
            echo "
                <tr>
                ";
            $closed = false;

        }
        if( $mode == ''){
        
            echo "
                <td class='gridstdborder' style='max-width:$picwidth;height:$picheight;overflow:hidden;background-color:white'>
                <img class='photoselect gridnoborder' src='$filename' 
                    title='photo $items $album'
                    style='position:relative;top;0;left:0;max-width:$picwidthIn;height:$picheightIn;cursor:pointer' 
                    data-filename='$rootserver/$installfolder/sharedirect.php?a=$row[alias]' data-alias='$row[alias]' 
                    data-caller='$caller' data-target='$target' data-passkey64='$passkey64' data-src='$src' data-mode='$mode'   />
                </td>
                ";
        } else
        if( $mode == 'X'){
        
            echo "
                <td class='gridstdborder' style='max-width:$picwidth;height:$picheight;overflow:hidden;background-color:white'>
                <img class='photoselect tapped2 gridnoborder' src='$rootserver/$installfolder/sharedirect.php?a=$row[alias]' 
                    title='photo $items $album'
                    style='position:relative;top;0;left:0;max-width:$picwidthIn;height:$picheightIn;cursor:pointer' 
                    data-filename='$rootserver/$installfolder/sharedirect.php?a=$row[alias]&f=*.jpg' 
                    data-alias='$rootserver/$installfolder/sharedirect.php?a=$row[alias]&f=*.jpg' 
                    data-caller='$caller' data-target='$target'  data-passkey64='$passkey64' data-src='$src' data-mode='$mode'   />
                </td>
                ";
        } else {
            
            echo "
                <td class='gridstdborder' style='width:$picwidth;max-width:$picwidth;height:$picheight;overflow:hidden;background-color:white'>
                <img class='photoselect tapped2 gridnoborder' src='$filename' 
                    title='photo $items $album'
                    style='position:relative;top;0;left:0;max-width:$picwidthIn;height:$picheightIn;cursor:pointer' 
                    data-filename='$row[filename]' data-alias='$row[alias]' 
                    data-caller='$caller' data-target='$target' data-passkey64='$passkey64'  data-src='$src' data-mode='$mode'   />
                </td>
                ";
        }

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

    echo "</div></div>            
";
    
function CreateAlbumList( $providerid, $selectedalbum, $selectedalbumHtml, $page, $target, $src, $mode, $caller  )
{
    global $passkey64;
    global $global_menu2_color;
    global $global_background;
    
    $selectedalbumDisplay = DeconvertHTML($selectedalbum);
    $result2 = pdo_query("1","
        select distinct public, album from photolib where
            ( ( providerid=? and album!='' and album!=? ) or public='Y')
            and album not like '* Artist%'
            and (hide is null or hide = 'N')
                order by public asc, album asc
        ",array($providerid,$selectedalbum));
    
    $color = "#a1a1a4";;
    $colorpublic = $global_menu2_color;//'#49a942';
    $folderdiv2 = "";       
    $folderdivback = "";/* "
            <br>
        <a value='' 
                class='photoselect tapped2'
                data-deletefilename='' data-filename='' 
                data-page='$page' data-rotate='' 
                data-album='All'
                data-target='$target' data-passkey64='$passkey64'
                data-src='$src'
                data-mode='$mode'
                data-caller='$caller'
                style='
                text-decoration:none;
                '
                >
                        <div class='smalltext'
                            style='
                            display:inline-block;margin:0px;
                            text-decoration:none;
                            padding-left:10px;
                            cursor:pointer;
                            vertical-align:middle;
                            color:white;'
                        >
                            <img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' style='' />
                        </div>
        </a> 
            ";
     * 
     */
    $foldercount = 0;
    

    while( $row2 = pdo_fetch($result)){
    
        $foldername_short = substr($row2['album'],0,25);
        if(strlen($row2['album'])>25){
            $foldername_short .= "...";
        }
        $color1 = $color;
        if($row2['public']=='Y'){
            $color1 = $colorpublic;
        }
        $albumHtml = DeconvertHTML($row2['album']);
        
        $folderdiv2 .= "
            <a  
                class='photoselect smalltext2'
                id='tapped2' 
                data-deletefilename='' data-filename='' 
                data-page='1' data-rotate='' 
                data-album='$albumHtml'
                data-target='$target' data-passkey64='$passkey64'
                data-src='$src'
                data-mode='$mode'
                data-caller='$caller'
                style='border-width:1px;border-color:gray;border-style:solid;
                display:inline-block;margin:0px;padding-top:3px;padding-bottom:5px;width:220px;
                min-width:18%;
                border-top-left-radius:10px; 
                border-top-right-radius:10px; 
                text-decoration:none;
                padding-left:3px;
                cursor:pointer;
                background-color:$color1;min-width:8%;vertical-align:middle;
                color:white;
                '
                >
                <div title='Album' style='padding-left:10px;padding-top:5px;padding-bottom:5px'>
                    $foldername_short
                </div>
            </a>
                ";
        
        $foldercount++;
    }
    if($foldercount == 0 ){
        $folder['container']='';
        $folder['div'] = "";
        $folder['back'] = "";
        $folder['count'] = "";
    return (object) $folder;
    }
    
    $folder['container'] =
            "
        <div style='margin:auto;background-color:$global_background;color:black;vertical-align:top;text-align:center'>
        <div style='height:auto;word-break;break-all;word-wrap:break-word;margin:auto;vertical-align:top'>
        ";
    
        if($selectedalbum==''){
            $tablelength = $foldercount * 130;

            $folder['container'] .=
            "
            <div class='tabcontainer' style='max-width:100%;overflow:hidden;text-align:left;margin:0;vertical-align:top;'>
                <div class='tabwrapper tabwrappertall' style='width:$tablelength px;overflow-x:hidden;overflow-y:visible;text-align:left;'>
                    <div class='tablist' id='myTab' style='height:80px;margin:0;text-align:left;padding:0'>
                        $folderdiv2
                    </div>
                </div>
                <div class='tabenlarge' title='Enlarge Album List' style='display:none;cursor:pointer;padding-left:10px;padding-top:10px;padding-bottom:10px'><img class='icon15' src='../img/arrowhead-down-gray-128.png' /> </div>
                <div class='tabshrink' title='Shrink Album List' style=';cursor:pointer;padding-left:10px;padding-top:10px;padding-bottom:10px'><img class='icon15' src='../img/arrowhead-up-gray-128.png' /> </div>
            </div>

            ";
        } else {
            $folder['container'] .=
                "
                <a value='' 
                        class='photoselect tapped2'
                        data-deletefilename='' data-filename='' 
                        data-page='1' data-rotate='' 
                        data-album='All'
                        data-target='$target' data-passkey64='$passkey64'
                        data-src='$src'
                        data-mode='$mode'
                        data-caller='$caller'
                        style='
                        text-decoration:none;
                        '
                        >
                            <div class='photoselect smalltext2'
                                data-deletefilename='' data-filename='' 
                                data-page='$page' data-rotate='' 
                                data-album='$selectedalbumHtml'
                                data-target='$target' data-passkey64='$passkey64'
                                data-src='$src'
                                data-mode='$mode'
                                data-caller='$caller'

                                style='border-width:1px;border-color:gray;border-style:solid;
                                display:inline-block;margin:0px;padding-top:10px;padding-bottom:10px ;width:220px;min-width:150px;
                                border-top-left-radius:10px; 
                                border-top-right-radius:10px; 
                                text-decoration:none;
                                padding-left:10px;
                                cursor:pointer;
                                background-color:$color;
                                color:white;'
                            >
                                <img class='icon20' src='../img/Arrow-Left-in-Circle-White_120px.png' style='' />
                                &nbsp;&nbsp;
                            
                                $selectedalbumDisplay
                            </div>
                </a> 
                    <br><br>
                ";
        }
        
    $folder['container'] .=
        "</div></div>";
    
    
    $folder['div'] = $folderdiv2;
    $folder['back'] = $folderdivback;
    $folder['count'] = $foldercount;
    return (object) $folder;
             
}
function ConvertHTML( $text )
{
        return rawurlencode(stripslashes($text));
}
function DeconvertHTML( $text )
{
        return stripslashes(rawurldecode($text));
}

    
?>            
    