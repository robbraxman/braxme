<?php
session_start();
require_once("validsession.inc.php");
require_once("config-pdo.php");
require_once("password.inc.php");
require_once("aws.php");
require_once("imageproc.inc");
require_once("textupload.php");
require_once("internationalization.php");

    $time1 = microtime(true);
    $braxinfo = "<img class='info_file icon35' src='../img/braxinfo.png' style='top:0px;float:right;padding-right:10px;padding-bottom:0px;' />";

    
    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_SESSION['pid']);
    
    $selectedfolder = @tvalidator("PURIFY",$_POST['folder']);
    $selectedfolderid = @tvalidator("PURIFY",intval($_POST['folderid']));
    $roomfolderid = @tvalidator("PURIFY",intval($_POST['roomfolderid']));
    $parentfolderid = @tvalidator("PURIFY",intval($_POST['parentfolderid']));
    $filename = @tvalidator("PURIFY",$_POST['filename']);
    $sort = @tvalidator("PURIFY",$_POST['sort']);
    $target = @tvalidator("PURIFY",$_POST['target']);
    $caller = @tvalidator("PURIFY",$_POST['caller']);
    $page = @intval( tvalidator("PURIFY",$_POST['page']));
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $altfilename = @tvalidator("PURIFY",$_POST['altfilename']);
    $filtername = @tvalidator("PURIFY",$_POST['filtername']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $targetemail = @tvalidator("PURIFY",$_POST['targetemail']);
    $passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);

    $braxdocs = "<img class='icon20' src='../img/brax-doc-round-white-128.png' style='top:3px;padding-left:10px' />";
    $statusMessage = "";
    SaveLastFunction($providerid,"F","");
    
    

    //Special Mode - return from Upload - restore Folder
    if($mode == 'R'){
        if(isset($_SESSION['filefolder']) && $_SESSION['filefolder']!=''){
            $selectedfolder = $_SESSION['filefolder'];
            $selectedfolderid = $_SESSION['filefolderid'];
        }
    }
    
    //echo "$roomfolderid";
    
    //Create Folder
    /*
     * 
     * 
     *    HANDLE FOLDER
     * 
     * 
     */
    
    
    /*
     * 
     *    CREATE FOLDER
     * 
     * 
     */
    if($mode=='F' && $selectedfolder!=''){
    
        if($selectedfolder!=''){
        
            $selectedfolder = preg_replace("/[^A-Za-z0-9-_. ]/", "", $selectedfolder);
        }

        //Currently undefined
        $parentfolder = ""; 
        
        pdo_query("1","
            insert into filefolders (providerid, foldername, parentfolder, parentfolderid) values
            (?, '--temp--',?,0)
            ",array($providerid,$parentfolder));
        
        $result = 
        pdo_query("1","
            select folderid from filefolders where providerid=$providerid and foldername='--temp--'
                ",array($providerid));
        if($row = pdo_fetch($result)){
        
            pdo_query("1","
                update filefolders set foldername=? where providerid=?
                    and folderid = $row[folderid]
                    ",array($selectedfolder, $providerid));
        }
        //$statusMessage = "Folder $selectedfolder created ($row[folderid])<br>";
        $statusMessage = "Folder $selectedfolder created<br>";
        
        //Temporary Return to Root Folder
        $selectedfolder = '';
        $selectedfolderid = '0';
        $mode = '';
        
    }
    //Delete Folders
    if($mode=='DF' && $selectedfolder!=''){
    
        pdo_query("1","
            delete from filefolders where providerid=? and folderid=?
            ",array($selectedfolderid));
        
        pdo_query("1","
            update filelib set status='N', folder='', folderid = 0 where providerid=? and folderid=?
            ",array($providerid,$selectedfolderid));
        //$statusMessage = "Folder $selectedfolder deleted ($selectedfolderid)<br>";
        $statusMessage = "Folder $selectedfolder deleted<br>";
        
        $selectedfolder = '';
        $selectedfolderid = 0;
        $mode = '';
        
    }
    
    //Figure out folder name from folderid
    /*
     * 
     *  FIGURE OUT OUR FOLDER
     * 
     * 
     */
    $_SESSION['filefolder']='';
    $_SESSION['filefolderid']=0;
    if(intval($selectedfolderid)!=0){
    
        $result = pdo_query("1","  
            select foldername from filefolders where providerid=? 
                and folderid = ?
                ",array($providerid,$selectedfolderid));
        if($row = pdo_fetch($result)){
            $selectedfolder = $row['foldername'];
            
        }
        $_SESSION['filefolder']=$selectedfolder;
        $_SESSION['filefolderid']=$selectedfolderid;
    }
        
    //Save Folder of File
    if( $mode == 'SF'){
    
        $origfolder = "";
        $result = pdo_query("1",
            "
                select folder, folderid from filelib where
                    filename=? and providerid = ? and status='Y'
            ",array($filename,$providerid));
        if($row = pdo_fetch($result)){
        
            $origfolder = $row['folder'];
            $origfolderid = $row['folderid'];
        }
        
        
        $result = pdo_query("1",
            "
                update filelib set folder=?, folderid=? where
                    filename=? and providerid = ?
            ",array($selectedfolder,$selectedfolderid, $filename, $providerid));
        $thisfolder = $selectedfolder;
        if($selectedfolder == ''){
            $thisfolder = "Root";
        }
        $statusMessage = "File moved to $thisfolder<br>";
        $filename = '';
        $selectedfolder = $origfolder;
        $selectedfolderid = $origfolderid;
        $_SESSION['filefolder'] = $origfolder;
        $_SESSION['filefolderid'] = $origfolderid;
        $mode = "";
        
    }
    
    
    
    
    
    
    if( $sort == "" || $sort == "createdate desc"){
    
        $sort_text = "createdate2 desc";
    }
    if( $sort == "filename"){
    
        $sort_text = "title, origfilename";
    }
    if( $sort == "filesize desc"){
    
        $sort_text = "filesize desc";
    }
    
    //Delete File
    if($mode=='D' && $filename!=''){
    
        
        pdo_query("1","
            update filelib set status='N' where providerid=? and filename=? and status='Y'
            ",array($providerid,$filename));
        
        deleteAWSObject($filename);
        $statusMessage = "File deleted<br>";
        $filename = '';
        $mode = '';
        //echo "Deleting '$bucket' '$filename<br>'";
        //var_dump($result);  
    }
    //PIN File
    if($mode=='PIN' && $filename!=''){
    
        
        pdo_query("1","
            update filelib set pin='Y' where providerid=? and filename=? and status='Y'
            ",array($providerid,$filename));
        
        $filename = '';
        $mode = '';
    }
    //PIN File
    if($mode=='UNPIN' && $filename!=''){
    
        
        pdo_query("1","
            update filelib set pin='' where providerid=? and filename=? and status='Y'
            ",array($providerid,$filename));
        
        $filename = '';
        $mode = '';
    }
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //Sent to EMAIL ADDRESS
    if( $mode == 'E'){
    
        $result = pdo_query("1",
            "
                select  providerid, providername from provider 
                where (replyemail = ? or (handle = ? and handle!='' ) )  and active='Y'
            ",array($targetemail,$targetemail));
        
        if($row = pdo_fetch($result)){
            $targetproviderid = $row['providerid'];
            $targetname = $row['providername'];
        } else {
            
            exit();
        }
        
        $result = pdo_query("1",
            "
                select  filename, origfilename, title, folder, filesize, 
                        filetype, title, createdate, alias, encoding 
                from filelib where filename=? and providerid= ? and status='Y'
            ",array($filename,$providerid));
        
        if($row = pdo_fetch($result)){
            $alias = uniqid("T4AZ", true);
            $unique_id = uniqid();
            
            $uploadfilename= $targetproviderid."_".$unique_id.".".$row['filetype'];
            $origfilename = DecryptText( $row['origfilename'],$row['encoding'],"$filename" );
            $neworigfilename = EncryptTextCustomEncode( $origfilename,"PLAINTEXT","$uploadfilename" );
            $title = EncryptTextCustomEncode($targetname,"PLAINTEXT", "$uploadfilename");
            
            if(copyAWSObject( $uploadfilename, $filename )){
            
                pdo_query("1",
                    "
                        insert into filelib
                        ( providerid, filename, origfilename, folder, filesize, 
                          filetype, title, createdate, alias, encoding, status )
                        values
                        ( ?, ?,?, '',$row[filesize], 
                          '$row[filetype]',?, now(), ?,'PLAINTEXT','Y' ) 
                    ",array(
                        $targetproviderid, $uploadfilename,$neworigfilename, 
                          $title,$alias ) 
                        
                    ));
            }
            
            
        }
        $mode = "";
        $filename = "";
        
    }
    
    if( $mode == "BW" ){
    
        $workingfolder = "upload-zone/files/";
        
        $statusMessage = "File converted to Black and White<br>";
        
        if(!is_writable ( $workingfolder )  ){        
        
            echo "Can't write to $workingfolder";
            exit();
        }
        try {
            $e = explode(".",$filename);
            $f = explode("_",$e[0]);
            $ext = strtolower($e[count($e)-1]);
            $uniqid = uniqid("",false);

            $newfilename= $f[0]."_".$f[1]."_".$uniqid.".$ext";
            //saveAWSObject($filename, "$workingfolder$newfilename");
            
            $encoding = "PLAINTEXT";
            $result = pdo_query("1","
                select fileencoding, filetype from filelib 
                    where providerid=? and filename=?
                    ",array($provider,$filename));
            if($row = pdo_fetch($result)){
                $encoding = $row['fileencoding'];
                $ext = $row['filetype'];
            }
            
            saveAWSObjectStreamEncrypted( $filename, $encoding, 0xFFFFFF, $encoding );
            echo "$newfilename<br>";
            
            $img = new ImageManipulation();
            $img->load("$workingfolder$filename");
            $img->blackwhite();
            
            $img->save_image("$workingfolder$newfilename", "$ext");
            putAWSObject($newfilename, "$workingfolder$newfilename");


            pdo_query("1","
                update filelib set filename=? 
                    where providerid=? and filename=?
                    ",array($newfilename,$providerid,$filename));
              
             


            //unlink("$workingfolder$filename");

            //unlink("$folder$newfilename");
            //
            deleteAWSObject($filename );
            $mode = "";


            //$filename = $newfilename;
        } catch (Exception $e) {
            echo $e->getMessage()." $rotatefolder$filename";
            exit();
        }
    }

    //*************************************************************
    //*************************************************************
    //*************************************************************
    //Save File
    if( $mode == 'S'){
    
        $path = pathinfo("$filename");
        $origfilename =  @tvalidator("PURIFY",$_POST['origfilename']);
        $pathname = pathinfo( $origfilename );
        if( strtolower($path['extension'])!=strtolower($pathname['extension'])){
        
            $origfilename .= ".$path[extension]";
            
        }          
        //Change the Name if it's a duplicate
        $origfilename = duplicateNameCorrection($providerid, $filename, $origfilename);

        
        $origfilename_encrypted = EncryptTextCustomEncode( $origfilename,"PLAINTEXT","$filename" );
        
        $title = EncryptTextCustomEncode(@tvalidator("PURIFY",$_POST['title']),"PLAINTEXT", "$filename");
        $result = pdo_query("1",
            "
                update filelib set origfilename=?, title=?, encoding='PLAINTEXT', folder=? where
                    filename=? and providerid = ?
            ",array(
                $origfilename_encrypted, $title, $selectedfolder,$filename, $providerid
            ));
        $statusMessage = "File $origfilename info changed<br>";
        $filename = '';
        $mode = "";
    }
    //Regenerate Alias to Change Link
    if( $mode == 'L'){
    
        $statusMessage = "File external link changed<br>";
        $alias = uniqid("T4AZ", true);
        $result = pdo_query("1",
            "
                update filelib set alias=? where
                    filename=? and providerid = ?
            ",array($alias,$filename,$providerid));
        $filename = '';
        $mode = "";
    }
    if( $mode == 'TEXT'){
        echo TextEditor($sort, $caller, $roomid, $page, $target, $selectedfolder, $selectedfolderid, "TEXTSAVE","","");
        exit();
    }
    if( $mode == 'TEXTSAVE'){
        $contents = ($_POST['content']);
        if($contents!=''){

            $contents = str_replace(chr(13),"",$contents);
            $contents = str_replace(chr(10),chr(13).chr(10),$contents);

            $origfilename = @tvalidator("PURIFY",$_POST['textfilename']);
            $upload_dir = "/var/www/html/$installfolder/upload-zone/files/$_SESSION[pid]";

            textsavefile($contents, $upload_dir, $origfilename, $roomid ) ;
        }
        $mode = "";
    }
    if( $mode == 'TEXTEDIT'){
        $contents = ($_POST['content']);
        
        $contents = str_replace(chr(13),"",$contents);
        $contents = str_replace(chr(10),chr(13).chr(10),$contents);
        
        $origfilename = @tvalidator("PURIFY",$_POST['filename']);
        $upload_dir = "/var/www/html/$installfolder/upload-zone/files/$_SESSION[pid]";
        
        texteditfile($contents, $upload_dir, $origfilename, $roomid ) ;
        $mode = "";
    }
    
    //*************************************************************
    //*************************************************************
    //*************************************************************

    /******************************
     * 
     *   PAGING SETTINGS
     * 
     */
    
    //$page = intval(tvalidator("PURIFY",$_POST['page']));
    if( $page == 0){
        $page = 1;
    }
    $pagenext = intval($page)+1;
    $pageprev = intval($page)-1;
    if( intval($pageprev)< 1 )
        $pageprev = 1;
    
    $max = 500;
    
    
    
    $pagestart = ($page-1) * $max;
    $pagestartdisplay = $pagestart+1;
    $pageenddisplay = $pagestart+$max;
    $pagedisplay = "$pagestartdisplay - $pageenddisplay";
    
    
    /***
     * 
     * 
     *    CREATE THE FOLDER DIVS
     * 
     * 
     */

    $folder =  CreateFolderList( $providerid, $mode, $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $roomid, $filename, $filtername, $passkey64 );
    
          
    $doclibtitle = "$menu_myfiles";
    $uploadfile = "uploadfile2";
    if( $caller!='') {
        $doclibtitle = "$menu_selectfile";
        //$uploadfile = "uploadfilefromselect";
        $uploadfile = "uploadfile2";
    }
    if($mode == 'CF'){
        $doclibtitle = 'Move to File Folder';
    }
    $upload = '';
    //if($caller!=='roomfile' && $mode!='CF')
    
    /***************************************************************
     * 
     * 
     * 
     *   START OF DISPLAY 
     * 
     * 
     ****************************************************************/
    
    $action = "feed";
    if(intval($_SESSION['profileroomid'])==0){
        $action = "userview";
    }
    
    if($caller=='roomfileedit'){
        
        echo 
        "<div class='' style='
         background-color:$global_background;color:$global_textcolor;
         padding-top:0px;max-width:100%;
         '>
            <div class='' style='background-color:white;padding:10px'>
         ";

         /*
            <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;padding-top:0px;margin:0;' >
                <img class='icon20 roomfiles mainbutton' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                    style='' data-roomid='$roomid' data-caller='none' />
                &nbsp;
                <span style='opacity:.5'>
                $icon_braxdoc2
                </span>
                <span class='pagetitle2a' style='color:white'>$menu_myfiles</span> 
            </div>
        */
    } else 
    if($caller!=='chat'){
        
        echo 
        "<div class='' style='
         background-color:$global_background;color:$global_textcolor;
         padding-top:0px;max-width:100%;
         '>
            <div class='' style='background-color:$global_background;padding:10px'>
         ";
    } else {
        
        echo 
        "<div class='' style='
         background-color:$global_background;color:$global_textcolor;
         padding-top:0px;max-width:100%;
         '>
            <div class='' style='background-color:$global_background;padding:10px'>
         ";
        
    }
    
    RefreshButton( $filtername, $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $passkey64, $upload, $mode, $filename );

    echo "     
            </div>
         ";
  
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    // SINGLE FILE DISPLAY
    //*************************************************************
    //*************************************************************
    //*************************************************************
    
    if( $mode == 'V' || $mode == 'VE'){
    
        ShowFile( false, $providerid, $mode, $filename, $altfilename, $target, $caller, $page, $sort, $selectedfolder, $selectedfolderid, $roomid );
        echo "</div>";
        exit();

    }
    
    /**********************************************************
     * 
     *   CHANGE FOLDER DISPLAY
     * 
    **********************************************************/
    if($mode == 'CF'){
        
        $result = pdo_query("1",
            "
                select origfilename, filename, folder, folderid, alias, views, filetype, filesize, title,
                date_format( date_add(createdate,INTERVAL ($_SESSION[timezoneoffset])*60 MINUTE),'%m/%d/%y %h:%i%p') as createdate,
                createdate as createdate2, encoding
                from filelib where providerid = ? and 
                filename=? and status='Y'
            ",array($providerid,$filename)
            );
        
        if($row = pdo_fetch($result)){
        
            $encoding = $row['encoding'];
            $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
        }
        
        echo "
                <br><br>
                &nbsp;&nbsp;<img class='doclib1 tapped2 icon30' src='$iconsource_braxarrowleft_common' 
                    style='display:inline'
                    
                    title='$origfilename'
                        
                    data-page='$page'
                    data-sort='$sort'
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-filename='$filename'
                    data-altfilename='$filename'  
                    data-target='$target' 
                    data-caller='$caller' 
                    data-roomid='$roomid'
                    data-passkey64='$passkey64'
                    />
                    Back to $origfilename
                    <br><br><br>
                    <b>&nbsp;&nbsp;Choose a Destination Folder</b>
                    <br><br>
                    <div class=gridstdborder style='cursor:pointer;max-width:100%:width:100%;height:auto;word-break;break-all;word-wrap:break-word;margin:0;background-color:whitesmoke'>
                        <br>
                        $folder->div
                        <br><br>
                    </div>
            ";        
            ShowFile( true, $providerid, $mode, $filename, $altfilename, $target, $caller, $page, $sort, $selectedfolder, $selectedfolderid, 0 );
            echo "</div>";
            exit();


    }
    /**********************************************************
     * 
     *   DISPLAY FOR ALL
     * 
    **********************************************************/
    if($mode == '' || $mode == 'R'){
    
        
        echo "
            <div class='gridnoborder innerpage' style='background-color:$global_background;overflow:hidden'>
            <b style='color:steelblue'>$statusMessage</b>
            ";
        if( $caller== "roomfile" || $caller== "roomfileedit"){
        
            echo "
                <img class='icon20 roomfiles tapped' src='$iconsource_braxarrowleft_common' 
                    data-page='$page' 
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-roomid='$roomid'
                    data-roomfolderid='$roomfolderid'
                    data-passkey64='$passkey64'
                    style='' /> $menu_back
                <br><br>
                ";
        } else 
        if( $caller== "casefile"){
        
            echo "
                <img class='icon20 casefiles tapped' src='$iconsource_braxarrowleft_common' 
                    data-page='$page' 
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-caseid='$roomid'
                    data-casefolderid='$roomfolderid'
                    data-passkey64='$passkey64'
                    style='' /> $menu_back
                <br><br>
                ";
        }
        else
        if( $caller!= ""){
        
            echo "
                <br><br>
                &nbsp;&nbsp;<img class='icon20 fileselect tapped2' src='$iconsource_braxarrowleft_common' 
                    data-page='$page' 
                    data-sort='$sort'
                    data-target='$target' 
                    data-caller='$caller' 
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-roomid='$roomid'
                    data-passkey64='$passkey64'
                    style='' />
                    $menu_back
                <br><br>
                ";
        }
        
        //New Folder
        echo "
            <div class='filefolderoptions' style='float:right;padding-right:20px;display:inline;cursor:pointer;color:$global_activetextcolor;'>$menu_newfolder&nbsp;</div>

            <div class='filefolderoptionsarea mainfont' style='display:none;color:$global_textcolor'>
                $menu_newfolder<br>
                <input class='dataentry mainfont file_newfolder' placeholder='$menu_name' type=text size=27 maxlength='30' value='' style='max-width:200px' />
                <img class='icon25 doclib tapped2' 
                    data-page='$page' 
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-roomid='$roomid'
                    data-filename=''  
                    data-folder='$selectedfolder'
                    data-passkey64='$passkey64'
                    data-mode='F'
                    src='$iconsource_braxarrowright_common' 
                    style='' />
                <br><br><br>
            </div>
            ";
        
    }
    
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    // FULL FILE LIST
    //*************************************************************
    //*************************************************************
    //*************************************************************
    
    
    
    


    echo "                </div>
        ";


    
    
    /* change Encryption */
    /*
    $result = pdo_query("1",
        "
            select origfilename, filename, title, encoding
            from filelib 
            
        ");
    
    while($row = pdo_fetch($result))
    {
        $encoding = $row['encoding'];
        $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
        $title = DecryptText($row['title'], $encoding, $row['filename'] );
        
        $temp = nl2br( stripslashes($origfilename));
        $neworigfilename = base64_encode( strtolower($temp) );
        
        $temp = nl2br( stripslashes($title));
        $newtitle = base64_encode( $temp );
        
        pdo_query("1","update filelib set origfilename = '$origfilename', title='$title', encoding='PLAINTEXT' 
            where filename ='$row[filename]' 
                ");
    }
     * 
     */
    
    
    /******************************************
     * 
     * 
     *   FOLDER DISPLAY
     * 
     * 
     * 
     */
    
    echo $folder->container;
    $time2 = microtime(true);
    
    echo SortButtons($sort, $target, $caller, $filtername, $selectedfolder, $selectedfolderid, $roomfolderid, $passkey64 );
    
    echo PagingButtons( $filtername, $pagedisplay, $page, $pagenext, $pageprev, 
        $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $passkey64);
    
    $limit = "";
    if($filtername==''){
        $limit = " limit $pagestart, $max";
    }
    $result = pdo_query("1",
        "
            select origfilename, filename, folder, folderid, alias, 
            views, filetype, filesize, title, pin,
            date_format( 
                date_add(createdate,INTERVAL ($_SESSION[timezoneoffset])*60 MINUTE),
                '%m/%d/%y %h:%i%p') as createdate,
            createdate as createdate2, encoding
            from filelib 
            where providerid = ? and 
            (
                folderid='$_SESSION[filefolderid]' and '$filtername'=''
                or
                '$filtername'!=''
                or (pin ='Y' and '0'=?)
            ) and status='Y'
            order by pin desc, $sort_text
            $limit
        ",array($providerid,$selectedfolderid));

    
    /****************
     * 
     * 
     *   START OF FILE LIST BLOCK
     * 
     ****************/
    echo "<div style='padding-top:0;padding-bottom:30px;margin:auto;text-align:center;background-color:$global_background;vertical-align:top'><br>";
    if($caller=='roomfile'){
        
        echo "
            <span class='filebatchaction' style='display:none'>
                <div class='pagetitle3 roomfileselectgroup gridstdborder rounded' style='padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px;display:inline-block;cursor:pointer;background-color:$global_titlebar_color;color:white;text-align:left'>
                        <b>Select Highlighted</b>
                </div>
                &nbsp;&nbsp;&nbsp;
                <div class='pagetitle3 doclibselectrowcancel gridstdborder rounded' style='padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px;display:inline-block;cursor:pointer;background-color:$global_titlebar_color;color:white;text-align:left'>
                    Cancel
                </div>
                <br><br>
            </span>
            ";
        
    } else {
        
        echo "
            <span class='filebatchaction' style='display:none'>
                <div class='pagetitle3 casefileselectgroup gridstdborder rounded' style='padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px;display:inline-block;cursor:pointer;background-color:$global_titlebar_color;color:white;text-align:left'>
                    <b>Share Selected Items</b>
                </div>
                &nbsp;&nbsp;&nbsp;
                <div class='pagetitle3 doclibselectrowcancel gridstdborder rounded' style='padding-left:20px;padding-right:20px;padding-top:10px;padding-bottom:10px;display:inline-block;cursor:pointer;background-color:$global_titlebar_color;color:white;text-align:left'>
                    Cancel
                </div>
                <br><br>
            </span>
            ";
        
    }
    
    $count = 0;
    
    while($row = pdo_fetch($result)){
        
        $createdate = InternationalizeDate($row['createdate']);
    
        $folder = $row['folder'];
        $folderid = $row['folderid'];
        $encoding = $row['encoding'];
        $origfilename = DecryptText($row['origfilename'], $encoding, $row['filename'] );
        $title = DecryptText($row['title'], $encoding, $row['filename'] );
        if($title == ''){
            $title = $origfilename;
        }
        
        if(!MatchName($filtername, $origfilename, $title)){
            continue;
        }
        $count++;
        if($filtername!='' && $count > 300){
            echo "<br><b>Too many results. Narrow down your search criteria.</b>";
            break;
        }
        if($title!=''){
           $origfilename = $title;
        }
        
        $icon = GetFileTypeIcon( $row['filetype']);           
        

        $shorttitle = substr($title,0,25);
        if(strlen($title)>25){
        
            $shorttitle .="...";
        }
        
        $shortname = substr($origfilename,0,25);
        if(strlen($origfilename)>25){
        
            $shortname .="...";
        }
        $foldertext='';
        if($filtername!=''){
           $foldertext = $row['folder']."/<br>"; 
        }
        
        $selectaction = 'doclib1';
        if($caller=='roomfile'){
            $selectaction = '';
        }
        $filesize = $row['filesize'];
        if($row['filesize']>1000000){
            $filesize = round($row['filesize']/1000000,1)."MB";
        }
        $filecolor = $global_textcolor;
        if($row['pin']=='Y'){
            $filecolor = $global_activetextcolor;
        }
        
        
        echo "
             <div  class='doclibfilerow fileboxwidth'
                style='
                display:inline-block;
                word-break:break-all;
                padding-left:20px;padding-bottom:10px;vertical-align:top;text-align:left;max-width:90%' 
                >
             <div class='$selectaction tapped2' 
                title='$origfilename'

                data-page='$page'
                data-sort='$sort'
                data-folder='$folder'
                data-folderid='$folderid'
                data-roomfolderid='$roomfolderid'
                data-filename='$row[filename]'
                data-altfilename='$row[filename]'  
                data-target='$target' 
                data-caller='$caller' 
                data-roomid='$roomid'
                data-passkey64='$passkey64'
                data-mode='V'
                style='
                cursor:pointer;vertical-align:top;;
                '
                >
                
                <img class='icon30' src='$icon' style='float:left;padding-right:10px;padding:bottom:5px;margin:0'/>
                <div
                    class='smalltext2'
                    style='
                    float:left;
                    display:inline-block;
                    text-align:left;
                    margin-auto;
                    background-color:transparent;
                    padding-top:10px;
                    word-break:break-all;
                    vertical-align:top;
                    color:$filecolor;'
                    >
                    <b class='smalltext' style='color:$filecolor'>$foldertext$shortname</b><br>
                    $createdate<br>$filesize ($row[views])
                </div>
            </div>
                ";
        
        echo fileListButtons( 
            $origfilename, $page, $sort, $folder,
            $folderid, $roomfolderid, $row['filename'], $target, $caller, $roomid, "150px"
            );

        
        echo "
            </div>
                ";

    }
    /****************
     * 
     * 
     *   END OF FILE LIST BLOCK
     * 
     ****************/
    
    
    if($count > 0){
        echo PagingButtons( $filtername, $pagedisplay, $page, $pagenext, $pageprev, 
            $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $passkey64);
    }

        echo "</div>";

    $result = pdo_query("1",
        "
            select sum(filesize) as totalsize, count(*) as filecount
            from filelib where providerid = ?
        ",array($providerid));
    $row = pdo_fetch($result);
    $totalsize = round($row['totalsize']/1000000,1);
    $filecount = $row['filecount'];
    
    $result = pdo_query("1",
        "
            select sum(filesize*views) as bandwidth
            from fileviews where providerid = ? 
        ",array($providerid));
    $row = pdo_fetch($result);
    
    $bandwidth = round($row['bandwidth']/1000000000,1);
    
    
    
    /*
    if($count == 0 && $folder->count == 0 && $selectedfolder!="" && $filtername == ''){
            echo DeleteFolder($folderode, $sort, $caller, $roomid, $page, $target, $selectedfolder, $selectedfolderid);
    }
     * 
     */
    if( $selectedfolder!="" && $filtername == ''){
            echo DeleteFolder($sort, $caller, $roomid, $page, $target, $selectedfolder, $selectedfolderid);
    }
    else
    if($filecount == 0  ) {
        echo RoomTips();
    }

    if($count > 0){
    echo "
        </div>
        <div class='smalltext' style='padding:10px;background-color:white'>
        $count files in folder
        <br><br>
        Total Space Used: $totalsize MB<br>
        Total Bandwidth Used: $bandwidth GB
        <br><br>
        <div class='privacytip smalltext' style='padding:20px;margin:auto;cursor:pointer;color:firebrick;text-align:center'>
        <b>Privacy Tips</b>
        </div>
            
        </div>
        ";
    }
    $time3 = microtime(true);

    $e1 = $time2 - $time1;
    $e2 = $time3 - $time1;

        

    if($providerid==$admintestaccount){

        echo " <br><br>
            1 $e1
            <br>2 $e2

        ";
    }
    //*************************************************************
    //*************************************************************
    //*************************************************************

    
    echo "</div></div>";
    
function GetFileTypeIcon( $filetype )
{
            $icon = '../img/flat_other.png';
            if( $filetype=='jpg'){
            
                $icon = '../img/flat_camera.png';
            }    
            if( $filetype=='png' ||
                $filetype=='gif' ||
                $filetype=='tif' ||
                $filetype=='tiff' ||
                $filetype=='bmp' 
            ){
            
                $icon = '../img/flat_photo.png';
            }    
            if( $filetype=='mp3' ||
                $filetype=='m4a' ||
                $filetype=='m4p' 
            ){
            
                $icon = '../img/flat_mp3.png';
            }    
            if( $filetype=='wav'){
            
                $icon = '../img/flat_wav.png';
            }    
            if( $filetype=='zip'){
            
                $icon = '../img/flat_zip.png';
            }    
            if( $filetype=='ppt' ||
                $filetype=='pptx' ){
            
                $icon = '../img/flat_ppt.png';
            }    
            if( $filetype=='xls' ||
                $filetype=='xlsx' ){
            
                $icon = '../img/flat_doc.png';
            }    
            if( 
                $filetype=='txt' ||
                $filetype=='html' ||
                $filetype=='htm' ||
                $filetype=='css' ){
            
                $icon = '../img/flat_doc.png';
            }    
            if( $filetype=='doc' ||
                $filetype=='docx' ||
                $filetype=='pages' ){
            
                $icon = '../img/flat_word.png';
            }    
            
            
            if( $filetype=='pdf'){
            
                $icon = '../img/flat_pdf.png';
            }    
            if( $filetype=='mp4' ||
                $filetype=='m4v' ||
                $filetype=='mov'){
            
                $icon = '../img/flat_movie.png';
            }    
            if( $filetype=='exe' ||
                $filetype=='js' ||
                $filetype=='php' ||
                $filetype=='py' ){
            
                $icon = '../img/flat_exec.png';
            }    
            
    return $icon;
}

function ShowFile( $displayOnly, $providerid, $mode, $filename, $altfilename, $target, $caller, $page, $sort, $selectedfolder, $selectedfolderid, $roomid  )
{
    global $installfolder;
    global $rootserver;
    global $appname;
    global $prodserver;
    global $passkey64;
    global $global_activetextcolor;
    global $menu_filename;
    global $menu_title;
    global $menu_back;
    global $global_textcolor;
    global $iconsource_braxarrowleft_common;
    global $iconsource_braxarrowright_common;
    global $iconsource_braxsettings_common;
    global $iconsource_braxdownload_common;
    global $iconsource_braxshare_common;
    global $iconsource_braxclose_common;
    //$shareserver = "$rootserver";
    //if($providerid == $admintestaccount){
    $shareserver = "$prodserver";
    //}
    
        $result = pdo_query("1",
            "
                select origfilename, filename, folder, alias, views, filetype, filesize, title,
                date_format( date_add(createdate,INTERVAL ($_SESSION[timezoneoffset])*60 MINUTE),'%b %d, %Y %h:%i%p') as createdate,
                createdate as createdate2, encoding, fileencoding
                from filelib where providerid = ? and 
                (filename=? or filename=?) and status='Y'
            ",array($providerid,$filename,$altfilename)
            );
        
        if($row = pdo_fetch($result)){
        
            $filesize = $row['filesize'];
            $encoding = $row['encoding'];
            $fileencoding = $row['fileencoding'];
            $filename = "$rootserver/$installfolder/$row[folder]$row[filename]";
            $createdate = $row['createdate'];
            $mp3 = "";
            $mp3link = "";
            $streamlink = "";
            $alias = rawurlencode(htmlentities($row['alias'],ENT_QUOTES));
            $origfilenameDisplay = DecryptText($row['origfilename'], $encoding, $row['filename'] );
            $origfilename = rawurlencode(htmlentities(DecryptText($origfilenameDisplay, $encoding, $row['filename']),ENT_QUOTES)); 
            $download = "$shareserver/f/$origfilename/$alias";
            $title = DecryptText($row['title'], $encoding, $row['filename'] );
            $title2 = str_replace(" ","-",$title);
            $title2 = rawurlencode($title2);
            
            if($origfilename == $title2){
            
                //$title2='';
            }
            if($title == ''){
                $title = rawurldecode($origfilename);
            }
            //if($fileencoding!='PLAINTEXT'){
                $streamUrl = "$rootserver/$installfolder/doc.php?p=".$row['alias']."&i=Y";
            //} else {
            //    $streamUrl = getAWSObjectUrlShortTerm( $row['filename'] );
            //}
            
            if( $row['filetype']=='mp3'){
            
                $mp3 = "$shareserver/$installfolder/soundplayer.php?p=$row[alias]&f=$origfilename&t=$title2";
                if($_SESSION['mobiledevice']=='P' || $_SESSION['mobiledevice']=='T'){
                    $download = $mp3;
                }
                $fbmp3 = "http://www.facebook.com/sharer.php?u=$shareserver/$installfolder/soundplayer.php?p=$alias";
                $gpmp3 = "https://plus.google.com/share?url=$shareserver/$installfolder/soundplayer.php?p=$alias";
                $mp3link = "<br><br><div class='divbutton4 divbutton4_unsel fileselect' 
                    data-link='$mp3' data-linkfb='$fbmp3' data-linkgp='$gpmp3'  data-passkey64='$passkey64' data-target='$target' data-caller='$caller' data-title='Mp3 Streaming Link for $origfilename'>
                        <img src='../img/link-gray-128.png' style='position:relative;top:3px;height:15px;' /> Streaming
                        </div>
                    </div>";
                $streamlink = "<br><br><br>
                    <div class='divbutton3 divbutton3_unsel fileselect' 
                        data-link='$mp3' data-linkfb='$fbmp3' data-linkgp='$gpmp3'  data-passkey64='$passkey64' data-target='$target' data-caller='$caller' data-title='Mp3 Streaming Link for $origfilename'>
                        <img src='../img/link-gray-128.png' style='position:relative;top:3px;height:15px;' /> 
                        Share Stream
                    </div>
                    <br><br><br>";
            }
            if( $row['filetype']=='mov' ||  $row['filetype']=='mp4' ){
            
                
                $mov = "$shareserver/$installfolder/videoplayer.php?p=$alias&f=$origfilename&t=$title2";
                if($_SESSION['mobiledevice']=='P' || $_SESSION['mobiledevice']=='T'){
                    $download = $mov;
                }
                
                $mov2 = "$shareserver/$installfolder/videoembed.php?p=$alias";
                $streamlink = "<br><br><br>
                    <div class='divbutton3 divbutton3_unsel fileselect' 
                        data-link='$mov'  data-target='$target' data-caller='$caller'  data-passkey64='$passkey64' data-title='MOV Streaming Link for $origfilename'>
                        <img class='icon20' src='../img/link-gray-128.png' style='' /> 
                        Share Stream
                    </div>
                    <br><br><br>";
            }
            
            
            $icon = GetFileTypeIcon( $row['filetype']);           
            if( 
                $row['filetype']=='jpg' ||
                $row['filetype']=='jpeg' || 
                $row['filetype']=='png' || 
                $row['filetype']=='tif' || 
                $row['filetype']=='tiff' || 
                $row['filetype']=='gif'){
            
                $icon = "$rootserver/$installfolder/doc.php?p=".$row['alias']."&i=Y";
                if($fileencoding!='PLAINTEXT'){
                    //$icon = "$rootserver/$installfolder/doc.php?p=".$row['alias']."&i=Y";
                } else {
                    //$icon = getAWSObjectUrl($row['filename']);
                }
                $docdisplay = "
                        <img class='photoview icon50' src='$icon' data-filename='$icon' style='cursor:pointer;' />
                            <br><br>
                           ";
            }
            else
            if( $row['filetype']=='pdfx' || $row['filetype']=='pdf' ){
            
                $docdisplay = '';
                /*
                $docdisplay = "
                        <!--<embed class='fileedit' src='$streamUrl' width='600' height='500' alt='pdf' pluginspage='https://www.adobe.com/products/acrobat/readstep2.html'>-->                        
                        <object class='fileedit' data='$streamUrl' type='application/pdf' width='90%' height='400px' style='cursor:pointer;' >
                            <embed class='fileedit' src='$streamUrl' type='application/pdf' style='cursor:pointer;max-height:400px;max-width:90%' />
                        </object>
                           ";
                 * 
                 */
                
            } else
            if( $row['filetype']=='mov' ||  $row['filetype']=='mp4' ){
            
                $docdisplay = "
                <span class='nonmobile'>
                <center>
                    <video src='$streamUrl' preload='metadata' height='300' width='640' controls />            
                    <br>
                </center>
                </span>
                    ";
            } else
            if($row['filetype']=='mp3'){
            
                $docdisplay = "
                <center>
                    <script>
                    audiojs.events.ready(function() 
                    {
                        var as = audiojs.createAll();
                    });
                    </script>
                    <audio src='$streamUrl' preload='none' />            
                </center>
                ";
            } else         
            if($row['filetype']=='txt' || 
               $row['filetype']=='sql' || 
               $row['filetype']=='xml' || 
               $row['filetype']=='css' || 
               $row['filetype']=='php' || 
               $row['filetype']=='py' || 
               $row['filetype']=='c' || 
               $row['filetype']=='cpp' || 
               $row['filetype']=='cxx' || 
               $row['filetype']=='cc' || 
               $row['filetype']=='c++' || 
               $row['filetype']=='dlg' || 
               $row['filetype']=='mak' || 
               $row['filetype']=='html' || 
               $row['filetype']=='htm' || 
               $row['filetype']=='js' ||
               $row['filetype']=='inc' || 
               $row['filetype']=='h' || 
               $row['filetype']=='que' || 
               $row['filetype']=='log' || 
               $row['filetype']=='pem' || 
               $row['filetype']=='key' || 
               $row['filetype']=='csv' || 
               $row['filetype']=='csr' || 
               $row['filetype']=='crt' || 
               $row['filetype']=='cer' || 
               $row['filetype']=='p7b' || 
               $row['filetype']=='keystore' || 
               $row['filetype']=='json'){

                if($row['fileencoding']!='PLAINTEXT'){
                    if($filesize < 1024000){
                        $txtdata =  substr(getAWSObjectStreamEncryptedContent( $row['filename'], $row['fileencoding'], 0xFFFFF, $filesize ),0, $filesize);
                        
                    } else {
                        $txtdata = "File too big to display. Download to view.";
                    }
                } else {
                    if($filesize < 1024000){
                        $txtdata = file_get_contents($streamUrl);
                    } else {
                        $txtdata = "File too big to display. Download to view.";
                    }
                }
                $docdisplay = TextEditor($sort, $caller, $roomid, $page, $target, $selectedfolder, $selectedfolderid, "TEXTEDIT", $row['filename'],$txtdata);
                
            } else {
                
                $docdisplay = "
                        <img class='fileedit' src='$icon' style='cursor:pointer;max-height:75px;max-width:90%' />
                           ";
                
            }
            
            if($displayOnly){
                echo $docdisplay;
                return;
            }
            
            if( $caller!= ""){
            
                if($caller!='roomfileedit'){
                echo "
                    &nbsp;&nbsp;&nbsp;&nbsp;<img class='icon25 doclib tapped' src='$iconsource_braxarrowleft_common' 
                        data-page='$page' 
                        data-sort='$sort' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-passkey64='$passkey64'
                        data-mode='BACK'
                        style='' />
                    <div class='file_not_editarea' style='color:$global_textcolor;padding:20px'>
                        $origfilenameDisplay<br>
                        $title<br>
                    </div>
                    ";
                }
                echo "
                    <div style='text-align:center;margin:auto'>
                        $docdisplay

                        <!-- <img class='fileedit' src='$icon' style='cursor:pointer;height:120px' /><br>
                        -->
                        <div class='file_not_editarea'>
                        <!--
                        $origfilenameDisplay<br>
                        $title<br>
                        -->
                        $row[createdate]<br>
                        </div>
                        ";
                echo "
                    <br>
                    $streamlink
                    <div class='divbutton3 divbutton3_unsel fileselect' data-link='$download ' data-target='$target' data-caller='$caller'  data-passkey64='$passkey64' >
                    Share Download Link
                    </div>
                    <br><br>
                    ";
                
            }
            if( $caller == ""){
            
                $selectedfolderdisplay = $selectedfolder;
                if($selectedfolderdisplay == ''){
                
                    $selectedfolderdisplay = '/';
                }
                echo "
                    &nbsp;&nbsp;&nbsp;&nbsp;<img class='icon25 doclib tapped' src='$iconsource_braxarrowleft_common' 
                        data-page='$page' 
                        data-sort='$sort' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-passkey64='$passkey64'
                        data-mode='BACK'
                        style='' />
                        $menu_back
                    <div style='text-align:center;margin:auto'>
                    
                        <div class='fileedit' 
                            style='color:steelblue;cursor:pointer;display:inline-block;padding:10px;margin:5px'
                            >
                            <img class='icon25' src='$iconsource_braxsettings_common' />
                       </div>
                        <a href='$download' target='_blank'  
                            style='cursor:pointer;text-decoration:none;color:black'>
                            <div
                                style='color:steelblue;height:20px;cursor:pointer;display:inline-block;
                                padding-top:0px;
                                padding-left:10px;
                                padding-right:10px;
                                padding-bottom:10px;
                                margin:5px;'
                                >
                                <img class='icon25' src='$iconsource_braxdownload_common' />
                            </div>
                        </a>
                        <div class='doclibchangefolder tapped' 
                                style='color:steelblue;height:20px;cursor:pointer;display:inline-block;
                                padding-top:0px;
                                padding-left:10px;
                                padding-right:10px;
                                padding-bottom:10px;
                                margin:5px;'
                            data-page='$page' 
                            data-sort='$sort' 
                            data-target='$target' 
                            data-caller='$caller' 
                            data-filename='$row[filename]'  
                            data-folder='$selectedfolder'
                            data-folderid='$selectedfolderid'
                            data-passkey64='$passkey64'
                            data-mode='CF'>
                                <img class='icon25' src='$iconsource_braxshare_common' />
                        </div>
                        <div class='doclib tapped'  
                                style='color:$global_activetextcolor;height:20px;cursor:pointer;display:inline-block;
                                padding-top:0px;
                                padding-left:10px;
                                padding-right:10px;
                                padding-bottom:10px;
                                margin:5px;'
                            data-filename='$row[filename]' 
                            data-origfilename='$row[origfilename]']
                            data-mode='D' 
                            data-page='$page'  
                            data-sort='$sort'
                            data-target='$target' 
                            data-caller='$caller' 
                            data-folder='$selectedfolder' 
                            data-folderid='$selectedfolderid' >
                                <img class='icon25' src='$iconsource_braxclose_common' />
                        </div>
                        <!--
                        <div class='filesend' 
                            style='color:$global_activetextcolor;cursor:pointer;display:inline-block;padding:10px;margin:5px'
                            >
                            Send
                        </div>
                        -->
                        ";
                    if($row['filetype']=='jpg'){
                    
                        echo "
                        <div class='doclib tapped'  
                                style='color:$global_activetextcolor;height:20px;cursor:pointer;display:inline-block;
                                padding-top:0px;
                                padding-left:10px;
                                padding-right:10px;
                                padding-bottom:10px;
                                margin:5px;'
                            data-filename='$row[filename]' 
                            data-mode='BW' 
                            data-page='$page'  
                            data-sort='$sort'
                            data-target='$target' 
                            data-caller='$caller' 
                            data-folder='$selectedfolder' 
                            data-folderid='$selectedfolderid' 
                            data-passkey64='$passkey64'
                                >
                                Convert to B&W
                        </div>
                        
                        ";
                    }
                    echo "<br>";
                
                if($mode == 'V'){
                    echo    "
                    <div class='file_editarea' style='display:none;text-align:center;margin:auto'>
                        ";
                }
                if($mode == 'VE'){
                    echo    "
                    <div class='file_editarea' style='text-align:center;margin:auto'>
                        ";
                }
                
                echo    "
                         <br>
                         <br>
                         
                         <table class='gridnoborder mainfont' style='color:$global_textcolor;margin:auto;max-width:500px'>
                            <tr><td>
                            $menu_filename<br>
                            <input class='mainfont dataentry filename' type='text' size=30 
                                value='$origfilenameDisplay' />
                            <br><br>
                            
                            $menu_title<br>
                            <input class='mainfont dataentry filetitle' type='text' size=30 
                                value='$title' />
                                <br><br>
                                
                            <img class='icon30 doclib' 
                               src='$iconsource_braxarrowright_common'  
                               data-page='$page' 
                               data-sort='$sort' 
                               data-target='$target' 
                               data-caller='$caller' 
                               data-filename='$row[filename]'  
                               data-folder='$selectedfolder'
                               data-folderid='$selectedfolderid'
                               data-passkey64='$passkey64'
                               data-mode='S'
                            />
                            </td></tr>
                         </table>
                         
                        <br><br>
                        
                         <br><br>
                     </div>
                ";
                
                echo    "
                    <div class='file_sendarea' style='display:none;text-align:center;margin:auto'>
                         <br>
                         Recipient's $appname Email Address or @handle<br>
                         <input class='mainfont dataentry filesendemail' type='email' size=30 
                         value='' />
                             <br><br>
                                <img class='icon20 doclib' 
                                src='../img/Arrow-Right-in-Circle_120px' style='' 
                                 data-page='$page' 
                                 data-sort='$sort' 
                                 data-target='$target' 
                                 data-caller='$caller' 
                                 data-filename='$row[filename]'  
                                 data-folder='$selectedfolder'
                                 data-folderid='$selectedfolderid'
                                data-passkey64='$passkey64'
                                 data-mode='E' />
                         <br><br>
                     </div>
                ";
                
                
                echo "
                        <div class='fileedit file_not_editarea smalltext' style='cursor:pointer;word-break:break-all' >
                        $selectedfolder/<br>$origfilenameDisplay<br>
                        $title<br>
                        </div>

                        $docdisplay
                        <br>                            
                        <div class='file_not_editarea smalltext' style='word-break:break-all' >
                        $row[createdate]<br>
                        $row[filesize]<br>
                        Views: $row[views]<br>
                            <br>
                        <span class=nonmobile>Shareable Download Link</span><br>
                        <span class=formobile><br></span>
                        <span class=nonmobile>
                            <input class='smalltext dataentry sharelink' type='text' size=50 
                            value='$download' style='max-width:300px' />
                            <br><br>
                        </span>
                        </div>
                        ";

                if( $row['filetype']=='mov' ||  $row['filetype']=='mp4' ){
                
                    echo "
                        <span class=nonmobile>
                            Video Streaming Link - Copy and Paste to Share
                            <br>
                            <input class='smalltext dataentry sharelink' type='text' size=90 
                            value='$mov'  style='max-width:300px' />
                            <br><br>
                            Embedded Streaming Link<br>
                            <input class='smalltext dataentry sharelink' type='text' size=90  style='max-width:300px'
                            value='<iframe src=\"$mov2\" width=560 height=350></iframe>' />
                        </span>        
                        <span class=formobile>
                            <a href='$mov2' style='text-decoration:none'>
                                <div class='divbuttontext'>Stream Video</div>
                            </a>
                        </span>
                        <br><br>
                        ";
                }
                
                if(
                    $row['filetype']=='m4v' 
                ){
                  
                    /*
                    echo "
                    <center>
                    <video width='356' height='200' controls poster='$rootserver/img/logo2.png'  >
                    <source src='$streamUrl' type='video/*' />
                    </video>   
                    </center>
                    ";
                     * 
                     */
                }
                if($row['filetype']=='mp3'){
                
                
                    echo "
                    <center>
                        Mp3 Streaming Link <span class=nonmobile>- Copy and Paste to Share</span><br>
                        <span class=formobile><br></span>
                        <span class=nonmobile>
                            <input class='mainfont dataentry sharelink' type='text' size=90  style='max-width:300px'
                            value='$mp3' />
                        <br><br>
                        <br><br>
                        </span>
                        <a href='$fbmp3' target='_blank'
                                style='text-decoration:none'>
                            <div class='divbutton3 divbutton3_unsel'>
                                <img class='icon15 blackandwhite' src='../img/facebook-flat.png' /> 
                            </div>
                        </a>
                        &nbsp;
                        <a href='$gpmp3' target='_blank'
                                style='text-decoration:none'>
                            <div class='divbutton3 divbutton3_unsel'> 
                                <img class='icon15 blackandwhite' src='../img/googleplus.jpg' /> 
                            </div>
                        </a>
                    </center>
                    <br><br><br><br>
                    ";
                }
                echo "                    
                        <span class='nonmobile'>
                            <div class='doclib tapped'  
                                    style='color:$global_activetextcolor;height:20px;cursor:pointer;display:inline-block;
                                    padding-top:0px;
                                    padding-left:10px;
                                    padding-right:10px;
                                    padding-bottom:10px;
                                    margin:5px;'
                                data-filename='$row[filename]' 
                                data-mode='L' 
                                data-page='$page'  
                                data-sort='$sort'
                                data-target='$target' 
                                data-caller='$caller' 
                                data-folder='$selectedfolder' 
                                data-folderid='$selectedfolderid' 
                                data-passkey64='$passkey64'
                                    >
                                    Revoke Old Link/Create New Link
                            </div>
                            <br>
                        </span>
                        <div class='doclib tapped'  
                                style='color:$global_activetextcolor;height:20px;cursor:pointer;display:inline-block;
                                padding-top:0px;
                                padding-left:10px;
                                padding-right:10px;
                                padding-bottom:10px;
                                margin:5px;'
                            data-filename='$row[filename]' 
                            data-mode='PIN' 
                            data-page='$page'  
                            data-sort='$sort'
                            data-target='$target' 
                            data-caller='$caller' 
                            data-folder='$selectedfolder' 
                            data-folderid='$selectedfolderid' 
                            data-passkey64='$passkey64'
                                >
                                Pin
                        </div>
                        <br>
                        <div class='doclib tapped'  
                                style='color:$global_activetextcolor;height:20px;cursor:pointer;display:inline-block;
                                padding-top:0px;
                                padding-left:10px;
                                padding-right:10px;
                                padding-bottom:10px;
                                margin:5px;'
                            data-filename='$row[filename]' 
                            data-mode='UNPIN' 
                            data-page='$page'  
                            data-sort='$sort'
                            data-target='$target' 
                            data-caller='$caller' 
                            data-folder='$selectedfolder' 
                            data-folderid='$selectedfolderid' 
                            data-passkey64='$passkey64'
                                >
                                Unpin
                        </div>
                <br>
                </div>";

            }
            exit();
        }    
}
function MatchName($filtername, $origfilename, $title)
{
    if($filtername == ''){
        return true;
    }
    if($origfilename == ''){
        return true;
    }
    $name = strtolower($origfilename.$title);
    $filter = strtolower($filtername);
    $status = stristr($name, $filter);
    //echo "$filter vs $name<br>";
    if($status == false){
        return false;
    }
    return true;

}

function CreateFolderList( $providerid, $mode, $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $roomid, $filename, $filtername, $passkey64 )
{
    global $global_menu2_color;
    global $global_background;
    global $iconsource_braxarrowleft_common;
    global $global_menu_color;
    
    if($filtername!=''){
        $folder['container'] = '';
        $folder['div'] = '';
        $folder['back'] = '';
        $folder['count'] = '';
    return (object) $folder;
        
    }
    
    $foldermode = '';
    if($mode == 'CF'){
        $foldermode = 'SF';
        $selectedfolderid = 0;
    }
    $result2 = pdo_query("1","
        select distinct foldername, folderid from filefolders where providerid = ?
            and parentfolderid=?
            order by foldername asc
        ",array($providerid,$selectedfolderid));
    $folderdiv = "";       
    $folderdiv2 = "";       
   if($mode == 'CF')
    {
        //define ROOT FOLDER
        $folderdiv2 .= "
            <a value='' 
                class='doclib smalltext2'
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
                    data-passkey64='$passkey64'
                    style='border-width:1px;border-color:white;border-style:solid;
                    display:inline-block;margin:0px;padding-top:5px;padding-bottom:5px ;
                    width:80px;
                    border-top-left-radius:10px; 
                    border-top-right-radius:10px; 
                    text-decoration:none;
                    padding-left:10px;
                    cursor:pointer;
                    background-color:$global_menu_color;
                    color:white;
                    '
                    >
                    <img class='icon15' src='../img/arrowhead-right-white-compact-128.png' style='position:relative;top:3px;padding:0;margin:0' />
                /
            </a>

            <br>
                ";
        
    }
    $folderdivback = "
        <a value='' style='cursor:pointer;display:inline;width:50px;text-align:left;vertical-align:top'
            class='doclib smalltext2 tapped2'
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
                data-passkey64='$passkey64'
                >

            <img class='icon25' src='$iconsource_braxarrowleft_common' style='' />
        </a> 
            ";
    $foldercount = 0;
    

    while( $row2 = pdo_fetch($result2))
    {
        $foldername_short = substr($row2['foldername'],0,15);
        if(strlen($row2['foldername'])>15){
            $foldername_short .= "...";
        }
        $folderdiv .= "
            <div value='$row2[foldername]' 
                style=''
                class='doclib smalltext2 tapped2 doclibbutton'
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
                    data-passkey64='$passkey64'
                    >
                
                <img class='icon20' src='../img/folder-closed-01-128.png' style='' />
                <br>
                $foldername_short
            </div>        
                ";
        $folderdiv2 .= "
            <a value='$row2[foldername]' 
                class='doclib smalltext2'
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
                    data-passkey64='$passkey64'
                    style='border-width:1px;border-color:whitesmoke;border-style:solid;
                    display:inline-block;margin:0px;padding-top:5px;padding-bottom:5px;
                    width:150px;
                    min-width:12%;
                    border-top-left-radius:10px; 
                    border-top-right-radius:10px; 
                    border-color:gray;
                    text-decoration:none;
                    padding-left:3px;
                    cursor:pointer;
                    background-color:$global_menu_color;vertical-align:middle;
                    color:white;
                    '
                    >
                    <img class='icon15' src='../img/arrowhead-right-white-compact-128.png' style='position:relative;top:3px;padding:0;margin:0' />
                /$foldername_short
            </a>
                ";
        
        $foldercount++;
    }
    if($foldercount == 0 && $selectedfolderid==0){
        $folder['container']='';
        $folder['div'] = "";
        $folder['back'] = "";
        $folder['count'] = "";
    return (object) $folder;
    }
    
    $folder['container'] =
            "
        <div class='gridnoborder' style='margin:auto;background-color:$global_background;color:black;vertical-align:top;text-align:center'>
        <div style='height:auto;word-break;break-all;word-wrap:break-word;margin:auto;vertical-align:top'>
        ";
    
        if($selectedfolder==''){
            $tablelength = $foldercount * 130;

            $folder['container'] .=
            " 
            <div class='gridnoborder tabcontainer' style='background-color:$global_background;max-width:100%;overflow:hidden;text-align:left;margin:0;vertical-align:top;'>
                <div class='gridnoborder tabwrapper' style='width:$tablelength px;overflow-x:hidden;overflow-y:visible;text-align:left;padding-left:10px;padding-right:10px'>
                    <div class='tablist' id='myTab' style='height:80px;margin:0;text-align:left;padding:0'>
                        $folderdiv2
                    </div>
                </div>
                <div class='tabenlarge' style='cursor:pointer;padding-left:10px;padding-top:10px;padding-bottom:10px'><img class='icon15' src='../img/arrowhead-down-gray-128.png' /> </div>
                <div class='tabshrink' style='display:none;cursor:pointer;padding-left:10px;padding-top:10px;padding-bottom:10px'><img class='icon15' src='../img/arrowhead-up-gray-128.png' /> </div>
            </div>

            ";
        } else {
            $folder['container'] .=
                "
                    <div style='padding-left:20px;padding-top:0px'>
                        <br>
                        $folderdivback&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class='smalltext'
                            style='border-width:1px;border-style:solid;
                            display:inline-block;margin:0px;padding-top:10px;padding-bottom:10px ;width:220px;min-width:150px;
                            border-top-left-radius:10px; 
                            border-top-right-radius:10px; 
                            border-color:gray;
                            text-decoration:none;
                            padding-left:10px;
                            cursor:pointer;
                            background-color:$global_menu_color;
                            color:white;'
                        >
                            /$selectedfolder
                        </div>
                    </div>
                    <br><br>
                ";
        }
        
    $folder['container'] .=
        "</div></div>";
    if($foldercount == 0){
        //$folder['container']='';
    }
    
    $folder['div'] = $folderdiv2;
    $folder['back'] = $folderdivback;
    $folder['count'] = $foldercount;
    return (object) $folder;
             
}
function PagingButtons( $filtername, $pagedisplay, $page, $pagenext, $pageprev, 
            $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $passkey64 )
{
    global $iconsource_braxarrowdown_common;
    global $iconsource_braxarrowup_common;
    
    if($filtername!=''){
        return "";
    }
    return "
                    <input id='photolibpage' class='photolibpage' type=hidden value='$page' />
                    <br><br>
                    <img class='doclib tapped icon25' id='prevfilepage' 
                        src='$iconsource_braxarrowup_common' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:35px;
                        margin-left:10px;
                        background-color:transparent;
                        data-page='$pagenext' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-mode=''  
                        data-sort='$sort' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-passkey64='$passkey64'
     
                    />
                    <img class='doclib tapped icon25' id='nextfilepage' 
                        src='$iconsource_braxarrowup_common' 
                        style='cursor:pointer;float:right;
                        padding-left:15px;
                        padding-right:5px;
                        background-color:transparent;
                        data-page='$pageprev' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-mode=''  
                        data-sort='$sort' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-passkey64='$passkey64'                        
                    />
                    <br>
                    <div class='smalltext' style='position:relative;top:3px;display:inline;float:right'>
                        Show $pagedisplay
                    </div>
     ";
}

function SortButtons($sort, $target, $caller, $filtername, $selectedfolder, $selectedfolderid, $roomfolderid, $passkey64 )
{
    global $menu_sortbydate;
    global $menu_sortbyname;
    global $menu_sortbysize;
    global $global_textcolor;
    global $global_background;
    global $global_activetextcolor;
    global $global_titlebar_color;
    
    $checked1 = "";
    $checked2 = "";
    $checked3 = "";
    $sortcolor = "$global_activetextcolor";
    $unsortedcolor = "gray";
    
    if( $sort == "" || $sort == "createdate desc")
    {
        $sort_text = "createdate2 desc";
        $checked1 = "checked=checked";
        $color1 = $sortcolor;
        $color2 = $unsortedcolor;
        $color3 = $unsortedcolor;
        $textcolor1 = 'white';
        $textcolor2 = $global_textcolor;
        $textcolor3 = $global_textcolor;
    }
    if( $sort == "filename")
    {
        $sort_text = "title, origfilename";
        $checked2 = "checked=checked";
        $color1 = $unsortedcolor;
        $color2 = $sortcolor;
        $color3 = $unsortedcolor;
        $textcolor1 = $global_textcolor;
        $textcolor2 = 'white';
        $textcolor3 = $global_textcolor;
    }
    if( $sort == "filesize desc")
    {
        $sort_text = "filesize desc";
        $checked3 = "checked=checked";
        $color1 = $unsortedcolor;
        $color2 = $unsortedcolor;
        $color3 = $sortcolor;
        $textcolor1 = $global_textcolor;
        $textcolor2 = $global_textcolor;
        $textcolor3 = 'white';
    }
    
    $text = 
            "
                    <div class='doclib smalltext2 gridnoborder' 
                        data-page='1' 
                        data-mode='' 
                        data-filename='' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-passkey64='$passkey64'                        
                        data-sort='createdate desc'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:27%;
                        background-color:$color1;color:$textcolor1'
                    >
                        $menu_sortbydate
                    </div>
                    <div class='doclib smalltext2 gridnoborder' 
                        data-page='1' 
                        data-mode='' 
                        data-filename='' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-passkey64='$passkey64'                        
                        data-sort='filename'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:27%;
                        background-color:$color2;color:$textcolor1'
                    >
                        $menu_sortbyname
                    </div>
                    <div class='doclib smalltext2 gridnoborder' 
                        data-page='1' 
                        data-mode='' 
                        data-filename='' 
                        data-target='$target' 
                        data-caller='$caller' 
                        data-folder='$selectedfolder'
                        data-folderid='$selectedfolderid'
                        data-roomfolderid='$roomfolderid'
                        data-passkey64='$passkey64'                        
                        data-sort='filesize desc'
                        style='cursor:pointer;display:inline-block;margin:0;padding:5px;text-align:center;width:27%;;
                        background-color:$color3;color:$textcolor1;'
                    >
                        $menu_sortbysize
                    </div>
                
            ";
    return $text;
 
                
}
function RefreshButton( $filtername, $selectedfolder, $selectedfolderid, $roomfolderid, $sort, $target, $caller, $passkey64, $upload, $mode, $filename )
{
    global $iconsource_braxtasks_common;
    global $iconsource_braxupload_common;
    global $iconsource_braxrefresh_common;
    global $iconsource_braxfind_common;
    global $global_textcolor;
    
    $uploadfile = "uploadfile2";
    if( $caller!='') {
        $uploadfile = "uploadfile2";
    }
    
    
    if( $mode!='CF' && $caller=='' && $filename == '')
    {
        echo "        
                    <img src='$iconsource_braxtasks_common' 
                            style='cursor:pointer;margin-left:10px;margin-right:10px'  
                            class='icon30 doclib tapped' id='createtext' 
                            data-page='1' 
                            data-folder='$selectedfolder'
                            data-folderid='$selectedfolderid'
                            data-roomfolderid='$roomfolderid'
                            data-mode='TEXT' 
                            data-filename='' 
                            data-sort='$sort'   
                            data-target='$target' 
                            data-caller='$caller' 
                            data-passkey64='$passkey64'        
                            title='Create an Encrypted Note'
                        >
                        ";
        echo "
                    <img class='icon30 $uploadfile' id='uploadfile' 
                        src='$iconsource_braxupload_common' style='cursor:pointer;' title='Upload a File'    />
            ";
        if($selectedfolder == ''){
            echo "
                <img src='$iconsource_braxfind_common' 
                        style='cursor:pointer;margin-left:10px'  
                        class='icon30 doclibsearch tapped showhidden' 
                        title='Find a File'
                    >
                <span class='doclibsearcharea' style='display:none'>
                    <input id='filefiltername' type='text' name='filefiltername' placeholder='Search All Folders' class='inputline mainfont dataentry filefiltername' size='20' style='color:$global_textcolor;max-width:200px' value='$filtername' />

                </span>
             ";
        }
        echo "        
            <img src='$iconsource_braxrefresh_common' 
                    style='display:none;cursor:pointer;margin-left:10px'  
                    class='showhiddenarea icon30 doclib tapped' id='refreshalbum' 
                    data-page='1' 
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-roomfolderid='$roomfolderid'
                    data-mode='' 
                    data-filename='' 
                    data-sort='$sort'   
                    data-target='$target' 
                    data-caller='$caller' 
                    data-passkey64='$passkey64'                        
                    title='Refresh File List'
                >
                ";
        echo "<br>";
        
        
    }
    //echo "$upload<br>";
    
}
function fileListButtons( 
        $origfilename, $page, $sort, $folder,
        $folderid, $roomfolderid, $filename, $target, $caller, $roomid, $width
        )
{
    global $global_activetextcolor;
    
        $buttons = "";
        

        if($caller=='roomfile'){
        
            $buttons .=
                "
                <div class='formobile'></div>
                <div style='display:inline-block;width:$width;height:10px;'>
                    <br>
                    <div
                        class='doclibselectrow mainfont tapped2 rounded gridstdborder'

                        title='$origfilename'

                        data-page='$page'
                        data-sort='$sort'
                        data-folder='$folder'
                        data-folderid='$folderid'
                        data-roomfolderid='$roomfolderid'
                        data-filename='$filename'
                        data-altfilename='$filename'  
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid'
                        data-origfilename='$origfilename'  

                        style='background-color:white;cursor:pointer;color:$global_activetextcolor;display:inline;margin-top:10px;padding:10px'

                        >
                        Select
                    </div><br>
                </div><br><br><br>
                ";            
        }
        if($caller=='casefile'){
        
            $buttons .=
                "
                <div class='formobile'></div>
                <div style='display:inline-block;width:$width;height:10px;'>
                    <br>
                    <div
                        class='doclibselectrow mainfont tapped2 rounded gridstdborder'

                        title='$origfilename'

                        data-page='$page'
                        data-sort='$sort'
                        data-folder='$folder'
                        data-folderid='$folderid'
                        data-roomfolderid='$roomfolderid'
                        data-filename='$filename'
                        data-altfilename='$filename'  
                        data-target='$target' 
                        data-caller='$caller' 
                        data-roomid='$roomid'
                        data-origfilename='$origfilename'  

                        style='background-color:white;cursor:pointer;color:$global_activetextcolor;display:inline;margin-top:10px;padding:10px'

                        >
                        Select
                    </div><br>
                </div><br><br><br>
                ";            
        }
        return $buttons;
            
}
function duplicateNameCorrection($providerid, $AWSfilename, $origfilename ) {
        
        $filename = $origfilename;
        $matched = true;
        while($matched){
        
            //No Encryption Currently
            $filename_encrypted = $filename;
            $result = pdo_query("1", 
                    "
                        select * from filelib 
                        where providerid = ? and 
                        origfilename = ? and status='Y'
                        and filename!=?
                     ",array($providerid,$filename_encrypted,$AWSfilename)
             );
            if(!$row = pdo_fetch($result)){
                $matched = false;
                return $filename;
            }
            $path = pathinfo($filename);
            $exploded = explode(".",$filename);
            $uniqid = substr(uniqid(),9,4);
            $filename = $exploded[0].".$uniqid.".$path['extension'];
                    
        }
        return $filename;
        
}
function RoomTips()
{
    if( $_SESSION['roomcreator']!='Y'){
        return
        "
            <div class='tipbubble pagetitle2a gridstdborder' style='background-color:white;margin:auto;padding:20px;width:500px;max-width:80%'>
            <span style='color:black'>
                <div style='padding-left:10px;padding-right:10px'>
                My Files is your encrypted storage area. Files are kept in original form.
                <br><br>
                Free Tier users 4GB of free storage + bandwidth  for personal use. 
                </div>
            </span>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            ";
    }
        return
        "
            <br>
            <div class='tipbubble pagetitle2a gridstdborder' style='background-color:white;margin:auto;padding:20px;width:500px;max-width:80%'>
            <span style='color:black'>
                <div style='padding-left:10px;padding-right:10px'>
                My Files is your encrypted storage area. Files are kept in original form.
                </div>
            </span>
            </div>
            <br>
            ";
    
}
function DeleteFolder($sort, $caller, $roomid, $page, $target, $selectedfolder, $selectedfolderid)
{
    return
            "
                <center>
                <div class='divbutton3 divbutton3_unsel doclib tapped' 
                    data-page='$page' 
                    data-sort='$sort' 
                    data-target='$target' 
                    data-caller='$caller' 
                    data-filename=''  
                    data-folder='$selectedfolder'
                    data-folderid='$selectedfolderid'
                    data-mode='DF'
                    >
                    <img src='../img/delete-circle-128.png' style='position:relative;top:3px;height:15px;' />
                    &nbsp;&nbsp;Delete entire folder '$selectedfolder'
                </div>
                </center>
                <br><br>
    
            ";
    
}
function TextEditor($sort, $caller, $roomid, $page, $target, $selectedfolder, $selectedfolderid, $mode, $filename, $textdata )
{
    global $iconsource_braxarrowleft_common;
    global $iconsource_braxarrowright_common;
    global $iconsource_braxadd_common;
    global $global_textcolor;
    global $global_background;
    
    $textdata = str_replace(chr(13),"",$textdata);
    
    if($caller!='roomfile' || $caller='roomfileedit'){
        $action = 'doclib';
    } else {
        $action = 'roomfiles';
    }
    
    
    $filenameentry = "";
    $title = "";
    if($filename == ''){
        $filenameentry = "<input id='textfilename' class='dataentry' type='text' style='width:100px' value='note.txt' />
            &nbsp;";
        $title = "<div class='pagetitle2a' style='color:$global_textcolor;display:inline;margin-left:10px;margin-top:10px'>New Encrypted Note</div>
            <div class='formobile'></div>
            &nbsp;&nbsp;
            <img class='icon15 $action tapped' data-roomid='$roomid' src='$iconsource_braxarrowleft_common' />&nbsp;Back
            &nbsp;&nbsp;
            ";
    }
    $editor =  "
        <div class='fileeditor pagetitle2a' style='padding:10px;background-color:$global_background;color:$global_textcolor'>
            $title
            $filenameentry
            <span class='doclib tapped' data-mode='$mode' 
                data-filename='$filename'
                data-page='$page' 
                data-sort='$sort' 
                data-target='$target' 
                data-caller='$caller' 
                data-filename=''  
                data-roomid='$roomid'
                data-folder='$selectedfolder'
                data-folderid='$selectedfolderid'
                    
                src='$iconsource_braxarrowright_common' style='cursor:pointer' >
            
                <img class='icon20' src='$iconsource_braxarrowright_common' />&nbsp;Save
            </span>
            &nbsp;
            &nbsp;
            &nbsp;
            <span class='doclib tapped' data-mode='PIN' 
                data-filename='$filename'
                data-page='$page' 
                data-sort='$sort' 
                data-target='$target' 
                data-caller='$caller' 
                data-filename=''  
                data-roomid='$roomid'
                data-folder='$selectedfolder'
                data-folderid='$selectedfolderid'
                    
                src='$iconsource_braxarrowright_common' style='cursor:pointer' >
            
                <img class='icon20' src='$iconsource_braxadd_common' />&nbsp;Pin
            </span>

            
            <br>
            <textarea id='texteditcontent' rows=15 style='width:80%;height:80%;padding:20px;margin:10px;font-family:courier'>$textdata</textarea>
        </div>
        <script>
        setTimeout( function(){
            $('#texteditcontent').focus();
            }, 500 );
        </script>
        ";
    return $editor;
  
    
}
?>