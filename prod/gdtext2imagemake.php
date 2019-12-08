<?php
session_start();
require_once("config.php");
require("aws.php");
//*********************************************
//*********************************************

$text = $_GET['t'];
$comment = htmlentities(@mysql_safe_string( $_GET['comment'] ), ENT_QUOTES);
$save = @mysql_safe_string( $_GET['save'] );
$fontsize = @mysql_safe_string( $_GET['f'] );
if($fontsize == "")
    $fontsize = 20;
$fontfamily = @mysql_safe_string( $_GET['family'] );

$wrap = mysql_safe_string( $_GET['w'] );
if( $wrap == "")
    $wrap = 20;

$bcolor = mysql_safe_string( $_GET['b'] );
if( $bcolor == "")
   // $bcolor = "F5F5F5"; //whitesmoke
    $bcolor = "FFFFFF"; //whites
$backgroundrgb = hex2RGB( $bcolor );

$fcolor = mysql_safe_string( $_GET['c'] );
if( $fcolor == "")
    $fcolor = "000000"; //black
$frgb = hex2RGB( $fcolor );


//*********************************************
//*********************************************

//For GD Library 2.0 and higher - This works with EC2 Turnkey
putenv('GDFONTPATH=' . realpath('.'));
if($fontfamily == '' ){
    $font = 'SFScribbledSans-Bold';
    $fontWidthRatio = 8.5/20; //Width / Height - This has to be estimated for every font
} else
if($fontfamily == 'light'){
    
    $font = 'Raleway-Light';
    $fontWidthRatio = 10/20; //Width / Height - This has to be estimated for every font
} else 
if($fontfamily == 'bold'){    
    $font = 'Raleway-Bold';
    $fontWidthRatio = 14/20; //Width / Height - This has to be estimated for every font
}
//For GD Library 2.1
//$font = 'SFScribbledSans-Bold.ttf';


//$font = 'arial.ttf';
//$font = 'Final Gambit.otf';

//*********************************************
//*********************************************
//$linesWrap = explode("<br />", wordwrap($text, 30, "<br />"));
//Compute Wrap
$linesWrap = explode("<br />", $text);
$wrap = 0;
foreach($linesWrap as $line)
{
    $w = strlen($line);
    if( $w > $wrap)
        $wrap = $w;
}

//Compute Pixel Size
$pixelsize = $wrap*50;

//*********************************************
//*********************************************

//Average out the Fontsize so the 
//Final pic is 1000pixels wide+margins
$fontsize = ($pixelsize / ($wrap));

//$text = nl2br($text);
$lines = explode("<br />", wordwrap($text, $wrap, "<br />"));


$x = $fontsize*($fontWidthRatio)*$wrap;
$y = count($lines)*($fontsize+($fontsize/4));

//Compute Margins at 10%/20%
$xmargin = $x * (.10);
$ymargin = $y * (.20);

$x_outer = $x+($xmargin*2);
$y_outer = $y+($ymargin*2);




//if closer to square, make closer to 16/9
//FB doesn't like 4:3 prefers Square or 16/9
if(
        ($x_outer/$y_outer) < (16/9) //Closer to square
  )
{
    $orig_x_outer = $x_outer;
    
    $x_outer = $y_outer * (16/9);
    if( $x_outer > $orig_x_outer )
    {
        $x_extramargin = ($x_outer - $orig_x_outer)/2 ;
        $xmargin += $x_extramargin;
        $x_outer = $x+($xmargin*2);
    }
    
}
else
if(
        ($x_outer/$y_outer) > (16/9)
  )
{
    $orig_y_outer = $y_outer;
    
    $y_outer = $x_outer * (9/16);
    if( $y_outer > $orig_y_outer )
    {
        $y_extramargin = ($y_outer - $orig_y_outer)/2 ;
        $ymargin += $y_extramargin;
        $y_outer = $y+($ymargin*2);
    }
    
}




// Create some colors
//$im = imagecreatefromjpeg("");
$im = imagecreatetruecolor($x_outer, $y_outer);
$background_outer = imagecolorallocate($im, 0xE5, 0xE5, 0xE5);
//$background_outer = imagecolorallocate($im, $backgroundrgb['red'], $backgroundrgb['green'], $backgroundrgb['blue'] );
//$background_outer = imagecolortransparent($im, $background_outer);
$background = imagecolorallocate($im, $backgroundrgb['red'], $backgroundrgb['green'], $backgroundrgb['blue'] );
$foreground = imagecolorallocate($im, $frgb['red'], $frgb['green'], $frgb['blue'] );
$grey = imagecolorallocate($im, 128, 128, 128);


//Use this to check actual text bounds (for measuring new fonts)
//imagefilledrectangle($im, 0, 0, $x_outer, $y_outer, $background_outer );
imagefilledrectangle($im, 0, 0, $x_outer, $y_outer, $background );


//imagefilledrectangle($im, 0, 0, $x+($xmargin*2), $y+($ymargin*2)+$outeroffset, $background);
imagefilledrectangle($im, $xmargin, $ymargin, $x+$xmargin, $y+$ymargin, $background);

//imagerectangle($im, 5,5, $x_outer-5, $y_outer-5, 'gray');
//*********************************************
//*********************************************

header('Content-type: image/png');

//Coordinates of lower left corner of 1st character
$y1 = $ymargin+$fontsize;
$x1 = $xmargin;

//$y1 = $fontsize;
foreach( $lines as $line)
{
    imagettftext($im, $fontsize, 0, $x1, $y1, $foreground, $font, $line );
    $y1+=$fontsize+($fontsize/4);
}

/*
foreach( $lines as $line)
{
    imagettftext($im, $fontsize, 0, $x1+$xmargin, $y1+$ymargin-$fontsize, $foreground, $font, $line );
    $y1+=$fontsize+$fontsize/3;
}
 * 
 */


if( $save=='Y')
{
    $providerid = $_SESSION['pid'];
    $album = "TextPics";
    $upload_dir = "upload-zone/files/";
    $filenameext = "png";
    $subject = "My Private Message";
    $fsize = 1000;
    
    $uniqid = uniqid("Txt");
    $filename= $providerid."_".$uniqid.".".$filenameext;

    $alias = uniqid("T2P", true);
    

    
    
    $quality = 50;
    $quality = 9 - (int)((0.9*$quality)/10.0);
    imagepng($im, $upload_dir.$filename, $quality);
    imagepng($im);
    imagedestroy($im);
    if(file_exists($upload_dir.$filename)){
        //Save to File
        $result = do_mysqli_query("1", 
                "
                    insert into photolib
                    ( providerid, album, filename, folder, filesize, filetype, title, createdate, alias, public, owner, comment )
                    values
                    ( $providerid, '$album', '$filename', '$upload_dir',$fsize, '$filenameext','$subject', now(),'$alias','', $providerid, '$comment' ) 
                 "
         );
        putAWSObject($filename, $upload_dir.$filename);
        unlink("$upload_dir.$filename");
        
    }
    //unlink(realpath("$upload_dir.$filename"));
}
else
{
// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);
}
//*********************************************
//*********************************************

function hex2RGB( $hexStr ) 
{
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) 
    {
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    }
   return $rgbArray; 
} 

?>