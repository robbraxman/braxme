<?php
require ("$_SERVER[DOCUMENT_ROOT]"."/libs/amazon/aws-autoloader.php");
use Aws\Common\Aws;

// Create the AWS service builder, providing the path to the config file
//$aws = Aws::factory("localsettings/aws-config.php");
$aws = Aws::factory("$_SERVER[DOCUMENT_ROOT]"."/prod/localsettings/secure/aws-config.php");
$s3Client = $aws->get('s3');
$s3Client->setRegion('us-west-2');
$s3Client->registerStreamWrapper();

$snsClient = $aws->get('Sns');
$snsClient->setRegion('us-west-2');

function createSnsPlatformEndpoint( $iostoken, $androidtoken )
{
    
    global $snsClient;
    if(!$snsClient)
        return "Invalid snsClient<br>";
    
    try {
        if( $androidtoken!='')
        {
            $application = "arn:aws:sns:us-west-2:688683380103:app/GCM/BraxMe-Anroid-1.0";

//                'CustomUserData' => "$_SESSION[replyemail]",
            $result = $snsClient->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $application,
                'Token'    => $androidtoken,
                'Attributes' => array(
                     'Enabled' => 'true' 
                     )
            ));
        }
        if( $iostoken!='')
        {
            $application = "arn:aws:sns:us-west-2:688683380103:app/APNS/Brax.Me-IOS";
            $result = $snsClient->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $application,
                'Token'    => $iostoken,
                'Attributes' => array(
                     'Enabled' => 'true' 
                     )
            ));
            //    'CustomUserData' => "",
        }
        $endpoint = (string) $result['EndpointArn'];
    } catch(Exception $e){
        echo 'Notification Exception(1): ',  $e->getMessage(), "\n";
        $endpoint = "";
    }
    if($endpoint!==''){
        /*
        $userdata = "$_SESSION[replyemail]";
        try {
            setSnsEndpointAttributes( $endpoint, $userdata );
        } catch(Exception $e){
            echo 'Notification Exception(2): ',  $e->getMessage(), "\n";
        }
         * 
         */
    }
    
    return $endpoint;

}
function setSnsEndpointAttributes( $arn, $userdata )
{
    
    global $snsClient;
    if(!$snsClient)
        return "Invalid snsClient<br>";
    
    $snsClient->setEndpointAttributes(
            array(
            'EndpointArn' => "$arn",
            'Attributes'    => array('Enabled' => 'true' )
            )
        );
        //                    'CustomUserData' => "",

    return $endpoint;

}
function publishSnsNotification( $arn, $message, $json )
{
    global $snsClient;
    
    $result = false;
    
    if( $json )
    {
        $result = $snsClient->publish(array(
            'TargetArn' => $arn,
            'Message'    => $message,
            'MessageStructure' =>'json'
        ));
    }
    else
    {
        $result = $snsClient->publish(array(
            'TargetArn' => $arn,
            'Message'    => $message
        ));
        
    }
    return $result;
}

function getAWSObject( $filename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    try {
        $result = $s3Client->getObject(array(
            'Bucket' => $bucket,
            'Key'    => $filename
        )); 
        return (string) $result['Body'];
    } catch(Exception $e){
        return  "";
    }

}

function getAWSObjectStreamEcho( $filename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    try {
        if ($stream = fopen("s3://$bucket/$filename", 'r')) {
            // While the stream is still open
            
            
            while (!feof($stream)) {
                // Read 1024 bytes from the stream
                echo fread($stream, 102400);
            }
            // Be sure to close the stream resource when you're done with it
            fclose($stream);
            return true;
        }    
    
    } catch(Exception $e){
        return  false;
    }
    return false;
}
function getAWSObjectStreamEncryptedEcho( $filename, $encoding, $chunksize, $filesize )
{
    $bucket= "braxwest";
    global $s3Client;
    
    try {
        if ($stream = fopen("s3://$bucket/$filename", 'r')) {
            // While the stream is still open
            if($encoding!='PLAINTEXT'){
                SetStreamFilter( $stream, $encoding);
            }
            
            echo fread($stream, $filesize);
                
            fclose($stream);
            return true;
            while (!feof($stream)) {
                // Read 1024 bytes from the stream
                echo fread($stream, $chunksize);
            }
            // Be sure to close the stream resource when you're done with it
            fclose($stream);
            return true;
        }    
    
    } catch(Exception $e){
        return  false;
    }
    return false;
}
function getAWSObjectStreamEncryptedContent( $filename, $encoding, $chunksize, $filesize )
{
    $bucket= "braxwest";
    global $s3Client;
    
    try {
        if ($stream = fopen("s3://$bucket/$filename", 'r')) {
            // While the stream is still open
            SetStreamFilter( $stream, $encoding);
            
            $content = "";
            while (!feof($stream)) {
                // Read 1024 bytes from the stream
                $content .= fread($stream, $chunksize);
            }
            $content = substr($content,0, $filesize);
            // Be sure to close the stream resource when you're done with it
            fclose($stream);
            return ($content);
        }    
    
    } catch(Exception $e){
        return  false;
    }
    return false;
}


function saveAWSObject( $filename, $targetfilename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    $result = $s3Client->getObject(array(
        'Bucket' => $bucket,
        'Key'    => $filename,
        'SaveAs' => $targetfilename
    ));
    
    
    
    return (string) $result['Body'];

}

function saveAWSObjectStreamEncrypted( $filename, $encoding, $chunksize, $targetfilename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    /*****
     * 
     *  NOT TESTED - Affects B/W Conversion in DocLIB. Will have to address later
     * 
     */
    
    try {
        if ($stream = fopen("s3://$bucket/$filename", 'r')) {
            $fp2 = fopen( $targetfilename, 'w' );
            // While the stream is still open
            SetStreamFilter( $stream, $encoding);
            
            $content = "";
            while (!feof($stream)) {
                // Read 1024 bytes from the stream
                $content = fread($stream, $chunksize);
                fwrite($fp2, $content, $chunksize);
            }
            // Be sure to close the stream resource when you're done with it
            fclose($stream);
            fclose($fp2);
        }    
    
    } catch(Exception $e){
        return  false;
    }
    return false;
}




function getAWSObjectUrl( $filename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($bucket, $filename,gmdate(DATE_RFC2822, strtotime('1 January 2036')));  
    return $url;

}
function getAWSObjectUrlShortTerm( $filename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($bucket, $filename,"+120 minutes");  
    return $url;

}
function getAWSObjectUrlShortTermImage( $filename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($bucket, $filename,"+1 minutes");  
    return $url;

}
function deleteAWSObject( $filename )
{
    $bucket= "braxwest";
    global $s3Client;
    
    $result = $s3Client->deleteObject(array(
        // Bucket is required
        'Bucket' => $bucket,
        // Key is required
        'Key' => $filename
    ));        
    
    return $result;

}
function putAWSObject( $filename, $filepath )
{
    $bucket= "braxwest";
    global $s3Client;
    
    // Upload an object to Amazon S3
    $result = $s3Client->putObject(array(
        'Bucket' => $bucket,
        'Key'    => $filename,
        'SourceFile' => $filepath,
    ));
    unlink($filepath);
    
    $aws_url = getAWSObjectUrl( $filename );
    do_mysqli_query("1","update photolib set aws_url='$aws_url' where filename='$filename' ");
    
    return $result;

}

function copyAWSObject( $filename, $source )
{
    $bucket= "braxwest";
    global $s3Client;
    
    // Upload an object to Amazon S3
    $result = $s3Client->copyObject(array(
        'Bucket' => $bucket,
        'Key'    => $filename,
        'CopySource' => "$bucket/$source",
    ));
    
    return $result;

}

?>