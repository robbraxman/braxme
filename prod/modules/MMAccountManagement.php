<?php

require_once('MMServices.php');

/**
 * Class providing access to account management features
 */
class MMAccountManagement extends MMServices {

    /**
     * Constructor
     *
     * @param   username    The username of the administrator user used to 
     *                      manage the account
     * @param   password    The password associated with the above username,
     *                      in plain text
     * @param   curlWrapper Light wrapper around cURL functionality used in
     *                      the MMServices class. Mocked for unit testing.
     *                      (optional)
     */
    public function __construct(
        $username,
        $password,
        $curlWrapper = NULL   // Mocked for unit testing
    ) {
        parent::__construct($username, $password, $curlWrapper);
    }

    /**
     * Get the user ID associated with a username
     *
     * @param   username  username to be queried
     * 
     * @return  the associated user ID or null if the lookup failed.
     */
    public function getUserID(
        $username
    ) {
        // Get a list of users in order to map a username to a user ID
        $ret = '';
        if (!$this->callServicesWithQueryStringArray("userdetails/$username", array(), $ret)) {
            // cURL error
            $this->setLastError("Failed while retrieving user ID - ". $this->getLastError());
            return null;
        }

        // Response should be a string containing the user ID
        $result = json_decode($ret, true);
        if (!isset($result)) {
            $this->setLastError("Invalid response received from server when retrieving user ID.");
            return null;
        }

        return $result;
    }

    /*
     * Create a new gateway user
     *
     * @param   customerId          Customer to create the user for
     * @param   contactName         Contact name for the new user
     * @param   contactPhone        Contact phone number for the new user
     * @param   contactEmail        Contact email address for the new user
     * @param   newUser             Return param. The username of the newly 
     *                              created user
     * @param   isAdmin             Whether or not to create a user with admin
     *                              privileges (optional)
     * @param   creditLimit         Credit limit for the new user (optional)
     * @param   creditLimitType     When the credit limit should be reset. May 
     *                              be: daily, monthly, never (default daily)
     *                              (optional)
     * @param   nextCreditLimit     Credit limit that will be applied after the
     *                              end of the first credit period. Only applied
     *                              if period is 'monthly'.
     *                              (optional)
     *
     * @return  boolean true if new user created, false otherwise
     */
    public function createNewUser (
        $customerId,
        $contactName,
        $contactPhone,
        $contactEmail,
        &$newUser,
        $isAdmin = false,
        $creditLimit = null,
        $creditLimitFrequency = "daily",
        $nextCreditLimit = 0
    ){
        if (trim($contactName) == '') {
            $this->setLastError("Contact name is required.");
            return false;
        }

        switch (strtolower($creditLimitFrequency)) {
        case 'daily':
            $creditLimitFrequency = 'D';
            break;
        case 'monthly':
            $creditLimitFrequency = 'M';
            break;
        case 'never':
            $creditLimitFrequency = 'U';  // U = untimed
            break;
        default:
            $this->setLastError("Invalid credit limit frequency. Valid options are 'daily', 'monthly' and 'never'. Set to NULL to leave unchanged.");
            return false;
        }

        if ($creditLimit === null) {
            $creditLimit = 5000;
        }

        $params = array(
            "user" => array(
                "isAdmin" => $isAdmin,
                "isSupport" => false,
                "creditLimit" => $creditLimit,
                "creditLimitFrequency" => $creditLimitFrequency,
                "nextCreditLimit" => $nextCreditLimit,
                "product" => array('id' => 5, 'name' => 'WebSMS'),
                "contact" => array(
                    "name" => $contactName,
                    "phone" => $contactPhone,
                    "email" => $contactEmail)));

        $jsonData = json_encode($params);

        $ret = null;
        if (!$this->callServicesWithJsonData("customers/$customerId", $jsonData, $ret)) {
            $this->setLastError("Failed while creating new user - " . $this->getLastError());
            return false;
        }

        $result = json_decode($ret, true);
        if (!isset($result)) {
            $this->setLastError("Invalid response received from server when creating new user.");
            return false;
        }

        if (!array_key_exists('user', $result) || !array_key_exists('username', $result['user'])) {
            $this->setLastError("Invalid response received from server when creating new user.");
            return false;
        }

        $newUser = $result['user']['username'];

        return true;
    }

    /**
     * Set a user's credit limit and credit limit type
     *
     * @param   username       The MessageMedia username to edit the credit
     *                         limit for
     * @param   currentLimit   The credit limit to apply for the current
     *                         period (e.g. month). This is applied IMMEDIATELY.
     *                         Set to NULL to leave unchanged
     * @param   nextLimit      The credit limit to apply to the next period.
     *                         Only applies to MONTHLY credit limits. Set to
     *                         NULL to leave current values unchanged
     * @param   frequency      How often the credit limit should be reset.
     *                         May be: daily, monthly, never. Set to NULL to
     *                         leave current value unchanged
     *
     * @return  boolean true if user was updated, false otherwise
     */
    public function setUserCreditLimit (
        $username,
        $currentLimit = NULL,
        $nextLimit = 0,
        $frequency = 'daily'
    ){
        $frequency = strtolower($frequency);
        if ($frequency != NULL && $frequency != 'daily' && $frequency != 'monthly' && $frequency != 'never') {
            $this->setLastError("Invalid frequency specified. Must be 'daily', 'monthly', 'never' or NULL (to leave unchanged).");
            return false;
        }

        // Get the user ID associated with the specified username
        $userId = $this->getUserID($username);
        if ($userId == null) {
            // Error message will be set by getUserID()
            return false;
        }

        // Retrieve user data
        $ret = '';
        if (!$this->callServicesWithQueryStringArray("users/$userId", array(), $ret)) {
            // cURL error
            $this->setLastError("Failed while retrieving user data - " . $this->getLastError());
            return false;
        }

        // Convert user data to a PHP array
        $userDataArray = json_decode($ret, true);
        if ($userDataArray == null || !is_array($userDataArray) || !array_key_exists('user', $userDataArray)) {
            $this->setLastError("Invalid response received from server when retrieving user data.");
            return false;
        }
    
        // Do not attempt to update the password   
        if (array_key_exists('password', $userDataArray['user'])) {
            unset($userDataArray['user']['password']);
        }

        if ($frequency !== NULL) { 
            // Update frequency data
            switch ($frequency) {
            case 'daily':
                $userDataArray['user']['creditLimitFrequency'] = 'D';
                break;
            case 'monthly':
                $userDataArray['user']['creditLimitFrequency'] = 'M';
                break;
            case 'never':
                $userDataArray['user']['creditLimitFrequency'] = 'U';  // U = untimed
                break;
            }
        }

        if ($currentLimit !== NULL) {
            $userDataArray['user']['creditLimit'] = $currentLimit;
        }

        if ($nextLimit !== NULL) {
            $userDataArray['user']['nextCreditLimit'] = $nextLimit;
        }

        // Update user data
        $jsonData = json_encode($userDataArray);
        if (!$this->callServicesWithJsonData("users/$userId", $jsonData, $ret, 'PUT')) {
            // cURL error
            $this->setLastError("Failed while updating user data - " . $this->getLastError());
            return false;
        }

        return true;
    }

    /**
     * Fetch the details of a user's credit limits
     *
     * Note that you should use SmsInterface::getCreditsRemaining() to get
     * details of credits REMAINING
     *
     * @param   username        The MessageMedia username to fetch details for
     * @param   currentLimit    Return param. The credit limit for the current
     *                          period (e.g. month). Not returned for daily
     *                          credit limit users
     * @param   nextLimit       Return param. The credit limit that will be
     *                          applied for the next period (e.g. month).
     *                          NULL is returned for daily credit limit users
     * @param   frequency       Return param. How often the credit limit will
     *                          be reset to "nextLimit".
     *                          Possible values are: "daily", "monthly" or never"
     *
     * @return  boolean true if the details are fetched from the MessageMedia
     *          gateway, false if an error occurred
     */
    public function getUserCreditLimit (
        $username,
        &$currentLimit = NULL,
        &$nextLimit = NULL,
        &$frequencyString = NULL
    ){
        // Get the user ID associated with the specified username
        $userId = $this->getUserID($username);
        if ($userId == null) {
            // Error message will be set by getUserID()
            return false;
        }

        // Retrieve user data
        $ret = '';
        if (!$this->callServicesWithQueryStringArray("users/$userId", array(), $ret)) {
            // cURL error
            $this->setLastError("Failed while retrieving user data - " . $this->getLastError());
            return false;
        }

        // Convert user data to a PHP array
        $userDataArray = json_decode($ret, true);
        if ($userDataArray == null || !is_array($userDataArray) || !array_key_exists('user', $userDataArray)) {
            $this->setLastError("Invalid response received from server when retrieving user data.");
            return false;
        }

        // Convert frequency character to word
        $frequencyString = 'daily';
        if (array_key_exists('creditLimitFrequency', $userDataArray['user'])) {
            $frequency = $userDataArray['user']['creditLimitFrequency'];
            switch ($frequency) {
            case 'D':
                $frequencyString = 'daily';
                break;
            case 'M':
                $frequencyString = 'monthly';
                break;
            case 'U': // untimed
                $frequencyString = 'never';
                break;
            }
        }
 
        if (array_key_exists('creditLimit', $userDataArray['user'])) {
            $currentLimit = (int) $userDataArray['user']['creditLimit'];
        }
   
        // Return nextCreditLimit only if frequency is not daily
        if (array_key_exists('nextCreditLimit', $userDataArray['user']) &&
                array_key_exists('creditLimitFrequency', $userDataArray['user'])) {
            $nextLimit = (int) $userDataArray['user']['nextCreditLimit'];
        } else {
            $nextLimit = 0;
        }
        
        return true;
    }
}

