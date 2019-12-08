    <?php
require_once("config.php");

function ProcessUpload( $providerid, $encoding )
{
        $max_file_size = 50480000; 
        $num_of_uploads=1;
        $file_types_array=array("doc","docx","png","txt","pdf","jpg","jpeg","gif","tiff","tif","mp3","htm","xml","zip","xls","xlsx","csv","pptx","ppt","apk","htm","html","css");
        $upload_dir="upload-zone/";    
        $upload_hdr="upload";    
        
        $uniqid = uniqid();

        //
        if( $encoding == "")
            $encoding = "BASE64";
        //$encoding = "3DES";
        //$encoding = "";
        //

        /*
        $upload_provdir="upload\\$providerid";    
        if( !file_exists( $upload_provdir ))
        {
            mkdir( $upload_provdir ); 
            
        }
         * 
         */
        
        
        $status = true;
        //if( !$_FILES)
        //{
        //    echo "No Upload Files Specified<br>";
        //    return true;
        //}
        $_SESSION['attachmentpath']=addslashes($upload_dir);
        $_SESSION['attachmentfilename']="";
        
        $UploadNo = 0;
    
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
                                        $_SESSION['attachmentfilename']= "$_SESSION[sessionid]_".$UploadNo.".$filenameext";
                                    
                                        $ftarget = fopen ( $upload_dir.$_SESSION['attachmentfilename'], "w" );

                                        if( $encoding == 'BINARY' )
                                        {
                                            
                                        }

                                        if( $encoding == 'BASE64' )
                                        {
                                            stream_filter_append($ftarget, 'convert.base64-encode');        
                                        }
                                        else
                                        if( $encoding == '3DES')
                                        {
                                            $passphrase = 'HYUuerueuy67hasdjfdesfa';

                                            /* Turn a human readable passphrase
                                             * into a reproducable iv/key pair
                                             */
                                            $iv = substr(md5('iv'.$passphrase, true), 0, 8);
                                            $key = substr(md5('pass1'.$passphrase, true) . 
                                                md5('pass2'.$passphrase, true), 0, 24);
                                            $opts = array('iv'=>$iv, 'key'=>$key);

                                            stream_filter_append($ftarget, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts);                                            
                                            
                                        }                                        

                                        while( $buffer = fread($fd, 0xFFFF))
                                        {
                                            fwrite($ftarget, $buffer);
                                        }

                                        fclose( $fd);
                                        fclose( $ftarget);

                                        if( $encoding !='BINARY')
                                        {
                                            echo("<br>File uploaded/encrypted successfully. - <a href='". $upload_dir.$upload_hdr.$filename."' target='_blank'>".$filename."</a><br>"); 
                                        }
                                        else
                                            echo("<br>File uploaded successfully. - <a href='". $upload_dir.$upload_hdr.$filename."' target='_blank'>".$filename."</a><br>"); 

                                        $result = do_mysqli_query("1", 
                                                "insert into attachments ( sessionid, item, attachfilename, origfilename, providerid, encoding, filesize, filetype ) ". 
                                                " values ( '$_SESSION[sessionid]', $UploadNo, '$_SESSION[attachmentfilename]', '$origfilename', $providerid, '$encoding', $fsize, '$filenameext' )"
                                         );
                                        /*
                                        echo
                                                "insert into attachments ( sessionid, item, attachfilename, origfilename, providerid, encoding, filesize, filetype ) ". 
                                                " values ( '$_SESSION[sessionid]', $UploadNo, '$_SESSION[attachmentfilename]', '$origfilename', $providerid, '$encoding', $fsize, '$filenameext' )"
                                                  ;
                                         * 
                                         */
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