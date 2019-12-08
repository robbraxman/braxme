<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("password.inc.php");
require_once("aws.php");
require_once("imageproc.inc");
require_once("internationalization.php");


    //savelastfunc ( "P" );

    //$replyflag = mysql_safe_string($_POST[replyflag]);
    $providerid = mysql_safe_string($_SESSION['pid']);
    
    
    $showfilename = '';
    if(isset($_POST['filename'])){
    
        $showfilename = mysql_safe_string($_POST['filename']);
    }

    $deletefilename = '';
    if(isset($_POST['deletefilename'])){
    
        $deletefilename = mysql_safe_string($_POST['deletefilename']);
    }
    $rotateangle = "";
    if(isset($_POST['rotate'])){
    
        $rotateangle = mysql_safe_string($_POST['rotate']);
    }
    
    //ForSQL
    $selectedalbum = "";
    $selectedalbumHtml = "";
    $selectedalbumSql = "";
    $origalbum = "";
    if(isset($_POST['album'])){
    
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['album']));
        $selectedalbumHtml = ConvertHTML($selectedalbum);
        $selectedalbumSql = mysql_safe_string($selectedalbum);
        $origalbum = DeconvertHTML(mysql_safe_string($_POST['origalbum']));
        //echo $selectedalbum;
    }
    
    $directshare = "";
    $roomshare = "";
    $title = "";
    $folder = "photolib/";
    $public = "N";
    //echo "selected $selectedalbum<br>";
    SaveLastFunction($providerid,"P", "$selectedalbum");
    
    if( $selectedalbum != '' ){
        if( $selectedalbum[0]!='*'){
            $public = 'Y';
        }
    
        if( $selectedalbum[0]!='*' && $selectedalbum!=''){
        
            $result = do_mysqli_query("1","select distinct album from photolib where album = '$selectedalbumSql' 
                and providerid=$providerid ");
            if($row = do_mysqli_fetch("1",$result)){
            
                $selectedalbum = $row['album'];
                $selectedalbumSql = mysql_safe_string($row['album']);
                $selectedalbumHtml = ConvertHTML($selectedalbum);
            } else {
            
                //check for closest name to album
                $result = do_mysqli_query("1","select distinct album from photolib where album like '$selectedalbumSql%' 
                    and providerid=$providerid order by album desc limit 1 ");
                if($row = do_mysqli_fetch("1",$result)){
                
                    $selectedalbum = $row['album'];
                    $selectedalbumSql = mysql_safe_string($row['album']);
                    $selectedalbumHtml = ConvertHTML($selectedalbum);
                } else {
                    
                    $selectedalbum = "";
                    $selectedalbumHtml = "";
                    $selectedalbumSql = "";
                }
            }
                        
        }
    }
    
    do_mysqli_query("1","
        update photolib set public='Y', providerid=0 where 
        ( album like '* Public%' or album = '* Artist Submission')  
        ");
    do_mysqli_query("1","
        update photolib set public='N', providerid=$providerid where album not like '* Public%'        
        and owner = $providerid
        ");
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //*************************************************************
    
    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //*************************************************************
    $save = "";
    if(isset($_POST['save'])){
        $save = mysql_safe_string($_POST['save']);
    }
    if( $save=='DA'){ //delete album

        /*Can't Delete Public Pics */
        
        $selectedalbum = mysql_safe_string(stripslashes($_POST['album']));        
        $result = do_mysqli_query("1",
            "
                delete from photolib 
                where owner= $providerid and album='$selectedalbum' 
                and public='N'
                
            ");
        $selectedalbum = "";
        $selectedalbumHtml = "";
        $selectedalbumSql = "";
        $save = "";
        //exit();
    }
    if( $save=='FAMILYSHARE' || $save=='FRIENDSHARE' || $save =='ALLSHARE'){ //Change Photo
    
        $comment = @mysql_safe_string($_POST['comment']);
        $title = @mysql_safe_string($_POST['title']);
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['album']));        
        $selectedalbumSql = mysql_safe_string($selectedalbum);
        $sharetype= 'C';
        if($save == 'FRIENDSHARE'){
            $sharetype = 'F';
        }
        if($save == 'ALLSHARE'){
            $sharetype = 'A';
        }
        $result = do_mysqli_query("1",
        "
            delete from photolibshare where providerid = $providerid and album = '$selectedalbumSql'
        ");
        
        $result = do_mysqli_query("1",
        "
            insert into photolibshare (providerid, album, sharetype ) values ($providerid, '$selectedalbumSql','$sharetype')
        ");
            
       $save ='';         
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['origalbum']));
        $selectedalbumHtml = ConvertHTML($selectedalbum);
        $selectedalbumSql = mysql_safe_string($selectedalbum);
       $showfilename = "";
       
       
       //exit();
    }
    if( $save=='FRIENDUNSHARE'){ //Change Photo
    
        $comment = @mysql_safe_string($_POST['comment']);
        $title = @mysql_safe_string($_POST['title']);
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['album']));        
        $selectedalbumSql = mysql_safe_string($selectedalbum);
        
        $result = do_mysqli_query("1",
        "
            delete from photolibshare where providerid = $providerid and album = '$selectedalbumSql'
        ");
            
       $save ='';         
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['origalbum']));
        $selectedalbumHtml = ConvertHTML($selectedalbum);
        $selectedalbumSql = mysql_safe_string($selectedalbum);
       $showfilename = "";
       
       
       //exit();
    }
    
    if( $save=='C'){ //Change Photo
    
        $comment = @mysql_safe_string($_POST['comment']);
        $title = @mysql_safe_string($_POST['title']);
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['album']));        
        $selectedalbumSql = mysql_safe_string($selectedalbum);
        if($selectedalbum==''){
            $selectedalbum = $origalbum;
            $selectedalbumSql = mysql_safe_string($selectedalbum);
        }
            $result = do_mysqli_query("1",
            "
                update photolib set title='$title', comment='$comment', album='$selectedalbumSql'
                where owner= $providerid and filename='$showfilename'
            ");
            
       $save ='';         
        $selectedalbum = DeconvertHTML(mysql_safe_string($_POST['origalbum']));
        $selectedalbumHtml = ConvertHTML($selectedalbum);
        $selectedalbumSql = mysql_safe_string($selectedalbum);
       $showfilename = "";
       
       
       //exit();
    }
    if( $save=='A'){ //Avatar Save
    
        
        $result = do_mysqli_query("1",
        "
            select aws_url from photolib where filename ='$showfilename' 
        ");
        $row = do_mysqli_fetch("1",$result);
        $avatarurl = $row['aws_url'];
        
        if( $avatarurl == "")
        {
            $avatarurl = "$rootserver/$installfolder/sharedirect.php?p=$showfilename";
        }
        
        do_mysqli_query("1","
            update provider set avatarurl = '$avatarurl', lastactive=now() where
                providerid = $providerid
            ");
       $showfilename = "";
        SaveLastFunction($providerid,"", "");
       
       exit();
    }
    if( $save=='CA'){ //Change Album
        
        $newalbum = "";
        $newalbum = DeconvertHTML(mysql_safe_string($_POST['newalbum'] ));
        $newalbumSql = mysql_safe_string($newalbum);
        if( $newalbum!=''){
            
            $newpublic = "N";
            if($newalbum[0]=='*'){
                $newpublic= "Y";
            }

            do_mysqli_query("1", "
                update photolib set album='$newalbumSql', public='$newpublic' where album='$selectedalbumSql' and public='N'
                and providerid=$providerid
                ");

            do_mysqli_query("1", "
                update sharecollection set album='$newalbumSql' where album='$selectedalbumSql'
                and providerid=$providerid
                ");

            do_mysqli_query("1", "
                update shares set sharelocal='$newalbumSql' where sharelocal='$selectedalbumSql'
                and providerid=$providerid and sharetype='A'
                ");
        }
       $showfilename = "";
        $selectedalbum = $newalbum;
        $selectedalbumHtml = ConvertHTML($newalbum);
        $selectedalbumSql = mysql_safe_string($newalbum);
        $save = "";
    }
        
    
    
    
    
    $result2 =do_mysqli_query("1","
        select sum(filesize) as filesize, count(*) as count from photolib where owner = $providerid and public!='Y'
        ");
    $row2 = do_mysqli_fetch("1",$result2);
    $totalsize = round(($row2['filesize']/1000000),1);
    $totalAll = $row2['count'];
    
    $result2 =do_mysqli_query("1","
        select count(*) as count, sum(filesize) as filesize from photolib where
        ( providerid = $providerid
            and (album='$selectedalbum' or 'All' ='$selectedalbum')
        )
        or
        (
           public = 'Y' and album='$selectedalbum'
        )
        ");
         
    
    $row2 = do_mysqli_fetch("1",$result2);
    $total = $row2['count'];
    
    $firstphoto = "";
    //If there's only 1 photo -- assume it is selected!
    if($totalAll == 1){
    
        
        $result2 = do_mysqli_query("1","
            select filename from photolib where
                providerid = $providerid
                and album='$selectedalbum'  
                    ");
        if($row2 = do_mysqli_fetch("1",$result2))
            $firstphoto = $row2['filename'];
                
        
    }

    //*************************************************************
    //*************************************************************
    //*************************************************************

    $page = 1;
    if(isset($_POST['page'])){
        $page = intval(mysql_safe_string($_POST['page']));
    }
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
    $pagedisplay = "$pagestartdisplay - $pageenddisplay /$total";
    if($pageenddisplay > $total){
    
        $pagedisplay = "$pagestartdisplay - $total";
        if( $pagestartdisplay > $total ){
        
            $pagedisplay = "$pagestartdisplay - End";
        }
    }
    
    

    if( $deletefilename!=''){
    

        if($_SESSION['superadmin']!='Y'){
            $result = do_mysqli_query("1",
                "   delete from photolib where 
                    (owner =$providerid )
                    and filename ='$deletefilename' 
                ");
        } else {
            $result = do_mysqli_query("1",
                "   delete from photolib where 
                    filename ='$deletefilename' 
                ");
            
        }
        //unlink("$filepath/$installfolder/$folder$deletefilename");
        $result = do_mysqli_query("1",
        "
            select filename from photolib where filename ='$deletefilename' 
        ");
        if(!$row = do_mysqli_fetch("1",$result)){
        
            deleteAWSObject($deletefilename);
        }
        
        
        //unlink("$folder$deletefilename");
    }
    
    if( $showfilename!=''){
    
        $result = do_mysqli_query("1",
        "
            select folder, title, filename, comment, album, alias, aws_url, public, owner,
            datediff(aws_expire,now()) as expire, 
            (select providername from provider where provider.providerid = photolib.owner) as ownername
            from photolib where filename ='$showfilename' 
        ");
        $row = do_mysqli_fetch("1",$result);
        $folder = $row['folder'];
        $title = htmlentities($row['title'], ENT_QUOTES);
        $comment = str_replace("<br />","\r\n",html_entity_decode($row['comment']));
        $filename = $row['filename'];
        $showfilenameUrl = $row['aws_url'];
        $ownername = $row['ownername'];
        
        $showfilenameUrl = getAWSObjectUrlShortTerm($row['filename']);
        
        
        $alias = $row['alias'];
        $directshare = "$rootserver/$installfolder/shareshowimg.php?a=$alias";
        $resizeshare = "$rootserver/$installfolder/showimg.php?a=$alias";
        $roomshare = "$rootserver/$installfolder/sharedirect.php?a=$alias";
        $insideshare = "$rootserver/$installfolder/sharedirect2.php?a=$alias";
        $album = ConvertHTML($row['album']);
        $public = $row['public'];
        $owner = $row['owner'];

    
        if( $public!='Y' && intval($rotateangle) != 0 ){
        
            $rotatefolder = "upload-zone/files/";
            if(!is_writable ( $rotatefolder )  ){          
            
                echo "Can't write to $rotatefolder";
                exit();
            }
            try {
                $f = explode("_",$filename);
                $e = explode(".",$filename);
                $ext = strtolower($e[count($e)-1]);
                $uniqid = uniqid("",false);

                $newfilename= $f[0]."_".$f[1]."_".$uniqid.".$ext";
                saveAWSObject($filename, "$rotatefolder$filename");

                $img = new ImageManipulation();
                $img->load("$rotatefolder$filename");

                $img->rotate_image(intval($rotateangle), 'ffffff',0 );
                //$img->output();
                $img->save_image("$rotatefolder$newfilename", "$ext");
                putAWSObject($newfilename, "$rotatefolder$newfilename");

                $new_awsurl = getAWSObjectUrl($newfilename, "$rotatefolder$newfilename");

                $alias = uniqid("T4AZ", true);

                do_mysqli_query("1","
                    update photolib set filename='$newfilename', aws_url='$new_awsurl',
                        alias='$alias'
                        where providerid=$providerid and filename='$filename'
                        ");
                
                unlink("$rotatefolder$filename");

                //unlink("$folder$newfilename");
                //
                deleteAWSObject($filename );


                $filename = $newfilename;
                $showfilename = "";        
            } catch (Exception $e) {
                echo $e->getMessage()." $rotatefolder$filename";
                exit();
            }
        }
    
    }
    
    $albumobj = AlbumMenu($providerid, $selectedalbum, $page);
    $albumselect = $albumobj->albumselect;
    $albummenu = $albumobj->albummenu;
        
        
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
        color:$global_textcolor;
        '>
            <div class='gridnoborder' style='background-color:$global_titlebar_color;color:white;padding-left:10px;padding-right:20px;padding-bottom:3px;margin:0;' >
                <!--
                <img class='icon20 $action mainbutton' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                    style='' data-providerid='$providerid'  data-roomid='$_SESSION[profileroomid]' data-caller='none' />
                &nbsp;
                -->
                <span style='opacity:.5'>
                $icon_braxphoto2
                </span>    
                <span class='pagetitle2a' style='color:white'>$menu_myphotos</span> 
            </div>
            ";
    if($selectedalbum == ''){
        echo "
            <span class=formobile>
               <div class='uploadphoto2 tapped' data-chatid='' style='display:inline-block;background-color:transparent;'>
                    <img class='icon35' title='Upload Photo' src='$iconsource_braxupload_common' style='margin-left:20px;margin-bottom:20px;margin-right:20px' />
                </div>
                &nbsp;
                <div class='textphoto tapped' style='display:inline-block;background-color:transparent;'>
                    <img class='icon35' title='Text to Pic Converter' src='$iconsource_braxtextphoto_common' style='margin-left:0px;margin-bottom:20px;margin-right:0px' />
                </div>
                <br>
            </span>
            <span class=nonmobile>
                &nbsp;&nbsp;
                <div class='smalltext2 uploadphoto2 tapped' data-chatid='' style='color:$global_textcolor;display:inline-block;height:40px;width:45px;text-align:center;cursor:pointer'>
                    <img class='icon30' title='Upload Photo' src='$iconsource_braxupload_common' style=''     />
                    <br><br>Upload
                </div>
                &nbsp;
                <div class='smalltext2 textphoto tapped' 
                    style='color:$global_textcolor;display:inline-block;height:40px;width:45px;text-align:center;cursor:pointer'>
                    <img class='icon30' title='Text to Pic Converter' src='$iconsource_braxtextphoto_common' style='' />
                    <br><br>Text2Pic
                </div>
                
                <br><br>
            </span>
            ";
    }
    echo "
        </div>
            ";



    //*************************************************************
    //*************************************************************
    //*************************************************************
    echo "
        <div class='panelhost' style='position:relative;background-color:$global_background;width:100%;margin:0;padding:0'>";

    /****************************************************
     * 
     *  PHOTO SELECT AREA
     * 
     *****************************************************/
    
    echo "<span class='photoselectarea'>";

   echo "<table class='gridstdnoborder' style='position:relative;padding:0;border:0;margin:0;max-width:100%'>";

    
    if( ($showfilename !== "" || $firstphoto!="") ){
    
        //PhotoShare( $showfilename, $firstphoto, $page, $folder, $title, $roomshare, $directshare );
    }
  
    if( $showfilename !== "" ){
    
        //Photo Display
        /*
         * *******************************
         */
        if( ($public !='Y') 
         ||
        ( $_SESSION['superadmin']=='Y')
          )
        {
            $albumitem2 = DeconvertHTML($selectedalbum);

            echo "
                <tr>
                    <td>
                        <span class='nonmobile'>
                            &nbsp;&nbsp;
                            <a href='$resizeshare' style='text-decoration:none;' target='_blank'>
                            <img class='icon20' title='Zoom' src='$iconsource_braxzoom_common'  />
                            </a>
                                &nbsp;
                            
                            <img class='icon20 photolibrary tapped' 
                                src='$iconsource_braxclose_common'  
                                title='Delete Photo'
                                data-deletefilename='$showfilename' 
                                data-save='D' 
                                data-page='$page'  
                                data-rotate='' 
                                data-album='$selectedalbumHtml' />
                                
                                &nbsp;
                            <a href='$rootserver/$installfolder/sharedownload.php?p=$row[filename]' style='text-decoration:none'>
                                <img class='icon20' title='Download Photo' src='$iconsource_braxdownload_common'  />
                            </a>
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            <div class='photogotoalbum' title='Go to album' 
                                data-filename='' data-deletefilename='' 
                                data-album='$selectedalbumHtml' 
                                style='cursor:pointer;display:inline;color:$global_activetextcolor'
                                >View Album $selectedalbum
                            </div>
                            <br>
                        </span>
                        <span class='formobile'>
                            &nbsp;
                            <a href='$resizeshare' style='text-decoration:none;' target='_blank'>
                            <img class='icon30' title='Zoom' src='$iconsource_braxzoom_common'  />
                            </a>
                            &nbsp;
                            &nbsp;
                            

                            <img class='photolibrary tapped icon30' 
                                src='$iconsource_braxclose_common' style='' 
                                title='Delete Photo'
                                data-deletefilename='$showfilename' 
                                data-save='D' 
                                data-page='$page'  
                                data-rotate='' 
                                data-album='$selectedalbumHtml' style='margin-left:10px' />
                                
                                &nbsp;
                                &nbsp;
                            <img class='downloadimg icon30' data-imgid='download_img1' src='$iconsource_braxdownload_common' style=''  style='margin-left:10px'/>
                                &nbsp;
                                 &nbsp;
                                 &nbsp;
                                 &nbsp;
                            <img id='download_img1' class='' data-filename2='$filename' src='$insideshare'
                                style='display:none' />
                            <br><br>
                            &nbsp;

                            <div class='photogotoalbum' title='Go to album' 
                                data-filename='' data-deletefilename='' 
                                data-album='$selectedalbumHtml' 
                                style='cursor:pointer;display:inline;color:$global_activetextcolor'
                                >View Album $selectedalbum
                            </div>

                        </span>
                        <br>
                    </td>
                </tr>
                    ";
        }
        
        echo "
            <tr style='background-color:white;max-width:100%;padding:0;margin:0'>
                <td style='position:relative;background-color:$global_background;max-width:100%;padding:0;margin:0'>


                    <img class='gridnoborder photounselect tapped2'  src='$showfilenameUrl' 
                        title='Tap on photo to return to album'
                        style='z-index:1;cursor:pointer;position:relative;top;0;left:0px;max-width:$picwidth1;height:auto;padding:0;margin:0' 
                        data-deletefilename='' data-filename='' data-album='$selectedalbumHtml' data-page='$page' />
                    <img class='icon30 photounselect' src='../img/arrow-stem-left-gray-128.png' style='z-index:100;padding:10px;position:absolute;top:0;left:0' />        
                            
                </td>
            </tr>
            ";
        //End of Photo Display
        /*
         * *******************************
         */
        

        //Public Photo - SET PROFILE ONLY
        echo "
            <tr>
                <td style='background-color:$global_background;color:$global_textcolor;padding:20px'>
              ";  
        
        if( ($public !='Y') ){
        
            echo "
                        
                                <span class='hiderename'>
                                    <b>Album</b>
                                    <div class='formobile'></div>
                                    <select name='album' class='photolib_editalbum' title='Album Selection' id='photolib_editalbum' data-deletefilename='' data-filename='' data-page='$page' data-rotate='' >
                                    <option value='$selectedalbumHtml' selected='selected'>$albumitem2</option>
                                    $albumselect
                                </span>
                                <span class='showrename' style='display:none'>
                                    <input class='dataentry mainfont' id='photoedit_album' placeholder='Create New Album' type='text' value='' size='20' 
                                        style='border-width:1px;border-style:solid;
                                        border-color:gray' 
                                        data-xaccode='T' 
                                        data-filename='$showfilename' />
                                </span>
                                <span class='formobile'>
                                    <img class='photochange icon25' title='Change album' src='$iconsource_braxarrowright_common' 
                                        data-filename='$showfilename' data-deletefilename='' 
                                        data-album='$selectedalbumHtml' 
                                        data-owner='$owner'
                                        data-page='$page'
                                        style='position:relative'
                                    />
                                </span>
                                <span class='nonmobile'>
                                    &nbsp;
                                    <img class='photochange icon20' title='Change album' src='$iconsource_braxarrowright_common' 
                                        data-filename='$showfilename' data-deletefilename='' 
                                        data-album='$selectedalbumHtml' 
                                        data-owner='$owner'
                                        data-page='$page'
                                        style='position:relative'
                                    />
                                </span>
                                        
                                        
                            <br><br>
                            ";
        }
            echo "
                                <div class='photolibrary tapped' id='setavatar' 
                                    data-album='$selectedalbumHtml' 
                                    data-filename='$showfilename' 
                                    data-deletefilename='' 
                                    data-save='A' 
                                    data-page='$page'
                                    style='display:inline-block;cursor:pointer;color:$global_activetextcolor'
                                >
                                Use as Profile Photo
                                </div>
                                <br><br>
                            ";
        if( ($public !='Y') ){
            echo "            
                                <span class='nonmobile'>
                                    <img class='photolibrary tapped icon20' 
                                        src='../img/rotate-left-128.png' style='' 
                                        id='photorotate' data-page='$page' data-deletefilename=''  
                                        data-album='$selectedalbumHtml' 
                                        data-save='' data-rotate='-90' data-filename='$showfilename'  />

                                        &nbsp;
                                    <img class='photolibrary tapped icon20' 
                                        src='../img/rotate-right-128.png' style='' 
                                        id='photorotate' data-page='$page' data-deletefilename=''  
                                        data-album='$selectedalbumHtml' 
                                        data-save='' data-rotate='90' data-filename='$showfilename'  />

                                        &nbsp;
                                </span>
                                <span class='formobile'>
                                    <img class='photolibrary tapped icon30' 
                                        src='../img/rotate-left-128.png' style='' 
                                        id='photorotate' data-page='$page' data-deletefilename=''  
                                        data-album='$selectedalbumHtml' 
                                        data-save='' data-rotate='-90' data-filename='$showfilename'  />

                                        &nbsp;
                                    <img class='photolibrary tapped icon30' 
                                        src='../img/rotate-right-128.png' style='' 
                                        id='photorotate' data-page='$page' data-deletefilename=''  
                                        data-album='$selectedalbumHtml' 
                                        data-save='' data-rotate='90' data-filename='$showfilename'  />

                                        &nbsp;
                                </span>
                                <div class='formobile'></div>
                                ";
            
        }
            echo "            
                </td>
            </tr>
            ";
        

        if( ($public !='Y') ){
            
            $albumitem2 = stripslashes($selectedalbum);
            echo "
                <tr class='mainfont'>
                    <td style='padding:20px;color:$global_textcolor'>
                    External Share Link<br>
                    <input class='dataentry' type='text' value='$directshare' />
                    &nbsp;
                    <a href='$directshare'>
                    <img class='icon20' title='Change album' src='$iconsource_braxarrowright_common'  />
                    </a>
                    </td>
                </tr>
                ";

        }
        if( ( $public =='Y' && $selectedalbum == '* Public - Artist Showcase') ||
              $selectedalbum == '* Artist Submission'
          )
        {
            $result2 = do_mysqli_query("1","select body from profile where providerid=$owner");
            if( $row2 = do_mysqli_fetch("1",$result2)){
               $profile = base64_decode($row2['body']);
            }
            echo "
                <tr>
                    <td>
                        <br>
                        <div style='padding:20px'>
                            <div class='pagetitle2'>Artist Profile</div><br>By $ownername<br><br>$profile
                        </div>
                    </td>
                </tr>
                ";
        }

        echo "
            </table>
            <br>
            <hr>
             ";
        
    }
    
    //Photounselectarea end
    /****************************************************
     * 
     *  PHOTO ALBUM AREA
     * 
     *****************************************************/
    echo " </span>
            <span class='photoalbumarea'>
         ";

        
    

    
    //*************************************************************
    //*************************************************************
    //*************************************************************
    //$albumitem2 = stripslashes($selectedalbum);
    
    echo "
            <div style='padding-left:20px;padding-right:10px;padding-bottom:10px'>
         ";
    
    if($totalAll == 0 && $page == 1 && $selectedalbum ==''){
        PhotoTip();
    }
    
    $album = CreateAlbumList($providerid, $selectedalbum, $selectedalbumHtml, $page, $total);
    echo $album->container;
    $hidealbum = 'display:block';
    $unhidealbum = 'display:none';
    if($selectedalbumHtml == 'zuck'){
        $unhidealbum = 'display:block';
    }
    if($_SESSION['superadmin']!='Y'){
        $hidealbum = 'display:none';
        $unhidealbum = 'display:none';
    }
    if($selectedalbum!='' && substr($selectedalbum,0,7)!='Upload-' ){
        echo "                    
            <span class='showhiddenarea' style='display:none'>
                <span class='hiderename' style='color:$global_textcolor'>
                    Move to
                    <select name='album' class='photolib_albumrename' title='Album Selection' id='photolib_editalbum' 
                        data-deletefilename='' 
                        data-filename='' 
                        data-page='$page' 
                        data-rotate='' >
                    $albumselect
                </span>

                <span class='showrename' style='display:none' >
                    <br>
                    <input class='photolib_albumrenamenew' placeholder='New Album Name' id='photolib_albumrename' type=text size=30 value='' />
                </span>
                &nbsp;&nbsp;
                <img class='photolibrary icon20' title='Move to Album' src='$iconsource_braxarrowright_common' 
                    data-filename='$showfilename' data-deletefilename='' 
                    data-album='$selectedalbumHtml' data-save='CA'
                    data-owner=''/>
                <br><br>
                <span class='photolibrary hiderename' 
                    data-filename='$showfilename' data-deletefilename='' 
                    data-album='$selectedalbumHtml' data-save='RENAME'
                    data-owner='' style='cursor:pointer'>
                    <br>
                    <span style='color:$global_activetextcolor'>Rename</span>
                     <br><br>
                    
                </span>
                <span class='photolibrary' style='color:$global_activetextcolor;cursor:pointer'
                    title='Delete entire album'
                    class='icon20 photolibrary tapped' 
                    data-album='$selectedalbumHtml' 
                    data-filename='$showfilename' 
                    data-deletefilename='' 
                    data-save='DA' 
                    data-page='$page'
                    >Delete Album</span>
                <br><br>
                <span class='photolibrary' style='$hidealbum;color:$global_activetextcolor;cursor:pointer'
                    title='Hide entire album'
                    class='icon20 photolibrary tapped' 
                    data-album='$selectedalbumHtml' 
                    data-filename='$showfilename' 
                    data-deletefilename='' 
                    data-save='HA' 
                    data-page='$page'
                    >Hide Album</span>
                <br>
                <span class='photolibrary' style='$unhidealbum;color:$global_activetextcolor;cursor:pointer'
                    title='Hide entire album'
                    class='icon20 photolibrary tapped' 
                    data-album='$selectedalbumHtml' 
                    data-filename='$showfilename' 
                    data-deletefilename='' 
                    data-save='UHA' 
                    data-page='$page'
                    >Unhide Album</span>
                    
                    ";
        echo "</span>";
    }

    if($selectedalbum!='' && substr($selectedalbum,0,7)=='Upload-' ){
        echo "                    
            <span class='showhiddenarea' style='display:none'>
                <span class='hiderename' style='color:$global_textcolor'>
                    Move to
                    <select name='album' class='photolib_albumrename' title='Album Selection' id='photolib_editalbum' 
                        data-deletefilename='' 
                        data-filename='' 
                        data-page='$page' 
                        data-rotate='' >
                    $albumselect
                </span>

                <span class='showrename' style='display:none' >
                    <br>
                    <input class='photolib_albumrenamenew' placeholder='New Album Name' id='photolib_albumrename' type=text size=30 value='' />
                </span>
                &nbsp;&nbsp;
                <img class='photolibrary icon20' title='Move to Album' src='$iconsource_braxarrowright_common' 
                    data-filename='$showfilename' data-deletefilename='' 
                    data-album='$selectedalbumHtml' data-save='CA'
                    data-owner=''/>
                    <br><br>
                <br>
        </span>        
                <span class='photolibrary hiderename showhidden' 
                    data-filename='$showfilename' data-deletefilename='' 
                    data-album='$selectedalbumHtml' data-save='RENAME'
                    data-owner='' style='cursor:pointer'>
                    <br>
                    <span style='color:$global_activetextcolor'>Rename</span>
                     <br><br>
                    
                </span>
                <span class='photolibrary' style='color:$global_activetextcolor;cursor:pointer'
                    title='Delete entire album'
                    class='icon20 photolibrary tapped' 
                    data-album='$selectedalbumHtml' 
                    data-filename='$showfilename' 
                    data-deletefilename='' 
                    data-save='DA' 
                    data-page='$page'
                    >Delete Album</span>
                    
                    ";
        //echo "</span>";

    }    
    
        echo "
                    <br><br>
                
                    <img class='icon25 photolibrary tapped' id='prevfilepage' 
                        title='Next Page'
                        src='$iconsource_braxarrowdown_common' 
                        data-page='$pagenext' data-deletefilename=''  
                        data-save='' data-filename='' 
                        data-album='$selectedalbumHtml' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:15px;
                        margin-left:20px;
                        background-color:transparent;
                        ' 
                    />
                    <img class='icon25 photolibrary tapped' id='prevfilepage' 
                        title='Previous Page'
                        src='$iconsource_braxarrowup_common' 
                        data-page='$pageprev' data-deletefilename=''  
                        data-save='' data-filename='' 
                        data-album='$selectedalbumHtml' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:5px;
                        margin-left:10px;
                        background-color:transparent;
                        ' 
                    />
                    <br>
                <div class='smalltext' style='display:inline;float:right;color:$global_textcolor'>$pagedisplay&nbsp;&nbsp;&nbsp;</div>
                    <br>
                <input id='photolibpage' class='photolibpage' type=hidden value='$page' />
           ";    
    echo "  
            </div>
             <table  class='gridstdborder' style='background-color:$global_background;border-collapse:collapse;border:0'>
         ";
    
    $result = do_mysqli_query("1",
        "
            select filename, folder, album, title, createdate, alias, filesize,
            aws_url, aws_expire, datediff(aws_expire,now()) as expire,
            public
            from photolib where 
            ( (providerid = $providerid or public='Y')
              and 
                (   album = '$selectedalbumSql' 
                    or 
                    ('$selectedalbumSql' = '' 
                      and public!='Y' 
                      and 
                         ( 
                           album like 'upload-%' 
                           or
                           album =  'new' 
                           or
                           (
                                album ='Textpics'
                                and datediff( now(), createdate ) < 2
                           )
                         )
                     ) 
                )
            )
            
                
            order by createdate desc limit $pagestart, $max
        ");
    
    
    $col=1;
    $items = 0;
    $closed = false;
    while($row = do_mysqli_fetch("1",$result)){
    
        $items+=1;
        //$filename = "$rootserver/$installfolder/$row[folder]$row[filename]";
        
        $filename = $row['aws_url'];
        
        $filename = "$rootserver/$installfolder/sharedirect.php?p=$row[filename]";
        //The above will filter but massively slow
        $album = ConvertHTML($row['album']);
        $title = htmlentities($row['album']);
        if($filename == '' || $row['expire'] <= 1 ){
        
            $filename = getAWSObjectUrl($row['filename']);
            do_mysqli_query("1","
                update photolib set aws_url = '$filename', aws_expire='2036-01-01' where providerid=$providerid and
                    filename = '$row[filename]'
                ");
            // 'expires'          => gmdate(DATE_RFC2822, strtotime('1 January 1980'))        
        }
        
        
        //create some album alias here but for now we'll just put the full identifier

        
        if( $col==1){
        
            echo "
                <tr>
                ";
            $closed = false;
        }
        echo "
            <td class='gridstdborder' style='width:$picwidth;max-width:$picwidth;overflow:hidden;background-color:transparent;padding:0px;margin:0px'>
            <div style='background-color:transparent;margin:0;padding:0'>
            <img class='photoitem photolibrary tooltip tapped2' src='$filename' 
                title='photo $items $title'
                style='position:relative;top;0;left:0;max-width:$picwidthIn;height:$picheightIn;cursor:pointer;border-width:0px;padding:0px;margin:0' 
                data-filename='$row[filename]' data-album='$album' data-page='$page' data-deletefilename='' data-save='' data-rotate='' />
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
        "&nbsp;&nbsp;No more photos ($pagedisplay)";
    } else {
        echo "
                <br>
                <div style='padding-right:10px'>
                    <img class='icon25 photolibrary tapped' id='prevfilepage' 
                        title='Next Page'
                        src='$iconsource_braxarrowdown_common' 
                        data-page='$pagenext' data-deletefilename=''  
                        data-save='' data-filename='' 
                        data-album='$selectedalbumHtml' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:15px;
                        margin-left:20px;
                        background-color:transparent;'
                    />
                    <img class='icon25 photolibrary tapped' id='prevfilepage' 
                        title='Previous Page'
                        src='$iconsource_braxarrowup_common' 
                        data-page='$pageprev' data-deletefilename=''  
                        data-save='' data-filename='' 
                        data-album='$selectedalbumHtml' 
                        style='cursor:pointer;float:right;
                        padding-left:5px;
                        padding-right:5px;
                        margin-left:10px;
                        background-color:transparent;'
                    />
                </div>
                    ";
    }
    echo "
                    <br>
                <br>
                <div class=smalltext style='margin-left:10px;color:$global_textcolor'>
                Total Space Used: $totalsize MB
                </div>
                <br>
                <br>

            </span>
            <div id='photomenudiv' style='position:absolute;left:-1000px;top:0px;height:0px;color:transparent'>
            $albummenu
            </div>
        </div>
         ";
    

function PhotoShare( $showfilename, $firstphoto, $page, $folder, $title, $roomshare, $directshare )
{
    global $appname;
    $first = "N";
    if( $showfilename == "")
    {
        $showfilename = $firstphoto;
        $first = 'Y';
    }
    if($showfilename == '')
    {
        echo "";
        return;
    }
    echo "
            <!--
            <div class=formobile><br></div>
            -->
    
            <div class='sharephotoarea' style='padding-left:10px;padding-right:10px'> 
        
            <span style='white-space:nowrap'>
            <div class='shareitbutton' id='shareitbutton' 
                 style='display:inline'
                title='Share on Facebook'
                data-folder='$folder' 
                data-filename='$showfilename' 
                data-sharetype='P'
                data-page='$page'
                data-platform='F'
                data-mode=''
                data-alias=''
                data-title='$title'>
                    <img class='icon25' src='../img/facebook-red-128.png' style='position:relative;top:0px;width:auto;margin:0;padding:0' /> 
                    
                </div>
                    &nbsp;
          ";
    
     echo "           

            <div class='shareitbutton tooltip' id='shareitbutton' 
                 style='display:inline'
                title='Share Externally - Generate Links'
                data-folder='$folder' 
                data-filename='$showfilename' 
                data-sharetype='P'
                data-platform='E'
                data-mode=''
                data-alias=''
                data-page='$page'
                data-title='$title'>
                    <img class='icon25' src='../img/link-red-128.png' style='position:relative;top:0px;width:auto;margin:0;padding:0' /> 
                    </div>

            </span>
            
            ";
     /*
     if($_SESSION[superadmin]=='Y')
         
     echo "           
                    
            <div class='divbuttonshare divbutton3_unsel feedphotoshare tooltip' id='shareroombutton' 
                title='Share to a Room'
                data-page='$page'
                data-mode='P'
                data-photo='$roomshare'
                data-title='$title'>
                    <img src='../img/braxroom.png' style='position:relative;top:8px;height:50px;width:auto;margin:0;padding:0' /> 
                </div>

          ";
      * 
      */

     echo "<br><br></div>";   
}
function SharePhoto( $selectedalbum, $selectedalbumHtml, $page, $total )
{
    $folder = "photolib/";
    
    $socialshare = "<br><br>";
    if( $selectedalbum != "" && $selectedalbum[0]!='*'  && $total > 1)
    {
        $socialshare =
            "
            <br>
            <div class='sharealbumarea' style=';padding-left:10px;padding-right:10px'> 
                <span style='white-space:nowrap'>
               
                    <div class=' shareitbutton  tapped' id='shareitbutton' 
                        style='display:inline'
                        title='Share album to Facebook'
                        data-folder='$folder' 
                        data-filename='$selectedalbumHtml' 
                        data-sharetype='A'
                        data-page='$page'
                        data-platform='F'
                        data-mode=''
                        data-alias=''
                        data-title='$selectedalbumHtml'>
                        <img class='icon25' src='../img/facebook-red-128.png' style='position:relative;top:0px;width:auto;margin:0;padding:0' /> 

                    </div>
                    &nbsp;
                    <!--

                    <div class='shareitbutton tapped' id='shareitbutton' 
                        style='display:inline'
                        title='Share album to Twitter'
                        data-folder='$folder' 
                        data-filename='$selectedalbumHtml' 
                        data-sharetype='A'
                        data-page='$page'
                        data-platform='T'
                        data-mode=''
                        data-first=''
                        data-title='$selectedalbumHtml'>
                        <img class='icon25' src='../img/twitter-red-128.png' style='position:relative;top:0px;width:auto;margin:0;padding:0' /> 

                    </div>
                    &nbsp;
                    -->


                    <div class='shareitbutton tapped' id='shareitbutton' 
                        style='display:inline'
                        title='Share album Externally'
                        data-folder='$folder' 
                        data-filename='$selectedalbumHtml' 
                        data-sharetype='A'
                        data-page='$page'
                        data-platform='E'
                        data-mode=''
                        data-alias=''
                        data-title='$selectedalbumHtml'>
                        <img class='icon25' src='../img/link-red-128.png' style='position:relative;top:0px;width:auto;margin:0;padding:0' /> 
                    </div>
                </span>
                <br>
            </div>
             ";
    }
    return $socialshare;
}




function RotateImage( $rotateangle, $filename, $folder)
{
    $f = explode("_",$filename);
    $e = explode(".",$filename);
    $ext = strtolower($e[count($e)-1]);
    $uniqid = uniqid("",false);

    $newfilename= $f[0]."_".$f[1]."_".$uniqid.".$ext";
    saveAWSObject($filename, "$folder$filename");

    $img = new ImageManipulation();
    $img->load("$folder$filename");

    $img->rotate_image(intval($rotateangle), 'ffffff',0 );
    //$img->output();
    $img->save_image("$folder$newfilename", "$ext");
    putAWSObject($newfilename, "$folder$newfilename");

    $new_awsurl = getAWSObjectUrl($newfilename, "$folder$newfilename");


    do_mysqli_query("1","
        update photolib set filename='$newfilename', aws_url='$new_awsurl' 
            where providerid=$providerid and filename='$filename'
            ");

    unlink("$folder$filename");

    //unlink("$folder$newfilename");
    //
    deleteAWSObject($filename );


    $filename = $newfilename;
    $showfilename = "";
    return $filename;

}

function CreateAlbumList( $providerid, $selectedalbum, $selectedalbumHtml, $page, $total )
{
    global $global_background;
    global $global_textcolor;
    global $global_menu_color;
    global $global_titlebar_alt_color;
    global $global_titlebar_color;
    global $global_activetextcolor;
    global $global_icon_check;
    global $iconsource_braxcheck_common;
    global $iconsource_braxsettings_common;
    
    $selectedalbumSql = mysql_safe_string($selectedalbum);
    $selectedalbumDisplay = DeconvertHTML($selectedalbum);
    $albumexclude = " and photolib.album not like '* Artist%' ";
    if($_SESSION['enterprise']=='Y' && $_SESSION['superadmin']!='Y'){
        $albumexclude = " and photolib.album not like '* Artist%' 
            and photolib.album not in ('* Public - Artist Showcase','* Public - Faces', '* Public - Memes', '* Public - Selfie') 
         ";
    }
    
    $result = do_mysqli_query("1","
        select sharetype from photolibshare where
              providerid=$providerid and  album='$selectedalbumSql' 
        ");

        $icon1 = "";
        $icon2 = "";
        $icon3 = "";
        $icon4 = "<img class='icon15' src='$iconsource_braxcheck_common' />";
        $color1 = 'lightgray';
        $color2 = 'lightgray';
        $color3 = 'lightgray';
        $color4 = "$global_activetextcolor";
    
    if($row = do_mysqli_fetch("1",$result)){
        
        
        if($row['sharetype'] == 'C'){
            $icon1 = "<img class='icon15' src='$iconsource_braxcheck_common' />";
            $icon2 = "";
            $icon3 = "";
            $color1 = "$global_activetextcolor";
            $color2 = 'lightgray';
            $color3 = 'lightgray';
            $color4 = 'lightgray';
        }
        if($row['sharetype'] == 'F'){
            $icon1 = "";
            $icon2 = "<img class='icon15' src='$iconsource_braxcheck_common' />";
            $icon3 = "";
            $color1 = 'lightgray';
            $color2 = "$global_activetextcolor";
            $color3 = 'lightgray';
            $color4 = "lightgray";
        }
        if($row['sharetype'] == 'A'){
            $icon1 = "";
            $icon2 = "";
            $icon3 = "<img class='icon15' src='$iconsource_braxcheck_common' />";
            $color1 = 'lightgray';
            $color2 = 'lightgray';
            $color3 = "$global_activetextcolor";
            $color4 = "lightgray";
        }
    }
        
    $shared = "     
                <div class='showhiddenarea' style='display:none;margin:auto'>
                    <br>
                    ";
    $shared .= "     
                    <div 
                            class='photolibrary mainfont'
                            id='photoalbumitem tapped2' 
                            data-album='$selectedalbumHtml'
                            data-deletefilename='' 
                            data-filename='' 
                            data-page='1' 
                            data-rotate='' 
                            data-album=''
                            data-origalbum=''
                            data-save='FAMILYSHARE'
                            style='
                            display:inline-block;
                            margin-left:20px;padding:5px;cursor:pointer;
                            color:$color1'
                            > Shared with Family $icon1 </div>
                            <br>
                ";
    $shared .= "     
                    <div 
                            class='photolibrary mainfont'
                            id='photoalbumitem tapped2' 
                            data-album='$selectedalbumHtml'
                            data-deletefilename='' 
                            data-filename='' 
                            data-page='1' 
                            data-rotate='' 
                            data-album=''
                            data-origalbum=''
                            data-save='FRIENDSHARE'
                            style='
                            display:inline-block;
                            margin-left:20px;padding:5px;cursor:pointer;
                            color:$color2;'
                            > Shared with Friends $icon2 </div>
                            <br>
                ";
    $shared .= "     
                    <div 
                            class='photolibrary mainfont'
                            id='photoalbumitem tapped2' 
                            data-album='$selectedalbumHtml'
                            data-deletefilename='' 
                            data-filename='' 
                            data-page='1' 
                            data-rotate='' 
                            data-album=''
                            data-origalbum=''
                            data-save='ALLSHARE'
                            style='
                            display:inline-block;
                            margin-left:20px;padding:5px;cursor:pointer;
                            color:$color3;'
                            > Shared with All $icon3 </div>
                            <br>
                ";
    $shared .= "     
                    <div 
                            class='photolibrary mainfont'
                            id='photoalbumitem tapped2' 
                            data-album='$selectedalbumHtml'
                            data-deletefilename='' 
                            data-filename='' 
                            data-page='1' 
                            data-rotate='' 
                            data-album=''
                            data-origalbum=''
                            data-save='FRIENDUNSHARE'
                            style='
                            display:inline-block;
                            margin-left:20px;padding:5px;cursor:pointer;
                            color:$color4'
                            > Not Shared </div>
                ";
    $shared .= "     <hr>
                </div>            
                            ";

    if(substr($selectedalbum,0,9)=="* Public "){
        $shared = "";
    }

    $result2 = do_mysqli_query("1","
        select distinct photolib.public, photolib.album, photolibshare.sharetype 
            from photolib 
            left join photolibshare on photolibshare.providerid = photolib.providerid and photolibshare.album = photolib.album
            where
            ( ( photolib.providerid=$providerid and photolib.album!='' and photolib.album!='$selectedalbumSql' ) or photolib.public='Y')
            $albumexclude and (hide is null or hide='N')
            order by photolib.public asc, photolib.album asc
        ");
    
    
    $color = $global_titlebar_alt_color;//'#a1a1a4';
    //$colorpublic = $global_menu_color; //storm
    $colorpublic = "black";
    $folderdiv2 = "";   
    
    $folderdivback = "
        <a value='' 
                class='gridnoborder photolibrary'
                id='photoalbumitem tapped2' 
                data-deletefilename='' data-filename='' 
                data-page='1' data-rotate='' 
                data-album=''
                data-origalbum=''
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
                            <img class='icon20' title='Back to All Pics' src='../img/Arrow-Left-in-Circle_120px.png' style='top:0px' />
                        </div>
        </a>
            ";
 
 
    $foldercount = 0;
    

    while( $row2 = do_mysqli_fetch("1",$result2)){

        $shareflag = '';
        if($row2['sharetype']=="F" || $row2['sharetype']=='C'  || $row2['sharetype']=='A'){
            $shareflag = "<img class='' src='$iconsource_braxcheck_common' style='height:12px;position:relative;top:3px' />";
        }
        $foldername_short = substr($row2['album'],0,25);
        if(strlen($row2['album'])>25){
            $foldername_short .= "...";
        }
        $color1 = $color;
        if($row2['public']=='Y'){
            $color1 = $colorpublic;
        }
        $albumHtml = ConvertHTML($row2['album']);
        $folderdiv2 .= "
            <a value='$albumHtml' 
                class='photolibrary smalltext photoalbumdrop'
                id='photoalbumitem tapped2' 
                data-deletefilename='' data-filename='' 
                data-page='1' data-rotate='' 
                data-album='$albumHtml'
                style='border-width:1px;border-color:$global_background;border-style:solid;
                display:inline-block;margin:0px;padding-top:3px;padding-bottom:5px;
                border-top-left-radius:10px; 
                border-top-right-radius:10px; 
                text-decoration:none;
                cursor:pointer;
                background-color:$color1;width:280px;min-width:25%;vertical-align:middle;
                color:white;
                '
                >
                <div title='Album' style='padding-left:10px;padding-top:5px;padding-bottom:5px'>
                $shareflag $foldername_short  
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
    
    $socialshare = "";//  SharePhoto( $selectedalbum, $selectedalbumHtml, $page, $total );
    
        $folder['container'] ="";
    
        if($selectedalbum==''){
            $tablelength = $foldercount * 130;

            $folder['container'] =
                    "
                <div class='gridstdborder' style='border-radius:5px;border-color:gray;margin:auto;background-color:$global_background;color:$global_textcolor;vertical-align:top;text-align:center'>
                <div style='height:auto;word-break;break-all;word-wrap:break-word;margin:auto;vertical-align:top'>
                ";
            $folder['container'] .=
            "
            <div class='tabcontainer gridnoborder' style='max-width:100%;overflow:hidden;text-align:left;margin:0;vertical-align:top;'>
                <div class='tabwrapper tabwrappertall gridnoborder' style='width:$tablelength px;overflow-x:hidden;overflow-y:visible;text-align:left;'>
                    <div class='tablist' id='myTab' style='height:80px;margin:0;text-align:left;padding:0'>
                        $folderdiv2
                    </div>
                </div>
                <div class='tabenlarge' title='Enlarge Album List' style='display:none;cursor:pointer;padding-left:10px;padding-top:10px;padding-bottom:10px'><img class='icon15' src='../img/arrowhead-down-gray-128.png' /> </div>
                <div class='tabshrink' title='Shrink Album List' style='display:inline-block;cursor:pointer;padding-left:10px;padding-top:10px;padding-bottom:10px'><img class='icon15' src='../img/arrowhead-up-gray-128.png' /> </div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='smalltext2'><img class='' src='$iconsource_braxcheck_common' style='height:12px;position:relative;top:3px' /> Shared</span>
            </div>

            ";
            $folder['container'] .=
                "</div></div>";
        } else {
            
            $settings = "";
            if( $_SESSION['superadmin']=='Y' || substr($selectedalbum,0,8)!='* Public'){
                $settings = "
                            <img class='icon20 showhidden' src='$iconsource_braxsettings_common' title='Album Settings' style='float:right;margin-top:20px;margin-right:25px' />
                            ";
            }
            
            $folder['container'] .=
                "
                        <a value='' 
                                class='photolibrary'
                                id='photoalbumitem tapped2' 
                                data-deletefilename='' data-filename='' 
                                data-page='1' data-rotate='' 
                                data-album=''
                                data-origalbum=''
                                style='
                                text-decoration:none;
                                '
                                >
                                        <div class='gridnoborder smalltext'
                                            style='
                                            margin-top:10px;
                                            display:inline-block;
                                            text-decoration:none;
                                            padding-left:10px;
                                            cursor:pointer;
                                            vertical-align:middle;
                                            color:white;'
                                        >
                                            <div class='smalltext'
                                                class='photolibrary'
                                                title='Current Album'
                                                data-deletefilename='' data-filename='' 
                                                data-page='$page' data-rotate='' 
                                                data-album='$selectedalbumHtml'

                                                style='border-width:1px;border-color:$global_background;border-style:solid;
                                                display:inline-block;margin:0px;padding-top:10px;padding-bottom:10px ;width:250px;min-width:150px;
                                                border-top-left-radius:10px; 
                                                border-top-right-radius:10px; 
                                                text-decoration:none;
                                                padding-left:10px;
                                                cursor:pointer;
                                                background-color:black;
                                                color:white;'
                                            >
                                                <img class='icon20' title='Back to All Pics' src='../img/Arrow-Left-in-Circle-White_120px.png' style='top:5px' />
                                                $selectedalbumDisplay
                                            </div>
                                        </div>
                        </a> 
                        $settings
                        <hr style='margin:0'>
                        $shared
                    <br>
                        $socialshare
                ";
        }
        
    
    
    $folder['div'] = $folderdiv2;
    $folder['back'] = $folderdivback;
    $folder['count'] = $foldercount;
    return (object) $folder;
             
}

function AlbumMenu($providerid, $selectedalbum, $page)
{
    $selectedalbumHtml = ConvertHTML($selectedalbum);
    $selectedalbumSql = mysql_safe_string($selectedalbum);
            
     $albumselect = "";  
     $albummenu = "<ul id='photomenu' style='height:500px;overflow:scroll'>";  
     $albummenu .=      "<li id='photoalbumitem' data-album='(New)'>(New)</li>";
     $albummenu .=      "<li id='photoalbumitem' data-album='$selectedalbumHtml'>$selectedalbum</li>";
     
    $result2 = do_mysqli_query("1","
        select distinct album from photolib where ( providerid = $providerid )
            and album!='' and album!='All' and 
                album!='* Artist Submission' order by album asc
        ");
    
    $albumselect .= "
            <option value='(New)' selected='selected'>(New)</option>";
    while( $row2 = do_mysqli_fetch("1",$result2)){

        $albumitem = ConvertHTML($row2['album']);
        $albumitem2 = DeconvertHTML($row2['album']);
        $selected = "";
        if($albumitem2 == DeconvertHTML($selectedalbum)){
            $selected = "selected=selected";
        }
        $albumselect .= "
            <option value='$albumitem' $selected>$albumitem2</option>        
                ";
        $albummenu .= 
                "<li id='photoalbumitem' class='tapped2' ".
                "data-deletefilename='' data-filename='' data-page='$page' data-rotate='' ".
                "data-album='$albumitem'>$albumitem2</li>";
    }
        
    if( $_SESSION['superadmin']=='Y' || $_SESSION['superadmin']=='A'){

        $result2 = do_mysqli_query("1","
            select distinct album from photolib where 
                public='Y'
                  and album!='' and album!='All' and album!='$selectedalbumSql'
                 order by album asc
            ");
    } else {
            
        $result2 = do_mysqli_query("1","
            select distinct album from photolib where  public='Y'
                and album!='' and album!='All' and album!='$selectedalbumSql' and album!='* Artist Submission' order by album asc
            ");
    }
        
    $albumselect .= "
        <option value=''>________________</option>        
        <option value=''></option>        
            ";
    $albummenu .= 
            "<li id='photoalbumitem' ".
            "data-deletefilename='' data-filename='' data-page='$page' data-rotate='' ".
            "data-album=''>________________</li>";

    
    while( $row2 = do_mysqli_fetch("1",$result2)){

        $albumitem = ConvertHTML($row2['album']);
        $albumitem2 = DeconvertHTML($row2['album']);
        $albumselect .= "
            <option value='$albumitem'>$albumitem2</option>        
                ";
        $albummenu .= 
                "<li id='photoalbumitem' ".
                "data-deletefilename='' data-filename='' data-page='$page' data-rotate='' ".
                "data-album='$albumitem'>$albumitem2</li>";
    }
    
    $albumselect .= "
        <option value=''>________________</option>        
            ";
    $albumselect .= "
            <option value='(New)'>(New)</option>";
    
    
    $albummenu .= 
            "<li id='photoalbumitem' ".
            "data-deletefilename='' data-filename='' data-page='$page' data-rotate='' ".
            "data-album=''>________________</li>";
        
    $albumselect .= "
        </select>
                ";
    $albummenu .= "</ul>";

    $array['albumselect'] = $albumselect;
    $array['albummenu'] = $albummenu;

    return (object) $array;
        
            
}
function ConvertHTML( $text )
{
        return rawurlencode(stripslashes($text));
}
function DeconvertHTML( $text )
{
        return stripslashes(rawurldecode($text));
}

function PhotoTip()
{
        global $global_textcolor;
            
        echo "
                <div class='pagetitle3' 
                    style='padding:20px;text-align:center;margin:auto;max-width:260px;width:80%;color:$global_textcolor;background-color:transparent'>
                    <div class='circular3' style=';overflow:hidden;margin:auto'>
                        <img class='' src='../img/agent.jpg' style='width:100%;height:auto' />
                    </div>
                    <div class='tipbubble pagetitle2a' style='padding:30px;color:black;background-color:whitesmoke'>
                        Upload your photos here for FREE. You can then share albums if you wish to all or selected friends.
                        <br><br>
                        Photos are metadata free.
                    </div>
                    <br>
                </div>
        </div>
        ";
}

?>
