<?php
session_start();
require_once("validsession.inc.php");
require_once("config.php");

//require("password.inc.php");
require_once("htmlhead.inc.php");

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



        $('body').on("mouseenter", ".divbutton3", function(){
            $(this).removeClass('divbutton3_unsel').addClass('divbutton3_sel');
            
        });
        $('body').on("mouseleave", ".divbutton3", function(){
            $(this).removeClass('divbutton3_sel').addClass('divbutton3_unsel');
            
        });

        $(document).on('click','.textphotodemo', function(){
            //Override Behavior of Textarea
             $.valHooks.textarea = {
                 get: function( elem ) {
                 return elem.value.replace( /\r?\n/g, "<br />" );
                 }                     
             };  
            $('.textbody2').val(
                    "I disagree with your opinion\r\n"+
                    "but I will explain in a post\r\n"+
                    "not visible to Facebook and Google\r\n\r\n(Click for more...)"
                    );
            $('.commentbody').val(
                "This is your real opinion. This will be visible only to those who click "+
                "the link to your social post. All you have to do is share the saved photo "+
                "to Facebook or other social media."+
                "\r\n\r\nClick on Preview to see what it looks like. Change the colors. "+
                "When you are done, save the image. It will be in My Photos, ready for sharing."
                );
            
        });
        $(document).on('click','.textphotodemo2', function(){
            //Override Behavior of Textarea
             $.valHooks.textarea = {
                 get: function( elem ) {
                 return elem.value.replace( /\r?\n/g, "<br />" );
                 }                     
             };  
            $('.textbody2').val(
                    "I love Zuck. Well not really but"+
                    "\r\nI will explain in a post"+
                    "\r\nnot visible to Facebook and Google\r\n\r\n(Click for more...)"
                    );
            
            $('.commentbody').val(
                "What the Zuck! Zucking Zuckbook is stealing your data and creating a PERMANENT PROFILE on you. "+
                "Not only are they making money off your private content by selling you constantly, "+
                "you are supporting world where information will be controlled by a few. Be scared. "+
                "Be very scared."
                );
            
        });


        $(document).on('click','#createsamplebutton', function(){
            
            //Override Behavior of Textarea
             $.valHooks.textarea = {
                 get: function( elem ) {
                 return elem.value.replace( /\r?\n/g, "<br />" );
                 }                     
             };  
             
             var family = '';
             if($('#fontbold').is(':checked')){
                 family = 'bold';
             }
             if($('#fontlight').is(':checked')){
                 family = 'light';
             }
            
            if($('.textbody2').val()=="")
            {
                alertify.alert("Please enter a Text for the Photo");
                return;
            }
            call = "gdtext2imagemake.php?t="+$('.textbody2').val();
            call += "&f="+$('.f').val();
            call += "&b="+$('.b').val();
            call += "&c="+$('.c').val();
            call += "&w="+$('.w').val();
            call += "&family="+family;
            $('.textpic').prop('src', call );
            $('.previewcomment').val($('.commentbody').val())
        });
        $(document).on('click','#savetextbutton', function(){
            
            //Override Behavior of Textarea
             $.valHooks.textarea = {
                 get: function( elem ) {
                 return elem.value.replace( /\r?\n/g, "<br />" );
                 }                     
             };  
            
             var family = '';
             if($('#fontbold').is(':checked')){
                 family = 'bold';
             }
             if($('#fontlight').is(':checked')){
                 family = 'light';
             }
            
            if($('.textbody2').val()==="")
            {
                alertify.alert("Please enter a Text for the Photo");
                return;
            }
            call = "gdtext2imagemake.php?save=Y&t="+$('.textbody2').val();
            call += "&f="+$('.f').val();
            call += "&b="+$('.b').val();
            call += "&c="+$('.c').val();
            call += "&w="+$('.w').val();
            call += "&family="+family;
            call += "&comment="+$('.commentbody').val();
            $('.textpic').prop('src', call );
            alertify.alert("Text Image was saved in My Photos - TextPics Album.");
            //top.PanelShow(4);
            
        });
        $(document).on('click','.backgroundcolor', function(){
            $('.b').val($(this).data('color'));
            $('.backgroundsample').css("background-color", "#"+$('.b').val());
            
            //Override Behavior of Textarea
             $.valHooks.textarea = {
                 get: function( elem ) {
                 return elem.value.replace( /\r?\n/g, "<br />" );
                 }                     
             };  
             var family = '';
             if($('#fontbold').is(':checked')){
                 family = 'bold';
             }
             if($('#fontlight').is(':checked')){
                 family = 'light';
             }
            
            if($('.textbody2').val()=="")
            {
                alertify.alert("Please enter a Text for the Photo");
                return;
            }
            call = "gdtext2imagemake.php?t="+$('.textbody2').val();
            call += "&f="+$('.f').val();
            call += "&b="+$('.b').val();
            call += "&c="+$('.c').val();
            call += "&w="+$('.w').val();
            call += "&family="+family;
            $('.textpic').prop('src', call );
            
        });
        $(document).on('click','.foregroundcolor', function(){
            $('.c').val($(this).data('color'));
            $('.foregroundsample').css("background-color", "#"+$('.c').val());
            
            //Override Behavior of Textarea
             $.valHooks.textarea = {
                 get: function( elem ) {
                 return elem.value.replace( /\r?\n/g, "<br />" );
                 }                     
             };  
             var family = '';
             if($('#fontbold').is(':checked')){
                 family = 'bold';
             }
             if($('#fontlight').is(':checked')){
                 family = 'light';
             }
            
            if($('.textbody2').val()=="")
            {
                alertify.alert("Please enter a Text for the Photo");
                return;
            }
            call = "gdtext2imagemake.php?t="+$('.textbody2').val();
            call += "&f="+$('.f').val();
            call += "&b="+$('.b').val();
            call += "&c="+$('.c').val();
            call += "&w="+$('.w').val();
            call += "&family="+family;
            $('.textpic').prop('src', call );
            
        });

        
 
  
       
       
       
    });
</script>
<title>Text2Photo</title>
</head>
<BODY class="" style='width:100%;padding:0;margin:0;background-color:<?=$global_background?>;color:<?=$global_textcolor?>' >
    
    <div class="pagetitle2a" style='text-align:center;margin:auto;color:<?=$global_textcolor?>'>
        <b>Convert Text to Image</b>
    </div>
    
        <table  class="gridnoborder mainfont" style='padding-top:0;margin-top:0;margin:auto;max-width:90%;color:<?=$global_textcolor?>'>
        <tr class='mainfont' style='margin:0;padding:0'>
            <td>    
               Convert Text to Image<br>
                <textarea class='textbody2 pagetitle2'  rows='5' 
                          style=padding:10px;width:100%;max-width:500px' 
                          ></textarea>
                <br><br>
                <!--
                Hidden Comment<br>
                <textarea class='commentbody mainfont' name='comment'  rows='10' 
                          style=padding:10px;width:100%;max-width:500px' 
                          ></textarea>
                <br><br>
                -->
                <input id='fontscript' type="radio" checked=checked name="font" value='' style='position:relative;top:5px'> Script&nbsp;&nbsp;&nbsp;
                <input id='fontbold' type="radio" name="font" value='bold' style='position:relative;top:5px'> Bold&nbsp;&nbsp;&nbsp;
                <input id='fontlight' type="radio" name="font" value='light' style='position:relative;top:5px'> Light
                <br><br><br>
                <div class="divbutton3 divbutton3_unsel createsamplebutton" id="createsamplebutton">
                    Preview Image</div>
                <br><br>Preview of Public Image<br>
                <img class='textpic' style='width:500px;max-width:100%;height:auto' src='../img/textsample.png' />
                <br><br>
                <!--
                Preview  of Hidden Comment<br>
                <textarea class='previewcomment smalltext2' readonly='readonly' style='width:100%'></textarea>
                <br><br>
                -->

                <b>Background Color</b>
                <br>
                <input class='b' name='b' type='text' maxlength='6' size='6' value='FFFFFF' style="display:none" />
                <div class="backgroundsample gridstdborder" style="width:100%;max-width:500px;height:20px;background-color:white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <table class="gridstdborder" style="border-collapse:collapse">
                    <tr>
                        <td class="backgroundcolor" data-color="E6E6FA" style="color:transparent;cursor:pointer;background-color:lavender">Color</td>
                        <td class="backgroundcolor" data-color="FFA07A" style="color:transparent;cursor:pointer;background-color:lightsalmon">Color</td>
                        <td class="backgroundcolor" data-color="FFC0CB" style="color:transparent;cursor:pointer;background-color:pink">Color</td>
                        <td class="backgroundcolor" data-color="FFD700" style="color:transparent;cursor:pointer;background-color:gold">Color</td>
                        <td class="backgroundcolor" data-color="66CDAA" style="color:transparent;cursor:pointer;background-color:mediumaquamarine">Color</td>
                        <td class="backgroundcolor" data-color="E0FFFF" style="color:transparent;cursor:pointer;background-color:lightcyan">Color</td>
                        <td class="backgroundcolor" data-color="B0C4DE" style="color:transparent;cursor:pointer;background-color:lightsteelblue">Color</td>
                    </tr>
                    <tr>
                        <td class="backgroundcolor" data-color="FFFFF0" style="color:transparent;cursor:pointer;background-color:ivory">Color</td>
                        <td class="backgroundcolor" data-color="F0F8FF" style="color:transparent;cursor:pointer;background-color:aliceblue">Color</td>
                        <td class="backgroundcolor" data-color="B22222" style="color:transparent;cursor:pointer;background-color:firebrick">Color</td>
                        <td class="backgroundcolor" data-color="D3D3D3" style="color:transparent;cursor:pointer;background-color:lightgray">Color</td>
                        <td class="backgroundcolor" data-color="708090" style="color:transparent;cursor:pointer;background-color:slategray">Color</td>
                        <td class="backgroundcolor" data-color="808080" style="color:transparent;cursor:pointer;background-color:gray">Color</td>
                        <td class="backgroundcolor" data-color="2F4F4F" style="color:transparent;cursor:pointer;background-color:darkslategray">Color</td>
                     </tr>
                    <tr>
                        <td class="backgroundcolor" data-color="000000" style="color:transparent;cursor:pointer;background-color:black">Color</td>
                        <td class="backgroundcolor" data-color="FFFF00" style="color:transparent;cursor:pointer;background-color:yellow">Color</td>
                        <td class="backgroundcolor" data-color="8B008B" style="color:transparent;cursor:pointer;background-color:darkmagenta    ">Color</td>
                        <td class="backgroundcolor" data-color="483D8B" style="color:transparent;cursor:pointer;background-color:darkslateblue">Color</td>
                        <td class="backgroundcolor" data-color="2E8B57" style="color:transparent;cursor:pointer;background-color:seagreen">Color</td>
                        <td class="backgroundcolor" data-color="1E90FF" style="color:transparent;cursor:pointer;background-color:dodgerblue">Color</td>
                        <td class="backgroundcolor" data-color="191970" style="color:transparent;cursor:pointer;background-color:midnightblue">Color</td>
                    </tr>
                    <tr>
                        <td class="backgroundcolor" data-color="87CEFA" style="color:transparent;cursor:pointer;background-color:lightskyblue">Color</td>
                        <td class="backgroundcolor" data-color="FFF8DC" style="color:transparent;cursor:pointer;background-color:cornsilk">Color</td>
                        <td class="backgroundcolor" data-color="C71585" style="color:transparent;cursor:pointer;background-color:mediumvioletred">Color</td>
                        <td class="backgroundcolor" data-color="FF8C00" style="color:transparent;cursor:pointer;background-color:darkorange">Color</td>
                        <td class="backgroundcolor" data-color="8B4513" style="color:transparent;cursor:pointer;background-color:saddlebrown">Color</td>
                        <td class="backgroundcolor" data-color="800000" style="color:transparent;cursor:pointer;background-color:maroon">Color</td>
                        <td class="backgroundcolor" data-color="000000" style="color:transparent;cursor:pointer;background-color:black">Color</td>
                      <br>
                    </tr>
                </table>
                    <br>
                <b>Text Color</b>
                <br>
                <input class='c' name='c' type='text' maxlength='6' size='6' value='000000' style="display:none" />
                <div class="foregroundsample gridstdborder" style="width:100%;max-width:500px;height:20px;background-color:black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <br>
                <table class="gridstdborder" style="border-collapse:collapse">
                    <tr>
                        <td class="foregroundcolor" data-color="E6E6FA" style="color:transparent;cursor:pointer;background-color:lavender">Color</td>
                        <td class="foregroundcolor" data-color="FFA07A" style="color:transparent;cursor:pointer;background-color:lightsalmon">Color</td>
                        <td class="foregroundcolor" data-color="FFC0CB" style="color:transparent;cursor:pointer;background-color:pink">Color</td>
                        <td class="foregroundcolor" data-color="FFD700" style="color:transparent;cursor:pointer;background-color:gold">Color</td>
                        <td class="foregroundcolor" data-color="66CDAA" style="color:transparent;cursor:pointer;background-color:mediumaquamarine">Color</td>
                        <td class="foregroundcolor" data-color="E0FFFF" style="color:transparent;cursor:pointer;background-color:lightcyan">Color</td>
                        <td class="foregroundcolor" data-color="B0C4DE" style="color:transparent;cursor:pointer;background-color:lightsteelblue">Color</td>
                    </tr>
                    <tr>
                        <td class="foregroundcolor" data-color="FFFFF0" style="color:transparent;cursor:pointer;background-color:ivory">Color</td>
                        <td class="foregroundcolor" data-color="F0F8FF" style="color:transparent;cursor:pointer;background-color:aliceblue">Color</td>
                        <td class="foregroundcolor" data-color="B22222" style="color:transparent;cursor:pointer;background-color:firebrick">Color</td>
                        <td class="foregroundcolor" data-color="D3D3D3" style="color:transparent;cursor:pointer;background-color:lightgray">Color</td>
                        <td class="foregroundcolor" data-color="708090" style="color:transparent;cursor:pointer;background-color:slategray">Color</td>
                        <td class="foregroundcolor" data-color="808080" style="color:transparent;cursor:pointer;background-color:gray">Color</td>
                        <td class="foregroundcolor" data-color="2F4F4F" style="color:transparent;cursor:pointer;background-color:darkslategray">Color</td>
                     </tr>
                    <tr>
                        <td class="foregroundcolor" data-color="000000" style="color:transparent;cursor:pointer;background-color:black">Color</td>
                        <td class="foregroundcolor" data-color="FFFF00" style="color:transparent;cursor:pointer;background-color:yellow">Color</td>
                        <td class="foregroundcolor" data-color="8B008B" style="color:transparent;cursor:pointer;background-color:darkmagenta    ">Color</td>
                        <td class="foregroundcolor" data-color="483D8B" style="color:transparent;cursor:pointer;background-color:darkslateblue">Color</td>
                        <td class="foregroundcolor" data-color="2E8B57" style="color:transparent;cursor:pointer;background-color:seagreen">Color</td>
                        <td class="foregroundcolor" data-color="1E90FF" style="color:transparent;cursor:pointer;background-color:dodgerblue">Color</td>
                        <td class="foregroundcolor" data-color="191970" style="color:transparent;cursor:pointer;background-color:midnightblue">Color</td>
                    </tr>
                    <tr>
                        <td class="foregroundcolor" data-color="87CEFA" style="color:transparent;cursor:pointer;background-color:lightskyblue">Color</td>
                        <td class="foregroundcolor" data-color="FFF8DC" style="color:transparent;cursor:pointer;background-color:cornsilk">Color</td>
                        <td class="foregroundcolor" data-color="C71585" style="color:transparent;cursor:pointer;background-color:mediumvioletred">Color</td>
                        <td class="foregroundcolor" data-color="FF8C00" style="color:transparent;cursor:pointer;background-color:darkorange">Color</td>
                        <td class="foregroundcolor" data-color="8B4513" style="color:transparent;cursor:pointer;background-color:saddlebrown">Color</td>
                        <td class="foregroundcolor" data-color="800000" style="color:transparent;cursor:pointer;background-color:maroon">Color</td>
                         <td class="foregroundcolor" data-color="000000" style="color:transparent;cursor:pointer;background-color:black">Color</td>
                   </tr>
                </table>
                <br><br><br>
                <div class="divbutton3 divbutton3_unsel savetextbutton" id="savetextbutton">
                    
                    Save Image to My Photos</div>
                    <br><br>

                <td class="dataarea">
                <input class    ='f' name='f' type='hidden' maxlength='3' size='6' value='20' />
                </td>
                <td class="dataarea">
                <input class='w' name='w' type='hidden' maxlength='2' size='6' value='30' />
                </td>
            </tr>
            </table>
        </tr>
        
   </table>
<br>
<br>
<br>
<br>
<br>
</body></html>
