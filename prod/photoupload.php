<?php
session_start();
require("validsession.inc.php");
ini_set("max_file_uploads","100");
require_once("config-pdo.php");
require("crypt-pdo.inc.php");
$_SESSION[returnurl]="<a href='$rootserver/$startupphp'>Login</a>";
require("password.inc.php");

$braxsocial = "<img src='../img/brax-photo-round-greatlake.png' style='position:relative;top:5px;height:30px;width:auto;padding-left:20px;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

require_once("htmlhead.inc.php");


$providerid = tvalidator("PURIFY",$_SESSION[pid]);
$today = date("M-d-y",time()+$_SESSION[timezone]*60*60);



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

        $(document).on('click','.cancelupload', function(){
                $('.preupload').show();
                $('.postupload').hide();
                $('#sendmessagebutton').hide();
                $('.cancelupload').hide();
                $('.photoselectarea').show();
            
        });
            

        $(document).on('click','#sendmessagebutton', function(){
            
                if($('#subject').val()==="")
                {
                    alertify.alert("Please enter a Title for the Photo");
                    return;
                }
                if($('#fileupload').val()==="" && $('#fileupload2').val()==="")
                {
                    alertify.alert("Please select files to upload");
                    return;
                }
                $('.preupload').hide();
                $('.postupload').show();
                
                $('#uploadphoto').submit();
 
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

        
        $('#fileupload').change(function(){
            
            var valid_extensions = /(.jpg|.jpeg|.gif|.png|.tiff|.tif)$/i;   
            var filelist='';
            for (var i = 0; i < this.files.length; i++)
            {
                if(!valid_extensions.test( this.files[i].name ))
                {
                    alertify.alert( this.files[i].name + " is an invalid file for uploading");
                    files.files = '';
                    break;
                };
                filelist += this.files[i].name+', ';
            }
            if( this.files.length > 0)
            {
                $('#uploadfiletext').text(filelist);
                $('#sendmessagebutton').show();
                $('.cancelupload').show();
                $('.photoselectarea').hide();
            }
        
            
        });
     
        $('#fileupload2').change(function(){
            
            var valid_extensions = /(.jpg|.jpeg|.gif|.png|.tiff|.tif)$/i;   
            var filelist='';
            for (var i = 0; i < this.files.length; i++)
            {
                if(!valid_extensions.test( this.files[i].name ))
                {
                    alertify.alert( this.files[i].name + " is an invalid file for uploading");
                    files.files = '';
                    break;
                };
                filelist += this.files[i].name+', ';
            }
            $('#uploadfiletext').text(filelist);
        
                if( $('.fileupload2').val()==="")
                {
                    alertify.alert("No photo taken");
                    return;
                }
                $('.preupload').hide();
                $('.postupload').show();
                
                $('#uploadphoto').submit();
        
            
        });
     
 
        
 
        
        $('.fileupload').click( function() 
        {
        });


        $(".uploadicon").click(function () {
            $("#fileupload").trigger('click');
            $('#uploadfiletext').show();
        });        
        
       
        $('.postupload').hide();
       
       
    });
</script>
<title>Upload Photos</title>
</head>
<body>
    <span class='postupload' style='display:none'>
        <span style='font-size:20px;color:gray;font-family:helvetica'>
            <img src="../img/loading.gif" style="height:50px" />
        </span>
    </span>
    <span class='preupload'>
        <FORM id="uploadphoto" ACTION="<?=$rootserver?>/<?=$installfolder?>/photouploadproc.php"  enctype="multipart/form-data" METHOD="POST" style='padding-top:0;margin-top:0;display:inline'>


               <?=$braxsocial?> &nbsp;&nbsp; <span class="pagetitle">Upload Photo</span>
               <br><br>
        <table style='
               background-color:white;padding:20px;
               margin:auto;font-size:13px;width:100%'>
            <tr>
            <td class="dataarea">
            <div class="label"></div>
            <br>
            <center>
            <span class='pagetitle2'>
                Select Photo to  Upload
            </span>
            <span class='photoselectarea'>
                <br><br>
                <img class='uploadicon tooltip' src='../img/arrowhead-circle-right-128.png' 
                     title='Select photo from your device photo library'
                     style='display:inline;height:30px;cursor:pointer;opacity:0.7' />
                <br><br>
                Find Existing Photo
                <br><br>
                <br>
            </span>
            <br><div id='uploadfiletext'><img src='../img/loading.gif' style='display:none;height:20px'/></div>
            <input type="hidden" name="MAX_FILE_SIZE" value="50000000">
            <input type='hidden' name='album' value='Upload-<?=$today?>' />
            <input type='hidden' name='subject' value='Photo' />
            <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" value="<?=$_SESSION[pid]?>">        
            <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="loginid" value="<?=$_SESSION[loginid]?>"  >
            <INPUT id="password" TYPE="hidden" NAME="password"  >
             <br>
             </center>
             <br>
             <br>

            </td>
            </tr>


            <tr>
            <td class="dataarea">
            </td>
            </tr>


            <tr>
            <td class="dataarea">
            </td>
            </tr>


            <tr>
                <td class="dataarea">
                <center>
                    <div class="divbutton3 divbutton3_unsel sendmessagebutton" style='display:none' id="sendmessagebutton"><b>Start Upload of Photo</b></div>
                    <div class='formobile'><br></div>
                    <span class='nonmobile'>&nbsp;&nbsp;</span>
                    <div class="divbutton3 divbutton3_unsel cancelupload" style='display:none' ><b>Cancel Upload</b></div>
                </center>
                </td>
            </tr>


        </table>
                <input id='fileupload' class='fileupload' type='file' name='file[]'   accept="image/*" style='width:0px;height:0px'>        
        </FORM>
    </span>

<br>
<br>

</body></html>


<?php
/*
            <span class='nonmobile'>
                <center>
                <b>Album Name</b><br>
                <select name="album" width='40'>

                <option value='Upload-<?=$today?>' selected='selected'>Upload-<?=$today?></option>";
                <?php
                $result = pdo_query("1","
                    select distinct album from photolib where providerid = $providerid 
                        and album!='' and album not like '* Public%' and album!='All' order by album asc
                    ");
                while( $row = pdo_fetch($result))
                {
                    echo "
                <option value='$row[album]'>$row[album]</option>        
                        ";
                }
                ?>


                </select>
                <br>
                or use New Album Name<br>
                <input name='newalbum' type='text' size='40'  />
                <br>
                <br>
                </center>
            </span>
 * 
 * 
            <span class='nonmobile'>
                <center>
                <div class="label">Photo Title:</div>
                <b>Title of Photo(s)<b><br>
                <input title='Subject' id="subject" type='text' class="subject" NAME="subject" size='40' maxlength="255" value="Photos" autocapitalize="off" autocorrect="off" autocomplete="off"  />
                 <br>
                 <br>
                 </center>
             </span>
 * 
 */
?>