<?php
session_start();
ini_set("max_file_uploads","100");
require_once("validsession.inc.php");
require_once("config.php");
require("crypt.inc.php");
$_SESSION['returnurl']="<a href='$rootserver/$installfolder/login.php'>Login</a>";
require("password.inc.php");


require_once("htmlhead2.inc.php");


$providerid = @mysql_safe_string($_SESSION['pid']);

$selectroom = "<select class='grouptextroom' id='textroomid' name='roomid'  style='width:250px'>";
$result = do_mysqli_query("1","
        select distinct room, roomid, 
        (select count(*) from statusroom s2 where s2.roomid = statusroom.roomid ) as count
        from statusroom 
        where 
        (   
            owner = $_SESSION[pid] 
            or roomid in (select roomid from roommoderator where providerid = $_SESSION[pid] ) 
        )
        order by room
        ");
while($row = do_mysqli_fetch("1",$result))
{
    $roomname = htmlentities($row['room']);
    $selectroom .= "<option value='$row[roomid]'>$roomname ($row[count])</option>";
}
$selectroom .= "</select>";


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
            var valid_extensions = /(.pdf|.txt|.doc|.docx|.xls|.xlsx|.ppt|.pptx|.mp3|.mp4|.zip|.tar|.rtf|.csv|.pps|.xml|.wav|.m4a|.wma|.aif|.jpg|.png|.tif|.tiff|.gif|.bmp|.html|.htm|.apk|.m4p|.mov|.pages|.qt|.au|.gz|.keynote|.numbers)$/i;   
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
         
       
    });
</script>
<title>Upload CSV File</title>
</head>
<BODY class="appbody mainfont" >
   <FORM id="uploadfile" ACTION="<?=$rootserver?>/<?=$installfolder?>/csvuploadproc.php"  enctype="multipart/form-data" METHOD="POST" style='padding-top:0;margin-top:0;display:inline'>

    <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" value="<?=$_SESSION['pid']?>">        
    <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="loginid" value="admin"  >
    <INPUT id="password" TYPE="hidden" NAME="password"  >
    <INPUT id="chatid" TYPE="hidden" NAME="chatid" value='<?=$chatid?>'  >
    
           <img src="../img/braxfile-square.png" style="height:25px;position:relative;top:3px"/>&nbsp;&nbsp; <span class="pagetitle">Upload CSV File to Room</span>
           
    <table id="newmsgtable" class="newmsgtable messageentrytable" style='padding-top:0;margin-top:0'>
        <tr>
        <td class="label">
        </td>
        <td class="dataarea">
        <div class="label"></div>
        <br>
        <label for="fileupload">Select CSV File
            </label><br>
        <input id='fileupload' class='fileupload' type='file' name='file[]' accept='.txt,.csv'  size="30" style='width:250px'>        
        <input type="hidden" name="MAX_FILE_SIZE" value="50480000">
        <br>

        </td>
        </tr>

        <tr>
            <td class="dummy"></td>
            <td class="dataarea">
                Room to Assign to Members List
                <br>
                <?=$selectroom?>
            </td>
        </tr>
        <tr>
            <td class="dummy"></td>
            <td class="dataarea">
                Community Code
                <br>
                    <input type="text" name="sponsor" value="" size='10'>
            </td>
        </tr>

        
        
        <tr>
            <td class="dummy"></td>
            <td class="dataarea">
                <br><br>
            <div class="divbutton3 divbutton3_unsel sendmessagebutton" id="sendmessagebutton">Start Upload of CSV Member List</div>
            </td>
        </tr>

        
        <tr>
            <td class="dummy"></td>
            <td class="dataarea" style='max-width:600px'>
                <br>
                <b>Instructions</b>
        <br><br>
        A CSV file is just a normal text file where each entry (email, sms, name) is separated by a carriage return. Each 
        line needs to contain data formated EXACTLY like this (exceptions noted below).<br><br>
        sample@email.com,310-555-1212,John Doe<br>
        sample2@email.com,310-555-1213,Jane Doe<br>
        <br>
        or
        <br>
        <br>
        "sample@email.com","310-555-1212","John Doe"<br>
        "sample2@email.com","310-555-1213","Jane Doe"<br>
        <br>
        The email address must be a valid email format. The phone number can include or exclude punctuation,
        i.e, these are all acceptable (310) 555-1212, 3105551212, 310/555-1212.<br><br>
        The name must be text only with no punctuation. Each value is separated by a comma. This is a common 
        format and is exported by apps such as Microsoft Excel. Or you can hand type it on a text editor. You may use quotes ("") to separate values.
        <br><br>
        The email address and name are required. If you do not include the mobile number, an email message 
        will be sent instead.
        <br><br>
        When you import a CSV file, you will be asked to select a room to import the members into. This is 
        why rooms need to be set up in advance before you do this. You can reimport a CSV file without consequence 
        since we remove duplicates. You can also reimport members to different rooms. We will automatically exclude 
        items that do not fit the minimum criteria.
        
        
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
