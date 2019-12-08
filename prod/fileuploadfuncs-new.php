<?php

 
    //Brax.ME
    function braxmeimport($origfilename, $upload_dir, $filetypes ) 
    {
        
        
            $physical_filename = $upload_dir.$origfilename;
            $fsize = filesize( $physical_filename );
            if($fsize == 0){
                return;
            }
            
            
            $unique_id = uniqid("", false);
            $providerid = $_SESSION['pid'];

            $filename = explode(".", $origfilename );
            $filenameext = strtolower($filename[count($filename)-1]); 
            unset($filename[count($filename)-1]); 
            $filename = implode(".", $filename); 
            $filename = substr($filename, 0, 15).".".$filenameext; 
            
            /* Removed File Extension Check
             * 
            $file_ext_allow = FALSE; 
            //$filetypes = options['file_types'];
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
             * 
             */
            
            
            //$origfilename = str_replace("'", "", $origfilename);        
            
            $aws_filename= $providerid."_".$unique_id.".$filenameext";
            
            $alias = uniqid("T4AZ", true);

                                        
            if(isset($_SESSION['filefolder'])){
                $filefolder = $_SESSION['filefolder'];
            }
                                        
            $filefolder = $_SESSION['filefolder'];
            $folderid = $_SESSION['filefolderid'];
            if(intval($folderid)==0) {
                $folderid = 0;
            }
            
            
            $duplicatechecked_origfilename = DuplicateCorrect($providerid, str_replace("'","",$origfilename), $folderid);
            //$encrypted_origfilename = $origfilename;
            $encrypted_title = '';
            $encoding = 'PLAINTEXT';
            //$subject = $origfilename;
            //$encrypted_origfilename = EncryptText($origfilename,"$attachmentfilename");
            //$subject = $origfilename;
            //$encrypted_subject = EncryptText($subject,"$attachmentfilename");
            
            
            //Encrypt Test
            $fileencoding = 'PLAINTEXT';
            
            
            if($providerid == 690001027){
            
                $fileStreamTypes = array("pdf","jpg","txt");
                foreach( $fileStreamTypes as $fileStreamType ){
                    
                    if(strtolower($filenameext)==$fileStreamType){
                        $fileencoding = 'AES';
                        break;
                    }
                }
                if($fileencoding == 'AES'){
                    StreamEncode($physical_filename );
                    //$physical_filename = StreamEncode($physical_filename );
                    //$physical_filename = StreamDecode($physical_filename );
                    $fileencoding = "PLAINTEXT";
                }
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
            
            
            /*  
            if( 
                $filenameext == 'jpg' || 
                $filenameext == 'jpeg' ||
                $filenameext == 'png' ||
                $filenameext == 'gif' ||
                $filenameext == 'tiff' ||
                $filenameext == 'tif' 
              )
            {
                $unique_id = uniqid("", false);
                $today = date("M-d-y",time()+$_SESSION['timezone']*60*60);
                $album = "Upload-Files".$today;
                $subject = "Photo";

                $uploadfilename= $providerid."_".$unique_id.".$filenameext";
                $attachmentfilename = $uploadfilename;

                $alias = uniqid("T4AZ", true);
                $filesize = filesize( $upload_dir."medium/".$origfilename );


                $result = do_mysqli_query("1", 
                        "
                            insert into photolib
                            ( providerid, album, filename, folder, filesize, filetype, title, createdate, alias, owner )
                            values
                            ( $providerid, '$album', '$attachmentfilename', '',$filesize, '$filenameext','$subject', now(), '$alias', $providerid ) 
                         "
                 );

                putAWSObject("$attachmentfilename",$upload_dir."medium/".$origfilename);
                
                unlink($upload_dir."thumbnail/".$origfilename);
            }
            else 
            {
                //unlink($upload_dir."medium/".$origfilename);
            }
            */

    }

    function braxmeimportphotos($origfilename, $upload_dir, $filetypes ) 
    {
        
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
            
            $uploadfilename= $providerid."_".$unique_id.".$filenameext";
            //rename( $upload_dir.$origfilename, $upload_dir.$uploadfilename);
            $attachmentfilename = $uploadfilename;
            $attachmentfilename_large = $providerid."_".$unique_id."_L.$filenameext";
            
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

            try {
                unlink($upload_dir."medium  /".$origfilename);
            } catch ( Exception $e){
            }
            
            try {
                unlink($upload_dir."thumbnail/".$origfilename);
            } catch ( Exception $e){
            }

            try {
                unlink($upload_dir."".$origfilename);
            } catch ( Exception $e){
            }
            
            
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
    function StreamEncode($source){
        
        $passphrase = "secret";
        $encodedflag = ".aes";
        
        $iv = substr(md5('iv'.$passphrase, true), 0, 8);
        $key = substr(md5('pass1'.$passphrase, true) . 
                       md5('pass2'.$passphrase, true), 0, 24);
        $opts = array('iv'=>$iv, 'key'=>$key);

        $target = $source.$encodedflag;
        
        $fp1 = fopen( $source, 'r');
        $fp2 = fopen( $target, 'w');
        
        
        stream_filter_prepend($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        //stream_filter_prepend($fp2, 'mdecrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        
        while (!feof($fp1)) {
            $contents = fread($fp1, 0xFFFF);
            fwrite($fp2, $contents);
        }        
        
        fclose($fp1);        
        fclose($fp2);        
        return "$target";
        
    }
    function StreamDecode($source){
        
        $passphrase = "secret";
        $encodedflag = ".aes-decoded";
        
        $iv = substr(md5('iv'.$passphrase, true), 0, 8);
        $key = substr(md5('pass1'.$passphrase, true) . 
                       md5('pass2'.$passphrase, true), 0, 24);
        $opts = array('iv'=>$iv, 'key'=>$key);

        $target = substr($source,-4);
        
        $fp1 = fopen( $source, 'r');
        $fp2 = fopen( $target, 'w');
        
        //stream_filter_prepend($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        //stream_filter_prepend($fp1, 'mdecrypt.rijndael-128', STREAM_FILTER_READ, $opts);
        
        while (!feof($fp1)) {
            $contents = fread($fp1, 0xFFFF);
            fwrite($fp2, $contents);
        }        
        
        fclose($fp1);        
        fclose($fp2);        
        return "$target";
        
    }
?>