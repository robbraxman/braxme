<?php
require_once("config-pdo.php");
require("aws.php");

function ProcessUpload( $providerid, $encoding, $subject, $album, $upload_hdr, $uploadtype )
{
        global $rootserver;
        global $installfolder;
        
        $max_file_size = 50480000; 
        $num_of_uploads=1;
        $file_types_array=array("png","jpg","jpeg","gif","tiff","tif");
        //$upload_hdr="photolib";    
        $upload_dir=$upload_hdr."/";    
        

        
        
        $status = true;
        
        $attachmentpath=addslashes($upload_dir);
        $attachmentfilename="";
        
        $UploadNo = 0;
    
        try {
        
            //Check Max File Size of Entire Batch
            $sizetotal = 0;
            if(count($_FILES)==0)
            {
                
                echo( "File not uploaded<br />"); 
                $status = false;
                return false;
            }
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
                                            $basefilename = hash('ripemd128',"$providerid".uniqid());

                                            $ftarget = fopen ( $upload_dir.$basefilename.".".$filenameext, "w" );
                                            if( !$ftarget){
                                                echo "<br>Failed ".$upload_dir.$basefilename.".".$filenameext;
                                            }

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


                                            $uploadfilename= $basefilename.".".$filenameext;
                                            $attachmentfilename= $basefilename.".".$filenameext;

                                            smart_resize_image($upload_dir.$uploadfilename , null, 1200 , 700 , true , $upload_dir.$attachmentfilename , true , false ,75 );
                                            $filesize = filesize( $upload_dir.$attachmentfilename );

                                            if( $encoding !='BINARY')
                                            {
                                                echo("<br>File uploaded successfully - ".$filename." to /$album/$uploadtype<br>"); 
                                                //echo("<br>Album Name: $album<br>"); 
                                            }
                                            else
                                            {
                                                echo("<br>File uploaded successfully. - ".$filename." to /$album/$uploadtype<br>"); 
                                            }

                                            $alias = uniqid("T4AZ", true);

                                            $result = pdo_query("1", 
                                                    "
                                                        insert into photolib
                                                        ( providerid, album, filename, folder, filesize, filetype, title, createdate, alias, owner )
                                                        values
                                                        ( ?, ?,?, ?,?, ?,?, now(), ?,? ) 
                                                     ",array(
                                                        $providerid, $album, $attachmentfilename, $upload_dir,$filesize, $filenameext, $subject, $alias, $providerid  
                                                     )
                                             );

                                            if( $uploadtype == "A" ){
                                            
                                                    $result = pdo_query("1", 
                                                            "
                                                                update provider set avatarurl = '$rootserver/$installfolder/sharedirect.php?p=$attachmentfilename' where
                                                                providerid = $providerid
                                                             ",null
                                                     );
                                                    $_SESSION['avatarurl']="$rootserver/$installfolder/sharedirect.php?p=$attachmentfilename";
                                            }
                                            putAWSObject("$attachmentfilename","$upload_dir$attachmentfilename");
                                            try {
                                                if(file_exists($upload_dir.$attachmentfilename)){
                                                    unlink("$upload_dir$attachmentfilename");
                                                }
                                            } catch (exception $e){
                                                
                                            }
                                        }

                                    }

                                    /*
                                    if(move_uploaded_file($_FILES["file"]["tmp_name"][$key], $upload_dir.$_SESSION[sessionid]."_".$UploadNo."_".$filename))
                                    { 
                                        $_SESSION[attachmentfilename]= "$_SESSION[sessionid]_".$UploadNo."_$filename";
                                        echo("<br>File uploaded successfully. - <a href='". $upload_dir.$upload_hdr.$filename."' target='_blank'>".$filename."</a><br>"); 

                                        $result = pdo_query("1", 
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
        }
        catch( Exception $e)
        {
            echo "Photo Upload Failed: ".$e->getMessage()."<br>";  
            var_dump($_FILES);
        }
        if( $uploadtype == "A"){
            echo("<br><a href='$rootserver/$installfolder/console.php'>Back</a><br>"); 
        }
        
        $upload_dir = $upload_hdr;
        try {
            if(file_exists($upload_dir)){
                array_map('unlink', glob($upload_dir."/*.*"));                    
                rmdir($upload_dir);
            }
        } catch ( Exception $e){
        }
        
        
        
        echo "<script>$('.mainview').scrollTop(0)</script>";
        return $status;
}


/**
 * easy image resize function
 * @param  $file - file name to resize
 * @param  $string - The image data, as a string
 * @param  $width - new image width
 * @param  $height - new image height
 * @param  $proportional - keep image proportional, default is no
 * @param  $output - name of the new file (include path if needed)
 * @param  $delete_original - if true the original image will be deleted
 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
 * @param  $quality - enter 1-100 (100 is best quality) default is 100
 * @return boolean|resource
 */
  function smart_resize_image($file,
                              $string             = null,
                              $width              = 0,
                              $height             = 0,
                              $proportional       = false,
                              $output             = 'file',
                              $delete_original    = true,
                              $use_linux_commands = false,
                              $quality = 50
         ) {
 
    if ( $height <= 0 && $width <= 0 ) return false;
    if ( $file === null && $string === null ) return false;
 
    # Setting defaults and meta
    $info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    list($width_old, $height_old) = $info;
    $cropHeight = $cropWidth = 0;
 
    # Calculating proportionality
    if ($proportional) {
      if      ($width  == 0)  $factor = $height/$height_old;
      elseif  ($height == 0)  $factor = $width/$width_old;
      else                    $factor = min( $width / $width_old, $height / $height_old );
 
      $final_width  = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
      $widthX = $width_old / $width;
      $heightX = $height_old / $height;
 
      $x = min($widthX, $heightX);
      $cropWidth = ($width_old - $width * $x) / 2;
      $cropHeight = ($height_old - $height * $x) / 2;
    }
 
    # Loading image to memory according to type
    switch ( $info[2] ) {
      case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
      case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
      case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
      default: return false;
    }
 
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);
      $palletsize = imagecolorstotal($image);
 
      if ($transparency >= 0 && $transparency < $palletsize) {
        $transparent_color  = imagecolorsforindex($image, $transparency);
        $transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
 
    # Taking care of original, if needed
    if ( $delete_original ) {
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }
 
    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
 
    # Writing image according to type to the output destination and image quality
    switch ( $info[2] ) {
      case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
      case IMAGETYPE_PNG:
        $quality = 9 - (int)((0.9*$quality)/10.0);
        imagepng($image_resized, $output, $quality);
        break;
      default: return false;
    }
 
    return true;
  }
/*  
//indicate which file to resize (can be any type jpg/png/gif/etc...)
$file = 'your_path_to_file/file.png';

//indicate the path and name for the new resized file
$resizedFile = 'your_path_to_file/resizedFile.png';

//call the function (when passing path to pic)
smart_resize_image($file , null, SET_YOUR_WIDTH , SET_YOUR_HIGHT , false , $resizedFile , false , false ,100 );
//call the function (when passing pic as string)
smart_resize_image(null , file_get_contents($file), SET_YOUR_WIDTH , SET_YOUR_HIGHT , false , $resizedFile , false , false ,100 );
*/
//done!  
?>