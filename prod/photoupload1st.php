<?php
session_start();
ini_set("max_file_uploads","100");
require_once("config.php");
require("crypt.inc.php");
$_SESSION[returnurl]="<a href='$rootserver/$installfolder/login.php'>Login</a>";
require("password.inc.php");

$braxsocial = "<img src='../img/braxphoto.png' style='position:relative;top:3px;height:30px;width:auto;padding-left:20px;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

require_once("htmlhead2.inc.php");


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
            $('#uploadfiletext').text(filelist);
            $('#sendmessagebutton').show();
        
            
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
        
        $(".takephotoicon").click(function () {
            $("#fileupload2").trigger('click');
            $('#uploadfiletext').show();
        });        
        
       
        $('.postupload').hide();
       
       
    });
</script>
<title>Upload Photos</title>
</head>
<BODY >
    <span class='postupload'>
        <span style='font-size:20px;color:gray;font-family:helvetica'><img src="../img/loading.gif" style="height:50px" /></span>
    </span>
    <span class='preupload'>
        <FORM id="uploadphoto" ACTION="<?=$rootserver?>/<?=$installfolder?>/photouploadproc.php"  enctype="multipart/form-data" METHOD="POST" style='padding-top:0;margin-top:0;display:inline'>

        <INPUT id="pid" class="pid" TYPE="hidden" NAME="pid" value="<?=$_SESSION[pid]?>">        
        <INPUT id="loginid" class="loginid" TYPE="hidden" NAME="loginid" value="<?=$_SESSION[loginid]?>"  >
        <INPUT id="password" TYPE="hidden" NAME="password"  >

               <?=$braxsocial?> &nbsp;&nbsp; <span class="pagetitle">Upload Photos</span>
               <br><br>
        <table  style='background-color:white;padding:20px  ;margin:auto;font-size:13px'>
            <tr>
            <td class="dataarea">
            <div class="label"></div>
            <br>
            <center>
                <span class='formobile'>
                    <b>Take/Select Photo(s) from Device</b>
                </span>
                <span class='nonmobile'>
                    <b>Select Photo(s)</b>
                </span>
            <br><br>
            <span class='formobile'>
            <img class='takephotoicon tooltip' src='../img/camera-magenta-128.png' 
                 title='Take photos'
                 style='display:inline;height:50px;cursor:pointer' /><br>
            Take Photo
            <br><br>
            </span>
            <img class='uploadicon tooltip' src='../img/photos-magenta-128.png' 
                 title='Select photos from your device photo library'
                 style='display:inline;height:50px;cursor:pointer' /><br>
            Find Existing Photos
            <br>
            <br><div id='uploadfiletext'><img src='../img/loading.gif' style='display:none;height:20px'/></div>
            <input type="hidden" name="MAX_FILE_SIZE" value="20480000">
            <input type='hidden' name='album' value='Upload-<?=$today?>' />
            <input type='hidden' name='subject' value='Photo' />
             <br>
             <i>Multiselect allowed<br>
             50MB Limit per batch</i>
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
                    <div class="divbutton3 divbutton3_unsel sendmessagebutton" style='display:none' id="sendmessagebutton"><b>Start Upload of Photos</b></div>
                </center>
                </td>
            </tr>


        </table>
                <input id='fileupload' class='fileupload' type='file' name='file[]' multiple='multiple'  accept="image/*;capture=camera" style='width:0px;height:0px'>        
                <input id='fileupload2' class='fileupload' type='file' name='file[]'  accept="image/*" capture="camera" style='width:0px;height:0px'>        
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
                $result = do_mysqli_query("1","
                    select distinct album from photolib where providerid = $providerid 
                        and album!='' and album not like '* Public%' and album!='All' order by album asc
                    ");
                while( $row = do_mysqli_fetch("1",$result))
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