<?php
require_once("config-pdo.php");
require_once("htmlios.inc");
?>
<script>
$(document).ready( function (){
});
</script>
</head> 
<title>Start <?=$appname?></title>
<body> 
 
<!-- start of page one -->
<div data-role="page" id="home" data-theme="b">
	<div data-role="header"  data-theme="e">
	</div>
 
        <div data-role="content" data-theme="b">	
           <img class="viewlogomobile" src="../img/braxsecure.png">
            <ul data-role="listview" data-inset="true" data-theme="c">
                    <li><a href="loginmobile.php" target="_blank">Login</a></li>
                    <li><a href="https://www.braxsecure.com" target="_blank">Home</a></li>
            </ul>
                   
        </div>
 
	<div data-role="footer" data-position="fixed">
    	</div>
 
</form>
</div>
<!-- end of page one -->


</body>
</html>
