<?php
session_start();
require_once("config-pdo.php");
require_once("password.inc.php");
require("aws.php");

    $braxinfo = "<img class='info_file' src='../img/info-yellow-128.png' style='float:right;cursor:pointer;position:relative;top:3px;height:25px;width:auto;padding-left:20px;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("PURIFY",$_SESSION[pid]);
    $page = tvalidator("PURIFY",$_POST[page]);
    $mode = tvalidator("PURIFY",$_POST[mode]);
    $filename = tvalidator("PURIFY",$_POST[filename]);
    $sort = tvalidator("PURIFY",$_POST[sort]);
    $target = tvalidator("PURIFY",$_POST[target]);
    $caller = tvalidator("PURIFY",$_POST[caller]);
    
    if( $sort == "" || $sort == "createdate desc")
    {
        $sort_text = "createdate2 desc";
        $checked1 = "checked=checked";
    }
    if( $sort == "filename")
    {
        $sort_text = "origfilename";
        $checked2 = "checked=checked";
    }
    if( $sort == "filesize desc")
    {
        $sort_text = "filesize desc";
        $checked3 = "checked=checked";
    }
    
    
    if($mode=='D')
    {
        
        pdo_query("1","
            delete from filelib where providerid=$providerid and filename='$filename' and status='Y'
            ");
        
        deleteAWSObject($filename);
        //echo "Deleting '$bucket' '$filename<br>'";
        //var_dump($result);  
    }
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    $page = intval(tvalidator("PURIFY",$_POST[page]));
    if( $page == 0)
        $page = 1;
    $pagenext = intval($page)+1;
    $pageprev = intval($page)-1;
    if( intval($pageprev)< 1 )
        $pageprev = 1;
    
    $max = 20;
    
    
    
    $pagestart = ($page-1) * $max;
    $pagestartdisplay = $pagestart+1;
    $pageenddisplay = $pagestart+$max;
    $pagedisplay = "$pagestartdisplay - $pageenddisplay";
    
        
    $braxdocs = "<img src='../img/brax-doc-round-greatlake-128.png' style='position:relative;top:5px;height:30px;width:auto;padding-top:0;padding-left:20px;padding-right:2px;padding-bottom:0px;' />";
    $safe = "";//<img src='../img/safe-orange-128.png' title='No Encryption - Documents - Protected by SSL' style='height:25px;width:auto;padding-top:0;padding-right:2px;display:inline;padding-bottom:0px;float:right' />";
          
    echo "
        <span class='pagetitle'>$braxdocs &nbsp; Files</span>$braxinfo
            <br>

            &nbsp;            
            &nbsp;

            <img class='fileselect' src='../img/arrowhead-left-128.png' 
                style='position:relative;top;0;cursor:pointer;height:30px' 
                data-caller='$caller' data-target='$target' data-link=' ' data-src='$src' data-mode='$mode'   />
          <br>

        <div class='innerpage' style='background-color:#E5E5E5'><br>";


    //*************************************************************
    //*************************************************************
    //*************************************************************
    echo "
                <div class='divbutton3  divbutton3_unsel fileselect' id='refreshalbum' data-page='$page' data-mode='' data-filename='' data-target='$target' data-caller='$caller'  data-sort='$sort'   >
                   <img src='../img/refresh-128.png' class='stdicon_nopad' />
                </div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input id='photolibpage' class='photolibpage' type=hidden value='$page' />
                [$pagedisplay]&nbsp;&nbsp;
                    &nbsp;
                    &nbsp;
                <div class='divbutton3 divbutton3_unsel fileselect' id='prevfilepage' data-page='$pageprev'  data-link='' data-mode=''  data-sort='$sort' data-target='$target' data-caller='$caller'  > <b> < </b> </div>
                    &nbsp;
                    &nbsp;
                <div class='divbutton3 divbutton3_unsel fileselect' id='nextfilepage' data-page='$pagenext'  data-link='' data-mode=''  data-sort='$sort' data-target='$target' data-caller='$caller'  > <b> > </b> </div>
                <div class=formobile></div>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    &nbsp;
                <div class=formobile></div>
                <input type='radio' name='docsort' class='fileselect' data-page='1' data-mode='' data-filename='' data-link='' data-target='$target' data-caller='$caller'  data-sort='createdate desc' $checked1> Sort by Date
                &nbsp;&nbsp;
                <div class=formobile></div>
                <input type='radio' name='docsort' class='fileselect' data-page='1' data-mode='' data-filename='' data-link='' data-target='$target' data-caller='$caller'  data-sort='filename' $checked2> Sort by Name
                &nbsp;&nbsp;
                <div class=formobile></div>
                <input type='radio' name='docsort' class='fileselect' data-page='1' data-mode='' data-filename='' data-link='' data-target='$target' data-caller='$caller'  data-sort='filesize desc' $checked3> Sort by Size
                &nbsp;&nbsp;
                <div class=formobile></div>
         <br>
         <br>
         ";

    echo "
         <table  class='gridstdborder' style='background-color:white;border-collapse:collapse'>
         ";
    
    $result = pdo_query("1",
        "
            select sum(filesize) as totalsize
            from filelib where providerid = $providerid and status='Y'
        ");
    $row = pdo_fetch($result);
    $totalsize = round($row[totalsize]/1000000,1);
    
    $result = pdo_query("1",
        "
            select origfilename, filename, folder, alias, views, filetype, filesize, title,
            date_format( date_add(createdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%m/%d/%y %h:%i %p') as createdate,
            createdate as createdate2
            from filelib where providerid = $providerid and status='Y'
            order by $sort_text
            limit $pagestart, $max
        ");

    echo "
            <tr class='gridstdborder gridcelltitle' style='background-color:whitesmoke;color:gray'>
                <td class='gridcell gridstdborder'>
                <b>Title / File Name</b>
                </td>
                <td class='gridcell gridstdborder nonmobile'>
                    <span style='border-width:0;border-color:transparent'>
                        <b>Upload Date</b>
                    </span>
                </td>
                <td class='gridcell gridstdborder nonmobile'>
                    <span style='border-width:0;border-color:transparent'>
                        <b>Views</b>
                    </span>
                </td>
                <td class='gridcell gridstdborder'>
                    <b>Select</b>
                </td>
            </tr>
        
        ";
    
    
    while($row = pdo_fetch($result))
    {
        $filename = "$rootserver/$installfolder/$row[folder]$row[filename]";
        $createdate = $row[createdate];
        $mp3 = "";
        $mp3link = "";
        if( $row[filetype]=='mp3')
        {
            $mp3 = "$rootserver/$installfolder/soundplayer.php?p=$row[alias]";
            $fbmp3 = "http://www.facebook.com/sharer.php?u=$rootserver/$installfolder/soundplayer.php?p=$row[alias]";
            $gpmp3 = "https://plus.google.com/share?url=$rootserver/$installfolder/soundplayer.php?p=$row[alias]";
            $mp3link = "<br><br><div class='divbutton3 divbutton3_unsel fileselect' 
                data-link='$mp3' data-target='$target' data-caller='$caller'>Get Mp3 Streaming Link</div>";
            
            
        }
        $download = "$rootserver/$installfolder/doc.php?p=$row[alias]";
        if($row[title]=='')
            $row[title]="$row[origfilename]";
        
        echo "
            <tr class='messagesselect messageselect_unsel'>
                <td class='gridcell gridstdborder'>
                <b>$row[title]</b><br>
                $row[origfilename]
                </td>
                <td class='gridcell gridstdborder nonmobile' style='font-size:11px'>
                    <span style='border-width:0;border-color:transparent'>
                        $createdate<br>
                        Size: $row[filesize]
                    </span>
                </td>
                <td class='gridcell gridstdborder nonmobile'>
                    <span style='border-width:0;border-color:transparent'>
                        $row[views]
                    </span>
                </td>
                <td class='gridcell gridstdborder'>
                    <div class='divbutton3 divbutton3_unsel fileselect' data-link='$download' data-target='$target' data-caller='$caller' >
                    Get Link
                    </div>
                    $mp3link
                </td>
                

            </tr>
              ";
    }
    echo "
        </table>
        <br>
        Total Space Used: $totalsize MB
        ";
      
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    echo "</div>";
    

    
?>