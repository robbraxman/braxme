<?php
session_start();


require_once("htmlhead.inc.php");

?>
<script>
   $(document).ready( function() {
       


        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });

        $('body').on("mouseenter", ".addressbooksel", function(){
            $(this).removeClass('unsel').addClass('sel');
            
        });
        $('body').on("mouseleave", ".addressbooksel", function(){
            $(this).removeClass('sel').addClass('unsel');
            
        });


        $(document).on('click','.divbutton', function(){
            if( $(this).attr('id')==='sendmessagebutton')
            {
                if( $('#email').val()!='' && $('#name').val()!='')
                {
                    $('#SendMsg').submit();
                }
                else
                {
                    alert('Missing Name and/or Email.');
                }
            }
 
        });

       
    });
</script>
<title>Tech Support</title>
</head>
<BODY class="buycontainer">
    <div class='buysubcontainer'>
   <FORM id="SendMsg" ACTION="forgotaccount.php"  enctype="multipart/form-data" METHOD="POST">
    <div class="banner">
        <b><?=$appname?> - Find My Account</b>&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="<?=$homepage?>" >Home</a>
    </div>
   <table id="newmsgtable" class="newmsgtable">
        <tr>
        <td class="label"></td>
        <td class="dataarea">
            <div class="divbutton divbutton_unsel sendmessagebutton" id="sendmessagebutton"><b>Find My Account</b></div>
            <br><br>
            <p>We can send your original Subscriber ID and Login ID to the original verified email that
                established the account. If you are not the Administrator, please contact the
                administrator for your login info.
        </tr><tr>
	<p>
        <td class="label"></td>
        <td class="dataarea">
        <div class="label">Subscriber Name:</div>
        <INPUT id="name" TYPE="name" NAME="name" SIZE="40">
        </td>
        </p>
            
        
        </tr><tr>
	<p>
        <td class="label"></td>
        <td class="dataarea">
        <div class="label">Administrator Email Address:</div>
        <INPUT id="email" TYPE="email" NAME="email" SIZE="40">
        </td>
        </p>
        
        </tr>
        

        
   </table>
    </FORM>
        </div>
    

<?php   
require("htmlfoot.inc");
?>   
