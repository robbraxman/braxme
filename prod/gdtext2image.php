<?php
session_start();
require_once("config.php");

$text = base64_decode(mysql_safe_string( isset( $_REQUEST["t"] ) ? $_REQUEST["t"] : ""  ));
$c = mysql_safe_string( isset( $_REQUEST["c"] ) ? $_REQUEST["c"] : ""  );

// Set the content-type
header('Content-type: image/png');

$size = intval(strlen($text))*15+20;
// Create the image
$im = imagecreatetruecolor($size, 25);

// Create some colors
$background = imagecolorallocate($im, 0xF5, 0xF5, 0xF5);
if($c == '1')
    $background = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, $size, 29, $background);

// Replace path by your own font path
//$font = 'arial.ttf';
putenv('GDFONTPATH=' . realpath('.'));
$font = 'arial';

// Add some shadow to the text
//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

// Add the text
imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);


?>