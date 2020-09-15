<?php
session_start();
include("config.php");
include("crypt.inc.php");
require 'aws.php';

$alias = @tvalidator("PURIFY", $_GET['p'] );
$inline = @tvalidator("PURIFY", $_GET['i'] );


    $result = do_mysqli_query("1","
        select filelib.filename, filelib.folder, filelib.origfilename, 
        filelib.filetype, filelib.filesize, filelib.providerid, 
        filelib.encoding, filelib.fileencoding, provider.blockdownload, provider.active
        from filelib 
        left join provider on filelib.providerid = provider.providerid
        where filelib.alias='$alias' and filelib.status='Y'
        ");
    if( !$row = do_mysqli_fetch("1",$result)){
    
        echo "File Not Found";
        exit();
    }
    if($row['blockdownload']=='Y'){
        echo "Downloading has been disabled for this file";
        exit();
        
    }
    if($row['active']=='N'){
        echo "Account is closed. File not found.";
        exit();
        
    }
    
    
    do_mysqli_query("1","
        update filelib set views=views+1 where filename='$row[filename]' and providerid=$row[providerid]
        ");
    
    do_mysqli_query("1","
        insert into fileviews (filename, providerid, viewdate, filesize, views, status )
        values ('$row[filename]', $row[providerid], now(), $row[filesize], 1, 'Y' )
        ");
    

    $filename = "$rootserver/$installfolder/$row[folder]$row[filename]";
    $filetype = $row['filetype'];
    $filesize = $row['filesize'];
    $encoding = $row['encoding'];
    $fileencoding = $row['fileencoding'];

    $origfilename = DecryptText($row['origfilename'],$row['encoding'],$row['filename']);
    $pos1 = strstr(strtolower($origfilename), strtolower("$row[filetype]"));
    if($pos1 === false){

        $origfilename = urlencode($origfilename);
        //$origfilename = str_replace(" ","",$origfilename);
        //$origfilename = str_replace("&","",$origfilename);
        if(substr($origfilename,-1)!=='.'){

            $origfilename .= ".";
        }

        $origfilename .= "$row[filetype]";
    } else {
        
        $origfilename = str_replace(" ","-",$origfilename);
        $origfilename = str_replace("(","",$origfilename);
        $origfilename = str_replace(")","",$origfilename);
        $origfilename = str_replace("&","",$origfilename);
        $origfilename = urlencode($origfilename);

    }

    if($fileencoding!='PLAINTEXT' ) {

        if($filetype=='pdf'){
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=$origfilename");

        } else {
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: inline; filename=$origfilename");

        }

        //header("Content-Type: application/octet-stream");
        //header("Content-Disposition: inline; filename=$origfilename");

        //Echo Decrypted Stream
        getAWSObjectStreamEncryptedEcho( $row['filename'], $fileencoding, 0xFFFFF, $filesize );

        exit();
    }





    if($inline != '' &&
        (
            $filetype == 'pdf' ||
            $filetype == 'docx' ||
            $filetype == 'txt' ||
            $filetype == 'doc' ||
            $filetype == 'pages'
        )
    )
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$origfilename");


        echo getAWSObjectStreamEcho($row['filename']);
        exit();
    }
    if(
        $filesize < 1000000*10 //10MB
    )
    {
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: inline; filename=$origfilename");


        echo getAWSObjectStreamEcho($row['filename']);
        exit();
    }
    if(
        $filesize > 1000000*1500 //1.5GB
    )
    {
        $awsurl = getAWSObjectUrlShortTermLarge( $row['filename']);

        header('Location: '.$awsurl);
        exit();
    }
    

    $awsurl = getAWSObjectUrlShortTerm( $row['filename']);

    header('Location: '.$awsurl);
    exit();

/*
header("Content-Type: application/octet-stream");
header("Content-Disposition: filename='$row[origfilename]'");
header("Cache-control: private;no-cache"); //prevent proxy caching
if( $row[filesize] > 0 )
     header("Content-length: $row[filesize]");

exit();

if ($fd = fopen ($filename, "rb")) {

    fpassthru($fd);
    fclose( $fd);
    exit();
}
 * 
 */

?>