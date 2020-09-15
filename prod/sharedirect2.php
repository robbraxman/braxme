<?php
session_start();
include("config.php");
require('aws.php');

$alias = '';
$share = '';

if(isset($_GET['a'])){
    $alias = mysql_safe_string( $_GET['a'] );
}
if(isset($_GET['p'])){
    $share = mysql_safe_string( $_GET['p'] );
}
if($alias == '' && $share == ''){
    exit(); 
}

if( $alias == ''){

    $result = do_mysqli_query("1","
            select filename, folder, title, comment, views, likes, filetype, filesize, public from photolib where filename='$share'
            ");
} else {
    
    $result = do_mysqli_query("1","
            select filename, folder, title, comment, views, likes, filetype, filesize, public from photolib where alias='$alias'
            ");
}
if( !$row = do_mysqli_fetch("1",$result)){


    $filename = "$rootserver/img/expired.jpg";
    header("Location: $filename");
    exit();
}

$filetype = $row['filetype'];
$filesize = $row['filesize'];
$public = $row['public'];

/*
 * Sharedirect will take directly from AWS S3
 * No intermediate filtering for photos only
 * Doc.php is for Files and can have stream cipher
 * 
 */

/*
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: filename="'.$appname.".".$row['filetype'].'"');
    if(!getAWSObjectStreamEcho( $row['filename'] )){
        exit();
    }
*/
    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: filename="'.$appname.".".$row['filetype'].'"');
    if(!getAWSObjectStreamEcho( $row['filename'] )){
        exit();
    }
    

    /*
    $awsurl = getAWSObjectUrl( "$row[filename]" );
    header("Location: $awsurl");
    exit();
    */
    
    
    
    exit();    
    
    
    
    

header("Content-Type: application/octet-stream");
header('Content-Disposition: inline, filename="'.$appname.".".$row['filetype'].'"');
echo getAWSObject("$row[filename]");
exit();



?>