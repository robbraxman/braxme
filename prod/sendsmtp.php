<?php
session_start();
$_SESSION['returnurl']="<a href='login.php'>Login</a>";
require_once("config.php");
require ("SmsInterface.inc");
require ("crypt.inc.php");
require ("notify.inc.php");
require ("aws.php");   
require ("password.inc.php");
require ("upload.inc.php");
require ("phpmailer/PHPMailerAutoload.php");
require ("htmlhead.inc.php");   
require ("messagesimap.inc.php");   

class PHPMailer_mine extends PHPMailer {
    public function get_mail_string() {
    return $this->MIMEHeader.$this->MIMEBody;
    }
}

echo "<title>Send Message</title>";
echo "</head>";
echo "<body class='newmsgbody'>";
echo "<div class='statustitle'>Message Status</div><br>Processing...<br>";

ignore_user_abort(true);

$_SESSION['sessionid'] = uniqid("", false);
$_SESSION['status'] = "Y";
$errorstate=false;

$providerid = rtrim(@tvalidator("PURIFY", "$_SESSION[pid]"));
$loginid = @tvalidator("PURIFY", "$_SESSION[loginid]");
$imap_item = intval(rtrim(@tvalidator("PURIFY",$_POST['imap'])))-1;
$autoencryption = intval(@tvalidator("PURIFY",$_POST['autoencryption']));
$alwaysencrypt = intval(@tvalidator("PURIFY",$_POST['alwaysencrypt']));
$originaltext = @tvalidator("PURIFY",$_POST['messagebase64']);

$uuid = intval(rtrim(@tvalidator("PURIFY",$_POST['uuid'])));
$folder = rtrim(@tvalidator("PURIFY",$_POST['folder']));
$sig = rtrim(@tvalidator("PURIFY",$_POST['sig']));
$contactgroup = rtrim(@tvalidator("PURIFY",$_POST['contactgroup']));
$mobile = @tvalidator("PURIFY",$_POST['mobile']);

$result = do_mysqli_query("1", "update imap set sig='$sig' where name = '".$_SESSION['imap_name'][$imap_item]."' and providerid=$providerid ");


$result = do_mysqli_query("1", 
        "SELECT providername, companyname, avatarurl, ".
        "autosendkey, serverhost, msglifespan, allowtexting, ".
        "allowrandomkey, allowkeydownload, afterreadlifespan ".
        "from provider where providerid=$providerid and active='Y' ");

if (!$row = do_mysqli_fetch("1",$result)) 
{
   echo "<tr class='msgsource'>";
   echo "<h2>Subscriber not Found or Not Active</h2><br>";
   echo "</tr'>";
   exit();
}
$providername = $row['providername'];
/*********************************************************************
 *                        VALIDATION CODE NON_RECIPIENT
 *********************************************************************/
if( $_POST['pid'] == ""  || !is_numeric(rtrim($_POST['pid'])))
{
     StatusMessage( "Invalid Subscriber '$_POST[pid]' ", 4);
     $errorstate = true;
}
if( $_POST['recipientname'] == "" && $_POST['ccname']=="" && $_POST['bccname']=="" )
{
     StatusMessage( "Missing Recipient Name", 4);
     $errorstate = true;
}
if( $_POST['message'] == ""  && $_POST['messagemobile']== "")
{
     StatusMessage( "No Message Text", 4);
     $errorstate = true;
}

if( !ProcessUpload("$_POST[pid]","BINARY" ))
{
    ReturnToMessageEntry();
    require("htmlfoot.inc");
    exit();
}

$recipients = tvalidator("PURIFY", $_POST['recipientname']);
$recipient_array  = imap_rfc822_parse_adrlist($recipients, "");

$cc = tvalidator("PURIFY", $_POST['ccname']);
$cc_array  = imap_rfc822_parse_adrlist($cc, "");

$bcc = tvalidator("PURIFY", $_POST['bccname']);
$bcc_array  = imap_rfc822_parse_adrlist($bcc, "");

if (
        (!is_array($recipient_array) || count($recipient_array) < 1 ) 
        &&
        (!is_array($cc_array) || count($cc_array) < 1 ) 
        &&
        (!is_array($bcc_array) || count($bcc_array) < 1 ) 
   )
{
     StatusMessage( "Incorrect or Missing Recipient List", 4);
    $errorstate = true;
}

$targetemail = '';
$targetname = '';
if (
        ( is_array($recipient_array) && count($recipient_array) == 1 ) 
   )
{
    $targetemail = $recipient_array[0]->mailbox."@".$recipient_array[0]->host;
    $targetname = htmlentities($recipient_array[0]->personal, ENT_QUOTES);
    $targetexists = false;
    $targetimapexists = false;
    $result = do_mysqli_query("1","select providerid from provider where replyemail = '$targetemail' and active='Y' ");
    if($row = do_mysqli_fetch("1",$result) ){
        $targetexists = true;
        $targetproviderid = $row['providerid'];
        
        $result = do_mysqli_query("1","select * from imap where email = '$targetemail'  ");
        if($row = do_mysqli_fetch("1",$result) ){
            $targetimapexists = true;
        }
        
    }
    
}



if($mobile ==''){
$message = @tvalidator("PURIFY", $_POST['message']);
    
} else {
$message = @tvalidator("PURIFY", $_POST['messagemobile']);
    
}
$message = str_replace('\"',"", $message);
$message = str_replace('\r\n',"", $message);

$sig = str_replace('\\n',"<br>", $sig);
$sig = str_replace('\\r',"", $sig);
if( $sig!='')
    $sig = "<br><br>$sig";
    
$message .= $sig;
$msgtitle = tvalidator("PURIFY", $_POST['msgtitle']);

/*********************************************************************
 *                        VALIDATION CODE RECIPIENT SPECIFIC
 *********************************************************************/

if( $errorstate == true)
{
    ReturnToMessageEntry();
    require("htmlfoot.inc");
    exit();
}


/*********************************************************************
 *                        SEND MESSAGES
 *********************************************************************/

@do_mysqli_query("1","
    delete from contactgroups where providerid=$providerid and groupname='$contactgroup'
    ");


$encryptflag = $autoencryption;

//See if All Recipients are Braxsecure Account Holders and
//also save to ContactGroup
foreach( $recipient_array as $recipient_item)
{
    
    $recipientemail =  "$recipient_item->mailbox@$recipient_item->host";
    //This means this is not a real email address so exit
    if(strstr($recipientemail, ".account@brax.me")!==false){
        exit();
    }

    $result = do_mysqli_query("1", 
            "
                select email from imap where 
                email = '$recipient_item->mailbox@$recipient_item->host' and
                providerid in (select providerid from provider where active='Y') 
            ");
    if( !$row = do_mysqli_fetch("1",$result))
    {
        $encryptflag = false;
    }
    SaveToContactGroup($providerid, $contactgroup, $recipient_item );
    
}

if($alwaysencrypt)
{
    $encryptflag = true;
}

if (is_array($cc_array) && count($cc_array) > 0) 
foreach( $cc_array as $cc_item)
{
    $result = do_mysqli_query("1", 
            "
                select email from imap where 
                email = '$cc_item->mailbox@$cc_item->host' and
                providerid in (select providerid from provider where active='Y' )
            ");
    if( !$row = do_mysqli_fetch("1",$result))
    {
        $encryptflag = false;
    }
    SaveToContactGroup($providerid, $contactgroup, $recipient_item );
}

$encryptedsection = $message;
if( $encryptflag == true)
{

    $attachmentlinks = "";
    //Encrypted Message - Move all Attachments to FILES AREA
    $result = do_mysqli_query("1", "
        select attachfilename, origfilename, filesize from attachments where sessionid = '$_SESSION[sessionid]' 
        ");
    while($row = do_mysqli_fetch("1",$result))
    {
        if( $attachmentlinks == ""){
            $attachmentlinks = "<br>";
        }
        $attachmentlinks .= UploadToMyFiles( $providerid, "attachments", $row['origfilename'], $row['origfilename'] , "/var/www/html/$installfolder/".$_SESSION['attachmentpath'].$row['attachfilename'], intval($row['filesize']) );
    }
    $result = do_mysqli_query("1", "
        delete from attachments where sessionid = '$_SESSION[sessionid]' 
        ");
    //Append Attachment Links Inline
    $message .= $attachmentlinks;
    $key1 =  str_pad(GenerateRandomNumberMail(),15, "0");
    $mailkey = $key1;  //should be 15 digits in length with 13 significant      
    $mailkeyactual = substr($mailkey,0,13);
    $encoding = str_pad($_SESSION['responseencoding'],30,"_");
    $encode = EncryptEmail ($message, $mailkeyactual );
    $decode = DecryptEmail ($encode, $_SESSION['responseencoding'], $mailkeyactual );
    //echo "<br>$decode<br>";
    //$encode = str_replace(" ","+", $encode);
    
    $encode = chunk_split( $encode, 80, "\n");
    //echo "<br>$encode<br>";
    if(!$targetexists)
    {
        $signuppage = "$rootserver/$installfolder/invite.php?invite=$targetemail&name=$targetname";
        $signuptext = 
            "<span style='font-size:11px' />
             If you are recieving this encrypted communication for the first time,<br>
             please set up a new free account on Brax.Me with your email settings<br>
             so you can read your messages. <br>
             <a href='$signuppage'>$signuppage</a>
             </span>";
    }
    if($targetexists && !$targetimapexists)
    {
        $signuptext = 
            "<span style='font-size:11px' />
             You have not set up an email account for $targetemail in Brax.Me.<br>
             Please set it up to read this encrypted email message.<br>
             </span>";
        
    }

    
    $encryptedsection = 
        "<br><h2>Secure Message</h2>Readable only on Brax.Me - Login to your Account to view your email</b><br>".  
        "<hr>".
        "<pre><div class=brax-aes-em2$mailkey$encoding>$encode</div></pre>".        
        "<hr>$signuptext".
        "<br><br><a href='$homepage'>$homepage</a><br>".
         "<a href='$homepage'><img src='$rootserver/img/lock.png' style='height: 20px; width: auto'></a><br>";
    
    /*
    $encryptedsection = 
        "<br><h2>Secure Message</h2>Readable only by Subscribers of Brax.Me - Login to your Account</b><br>".  
        "<hr>".
        "<pre><div class=brax-aes-sp1>$encode</div></pre>".        
        "<hr>".
        "<a href='$homepage'>$homepage</a><br>".
         "<a href='$homepage'><img src='$rootserver/img/lock.png' style='height: 20px; width: auto'></a><br>";
     * 
     */
    
    
    
    
    
}        
else 
{
     $encryptedsection .=
        "<br><br><span style='font-size:13px'>Sent from $appname</span><br>".
        "<a href='$homepage'>$homepage</a><br>".
         "<a href='$homepage'><img src='$rootserver/img/lock.png' style='height: 20px; width: auto'></a><br>";
    
}

$mail             = new PHPMailer_mine();
$body             = $encryptedsection;
$mail->IsSMTP(); // telling the class to use SMTP

if( $_SESSION['imap_smtp_host'][$imap_item]!="")
{
    $mail->Host       = $_SESSION['imap_smtp_host'][$imap_item];
    $mail->Port       = $_SESSION['imap_smtp_port'][$imap_item];                    // set the SMTP port for the GMAIL server
    $mail->Username   = $_SESSION['imap_smtp_username'][$imap_item];
    $imap_password_decrypted = DecryptResponse( $_SESSION['imap_smtp_password_encrypted'][$imap_item], $_SESSION['imap_encoding'][$imap_item], "$providerid", $_SESSION['imap_name'][$imap_item]);
    $mail->Password   = $imap_password_decrypted;
    if( $_SESSION['imap_smtp_options'][$imap_item]=="/smtp/ssl/novalidate-cert"){
        $mail->SMTPSecure = "ssl";
    }
    if( $_SESSION['imap_smtp_options'][$imap_item]=="/smtp/tls/novalidate-cert"){
        $mail->SMTPSecure = "tls";
    }
    $mail->SetFrom($_SESSION['imap_smtp_email'][$imap_item],$_SESSION['imap_smtp_mailname'][$imap_item]);
    $mail->AddReplyTo($_SESSION['imap_smtp_email'][$imap_item],$_SESSION['imap_smtp_mailname'][$imap_item]);
}
else
{
    $mail->Host       = $_SESSION['smtp_host'];
    $mail->Port       = $_SESSION['smtp_port'];                    // set the SMTP port for the GMAIL server
    $mail->Username   = $_SESSION['smtp_username'];
    $smtp_password_decrypted = DecryptResponse( $_SESSION['smtp_password_encrypted'], $_SESSION['smtp_encoding'], "$providerid", $_SESSION['smtp_name']);
    $mail->Password   = $smtp_password_decrypted;
    if( $_SESSION['smtp_options']=="/smtp/ssl/novalidate-cert"){
        $mail->SMTPSecure = "ssl";
    }
    if( $_SESSION['smtp_options']=="/smtp/tls/novalidate-cert"){
        $mail->SMTPSecure = "tls";
    }
    $mail->SetFrom("$_SESSION[smtp_email]","$_SESSION[smtp_mailname]");
    $mail->AddReplyTo("$_SESSION[smtp_email]","$_SESSION[smtp_mailname]");
    
}

//print_r($_SESSION);
//echo "$imap_item<br>".$mail->Host.":".$mail->Port."<br>";



$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Subject    = stripslashes($msgtitle);
//$mail->AltBody    = $body;
$mail->MsgHTML($body);

foreach( $recipient_array as $recipient_item)
{
    //To account for NULL
    $personal = "";
    //if(property_exists( $recipient_item."personal"))
    {
        $personal = ucwords("$recipient_item->personal");
    }
    if($personal == ""){
        $personal = "";
    }
    $mail->AddAddress($recipient_item->mailbox."@".$recipient_item->host, $personal);
    ImapPopulateContacts2($personal, $recipient_item->mailbox."@".$recipient_item->host );
    AlertNotification( $encryptflag, $providerid, $providername, $recipient_item->mailbox."@".$recipient_item->host );
}

if (is_array($cc_array) && count($cc_array) > 0) {
    foreach( $cc_array as $cc_item)
    {
        //To account for NULL
        $personal = "";
        //if(property_exists( $cc_item,"personal"))
        {
            $personal = ucwords("$cc_item->personal");
        }
        if($personal == ""){
            $personal = "";
        }
        $mail->AddCC($cc_item->mailbox."@".$cc_item->host, $personal);
        ImapPopulateContacts2($personal, $cc_item->mailbox."@".$cc_item->host );
    }
}

if (is_array($bcc_array) && count($bcc_array) > 0) {
    foreach( $bcc_array as $bcc_item)
    {
        //To account for NULL
        $personal = "";
        //if(property_exists( $bcc_item,"personal"))
        {
            $personal = ucwords("$bcc_item->personal");
        }
        if($personal == ""){
            $personal = "";
        }
        $mail->AddBCC($bcc_item->mailbox."@".$bcc_item->host, $personal);
        ImapPopulateContacts2($personal, $bcc_item->mailbox."@".$bcc_item->host );
    }
}

$result = do_mysqli_query("1", "
    select attachfilename, origfilename from attachments where sessionid = '$_SESSION[sessionid]' 
    ");
while($row = do_mysqli_fetch("1",$result))
{
    $mail->AddAttachment( "/var/www/html/$installfolder/".$_SESSION['attachmentpath'].$row['attachfilename'], $row['origfilename'] );
    echo "Attaching $row[origfilename] <br>";
}

if( $originaltext!="")
{
    $mail->AddStringAttachment(base64_decode($originaltext),"email.html","base64","application/octet-stream");
    //echo "$originaltext";
    //$mail->AddStringAttachment( getbody_for_forward ( $uuid, $folder ), "email.eml","base64","application/octet-stream");
    
}

if( $originaltext!="")
{
    $imap_password_decrypted = DecryptResponse( $_SESSION['imap_password_encrypted'][$imap_item], $_SESSION['imap_encoding'][$imap_item], "$providerid", $_SESSION['imap_name'][$imap_item]);
    $inbox = @imap_open($_SESSION['imap_host'][$imap_item],$_SESSION['imap_username'][$imap_item],$imap_password_decrypted) or die('Cannot connect to IMAP Server: ' . imap_last_error());
    $structure = imap_fetchstructure($inbox, $uuid, FT_UID);
    
    $attachments = extract_attachments_send( $inbox, $uuid, $structure, "" );
    imap_close($inbox);

    foreach($attachments as $attachment)
    {
        if( $attachment['attachment']!=''){
            $mail->AddStringAttachment( $attachment['attachment'], $attachment['filename'],"base64","application/octet-stream");
        }
    }
}



//echo "UUID=$uuid FOLDER=$folder<br>";

$result = $mail->Send();
if(!$result )
{
  echo "<br>Mailer Error: " . $mail->ErrorInfo;
  echo "<br>Host: ". $mail->Host;
  echo "<br>Port: ". $mail->Port;
  echo "<br>Username: ". $mail->Username;
  //echo "<br>Password: ". $mail->Password;
  echo "<br>Secure: ". $mail->SMTPSecure;
} 
else 
{
    $mail_string=$mail->get_mail_string()."\r\n";
    $sentfolder = "Sent";   
    
    $imap_password_decrypted = DecryptResponse( $_SESSION['imap_password_encrypted'][$imap_item], $_SESSION['imap_encoding'][$imap_item], "$providerid", $_SESSION['imap_name'][$imap_item]);

    $inbox = ImapConnect(  $providerid, $imap_item, $_SESSION['imap_host'][$imap_item], $_SESSION['imap_username'][$imap_item], $imap_password_decrypted, $sentfolder );
    
    //$inbox = imap_open($_SESSION[imap_host][$imap_item],$_SESSION[imap_username][$imap_item],$_SESSION[imap_password][$imap_item]) or die('Cannot connect to IMAP Server: ' . imap_last_error());
    $s = @imap_append($inbox, $_SESSION['imap_host'][$imap_item].$sentfolder, $mail_string, "\\Seen");
    if(!$s)
    {
        //echo "<br>Save to Sent Folder -  Error";
        //var_dump(imap_errors());
    }
    imap_close($inbox);
    echo "<br>Message sent successfully<br>";
    if( $encryptflag)
    {
        echo "<br>Encryption enabled for these recipient(s)<br>";
        echo "<img src='../img/lock.png' style='height:20px;width:auto' /><br>";
        //echo "$decode";
    }
  //echo $mail_string;
}
    

$result = do_mysqli_query("1", "
    select attachfilename from attachments where sessionid = '$_SESSION[sessionid]' 
    ");
while($row = do_mysqli_fetch("1",$result))
{
    unlink("/var/www/html/$installfolder/".$_SESSION['attachmentpath'].$row['attachfilename'] );
}
$result = do_mysqli_query("1", "
    delete from attachments where sessionid = '$_SESSION[sessionid]' 
    ");


ReturnToMessageEntry();
require("htmlfoot.inc");

/*********************************************************************
 *                        FUNCTIONS
 *********************************************************************/
function ImapPopulateContacts2( $to, $email )
{
    global $providerid;
    
        do_mysqli_query("1","
            insert into contacts (providerid, contactname, email, blocked) 
            values 
            ($providerid, '$to', '$email','')

        ");


}

function ReturnToMessageEntry()
{
    if( $_POST['returnurl']!='')
    {
        echo "<br><a href='javascript:window.close()' >Close</a>";
        
    }
    
}
function StatusMessage( $status, $level )
{
    //Level 1 OK
    //Level 2 Warning
    //Level 3 Severe - DIE
    //Level 4 Suspend Exit
    $truelevel = $level;
    if( $level == 4)
        $truelevel = 3;
    echo "<div class='statusmessage$truelevel'>";
    echo "$status";
    echo "</div>";
    if( $level == 3 )
        exit();
}
function CleanPhone( $phone )
{
    $phone = str_replace( "(", "", $phone );
    $phone = str_replace( "/", "", $phone );
    $phone = str_replace( ")", "", $phone );
    $phone = str_replace( " ", "", $phone );
    $phone = str_replace( "-", "", $phone );
    $phone = str_replace( ".", "", $phone );

    return $phone;
}
function InternationalizePhone ( $phone )
{
    if( $phone[0]!='+' && $phone !='')
        $phone = "+1".$phone;

    return $phone;
}
function GenerateResponseHash( $responsetext, $providerid, $recipientemail )
{

    if( $responsetext=='')
    {
        $temp = uniqid();
        $responsetext = substr("$temp",7,7);
    }


    return $responseencrypt = EncryptResponse( $responsetext, "$providerid", $recipientemail );

}
function SaveToContactGroup( $providerid, $contactgroup, $recipient )
{
    if( rtrim($contactgroup) == "")
    {
        return;
    }

    if( $recipient->mailbox == 'UNEXPECTED_DATA_AFTER_ADDRESS' )
        return;
    
    $contactgroup = ucwords( $contactgroup );
    
    $email = $recipient->mailbox."@".$recipient->host;
    $name =  ucwords("$recipient->personal");
    
    @do_mysqli_query("1","
        insert into contactgroups 
        ( providerid, contactname, email, groupname ) values
        ( $providerid, '$name', '$email', '$contactgroup' )
        ");
    
}


function extract_attachments_send($inbox, $uuid, $structure, $partno = "" ) 
{
   
    $attachments = array();
   
    if(isset($structure->parts) && count($structure->parts)) 
    {
        $i=0;
        //echo "parts".count($structure->parts);
        foreach( $structure->parts as $part)
        {
            $loop_partno = $i+1;
            
            $attachments[$i] = array(
                'is_attachment' => false,
                'filename' => '',
                'name' => '',
                'attachment' => ''
            );
           
            if($structure->parts[$i]->ifdparameters) 
            {
                foreach($structure->parts[$i]->dparameters as $object) 
                {
                    if(strtolower($object->attribute) == 'filename') 
                    {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['filename'] = $object->value;
                    }
                }
            }
           
            if($structure->parts[$i]->ifparameters) {
                foreach($structure->parts[$i]->parameters as $object) {
                    if(strtolower($object->attribute) == 'name') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['name'] = $object->value;
                        

                    }
                }
            }
           
            if($attachments[$i]['is_attachment'] )
            {
                $attachments[$i]['attachment'] = imap_fetchbody($inbox, $uuid, $partno.$loop_partno, FT_UID);
                if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                }
                elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                }
            }
            else 
            {
                $attachments[$i]['is_attachment'] = false;
                
            }
            $i++;
        }
        $i=0;
        foreach( $structure->parts as $part)
        {
            $loop_partno = $i+1;
            if($part->type == 1 )
            {
                $attachments_2 = extract_attachments($inbox, $uuid, $part, $loop_partno."." );
                if( count($attachments_2))
                    $attachments = array_merge( $attachments, $attachments_2 );
            }
            $i++;
        }
    }
   
    return $attachments;
   
}


function extract_attachments_old( $uuid, $folder ) {
    global $imap_item;
    
    $imap_password_decrypted = DecryptResponse( $_SESSION['imap_password_encrypted'][$imap_item], $_SESSION['imap_encoding'][$imap_item], "$providerid", $_SESSION['imap_name'][$imap_item]);
    $inbox = @imap_open($_SESSION['imap_host'][$imap_item],$_SESSION['imap_username'][$imap_item],$imap_password_decrypted) or die('Cannot connect to IMAP Server: ' . imap_last_error());
    
    $attachments = array();
    $structure = imap_fetchstructure($inbox, $uuid, FT_UID);
   
    if(isset($structure->parts) && count($structure->parts)) {
   
        for($i = 0; $i < count($structure->parts); $i++) {
   
            $attachments[$i] = array(
                'is_attachment' => false,
                'filename' => '',
                'name' => '',
                'attachment' => ''
            );
           
            if($structure->parts[$i]->ifdparameters) {
                foreach($structure->parts[$i]->dparameters as $object) {
                    if(strtolower($object->attribute) == 'filename') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['filename'] = $object->value;
                    }
                }
            }
           
            if($structure->parts[$i]->ifparameters) {
                foreach($structure->parts[$i]->parameters as $object) {
                    if(strtolower($object->attribute) == 'name') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['name'] = $object->value;
                    }
                }
            }
           
            if($attachments[$i]['is_attachment'] )
            {
                $attachments[$i]['attachment'] = imap_fetchbody($inbox, $uuid, $i+1, FT_UID);
                if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                }
                elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                }
            }
            else 
            {
                $attachments[$i]['is_attachment'] = false;
                
            }
           
        }
       
    }
    imap_close($inbox);
   
    return $attachments;
   
}
function AlertNotification( $encryptflag, $providerid, $sendername, $targetemail )
{
    if( $encryptflag === 0)
    {
        return;
    }
    $result = do_mysqli_query("1",
        "
        SELECT providerid FROM imap where email='$targetemail' and providerid 
        in (select providerid from notifytokens  where  imap.providerid = notifytokens.providerid )
        ");
    
    while($row = do_mysqli_fetch("1",$result))
    {
        //No Response Yet
        $row['providerid'];

        GenerateNotification( 
            $providerid, 
            $row['providerid'], 
            'EN', null, 
            null, null, 
            $payload, $payloadsms,
            $_SESSION['responseencoding'],'','' );
    }    
    

    
}
function UploadToMyFiles( $providerid, $filefolder, $origfilename, $title, $localfilename, $fsize )
{
    global $rootserver;
    global $installfolder;
    
    $tempfilename = explode(".", $origfilename );
    $filenameext = strtolower($tempfilename[count($tempfilename)-1]); 

    $uniqid = uniqid();
    $attachmentfilename = $providerid."_$uniqid.$filenameext";

    $alias = uniqid("T4AZ", true);
    $encrypted_origfilename = EncryptTextCustomEncode($origfilename,"PLAINTEXT","$attachmentfilename");
    $encrypted_title = EncryptTextCustomEncode($title,"PLAINTEXT","$attachmentfilename");
    
    //Ignore Errors
    @do_mysqli_query("1","
        insert into filefolders (providerid, foldername) values
        ($providerid, 'attachments')
        ");

    $result = do_mysqli_query("1", 
            "
                insert into filelib
                ( providerid, filename, origfilename, folder, filesize, filetype, title, createdate, alias, encoding, status )
                values
                ( $providerid, '$attachmentfilename','$encrypted_origfilename', '$filefolder',$fsize, '$filenameext','$encrypted_title', now(), '$alias','PLAINTEXT','Y' ) 
             "
     );

    //*********AWS ***********//
    //*********AWS ***********//
    //*********AWS ***********//    
    //*********AWS ***********//
    putAWSObject($attachmentfilename, $localfilename);
    return "<br><a href='$rootserver/$installfolder/doc.php?p=$alias' target='_blank'>$origfilename</a><br>";


}

/*
 * 
 function getbody_for_forward ( $uuid, $folder )
{
   global $imap_item;
   
   if( $uuid == '')
       return "";
   $inbox = imap_open($_SESSION[imap_host][$imap_item],$_SESSION[imap_username][$imap_item],$_SESSION[imap_password][$imap_item]) or die('Cannot connect to IMAP Server: ' . imap_last_error());
   $body = imap_body( $inbox,$uuid, FT_UID);
   imap_close($inbox);
   return $body;
}


function download_attachments ( $uuid, $folder )
{
    global $imap_item;
    
    $inbox = imap_open($_SESSION[imap_host][$imap_item],$_SESSION[imap_username][$imap_item],$_SESSION[imap_password][$imap_item]) or die('Cannot connect to IMAP Server: ' . imap_last_error());

    $attachments = extract_attachments($inbox, $uuid, $filename );

    foreach($attachments as $attach1)
    {
        if( $attach1[is_attachment] == true)
        {
            header("Content-Type: application/octet-stream");
            header('Content-Disposition: attachment; filename="'.$attach1['filename'].'"');
            header("Cache-control: private;no-cache"); //prevent proxy caching
            //$filesize = strlen($attach[attachment]);
            //header("Content-length: $filesize");
            //echo "<!DOCTYPE html>\r\n";
            //echo "<!Doctype><html><body>\r\n";
            echo $attach1[attachment];
            //echo "</body></html>\r\n";
        }


    }
    //print_r($attachments);
    imap_close($inbox);
}

function find_attachments($uuid, $filename, $structure)
{
    //Display Attachments
    if($structure->type==1 && $structure->parts) //multipart
    {
        foreach($structure->parts as $key => $part) {
            //echo "<br>Key: ".$key."<br>".print_r($part, true);
            
            if(strtolower($part->disposition) == "attachment")
            {
                foreach( $part->dparameters as $dparmsstruct1)
                {
                    //echo "<br>'$dparmsstruct1->value' vs '$filename'";
                    if( strtolower($dparmsstruct1->attribute) == "filename" && $dparmsstruct1->value == $filename )
                    {
                        return $key;
                    }
                }

            }
        }

    }
    return "None";

}

 * 
 */
?>

