<?php
session_start();
include("config-pdo.php");
require('aws.php');

$alias = '';
$share = '';

if(isset($_GET['a'])){
    $alias = tvalidator("PURIFY", $_GET['a'] );
}
if(isset($_GET['p'])){
    $share = tvalidator("PURIFY", $_GET['p'] );
}
if($alias == '' && $share == ''){
    exit(); 
}
if($share!=''){
    require('validsession.inc.php');
}


if( $alias == '')
{
    $result = pdo_query("1","
            select filename, folder, title, comment, views, likes, filetype, filesize, public from photolib where filename='$share'
            ");
}
else
{
    $result = pdo_query("1","
            select filename, folder, title, comment, views, likes, filetype, filesize, public from photolib where alias='$alias'
            ");
}
if( !$row = pdo_fetch($result))
{

    $filename = "$rootserver/img/expired.jpg";
    header("Location: $filename");
    exit();


    if ($fd = fopen ($filename, "rb")) {

        fpassthru($fd);
        fclose( $fd);
        exit();
    }
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
    if($public == 'Y'){
        /* go direct to AWS S3 for Speed - not private */
        $awsurl = getAWSObjectUrl( "$row[filename]" );
        header("Location: $awsurl");
        exit();

    }
    $awsurl = getAWSObjectUrl( "$row[filename]" );
    header("Location: $awsurl");
    exit();
    
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