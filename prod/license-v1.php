<?php
require_once("config.php");
require_once("htmlhead.inc.php");
$i = @tvalidator("PURIFY",$_GET['i']);
$apn = @tvalidator("PURIFY",$_GET['apn']);
$gcm = @tvalidator("PURIFY",$_GET['gcm']);
$source = @tvalidator("PURIFY",$_GET['s']);
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
