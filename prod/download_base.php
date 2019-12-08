<?php
session_start();
require_once("config.php");

require_once("crypt.inc.php");

$responsetext = strtolower(mysql_safe_string( $_GET[responsetext]));
$responsehash = mysql_safe_string( $_GET[responsehash]);
$sessionid = strtolower( mysql_safe_string( $_GET[sessionid]));
$providerid = strtolower( mysql_safe_string( $_GET[providerid]));
$party = strtolower( mysql_safe_string( $_GET[party]));

$result = do_mysqli_query("1",

    "SELECT responsehash, encoding2, recipientemail from msgto " .
    "where sessionid='$sessionid' and providerid=$providerid and party=$party"
);

if( $row = do_mysqli_fetch("1",$result))
{
        $responsekey = $row[responsehash];

        $responsedecrypt = DecryptResponse( $row[responsehash],  "$row[encoding2]", "$providerid", "$row[recipientemail]" );

        //$responsedecrypt = base64_decode( $responsekey );
        //$responsedecrypt = $encryptor->decrypt( $responsedecrypt, "$providerid");
       
        /*
        echo "0:$responsekey<br>";
        echo "1:$responsetext<br>";
        echo "2:$responsedecrypt<br>";
        echo "3:$providerid<br>";
         * 
         */
        if( $responsehash == '')
        {
            if( $responsetext != $responsedecrypt )
            {
                exit();
            }
        }
        else
        {
            if( $responsekey != $responsehash )
            {
                exit();
            }
            
        }
}
else
    exit();

// place this code inside a php file and call it f.e. "download.php"
$path = $_SERVER['DOCUMENT_ROOT']."/$installfolder/upload/"; // change the path to fit your websites document structure
$fullPath = strtoupper( $path.$_GET['download_file']);
$path_parts = pathinfo($fullPath);
$ext = strtoupper($path_parts["extension"]);
$basename = $path_parts['basename'];

$result = do_mysqli_query("1",

    "SELECT providername from provider where providerid=$providerid   "
);
$row = do_mysqli_fetch("1",$result);
$providername = $row[providername];

$result = do_mysqli_query("1",

    "SELECT alias, recipientname, replyflag from msgto where sessionid='$sessionid' and party=$party  "
);
$row = do_mysqli_fetch("1",$result);
$alias = $row[alias];
$recipientname = $row[recipientname];
$replyflag = $row[replyflag];
if( $replyflag == 'Y')
    $targetname = $recipientname;
else
{
    if($alias != '')
        $targetname = $alias;
    else
        $targetname = $providername;
    
}



$result = do_mysqli_query("1",

    "SELECT encoding, filesize, filetype from attachments " .
    "where sessionid='$sessionid' and providerid=$providerid and attachfilename = '$basename'  "
);
$row = do_mysqli_fetch("1",$result);
$encoding = $row[encoding];
$filesize = $row[filesize];

//Continue only if authenticated user


if ($fd = fopen ($fullPath, "rb")) {
    //$fsize = filesize($fullPath);
    set_time_limit(0);
    
    if( $encoding == 'BASE64' )
    {
        stream_filter_append($fd, 'convert.base64-decode');        
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

        stream_filter_append($fd, 'mcrypt.tripledes', STREAM_FILTER_READ, $opts);                                            

    }
    
    if( 
            $row[filetype]=='jpg' || 
            $row[filetype]=='jpeg' || 
            $row[filetype]=='gif' || 
            $row[filetype]=='tif' || 
            $row[filetype]=='tiff' || 
            $row[filetype]=='png'
      )
    {
        header("Content-Type: image");
        //header('Content-Disposition: filename="'.$appname.'-'.$targetname.'.'.$ext.'"');
        header("Cache-control: no-store;no-cache;must-revalidate"); //prevent proxy caching
        header("Pragma: no-cache, no-store");
        header("Expires: 0");
    }
    else
    {
        header("Content-Type: application/octet-stream");
        header('Content-Disposition: attachment; filename="'.$appname.'-'.$targetname.'.'.$ext.'"');
        header("Cache-control: private;no-cache"); //prevent proxy caching
    }

   if( $filesize > 0 )
        header("Content-length: $filesize");
   ob_end_clean();
 
   fpassthru( $fd );
   /*
    while(!feof($fd)) {
        $buffer = fread($fd, 8192);
        echo $buffer;
    }
    * 
    */
    fclose ($fd);
   exit();
}


exit;
?>