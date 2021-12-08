<?php
//*********************************************************************************
//*********************************************************************************
//KEY FOR CURRENT ENCODING - NOT USED IN NEW ENCRYPTION
//*********************************************************************************
//*********************************************************************************

function StdEncryptV2( $message, $encoding, $salt )
{


    try {

        $key = RetrieveSecretKey("$encoding");
    } catch (Exception $e) {
        return "";
    }        
    
    return OpenSSLEncrypt($message,$key.$salt);

}
function StdDecryptV2( $ciphermessage, $encoding, $salt )
{

    try {

        $key = RetrieveSecretKey("$encoding");
    } catch (Exception $e) {
        return "";
    }        
    
    return OpenSSLDecrypt($ciphermessage,$key.$salt);

}
    


function OpenSSLEncrypt($plaintext,$key)
{
    
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
    
    return $ciphertext;
}

function OpenSSLDecrypt($ciphertext,$key)
{
    $c = base64_decode($ciphertext);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
    if (hash_equals($hmac, $calcmac))// timing attack safe comparison
    {
       return $original_plaintext;
    }
    return "";
 }
  

    
?>
