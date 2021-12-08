<?php
session_start();
require_once("config-pdo.php");
require_once("htmlhead.inc.php");
?>
<script>
   $(document).ready( function() {
       
            
        $("form").submit(function() {
            
           
        });



        function CheckMessages()
        {
            
<?php
for($i=1; $i <= 10; $i++)
{
     echo "if( $('#providerid$i').val()!='' ) {\r\n";
     echo "   $('#statusmessage$i').load( 'alert.php',  {'providerid': $('#providerid$i').val(), 'createtime' : '20130327' });\r\n";
     echo "   $('#name$i').load( 'providername.php',  {'providerid': $('#providerid$i').val() });\r\n";
     echo "   $('#statusmessage$i').val();\r\n";
     echo "}\r\n";
}
?>
        }

        $("#startmonitor").click(function() {

            setInterval( function(){ CheckMessages(); }, 10000);
        });

 
       
    });
       </script>
       <title id="providername2"></title>
<BODY class="newmsgbody">
    <b><?=$appname?> Message Monitor</b>&nbsp;&nbsp;<a href="http://www.braxsecure.com">Home</a>
<?php 
echo "&nbsp&nbsp<a href='$rootserver/admin/loginmgr.php'>Login</a>&nbsp&nbsp";

echo "<button id='startmonitor' class='startmonitor'>Start Monitoring</button>&nbsp;&nbsp;";
echo "<button id='stopmonitor' class='stopmonitor'>Stop Monitoring</button>";
echo "<table class='monitor'>";
echo "<tr id='monitortitle'>";
echo "<td class='monitor1'>Subscriber ID</td>";
echo "<td class='monitor2'>Name</td>";
echo "<td class='monitor3'>Status</td>";
echo "</tr>";
for($i=1; $i <= 10; $i++)
{
    echo "<tr id='monitorrow'>";
    echo "<td class='monitor1'><input id='providerid$i' class='providerid' type='text'  size='10' maxlength='15' ></td>";
    echo "<td class='monitor2'><span id='name$i' class='name'></span></td>";
    echo "<td class='monitor3'><span id='statusmessage$i' class='statusmessage'></span></td>";
    echo "</tr>";
}

echo "</table>";
            
            
?>
   
       
</BODY>
</HTML>

    
