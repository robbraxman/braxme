<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
require_once("config.php");
require ("SmsInterface.inc");
require ("crypt.inc");
require ("sqlcore.inc");

$sessionid = tvalidator("PURIFY","$_REQUEST[sessionid]");
$providerid = tvalidator("PURIFY","$_REQUEST[pid]");
$loginid = tvalidator("PURIFY","$_REQUEST[loginid]");
$password = tvalidator("PURIFY","$_REQUEST[password]");
        

$result = mysql_query( 
    "SELECT encoding, message, recipientname, recipientsms, recipientemail, patientname, patientmrno ".
    "from msgmain ".
    "where sessionid='$sessionid' and '$loginid' in  ".
    "(select loginid from staff where providerid = $providerid and passwordhash = password('$password') ) "
        
);

if( $result)
{
        $row = do_mysqli_fetch("1",$result);       
        if ($row) 
        {
                //Decoding Routines if Applicable
                if( $row[encoding]=='HASH')
                {
                    $UnencodedText = base64_decode( $row[message] );
                    $UnencodedText = $encryptor->decrypt( $UnencodedText, $key);
                }
                else
                if( $row[encoding]=='BASE64')
                {
                    $UnencodedText = "$row[message]";
                    $UnencodedText = base64_decode( $UnencodedText);
                    $UnencodedText = ltrim( $UnencodedText );
                    $UnencodedText = rtrim( $UnencodedText );
 
                }
                else
                   $UencodedText =  "$row[message]";
                
                $sms = substr( "$row[recipientsms]",2, 20 );
                
               $message = array( 
                   'message' => "$UnencodedText",
                   'recipientname' => "$row[recipientname]",
                   'recipientid' => "$row[recipientid]",
                   'recipientphone' => "$sms",
                   'recipientemail' => "$row[recipientemail]",
                   'patientname' => "$row[patientname]",
                   'patientmrno' => "$row[patientmrno]",
                   'status' => 'success'
                       );
               echo json_encode( $message );

        }
}
else
{
               echo json_encode( array( 'status'=>"Failed" ));
    
}
     
?>
