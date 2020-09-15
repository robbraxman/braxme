<?php
require_once("localsettings.php");


//*********************************************************************************
//*********************************************************************************
//Current Forward Secrecy Encoding Scheme
//*********************************************************************************
//*********************************************************************************
    $result = pdo_query("1", 
       "select encoding from cryptkeys where keyid = 'NEWENCODING' and passphrase='' ");
    if( $row = pdo_fetch($result))
    {
        $currentencoding = $row['encoding'];
    }
    $superadmin = @tvalidator("PURIFY",$_SESSION['superadmin']);
    //if($superadmin!='Y'){
    //    $currentencoding = "SPA1.1";
    //}
    //$currentencoding = "SPA1.1";
    $_SESSION['responseencoding'] = $currentencoding;
    
    $keyCache = array();
    $keyCacheCount = 0;
    $keyQueryCount = 0;
//*********************************************************************************
//*********************************************************************************
//*********************************************************************************



//*********************************************************************************
//*********************************************************************************
//SITE KEY 1 FOR CURRENT ENCODING SCHEME
//*********************************************************************************
//*********************************************************************************

    $lastencoding = '';
    $site_key1 = RetrieveSecretKey("$currentencoding");
    //GenerateNewEncoding(); - moved to Startup.Inc



//*********************************************************************************
//*********************************************************************************
//KEY FOR CURRENT ENCODING - NOT USED IN NEW ENCRYPTION
//*********************************************************************************
//*********************************************************************************
    //Create Instance
    $encryptor = new Encryptor('');


    class Encryptor {
            const HC_KEY = 
                    "eb6b832b805978c6e3542211aa933320e46e4442112e6f6732a3baca4918389536f7b54f32e56b4ed446b4cc8d19b53de1ecfb1471dd6b8544615da88b4e1685";
            private $site_keyV2 = '';
            private $hash_method = 'sha512';
            private $cipher = MCRYPT_RIJNDAEL_256;
            private $mode = MCRYPT_MODE_CBC;

            public function __construct($site_key=NULL) {
                    !$site_key OR $this->set_site_key($site_key);
            }
            public function setStreamKey($passphrase ) {


                $iv = substr(md5('iv'.$passphrase, true), 0, 8);
                 $key = substr(md5('pass1'.$passphrase, true) . 
                                md5('pass2'.$passphrase, true), 0, 24);
                 $opts = array('iv'=>$iv, 'key'=>$key);
                return $opts;


                
            }

            
            
            public function encryptV2($string, $salt) {
                    $key = substr(
                            hash_hmac($this->hash_method, $salt, $this->site_keyV2 . self::HC_KEY), 
                            19, 
                            mcrypt_get_key_size($this->cipher, $this->mode));
                    $iv = mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
                    return base64_encode($iv . mcrypt_encrypt($this->cipher, $key, $string, $this->mode, $iv));
            }

            public function decryptV2($string, $salt) {
                    $key = substr(
                            hash_hmac($this->hash_method, $salt, $this->site_keyV2 . self::HC_KEY), 
                            19, 
                            mcrypt_get_key_size($this->cipher, $this->mode));
                    $string = base64_decode($string);
                    $iv_size = mcrypt_get_iv_size($this->cipher, $this->mode);
                    $iv = substr($string, 0, $iv_size);
                    $string = substr($string, $iv_size);
                    $string = mcrypt_decrypt($this->cipher, $key, $string, $this->mode, $iv);
                    $string = rtrim($string, "\0");
                    return $string;
            }


            public function hash($string, $key, $times=2) {
                    $algos = hash_algos();
                    if (!in_array($this->hash_method, $algos)) {
                            throw new Exception("Hash method not available: {$this->hash_method}");
                    }
                    $combined_key = $this->site_keyV2 . $key . self::HC_KEY;
                    $hashed_string = $combined_key . $string;
                    for ($i = 1; $i <= $times; $i++) {
                            $hashed_string = hash_hmac($this->hash_method, $hashed_string, $combined_key);
                    }
                    return $hashed_string;
            }

            public function set_site_keyV2($key, $encoding) {
                if( $encoding!='SPA1.0'){
                    $this->site_keyV2 = $key;
                }
                else  {
                    $this->site_keyV2 = '';
                }
            }


    }



    function EncryptResponse( $newkey, $salt1, $salt2 )
    {
            $encoding = $_SESSION['responseencoding'];

            if( substr($encoding,0,3) == "SPA")
            {
                return StdEncrypt( $newkey, $encoding, $salt1.$salt2 );
            }
        return "";
    }


    function DecryptResponse( $responsetext, $encoding, $salt1, $salt2 )
    {

            if( substr($encoding,0,3) == "SPA")
            {
                return StdDecrypt( $responsetext, $encoding, $salt1 . $salt2 );
            }

            return "";
    }


    function EncryptEmail( $message, $salt1 )
    {
            $encoding = $_SESSION['responseencoding'];

            if( substr($encoding,0,3) == "SPA")
            {
                return StdEncrypt( $message, $encoding, $salt1 );
            }
        return "";
    }
    function DecryptEmail( $message, $encoding, $salt1 )
    {

            //$encoding = "SPA1.0";
            if( substr($encoding,0,3) == "SPA")
            {
                return StdDecrypt( $message, $encoding, $salt1 );
            }
            return "";
    }


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

            return $SlashedMessage;
    }


    function DecryptPost( $message, $encoding, $salt1, $salt2 )
    {

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
                return StdDecrypt( $message, $encoding, $salt1 . $salt2 );
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
                //return StdEncrypt( $message, $salt1.$salt2 );
            }

            return $SlashedMessage;
    }


    function DecryptChat( $message, $encoding, $salt1, $salt2 )
    {


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

            return $UnencodedText;
    }    

    
    function EncryptJs( $message, $salt1 )
    {

            if( $message == '')
                return '';
            
            return StdEncrypt( $message, "js", $salt1 );
    }


    function DecryptJs( $message, $salt1  )
    {

            if( $message == '')
                return '';

            return StdDecrypt( $message, "js", $salt1 );
    }    
    
    
    function EncryptTextCustomEncode( $message, $encoding, $salt1 )
    {

            if( $message == '')
                return '';
            if( $encoding == '')
                return '';
            
            //$encoding = $_SESSION['responseencoding'];

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
                //$encoding = $_SESSION['responseencoding'];
                return StdEncrypt( $message, $encoding, $salt1 );
            }

            return $SlashedMessage;
    }

    
    
    function StdEncrypt( $message, $encoding, $salt )
    {
        global $site_key1;
        global $encryptor;


        try {

            $site_key1 = RetrieveSecretKey("$encoding");
            if( $site_key1 == ''){
                return "";
            }
        } catch (Exception $e) {
            return "";
        }        
            
        $encryptor->set_site_keyV2( $site_key1, $encoding );
        $SlashedMessage = $encryptor->encryptV2(stripslashes($message), "$salt");
        $SlashedMessage = base64_encode( $SlashedMessage );

        return $SlashedMessage;
    }

    function StdDecrypt( $message, $encoding, $salt )
    {
        global $site_key1;
        global $encryptor;
        
        try {

            $site_key1 = RetrieveSecretKey("$encoding");
            if( $site_key1 == ''){
                return "Decrypt Error";
            }
            $encryptor->set_site_keyV2( $site_key1, $encoding );
            $UnencodedText = base64_decode( $message );
            $UnencodedText = $encryptor->decryptV2( $UnencodedText, $salt );

                return $UnencodedText;
        } catch (Exception $e) {
            return "Decrypt Exception";
        }        
    }

//*********** NOT USED
//*********** NOT USED
//*********** NOT USED
//*********** NOT USED
//*********** NOT USED
//*********** NOT USED
//*********** NOT USED

function EncryptMessage( $vpnkey, $message, $salt1, $salt2 )
{
        global $key;
        global $encryptor;
        global $currentencoding;
        global $site_key1;
        
                


        //Current Encoding Scheme
        $encoding = "$currentencoding"; //Takes effect on Future Messages
        $_SESSION[encoding]="$encoding";
        
        //echo "<script>console.log('key $key Encoding $encoding site $site_key1')</script>";
        

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
        if(substr($encoding, 0, 2) == 'SV' ) //=="SV2.2" || $encoding =="SV2.3")
        {
            //vpn key is decoded from base64
            $encryptor->set_site_keyV2( $site_key1, $encoding );
            $SlashedMessage = nl2br( stripslashes($message));
            $SlashedMessage = $encryptor->encryptV2(stripslashes($message), $vpnkey . $salt1 . $key);
            $SlashedMessage = base64_encode( $SlashedMessage );
        }
        return $SlashedMessage;
}


function DecryptMessage( $message, $encoding, $salt1, $salt2, $storedkey )
{
        global $encryptor;
        global $site_key1;


        //Decoding Routines if Applicable
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
        if( substr($encoding,0,2) == 'SV' ) //=='SV2.2' || $encoding=='SV2.3')
        {
            $encryptor->set_site_keyV2( $site_key1, $encoding );
            $UnencodedText = base64_decode( $message );
            $UnencodedText = $encryptor->decryptV2( $UnencodedText, $salt1 . $storedkey);
        }


        return $UnencodedText;
}
function StreamEncode($source, $target, $encoding){
    
        global $encryptor;

        unlink($target);

    
        $passphrase = RetrieveSecretKey("$encoding");
        $opts = $encryptor->setStreamKey($passphrase);

        
        
        $fp1 = fopen( $source, 'r');
        $fp2 = fopen( $target, 'w');


        stream_filter_append($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        //stream_filter_prepend($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        //SetStreamFilter( $fp2, $encoding, "W");

        while (!feof($fp1)) {
            $contents = fread($fp1, 0xFFFFF);
            fwrite($fp2, $contents);
        }        

        fclose($fp1);        
        fclose($fp2);
        unlink($source);
        //copy($target, $target.".jpg");

        return true;

}
function TextStreamEncode($contents, $target, $encoding){
    
        global $encryptor;

        if(file_exists($target)){
            unlink($target);
        }
        $passphrase = RetrieveSecretKey("$encoding");
        $opts = $encryptor->setStreamKey($passphrase);

        $fp2 = fopen( $target, 'w');

        stream_filter_append($fp2, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);

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
    
        $passphrase = RetrieveSecretKey("$encoding");
        $opts = $encryptor->setStreamKey($passphrase);

        stream_filter_append($fp, 'mdecrypt.rijndael-128', STREAM_FILTER_READ, $opts);
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

        //This prevents constant requerying if the encoding is the same - performance
        if( $lastencoding==$encoding && $site_key1 !=''){
            $keyCacheCount++;
            return $site_key1;
        }
        
        //See if already in Cache
        
        
        /*
        try {
            $site_key1 = $keyCache[$encoding];
            if($site_key1 !=''){
            
                $keyCacheCount++;
                return $site_key1;
            }
        }  catch( Exception $e){
            
        }
        */
        
          
         
        
        /* Speed Implementation Tip for Future 
         *  One way to speed this up for later is to allow caching of several keys to prevent
         *  having to always retrieve
         *  At the moment - not an issue yet
         */
        
        $lastencoding = $encoding;
        /*
         * This routine ensures that key management is kept in a separate module
         * this server is visible and accessible only on AWS VPC. Traffic is restricted
         * bidirectionally to registered EC2 servers. Logic of key generation is 
         * unknown to this EC2 instance. In case of database intrusion, the absence
         * of a key lookup prevents decryption
          */
        
        //We cache JSKEY
        /*
        if($encoding == 'js' && isset($_SESSION['jskey']) ){
            $site_key1 = $_SESSION['jskey'];
            return $site_key1;
        }
        if(isset($_SESSION[$encoding])){
            $site_key1 = $_SESSION[$encoding];
            return $site_key1;
        }
         * 
         */
        if($encoding!='js' && isset($_SESSION[$encoding])){
            $site_key1 = $_SESSION[$encoding];
            return $site_key1;
        }

        
        //Load Balancer Code
        $random = rand(1,6);
        
        //if(intval($random) % 2 == 0){
        if( $random == 1 || $random == 2 ){ 
            
            $ch = curl_init('https://crypt.braxvpc.me/prod/get1timepad.php');
            
        } else 
        if( $random == 3 || $random == 4 ){ 
            
            $ch = curl_init('https://crypt.braxvpc.me/prod/get1timepad.php');
            
        } else 
        if( $random == 5 || $random == 6 ){ 
            
            $ch = curl_init('https://crypt.braxvpc.me/prod/get1timepad.php');
            
        } 
        
        if($ch!== false ){

            curl_setopt($ch, CURLOPT_POST, true);


            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            $data_string = "encoding=$encoding&apikey=FyUbSACtui877tooj";
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $site_key1 = curl_exec($ch);

            //close connection
            curl_close($ch);
        } else {
            $site_key1 = "";
        }
        
        if($site_key1 === '' || $site_key1 === false ){
            $site_key1 = '';
            error_log("GetKey Error $encoding");
            echo "<br>Sorry - Maintenance is being performed. We'll be back shortly.<br>";
            exit();
        }
        
        //No repeats necessary on JS - Not as secure
        if($encoding == "js"){
            $_SESSION['jskey'] = "$site_key1";
        } else {
            $_SESSION[$encoding] = "$site_key1";
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
        ", array($chatid, $recipientid, $senderid,$passjeyNew64)
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
                insert into keysend 
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