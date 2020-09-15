<?php
require_once("validsession.inc.php");
require_once("crypt.inc.php");
require_once("notify.inc.php");

     //Brax.ME
    function braxmecleanup($upload_dir) 
    {
            try {
                if(file_exists($upload_dir."thumbnail")){
                    rmdir($upload_dir."thumbnail");
                }
            } catch ( Exception $e){
            }
            
            try {
                if(file_exists($upload_dir."medium")){
                    rmdir($upload_dir."medium");
                }
            } catch ( Exception $e){
            }
            
            try {
                if(file_exists($upload_dir."large")){
                    rmdir($upload_dir."large");
                }
            } catch ( Exception $e){
            }
            
            $upload_dir2 = substr($upload_dir,0, strlen($upload_dir)-1);
            
            try {
                if(file_exists($upload_dir2)){
                    rmdir($upload_dir2);
                }
            } catch ( Exception $e){
            }
        
    }

    function textsavefile($contents, $upload_dir, $origfilename, $roomid ) 
    {
            mkdir ( $upload_dir, 0755 , true );        
            $unique_id = uniqid("", false);
            $physical_filename = $upload_dir."/".$unique_id.".txt";

            
            
            $providerid = $_SESSION['pid'];

            $filename = strtolower($origfilename);
            $filenameext = "txt";
            
            $aws_filename= $providerid."_".$unique_id.".$filenameext";
            

            
            $alias = uniqid("T4AZ", true);

            $filefolder = "";
            $folderid= 0;
            if(isset($_SESSION['filefolder'])){
                $filefolder = $_SESSION['filefolder'];
                $folderid = $_SESSION['filefolderid'];
            }
                                        
            if(intval($folderid)==0) {
                $folderid = 0;
            }
            
            $filename2 = explode(".", $origfilename );
            $filenameext2 = strtolower($filename2[count($filename2)-1]); 
            if($filenameext2!='txt'){
                $origfilename = $origfilename.".txt";
            }
            
            $duplicatechecked_origfilename = DuplicateCorrect($providerid, str_replace("'","",$origfilename), $folderid);
            //$encrypted_origfilename = $origfilename;
            $encrypted_title = $origfilename;
            $encoding = 'PLAINTEXT';
            
            
            //Encrypt Test
            
            $fileencoding = $_SESSION['responseencoding'];

            //file_put_contents($physical_filename.".test", $contents );
           
            TextStreamEncode($contents, $physical_filename.".aes", $fileencoding );
            $physical_filename = $physical_filename.".aes";
            $fsize = strlen($contents);
            
            
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
            
            braxmecleanup($upload_dir."/");
            
            do_mysqli_query("1","
                insert into roomfiles (roomid, providerid, filename, folderid, createdate, downloads)
                values
                ($roomid, $providerid, '$aws_filename',0, now(), 0 )
                    ");


    }
    function texteditfile($contents, $upload_dir, $origfilename, $roomid ) 
    {
            mkdir ( $upload_dir, 0755 , true );        
            $unique_id = uniqid("", false);
            $physical_filename = $upload_dir."/".$unique_id.".txt";
                

            
            
            $providerid = $_SESSION['pid'];

            $filename = $origfilename;
            $filenameext = "txt";
            
            $aws_filename= $origfilename;
            
            $fileencoding = $_SESSION['responseencoding'];

            //file_put_contents($physical_filename.".test", $contents );
           
            TextStreamEncode($contents, $physical_filename.".aes", $fileencoding );
            $physical_filename = $physical_filename.".aes";
            $fsize = strlen($contents);
            
            
            if(!file_exists($physical_filename)){
                error_log("$physical_filename target file not found");
                return; 
            }
                    
            $result = do_mysqli_query("1", 
                    "  update filelib set filesize = $fsize, fileencoding = '$fileencoding'
                       where filename = '$aws_filename'
                     "
             );
            deleteAWSObject( $aws_filename );
            putAWSObject("$aws_filename",$physical_filename );
            
            braxmecleanup($upload_dir."/");

    }
    
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