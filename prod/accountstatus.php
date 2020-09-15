<?php
session_start();
require_once("config-pdo.php");
require_once("password.inc.php");

require_once("htmlmobile.inc");
require("accountcheck.inc");
$loginid = mysql_safe_string($_POST['loginid']);
?>
    <title>Account Status</title>
</head>    
<body class="appbody">
<div data-role="page" data-theme="b">
	<div data-role="header" data-position="fixed" data-theme="a">
                <h1>Account Status</h1>
                <a href="<?=$_SESSION[returnurlonly]?>" data-ajax='false' data-icon="arrow-l" data-iconpos="left" class="ui-btn-right">Back</a>
	</div>
 
        <div data-role="content" data-theme="b">	

        <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" readonly=readonly size="15" >
        <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="loginid" readonly=readonly size="15" value="<?php echo "$loginid"; ?>" >
        <INPUT id="password" TYPE="hidden" name="password" >
        
<?php        
           if( $avatarurl!='')
           {
                echo "<img class='avatar' src='$avatarurl'><br>";
           }
?>        

        <img class='viewbarmsg' src="../img/msgbar.png"><br>
        Contract Type
        <INPUT id="contracttype" value='<?=$contracttype?>' TYPE="text" NAME="contracttype" SIZE="10" readonly='readonly' maxlength="15" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        Contract Period
        <INPUT id="contractperiod" value='<?=$contractperiod?>' TYPE="text" NAME="contractperiod" SIZE="30" readonly='readonly' maxlength="15" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        Allow Texting?
        <INPUT id="allowtexting"  value='<?=$allowtexting?>' TYPE="text" NAME="allowtexting" SIZE="2" readonly='readonly' maxlength="15" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        Total Messages Available in Plan
        <INPUT id="msgcount"  value='<?=$msgavail?>' TYPE="text" NAME="msgavail" SIZE="10" readonly='readonly' maxlength="15" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        Total Messages Sent/Received
        <INPUT id="msgcount"  value='<?=$msgcount?>' TYPE="text" NAME="msgcount" SIZE="10" readonly='readonly' maxlength="15" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
        
        

        <div id="status" class="status" ></div>
</BODY>
</HTML>

