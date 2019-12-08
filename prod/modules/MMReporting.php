<?php

require_once('MMServices.php');

class MMReporting extends MMServices {

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
     * Show all messages sent or received for a user between the two timestamp
     * values.
     *
     * @param   userId          uid for the user to be reporting on
     *                          (required)
     * @param   rangeStart      Index of first row to be returned 
     *                          (optional; default is 0)
     * @param   rangeLimit      Index of last row to be returned
     *                          (optional; default is 50)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   broadcast       Only show broadcast messages. Use 'ALL' to show
     *                          messages for all broadcasts, or a broadcast URN
     *                          to show messages for a specific broadcast
     *                          (optional; default is 'ALL')
     * @param   broadcastFields If broadcast is set, broadcastFields is an
     *                          array of fields that should be reported on
     *                          (only required if broadcast is set)
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     *
     * @return  a PHP array containing the requested report data, or NULL if an
     *          error occurred
     */
    public function showReportForUser(
        $userId,
        $rangeStart = null,
        $rangeLimit = null,
        $messageType = null,
        $timezone = null,
        $broadcast = null,
        $broadcastFields = null,
        $timestampBegin = null,
        $timestampEnd = null
    ) {
        $params = Array('returnUri' => 0);

        if ($rangeStart !== null) {
            if (!is_int($rangeStart) || $rangeStart < 0) {
                $this->setLastError("Invalid range start index.");
                return null;
            }
            $params['start'] = $rangeStart;
        }

        if ($rangeLimit !== null) {
            if (!is_int($rangeLimit) || $rangeLimit < 1 || $rangeLimit > 10000) {
                $this->setLastError("Invalid range limit. Valid range is 1 to 10000.");
                return null;
            }
            $params['limit'] = $rangeLimit;
        }

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($broadcast !== null) {
            $params['broadcast'] = $broadcast;
        }

        if ($broadcastFields !== null) {
            $params = array_merge($params, $this->formatBroadcastFields($broadcastFields));
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        if (!$this->callServicesWithQueryStringArray ("users/$userId/reporting", $params, $ret)) {
            return null;
        }

        $result = json_decode($ret, true);

        if (isset($result) && array_key_exists('reports', $result) && array_key_exists('list', $result['reports'])) {
            if ($result['reports']['list'] == NULL) {
                return Array();
            }
            return $result['reports']['list'];
        }
    
        return null;
    }    

    /**
     * Show all messages sent or received for a customer between the two
     * timestamp values.
     *
     * @param   customerId      ID for the customer to be reported on
     *                          (required)
     * @param   rangeStart      Index of first row to be returned 
     *                          (optional; default is 0)
     * @param   rangeLimit      Index of last row to be returned
     *                          (optional; default is 50)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     *
     * @return  a PHP array containing the requested report data or NULL if an
     *          error occurred
     */
    public function showReportForCustomer(
        $customerId,
        $rangeStart = null,
        $rangeLimit = null,
        $messageType = null,
        $timezone = null,
        $timestampBegin = null,
        $timestampEnd = null
    ) {
        $params = Array('returnUri' => 0);

        if ($rangeStart !== null) {
            if (!is_int($rangeStart) || $rangeStart < 0) {
                $this->setLastError("Invalid range start index.");
                return null;
            }
            $params['start'] = $rangeStart;
        }

        if ($rangeLimit !== null) {
            if (!is_int($rangeLimit) || $rangeLimit < 1 || $rangeLimit > 10000) {
                $this->setLastError("Invalid range limit. Valid range is 1 to 10000.");
                return null;
            }
            $params['limit'] = $rangeLimit;
        }

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        if (!$this->callServicesWithQueryStringArray("customers/$customerId/reporting", $params, $ret)) {
            return null;
        }

        $result = json_decode($ret, true); 
        
        if (isset($result) && array_key_exists('reports', $result) && array_key_exists('list', $result['reports'])) {
            if ($result['reports']['list'] == NULL) {
                return Array();
            }
            return $result['reports']['list'];
        }
    
        return null;
    }

    /**
     * Retrieve a summarised report of messages sent and received for a user
     * account between two timestamp values.
     *
     * @param   userId          ID of the user to be reported on
     *                          (required)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   broadcast       Only show broadcast messages. Use 'ALL' to show
     *                          messages for all broadcasts, or a broadcast URN
     *                          to show messages for a specific broadcast
     *                          (optional; default is 'ALL')
     * @param   broadcastFields If broadcast is set, broadcastFields is an
     *                          array of fields that should be reported on
     *                          (only required if broadcast is set)
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   groupType       How to group results. Usually, this should be
     *                          set to 'date'. If broadcast or broadcastField
     *                          are set, then this parameter can also be set
     *                          to either 'broadcast' or 'broadcastField'.
     *                          (required)
     * @param   groupName       Used when groupType is date or broadcastField.
     *                          When groupType is 'date', valid values are:
     *                          'month', 'week' or 'date' (defaults to 'date').
     *                          When groupType is 'broadcastField' this should
     *                          be the name of a broadcast field.
     *                          (optional)
     *
     * @return  a PHP array containing the requested report data or NULL if an
     *          error occurred
     */
    public function showSummaryReportForUser(
        $userId,
        $messageType = null,
        $timezone = null,
        $broadcast = null,
        $broadcastFields = null,
        $timestampBegin = null,
        $timestampEnd = null,
        $groupType,
        $groupName = null
    ) {
        $params = Array('returnUri' => 0);

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($broadcast !== null) {
            $params['broadcast'] = $broadcast;
        }

        if ($broadcastFields !== null) {
            $params = array_merge($params, $this->formatBroadcastFields($broadcastFields));
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        if ($groupType == null) {
            $this->setLastError("groupType parameter is required.");
            return null;
        }

        $params['group[0][type]'] = $groupType;

        if ($groupName !== null) {
            $params['group[0][granularity]'] = $groupName;
        }

        if (!$this->callServicesWithQueryStringArray("users/$userId/reporting/summary", $params, $ret)) {
            return null;
        }

        $result = json_decode($ret, true);

        if (isset($result) && array_key_exists('summaries', $result) && array_key_exists('list', $result['summaries'])) {
            if ($result['summaries']['list'] == NULL) {
                return Array();
            }
            return $result['summaries']['list'];
        }
    
        return null;
    }

    /**
     * Retrieve a summarised report of messages sent and received for a customer
     * account between two timestamp values.
     *
     * @param   customerId      ID of the customer account to be reported on
     *                          (required)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   groupType       How to group results. Usually, this should be
     *                          set to 'date'. If broadcast or broadcastField
     *                          are set, then this parameter can also be set
     *                          to either 'broadcast' or 'broadcastField'.
     *                          (required)
     * @param   groupName       Used when groupType is date or broadcastField.
     *                          When groupType is 'date', valid values are:
     *                          'month', 'week' or 'date' (defaults to 'date').
     *                          When groupType is 'broadcastField' this should
     *                          be the name of a broadcast field.
     *                          (optional)
     *
     * @return  a PHP array containing the requested report data or NULL if an
     *          error occurred
     */
    public function showSummaryReportForCustomer(
        $customerId,
        $messageType = null,
        $timezone = null,
        $timestampBegin = null,
        $timestampEnd = null,
        $groupType,
        $groupName = null
    ) {
        $params = Array('returnUri' => 0);

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        if ($groupType == null) {
            $this->setLastError("groupType parameter is required.");
            return null;
        }

        $params['group[0][type]'] = $groupType;

        if ($groupName !== null) {
            $params['group[0][granularity]'] = $groupName;
        }

        if (!$this->callServicesWithQueryStringArray("customers/$customerId/reporting/summary", $params, $ret)) {
            return null;
        }

        $result = json_decode($ret, true);

        if (isset($result) && array_key_exists('summaries', $result) && array_key_exists('list', $result['summaries'])) {
            if ($result['summaries']['list'] == NULL) {
                return Array();
            }
            return $result['summaries']['list'];
        }
    
        return null;
    }

    /**
     * Checks that a user has the correct credentials for downloading a report,
     * and that the requested report does not exceed the maximum report
     * download size (currently 10000 rows)
     *
     * @param   userId          ID of the user to be reported on
     *                          (required)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     *
     * @return  Boolean indicating whether or not user can download the report
     */
    public function checkCsvReportForUser(
        $userId,
        $messageType = null,
        $timezone = null,
        $timestampBegin = null,
        $timestampEnd = null
    ) {
        $params = Array();

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        $params['Authorization'] = "Basic+" . self::constructAuthString();

        if (!$this->callServicesWithQueryStringArray("users/$userId/reporting/csv/check", $params, $ret)) {
            return null;
        }

        $result = json_decode($ret, true);

        if (isset($result) && array_key_exists('allowed', $result) && $result['allowed'] == 'true') {
            return true;
        }

        return false;
    }

    /**
     * Checks that a user has the correct credentials for downloading a report,
     * and that the requested report does not exceed the maximum report
     * download size (currently 10000 rows)
     *
     * @param   customerId      ID of the customer to be reported on
     *                          (required)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     *
     * @return  Boolean indicating whether or not customer can download the report
     */
    public function checkCsvReportForCustomer(
        $customerId,
        $messageType = null,
        $timezone = null,
        $timestampBegin = null,
        $timestampEnd = null
    ) {
        $params = Array();

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        $params['Authorization'] = "Basic+" . self::constructAuthString();

        if (!$this->callServicesWithQueryStringArray("customers/$customerId/reporting/csv/check", $params, $ret)) {
            return null;
        }

        $result = json_decode($ret, true);

        if (isset($result) && array_key_exists('allowed', $result) && $result['allowed'] == 'true') {
            return true;
        }

        return false;
    }

    /**
     * Download the requested report in CSV format with the content type set
     * to application/octet-stream.
     *
     * @param   userId          ID of the user to be reported on
     *                          (required)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   broadcast       Only show broadcast messages. Use 'ALL' to show
     *                          messages for all broadcasts, or a broadcast URN
     *                          to show messages for a specific broadcast
     *                          (optional; default is 'ALL')
     * @param   broadcastFields If broadcast is set, broadcastFields is an
     *                          array of fields that should be reported on
     *                          (only required if broadcast is set)
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     *
     * @return  the request report in CSV format.
     */
    public function showCsvReportForUser(
        $userId,
        $messageType = null,
        $timezone = null,
        $broadcast = null,
        $broadcastFields = null,
        $timestampBegin = null,
        $timestampEnd = null
    ) {
        $params = Array();

        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($broadcast !== null) {
            $params['broadcast'] = $broadcast;
        }

        if ($broadcastFields !== null) {
            $params = array_merge($params, $this->formatBroadcastFields($broadcastFields));
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        if (!$this->checkCsvReportForUser($userId, $messageType, $timezone, $timestampBegin, $timestampEnd)) {
            if (strpos($this->getLastError(), 'Request returned error code ') !== false) {
                return null;
            }
            $this->setLastError("Sorry, your download could not be processed. It may be too large, please consider refining your search criteria.");
            return null;
        }

        $params['Authorization'] = "Basic+" . $this->constructAuthString();

        if (!$this->callServicesWithQueryStringArray("users/$userId/reporting/csv", $params, $ret)) {
            return null;
        }

        return $ret;
    }

    /**
     * Download the requested report in CSV format with the content type set
     * to application/octet-stream.
     *
     * @param   customerId      ID of the customer to be reported on
     *                          (required)
     * @param   messageType     Kind of message, 'S' for sent, 'R' for received
     *                          (optional; default is 'S')
     * @param   timezone        Timezone identifier - e.g. America/Los_Angeles
     *                          (optional; default timezone is set in manager).
     * @param   timestampBegin  Timestamp for the beginning of the range to be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     * @param   timestampEnd    Timestamp for the end of the range ot be
     *                          reported on. Timestamp format:
     *                          'YYYY-MM-DD HH:MM:SS'
     *                          (optional)
     *
     * @return  the requested report in CSV format
     */
    public function showCsvReportForCustomer(
        $customerId,
        $messageType = null,
        $timezone = null,
        $timestampBegin = null,
        $timestampEnd = null
    ) {
        $params = Array();
        if ($messageType !== null) {
            $messageType = strtoupper($messageType);
            if ($messageType != 'S' && $messageType != 'R') {
                $this->setLastError("Invalid message type. Allowed values are 'S' or 'R'.");
                return null;
            }
            $params['messageType'] = $messageType;
        }

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        if ($timestampBegin !== null && $timestampEnd !== null) {
            $params['timestamp[b]'] = Array(
                urlencode($timestampBegin),
                urlencode($timestampEnd));
        }

        if (!$this->checkCsvReportForCustomer($customerId, $messageType, $timezone, $timestampBegin, $timestampEnd)) {
            if (strpos($this->getLastError(), 'Request returned error code ') !== false) {
                return null;
            }
            $this->setLastError("Sorry, your download could not be processed. It may be too large, please consider refining your search criteria.");
            return null;
        }

        $params['Authorization'] = "Basic+" . $this->constructAuthString();

        if (!$this->callServicesWithQueryStringArray("customers/$customerId/reporting/csv", $params, $ret)) {
            return null;
        }

        return $ret;
    }

}

