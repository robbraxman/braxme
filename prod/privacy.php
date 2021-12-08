<?php
session_start();
header('X-Frame-Options: SAMEORIGIN');
require( "config-pdo.php");
require( "htmlhead.inc.php");

$i = @tvalidator("PURIFY",$_GET['i']);
$apn = @tvalidator("PURIFY",$_GET['apn']);
$gcm = @tvalidator("PURIFY",$_GET['gcm']);
$source = @tvalidator("PURIFY",$_GET['s']);
?>
</head>
<title>Privacy Policy</title>
</head>
<BODY class="appbody" style="padding:0;margin:0;background-image:url('<?=$rootserver?>/img/Blurred Backgrounds (12).jpg');background-size:cover">
<?php
if(isset($i) && $i == 'Y'){
    echo "&nbsp;&nbsp;<a class='smalltext' href='invite.php?s=$source&apn=$apn&gcm=$gcm&lang=$_SESSION[language]'><img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' /></a> Back<br><br>";
}
?>
    <div style='background-color:<?=$global_background?>;color:<?=$global_textcolor?>;padding:10%;max-width:90%;margin:auto'>
    <span style='font-family:"helvetica nueue", helvetica;font-weight:100'>
        <a href='<?=$homepage?>' style='text-decoration:none'>
            <img src="<?=$applogo?>" style="width:auto;height:45px;">
            </a>
            </span>
        <br><br>
        <center><h1><?=$appname?> Privacy Policy</h1></center>
    <div class='mainfont'>
                    <strong>Your Privacy</strong> <br /><br />
                    We place extraordinary importance to your privacy and protecting it while you use our app. As part
                    of our desire to provide security for your data, we also need to be confident of your 
                    identity so that your contacts and friends know to whom they are talking to.
                    
                        <br /><br />                    
                    <strong>What information do we collect?</strong> <br /><br />
                    During signup, we collect your name. It is not required that you
                    give your real name, however it may be difficult for you to be identified by your friends if you
                    don't provide it. You can always change this at any time. 
                        <br /><br />                    
                    We ask you to enter an email address. You must have access to this email address. 
                    We will verify it for your protection. You may choose to not supply an email address. This will prevent you from 
                    doing a password reset if you forget your password, however.
                        <br /><br />                    
                    You use a @username for people to use to contact you on the app without using any other identifier.
                        <br /><br />                    
                    We ask for an optional mobile phone to send you SMS (text) messages. The primary purpose of this is to allow you to conveniently receive temporary passwords so you can 
                    easily get back to your account if you forget your password. 
                    It is also used to send you notifications
                    if you do not use the mobile app version (you will not get SMS messages if your mobile 
                    notifications are enabled).
                    We consider this mobile phone to be very private. It is never displayed 
                    on the app for others to see and is stored in an encrypted form.
                        <br /><br />                    
                    We do not store your password directly (we use something called an irreversible hash). This means that we 
                    cannot see your password or log in to your account without your knowledge. 
                    We are also unable to change your passwords 
                    for you.
                        <br /><br />                    
                    We do not extract or store EXIF data from your photos in My Photos other than to read the orientation of the photo. We 
                    then strip out the EXIF data upon storage. We do not read EXIF data of photo files in My Files. It is encrypted directly.
                        <br /><br />                    
                    We do not track anything in cookies. We do not track your location or your IP address. 
                    Your device retains an encrypted form of your login information for quick access to the app when you are 
                    logged in. This login information is removed when you log out.
                        <br /><br />         
                    If you click on an external link posted by other users, other websites may create cookies that track you. 
                    Note that those links mean you have left this app.
                        <br /><br />         
                    If we detect an attack on our servers (such as setting up multiple accounts, or denial of service attempts), 
                    we reserve the right to begin fingerprinting you to lock access to the app.
                        <br /><br />                    
                        
                        <strong>We Do Not Share your Info</strong> 
                        <br /><br />     
                        
                    We do not sell, exchange, or transfer your email address, or SMS number to other companies without your express
                    permission. We will contact you via email only in relation to this app (such as messages and alerts).
                        <br /><br />     
                    We do not collect personally identifiable information (PII). There are no "profiles" on you. You 
                    are free to reveal information to those you are communicating with on your own. We do not provide 
                    information on your actions to any outside party or track you for the purpose of advertising.
                        <br /><br />     
                        <strong>Confidentiality of Your Data</strong> 
                        <br /><br />   
                    We take extraordinary precautions to ensure that your data remains private. Even with a 
                    security breach, your data is resistant to hacking both from external or internal players.
                        <br /><br />   
                    When you send messages and create posts, be assured that your content is kept in non-human readable 
                    form. It is also designed to be unreadable to us. Your private data is always encrypted whether in 
                    storage or in transit. Note that there are public areas in <?=$appname?> and those are readable 
                    and open, although the identity of the posters can be aliased or anonymous. Open Membership areas are clearly 
                    labeled.
                        <br /><br />                    
                    
                        <strong>Amazon AWS</strong> 
                        <br /><br />                    
                        <?=$appname?> is built on the Amazon Web Services (AWS) platform. This is the Independent Accountant's 
                        <a href='https://cert.webtrust.org/pdfs/soc3_amazon_web_services.pdf' > security review</a>
                        of AWS. Note that we add another layer of encryption to what is provided by AWS.
                        
                    <br /><br />    
                    
                        
                    <strong>HIPAA Compliant (USA)</strong> <br /><br />
                    Except for Open Membership Rooms, all features of this app are HIPAA compliant (ensuring privacy of Medical information). 
                    Because our company is not able to see your private content, there is no necessity for 
                    a Business Associate Agreement but we will sign the agreement if asked to do so. This 
                    app is safe for use in provider-to-provider and provider-patient communications.
                    <br /><br />                    

                    
                    
                                <strong>California Online Privacy Protection Act Compliance</strong><br /><br />
                                Because we value your privacy we have taken the necessary precautions to be in compliance with 
                                the California Online Privacy Protection Act. We therefore will not distribute your personal information 
                                to outside parties without your consent.<br /><br />As part of the California Online Privacy Protection Act, 
                                all users of our site may make any changes to their information at anytime by logging into account 
                                and going to the 'Profile' page.<br /><br /><strong>Children's Online Privacy Protection Act Compliance</strong> 
                                <br /><br />We are in compliance with the requirements of COPPA (Children's Online Privacy Protection Act), 
                                we do not collect any information from anyone under 13 years of age. Our app, website, 
                                products and services are all directed to people who are at least 13 years old or older.<br /><br /><strong>
                                    Your Consent</strong> <br /><br />By using our Mobile App and site, you consent to our 
                                    <a style='text-decoration:none; color:#3C3C3C;' href='http://www.freeprivacypolicy.com/' target='_blank'>privacy policy</a>.<br /><br /><strong>
                                        Changes to our Privacy Policy</strong> <br /><br />If we decide to change our privacy policy, 
                                        we will post those changes on our website at <?=$homepage?>. <br /><br /><strong>Contacting Us</strong> <br /><br />
                                        If there are any questions regarding this privacy policy you may contact us using the information below. 
                                        <br /><br /><?=$appname?><br />13428 Maxella Ave #560<br />Marina Del Rey, CA 90292<br />
                                        <br /><br /><span></span><span></span>
                                        <span></span><span></span><span></span>             
    </div>


    </div>
    
    </div>
    
</body></html>

<?php
exit();
?>
