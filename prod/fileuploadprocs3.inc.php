<?php
require_once("config-pdo.php");
require_once("aws.php");
require_once("crypt-pdo.inc.php");


function ProcessUpload( $providerid, $encoding, $subject, $upload_hdr, $uploadtype, $folder, $sendemail, $chatid, $passkey64 )
{
        $max_file_size = 200000000; 
        //$max_file_size = 504800; 
        $num_of_uploads=1;
        $file_types_array=array(
            "pdf",
            "docx",
            "doc",
            "txt",
            "ppt",
            "pptx",
            "xls",
            "xlsx",
            "mp3",
            "mp4",
            "avi",
            "zip",
            "tar",
            "rtf",
            "csv",
            "xml",
            "wav",
            "wma",
            "aif",
            "m4a",
            "jpg",
            "png",
            "tif",
            "gif",
            "bmp",
            "apk",
            "mov",
            "m4p",
            "pages",
            "keynote",
            "numbers",
            "epub");
        
        
        $filefolder = '';
        if(isset($_SESSION['filefolder'])){
            $filefolder = $_SESSION['filefolder'];
        }
        //who is other?
        $uploadprovider = 0;
        $result = pdo_query("1", 
          "select providerid, providername from provider where
              (replyemail = '$sendemail' or (handle = '$sendemail' and '$sendemail' != '')) and active='Y' "
        );
        if( $row = pdo_fetch($result))
        {
            $uploadprovider = $row['providerid'];
            $uploadprovidername = $row['providername'];
            echo "Copy sent to $sendemail<br>";
        }
        //if($sendemail!=''){
        //    echo "Copy to $sendemail<br>";
        //}
        
        //$upload_hdr="photolib";    
        $upload_dir=$upload_hdr."/";    

        
        
        //if( $encoding == "")
        //    $encoding = "BASE64";
        
        //$encoding = "3DES";
        //$encoding = "";
        //

        //$upload_provdir="upload/$providerid";    
        //if( !file_exists( $upload_provdir ))
        //{
        //    mkdir( $upload_provdir ); 
        //    
        //}
        
        
        $status = true;
        
        $attachmentpath=addslashes($upload_dir);
        $attachmentfilename="";
        
        $UploadNo = 0;
        
        //Check Max File Size of Entire Batch
        $sizetotal = 0;
        foreach($_FILES["file"]["error"] as $key => $value)
        { 
            $sizetotal += $_FILES["file"]["size"][$key];
        }
        if($sizetotal > $max_file_size )
        {
            echo( "Batch exceeded Max Size limit. Upload cancelled<br />"); 
            $status = false;
            return false;
        }
        
        foreach($_FILES["file"]["error"] as $key => $value)
        { 
            $UploadNo +=1;
            
            if($_FILES["file"]["name"][$key]!="")
            { 
                $origfilename = $_FILES["file"]["name"][$key]; 
                $origfilename = str_replace("%20","-", $origfilename);
                
                if($value==UPLOAD_ERR_OK)
                { 
                        
                        //echo "<br>Processed Upload: $origfilename<br> ";
                        $origfilename = $_FILES["file"]["name"][$key]; 
                        
                        $filename = explode(".", $_FILES["file"]["name"][$key]); 
                        $filenameext = strtolower($filename[count($filename)-1]); 
                        unset($filename[count($filename)-1]); 
                        $filename = implode(".", $filename); 
                        $filename = substr($filename, 0, 15).".".$filenameext; 
                        $file_ext_allow = FALSE; 
                        for($x=0;$x<count($file_types_array);$x++)
                        { 
                            if(strtolower($filenameext)==$file_types_array[$x])
                            { 
                              $file_ext_allow = TRUE; 
                            }           
                        } 
                        if($file_ext_allow)
                        { 
                            $fileencoding = 'PLAINTEXT';
                            //echo "Uploading Size: ".$_FILES['file']['size'][$key]."<br>";
                            if($_FILES["file"]["size"][$key]<$max_file_size)
                            { 
                                    $physical_filename = $_FILES["file"]["tmp_name"][$key];
                                    $fsize = filesize($physical_filename);
                                    
                                    
                                    if( $fsize > 0 )
                                    {
                                        
                                        /* Encoding */

                                        $fileencoding = $_SESSION['responseencoding'];
                                        $fileStreamTypes = array("mp4","mp3","mov","m4a","wav","m4v");
                                        foreach( $fileStreamTypes as $fileStreamType ){

                                            if(strtolower($filenameext)==$fileStreamType){
                                                $fileencoding = 'PLAINTEXT';
                                                break;
                                            }
                                        }
                                        if($fileencoding != 'PLAINTEXT'){
                                            $uniqueid = uniqid();
                                            StreamEncode($physical_filename, $physical_filename.".aes", $fileencoding );
                                            $physical_filename = $upload_dir.$uniqueid.".aes";
                                        }
                                        
                                        
                                        
                                        $attachmentfilename= $providerid."_".$_SESSION['sessionid']."_".$UploadNo.".".$filenameext;

                                        //$uploadfilename= $_SESSION['sessionid']."_".$UploadNo.".".$filenameext;
                                        $attachmentfilename= $providerid."_".$_SESSION['sessionid']."_".$UploadNo.".".$filenameext;
                                        
                                        if(intval($uploadprovider)>0)
                                        {
                                            $UploadNo +=1;
                                            //$uploadfilename2= $_SESSION['sessionid']."_".$UploadNo.".".$filenameext;
                                            $attachmentfilename2= $providerid."_".$_SESSION['sessionid']."_".$UploadNo.".".$filenameext;
                                            
                                        }
                                        
                                        
                                        echo("<br>File uploaded successfully. - ".$filename." <br>"); 
                                        
                                        $alias = uniqid("T4AZ", true);
                                        $encrypted_origfilename = EncryptTextCustomEncode($origfilename,"PLAINTEXT","$attachmentfilename");
                                        $encrypted_subject = EncryptTextCustomEncode($subject,"PLAINTEXT","$attachmentfilename");
                                        if($encrypted_subject == "Upload"){
                                            $encrypted_subject = $encrypted_origfilename;
                                        }
                                        $encrypted_subject .= " sent to $sendemail";

                                        $result = pdo_query("1", 
                                                "
                                                    insert into filelib
                                                    ( providerid, filename, origfilename, folder, filesize, filetype, title, createdate, alias, fileencoding, encoding, sendtoid, status )
                                                    values
                                                    ( $providerid, '$attachmentfilename','$encrypted_origfilename', '$filefolder',$fsize, '$filenameext','$encrypted_subject', now(), '$alias','$fileencoding','PLAINTEXT', $uploadprovider,'Y' ) 
                                                 "
                                         );
                                        
                                        //*********AWS ***********//
                                        //*********AWS ***********//
                                        //*********AWS ***********//    
                                        //*********AWS ***********//
                                        putAWSObject($attachmentfilename, $physical_filename);
                                        //unlink("$tempfile");
                                        if(intval($uploadprovider)>0)
                                        {
                                            $filefolder = "";
                                            if( isset($_SESSION['filefolder'])){
                                                $filefolder = $_SESSION['filefolder'];
                                            }
                                            
                                            $alias2 = uniqid("T4AZ", true);
                                            $encrypted_origfilename = EncryptTextCustomEncode($origfilename,"PLAINTEXT","$attachmentfilename2");
                                            $encrypted_subject = EncryptTextCustomEncode("$origfilename from $_SESSION[providername]","PLAINTEXT","$attachmentfilename2");
                                            
                                            $result = pdo_query("1", 
                                                    "
                                                        insert into filelib
                                                        ( providerid, filename, origfilename, folder, filesize, filetype, title, createdate, alias, encoding, sendtoid, status )
                                                        values
                                                        ( $uploadprovider, '$attachmentfilename2','$encrypted_origfilename', '$filefolder',$fsize, '$filenameext','$encrypted_subject', now(), '$alias2','PLAINTEXT', $uploadprovider, 'Y' ) 
                                                     "
                                             );
                                            
                                            copyAWSObject($attachmentfilename2, $attachmentfilename);
                                            if( intval($chatid) > 0)
                                            {
                                                $passkey = DecryptE2EPasskey($passkey64,$providerid);
                                                echo "<br>Chat ID Ref:$chatid";
                                                $encode = EncryptChat (
                                                        "   <br>
                                                            <div class='divbuttonchatupload doclib1' 
                                                            data-filename='$attachmentfilename'
                                                            data-altfilename='$attachmentfilename2'
                                                            data-sort='' data-folder='' data-caller='' data-target=''
                                                            data-page='1'>My Files</div> <br><br>$origfilename was added to your MY FILES. <br>", 
                                                        "$chatid","$passkey" );

                                                $result = pdo_query("1",
                                                    "
                                                        insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status)
                                                        values
                                                        ( $chatid, $providerid, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y' );
                                                    ");
                                                pdo_query("1",
                                                    "
                                                    update chatmembers set lastmessage=now(), lastread=now() where providerid= $providerid and chatid=$chatid and status='Y'
                                                    ");
                                                pdo_query("1",
                                                    "
                                                    update chatmaster set lastmessage=now() where  chatid=$chatid and status='Y'
                                                    ");
                                            }
                                            
                                        }
                                        
                                        //*********AWS ***********//
                                        //*********AWS ***********//
                                        //*********AWS ***********//
                                        //*********AWS ***********//
                                        
                                        
                                    }
                                    
                            }
                            else
                            { 
                                echo($origfilename." was too big, not uploaded<br>"); 
                                $status = false;
                            } 
                        }
                        else
                        { 
                           echo($origfilename." had an invalid file extension, not uploaded<br>"); 
                            $status = false;
                        } 
                }
                else
                { 
                    echo($origfilename." was not successfully uploaded<br />"); 
                    $status = false;
                } 
            } 
        } 
        return $status;
}

?>