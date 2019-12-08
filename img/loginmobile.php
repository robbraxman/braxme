<?php
session_start();
require_once("config.php");
$_SESSION[returnurl]="<a href='loginmobile.php'>Login</a>";
require("htmlmobile.inc");
?>
<script>
var hasStandAloneMode = false;
var standAloneHTML = '';
var standAloneFlag = false;

if (
("standalone" in window.navigator) &&
!window.navigator.standalone
){
        standAloneFlag = true;
}

//localStorage.pageno = parseFloat("0");
if( navigator.userAgent.match(/iPhone/i)) {
        hasStandAloneMode = true;
        standAloneHTML = "standalone.html";
}
else
if( navigator.userAgent.match(/iPad Mini/i)) {
        hasStandAloneMode = true;
        standAloneHTML = "standalone-ipad.html";
}
else
if( navigator.userAgent.match(/iPad/i)) {
        hasStandAloneMode = true;
        standAloneHTML = "standalone-ipad.html";
}
$(document).bind("mobileinit", function () {
    $.mobile.ajaxEnabled = false;
});
$(document).on( "pageinit", function() {

    if( localStorage.pid != '')
    {
        $('#pid').val( localStorage.pid );
        $('#loginid').val( localStorage.loginid );
        $('#password').val( localStorage.password );
    }
    else
    {
        $('#pid').val( '<?=$_SESSION[pid]?>' );
        $('#loginid').val( '<?=$_SESSION[loginid]?>' );
        $('#password').val( '<?=$_SESSION[password]?>' );
            
    }

});    
$(document).ready( function() {
    $('#savelogin').click( function(){
        localStorage.pid = $('#pid').val();
        localStorage.loginid = $('#loginid').val();
        localStorage.password = $('#password').val();
        $('div.ui-collapsible-content','#loginset').addClass('ui-collapsible-content-collapsed');
        $('#actionset').show();
        alert("Login Info Saved.");
    });

    $('#createaccount').hide();
    
/*        
    $('#pid').val( localStorage.pid );
    $('#loginid').val( localStorage.loginid );
    $('#password').val( localStorage.password );
 */   
    if( $('#pid').val()==='')
    {
        $('#actionset').hide();
        $('#createaccount').show();
        $('#loginset').data("collapsed","false");
    }
    else
    {
        $('#actionset').show();
        $('#createaccount').hide();
        $('#loginset').data("collapsed","true");
    }
    

    $('#logout').click( function(){
        $('#actionset').hide();
        localStorage.pid = "";
        localStorage.loginid = "";
        localStorage.password = "";
        $('#pid').val( "" );
        $('#loginid').val( "" );
        $('#password').val( "" );
    });



    $('#action1').click( function() {
        $('#loaderform').attr('ACTION', "messageMgrMobile.php" );
    });
    $('#action2').click( function() {
        $('#loaderform').attr('ACTION', "newmsgMobile1.php" );
    });
    $('#action3').click( function() {
        $('#loaderform').attr('ACTION', "profileMobile.php" );
    });
    $('#action4').click( function() {
        $('#loaderform').attr('ACTION', "chgpwmobile.php" );
    });
    $('#action5').click( function() {
        $('#loaderform').attr('ACTION', "signupMobile.php" );
   });
    $('#action6').click( function() {
        $('#loaderform').attr('ACTION', "signupMobile.php" );
   });
    $('#action7').click( function() {
        $('#loaderform').attr('ACTION', "signupMobile.php" );
    });
    $('#action8').click( function() {
        $('#loaderform').attr('ACTION', "accountstatus.php" );
    });

    $("#loaderform").submit( function()
    {
    });
        $(document).on('click','.forgot', function(e){
            r = confirm( "Did You Forget Your Password?\n\nClick OK to reset to a Temporary Password.\nThis will be emailed to the email address associated\n with this Login ID.");
            if(r)
                $.post('forgot.php', { pid: $('#pid').val(), loginid: $('#loginid').val() }, function(data, status) {
                    if( status == "success")
                        alert( data );
                    
                } );
        });
});
$(document).on("pageinit", function(){

    if ( hasStandAloneMode && standAloneFlag )
    {
            $('span.runstandalone').load( standAloneHTML );
    }
});
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-44029468-1', 'braxsecure.com');
  ga('send', 'pageview');

</script>
<title>BraxSecure</title>
</head>
<BODY class="newmsgbody">
<div data-role='page' data-theme='d' id='LoginPage'>
    <div data-role='header' id='LoginHeader'>
        <h1>BraxSecure</h1>
    </div>

    <div data-role='content' id='LoginContent1'>
        <span class="runstandalone"></span>
         <img class="viewlogomobile" src="../img/braxmobile.png">

         <FORM id="loaderform" ACTION="messageMgrMobile.php" class="loaderform" name="loaderform" METHOD="POST"  >

             <div id="loginset" data-role="collapsible" data-theme="a" data-collapsed="true" data-mini="true">
                 <h1>Login/Logout</h1>
                 <p class="smalltext1"><b>If you are an existing BraxSecure Subscriber, enter your Login Info once and it will be remembered on this device. You will be logged
                     in automatically each time you start the App. If you lose your mobile device, go to www.braxsecure.com to
                     reset your password.</b></p>


                  <label for="pid">Subscriber ID:</label>
                  <INPUT id="pid" TYPE="text" NAME="pid" value='<?php echo "$_GET[pid]"; ?>'>

                  <label for="loginid">Staff Login ID:</label>
                  <INPUT id="loginid" TYPE="text" NAME="loginid" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">

                  <label for="password">Password:</label>
                  <INPUT id="password" TYPE="password" NAME="password" SIZE="10" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">

                 <INPUT id="logout" TYPE="button" data-inline="true" value="Logout" data-theme="d">
                  <INPUT id="savelogin" TYPE="button" data-inline='true' value='Save Login' data-theme="d">
                  
                    <a class="forgot" href="#"><p>Forget password?</p></a>
                  
             </div>
             <div id="actionset">


                <INPUT id="action1" class="changeaction" TYPE="submit" NAME="action1" data-theme='b' value="Message Manager" >
                <INPUT id="action2" class="changeaction" TYPE="submit" NAME="action2" data-theme='b' value="New Message">
                <INPUT id="action3" class="changeaction" data-mini='true' TYPE="submit" NAME="action3" data-theme='c' value="Profile">
                <INPUT id="action4" class="changeaction" data-mini='true' TYPE="submit" NAME="action4" data-theme='c' value="Change Password">
                <INPUT id="action8" class="changeaction" data-mini='true' TYPE="submit" NAME="action4" data-theme='c' value="Account Status">
<?php                
 //               <INPUT id="action3" class="changeaction" TYPE="submit" NAME="action3" value="Staff Setup">
 //               <INPUT id="action4" class="changeaction" TYPE="submit" NAME="action4" value="Address Book Setup">
 //               <INPUT id="action5" class="changeaction" TYPE="submit" NAME="action5" value="Profile Setup">
 //               <INPUT id="action6" class="changeaction" TYPE="submit" NAME="action6" value="Change Password">
?>
             </div>
             <div id="about" data-role="collapsible" data-theme="a" data-collapsed="true" data-mini="true">
                 <h1>About BraxSecure</h1>
                 <ul>
                     <li><p>Welcome to the BraxSecure Companion App. This App is used to send Encrypted Text and Email messages
                             to any party without the need for installing any apps or software at the recipient's end. 
                         </p>
                     <li><p><b>This App requires a subscription to the Desktop/Cloud version.</b>
                         </p>
                     <li><p>This is a companion App to the Full Desktop/Cloud version which has more features and can be accessed
                             by account holders for free.
                         </p>
                     <li><p>Messages sent via BraxSecure are secured with Military Grade encryption and allow you to include attachments
                             such as PDF (limited to photos on the Mobile App). Messages have a limited lifespan (up to 30 days and user
                             specified). For Health Care Providers, these messages are fully HIPAA compliant so they can be used to send 
                             Medical Information including Medical reports.
                         </p>
                     <li><p>Using this product, you can safely pass medical, financial (like Credit Card Numbers) and personal secure 
                             information, including photos, 
                             and documents between sender and recipient and can be an alternate to faxing. 
                             Recipients are able to reply and they can include an attachment. This means you can use the message to request
                             secure information.</p> 
                     <li><p>        
                             This product is perfect for Sales, Doctors, Lawyers, Accountants,
                             Real Estate Agents, Investment Firms, Hospitals since it allows them to easily pass information, secure in the knowledge
                             that they have taken care to prevent information leaks.
                         </p>
                     <li><p>As long as you keep your passwords long, complex and private, and within the limited lifespan of your messages, the content
                             of your messages will be safe from view.
                             </p>
                     <li><p>If you are already a subscriber to BraxSecure, simply fill in your Log In information once and it will be stored on
                             your device. Next time, you can simply go directly to sending or viewing your messages.
                             </p>
                    
                 </ul>
             </div>
             <div id="privacy" data-role="collapsible" data-theme="a" data-collapsed="true" data-mini="true">
                 <h1>Privacy Policy</h1>
             
                <!-- START PRIVACY POLICY CODE --><div style="font-family:arial">
                    <strong>What information do we collect?</strong> <br /><br />
                    We collect information from you when you Sign Up on our Mobile App or Website, make a purchase, or send Messages.
                    Your messages and passwords are stored in an encrypted form in our Servers and are not in any human readable
                    format. 
                    We only store sufficient readable data to
                    track the message and the volume of messages. Message content is stored only for a limited time that you
                    specify.<br />
                    <br />When signing up on our Mobile App or website and using our services, as appropriate, 
                    you may be asked to enter your: name, e-mail 
                    address, mailing address, phone numbers or credit card information. You enter as much or as little data as you
                    wish about yourself and we tell you how the information will be used when you enter it.<br />
                    <br /><strong>What do we use your information for?</strong> <br /><br />
                    Any of the information we collect from you may be used in one of the following ways: <br /><br />
                    <li>
                    To process your messages and transactions<br /><br />
                        Your information, whether public or private, will not be sold, exchanged, transferred, or given to any other 
                        company for any reason whatsoever, without your consent, other than for the express purpose of delivering 
                        the message or service requested.
                    <br /><br />
                    <li>
                    To personalize your experience<br />(your information helps us to better respond to your individual needs)<br /><br />
                    <li> 
                    To improve our website<br />(we continually strive to improve our product offerings based on the information and 
                    feedback we receive from you)<br /><br />
                    <li>To improve customer service<br />(your information helps us to more 
                    effectively respond to your customer service requests and support needs)<br /><br />
                    <br /><br />
                    
                    <strong>
                            How do we protect your information?</strong> <br /><br />
                            We implement a variety of security measures to maintain the safety of your personal information 
                            when you send messages, place an order or enter, submit, or access your personal information. <br /> <br />
                            We offer the use of a secure server. All supplied sensitive/credit information is transmitted 
                            via Secure Socket Layer (SSL) technology and then encrypted into our Payment gateway providers 
                            database only to be accessible by those authorized with special access rights to such systems, 
                            and are required to keep the information confidential.<br /><br />After a transaction, your private 
                            information (credit cards, etc.) will not be stored on our servers.
                            Currently, we use PayPal so your payment information is kept there and only under your control.<br /><br />
                            We store information on our servers in an encrypted format using Military Grade Cryptology technologies. For this
                            reason, secure messages may only be sent to Countries allowed by US Laws.<br /><br />
                            We are also unable to provide you your passwords either by email or by phone, although you are
                            allowed to reset it at any time.
                            <br />
                            <br /><strong>Do we use cookies?</strong> <br /><br />
                            Yes (Cookies are small files that a site or its service provider transfers to your computers hard 
                            drive through your Web browser (if you allow) that enables the sites or service providers systems to 
                            recognize your browser and capture and remember certain information<br /><br /> We use cookies and
                            a browser's Local Storage to
                            reduce data entry by remembering your prior selections. You may choose to turn off cookies and Local Storage
                            if you wish
                            although it will require you to re-enter your login credentials each time you use the service.
                            <br /><br /><strong>Do we disclose any information 
                                to outside parties?</strong> <br /><br />We do not sell, trade, or otherwise transfer to outside parties 
                                any of your personally identifiable information. This does not include trusted third parties who assist us in 
                                operating our apps and website, conducting our business, or servicing you, so long as those parties agree to keep 
                                this information confidential. We may also release your information when we believe release is appropriate 
                                to comply with the law, enforce our site policies, or protect ours or other's rights, property, or safety. 
                                However, non-personally identifiable visitor information may be provided to other parties for marketing, 
                                advertising, or other uses.<br /><br /><strong>California Online Privacy Protection Act Compliance</strong><br /><br />
                                Because we value your privacy we have taken the necessary precautions to be in compliance with 
                                the California Online Privacy Protection Act. We therefore will not distribute your personal information 
                                to outside parties without your consent.<br /><br />As part of the California Online Privacy Protection Act, 
                                all users of our site may make any changes to their information at anytime by logging into account 
                                and going to the 'Profile' page.<br /><br /><strong>Children's Online Privacy Protection Act Compliance</strong> 
                                <br /><br />We are in compliance with the requirements of COPPA (Children's Online Privacy Protection Act), 
                                we do not collect any information from anyone under 13 years of age. Our App, website, 
                                products and services are all directed to people who are at least 13 years old or older.<br /><br /><strong>
                                    Your Consent</strong> <br /><br />By using our Mobile App and site, you consent to our 
                                    <a style='text-decoration:none; color:#3C3C3C;' href='http://www.freeprivacypolicy.com/' target='_blank'>privacy policy</a>.<br /><br /><strong>
                                        Changes to our Privacy Policy</strong> <br /><br />If we decide to change our privacy policy, 
                                        we will post those changes on our website at www.braxsecure.com. <br /><br /><strong>Contacting Us</strong> <br /><br />
                                        If there are any questions regarding this privacy policy you may contact us using the information below. 
                                        <br /><br />Maddison-Crosse Software Inc.<br />12424 Wilshire Blvd. Ninth Floor<br />Los Angeles, CA 90025<br />sales@braxsecure.com
                                        <br /><br /><span></span><span></span>
                                        <span></span><span></span><span></span></div><!-- END PRIVACY POLICY CODE -->             
            </div>

             <INPUT id="sessionid" TYPE="hidden" NAME="sessionid" 
                    value="<?php echo "$_GET[sessionid]"; ?>" >
             
         </FORM>
    </div>
</div>
</BODY>
</HTML>
