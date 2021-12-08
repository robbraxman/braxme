<?php
//*********************************************************************************
//*********************************************************************************
//KEY FOR CURRENT ENCODING - NOT USED IN NEW ENCRYPTION
//*********************************************************************************
//*********************************************************************************


    class Encryptor {
            const HC_KEY = 
                    "somekey";
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
            if( $site_key1 == '' || $site_key1=='error'){
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


    
?>
