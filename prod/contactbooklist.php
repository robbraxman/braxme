<?php
session_start();
require_once("validsession.inc.php");
require_once("config.php");

require_once("password.inc.php");
require_once("htmlhead.inc.php");
?>
<script>
   $(document).ready( function() {
        
        $('td.label').show();
        $('td.labelrequired').show();
        $('div.label').hide();
        $('div.labelrequired').hide();

 
         $('body').on("mouseenter", ".addressbooksel", function(){
            $(this).removeClass('unsel').addClass('sel');
            
        });
        $('body').on("mouseleave", ".addressbooksel", function(){
            $(this).removeClass('sel').addClass('unsel');
            
        });


        $('body').on("mouseenter", ".divbutton", function(){
            $(this).removeClass('divbutton_unsel').addClass('divbutton_sel');
            
        });
        $('body').on("mouseleave", ".divbutton", function(){
            $(this).removeClass('divbutton_sel').addClass('divbutton_unsel');
            
        });

        $('body').on("mouseenter", ".divbuttontext", function(){
            $(this).removeClass('divbuttontext_unsel').addClass('divbuttontext_sel');
            
        });
        $('body').on("mouseleave", ".divbuttontext", function(){
            $(this).removeClass('divbuttontext_sel').addClass('divbuttontext_unsel');
            
        });

        
        //$( '#message' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
         $('body').on("mouseenter", ".stdlistrow", function(){
            $(this).removeClass('unsel').addClass('sel');
            
        });
        $('body').on("mouseleave", ".stdlistrow", function(){
            $(this).removeClass('sel').addClass('unsel');
            
        });


                
        $('.start').click( function() 
        {
            $('#page').val("1");
            RefreshAddressBook();
        });
        
        $('.prev').click( function() 
        {
            //alert( parseInt( $('#page').val(),10) );
            if( parseInt( $('#page').val(), 10 )> 1)
            {
                CurPage = parseInt($('#page').val(),10)-1;
                $('#page').val(CurPage );
            }
            else
            {
                $('#page').val("1");
            }
            RefreshAddressBook();
        });
        
        $('.next').click( function() 
        {
            CurPage = parseInt( $('#page').val(),10 )+1;
            $('#page').val(CurPage);
            RefreshAddressBook();
            
        });
        
        $("form").submit(function() {
            if( $('#recipientname').val().length < 4 )
            {
                alert("Invalid contact name.");
                return false;
            }
           
        });

        $('#clear').click( function() 
        {
            $('#searchfilter').val( "");
        });
        
        $('#save').click( function() 
        {
            $('#status').load( "contactbooksave.php", 
            { 
                'mode': 'S', 
                'providerid': $('#pid').val(), 
                'recipientname': $('#recipientname').val(), 
                'recipientemail': $('#email').val(),
                'sms': $('#sms').val(),
                'handle': $('#handle').val()
            }, function() {
                $('#recipientname').val("");
                $('#email').val("");
                $('#sms').val("");
                $('#handle').val("");
                RefreshAddressBook();
                
            });
        });

        $('#delete').click( function() 
        {
            
            $('#status').load( "contactbooksave.php", 
            { 
                'mode': 'D', 
                'providerid': $('#pid').val(), 
                'recipientname': $('#recipientname').val(), 
                'recipientemail': $('#email').val(),
                'handle': $('#handle')
            }, function(){
                $('#recipientname').val("");
                $('#email').val("");
                $('#sms').val("");
                $('#handle').val("");
                RefreshAddressBook();
                
            });
                     
        });
        $('body').on('click','.deleterowbutton', function() 
        {
            
            $('#status').load( "contactbooksave.php", 
            { 
                'mode': 'D', 
                'providerid': $('#pid').val(), 
                'recipientemail': $(this).data('email'), 
                'recipientname': $(this).data('name')
            }, function(){
                $('#recipientname').val("");
                $('#email').val("");
                $('#sms').val("");
                $('#handle').val("");
                RefreshAddressBook();
                
            });
                     
        });
        $('body').on('click','.blockbutton', function() 
        {
            
            $('#status').load( "contactbooksave.php", 
            { 
                'mode': 'B', 
                'providerid': $('#pid').val(), 
                'recipientemail': $(this).data('email'), 
                'recipientname': $(this).data('name'),
                'handle': $(this).data('handle')
            }, function(){
                $('#recipientname').val("");
                $('#email').val("");
                $('#sms').val("");
                $('#handle').val("");
                RefreshAddressBook();
                
            });
                     
        });
        $('body').on('click','.unblockbutton', function() 
        {
            
            $('#status').load( "contactbooksave.php", 
            { 
                'mode': 'U', 
                'providerid': $('#pid').val(), 
                'recipientemail': $(this).data('email'), 
                'recipientname': $(this).data('name'),
                'handle': $(this).data('handle')
            }, function(){
                $('#recipientname').val("");
                $('#email').val("");
                $('#sms').val("");
                $('#handle').val("");
                RefreshAddressBook();
                
            });
                     
        });
       
        
        function RefreshAddressBook()
        {
            $('#addressbookhide').attr("checked", false );
            nonpatient = '';
            if( $('#nonpatient').is(":checked"))
            {
                nonpatient = 'P';
            }
            $('.prev').show();
            $('.next').show();

            $('#addressbookcontent').load( "contactbook.php", 
                 { 'providerid': $('#pid').val(), 
                   'searchfilter': $('#searchfilter').val(), 
                   'source': '1', 
                   'page' : $('#page').val()  },
                 function(data, status ) 
                 {
                    if( status==="success")
                    {
                        $('.addressbookcontent').show();
                        $('.contacteditarea').hide();
                        $('td.addressbookrow').on('click', function() {
                            $('#recipientname').val( $(this).parent().find('.addressbook1').text());
                            $('#email').val( $(this).parent().find('.addressbook3').text());
                            $('#sms').val( $(this).parent().find('.addressbook4').text());
                            $('#handle').val( $(this).parent().find('.addressbook6').text());
                            $('.contacteditarea').show();
                            $('.addressbookcontent').hide();
                            $('.prev').hide();
                            $('.next').hide();
                        });                  
                        
                    }
                    else
                    {
                        alertify.set({ delay: 2000 });
                        alertify.log(data);
                    }
                 }
                 
            );
            
        }

        $('#getaddressbook').click( function() 
        {
            RefreshAddressBook();
            $('#status').html("");
        });
        
         $('#searchfilter').keyup(function(e){
             
            var code = e.keyCode || e.which;
            if(code < 32 || code > 122) { //Enter keycode
              return;
            }            
            
            if( $('#searchfilter').val().length > 3  )
            {
               RefreshAddressBook();
            }
        });
        
        
        $("#page").val("1");

        RefreshAddressBook();
        
        $('.contacteditarea').hide();
        $('body').on('click','.viewcontacteditarea',function(){
            $('.contacteditarea').show();
            $('.addressbookcontent').hide();
            $('.prev').hide();
            $('.next').hide();
            $('#recipientname').val("");
            $('#email').val("");
            $('#sms').val("");
            $('#handle').val("");
            $('#mainview').scrollTop(0);
            window.parent.parent.scrollTo(0,0);
        });
        $('body').on('click','.hidecontacteditarea',function(){
            $('.contacteditarea').hide();
            $('.addressbookcontent').show();
            $('.prev').show();
            $('.next').show();
            $('#recipientname').val("");
            $('#email').val("");
            $('#sms').val("");
            $('#handle').val("");
        });
        $('td.label').hide();
        $('td.labelrequired').hide();
       
    });
       </script>
    <title>Contacts List</title>
</head>
<BODY class="mainfont" style=''>
        
    
        <span class=''>
            <div class='gridstdborder' 
                data-room='All' data-roomid='All'                
                style='background-color:<?=$global_titlebar_color?>;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
                <!--
                <img class='tilebutton icon20' src='../img/Arrow-Left-in-Circle-White_120px.png' />
                &nbsp;&nbsp;
                -->
                <?=$icon_braxsettings2?>
                <span class='pagetitle2a' style='color:white'>Contacts</span> 
            </div>
        </span>
        <div style='margin:auto;text-align:center'>
            Page <input class='smalltext' id="page" name="page" type='text'   size="2">
            &nbsp;
            &nbsp;
            <img  class='start icon20' src='../img/rewind-circle-128.png' style="" />
            &nbsp;
            &nbsp;
            <div class='formobile'><br></div>
            Search Name <input class='' id="searchfilter" name="searchfilter" type="text" size='20'>
            &nbsp;
            <img id='getaddressbook' class='icon20' src='../img/refresh-circle-128.png' style="" />
            &nbsp;&nbsp;
            <img class='viewcontacteditarea icon20'  src='<?=$rootserver?>/img/add-circle-128.png' style=''>
        </div>  
            <br>
        <span class='contacteditarea'  >
            <table id="form1" style='margin:auto' >

            <tr>
            <td class="dataarea">
            <div class="divbuttontext divbuttontext_unsel" id="save" name="save">&nbsp; Save Contact &nbsp;</div>
            <div class="divbuttontext divbuttontext_unsel hidecontacteditarea" id="hide" name="save">&nbsp; Done &nbsp;</div>
            <br><br>
            <div id="status" class="status" ></div>
            </td>

            </tr><tr>
            <td class="dataarea">
            <div class="labelrequired">Contact Name:</div>
            Contact Name<br>
            <INPUT class='dataentry' id="recipientname" TYPE="text" NAME="recipientname" placeholder='Contact Name' SIZE="32">        

            </td>

            </tr><tr>
            <td class="dataarea">
            <div class="label">Email Address:</div>
            Email Address<br>
            <INPUT class='dataentry' id="email" TYPE="email" NAME="recipientemail" placeholder='Email Address' SIZE="32">
            </td>

            </tr><tr>
            <td class="dataarea">
            <div class="label">@Handle:</div>
            @Handle<br>
            <INPUT class='dataentry' id="handle" TYPE="text" NAME="handle" placeholder='@handle' SIZE="32">
            <br>
            </td>

            </tr><tr>
            <td class="dataarea">
            <div class="label">Mobile Phone (Text):</div>
            Mobile Phone (Text)<br>
            <INPUT class='dataentry' id="sms" TYPE="text" NAME="sms" placeholder='000/000-0000 Mobile Phone' SIZE="32">
            <br><span class='smalltext'>+CountryCode if Non-US</span>
            </td>

            </tr>
            </form>
            </table>
            <hr>
        </span>
        <br>
        <div style='text-align:center;margin:auto'>
            <img class='prev icon20' src='../img/arrow-circle-up-128.png' style="cursor:pointer" />
            &nbsp;
            &nbsp;
            <img class='next icon20' src='../img/arrow-circle-down-128.png' style="cursor:pointer" />
            <br><br>
        </div>
        <div id="addressbookcontent" class="addressbookcontent" style='margin:auto' ></div>
        <div style='text-align:center;margin:auto'>
            <img class='prev icon20' src='../img/arrow-circle-up-128.png' style="cursor:pointer" />
            &nbsp;
            &nbsp;
            <img class='next icon20' src='../img/arrow-circle-down-128.png' style="cursor:pointer" />
        </div>
       
        <br><br>
    
       
</BODY>
</HTML>

   