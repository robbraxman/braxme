<?php

require_once('CurlWrapper.php');

/**
 * MMServices encapsulates common web service functionality required for MM APIs.
 */
class MMServices {

    static private $mmv1Url = 'https://services.message-media.com/services/mmv1';

    protected $error = "";

    protected $username;
    protected $password;

    private $curlWrapper;

    public static function getVersionNumber() {
    	// This value is populated by configuration in the pom.xml & assembly.xml
    	return '83.10';
    }
    
    /**
     * Constructor
     *
     * @param   username    The username of the administrator user used to 
     *                      manage the account
     * @param   password    The password associated with the above username,
     *                      in plain text
     */
    public function __construct (
        $username,
        $password,
        $curlWrapper = NULL
    ){
        $this->username = $username;
        $this->password = $password;

        if ($curlWrapper == NULL) {
            $this->curlWrapper = new MMServicesCurlWrapper();
        } else {
            $this->curlWrapper = $curlWrapper;
        }
    }

    protected function constructAuthString () {
        return base64_encode($this->username . ':' . $this->password);
    }

    /**
     * Construct a query string from a parameters array
     *
     * @param   params  Associative array containing parameters. Values either
     *                  be strings or flat arrays containing one or more
     *                  parameter values. If an array value is provided, the
     *                  key will be repeated (and appended with []) for each
     *                  value in the array.
     *
     * @return  a concatenated query string
     */
    static private function constructQueryString (
        $params
    ) {
        // Construct the query string
        $queryString = '';
        if (is_array($params)) {
            foreach($params as $key => $value) {
                // Unroll parameter arrays
                if (is_array($value)) {
                    foreach($value as $subvalue) {
                        if ($queryString != '') {
                            $queryString .= '&';
                        }
                        $queryString .= $key. '[]=' . $subvalue;
                    }
                } else {
                    if ($queryString != '') {
                        $queryString .= '&';
                    }
                    $queryString .= "$key=$value";
                }
            }
        } 
        return $queryString; 
    }

    /**
     * Call the web service via mmv1. Requires curl.
     *
     * @param   resource  resource path to access
     * @param   output    Reference to a variable for returning response data
     *
     * @return  boolean true if the request succeeded, false otherwise
     */
    protected function callServicesWithJsonData (
        $resource,
        $jsonData,
        &$output = NULL,
        $method = 'POST'
    ) {
        $url = self::$mmv1Url . "/$resource";
        $ch = $this->curlWrapper->curl_init();
        $this->curlWrapper->curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array(
                        "Authorization: Basic " . $this->constructAuthString(),
                        "Accept: application/json",
                        "Content-Type: application/json",
                       	"User-Agent: MessageMedia_PHP-API/" . self::getVersionNumber() 
                ),
                CURLOPT_RETURNTRANSFER => true,
        		CURLOPT_SSL_VERIFYPEER => false,
        ));

        $res = $this->curlWrapper->curl_exec($ch);
        
        $curlInfo = $this->curlWrapper->curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (substr($curlInfo,0,1) == 4 || substr($curlInfo,0,1) == 5) {
            // There was an error returned from the URL
            $this->error = "Request returned error code " . $curlInfo;
            $output = $res;
            return false;
        }
        
        if ($res === FALSE){
            // We were unable to call the URL
            $this->error = "Failed to connect";
            return false;
        }

        $this->error = '';        
        $output = $res;
        return true; 
    }


    /**
     * Call the web service via mmv1. Requires curl.
     *
     * @param   resource  resource path to access
     * @param   output    Reference to a variable for returning response data
     *
     * @return  boolean true if the request succeeded, false otherwise
     */
    protected function callServicesWithQueryStringArray (
        $resource,
        $params,
        &$output = NULL
    ) {
        // Construct the final URL
        $queryString = self::constructQueryString($params);
        $url = self::$mmv1Url . "/$resource";
        if ($queryString != '') {
            $url .= "?$queryString";
        }

        $ch = $this->curlWrapper->curl_init();
        $this->curlWrapper->curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array(
                        "Authorization: Basic " . $this->constructAuthString(),
                        "Accept: application/json",
                       	"User-Agent: MessageMedia_PHP-API/" . self::getVersionNumber() 
                ),
                CURLOPT_HTTPGET => true,
                CURLOPT_RETURNTRANSFER => true,
        		CURLOPT_SSL_VERIFYPEER => false,
        ));

        $res = $this->curlWrapper->curl_exec($ch);
        
        $curlInfo = $this->curlWrapper->curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (substr($curlInfo,0,1) == 4 || substr($curlInfo,0,1) == 5) {
            // There was an error returned from the URL
            $this->error = "Request returned error code " . $curlInfo;
            $output = $res;
            return false;
        }
        
        if ($res === FALSE){
            // We were unable to call the URL
            $this->error = "Failed to connect";
            return false;
        }

        $this->error = '';
        $output = $res;
        return true; 
    }

    /**
     * Get the last error message from the webservice
     */
    public function getLastError () {
        if ($this->error != '' && substr($this->error, strlen($this->error) - 1) != "\n") {
            return $this->error . "\n";
        }
        return $this->error;
    }

    /**
     * Set the last error message
     */
    public function setLastError($message) {
        $this->error = $message;
    }
    
    /**
    * Convert string or array or broadcast fields into query string
    * recognised by serives code
    */
    public function formatBroadcastFields($broadcastFields) {
    	$broadcastFieldsQueryArray = array();
    	if (is_array($broadcastFields)) {
    		$i = 0;
    		foreach ($broadcastFields as $broadcastField) {
    			$broadcastFieldsQueryArray["broadcastField[$i][name]"] = $broadcastField;
    			$i++;
    		}
    	}
    	else {
    		if ($broadcastFields != '') {
    			$broadcastFieldsQueryArray["broadcastField[][name]"] = $broadcastFields;
    		}
    	}
    	return $broadcastFieldsQueryArray;
    }
}

