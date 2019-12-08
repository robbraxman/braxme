<?php
require_once("config.php");

function ProcessUpload( $providerid, $encoding, $subject, $upload_hdr, $uploadtype )
{
        //$max_file_size = 50480000; 
        $max_file_size = 50480000; 
        $num_of_uploads=1;
        $file_types_array=array("pdf","docx","doc","txt","ppt","pptx","xls","xlsx","mp3","mp4","avi","zip","tar","rtf","csv","xml","wav","wma","aif","m4a","jpg","png","tif","tiff","gif","bmp","htm","html","apk","m4p","mov","pages",".qt",".au",".gz");
        
        
        //$upload_hdr="photolib";    
        $upload_dir=$upload_hdr."/";    

        //
        
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
                            //echo "Uploading Size: ".$_FILES['file']['size'][$key]."<br>";
                            if($_FILES["file"]["size"][$key]<$max_file_size)
                            { 
                                if( $fd = fopen( $_FILES["file"]["tmp_name"][$key], "r"))
                                {
                                    $fsize = filesize($_FILES["file"]["tmp_name"][$key]);
                                    if( $fsize > 0 )
                                    {
                                        $attachmentfilename= $providerid."_".$_SESSION[sessionid]."_".$UploadNo.".".$filenameext;
                                    
                                        $ftarget = fopen ( $upload_dir.$attachmentfilename, "w" );
                                        if( !$ftarget)
                                            echo "<br>Failed ".$upload_dir.$_SESSION[sessionid]."_".$UploadNo.".".$filenameext;

                                        if( $encoding == 'BINARY' )
                                        {
                                            
                                        }

                                        if( $encoding == 'BASE64' )
                                        {
                                            //stream_filter_append($ftarget, 'convert.base64-encode');        
                                        }

                                        while( $buffer = fread($fd, 0xFFFF))
                                        {
                                            fwrite($ftarget, $buffer);
                                        }

                                        fclose( $fd);
                                        fclose( $ftarget);
                                        

                                        $uploadfilename= $_SESSION[sessionid]."_".$UploadNo.".".$filenameext;
                                        $attachmentfilename= $providerid."_".$_SESSION[sessionid]."_".$UploadNo.".".$filenameext;
                                        
                                        
                                        if( $encoding !='BINARY')
                                        {
                                            echo("<br>File uploaded successfully. - ".$filename."<br>"); 
                                        }
                                        else
                                            echo("<br>File uploaded successfully. - ".$filename."<br>"); 
                                        
                                        $alias = uniqid("T4AZ", true);

                                        $result = do_mysqli_query("1", 
                                                "
                                                    insert into filelib
                                                    ( providerid, filename, origfilename, folder, filesize, filetype, title, createdate, alias, status )
                                                    values
                                                    ( $providerid, '$attachmentfilename','$origfilename', '$upload_dir',$fsize, '$filenameext','$subject', now(), '$alias','Y' ) 
                                                 "
                                         );
                                        
                                        
                                    }
                                    
                                }

                                /*
                                if(move_uploaded_file($_FILES["file"]["tmp_name"][$key], $upload_dir.$_SESSION[sessionid]."_".$UploadNo."_".$filename))
                                { 
                                    $_SESSION[attachmentfilename]= "$_SESSION[sessionid]_".$UploadNo."_$filename";
                                    echo("<br>File uploaded successfully. - <a href='". $upload_dir.$upload_hdr.$filename."' target='_blank'>".$filename."</a><br>"); 
                                    
                                    $result = do_mysqli_query("1", 
                                            "insert into attachments ( sessionid, item, attachfilename, providerid, encoding ) ". 
                                            " values ( '$_SESSION[sessionid]', $UploadNo, '$_SESSION[attachmentfilename]', $providerid, '$encoding' )"
                                     );
                                    
                                }
                                else
                                { 
                                    echo($origfilename." was not successfully uploaded<br>"); 
                                    //$status = false;
                                } 
                                 * 
                                 */
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