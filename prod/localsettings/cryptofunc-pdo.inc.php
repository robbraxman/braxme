<?php
require_once("localsettings.php");
require_once("mcryptlib.php");
require_once("openssllib.php");


//*********************************************************************************
//*********************************************************************************
//Current Forward Secrecy Encoding Scheme
//*********************************************************************************
//*********************************************************************************
    $result = pdo_query("1", 
       "select encoding from cryptkeys where keyid = 'NEWENCODING' and passphrase='' ",null);
    if( $row = pdo_fetch($result))
    {
        $currentencoding = $row['encoding'];
        $currentfileencoding = $row['encoding'];
    }
    $superadmin = @tvalidator("PURIFY",$_SESSION['superadmin']);
    
    //Override All Encryption
    //$currentencoding = "BASE64";
    //$currentfileencoding = "BASE64";
    
    $_SESSION['responseencoding'] = $currentencoding;
    $_SESSION['fileencoding'] = $currentfileencoding;
    
    $keyCache = array();
    $keyCacheCount = 0;
    $keyQueryCount = 0;
//*********************************************************************************
//*********************************************************************************
//*********************************************************************************
/*

   1. Original Encoding was MCrypt which is now Deprecated so Mcrypt is for conversion only
   2. Text encryption is now OpenSSL
   3. Stream Encryption is still Mcrypt - to be replaced by Sodium Stream Encryption Later

*/
//*********************************************************************************
//*********************************************************************************
//SITE KEY 1 FOR CURRENT ENCODING SCHEME
//*********************************************************************************
//*********************************************************************************

    $lastencoding = '';
    $site_key1 = "";
    $site_key1 = RetrieveSecretKey("$currentfileencoding");
    //GenerateNewEncoding(); - moved to Startup.Inc

    //Create Mcrypt Instance - Deprecated - For Conversion Only
    $encryptor = new Encryptor('');


//*********************************************************************************
//*********************************************************************************
//KEY FOR CURRENT ENCODING - NOT USED IN NEW ENCRYPTION
//*********************************************************************************
//*********************************************************************************



    function EncryptPost( $message, $salt1, $salt2 )
    {
            $encoding = $_SESSION['responseencoding'];

            if($encoding=="BASE64")
            {
                $SlashedMessage = nl2br( stripslashes($message));
                $SlashedMessage = base64_encode( $SlashedMessage );
            }
            else
            if($encoding=="PLAINTEXT")
            {
                return nl2br( stripslashes($message));
            }
            else
            if( substr($encoding,0,3) == "SPA")
            {
                return StdEncrypt( $message, $encoding, $salt1.$salt2 );
            }
            else
            if( substr($encoding,0,2) == "OX")
            {
                return StdEncryptV2( $message, $encoding, $salt1.$salt2 );
            }

            return $SlashedMessage;
    }


    function DecryptPost( $message, $encoding, $salt1, $salt2 )
    {

            //Decoding Routines if Applicable
            if( $encoding=='')
            {
                return "";
            }
            else
            if( $encoding=='BASE64')
            {
                $UnencodedText = base64_decode( $message );
            }
            else
            if( $encoding=='PLAINTEXT')
            {
                return $message;
            }
            else
            if( substr($encoding,0,3) == "SPA")
            {
                return StdDecrypt( $message, $encoding, $salt1 . $salt2 );
            }
            else
            if( substr($encoding,0,2) == "OX")
            {
                return StdDecryptV2( $message, $encoding, $salt1 . $salt2 );
            }

            return $UnencodedText;
    }

    function EncryptChat( $message, $salt1, $salt2 )
    {
            //If no Salt then must be error
            if($salt1 == ''){
                return "";
            }

            $encoding = $_SESSION['responseencoding'];

            if($encoding=="BASE64")
            {
                $SlashedMessage = nl2br( stripslashes($message));
                $SlashedMessage = base64_encode( $SlashedMessage );
            }
            else
            if($encoding=="PLAINTEXT")
            {
                return nl2br( stripslashes($message));
            }
            else
            if( $encoding == "SPA1.0")
            {
                return StdEncrypt( $message, "" );
            }
            else
            if( substr($encoding,0,3) == "SPA")
            {
                return StdEncrypt( $message, $encoding, $salt1.$salt2 );
            } else
            if( substr($encoding,0,2) == "OX")
            {
                return StdEncryptV2( $message, $encoding, $salt1.$salt2 );
            }

            return $SlashedMessage;
    }


    function DecryptChat( $message, $encoding, $salt1, $salt2 )
    {
            $UnencodedText = "";

            //Decoding Routines if Applicable
            if( $encoding=='')
            {
                return $message;
            }
            else
            if( $encoding=='BASE64')
            {
                $UnencodedText = base64_decode( $message );
            }
            else
            if( $encoding=='PLAINTEXT')
            {
                return $message;
            }
            else
            if( $encoding == "SPA1.0")
            {
                return StdDecrypt( $message, $encoding, "" );
            }
            else
            if( substr($encoding,0,3) == "SPA")
            {
                return StdDecrypt( $message, $encoding, $salt1 . $salt2 );
            }
            else
            if( substr($encoding,0,2) == "OX")
            {
                return StdDecryptV2( $message, $encoding, $salt1 . $salt2 );
            }

            return $UnencodedText;
    }

    
    function EncryptText( $message, $salt1 )
    {

            if( $message == '')
                return '';
            
            $encoding = $_SESSION['responseencoding'];

            if($encoding=="BASE64")
            {
                $SlashedMessage = nl2br( stripslashes($message));
                $SlashedMessage = base64_encode( $SlashedMessage );
            }
            else
            if($encoding=="PLAINTEXT")
            {
                return nl2br( stripslashes($message));
            }
            else
            if( substr($encoding,0,3) == "SPA")
            {
                return StdEncrypt( $message, $encoding, $salt1 );
            }
            else
            if( substr($encoding,0,2) == "OX")
            {
                return StdEncryptV2( $message, $encoding, $salt1 );
            }

            return $SlashedMessage;
    }


    function DecryptText( $message, $encoding, $salt1  )
    {

            if( $message == '')
                return '';

            //Decoding Routines if Applicable
            if( $encoding=='')
            {
                return $message;
            }
            else
            if( $encoding=='BASE64')
            {
                $UnencodedText = base64_decode( $message );
            }
            else
            if( $encoding=='PLAINTEXT')
            {
                return $message;
            }
            else
            if( substr($encoding,0,3) == "SPA")
            {
                return StdDecrypt( $message, $encoding, $salt1 );
            }
            else
            if( substr($encoding,0,2) == "OX")
            {
                return StdDecryptV2( $message, $encoding, $salt1 );
            }

            return $UnencodedText;
    }    

    

    
    
function StreamEncode($source, $target, $encoding){
    
        global $encryptor;

        try {
            unlink($target);
        } catch (exception $e){
            
        }

        $fp1 = fopen( $source, 'r');
        $fp2 = fopen( $target, 'w');
        
        if($encoding === 'BASE64'){
        
            stream_filter_append($fp2, 'convert.base64-encode', STREAM_FILTER_WRITE, $opts);
            
        } else
        if($encoding!== 'PLAINTEXT'){
    
            $passphrase = RetrieveSecretKey("$encoding");
            $opts = $encryptor->setStreamKey($passphrase);

            stream_filter_append($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        } 
        

        while (!feof($fp1)) {
            $contents = fread($fp1, 0xFFFFF);
            fwrite($fp2, $contents);
        }        

        fclose($fp1);        
        fclose($fp2);
        try {
            unlink($source);
        } catch (exception $e){
            
        }
        //copy($target, $target.".jpg");

        return true;

}
function TextStreamEncode($contents, $target, $encoding){
    
        global $encryptor;

        if(file_exists($target)){
            unlink($target);
        }
        $fp2 = fopen( $target, 'w');
        
        if($encoding==='BASE64'){
            stream_filter_append($fp2, 'convert.base64-encode', STREAM_FILTER_WRITE, $opts);
            
        } else 
        if($encoding!=='PLAINTEXT'){
            $passphrase = RetrieveSecretKey("$encoding");
            $opts = $encryptor->setStreamKey($passphrase);
            stream_filter_append($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        }



        fwrite($fp2, $contents);

        fclose($fp2);

        return true;

}

function StreamDecode($source, $ext, $encoding){


        $target = $source.".".$ext;
        unlink($target);

        $fp1 = fopen( $source, 'r');
        $fp2 = fopen( $target, 'w');

        //stream_filter_prepend($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        //stream_filter_prepend($fp1, 'mdecrypt.rijndael-128', STREAM_FILTER_READ, $opts);
        SetStreamFilter( $fp1, $encoding );

        while (!feof($fp1)) {
            $contents = fread($fp1, 0xFFFFF);
            fwrite($fp2, $contents);
        }        

        fclose($fp1);        
        fclose($fp2);        
        return "$target";

}
function StreamDecodeDownload($source, $ext, $encoding){


        $fp1 = fopen( $source, 'r');
        SetStreamFilter( $fp1, $encoding);

        while (!feof($fp1)) {
            $contents = fread($fp1, 0xFFFF);
            echo $contents;
        }        

        fclose($fp1);        
        return;

}
function SetStreamFilter( $fp, $encoding ){

        global $encryptor;
        
        if($encoding==='PLAINTEXT'){
            return;
        } else
        if($encoding!=='BASE64'){
    
            $passphrase = RetrieveSecretKey("$encoding");
            $opts = $encryptor->setStreamKey($passphrase);

            stream_filter_append($fp, 'mdecrypt.rijndael-128', STREAM_FILTER_READ, $opts);
            
        } else {
            
            stream_filter_append($fp, 'convert.base64-decode', STREAM_FILTER_READ, $opts);
            
        }
        return;

}
function SetSaveStreamFilter( $fp ){

        global $encryptor;
    
        
        $passphrase = RetrieveSecretKey("$encoding");
        $opts = $encryptor->setStreamKey($passphrase);

        stream_filter_append($fp, 'mcrypt.rijndael-128', STREAM_FILTER_READ, $opts);
        return;

}
//*********************************************************************************
//*********************************************************************************
//*********************************************************************************
/* 
 * 
 *      Key Retrieval and Generation
 * 
 * 
 */
//*********************************************************************************
//*********************************************************************************
//*********************************************************************************


    
    
    function RetrieveSecretKey( $encoding )
    {
        global $lastencoding;
        global $site_key1;
        global $keyCache;
        global $keyCacheCount;
        global $keyQueryCount;
        
        if($encoding == 'BASE64'){
            return "";
        }

        
        //This prevents constant requerying if the encoding is the same - performance
        if( $lastencoding==$encoding && $site_key1 !=''){
            $keyCacheCount++;
            return $site_key1;
        }
        if($encoding!='js' && isset($_SESSION[$encoding])){
            $site_key1 = $_SESSION[$encoding];
            return $site_key1;
        }
        $lastencoding = $encoding;
        
        
        $key = "";
        if( $encoding == "js"){
            $key = base64_encode("somekey");
        } else {
            $result = pdo_query("4", 
               "select SHA2(passphrase,256) as passphrase from cryptkeys where keyid = 'SITE' AND ENCODING=? ",
               array($encoding));
            if( $row = pdo_fetch( $result))
            {
                //Legacy - not hashed
                if( $encoding == 'SPA1.0') {
                    $key = base64_encode($row['passphrase']);
                } else {
                    $key = base64_encode($row['passphrase']);
                }

            } else {
                error_log("GetKey Error $encoding");
                echo "<br>Sorry - Encoding '$encoding' Not Found - Tech Support Issue<br>";
                return "error";
                exit();
            }
        }
        $site_key1 = $key;
        
        if($site_key1 === '' || $site_key1 === false ){
            $site_key1 = '';
            error_log("GetKey Error $encoding");
            echo "<br>Sorry - Maintenance is being performed. We'll be back shortly.<br>";
            return "error";
            exit();
        }

        
        $keyCache[$encoding]="$site_key1";
        $keyQueryCount++;
        //error_log("$encoding [$site_key1) $keyQueryCount");
        return $site_key1;
        
    }

    function GenerateRandomNumberMail()
    {
        //uses dev/urandom which is safest
        //generated based on SHA hash of entropy pool
        //not possible to derive original value
        //fear is in predicting random number from prior pattern
        $bytes = 10;
        $random = mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM);
        return base64_encode($random);
        
    }
    function PassE2EKey($chatid, $passkey64, $senderid, $recipientid)
    {
        if($passkey64==''){
            return;
        }
        
        $passkey =  DecryptE2EPasskey($passkey64, $senderid);
        $passkeyNew64 = EncryptE2EPasskey($passkey,$recipientid);        
            
        /* Security Note
         * 
         * There's a brief exposure here as passkeys (though encrypted)
         * are stored in the database until picked up and deleted. At this
         * point passkeys are reversible. 
         * However once passkeys are picked up, there is no longer a risk
         * 
         * 
         */
        
        pdo_query("1",
        "
            insert into keysend 
            ( chatid, providerid, senderid, expiration, passkey, encoding ) 
            values
            ( ?, ?, ?, now(), ?,'JS' );
        ", array($chatid, $recipientid, $senderid,$passkeyNew64)
        );

    }
    function PassE2EKeyHandleRequests($chatid, $passkey64, $senderid )
    {
        if($passkey64==''){
            return;
        }
        
        $passkey =  DecryptE2EPasskey($passkey64, $senderid);
        
        $result = pdo_query("1","
            select providerid from keysend where passkey='' and chatid=? and senderid = ?
                ",array($chatid, $senderid));
        while($row = pdo_fetch($result) ){
            $recipientid = $row['providerid'];
            $passkeyNew64 = EncryptE2EPasskey($passkey,$recipientid);        
            
            pdo_query("1",
            "
                delete from keysend where providerid = ? and passkey='' and chatid = ?
            ", array($recipientid, $chatid));
            
            
            pdo_query("1",
            "
                insert into keysend 
                ( chatid, providerid, senderid, expiration, passkey, encoding ) 
                values
                ( ?, ?, ?, now(), ?,'JS' );
            ", array( $chatid, $recipientid, $senderid, $passkeyNew64 )

            );
            
        }
        
            
        /* Security Note
         * 
         * There's a brief exposure here as passkeys (though encrypted)
         * are stored in the database until picked up and deleted. At this
         * point passkeys are reversible. 
         * However once passkeys are picked up, there is no longer a risk
         * 
         * 
         */
        

    }
    function PassE2EKeyMakeRequest($chatid, $providerid )
    {

        pdo_query("1",
        "
            delete from keysend where providerid = ? and passkey='' and chatid=?
        ",array($providerid, $chatid));

        
        $result = pdo_query("1","
            select providerid from chatmembers where chatid = ?   ",array($chatid));
        
        while($row = pdo_fetch($result) ){
            
            $senderid = $row['providerid'];
            
            
            pdo_query("1",
            "
                insert ignore into keysend 
                ( chatid, providerid, senderid, expiration, passkey, encoding ) 
                values
                ( ?, ?, ?, now(), '','JS' );
            ",array($chatid, $providerid, $senderid)
            );
            
        }
    }
    
    function GetSentKeys($providerid)
    {

        $script = "";
        $result = pdo_query("1",
        
            "
            select chatid, passkey from keysend where providerid = ? and passkey!=''
            ",array($providerid)
        );
        while($row = pdo_fetch($result)){
            
            echo "<script>localStorage.setItem('chat-$row[chatid]', '$row[passkey]'); </script>";
            pdo_query("1","delete from keysend where providerid=? and chatid=? ",array($providerid, $row['chatid']));
            
        }
        return $script;
    }
    function GetOneSentKey($providerid, $chatid)
    {

        $passkey = "";
        $result = pdo_query("1",
        
            "
            select passkey, senderid from keysend where providerid = ? and chatid= ? and passkey!=''
            ", array($providerid, $chatid)
        );
        if($row = pdo_fetch($result)){
            $passkey =  DecryptE2EPasskey($row['passkey'], $providerid);
            return $passkey;
            
        }
        return "";
    }

    
    
?>
