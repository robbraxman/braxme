<?php
require_once("config.php");

/**
 * The Image Manupulation class
 * Currently can only rotate and flip an image
 * More will be added soon
 * @author Swashata <swashata at intechgrity.com>
 * @license Free
 * 
 */
class ImageManipulation {
 
    /**
     * The image container variable
     * Should be false if no image present
     * Otherwise should hold the GD Image Object
     * @var &gd.return.identifier
     */
    public $img;
 
    /**
     * The type of the image loaded
     * Should be false or any of the jpg|jpeg|png|gif
     * @var string
     */
    public $type;
 
    /**
     * The constructor function
     *
     * Set the default false value to both the variable
     */
    public function __construct() {
        $this->img = false;
        $this->type = false;
    }
 
    /**
     * The loader function
     * Loads the image from a file to the $this->img variable
     *
     * Also checks if the file exists and of valid image type by checking the extension
     * @param string $filename The path of the file to be loaded
     * @return boolean true on success, false otherwise
     */
    public function blackwhite () {
        imagefilter($this->img, IMG_FILTER_GRAYSCALE);
        imagefilter($this->img, IMG_FILTER_CONTRAST, -100);    
        return true;
    }
    
    
    public function load($filename) {
        //first check if the file exists
        if(!file_exists($filename))
            return false;
 
        //extract the extension & thereby check if valid image
        $ext = '';
        //we check the extension, it can be 4 characters (for jpeg) and must be atleast 3 characters
        if(preg_match('/.*\.([A-Za-z]?[A-Za-z]{3})$/', $filename, $ext)) {
            //var_dump($ext);
            $ext = $ext[1];
            //var_dump($ext);
        } else {
            //invalid image
            return false;
        }
 
        $this->type = $ext;
        switch($ext) {
            case 'jpg' :
            case 'jpeg' :
                $this->img = imagecreatefromjpeg($filename);
                break;
            case 'png' :
                $this->img = imagecreatefrompng($filename);
                break;
            case 'gif' :
                $this->img = imagecreatefromgif($filename);
                break;
            default :
                return false; //failure on invalid extension
        }
        imagealphablending($this->img, true);
        imagesavealpha($this->img, true);
        return true; //if we are at this point, then everything went fine
    }
 
    /**
     * Saves the image in a file
     *
     * The image from img variable is saved
     *
     * @param string $filename The path where the file is to be saved
     * @param string $type The type of the saved file. Use inherit for original, or use jpg|jpeg|png|gif
     * @param int $jpeg_quality The quality of the jpeg image (used only if being saved as jpeg), 0 - 100 (max quality)
     * @param int $png_compression The compression of the PNG image (used only if being saved as png), 0 (max quality, min compression) - 9
     * @return boolean true on success, false otherwise
     */
    public function save_image($filename, $type = 'inherit', $jpeg_quality = 100, $png_compression = 0) {
        $ext = 'inherit' == $type ? $this->type : $type;
        switch($ext) {
            case 'jpg' :
            case 'jpeg' :
                imagejpeg($this->img, $filename, $jpeg_quality);
                break;
            case 'png' :
                imagepng($this->img, $filename, $png_compression = 0);
                break;
            case 'gif' :
                imagegif($this->img, $filename);
                break;
            default :
                return false;
        }
        return true;
    }
 
    /**
     * Output the image directly into the browser.
     * Also stops execution of other scripts by calling die() function
     *
     * @uses die
     * @param string $type The type of the saved file. Use inherit for original, or use jpg|jpeg|png|gif
     * @param int $jpeg_quality The quality of the jpeg image (used only if being saved as jpeg), 0 - 100 (max quality)
     * @param int $png_compression The compression of the PNG image (used only if being saved as png), 0 (max quality, min compression) - 9
     * @return boolean false on failure
     */
    public function output($type = 'inherit', $jpeg_quality = 100, $png_compression = 0) {
        if($this->img == false)
            return false;
 
        header('Content-type: image/' . $this->type);
        $this->save_image(null, $type, $jpeg_quality, $png_compression);
        //die to stop execution
        die();
    }
 
    /**
     * Flip the image horizontally, vertically or both
     *
     * Manipulates the image object saved in the img variable
     *
     * @param string $type Flipping type, can be hori|horizontal|vert|vertical|both
     * @return boolean true on success, false on failure
     */
    public function flip_image($type) {
        //little bit of error check
        if(false === $this->img) {
            return false;
        }
        //first get the height and width
        $width = imagesx($this->img);
        $height = imagesy($this->img);
 
        //create the empty destination image
        $dest = imagecreatetruecolor($width, $height);
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
 
        //now work with the type and do the necessary flipping
        switch($type) {
            case 'vert' : //vertical flip
            case 'vertical' :
                for($i = 0; $i < $height; $i++) {
                    /**
                     * What we do here is pixel wise row flipping
                     * The first row of pixels of the source image (ie, when $i = 0)
                     * goes to the last row of pixels of the destination image
                     *
                     * So, mathematically, for the row $i of the source image
                     * the corresponding row of the destination should be
                     * $height - $i - 1
                     * -1, because y and x both co-ordinates are calculated from zero
                     */
                    imagecopy($dest, $this->img, 0, ($height - $i - 1), 0, $i, $width, 1);
                }
                break;
 
            case 'hori' : //horizontal flip
            case 'horizontal' :
                for($i = 0; $i < $width; $i++) {
                    /**
                     * Here we apply the same logic for other direction
                     * The first column of pixels of the source image
                     * goes to the last column of pixels of the destination image
                     *
                     * So, for the $i -th column of the source
                     * the column of the destination would be
                     * $width - $i - 1
                     */
                    imagecopy($dest, $this->img, ($width - $i - 1), 0, $i, 0, 1, $height);
                }
                break;
 
            case 'both' :
                //we simply return using recursive call
                if($this->flip_image('horizontal') && $this->flip_image('vertical'))
                    return true;
                else
                    return false;
                break;
            default :
                return false;
        }
 
        //now make the changes
        imagedestroy($this->img);
        $this->img = $dest;
        return true;
    }
 
    /**
     * Rotate the image to the given angle, filled with given color and alpha
     *
     * Rotates in clockwise direction and takes hexadecimal color code as input
     *
     * @param int $angle The rotational angle
     * @param string $bgd_color The background color code in hex. Optional, default is ffffff (white)
     * @param int $alpha The alpha value, 0 for opaque, 127 for transparent, anything between for translucent
     * @return void
     */
    public function rotate_image($angle, $bgd_color = 'ffffff', $alpha = 0) {
        if($angle == 0)
            return;
        $clockwise = true;
        if( $angle < 0)
            $clockwise = false;
        $angle = abs($angle);
        //make the value for clockwise rotation
        if( $clockwise )
            $r_angle = 360 - ($angle % 360);
        else
            $r_angle = $angle;
 
        extract($this->hex_to_rgb($bgd_color));
        $color = imagecolorallocatealpha($this->img, $red, $green, $blue, $alpha);
 
        $dest = imagerotate($this->img, $r_angle, $color);
 
        if(false !== $dest) {
            imagealphablending($dest, true);
            imagesavealpha($dest, true);
            imagedestroy($this->img);
            $this->img = $dest;
        }
 
    }
 
    /**
     * Converts hexadecimal to RGB color array
     * @link http://www.anyexample.com/programming/php/php_convert_rgb_from_to_html_hex_color.xml
     * @uses hexdec http://php.net/manual/en/function.hexdec.php
     * @access private
     * @param string $color 6 or 3 character long hexadecimal code
     * @return array with red, green, blue keys and corresponding values
     */
    private function hex_to_rgb($color) {
        if($color[0] == '#')
            $color = substr($color, 1);
 
        if(strlen($color) == 6) {
            list($r, $g, $b) = array(
                $color[0].$color[1],
                $color[2].$color[3],
                $color[4].$color[5]
            );
        } elseif (strlen($color) == 3) {
            list($r, $g, $b) = array(
                $color[0].$color[0],
                $color[1].$color[1],
                $color[2].$color[2]
            );
        } else {
            return array('red' => 255, 'green' => 255, 'blue' => 255);
        }
 
        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
        return array('red' => $r, 'green' => $g, 'blue' => $b);
    }
}
    
   


?>