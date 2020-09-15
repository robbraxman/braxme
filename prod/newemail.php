<?php
session_start();
require_once("validsession.inc.php");
require_once("config.php");

$_SESSION['returnurl']="<a href='$rootserver/$installfolder/logins.php'>Login</a>";
require("crypt.inc.php");
require("password.inc.php");


require_once("htmlhead2.inc.php");


$providerid = @tvalidator("PURIFY",$_POST['pid']);
$msgtitle = @tvalidator("PURIFY",$_POST['msgtitle']);
$recipientname = base64_decode(@tvalidator("PURIFY",$_POST['recipientname']));

$imap = intval(rtrim(@tvalidator("PURIFY",$_POST['imap'])));
$imap_item = intval(rtrim(@tvalidator("PURIFY",$_POST['imap'])))-1;
$originaltext = base64_decode(@tvalidator("PURIFY",$_POST['originaltext']));
$originaltext_base64 = @tvalidator("PURIFY",$_POST['originaltext']);
$uuid = @tvalidator("PURIFY",$_POST['uuid']);

//To Get Attachment to Forward
$folder = rtrim(@tvalidator("PURIFY",isset($_POST['folder']),$_POST['folder']));
//This just goes directly into TextArea
$sharetext = base64_decode(@tvalidator("PURIFY",$_POST['sharetext']));

//Forwarding as attachment
if( $recipientname == "" )
{
    if( $originaltext!='')
        $originaltext = "Forwarded email attached";
    $replyheader = '';
}
else 
{
    $originaltext_base64 = "";
    $replyheader = "<br><br><br><br><hr>";
}

//Special case of Social Share from within APP
//Preopulate Textarea
//


if( $_SESSION['imap_smtp_host'][$imap_item]!="")
    $imapstring = "Host: ". $_SESSION['imap_name'][$imap_item];//.":".$_SESSION[imap_smtp_port][$imap_item];
else
    $imapstring = "Host: ". $_SESSION['smtp_name'];//.":".$_SESSION[smtp_port];

$sender =  $_SESSION['imap_smtp_email'][$imap_item];

$result = do_mysqli_query("1", "select alias, uploadcount, industry, allowkeydownload, allowrandomkey from provider where providerid = $providerid ");
$row = do_mysqli_fetch("1",$result);
$alias = $row['alias'];
$uploadcount = $row['uploadcount'];
$industry = $row['industry'];

$result = do_mysqli_query("1", "select sig from imap where name = '".$_SESSION['imap_name'][$imap_item]."' and providerid=$providerid ");
$row = do_mysqli_fetch("1",$result);
$sig = $row['sig'];


if( $uploadcount == 0 || $uploadcount == ''){
    $uploadcount = 1;
}
$replymode = @mysql_isset_safe_string(isset($_POST['replymode']),$_POST['replymode']);
if( $replymode=='Y'){
    $replymode = "<div class=replybox>Reply</div>";
}
    $safe = "<img src='../img/safe-yellow-128.png' title='Partially Safe - Message Body Encrypted - Caution: Attachments/Forwarded Messages are Not Encrypted' style='height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;float:right' />";
    $braxmail = "<img class='icon25' src='../img/braxmail-square.png' style='' />";
?>
<script>
   $(document).ready( function() {

        //$(document).tooltip();
        MobileCapable = false;
        if( navigator.userAgent.match(/iPhone/i)) {
            mobileDevice = "P";
            MobileCapable = true;
            MobileType = "I";
        }
        else
        if( navigator.userAgent.match(/iPad Mini/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
        }
        else
        if( navigator.userAgent.match(/iPad/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "I";
        }
        else
        if( navigator.userAgent.match(/Android/i)) {
            mobileDevice = "T";
            MobileCapable = true;
            MobileType = "A";
        }
        if( MobileCapable)
        {
            $('.nonmobile').hide();
            $('.mobile').show();
            //$('.mobilewidth').width(320);
            $('.mobilewidth').attr('cols','50');
            $('#mobile').val("Y");
        }
        else
        {
            $('.nonmobile').show();
            $('.mobile').hide();
            
        }

       var externallyCalled = false;
       var keycallfrom = 0;
       var xhr = null;
       
        CKEDITOR.disableAutoInline = true;
        CKEDITOR.resize_enabled = true;
        CKEDITOR.replace('message');
        CKEDITOR.config.enterMode = 2;
        //CKEDITOR.config.extraPlugins = 'resize';        
        
        var recipientname = "<?=$recipientname?>";
        
        if( recipientname!="")
        {
        }
        else
        {
            //CKEDITOR.instances.message.setData(atob(originaltext));
        }
        
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

        $('body').on("mouseenter", ".divbuttoncolor1", function(){
            $(this).removeClass('divbuttoncolor1_unsel').addClass('divbuttoncolor1_sel');
            
        });
        $('body').on("mouseleave", ".divbuttoncolor1", function(){
            $(this).removeClass('divbuttoncolor1_sel').addClass('divbuttoncolor1_unsel');
            
        });

        $('body').on("mouseenter", ".divbuttoncolor3", function(){
            $(this).removeClass('divbuttoncolor3_unsel').addClass('divbuttoncolor3_sel');
            
        });
        $('body').on("mouseleave", ".divbuttoncolor3", function(){
            $(this).removeClass('divbuttoncolor3_sel').addClass('divbuttoncolor3_unsel');
            
        });
        $(document).on('click','#contactgroupbutton', function(){
        });


        $(document).on('click','#sendmessagebutton', function(){
                if( SubmitValidate() )
                {
                    $('#SendMsg').submit();
                }
        });
        $(document).on('click','#showaddressbook', function(){
                 searchitem = $('#recipientname').val();
                RefreshAddressBook(searchitem);
        });


        
        <?php
        //InitializeFields();  
        //if( $_POST[loginid]!='')
        ?> 
        $('td.label').show();
        $('td.labelrequired').show();
        $('div.label').hide();
        $('div.labelrequired').hide();
 
        $('#contactlistbutton').click( function() 
        {
            $('form#contactbook').submit();
        });


        $('#start').click( function() 
        {
            $('#addressbookcontent').html( "");
            $('#status').html("");
            nonpatient = '';
            if( $('#nonpatient').is(":checked"))
            {
                nonpatient = 'P';
            }
            $('#page').val("1");
            RefreshAddressBook();
        });
        
        $('#prev').click( function() 
        {
            $('#addressbookcontent').html( "");
            $('#status').html("");
            nonpatient = '';
            if( $('#nonpatient').is(":checked"))
            {
                nonpatient = 'P';
            }
            //alert( parseInt( $('#page').val(),10) );
            if( parseInt( $('#page').val(), 10 )> 1)
            {
                CurPage = parseInt($('#page').val(),10)-1;
                $('#page').val(CurPage );
            }
            RefreshAddressBook();
        });
        
        $('#next').click( function() 
        {
            $('#addressbookcontent').html( "");
            $('#status').html("");
            nonpatient = '';
            if( $('#nonpatient').is(":checked"))
            {
                nonpatient = 'P';
            }
            CurPage = parseInt( $('#page').val(),10 )+1;
            $('#page').val(CurPage);
            RefreshAddressBook();
            
        });
        $('#info1').click( function() 
        {
            alertify.alert(" <b>Tip</b><br><br> \
            You can automatically add to the Contact List<br>by entering an email address in format:<br><br> \
              <i>MyFirstName MyLastName  &ltmyname@domain.com&gt</i> <br><br>\
              Separate multiple addresses with a comma.<br><br>\
              Contact names are automatically<br>searched after you type at least 4 letters.<br> \
            ");
        });
        $('#info2').click( function() 
        {
            alertify.alert(" <b>Tip</b><br><br> \
            If you wish to upload several files, perform a multiselect on the file browser window. <br><br>\
            There is a 50MB limit to the combined files. There may be other limits set by your email provider. <br> \
            ");
        });
        $('#info3').click( function() 
        {
            alertify.alert(" <b>Tip</b><br><br> \
            If you supply a Group Name, you can recall the email addresses later on without \
            having to reenter them by reentering the group name here. \n\
            This is convenient for frequent group communications.<br><br>It will be \
            saved automatically when the email is sent. If you add or remove an email address, the group \
            will be automatically modified.<br><br> \
            Use the List Groups button to show existing groups and then click the desired group. \
            ");
        });
        $('#info4').click( function() 
        {
            alertify.alert(" <b>Tip</b><br><br> \
            Enter your signature line here. This will be appended to the bottom of all your emails. \
            It will be recalled automatically next time. \
            ");
        });

        $('body').on('click','.contactgroupshow', function(e){
            if( $('.sendtogroup').is(":visible"))
            {
                $('.sendtogroup').hide();
            }
            else
                $('.sendtogroup').show();
            
        });

        $('body').on('click','.ccshow', function(e){
            if( $('.cc').is(":visible"))
            {
                $('.cc').hide();
            }
            else
                $('.cc').show();
            
        });
        
        $('body').on('click','.bccshow', function(e){
            if( $('.bcc').is(":visible"))
            {
                $('.bcc').hide();
            }
            else
                $('.bcc').show();
            
        });
        

        $('body').on('click','.contactgrouplist', function(e){
            
            $('.grouplist').load('contactgroup.php',
            { 'providerid': <?=$providerid?>, 'contactgroup' : '', 'mode' : 'S'  });
        });
        
        $('body').on('click','.group', function(e){
            $('#contactgroup').val( $(this).data('groupname'));
            $('#recipientname').load('contactgroup.php',
            { 'providerid': <?=$providerid?>, 'contactgroup' : $('#contactgroup').val(), 'mode' : 'L'  }, function(){
                $('.input-prompt').hide();
            });
        });
        $('body').on('click','.groupdelete', function(e){
            $('.grouplist').load('contactgroup.php',
            { 'providerid': <?=$providerid?>, 'contactgroup' : $('#contactgroup').val(), 'mode' : 'D' }, function(){
                $('.input-prompt').hide();
            });
        });
 
        
         $('#recipientname').keyup(function(e){
            
            var code = e.keyCode || e.which;
            if(code < 32 || code > 122) { //Enter keycode
              return;
            }            
             
            s = $('#recipientname').val();
            s = s.replace(";",",");
            $('#recipientname').val(s);
            n1 = s.lastIndexOf(",");
            if( n1 === -1 )
            {
                searchitem = $('#recipientname').val();
            }
            else
            {
                searchitem = s.substr(n1+1);
            }
            if( searchitem.length > 3  )
            {
                
                keycallfrom = $('#recipientname');
               RefreshAddressBook(searchitem);
            }
            //else
            //    $('.addressbookcontent').hide();
        });
        
         $('#ccname').keyup(function(e){
             
            var code = e.keyCode || e.which;
            if(code < 32 || code > 122) { //Enter keycode
              return;
            }            
            
            s = $('#ccname').val();
            s = s.replace(";",",");
            $('#ccname').val(s);
            n1 = s.lastIndexOf(",");
            if( n1 === -1 )
            {
                searchitem = $('#ccname').val();
            }
            else
            {
                searchitem = s.substr(n1+1);
            }
            if( searchitem.length > 3  )
            {
                keycallfrom = $('#ccname');
               RefreshAddressBook(searchitem);
            }
            //else
            //    $('.addressbookcontent').hide();
        });

         $('#bccname').keyup(function(e){
             
            var code = e.keyCode || e.which;
            if(code < 32 || code > 122) { //Enter keycode
              return;
            }            
            
            s = $('#bccname').val();
            s = s.replace(";",",");
            $('#bccname').val(s);
            n1 = s.lastIndexOf(",");
            if( n1 === -1 )
            {
                searchitem = $('#bccname').val();
            }
            else
            {
                searchitem = s.substr(n1+1);
            }
            if( searchitem.length > 3  )
            {
                keycallfrom = $('#bccname');
                RefreshAddressBook(searchitem);
            }
            //else
            //    $('.addressbookcontent').hide();
        });
        
        $('.fileupload').change(function(){
            
            var valid_extensions = /(.jpg|.jpeg|.gif|.zip|.pdf|.txt|.ppt|.pptx|.png|.doc|.docx|.xls|.xlsx|.tiff|.tif|.mp3|.xml|.csv|.apk|.htm|.html)$/i;   
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
        $('body').on("click",".loaddraft",  function(e){
            var id = $(this).data('id');
            xhr = $.ajax({
                url: 'imapdraft.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'id': id,
                   'mode': 'R'
                  }
            }).done(function(data){
                var msg = jQuery.parseJSON(data);
               
                $('.input-prompt').hide();
                if(MobileCapable)
                {
                    $('.mailinput').val(msg.body);
                }
                else
                {
                    CKEDITOR.instances.message.setData(msg.body);
                }

                $('#recipientname').val(msg.toaddress);
                $('#ccname').val(msg.ccaddress);
                $('#bccname').val(msg.bccaddress);
                $('#msgtitle').val(msg.subject);
                
            });
            
        });
        $('body').on("click",".deletedraft",  function(e){
            var id = $(this).data('id');
            xhr = $.ajax({
                url: 'imapdraft.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'id': id,
                   'mode': 'D'
                  }
            }).done(function(data){
                $('.draftlist').html(data);
            });
        });
     
        $('body').on("click",".savedraft",  function(e){
            var body;
            if( MobileCapable )
            {
                body = $('.mailinput').val();
            }
            else
            {
                body = CKEDITOR.instances.message.getData();
            }
            if( body === "")
                return;
            if( $('#msgtitle').val() === "")
            {
                alertify.alert("Please enter a Subject before saving");
                return;
            }

            xhr = $.ajax({
                url: 'imapdraft.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'subject': $('#msgtitle').val(), 
                   'body': body,
                   'ccaddress': $('#ccname').val(),
                   'bccaddress': $('#bccname').val(),
                   'toaddress': $('#recipientname').val(),
                   'mode': 'S'
                  }
            }).done(function(data){
                $('.draftlist').html(data);
            });
            //alertify.alert('Draft Saved');
            e.preventDefault();
            alertify.alert("Draft Saved");
           
        });
        function LoadDrafts()
        {
            xhr = $.ajax({
                url: 'imapdraft.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'mode': 'L'
                  }
            }).done(function(data){
                $('.draftlist').html(data);
            });
            
        }
        
        function SubmitValidate() 
        {
            if( CKEDITOR.instances.message.getData() == '' &&
                $('.mailinput').val()=='')
            {
                alertify.alert("Invalid Message.");
                return false;
            }
            
            if( $('#recipientname').val().length < 4 && 
                $('#ccname').val().length < 4 &&
                $('#bccname').val().length < 4 
              )
            {
                alertify.alert("Missing recipient name.");
                return false;
            }
            return true; 
           
        };
        

        
        $("#page").val("1");
        
        $('.fileupload').click( function() 
        {
            if( CKEDITOR.instances.message.getData() == '' )
            {
                CKEDITOR.instances.message.setData("File attached.");
            }
        
        });
        
        $("#uploadicon").click(function () {
            $("#fileupload").trigger('click');
        });        
        


    
        function RefreshAddressBook( searchfilter )
        {
           if( xhr && xhr.readyState !== 4){
                xhr.abort();
            }
             
            
            xhr = $.ajax({
                url: 'contactbook.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'searchfilter': searchfilter, 
                   'page': $('#page').val() 
                  },
                        
            }).done(function( html, status ) {
                $('#addressbookcontent').html(html);
                xhr = null;
                if( status=="success")
                {

                        $('.hidecontactbook').click( function(){

                            $('.addressbookcontent').hide();
                        });

                        $('.addressbookcontent').show();
                        $('tr.addressbook').on('click', function() {
                            //alert("User clicked it " + $(this).text()  );

                            if( $(this).find('.addressbook3').text().trim() !='' )
                            {
                                if( keycallfrom === 0)
                                    return;

                                s = $(keycallfrom).val();
                                n1 = s.lastIndexOf(",");
                                if(n1 === -1)
                                    $(keycallfrom).val("");
                                else
                                    $(keycallfrom).val(s.substr(0, n1+1));


                                $(keycallfrom).val( $(keycallfrom).val()+""+
                                        $(this).find('.addressbook1').text().trim()+' <'+
                                        $(this).find('.addressbook3').text().trim()+">," );
                            }


                            //$('#recipientname).val( $(this).children('.addressbook1').text().trim() );
                            //$('#email').val( $(this).children('.addressbook3').text().trim());

                            $('.addressbookcontent').hide();

                        });

                }
            }).fail(function( html, status) {
            }).always(function( html, status) {
            });
            
        }
            
       
       
       
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
    
        LoadDrafts();
       
        $(this).mousemove(function(e){
              parent.ResetTimeout();
              window.top.ResetTimeout();
        });
        
        setInterval( function(){ SafetySave(); }, 5000);
        function SafetySave() {
            
            var body;
            if( MobileCapable )
            {
                body = $('.mailinput').val();
            }
            else
            {
                body = CKEDITOR.instances.message.getData();
            }
            if( body.trim() === "")
                return;
            var subject = 'Last';
            if( $('#msgtitle').val() === "")
            {
                subject = $('#msgtitle').val();
            }

            xhr = $.ajax({
                url: 'imapdraft.php',
                context: document.body,
                type: 'POST',
                data: 
                 { 'providerid': $('#pid').val(), 
                   'subject': subject, 
                   'body': body,
                   'ccaddress': $('#ccname').val(),
                   'bccaddress': $('#bccname').val(),
                   'toaddress': $('#recipientname').val(),
                   'mode': 'S'
                  }
            }).done(function(data){
                $('.draftlist').html(data);
            });
            
        }
       
        $('.mailinput').on('focus',function(){
                $('.mailcontent').hide();
                $('.cc').hide();
                $('.bcc').hide();
                $('.sendtogroup').hide();
            
        });
        $('.mailinput').on('blur',function(){
                $('.mailcontent').show();
            
        });


    });
</script>
<title>New Message</title>
</head>
<BODY class='mainfont'>
   <FORM id="SendMsg" ACTION="<?=$rootserver?>/<?=$installfolder?>/sendsmtp.php"  enctype="multipart/form-data" METHOD="POST" style='padding-top:0;margin-top:0;display:inline'>
        <span class='mailcontent'>
            <br>
                &nbsp;
                <div class="divbutton3 divbutton3_unsel sendmessagebutton" id="sendmessagebutton">Send Email</div>
                <span class="nonmobile">
                &nbsp;
                <div class="divbutton3 divbutton3_unsel contactlistbutton" id="contactlistbutton">Contact List</div>
                &nbsp;
                <div class="divbutton3 divbutton3_unsel savedraft" id="savedraft">Save to Draft</div>

                </span>
<?php
    if($uuid == "")
    {
?>
                <br>
                &nbsp;&nbsp;<input id="autoencryption" type='checkbox' 
                                   class="mainfont autoencryption" NAME="autoencryption" value="1" 
                                   checked="checked"
                                   style='position:relative;top:5px' /><span class=smalltext>Auto-encrypt to <?=$appname?> recipients</span></div>
        <br>
                &nbsp;&nbsp;<input id="alwaysencrypt" type='checkbox' 
                                   class="mainfont alwaysencrypt" NAME="alwaysencrypt" value="1"
                                   style='position:relative;top:5px'/><span class=smalltext>Always encrypt for all recipients</span></div>
<?php 
    }
    else
    {
        echo "No Encryption on attachments";
    }
?>
            
            <br><br>
        </span>
    <INPUT TYPE="hidden" NAME="pid" value='<?=$providerid?>' >
     <INPUT TYPE="hidden" NAME="loginid" value='<?=$_SESSION['loginid']?>' >
    <INPUT id="password" TYPE="hidden" NAME="password"  >
    <INPUT id="imap" TYPE="hidden" NAME="imap" value="<?=$imap?>"  >
    
    <INPUT id="uuid" TYPE="hidden" NAME="uuid" value="<?=$uuid?>"  >
    <INPUT id="folder" TYPE="hidden" NAME="folder" value="<?=$folder?>"  >
    <INPUT id="mobile" TYPE="hidden" NAME="mobile" value=""  >
           
    <table id="newmsgtable" class="mainfont" style='padding-top:0;margin-left:0;max-width:95%'>



        
        
        <tr class='mailcontent'>            
        <td class="dataarea">
        <div class="labelrequired">To:*</div>
        <span class='smalltext'>From: <?=$sender?></span><br>
        <textarea class='mainfont dataentry' title="Sample Name &lt; email &gt;," id="recipientname" name="recipientname"  rows="3" style='width:95%'><?=$recipientname?></textarea>
        <br>
         <div class='ccshow' style="display:inline;cursor:pointer;color:steelblue">Cc</div>
         &nbsp;
         &nbsp;
         <div class='bccshow' style="display:inline;cursor:pointer;color:steelblue">Bcc</div>
         &nbsp;
         &nbsp;
         <div class='contactgroupshow' style="display:inline;cursor:pointer;color:steelblue">Email Groups</div>
         &nbsp;&nbsp;
         <img class='icon20' src="../img/info-128.png" style="" id="info1" />
        </td>
        </tr>
        
        <tr class="sendtogroup" style="display:none">            
        <td class="dataarea">
        <div class="label"></div>
        Save to Group Name: <input class='mainfont dataentry' title="Email Group" id="contactgroup" name="contactgroup" size="40" />
         <div class='divbutton divbutton_unsel contactgrouplist'>List Email Groups</div>
         <img class='icon20' src="../img/info-128.png" style="" id="info3" />
         <div class='grouplist'></div>
        </td>
        </tr>
        
        
        <tr class="cc" style="display:none">            
        <td class="dataarea">
        <div class="label">Cc:</div>
        Cc<br>
        <textarea class='mainfont mobilewidth' title="Sample Name &lt; email &gt;," id="ccname" name="ccname" cols="50" rows="1" style='width:95%'></textarea>
        </td>
        </tr>
        
        <tr class="bcc" style="display:none">            
        <td class="dataarea">
        <div class="label">Bcc:</div>
        Bcc<br>
        <textarea class='mainfont mobilewidth' title="Sample Name &lt; email &gt;," id="bccname" name="bccname" cols="50" rows="1" style='width:95%'></textarea>
        </td>
        </tr>
        
        
        <tr class='mailcontent'>            
        <td class="dataarea">
            <div id="addressbookcontent" name="addressbookcontent" class="dataentry addressbookcontent" style="display:none"></div>
        </td>
        </tr>

        
        
        
        <tr class='mailcontent'>
        <td class="dataarea">
        <div class="label"></div>
        <input title='Subject' id="msgtitle" type='text' class="dataentry msgtitle" NAME="msgtitle"  value="<?=$msgtitle?>"  style='width:95%' autocapitalize="off" autocorrect="off" autocomplete="false"  />
        </td>
        </tr>
        
        <tr class='nonmobile'>
        <td class="dataarea">
        <div class="labelrequired">Message:*</div>
        <textarea id="message" class="mainfont dataentry msg mailcontent" NAME="message"  rows="5" maxlength="10240000" ><?=$replyheader?><?=$originaltext?><?=$sharetext?></textarea>
        <textarea id="messagebase64" class="msgorig" NAME="messagebase64"  style='display:none'><?=$originaltext_base64?></textarea>
        </td>
        </tr>
        
        <tr class='mobile'>
        <td class="dataarea">
        <div class="labelrequired">Message:*</div>
        <textarea id="messagemobile" class="msgmobile mailinput mobilewidth" name='messagemobile'  cols="50" rows="5" maxlength="10240000" style='width:95%'><?=$sharetext?></textarea>
        </td>
        </tr>
        
        
        <tr class='mailcontent'>
        <td class="dataarea">
        <div class="label">Attachments:*</div>
        <br>
         <img class='icon20' src="../img/attachment-128.png" style="" id="uploadicon" />
 
        Files to Upload 
        <span id="uploadfiletext" class="nonmobile"> (Multiselect allowed):<br><i class='smalltext'>Limit: 10 Files and No larger than 50MB total</i></span>        
         <img class='icon20' src="../img/info-128.png" style="" id="info2" />
        
         <input class='fileupload' id='fileupload' type='file' name='file[]' multiple='multiple'  style="display:none"><br>        
         <input type="hidden" name="MAX_FILE_SIZE" value="20480000">
         <br>
         <br>
        </td>
        </tr>

        <tr class="nonmobile mailcontent">
        <td class="dataarea">
        <div class="labelrequired"></div>
        Signature<br>
        <textarea id="sig" class="dataentry sig" NAME="sig" cols="50" rows="5" maxlength="1024" style=''><?=$sig?></textarea>
         <img class='icon20' src="../img/info-128.png" style="" id="info4" />
        </td>
        </tr>
        
        <tr class='mailcontent'>
        <td class="dataarea">
        <div class="labelrequired">Drafts:</div>
        <div class="draftlist"></div>
        </td>
        </tr>
        
   </table>
	<INPUT id="dob" TYPE="hidden" NAME="dob" >
	<INPUT id="returnurl" TYPE="hidden" NAME="returnurl" >
	<INPUT id="sessionthread" TYPE="hidden" NAME="sessionthread" >
        <INPUT id="pid" TYPE="hidden" NAME="pid" value='<?=$providerid?>' >
         <INPUT TYPE="hidden" NAME="loginid" value='<?=$_SESSION['loginid']?>' >
    </FORM>
        <span class='mailcontent'>

        
   

        <br><span id='inactivityseconds'></span>
     <span style='display:none'>
        <FORM id="contactbook" name='contactbook'  ACTION="contactbooklist.php" METHOD="POST" target=_self >
                <INPUT TYPE="hidden" NAME="pid" value='<?=$providerid?>' >
                 <INPUT TYPE="hidden" NAME="loginid" value='<?=$_SESSION['loginid']?>' >
                 <INPUT TYPE="hidden" NAME="returnurl" value='<a href=login.php>Login</a>' >
        </FORM>
        </span>
     </span>
<?php   
require("htmlfoot.inc");


?>   
