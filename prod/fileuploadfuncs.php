<?php
require("validsession.inc.php");
require_once("crypt.inc.php");
require_once("notify.inc.php");
     //Brax.ME
    function braxmecleanup($upload_dir) 
    {
            try {
                if(file_exists($upload_dir."thumbnail")){
                    
                    array_map('unlink', glob($upload_dir."thumbnail/*.*"));                    
                    rmdir($upload_dir."thumbnail");
                }
            } catch ( Exception $e){
            }
            
            try {
                if(file_exists($upload_dir."medium")){
                    array_map('unlink', glob($upload_dir."medium/*.*"));                    
                    rmdir($upload_dir."medium");
                }
            } catch ( Exception $e){
            }
            
            try {
                if(file_exists($upload_dir."large")){
                    array_map('unlink', glob($upload_dir."large/*.*"));                    
                    rmdir($upload_dir."large");
                }
            } catch ( Exception $e){
            }
            
            $upload_dir2 = substr($upload_dir,0, strlen($upload_dir)-1);
            
            try {
                if(file_exists($upload_dir2)){
                    array_map('unlink', glob($upload_dir2."/*.*"));                    
                    rmdir($upload_dir2);
                }
            } catch ( Exception $e){
            }
        
    }

    function braxmeimportNew($origfilename, $upload_dir, $filetypes ) 
    {
            //$origfilename = preg_replace("/[^.a-zA-Z0-9]/", "", $origfilename1);        
            $physical_filename = $upload_dir.$origfilename;
            $fsize = filesize( $physical_filename );
            if($fsize == 0){
                error_log("FileUpload: $physical_filename 0 Bytes");
                return;
            }
            
            if(!file_exists($physical_filename)){
                error_log("$physical_filename target file not found");
                return;
            }
            
            $unique_id = uniqid("", false);
            $providerid = $_SESSION['pid'];

            $filename = explode(".", $origfilename );
            $filenameext = strtolower($filename[count($filename)-1]); 
            unset($filename[count($filename)-1]); 
            $filename = implode(".", $filename); 
            $filename = substr($filename, 0, 15).".".$filenameext; 
            
            
            $aws_filename= $providerid."_".$unique_id.".$filenameext";
            
            error_log("$aws_filename ready");
            
            $alias = uniqid("T4AZ", true);

            $filefolder = "";
            $folderid= 0;
            if(isset($_SESSION['filefolder'])){
                $filefolder = $_SESSION['filefolder'];
            }
            if(isset($_SESSION['filefolderid'])){
                $folderid = $_SESSION['filefolderid'];  
            }
                                        
            if(intval($folderid)==0) {
                $folderid = 0;
            }
            
            
            $duplicatechecked_origfilename = DuplicateCorrect($providerid, str_replace("'","",$origfilename), $folderid);
            //$encrypted_origfilename = $origfilename;
            $encrypted_title = $origfilename;
            $encoding = 'PLAINTEXT';
            
            
            //Encrypt Test
            $nVideo = false;
            $fileencoding = $_SESSION['responseencoding'];
            $fileStreamTypes = array("mp4","mp3","mov","m4a","wav","m4v","pptx","ppt","avi");
            foreach( $fileStreamTypes as $fileStreamType ){

                if(strtolower($filenameext)==$fileStreamType){
                    if(
                      $fileStreamType == 'mp4' ||
                      $fileStreamType == 'mov' 
                      ){
                            $nVideo = true;
                            $fileencoding = 'PLAINTEXT';
                            break;
                      }
                }
            }
            if($fsize > 10000000 ){
                $fileencoding = 'PLAINTEXT';
            }
            if( $nVideo == true ){
                exec("ffmpeg -vcodec png -i $physical_filename -ss  00:00:00 -vframes frames $upload_dir"."video-preview.png");            
                
            }
            if($fileencoding != 'PLAINTEXT'){
                StreamEncode($physical_filename, $physical_filename.".aes", $fileencoding );
                $physical_filename = $physical_filename.".aes";
            }
            
            if(!file_exists($physical_filename)){
                error_log("$physical_filename target file not found");
                return;
            }
                    
            $result = do_mysqli_query("1", 
                    "
                        insert into filelib
                        ( providerid, filename, origfilename, folder, folderid, 
                          filesize, filetype, title, createdate, alias, encoding, fileencoding, status )
                        values
                        ( $providerid, '$aws_filename','$duplicatechecked_origfilename', 
                          '$filefolder',$folderid, $fsize, '$filenameext','$encrypted_title', now(), '$alias','$encoding','$fileencoding','Y' ) 
                     "
             );
            putAWSObject("$aws_filename",$physical_filename );
            
            
            
            ChatLink($providerid, $alias,"F" );
            CaseLink($providerid, $aws_filename );


    }
    
    //Brax.ME
    function braxmeimport($origfilename, $upload_dir, $filetypes ) 
    {
        
            return braxmeimportNew($origfilename, $upload_dir, $filetypes );
            
            if($_SESSION['superadmin']=='Y'){
                return braxmeimportNew($origfilename, $upload_dir, $filetypes );
            }
        
            $unique_id = uniqid("", false);
        
            $providerid = $_SESSION['pid'];
            //$upload_dir = $this->get_upload_path();

            $filename = explode(".", $origfilename );
            $filenameext = strtolower($filename[count($filename)-1]); 
            unset($filename[count($filename)-1]); 
            $filename = implode(".", $filename); 
            $filename = substr($filename, 0, 15).".".$filenameext; 
            $file_ext_allow = FALSE; 
            //$filetypes = options['file_types'];
            $validType = false;
            
            
            
            $origfilename = str_replace("'", "", $origfilename);        
            
            $uploadfilename= $providerid."_".$unique_id.".$filenameext";
            //rename( $upload_dir.$origfilename, $upload_dir.$uploadfilename);
            $attachmentfilename = $uploadfilename;
            
            $alias = uniqid("T4AZ", true);
            $fsize = filesize( $upload_dir.$origfilename );

                                        
            if(isset($_SESSION['filefolder'])){
                $filefolder = $_SESSION['filefolder'];
            }
                                        
            $alias = uniqid("T4AZ", true);
            $filefolder = $_SESSION['filefolder'];
            $folderid = $_SESSION['filefolderid'];
            if(intval($folderid)==0) {
                $folderid = 0;
            }
            
            
            $encrypted_origfilename = DuplicateCorrect($providerid, $origfilename, $folderid);
            //$encrypted_origfilename = $origfilename;
            $encrypted_subject = '';
            $encoding = 'PLAINTEXT';
            
            
            //Encrypt Test
            $fileencoding = 'PLAINTEXT';
            $encodedflag = "";
            
                    
            $result = do_mysqli_query("1", 
                    "
                        insert into filelib
                        ( providerid, filename, origfilename, folder, folderid, 
                          filesize, filetype, title, createdate, alias, encoding, fileencoding, status )
                        values
                        ( $providerid, '$attachmentfilename','$encrypted_origfilename', 
                          '$filefolder',$folderid, $fsize, '$filenameext','$encrypted_subject', now(), '$alias','$encoding','$fileencoding','Y' ) 
                     "
             );
                    

            putAWSObject("$attachmentfilename",$upload_dir.$origfilename.$encodedflag);
            try {
                rmdir($upload_dir."thumbnail");
            } catch ( Exception $e){
            }
            
            try {
                rmdir($upload_dir."medium");
            } catch ( Exception $e){
            }
            
            try {
                rmdir($upload_dir."large");
            } catch ( Exception $e){
            }
            
            
            try {
                rmdir($upload_dir);
            } catch ( Exception $e){
            }
            


    }
    

    function braxmeimportphotos($origfilename, $upload_dir, $filetypes ) 
    {
            //$origfilename = preg_replace("/[^.a-zA-Z0-9]/", "", $origfilename1);        
        
            try {
                if(file_exists($upload_dir.$origfilename)){
                    unlink($upload_dir.$origfilename);
                }
            } catch ( Exception $e){
            }
            
            try {
                if(file_exists($upload_dir."thumbnail/".$origfilename)){
                    unlink($upload_dir."thumbnail/" .$origfilename);
                }
            } catch ( Exception $e){
            }
        
        
            $unique_id = uniqid("", false);
        
            $today = date("M-d-y",time()+$_SESSION['timezone']*60*60);
            $album = "Upload-".$today;
            $subject = "Photo";
            $providerid = $_SESSION['pid'];
            //$upload_dir = $this->get_upload_path();

            $filename = explode(".", $origfilename );
            $filenameext = strtolower($filename[count($filename)-1]); 
            unset($filename[count($filename)-1]); 
            $filename = implode(".", $filename); 
            $filename = substr($filename, 0, 15).".".$filenameext; 
            $file_ext_allow = FALSE; 
            //$filetypes = $this->options['file_types'];
            $validType = false;
            for($x=0;$x<count($filetypes);$x++){
                if(strtolower($filenameext)==$filetypes[$x]){
                    $validType = true;
                }
            }
            if($validType == false){
                unlink($upload_dir.$origfilename);
                return;
            }
            
            $basefilename = hash('ripemd128',"$providerid".$unique_id);
            
            $uploadfilename= $basefilename.".$filenameext";
            //rename( $upload_dir.$origfilename, $upload_dir.$uploadfilename);
            $attachmentfilename = $uploadfilename;
            $attachmentfilename_large = $basefilename."_L.$filenameext";
            
            $alias = uniqid("T4AZ", true);
            $filesize = filesize( $upload_dir."medium/".$origfilename );


            $result = do_mysqli_query("1", 
                    "
                        insert into photolib
                        ( providerid, album, filename, folder, filesize, filetype, title, createdate, alias, owner, f_filename )
                        values
                        ( $providerid, '$album', '$attachmentfilename', '$upload_dir',$filesize, '$filenameext','$subject', now(), '$alias', $providerid, '$attachmentfilename_large' ) 
                     "
             );

            putAWSObject("$attachmentfilename",$upload_dir."medium/".$origfilename);
            //putAWSObject("$attachmentfilename_large",$upload_dir."".$origfilename);
            
            //Add Photo to Chat if Applicable
            ChatLink($providerid, $alias,"P" );
            
            

    }
    
    function ChatLink($providerid, $alias, $mode )
    {
        global $rootserver;
        global $installfolder;
        global $appname;
        
        $lastfunc = GetLastFunction($providerid, 0);
        if($lastfunc->lastfunc==='C')
        {
            $chatid = intval($lastfunc->parm1);    
            //$message = "$imgurl";
                //echo "<br>Chat ID Ref:$chatid";
            if($mode == 'P'){
                $imgurl = "<img class='feedphotochat' src='$rootserver/$installfolder/sharedirect.php?a=$alias&f=*.jpg' alt='Loading Image...'/>";

                $message = "$imgurl";
                $encode = EncryptChat ($message,"$chatid","" );
                $encodeshort = EncryptChat ("Photo Uploaded","$chatid","" );

            }
            if($mode == 'F'){
                $imgurl = "<a href='$rootserver/$installfolder/doc.php?p=$alias' >$appname Download Link</a>";

                $message = "File Upload<br><br>$imgurl";
                $encode = EncryptChat ($message,"$chatid","" );
                $encodeshort = EncryptChat ("File Uploaded","$chatid","" );

            }

            $result = do_mysqli_query("1",
                "
                    insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                    values
                    ( $chatid, $providerid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
                ");
            $result = do_mysqli_query("1",
                "
                update chatmembers set lastmessage=now(), lastread=now() where providerid= $providerid and chatid=$chatid and status='Y'
                ");
            $result = do_mysqli_query("1",
                "
                update chatmaster set lastmessage=now() where chatid=$chatid 
                ");
            
            ChatNotificationRequest($providerid, $chatid, $encodeshort, $_SESSION['responseencoding'],'P');
            SaveLastFunction($providerid,"C", "$chatid");
        }
        
    }
    
    function CaseLink($providerid, $filename  )
    {
        global $rootserver;
        global $installfolder;
        global $appname;

        $casefolderid = 0;
        if(isset($_SESSION['casefolderid'])){
            $casefolderid = intval($_SESSION['casefolderid']);
        }
        $lastfunc = GetLastFunction($providerid, 0);
        if($lastfunc->lastfunc==='X')
        {
            $caseid = intval($lastfunc->parm1);    
            do_mysqli_query("1","insert into casefiles (caseid, filename, createdate, providerid, downloads, folderid) 
                values ($caseid, '$filename', now(), $providerid, 0, $casefolderid ) 
                    ");
            
            SaveLastFunction($providerid,"X", "$caseid");
            //exit();
        }
    }
    
    
    //Brax.ME
    function DuplicateCorrect($providerid, $origfilename, $folderid) {
        
        $filename = $origfilename;
        $matched = true;
        while($matched)
        {
            //No Encryption Currently
            $filename_encrypted = $filename;
            $result = do_mysqli_query("1", 
                    "
                        select * from filelib 
                        where providerid = $providerid and origfilename = '$filename_encrypted' and status='Y'
                        and folderid = $folderid
                     "
             );
            if(!$row = do_mysqli_fetch("1",$result)){
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
?>