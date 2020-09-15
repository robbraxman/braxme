<?php
require_once("config.php");
require_once("htmlhead.inc.php");
$i = @mysql_safe_string($_GET['i']);
$apn = @mysql_safe_string($_GET['apn']);
$gcm = @mysql_safe_string($_GET['gcm']);
$source = @mysql_safe_string($_GET['s']);
if($i!='Y'){
    $i = "";
}
?>
<body class='mainfont' style='background-color:#E5E5E5'>
<?php
if($i == 'Y'){
    echo "<a class='smalltext' href='invite.php?s=$source&apn=$apn&gcm=$gcm&lang=$_SESSION[language]'>
          <img class='icon20' src='$rootserver/img/Arrow-Left-in-Circle_120px.png' /> 
          </a> Back<br><br>";
}
require_once("license-v1-text.php");
?>


</body>
</html>
