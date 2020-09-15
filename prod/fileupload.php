<?php
session_start();
require("validsession.inc.php");
ini_set("max_file_uploads","100");
require_once("config-pdo.php");
require("crypt-pdo.inc.php");
$_SESSION['returnurl']="<a href='$rootserver/$installfolder/login.php'>Login</a>";
require("password.inc.php");


require_once("htmlhead2.inc.php");


$providerid = @tvalidator("PURIFY",$_SESSION['pid']);
$today = date("m-d",time()+$_SESSION['timezone']*60*60);
$otherid = @tvalidator("PURIFY",$_POST['otherid']);
$chatid = @tvalidator("PURIFY",$_POST['chatid']);
$passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);

?>
<script>
   $(document).ready( function() {
       
       
       var xhr = null;
       
  
        
        //$( '#message' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
         $('body').on("mouseenter", ".stdlistrow", function(){
            $(this).removeClass('unsel').addClass('sel');
            
        });
        $('body').on("mouseleave", ".stdlistrow", function(){
            $(this).removeClass('sel').addClass('unsel');
            
        });



        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });

       $('body').on("mouseenter", ".divbutton3", function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
            
        });
        $('body').on("mouseleave", ".divbutton3", function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
            
        });


        $(document).on('click','#sendmessagebutton', function(){
            
                if($('#subject').val()=="")
                {
                    //alertify.alert("Please provide a public title for the file to upload");
                    //return;
                    $('#subject').val('Upload');
                }
                if($('.fileupload').val()=="")
                {
                    alertify.alert("Please select files to upload");
                    return;
                }
                
                $('#uploadfile').submit();
 
        });

        
 
        $('td.label').show();
        $('td.labelrequired').show();
        $('div.label').hide();
        $('div.labelrequired').hide();
 

        $('#info1').click( function() 
        {

        });
        $('#info2').click( function() 
        {

        });

        
        $('.fileupload').change(function(){
            var valid_extensions = /(.pdf|.txt|.doc|.docx|.xls|.xlsx|.ppt|.pptx|.mp3|.mp4|.zip|.tar|.rtf|.csv|.pps|.xml|.wav|.m4a|.wma|.aif|.jpg|.png|.tif|.tiff|.gif|.bmp|.html|.htm|.apk|.m4p|.mov|.pages|.qt|.au|.gz|.keynote|.numbers|.epub)$/i;   
            var filelist='';
            for (var i = 0; i < this.files.length; i++)
            {
                if(!valid_extensions.test( this.files[i].name ))
                {
                    alertify.alert( this.files[i].name + " is an invalid file for uploading");
                    files.files = '';
                    break;
                };
                filelist += this.files[i].name+' ';
            }
            $('#uploadfiletext').text(filelist);
        
            
        });
     
     
 
        
 
        
        $('.fileupload').click( function() 
        {
        });
        
        $("#uploadicon").click(function () {
            $("#fileupload").trigger('click');
        });        
       
       
        $('input[type=text][title],input[type=password][title],input[type=email][title],textarea[title]').each(function(i){
            if( externallyCalled == true)
                return;
            $(this).addClass('input-prompt-' + i);
            var promptSpan = $('<span class="input-prompt"/>');
            $(promptSpan).attr('id', 'input-prompt-' + i);
            $(promptSpan).append($(this).attr('title'));
            $(promptSpan).click(function(){
                $(this).hide();
                $('.' + $(this).attr('id')).focus();
            });
            if($(this).val() != ''){
              $(promptSpan).hide();
            }
            $(this).before(promptSpan);
            $(this).focus(function(){
                  $('#input-prompt-' + i).hide();
            });
            $(this).blur(function(){
                if($(this).val() == ''){
                  $('#input-prompt-' + i).show();
                }
            });
         });       
         $('#sendshow').on('click', function(){
             if( $('.sendarea').is(':visible'))
             {
                 $('.sendarea').hide();
             }
             else
             {
                 $('.sendarea').show();
                 $('.sendshow').hide();
                 $('#sendshow').prop('checked',false);
             }
             
         });
        $('.sendarea').hide();
         
<?php
$subtitle = '';
if($otherid!='')
{
    
        echo "$('.sendarea').hide();";
        echo "$('.sendshow').hide();";
        $subtitle = "<div class='gridstdborder' style='background-color:gold;padding:20px'><b>Copy will be sent to chat party:</b><br>$otherid</div><br><br>";
}
?>       
       
    });
</script>
<title>Upload Files</title>
</head>
<BODY class="appbody mainfont" >
   <FORM id="uploadfile" ACTION="<?=$rootserver?>/<?=$installfolder?>/fileuploadprocs3.php"  enctype="multipart/form-data" METHOD="POST" style='padding-top:0;margin-top:0;display:inline'>

    <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" value="<?=$_SESSION['pid']?>">        
    <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="loginid" value="admin"  >
    <INPUT id="password" TYPE="hidden" NAME="password"  >
    <INPUT id="chatid" TYPE="hidden" NAME="chatid" value='<?=$chatid?>'  >
    <INPUT id="passkey64" TYPE="hidden" NAME="passkey64" value='<?=$passkey64?>'  >
    
           
    <table id="newmsgtable" class="newmsgtable messageentrytable" style='padding-top:0;margin-top:0'>
        <tr>
        <td class="label">
        </td>
        <td class="dataarea">
            <img src="../img/brax-doc-round-gold-128.png" style="height:25px;position:relative;top:3px"/>&nbsp;&nbsp; <span class="pagetitle">Upload Files</span>
            <br><br>
        <?=$subtitle?>
        <div class="label"></div>
        <br>
        <label for="fileupload">Select Files
            </label><br>
        <input id='fileupload' class='fileupload' type='file' name='file[]' accept='*' multiple="multiple" size="30" style='width:250px;cursor:pointer'>        
        <input type="hidden" name="MAX_FILE_SIZE" value="5048000000">
        <br>
         <br>

        </td>
        </tr>

        <tr>
            <td class="label"></td>
            <td class="dataarea">
                <input id='subject' name='subject' type='text' size='30' placeholder='Document Name'  style='width:250px'/>
            </td>
        </tr>
        
        
        <tr class='sendshow'>
            <td class="label"></td>
            <td class="dataarea">
                Send file to another user?
                <input id='sendshow'  type='checkbox' style="position:relative;top:5px"  />
            </td>
        </tr>
        
        <tr class='sendarea'>
            <td class="label"></td>
            <td class="dataarea">
                Upload File Copy To<br><span class='smalltext'>(Specify Email or @handle of Brax.Me Account)</span><br>
                <input id='sendemail' name='sendemail' type='email' size='30' value="<?=$otherid?>"  style='width:250px' />
            </td>
        </tr>
        
        
        <tr>
            <td class="dummy"></td>
            <td class="dataarea">
            <img class='sendmessagebutton' id='sendmessagebutton' 
                 src="../img/arrow-stem-circle-right-128.png" 
                 style='position:relative;top:0px;height:35px;width:auto;cursor:pointer'
                 />
            </td>
        </tr>

        <tr>
            <td class="dummy"></td>
            <td class="dataarea">
                
            </td>
        </tr>
        
   </table>
    </FORM>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
</body></html>
