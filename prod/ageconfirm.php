<?php
session_start();
require("validsession.inc.php");
require_once("config.php");
require_once("password.inc.php");

require_once("htmlhead.inc.php");
$providerid = @mysql_safe_string($_SESSION['pid']);

?>
<script>
   $(document).ready( function() {
        $('#pid').val( '<?=$providerid?>' ); 
        $('#loginid').val( '<?=$loginid?>' ); 
        providerid = '<?=$providerid?>'; 
        loginid = '<?=$loginid?>'; 
        password = '<?=$password?>'; 
        
            $('td.label').hide();
            $('td.labelrequired').hide();
            $('div.label').show();
            $('div.labelrequired').show();
                
        $('body').on("mouseenter", ".divbutton3", function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
            
        });
        $('body').on("mouseleave", ".divbutton3", function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
            
        });
        
        
        $('body').on('click','#age0', function() 
        {
            agesave(0);
        });
        $('body').on('click','#age13', function() 
        {
            agesave(13);
        });
        $('body').on('click','#age14', function() 
        {
            agesave(14);
        });
        $('body').on('click','#age18', function() 
        {
            agesave(18);
        });
        $('body').on('click','#age21', function() 
        {
            agesave(21);
        });
        function agesave(age)
        {
            $('#status').load("agesave.php", { 
                'pid': $('#pid').val(),
                'age': age
            }, function(data, status){
            });
       }
 
       
    });
       </script>
    <title>Profile Picture</title>
</head>    
<BODY class="appbody mainfont" >

    <div class="showtable">
        <table>

        <tr>
        <td class='dataarea'>
        <span class='pagetitle'>Select your age category</span>
        <br>
        <input class='age' id='age21' name='age' type='radio' value='21' style='position:relative;top:5px'/> 21 and over
        <br>
        <input class='age' id='age18' name='age' type='radio' value='18' style='position:relative;top:5px'/> 18 and over
        <br>
        <input class='age' id='age14' name='age'  type='radio' value='14' style='position:relative;top:5px'/> 14-17
        <br>
        <input class='age' id='age13' name='age'  type='radio' value='13' style='position:relative;top:5px'/> Under 14
        <br>
        <input class='age' id='age0' name='age'  type='radio' value='0' style='position:relative;top:5px' checked='checked'/> Unspecified
        <br>
        <br>
        </td>
        </tr>
        
        

        <tr>
        <td class='dataarea' feedphoto>
        <INPUT id="pid" class="pid" TYPE="hidden" >
        <br><br>
        </td>
        </tr>
        
        
        
        </table>
    </div>
        <br>
        <br>
        <br>
    <div id="status" class="status" ></div>
</BODY>
</HTML>

