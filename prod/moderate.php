<?php
session_start();
require_once("config-pdo.php");
require_once("profanity.php");
$userid = @tvalidator("PURIFY",$_REQUEST['userid']);
$mode = @tvalidator("PURIFY",$_REQUEST['mode']);

if($userid == ''){
    echo "Userid is blank";
    exit;
}
if($mode=='W'){
    ModerationOneDayDelete($userid);
}
if($mode=='H'){
    $result = ModerationHardRestrict($userid);
    echo "$result";
    exit();
}
if($mode=='P'){
    $result = ModerationProfileRestrict($userid);
    echo "$result";
    exit();
}
if($mode=='S'){
    $result = ModerationShadowBan($userid);
    echo "$result";
    exit();
}
if($mode=='R'){
    $result = ModerationRestrict($userid);
    echo "$result";
    exit();
}
echo "OK $mode-";

exit();