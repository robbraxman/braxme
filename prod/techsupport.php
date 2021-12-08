<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");


require_once("htmlhead2.inc.php");

?>
<script>
   $(document).ready( function() {
       
       
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.replace('message');
        

        //$( '#message' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.


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
<BODY class="" style="">
  <FORM id="SendMsg" ACTION="techsupportsend.php"  enctype="multipart/form-data" METHOD="POST">
    <div class='pagetitle'>
        Tech Support Inquiry
    </div>
   <b><span id="providername"></span></b>&nbsp;&nbsp;&nbsp;&nbsp;    
   <table id="" class="">
        <tr>
        <td class="label"></td>
        <td class="dataarea">
            <div class="divbutton divbutton_unsel sendmessagebutton" id="sendmessagebutton"><b>Send Secure Message</b></div>
            <br>
                
        </tr><tr style='display:none'>
	<p>
        <td class="label"></td>
        <td class="dataarea">
        <div class="label">Your Name:</div>
        <INPUT id="name" class='dataentry' TYPE="name" NAME="recipientname" SIZE="40" value='<?=$_SESSION['providername']?>'>
        </td>
        </p>
            
        
        </tr><tr style='display:none'>
	<p>
        <td class="label"></td>
        <td class="dataarea">
        <div class="label">Your Email Address:</div>
        <INPUT id="email" class='dataentry' TYPE="email" NAME="recipientemail" SIZE="40" value='<?=$_SESSION['replyemail']?>'>
        </td>
        </p>
        
        
        </tr><tr>
	<p>
        <td class="label"></td>
        <td class="dataarea">
        <div class=pagetitle3>Tech Support Question</div>    
        <textarea id="message" class="msg mobilewidth" NAME="message" cols="40" rows="10" maxlength="10240000"></textarea>
        </td>

        </tr><tr>

        
   </table>
    </FORM>
    

<?php   
require("htmlfoot.inc");
?>   
