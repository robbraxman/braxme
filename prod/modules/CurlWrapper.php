<?php

/**
 * Very thin wrapper around cURL that can be mocked when unit testing
 * 
 * @codeCoverageIgnore
 */
class MMServicesCurlWrapper {

    public function curl_init($url = NULL) {
        return curl_init($url);
    }

    public function curl_exec($ch) {
        return curl_exec($ch);
    }

    public function curl_setopt_array($ch, $options) {
        return curl_setopt_array($ch, $options);
    }

    public function curl_getinfo($ch, $opt) {
        return curl_getinfo($ch, $opt);
    }
    
    public function curl_error($ch) {
    	return curl_error($ch);
    }
}

