<?php
require_once("config.php");
require_once("crypt.inc.php");
require_once("aws.php");

function ProcessUpload( $providerid, $encoding, $uploadtype )
{
        global $rootserver;
        global $installfolder;
        
        $max_file_size = 5000480000; 
        $num_of_uploads=1;
        $file_types_array=array(
            "pdf","docx","doc","txt","ppt","pptx","xls","xlsx","mp3","mp4",
            "avi","zip","tar","rtf","csv","xml","wav","wma","aif","m4a","jpg",
            "png","tif","gif","bmp","apk","mov","m4p","3gp","ogv","webm","err"
          );
        $upload_dir="upload-zone/files";
        mkdir($upload_dir);
        $upload_dir="upload-zone/files/$providerid";  
        mkdir($upload_dir);
        $upload_dir="upload-zone/files/$providerid/";  
        
        
        $origfilename = $_FILES["file"]["tmp_name"]; 
        
        $fileid = uniqid("", false);
        

        $parts = pathinfo($_FILES['file']['name']);
        $filenameext = strtolower($parts['extension']);
        
        if($filenameext == ''){
            $filenameext = "bin";
        }
        
        $origfilename = "file-$fileid.".$filenameext;
        //$origfilename = $_FILES['file']['name'];
        
        //$new_filename = "mobile_$providerid_".$fileid.$filenameext;       
        $attachmentfilename= $providerid."_".$fileid.".$filenameext";
        move_uploaded_file($_FILES["file"]["tmp_name"], "/var/www/html/$installfolder/$upload_dir".$attachmentfilename);

        $physical_filename = "$upload_dir".$attachmentfilename;
        $filesize = filesize( "$physical_filename" );
        LogDebug($providerid, "2-file: $upload_dir$attachmentfilename $filesize");
        $alias = uniqid("T4AZ", true);
        
        if($filesize > 0){
            

            $fileencoding = "PLAINTEXT";
            
            //if($_SESSION['superadmin']=='Y'){
                //Encrypt Test

                $fileencoding = $_SESSION['responseencoding'];
                $fileStreamTypes = array("mp4","mp3","mov","m4a","wav","m4v");
                foreach( $fileStreamTypes as $fileStreamType ){

                    if(strtolower($filenameext)==$fileStreamType){
                        $fileencoding = 'PLAINTEXT';
                        break;
                    }
                }
                if($fileencoding != 'PLAINTEXT'){
                    StreamEncode($physical_filename, $physical_filename.".aes", $fileencoding );
                    $physical_filename = $physical_filename.".aes";
                }
            //}
            

            $result = do_mysqli_query("1", 
                    "
                        insert into filelib
                        ( providerid, filename, origfilename, folder, filesize, filetype, title, createdate, alias, encoding, fileencoding, status )
                        values
                        ( $providerid, '$attachmentfilename','$origfilename', '',$filesize, '$filenameext','$origfilename', now(), '$alias', 'PLAINTEXT','$fileencoding','Y' ) 
                     "
             );
            LogDebug($providerid, "3-file: ( $providerid, '$attachmentfilename','$origfilename', '',$filesize, '$filenameext','$origfilename', now(), '$alias' ) ");

            putAWSObject("$attachmentfilename","$physical_filename");
        } else {
            LogDebug($providerid, "4-filesize error: $upload_dir$attachmentfilename $filesize");
        }
        try {
            $upload_dir="upload-zone/files/$providerid";  
            array_map('unlink', glob($upload_dir."/*.*"));                    
            rmdir($upload_dir);
        } catch ( Exception $e){
        }

        if($filenameext!=='jpg'){
            return "";
        }
        return $alias;
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