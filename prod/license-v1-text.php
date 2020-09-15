<?php
require_once("config.php");
require_once("colorscheme.php");
?>
<div style='padding:10%;font-family:helvetica neue,helvetica, arial, san-serif  ;background-color:<?=$global_background?>;color:<?=$global_textcolor?>;margin:auto;max-width:90%'>
    <center>
    <div style='font-family:"Helvetica Neue",Helvetica, Arial, san-serif;font-size:30px;font-weight:100;'><?=$appname?> Terms of Service Use</div>
    </center>
    <br><br>
<?php
if( $i != 'Y'){
?>
    <b>Please review the Terms of Use and indicate your agreement or disagreement at the bottom of this document. The
    terms of use have been changed as of June 19, 2017.</b>
    <br><br>
<?php
}
?>
    
Use of this service is FREE for non-commercial use and for personal storage use under 4GB, and file sharing download bandwidth under 4GB. 
<?=$enterpriseapp?> use requires an annual subscription and is subject to the storage and bandwidth limits of the subscription. Enterprise 
users must sign up on the website <?=$homepage?>.
    <br><br>
You may choose to terminate this service at any time and you have the ability to delete all content before 
you do so. If you terminate this service, your content will not be retrievable.
    <br><br>
Use of this software is prohibited in certain countries to which export of Cryptography is banned. These are
"Terrorist Supporting Countries" classified as "E1" as defined in  EAR part 772.1 of US Export Laws.
    <br><br>

    
<b>Privacy</b>
<br>
<br>

Your privacy is our focus and is of the utmost importance to us. 
<br><br>
We designed our Privacy Policy to make important disclosures about 
how you can use <?=$appname?> to communicate with others and what information we collect and use.
We encourage you to read the Privacy Policy, and to use it to help you make informed decisions.
<br><br>
 
<b>Sharing Your Content and Information</b>
<br><br>

You own all of the content and information you post on <?=$appname?>, and you can control how it is 
shared through your privacy and application settings. We do not share your information unless required
to do so by US law.
<br><br>
<b><?=$appname?> Photos</b> - Photos uploaded to the <?=$appname?> My Photos are stored in an encrypted form. We do not expose an open
link to the Internet to those photos unless you choose to do it yourself. You can share your photos as "Open" or "Hidden". At any time,
you can delete a photo and it will be unavailable to all where it was shared. 
<br><br>
<b><?=$appname?> Files</b> - Files uploaded to the <?=$appname?> My Files are also stored in an encrypted form. You can provide 
links to your stored file for external use if you wish. Note that downloads of your content is subject to the bandwidth and 
storage limit of your subscription.
<br><br>
<b><?=$appname?> Rooms</b> - discussions and photos are private and encrypted and not exposed to non-members of the room. The
room creator decides which rooms will have open or restricted membership. Subscribers cannot see who is participating in any other 
<?=$appname?> Room unless they are a member of that room. Rooms are not visible to search engines and robot crawlers.
<br><br>
<b><?=$appname?> Chat</b> - If you 'Chat' with any party be assured that your messages are encrypted. You control the lifespan of the 
conversation. Once you delete the conversation, there will be nothing kept on our end. This is HIPAA compliant.
<br><br>
If you are on the free subscription, you are only eligible to see chat messages within the last 60 days. However, once you change 
to a paid subscription, viewing of all messages will be restored.

<br><br>
Social media posts, likes, or any other content created through <?=$appname?> are not used to track your behavior in any
way for advertising purposes. We strive to limit exposure of your identity on the Internet.
<br><br>
Passwords to all aspects of <?=$appname?> are stored with encryption or stored as hashes. Only you can know your password. We
cannot view it or modify it for you. You may reset the password yourself as long as you control your email address. In
the event that you lose control of your email address, we advise you to set up a new account. We will be happy to 
transfer any account credits if any apply (for paid services).
<br><br>

<b>Encryption</b>
<br><br>
All content is kept in an encrypted form both in storage and in transport. Encryption ciphers used meet or 
exceed US Government security requirements (cipher used is approved for TOP SECRET use).
<br><br>

<b>Content Moderation</b>
<br><br>
Public (discoverable and searchable) viewable content or materials of any kind (text, graphics,
images, photographs, sounds, etc.) that in our reasonable judgment may be found objectionable or
inappropriate, for example, materials that may be considered obscene, pornographic, or defamatory will be
removed from public access.
<br><br>
Users who create inappropriate content in publicly discoverable areas are subject to having their account inactivated or disabled.
Users may block individuals from contacting them by adding them to their block list.
<br><br>

<b>Copyrights and Trademarks</b>
<br><br>
You will not represent yourself as employees or officials of <?=$appname?> without prior authorization. You will 
not use the name <?=$appname?> and logos of <?=$appname?> on merchandise without the prior approval of the company.
<br><br>



<b>Your Commitments</b>
<br><br>
We do our best to keep <?=$appname?> safe, but we cannot guarantee it. 
We need your help to keep <?=$appname?> safe, which includes the following commitments by you:
<br><br>
You will not post unauthorized commercial communications (such as spam).
<br><br>
You will not collect users' content or information, or otherwise access <?=$appname?>, using automated means 
(such as harvesting bots, robots, spiders, or scrapers) without our prior permission.
<br><br>
You will not engage in unlawful multi-level marketing, such as a pyramid scheme, on <?=$appname?>
<br><br>
You will not create discoverable or searchable content that can be considered obscene, pornographic, or defamatory.
<br><br>
You will not upload viruses or other malicious code.
<br><br>
You will not solicit login information or access an account belonging to someone else.
<br><br>
You will not use <?=$appname?> to do anything unlawful, misleading, malicious, or discriminatory.
<br><br>
You will not do anything that could disable, overburden, or impair the proper working or appearance of Brax.Me, 
such as a denial of service attack or interference with page rendering or other <?=$appname?> functionality.
<br><br>
You will not facilitate or encourage any violations of this Statement or our policies.
<br><br>
You must agree to the Terms of Use before using this service. You must be of legal age in your jurisdiction 
to accept the Terms of use. If not then the terms of use must be accepted by your guardian. You must be at least 13 
years old to use this service.
<br><br>
<br><br>
<?php
if($i != 'Y'){
?>

<div class='divbutton3 termsofuseagree' style="background-color:<?=$global_menu2_color?>;color:white"><b>I agree to the Terms of Use</b></div>
<div class='formobile'><br><br></div>
<div class='divbutton3 termsofusedisagree' style="background-color:<?=$global_menu2_color?>;color:white"><b>I do not agree</b></div>
<br><br>
<br><br>
<?php
}
?>
<br><br>
<br><br>
(C) Copyright 2018 <?=$appname?>


 
</div>
