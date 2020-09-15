<?php
session_start();
require_once("config.php");

$text = mysql_safe_string( $_GET[t] );
$x = explode("\\r\\n",$text);

$x[1]='OK';
var_dump($x);



exit();
// Set the content-type
header('Content-type: image/png');

$size = intval(strlen($text))*15;
// Create the image
$im = imagecreatetruecolor($size, 30);

// Create some colors
$background = imagecolorallocate($im, 0xF5, 0xF5, 0xF5);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, $size, 29, $background);

// Replace path by your own font path
$font = 'arial.ttf';

// Add some shadow to the text
//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

// Add the text
imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);


?>