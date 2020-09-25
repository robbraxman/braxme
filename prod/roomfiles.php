<?php
session_start();
require_once("validsession.inc.php");
require_once("config-pdo.php");
require_once("password.inc.php");
require_once("aws.php");
require_once("internationalization.php");

    $braxinfo = "<img class='icon30 info_file' src='../img/braxinfo.png' style='float:right;' />&nbsp;&nbsp;&nbsp;";

    
    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_SESSION['pid']);
    
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $selectedfolder = @tvalidator("PURIFY",$_POST['folder']);
    $selectedfolderid = @tvalidator("PURIFY",intval($_POST['folderid']));
    $roomfolderid = @tvalidator("PURIFY",intval($_POST['roomfolderid']));
    $filename = @tvalidator("PURIFY",$_POST['filename']);
    $sort = @tvalidator("PURIFY",$_POST['sort']);
    $target = @tvalidator("PURIFY",$_POST['target']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $page = @intval( tvalidator("PURIFY",$_POST['page']));
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $altfilename = @tvalidator("PURIFY",$_POST['altfilename']);
    $filtername = @tvalidator("PURIFY",$_POST['filtername']);

    //echo "selected folder = $roomfolderid";
    
    $result = pdo_query("1","select room, owner from statusroom where roomid=? and owner=providerid limit 1",array($roomid));
    while( $row = pdo_fetch($result))
    {
        $room = $row['room'];
        $roomowner = $row['owner'];
    }
    
    if($mode=='F' && $selectedfolder!='')
    {
        if($selectedfolder!='')
        {
            $selectedfolder = preg_replace("/[^A-Za-z0-9-_. ]/", "", $selectedfolder);
        }

        
        pdo_query("1","
            insert into roomfilefolders (roomid, providerid, foldername, parentfolderid, createdate) values
            (?, ?, '--temp--',0, now() )
            ",array($roomid,$providerid)
                );
        
        $result = 
        pdo_query("1",
            "
            select folderid from roomfilefolders where roomid=? and foldername='--temp--'
                ",array($roomid));
        if($row = pdo_fetch($result)){
        
            pdo_query("1",
                "
                update roomfilefolders set foldername=? where roomid=?
                    and folderid = $row[folderid]
                    ",array($selectedfolder,$roomid));
        }
        $statusMessage = "Folder $selectedfolder created<br>";
        
        //Temporary Return to Root Folder
        $selectedfolder = '';
        $selectedfolderid = '0';
        $mode = '';
        
    }    
    if($mode == 'DF')
    {
        pdo_query("1",
            "
            delete from roomfilefolders where roomid=? and providerid=? and folderid=?
                ",array($roomid,$providerid,$selectedfolderid));
        

        $mode = "";
        $selectedfolder = '';
        $selectedfolderid = 0;
    }
    //Save Folder of File
    if( $mode == 'SF')
    {
        
        
        $result = pdo_query("1",
            "
                update roomfiles set folderid=? where
                    filename=? and roomid=? and providerid = ?
            ",array($selectedfolderid,$filename,$roomid,$providerid));
        $filename = '';
        $mode = "";
        
    }
    
    
    
    if($mode == 'S')
    {
        $selectedfolderid = $roomfolderid;
        $result = pdo_query("1","select foldername from roomfilefolders where folderid=? ",array($roomfolderid));
        if($row = pdo_fetch($result)){
            $selectedfolder = $row['foldername'];
        }
        
        
        $jsonfilelist = stripslashes(@tvalidator("PURIFY",$_POST['filenamelist']));
        //$jsonfilelist = @tvalidator("PURIFY",$_POST['filenamelist']);
        
        $filenamelist =  json_decode($jsonfilelist, true);
        //var_dump($filenamelist);
        //exit();
        foreach($filenamelist as $filename){
            //var_dump( $filename )."<br>";
            pdo_query("1",
                "
                insert into roomfiles (roomid, providerid, filename, folderid, createdate, downloads)
                values
                (?, ?, ?,?, now(), 0 )
                    ",array($roomid,$providerid,$filename,$selectedfolderid));
        }
        //exit();

        $mode = "";
        
        pdo_query("1",
            " 
            update statusroom set lastaccess = now()
            where roomid = ?
        ",array($roomid));
    }
    if($mode == 'D')
    {
        pdo_query("1",
            "
            delete from roomfiles where roomid=? and 
            providerid=? and filename=? and
            folderid = ?
            
                ",array($roomid,$providerid,$filename,$selectedfolderid)
             );

        $mode = "";
    }
    

    
    if($selectedfolder!='')
    {
        $selectedfolder = preg_replace("/[^A-Za-z0-9 ]/", "", $selectedfolder);
    }
    if($selectedfolder == "/"){
        $selectedfolder = "";
    }
    $selectedfoldername=$selectedfolder;
    $_SESSION['filefolder']=$selectedfolder;
    if( $selectedfolder=='')
        $selectedfoldername='[Top]';
    
    
    if( $sort == "" || $sort == "createdate desc")
    {
        $sort_text = "createdate2 desc";
    }
    if( $sort == "filename")
    {
        $sort_text = "origfilename";
    }
    if( $sort == "filesize desc")
    {
        $sort_text = "filesize desc";
    }
    
    
    if($mode=='D' && $filename!='')
    {
        
        pdo_query("1",
            "
            delete from filelib where providerid=? and filename=? and status='Y'
            ",array($providerid,$filename));
        
        deleteAWSObject($filename);
        //echo "Deleting '$bucket' '$filename<br>'";
        //var_dump($result);  
    }

    
    $result = pdo_query("1",
        "
            select count(*) as total
            from filelib where 
            filename in (select filename from roomfiles where roomid=? )
            and filetype!='mp3' and status='Y'
        ",array($roomid));
    if($row = pdo_fetch($result))
    {
        $nonmusictotal = $row['total'];
    }
    $result = pdo_query("1",
        "
            select count(*) as total
            from filelib where 
            filename in (select filename from roomfiles where roomid=? )
            and filetype='mp3' and status='Y'
        ",array($roomid));
    if($row = pdo_fetch($result))
    {
        $musictotal = $row['total'];
    }
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    //$page = intval(tvalidator("PURIFY",$_POST['page']));
    if( $page == 0)
        $page = 1;
    $pagenext = intval($page)+1;
    $pageprev = intval($page)-1;
    if( intval($pageprev)< 1 )
        $pageprev = 1;
    
    $max = 100;
    
    
    
    $pagestart = ($page-1) * $max;
    $pagestartdisplay = $pagestart+1;
    $pageenddisplay = $pagestart+$max;
    $pagedisplay = "$pagestartdisplay - $pageenddisplay";
    
    $folder = CreateFolderList( $roomid, $mode, $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $filename, $filtername  );
    

    
         
        
    $braxdocs = "<img class='icon35' src='../img/files-round-gray-128.png' style='display:inline-block;padding-left:20px;padding-right:2px;padding-bottom:0px;' />";
    $safe = "";//<img src='../img/safe-orange-128.png' title='No Encryption - Documents - Protected by SSL' style='height:25px;width:auto;padding-top:0;padding-right:2px;display:inline;padding-bottom:0px;float:right' />";
          
    if($musictotal > 0 && $nonmusictotal == 0)
    {
        $doclibtitle = "<div class='pagetitle' style='padding-left:20px;padding-right:20px'>Music in $room</div>";
    }
    else
    {
        $doclibtitle = "<div class='pagetitle2' style='padding-left:20px;padding-right:20px'><span class='smalltext'></span><b>$menu_roomfiles</b></div>";
    }
    $uploadfile = "uploadfile";
    
    echo "

        <div style='
         background-color:white;color:black;
         '>
                <div class='feed gridnoborder' 
                     data-roomid=$roomid 
                     style='cursor:pointer;background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                    <img class='icon20' src='../img/Arrow-Left-in-Circle-White_120px.png'/>                        
                        &nbsp;&nbsp;
                    <span class='pagetitle2a' style='color:white'>$menu_roomfiles</span> 
                </div>
                &nbsp;
                &nbsp;
                &nbsp;
            <center><b>$room</b></center>
            <div class='fileselect tapped' 
                style='cursor:pointer;color:gray;display:inline-block;margin-left:20px'
                id='fileselect_icon' data-target='' data-album='' data-roomid='$roomid'
                 data-src='' data-filename='' data-link=''  data-caller='roomfile' 
                 data-roomfolderid='$selectedfolderid' data-folder='' data-folderid='0'
            >
                  <img class='icon25' src='../img/files-circle-gray-128.png' style='display:inline-block;top:0px' />
                  <div style='display:inline-block'>
                    <br><br>
                    Share from<br>My Files
                  </div>
            </div>
            
            <img src='../img/tasks-circle-128.png' 
                    style='cursor:pointer;margin-left:10px;margin-right:10px'  
                    class='icon25 doclib tapped' id='createtext' 
                    data-page='1' 
                    data-folder=''
                    data-folderid='0'
                    data-roomfolderid='$selectedfolderid'
                    data-roomid='$roomid'
                    data-mode='TEXT' 
                    data-filename='' 
                    data-sort=''   
                    data-target='$target' 
                    data-caller='roomfileedit' 
                    data-passkey64=''        
                    title='Create a Text File'
                >
            <img class='icon25 $uploadfile' id='uploadfile' 
                src='../img/upload-circle-128.png' style='cursor:pointer;' title='Upload a File'    />
            <br><br>
            

        <div class='innerpage' style='background-color:white;overflow:hidden'>
        ";
    if( $filename=='' || $caller=='roomfile')
    {
        echo "
            ";
            
    }

    if($providerid == $roomowner){
    //New Folder
    echo "
        <div class='filefolderoptions mainfont' style='cursor:pointer;float:right;color:$global_activetextcolor;margin-right:20px;margin-botton:10px'>$menu_newfolder&nbsp;<br></div>

        <div class='filefolderoptionsarea mainfont' style='display:none;color:$global_activetextcolor'>
            $menu_newfolder<br>
            <input class='room_newfolder mainfont dataentry' placeholder='$menu_name' type=text size=27 maxlength='30' value='' />
            <img class='icon20 roomfiles tapped2' 
                data-page='$page' 
                data-sort='$sort' 
                data-target='$target' 
                data-caller='$caller' 
                data-roomid='$roomid'
                data-filename=''  
                data-folder='$selectedfolder'
                data-mode='F'
                src='../img/Arrow-Right-in-Circle_120px.png' 
                style='' />
            <br><br><br>
        </div>
        ";
    }

    
    /**********************************************************
     * 
     *   CHANGE FOLDER DISPLAY
     * 
    **********************************************************/
    if($mode == 'CF'){
        
        echo "
                <br>
                &nbsp;&nbsp;<img class='icon20 roomfiles tapped2' src='../img/Arrow-Left-in-Circle_120px.png' 
                    style=''
                        
                    data-page='$page'
                    data-sort='$sort'
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-target='$target' 
                    data-caller='$caller' 
                    data-roomid='$roomid'
                    />
                    Back 
                    <br><br><br>
                    <b>&nbsp;&nbsp;Choose a Destination Folder</b>
                    <br><br>
                    <div class=gridstdborder style='cursor:pointer;max-width:100%:width:100%;height:auto;word-break;break-all;word-wrap:break-word;margin:0;background-color:whitesmoke'>
                        <br>
                        $folder->div
                        <br><br>
                    </div>
            ";        
            echo "</div>";
            exit();


    }

    //*************************************************************
    //*************************************************************
    //*************************************************************
    // FULL FILE LIST
    //*************************************************************
    //*************************************************************
    //*************************************************************
    PageButtons( $selectedfolder, $selectedfolderid, $roomfolderid, $pagedisplay, $pagenext, $pageprev, $roomid, $sort, $target, $caller, $page );

    echo "                </div>
        ";
    
    echo $folder->container;
    echo SortButtons($sort, $roomid, $target, $caller  );

    
    
    
    

    
    if($filtername==''){
        $limit = " limit $pagestart, $max";
    }
    $result = pdo_query("1",
        "
            select filelib.origfilename, filelib.filename, filelib.folder, 
            filelib.alias, filelib.views, filelib.filetype, filelib.filesize, filelib.title,
            date_format( date_add(roomfiles.createdate,INTERVAL ($_SESSION[timezoneoffset])*60 MINUTE),'%b %d, %y %h:%i%p') as createdate,
            filelib.createdate as createdate2, filelib.encoding, filelib.providerid
            from filelib 
            left join roomfiles on roomfiles.filename = filelib.filename 
                and roomfiles.folderid=?
            where roomfiles.roomid = ? and filelib.status = 'Y'
            order by $sort_text
            $limit
        ",array($selectedfolderid,$roomid));

    echo "<div style='width:100%;padding:0;margin:auto;text-align:center;background-color:white;vertical-align:top'>";
    
    
    /*
    echo " 
            <script>
            audiojs.events.ready(function() 
            {
                 var as = audiojs.createAll();
            });
            </script>
        ";
     * 
     */
    
    //echo "          <hr style='height:0px;border:0;border-top: 1px solid rgba(0, 0, 0, 0.1);border-bottom: 1px solid rgba(255, 255, 255, 0.3);'>
    //    ";
    
    $count=0;
    while($row = pdo_fetch($result))
    {
        $count++;
        $encoding = $row['encoding'];
        $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
        $title = DecryptText($row['title'], $encoding, $row['filename'] );
        if($title == ''){
            $title = $origfilename;
        }
        
        $icon = GetFileTypeIcon( $row['filetype']);           
        

        $shorttitle = substr($title,0,25);
        if(strlen($title)>25)
        {
            $shorttitle .="...";
        }
        
        $shortname = substr($origfilename,0,25);
        if(strlen($origfilename)>25)
        {
            $shortname .="...";
        }
        $filesize = $row['filesize'];
        if($row['filesize']>1000000){
            $filesize = round($row['filesize']/1000000,1)."MB";
        }
        
        
        if( $row['filetype']=='mov' ||  $row['filetype']=='mp4' ){
        
            $href = "$rootserver/$installfolder/videoplayer.php?p=$row[alias]&f=$origfilename&t=$title";
        } else {
            $href = "$rootserver/$installfolder/doc.php?p=$row[alias]";
        }
        
            
        
        $delete = "";
        if( $row['providerid'] == $providerid){
        
            $delete = "
                       <div class='roomfiles tapped smalltext' data-mode='CF' data-roomid='$roomid' data-folderid='$selectedfolderid' data-folder='$selectedfolder' data-filename='$row[filename]'
                        style='float:left;display:inline-block;cursor:pointer;padding-right:20px;margin-left:30px;margin-bottom:10px;'>
                        <img class='icon20' src='../img/share-circle-gray-128.png' style='' />
                       </div>
                       <div class='roomfiles tapped' data-mode='D' data-roomid='$roomid' data-folderid='$selectedfolderid' data-folder='$selectedfolder' data-filename='$row[filename]'
                        style='float:left;display:inline-block;cursor:pointer;padding-right:20px;'>
                        <img class='icon20' src='../img/Close_120px.png' style='' />
                       </div>
                       ";
        }

        
        echo "
             <div  class='fileboxwidth'
                style='
                display:inline-block;
                word-break:break-all;
                padding-left:20px;padding-bottom:10px;vertical-align:top;text-align:left;max-width:90%' 
                >
                
                <img class='icon30' src='$icon' style='float:left;padding-right:10px;padding:bottom:25px;margin:0'/>
                
                    
                
                <div
                    class='doclib1 smalltext2'
                    data-filename='$row[filename]'
                    data-mode='V'
                    data-caller='roomfileedit'
                    data-roomid='$roomid'
                    style='
                    cursor:pointer;
                    display:inline-block;
                    text-align:left;
                    margin:auto;
                    background-color:transparent;
                    padding-top:10px;
                    word-break:break-all;
                    vertical-align:top' 
                    

                    >
                    <b class='smalltext'>$shortname</b><br>
                    $row[createdate]<br>$filesize 
                </div>
                <span class='smalltext2'>
                    <br>
                </span>
                <a href='$href' target='_blank' style='float:left;margin-top:0px;text-decoration:none;color:$global_activetextcolor'>
                    Download 
                </a>
                $delete
            </div>
                ";
        
        

    }
    
    if($roomowner == $providerid && $count == 0 && intval($selectedfolderid)!=0 && intval($page) <= 1 ){
            echo "
                <br><br>
                <center>
                <div class='divbutton3 divbutton3_unsel roomfiles tapped' 
                    data-page='$page' 
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-filename=''  
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-roomid='$roomid'
                    data-mode='DF'>
                    <img class='icon20' src='../img/delete-circle-128.png' style='' />
                    Delete Empty Folder '$selectedfolder'
                </div>
                </center>
                <br><br>
    
            ";
        
    }
    echo "
        </div>
        <br><br><br>
        ";
      
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    echo "</div></div>";
    
function GetFileTypeIcon( $filetype )
{
            $icon = '../img/flat_other.png';
            if( $filetype=='jpg')
            {
                $icon = '../img/flat_camera.png';
            }    
            if( $filetype=='png' ||
                $filetype=='gif' ||
                $filetype=='tif' ||
                $filetype=='tiff' ||
                $filetype=='bmp' 
              )
            {
                $icon = '../img/flat_photo.png';
            }    
            if( $filetype=='mp3' ||
                $filetype=='m4a' ||
                $filetype=='m4p' 
              )
            {
                $icon = '../img/flat_mp3.png';
            }    
            if( $filetype=='wav')
            {
                $icon = '../img/flat_wav.png';
            }    
            if( $filetype=='zip')
            {
                $icon = '../img/flat_zip.png';
            }    
            if( $filetype=='ppt' ||
                $filetype=='pptx' )
            {
                $icon = '../img/flat_ppt.png';
            }    
            if( $filetype=='xls' ||
                $filetype=='xlsx' )
            {
                $icon = '../img/flat_excel.png';
            }    
            if( $filetype=='doc' ||
                $filetype=='docx' ||
                $filetype=='pages' )
            {
                $icon = '../img/flat_doc.png';
            }    
            if( $filetype=='pdf')
            {
                $icon = '../img/flat_pdf.png';
            }    
            if( $filetype=='mp4' ||
                $filetype=='mov')
            {
                $icon = '../img/flat_movie.png';
            }    
    return $icon;
}
function CreateFolderList( $roomid, $mode, $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $filename, $filtername )
{
    
    $foldermode = '';
    if($mode == 'CF'){
        $foldermode = 'SF';
        $selectedfolderid = 0;
    }
    $result2 = pdo_query("1","
        select distinct foldername, folderid from roomfilefolders where roomid = ? 
            and parentfolderid= ?
            order by foldername asc
        ",array($roomid,$selectedfolderid));
    $folderdiv = "";  
    $folderdiv2 = "";
    if($mode == 'CF')
    {
        //define ROOT FOLDER
        $folderdiv .= "
            <div value='' style='word-break:break-all;cursor:pointer;display:inline-block;height:100px;width:100px;text-align:center;vertical-align:top'
                class='roomfiles smalltext tapped2'
                    data-page='1' 
                    data-folder=''
                    data-folderid='0'
                    data-mode='$foldermode'  
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-filename='$filename' 
                    data-roomid='$roomid'
                    data-roomfolderid='$roomfolderid'
                    >
                
                <img class='icon20; src='../img/folder-closed-01-128.png' style='' />
                <br>
                /
            </div>        
                ";
        
        //define ROOT FOLDER
        $folderdiv2 .= "
            <a value='' 
                class='roomfiles smalltext2'
                    data-page='1' 
                    data-folder=''
                    data-folderid='0'
                    data-mode='$foldermode'  
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-filename='$filename' 
                    data-roomid='$roomid'
                    data-roomfolderid='$roomfolderid'
                    style='border-width:1px;border-color:whitesmoke;border-style:solid;
                    display:inline-block;margin:0px;padding-top:5px;padding-bottom:5px ;width:120px;
                    border-top-left-radius:10px; 
                    border-top-right-radius:10px; 
                    text-decoration:none;
                    padding-left:10px;
                    cursor:pointer;
                    background-color:#49a942;
                    color:white;
                    '
                    >
                /
            </a>

            <br>
                ";
    }
    $folderdivback = "
        <div value='' style='cursor:pointer;display:inline;width:50px;text-align:left;vertical-align:top'
            class='roomfiles smalltext tapped2'
                data-page='1' 
                data-folder=''
                data-folderid='0'
                data-mode='$foldermode'  
                data-sort='$sort' 
                data-target='$target' 
                data-caller='$caller' 
                data-filename='' 
                data-roomid='$roomid'
                data-roomfolderid='$roomfolderid'
                >

            <img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' style='' />
        </div> 
            ";
    $foldercount = 0;
    while( $row2 = pdo_fetch($result2)){
    
        $foldername_short = substr($row2['foldername'],0,15);
        if(strlen($row2['foldername'])>15){
            $foldername_short .= "...";
        }
        $folderdiv .= "
            <div value='$row2[foldername]' style='word-break:break-all;cursor:pointer;display:inline-block;height:100px;width:100px;text-align:center;vertical-align:top'
                class='roomfiles smalltext tapped2'
                    data-page='1' 
                    data-folder='$row2[foldername]'
                    data-folderid='$row2[folderid]'
                    data-mode='$foldermode'  
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-filename='$filename' 
                    data-roomid='$roomid'
                    data-roomfolderid='$roomfolderid'
                    >
                
                <img class='icon20' src='../img/folder-closed-01-128.png' style='' />
                <br>
                $row2[foldername]
            </div>        
                ";
        $folderdiv2 .= "
            <a value='$row2[foldername]' 
                class='roomfiles smalltext2'
                    data-page='1' 
                    data-folder='$row2[foldername]'
                    data-folderid='$row2[folderid]'
                    data-mode='$foldermode'  
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-filename='$filename' 
                    data-roomid='$roomid'
                    data-roomfolderid='$roomfolderid'
                    style='border-width:1px;border-color:whitesmoke;border-style:solid;
                    display:inline-block;margin:0px;padding-top:5px;padding-bottom:5px;width:150px;
                    border-top-left-radius:10px; 
                    border-top-right-radius:10px; 
                    text-decoration:none;
                    padding-left:5px;
                    cursor:pointer;
                    background-color:#a1a1a4;min-width:6%;
                    color:white;
                    '
                    >
                    <img class='icon15' src='../img/arrowhead-right-white-compact-128.png' style='position:relative;top:3px;padding:0;margin:0' />
                /$foldername_short
            </a>
                ";
        $foldercount++;
    }
    
    $folder['container'] =
            "
        <div style='cursor:pointer;max-width:100%:width:100%;height:auto;word-break;break-all;word-wrap:break-word;margin:0;background-color:#E5E5E5;color:black;vertical-align:top'>
        ";
        if($selectedfolder==''){
            
            $tablelength = $foldercount * 130;

            $folder['container'] .=
            "
            <div class='tabcontainer' style='background-color:whitesmoke;max-width:100%;overflow:hidden;text-align:left;margin:0;vertical-align:top'>
                <div class='tabwrapper' style='width:$tablelength px;overflow-x:hidden;overflow-y:scroll;text-align:left'>
                    <div class='tablist' id='myTab' style='background-color:whitesmoke;height:80px;margin:0;text-align:left;padding:0'>
                        $folderdiv2
                    </div>
                </div>
                <div class='tabenlarge' style='background-color:whitesmoke;cursor:pointer;padding-top:10px;padding-bottom:10px'>&nbsp;<img class='icon15' src='../img/arrowhead-down-gray-128.png' /> </div>
            </div>
            ";
        } else {

            $folder['container'].=
                "
                    <div style='padding-left:20px;padding-top:0px'>
                        <br>
                        $folderdivback&nbsp;&nbsp;
                        <div class='smalltext2'
                            style='border-width:1px;border-color:gray;border-style:solid;
                            display:inline-block;margin:0px;padding-top:10px;padding-bottom:10px;width:220px;
                            border-top-left-radius:10px; 
                            border-top-right-radius:10px; 
                            text-decoration:none;
                            padding-left:10px;
                            cursor:pointer;
                            background-color:#a1a1a4;
                            color:white;'
                        >
                            /$selectedfolder
                        </div>
                    </div>
                    <br><br>
                ";
            
        }
    $folder['container'] .=
        "</div><br>";
    if($foldercount == 0 && $selectedfolder == ''){
        $folder['container']='';
    }
    
    $folder['div'] = $folderdiv;
    $folder['back'] = $folderdivback;
    $folder['count'] = $foldercount;
    return (object) $folder;
             
}    
function PageButtons( $selectedfolder, $selectedfolderid, $roomfolderid, $pagedisplay, $pagenext, $pageprev, $roomid, $sort, $target, $caller, $page )
{
  
    echo "
                    <input id='photolibpage' class='photolibpage' type=hidden value='$page' />
                    <img class='icon25 roomfiles tapped' id='prevfilepage' 
                        src='../img/arrow-circle-down-128.png' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:5px;
                        margin-left:10px;
                        background-color:transparent;'
                        data-page='$pagenext' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-mode=''  
                        data-sort='$sort' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid' 
                        
                    />
                    <img class='icon25 roomfiles tapped' id='prevfilepage' 
                        src='../img/arrow-circle-up-128.png' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:5px;
                        background-color:transparent;'
                        data-page='$pageprev' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-mode=''  
                        data-sort='$sort' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid' 
                        
                    />
                    <br>
                    <div class='smalltext' style='float:right'>
                         $pagedisplay&nbsp;&nbsp;
                    </div>
         <br>
         ";
    
}
function SortButtons($sort, $roomid, $target, $caller  )
{
    global $menu_sortbydate;
    global $menu_sortbyname;
    global $menu_sortbysize;
    
    $checked1 = "";
    $checked2 = "";
    $checked3 = "";
    $sortcolor = "#e5e5e5";
    $unsortedcolor = "white";
    
    if( $sort == "" || $sort == "createdate desc")
    {
        $sort_text = "createdate2 desc";
        $checked1 = "checked=checked";
        $color1 = $sortcolor;
        $color2 = $unsortedcolor;
        $color3 = $unsortedcolor;
    }
    if( $sort == "filename")
    {
        $sort_text = "title, origfilename";
        $checked2 = "checked=checked";
        $color1 = $unsortedcolor;
        $color2 = $sortcolor;
        $color3 = $unsortedcolor;
    }
    if( $sort == "filesize desc")
    {
        $sort_text = "filesize desc";
        $checked3 = "checked=checked";
        $color1 = $unsortedcolor;
        $color2 = $unsortedcolor;
        $color3 = $sortcolor;
    }
    
    $text = 
            "
                    <div class='roomfiles gridstdborder smalltext' 
                        data-page='1' 
                        data-mode='' 
                        data-filename='' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid' 
                        data-sort='createdate desc'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:30%;
                        background-color:$color1'
                    >
                        $menu_sortbydate
                    </div>
                    <div class='roomfiles gridstdborder smalltext' 
                        data-page='1' 
                        data-mode='' 
                        data-filename='' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid' 
                        data-sort='filename'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:30%;
                        background-color:$color2'
                    >
                        $menu_sortbyname
                    </div>
                    <div class='roomfiles gridstdborder smalltext' 
                        data-page='1' 
                        data-mode='' 
                        data-filename='' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid' 
                        data-sort='filesize desc'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:30%;;
                        background-color:$color3;'
                    >
                        $menu_sortbysize
                    </div>
                
            ";
    return $text;
    

}

?>