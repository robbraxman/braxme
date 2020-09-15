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
$s3bucket= "braxwest";


function createSnsPlatformEndpoint( $iostoken, $androidtoken, $app )
{
    
    global $snsClient;
    if(!$snsClient)
        return "Invalid snsClient<br>";
    
    try {
        if( $androidtoken!='')
        {
            if($app == 'Brax.Me'){
                $application = "arn:aws:sns:us-west-2:688683380103:app/GCM/BraxMe-Anroid-1.0";
            }
//                'CustomUserData' => "$_SESSION[replyemail]",
            $result = $snsClient->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $application,
                'Token'    => "$androidtoken",
                'Attributes' => array(
                     'Enabled' => 'true' 
                     )
            ));
        }
        if( $iostoken!='')
        {
            if($app == 'Brax.Me'){
                $application = "arn:aws:sns:us-west-2:688683380103:app/APNS/Brax.Me-IOS";
            }
            
            
            $result = $snsClient->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $application,
                'Token'    => "$iostoken",
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
        

        $error = $e->getMessage();
        if(strstr("$error","arn:aws:sns:")!==false ){
            $temp = strstr("$error","arn:aws:sns:");
            $temp2 = explode(" ", $temp);
            $arn = $temp2[0];
            echo "Found $arn\n";
        } else {
            echo "Not Found $arn\n";
            
        }
        
        
        if($arn!=''){
            deleteSnsPlatformEndpoint( $arn );
            //echo "\ndeleting $arn";
        }
        
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
function deleteSnsPlatformEndpoint( $arn )
{
    
    global $snsClient;
    
    if(!$snsClient)
        return "Invalid snsClient<br>";
    
    try {
            $result = $snsClient->deleteEndpoint(array(
                'EndpointArn' => "$arn"
            ));
    } catch(Exception $e){
        echo 'Notification Exception(1): ',  $e->getMessage(), "\n";
        $result = $e->getMessage();
    }
    
    return $result;

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

    return $arn;

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

function publishSMSNotification( $sender, $message, $phone )
{
    global $snsClient;
    
    
    $args = array(
        "Message" => "$message",
        "MessageStructure" => "SMS",
        "MessageAttributes" => [
            'AWS.SNS.SMS.SenderID' => [
                'DataType' => 'String', // REQUIRED
                'StringValue' => "$sender"
            ],
            'AWS.SNS.SMS.SMSType' => [
                'DataType' => 'String', // REQUIRED
                'StringValue' => 'Transactional' // or 'Promotional'
            ]
        ],
        "PhoneNumber" => "$phone"
    );
    
    
    $result = false;

    try {
        $result = $snsClient->publish($args);
        return $result;
    } catch(Exception $e){
        echo  "$e";
        exit();
    }
    
    
    exit();

    
    return $snsClient->publish($args);
}


function getAWSObject( $filename )
{
    global $s3bucket;
    global $s3Client;
    
    try {
        $result = $s3Client->getObject(array(
            'Bucket' => $s3bucket,
            'Key'    => $filename
        )); 
        return (string) $result['Body'];
    } catch(Exception $e){
        return  "";
    }

}

function getAWSObjectStreamEcho( $filename )
{
    global $s3bucket;
    global $s3Client;
    
    try {
        if ($stream = fopen("s3://$s3bucket/$filename", 'r')) {
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
    global $s3bucket;
    global $s3Client;
    
    try {
        if ($stream = fopen("s3://$s3bucket/$filename", 'r')) {
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
    global $s3bucket;
    global $s3Client;
    
    try {
        if ($stream = fopen("s3://$s3bucket/$filename", 'r')) {
            // While the stream is still open
            if($encoding!='PLAINTEXT'){
                SetStreamFilter( $stream, $encoding);
            }
            if($chunksize > $filesize && $filesize > 0){
                $chunksize = $filesize;
            }
            $content = "";
            while (!feof($stream)) {
                // Read 1024 bytes from the stream
                $content .= fread($stream, $chunksize);
            }
            //$content = substr($content,0, $filesize);
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
    global $s3bucket;
    global $s3Client;
    
    $result = $s3Client->getObject(array(
        'Bucket' => $s3bucket,
        'Key'    => $filename,
        'SaveAs' => $targetfilename
    ));
    
    
    
    return (string) $result['Body'];

}

function saveAWSObjectStreamEncrypted( $filename, $encoding, $chunksize, $targetfilename )
{
    global $s3bucket;
    global $s3Client;
    
    /*****
     * 
     *  NOT TESTED - Affects B/W Conversion in DocLIB. Will have to address later
     * 
     */
    
    try {
        if ($stream = fopen("s3://$s3bucket/$filename", 'r')) {
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


function saveAWSObjectStream( $filename, $chunksize, $targetfilename )
{
    global $s3bucket;
    global $s3Client;
    
    /*****
     * 
     *  NOT TESTED - Affects B/W Conversion in DocLIB. Will have to address later
     * 
     */
    
    try {
        if ($stream = fopen("s3://$s3bucket/$filename", 'r')) {
            $fp2 = fopen( $targetfilename, 'w' );
            // While the stream is still open
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
    global $s3bucket;
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($s3bucket, $filename,gmdate(DATE_RFC2822, strtotime('1 January 2036')));  
    return $url;

}
function getAWSObjectUrlShortTerm( $filename )
{
    global $s3bucket;
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($s3bucket, $filename,"+120 minutes");  
    return $url;

}
function getAWSObjectUrlShortTermLarge( $filename )
{
    global $s3bucket;
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($s3bucket, $filename,"+1440 minutes");  
    return $url;

}


function getAWSObjectUrlShortTermImage( $filename )
{
    global $s3bucket;
    global $s3Client;
    
    $url = $s3Client->getObjectUrl($s3bucket, $filename,"+1 minutes");  
    return $url;

}
function deleteAWSObject( $filename )
{
    global $s3bucket;
    global $s3Client;
    
    $result = $s3Client->deleteObject(array(
        // Bucket is required
        'Bucket' => $s3bucket,
        // Key is required
        'Key' => $filename
    ));        
    
    return $result;

}
function putAWSObject( $filename, $filepath )
{
    global $s3bucket;
    global $s3Client;
    
    // Upload an object to Amazon S3
    $result = $s3Client->putObject(array(
        'Bucket' => $s3bucket,
        'Key'    => $filename,
        'SourceFile' => $filepath,
    ));
    unlink($filepath);
    
    $aws_url = getAWSObjectUrl( $filename );
    pdo_query("1","update photolib set aws_url='$aws_url' where filename='$filename' ");
    
    return $result;

}

function copyAWSObject( $filename, $source )
{
    global $s3bucket;
    global $s3Client;
    
    // Upload an object to Amazon S3
    $result = $s3Client->copyObject(array(
        'Bucket' => $s3bucket,
        'Key'    => $filename,
        'CopySource' => "$s3bucket/$source",
    ));
    
    return $result;

}

?>