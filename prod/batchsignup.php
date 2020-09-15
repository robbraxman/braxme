<?php
require_once("config-pdo.php");
$_SERVER['DOCUMENT_ROOT']='/var/www/html';
require_once("aws.php");
require_once("SmsInterface.inc");
require_once("sendmail.php");
require_once("crypt.inc.php");

require_once("signupfunc.php");

function ProcessUpload3()
{
    $status = true;
    $signup = new SignUp;
    $signup->BatchCreateAccount("", "LIMIT 200");
    unset( $signup );
    return $status;
}

ProcessUpload3();

?>