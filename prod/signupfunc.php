<?php
require_once("config-pdo.php");
require_once("SmsInterface.inc");
require_once("sendmail.php");
require_once("crypt-pdo.inc.php");
require_once("roommanage.inc.php");
require_once("whitelist.inc.php");
class SignUp 
{
    
    var $serverhost;
    
    var $providerid;
    var $providername;
    var $replysms;
    var $replyemail;
    var $handle;
    var $password;
    var $enterprise;
    var $industry;
    var $companyname;
    
    var $roomhandle;
    var $roomid;
    var $invited;
    
    var $gcm;
    var $apn;
    var $mobile;

    var $avatarurl;
    var $active = 'Y';
    var $loginid;
    var $lifespan;
    var $msglifespan;
    
    var $dealeremail;
    var $invitesource;
    var $accountnote;

    var $msgmasterkey;
    var $autosendkey;
    var $newmsgurl;
    var $allowtexting;
    var $dealer;
    var $contractperiod;
    var $contracttype;
    var $verified;    
    var $inactivitytimeout;
    var $notifications;
    var $notificationflags;
    var $publish;
    var $publishprofile;
    var $roomdiscovery;
    var $newemail;
    var $superadmin;
    
    var $invitedemail;
    var $invitedsource;
    
    var $eowner;
    
    var $errorcount;
    var $errormsg;
    var $displaymsg;
    var $error1;
    var $overwrite;
    
    //Edit
    var $enable_email;
    var $age;
    var $alias;
    var $positiontitle;
    var $defaultsmtp;
    var $sponsor;
    var $orighandle;
    var $onetimeflag;
    var $inviteid;
    var $streamingaccount;
    var $welcome;
    var $sponsorlist;
    var $pin;
    var $colorscheme;
    var $gift;
    var $wallpaper;
    var $termsofuse;
    var $language;
    var $roomfeed;
    var $iphash;
    var $iphash2;
    var $iphash3;
    var $timezone;
    var $ipsource;
    var $trackerid;
    var $roomcreator;
    var $broadcaster;
    var $store;
    var $web;
    var $hardenter;
    
    
    function IPHashCheck($timezone, $deviceid, $lastuser,$signupcookie, $innerwidth, $innerheight)
    {
        $ip = WhiteListCheck(false);
        if($ip == 'internal' || $ip == 'whitelist' ){
            return true;
        }
        if(strstr($ip,".")!==false){
            $this->iphash = hash("sha256",$ip);
        } else {
            $this->iphash = $ip;
        }
        $useragent = tvalidator("PURIFY",$_SERVER['HTTP_USER_AGENT']);
        $this->iphash2 = hash("sha256",$ip.$useragent.$timezone);
        $this->iphash3 = hash("sha256",$ip.$useragent.$timezone.$innerwidth.$innerheight);
        
        //Which hash to use for tracking
        $iphash = $this->iphash2;
        
        if($iphash == ''){
            return true;
        }
        pdo_query("1"," 
            insert ignore into iphash ( ip, activitydate, lastaction, icount )
            values( ?, now(), null, 0 )
            ",array($iphash));
        
        $result = pdo_query("1","
            select lastaction from iphash where ip = ? 
                ",array($iphash));
        if( $row = pdo_fetch($result)){
            $lastaction = $row['lastaction'];
        }
        
        
        if($deviceid!=''){
            pdo_query("1"," 
                update iphash set deviceid=? where deviceid is null and ip = ?
                ",array($deviceid,$iphash));
        }
        if($lastuser!=''){
            pdo_query("1"," 
                update iphash set lastuser=? where lastuser is null and ip = ?
                ",array($lastuser,$iphash));
            //Connect this account to other user.
            pdo_query("1"," 
                update provider set iphash2 =?, multi=multi+1 where handle=? and active='Y' 
                ",array($iphash,$lastuser));
            
        }
        if($timezone!=''){
            pdo_query("1"," 
                update iphash set timezone=? where timezone is null and ip = ?
                ",array($timezone,$iphash));
        }
        if($signupcookie!=''){
            pdo_query("1"," 
                update iphash set signupcookie=? where signupcookie is null and ip = ?
                ",array($signupcookie,$iphash));
        }
        if($innerwidth!=''){
            pdo_query("1"," 
                update iphash set innerwidth=? where innerwidth is null and ip = ?
                ",array($innerwidth,$iphash));
        }
        if($innerheight!=''){
            pdo_query("1"," 
                update iphash set innerheight=? where innerheight is null and ip = ?
                ",array($innerheight,$iphash));
        }
        
        
        
        pdo_query("1"," 
            update iphash set icount = icount+1, lastaction = now() 
                where 
                (   ip = ? or 
                    (deviceid=? and ?!='') or 
                    (lastuser=? and ?!='') 
                )
            ",array($iphash,$deviceid,$deviceid,$lastuser,$lastuser));
        
        $result = pdo_query("1","
            select icount from iphash where ip = ? and datediff(?, lastaction)<1
                and icount > 2
                ",array($iphash,$lastaction));
        if( $row = pdo_fetch($result)){
            $icount = $row['icount'];
            pdo_query("1"," 
                update iphash set ipattacker = ? where ip = ? and ipattacker is null
                ",array($ip,$iphash));
            
                return $icount;
        }
        
        pdo_query("1"," 
            update iphash set icount = 1 where ip = ? and datediff(activitydate, lastaction)>0
            ",array($iphash));
        return true;
        
    }
    function InitVars(
            $providerid, $providername, $replyemail, $replysms, $handle, $password )
    {

        $this->providerid = $providerid;
        $this->providername = $providername;
        $this->replyemail = $replyemail;
        if($this->replyemail == '' && $this->providerid !=''){
            $this->replyemail = "$this->providerid".".account@brax.me";
        }
        $this->replysms = $this->CleanPhone($replysms);
        $this->handle = $handle;
        $this->password = $password;
        $this->overwrite = 'N';
        $this->newemail = "";
        $this->errorcount = 0;
        $this->sponsor = "";
        $this->onetimeflag = "";
        $this->eowner = "null";
        $this->roomcreator = 'N';
        $this->broadcaster = 'N';
        $this->store = 'N';
        $this->web = 'N';
    }
    
    function QueueCreateAccount(
            $providerid, $providername, $replyemail, $replysms, $handle, $password, $roomid, $sponsor, $companyname )
    {

        $this->providerid = $providerid;
        $this->providername = $providername;
        $this->replyemail = $replyemail;
        $this->replysms = $this->CleanPhone($replysms);
        $this->handle = $handle;
        $this->password = strtolower($password);
        $this->roomid = $roomid;
        $this->sponsor = $sponsor;
        $this->companyname = $companyname;
        
        $result = pdo_query("1"," 
            insert ignore into csvsignup ( email, sms, name, handle, ownerid, roomid, temppassword, sponsor, companyname, uploaded, status )
            values (?,?,
            ?,?,$_SESSION[pid],?,?, ?,?, now(),'U' )
            ",array(
                $this->replyemail,$this->replysms,
                $this->providername,$this->handle, $this->roomid, $this->password, $this->sponsor,$this->companyname
                
            ));
        if($result){
            return true;
        }
        return false;
    }
    function BatchCreateAccount( $providerid, $limit )
    {
        $overwrite = 'Y';
        $errorcount = 0;
        $onetimeflag = 'Y';
        $timezone = "-8";
        $trackerid = '';
        
        $result = pdo_query("1"," 
            select id, name, email, sms,handle,  temppassword, ownerid, roomid, sponsor, companyname  from csvsignup
            where status='N'  $limit
            ");
        while($row = pdo_fetch($result)){
            
            $ownerid = $row['ownerid'];
            $name = ucwords(strtolower($row['name']));
            $handle = strtolower($row['handle']);
            $email = strtolower($row['email']);
            $sms = $row['sms'];
            $companyname = ucwords(strtolower($row['companyname']));
            $termsofuse = 'Y';
            $language = 'English';
            
            /* Customized for DTE */
            $name = str_replace("D/b/a","DBA",$name);
            $companyname = str_replace("D/b/a","DBA",$companyname);
            
            $status = $this->CreateAccount(
                $providerid, $name, $email, $sms, $handle,
                $row['temppassword'],
                '', //logind
                '', //Enterprise
                '', //industry
                $companyname, //company
                $row['sponsor'], //sponsor
                '', //Roomhandle
                $row['roomid'], 
                '', //Mobile
                '', //Invited
                '', //Avatarurl
                "$overwrite", "$onetimeflag", $ownerid,
                $termsofuse, $language, $timezone,$trackerid
            ); //Overwrite
            
            //Change Status to Success
            if($status){
                //echo "OK - ".$row['name']."<br>";
                pdo_query("1","update csvsignup set status='Y' where status='N' and id = $row[id]  ");
            } else {
                echo $this->DisplayErrors();
                  echo "Error - ".$row['name']."<br>";
                $errorcount++;
                pdo_query("1","update csvsignup set status='E', error='$this->error1' where status='N' and id = $row[id] ");
                
            }
            
        }
        echo "Create Account Errors $errorcount<br>";
        
    }
    
    

    function CreateAccount(
            $providerid, $providername, $replyemail, $replysms, $handle, $password, 
            $loginid,
            $enterprise, $industry, $companyname, $sponsor, $roomhandle, $roomid, $mobile, $invited,
            $avatarurl, $overwrite, $onetimeflag, $ownerid, $termsofuse, $language, $timezone, $trackerid )
    {
        global $rootserver;
        global $prodserver;
        
        $this->error1 = '';
        $this->errorcount = 0;
        $this->overwrite = $overwrite;
        unset($this->errormsg);       
        
        $this->timezone = $timezone;
        
        $this->iphash = hash("sha256", WhiteListCheck(2));
        $ip = WhiteListCheck(true);
        $this->ipsource = $ip;
        $useragent = tvalidator("PURIFY",$_SERVER['HTTP_USER_AGENT']);
        $this->iphash2 = hash("sha256",$ip.$useragent.$timezone);

        $this->trackerid = $trackerid;
        
        
        $this->password = $password;

        if( "$this->password"==""){
            $this->StoreError("No Password");
            return false;
        }

        $this->providerid = $providerid;
        if($providerid == ''){
            $this->providerid = $this->GetProviderid();
        }
        
        $this->replyemail = $replyemail;
        if($this->replyemail == ''){
            $this->replyemail = "$this->providerid".".account@brax.me";
        }
        $this->newemail = $this->replyemail;
        $this->onetimeflag = purify_string($onetimeflag);
        $this->termsofuse = purify_string($termsofuse);
        $this->language = strtolower(purify_string($language));
        if($this->language==''){
            $this->language = 'english';
        }
        
        $this->roomcreator = 'Y';
        $this->broadcaster = 'N';
        $this->storeowner = 'N';
        /*
        echo "
            Providerid $providerid<br>
            Providername = $providername<br>
            Email $replyemail<br>
            SMS $replysms<br>
            Handle $handle<br>
            Password $password<br>
                ";
        */
        
        $this->providername = htmlentities(ltrim(purify_string($providername)),ENT_QUOTES);
        $this->name2 = htmlentities(ltrim(purify_string($providername)),ENT_QUOTES);
        $this->replysms = purify_string($this->CleanPhone($replysms));
        $this->handle = purify_string($handle);
        $this->password = strtolower($password);
        $this->enterprise = purify_string($enterprise);
        $this->industry = purify_string($industry);
        $this->companyname = purify_string($companyname);
        $this->sponsor = purify_string($sponsor);
        $this->roomhandle = purify_string($roomhandle);
        $this->roomid = purify_string($roomid);
        $this->mobile = purify_string($mobile);
        $this->invited = purify_string($invited);
        $this->loginid = purify_string(strtolower( $loginid));
        //Hardcode for now
        $this->loginid = 'admin';
        $this->roomdiscovery = 'Y';
        $this->hardenter = '';

        //allow & in names after purify
        $this->providername = str_replace("&amp;","&",$this->providername);
        $this->companyname = str_replace("&amp;","&",$this->companyname);
        $this->name2 = str_replace("&amp;","&",$this->name2);
        
        if($this->onetimeflag == 'Y'){
            $this->eowner = $ownerid;
        }
        if($this->eowner ==''){
            $this->eowner = "null";
        }
        
        
        if( "$this->providername"==""){
            $this->StoreError("No Name Provided");
            return false;
        }
        
        
        if( $this->DuplicateAccountCheck()){
            return false;
        }
        if(!$this->ValidateHandle("")){
            return false;
        }

        
        if($loginid == ''){
            $this->loginid = 'admin';
        }
        $this->avatarurl = $avatarurl;
        //if($this->avatarurl == ''){
            $this->avatarurl = "$prodserver/img/faceless.png";
        //}
        
        $this->serverhost = $rootserver;
        $this->active = 'Y';
        $this->msgmasterkey = '';
        $this->newmsgurl = '';
        $this->dealeremail = '';
        $this->invitesource = '';
        $this->lifespan = 0;
        $this->accountnote = '';
        $this->autosendkey = 'Y';
        $this->inactivitytimeout = 0;
        $this->notifications = 'Y';
        
        $this->allowtexting = 'Y';
        $this->dealer = '';
        $this->contractperiod = 0;
        $this->contracttype = 'Trial';
        
        
        $this->invitedemail = '';
        $this->invitedsource = '';
        $this->msglifespan = 86400;
        $this->roomfeed = 'Y';
        
        
        $this->verified = 'N';
        if($this->invited == 'Y'){
            $this->invitedemail = $this->replyemail;
            $this->verified = 'Y';
        }
        
        if($this->industry==''){
            $this->industry = 'personal';
        }
        if($this->enterprise==''){
            $this->enterprise = 'N';
        }
        
        if($this->industry=='enterprise'){
            $this->enterprise = 'Y';
            $this->msglifespan = 0;
        }
        if($this->industry=='commercial'){
            $this->enterprise = 'C';
            $this->msglifespan = 0;
        }
        
        $this->IsSponsoredNew();
        
        
        if( !$this->ErrorCheck()){
            echo $this->DisplayErrors();
            return false;
        }

        $this->SaveAccount();
        return true;
        
        
    }
    function SaveAccount()
    {
        global $appname;
        
        /*Temporary while debugging IsSponsorPartitioned -- not working */
        if($this->enterprise=='Y'){
            $this->sponsorlist = 'Y';
            $this->sponsor = "";
            $this->roomhandle = "";
            $this->roomfeed = 'N';
            $this->roomcreator = 'Y';
            $this->broadcaster = 'Y';
            $this->store = 'N';
            $this->web = 'Y';
        }
        
        //Errorcleanup
        if($this->roomhandle == '#000000000' ||
           $this->roomhandle == '#undefined' ){
            $this->roomhandle = '';
        }
        
        $result = pdo_query("1", 
            "insert into provider 
            ( newbie, providerid, createdate, providername, name2, 
             companyname, handle, active, 
             replyemail, loginid, 
             avatarurl, enterprise, industry,  
             inactivitytimeout, verified, verifiedemail, proxy,
             invitesource, featureemail, notifications, publish,
             contractperiod, contracttype, dealer, dealeremail, 
             allowrandomkey, cookies_recipient, cookies_sender, allowkeydownload, 
             serverhost, allowtexting, msglifespan, sponsor, roomdiscovery, member, eowner, sponsorlist,
             colorscheme, language, joinedvia, appname, iphash, iphash2, timezone, ipsource, trackerid,
             roomcreator, broadcaster, web, store, hardenter
             ) values (
              'Y',
              ?,now(),?,?,
              ?,?,?,
              ?,?,
              ?,?,?,
              ?,?,?,'N',
              ?,?,?,?,
              ?,?,?,?,
              'N', 'N','Y', 'Y',
              ?,?,?,
              ?,?,?,?,?,
              'std',?,?,'$appname',?,?,?,?,
              ?,?,?,?,?,?,
            )",array(
              $this->providerid, $this->providername, $this->name2, 
              $this->companyname, $this->handle, $this->active,  
              $this->replyemail, $this->loginid, 
              $this->avatarurl, $this->enterprise, $this->industry, 
              $this->inactivitytimeout, $this->verified, $this->invitedemail,
              $this->invitesource,$this->invitesource,$this->notifications, $this->publish,
              $this->contractperiod,  $this->contracttype,$this->dealer,$this->dealeremail,
              $this->serverhost,  $this->allowtexting, $this->msglifespan,
              $this->sponsor, $this->roomdiscovery, $this->onetimeflag, $this->eowner, $this->sponsorlist,
              $this->language, $this->roomhandle,$appname,$this->iphash, $this->iphash2, $this->timezone', '$this->ipsource,
              $this->trackerid,$this->roomcreator,$this->broadcaster, $this->web, $this->store, $this->hardenter
                
            )
        );
        if(!$result){
            return false;
        }
        if($this->termsofuse=='Y'){
            pdo_query("1","update provider set termsofuse = now() where providerid=? ",array($this->providerid));
        }
        
        $result = pdo_query("1", 
          " delete from staff where providerid=? and loginid=? ",array($this->providerid,$this->loginid));

        $result = pdo_query("1", 
          " insert into staff (providerid, loginid, adminright, emailalert, workgroup, staffname, active, email) values  " .
          " (?, ?, 'Y',  'Y', 'admin', ?,'Y', ? )",
                array(
                    $this->providerid,$this->loginid,$this->providername,$this->replyemail
                    
                ));
    
        if( "$this->password"!=""){
        
            $pwd_hash = password_hash($this->password, PASSWORD_DEFAULT);
            $result = pdo_query("1", 
             " 
                update staff set 
                pwd_ver = 3, 
                pwd_hash = ?,
                fails = 0,
                onetimeflag=?
                where providerid=? and loginid=? 

             ",array($pwd_hash,$this->onetimeflag,$this->providerid,$this->loginid));
        }
    
        //Create encrypted SMS
        pdo_query("1","
            delete from sms where providerid = ?
            ",array($this->providerid));
    
        if($this->replysms!=''){
            $sms_encrypted = EncryptText($this->replysms, $this->providerid);
            pdo_query("1","
                insert into sms (providerid, sms, encoding ) values 
                (
                    ?, ?,'$_SESSION[responseencoding]'
                )
            ",array($this->providerid, $sms_encrypted));
        }

    
        if( $this->enterprise == 'Y'){
        
            $result = pdo_query("1", 
              " 
                  insert into msgplan (providerid, planid, datestart, dateend, count, active, created )
                  values (?, 'STD',now(), now(), 1, 'N', now() )
              ",array($this->providerid));

        }        
        //Invitation - Add Email if Missing so invite can be sent with SMS only
        if($this->replysms!='+1' && $this->replysms!='' && $this->invited=='Y'){
        
            $result = pdo_query("1", 
                "
                update invites set email = ? where sms=?
                and email = '' and status='Y'
                ",array($this->replyemail,$this->replysms)
              );
        }
        //Invitation Code
        if( $this->roomid !==''){
        
            if( AddMember( 0, $this->providerid, $this->roomid )
            ){
            }

        } else 
        if( $this->roomhandle !==''){
        
            $result = pdo_query("1", 
                "
                select roomid, 
                (select owner from statusroom where roomhandle.roomid = statusroom.roomid limit 1 ) as owner
                from roomhandle where handle=?
                ",array($this->roomhandle)
              );
            if($row = pdo_fetch($result)){
            
                $roomid = $row['roomid'];
                $owner = $row['owner'];

                if( AddMember( 0, $this->providerid, $roomid)
                ){
                }
                
                /*
                $inviteid = base64_encode(uniqid("$owner"));

                $result = pdo_query("1", 
                    "
                    insert into invites 
                    (providerid, name, email, status, invitedate, 
                    roomid, contactlist, sms, retries, retrydate, chatid, inviteid )
                    values
                    ($owner, '$this->providername','$this->replyemail','Y', now(),
                    $this->roomid, '','',0, null, null, '$inviteid' )
                    "
                  );
                $result = pdo_query("1","
                    insert into contacts (providerid, contactname, email, friend, imapbox, blocked ) values
                    ($owner, '$this->providername', '$this->replyemail', '', null,''  )
                ");
                 * 
                 */

            }

        }
        
        pdo_query("1","insert into handle (handle, email, providerid) values (?,?,?) ",
                array($this->handle,$this->replyemail,$this->providerid));
        $result = pdo_query("1","select * from provider where providerid = ? and active='Y' ",array($this->providerid));
        if($row = pdo_fetch($result)){
            $this->SendSignUpEmail();
        }
        
        
    }
    function EditAccount(
            $providerid, $providername, $name2, $replyemail, $replysms, $handle,  
            $alias, $positiontitle, $active, $sponsor,
            $enterprise, $industry, $companyname, $enable_email, 
            $notifications, $notificationflags,
            $publish, $publishprofile, $roomdiscovery, $streamingaccount, $welcome, $sponsorlist,
            $inactivitytimeout, $pin, $colorscheme, $gift, $wallpaper, $hardenter )
    {
        $result = pdo_query("1","
            select handle, replyemail, verified from provider where providerid = ?
                ",array($providerid));
        if($row = pdo_fetch($result)){
            $this->orig_handle = $row['handle'];
            $origemail = $row['replyemail'];
            $origverified = $row['verified'];
        }
        $loginid = 'admin';
        
        $this->error1 = '';
        $this->errorcount = 0;
        $this->errormsg = null;
        $this->password = "dummy";
        $this->pin = $pin;

        $this->providerid = $providerid;
        $this->providername = ltrim($providername);
        if( "$this->providername"==""){
            $this->StoreError("No Name Provided");
            return false;
        }
        
        
        $this->name2 = ltrim($name2);
        $this->alias = ltrim($alias);
        $this->positiontitle = ltrim($positiontitle);
        //$this->replyemail = $replyemail;
        $this->replyemail = $origemail;
        $this->newemail = "";
        if($origemail!=$replyemail){
            if($replyemail == ''){
                $replyemail = "$this->providerid".".account@brax.me";
            }
            $this->newemail = $replyemail;
        }
            
        $this->replysms = $this->CleanPhone($replysms);
        $this->handle = $handle;
        if( "$this->handle"=="" || "$this->handle"=="@"){
            $this->StoreError("No Handle Provided");
            return false;
        }
        
        $this->enterprise = $enterprise;
        if($this->enterprise=='Y'){
            $this->roomcreator = 'Y';
            $this->broadcaster = 'Y';
        }
        
        
        $this->industry = $industry;
        $this->companyname = $companyname;
        $this->loginid = strtolower( $loginid);
        
        $result = pdo_query("1","select superadmin from provider where providerid = ? ",array($this->providerid));
        if($row = pdo_fetch($result)){
            $this->superadmin = $row['superadmin'];
        }
        
        $this->enable_email = $enable_email;
        if($notifications!='Y'){
            $notifications = 'N';
        }
        $this->notifications = $notifications;
        $this->notificationflags = $notificationflags;
        $this->publish = $publish;
        $this->publishprofile = $publishprofile;
        $this->roomdiscovery = $roomdiscovery;
        $this->streamingaccount = $streamingaccount;
        $this->verified = $origverified;
        //$this->defaultsmtp = $defaultsmtp;
        $this->active = $active;
        $this->sponsorlist = $sponsorlist;
        if($inactivitytimeout >= 0 && $inactivitytimeout <= 7200 ){
            $this->inactivitytimeout = $inactivitytimeout;
        } else {
            $this->inactivitytimeout = 0;
        }
        
        if($this->active == 'N'){
            $this->verified = 'Y';
        }

        if(!$this->ValidateHandle($this->providerid)){
            return false;
        }
        
        //Parse Sponsor
        $sponsorhold = explode("/", $sponsor);
        $sponsor1 = $sponsorhold[0];
    
        if( count($sponsorhold)>1){
            if($sponsorhold[1]=='Y'){
                $this->enterprise = 'Y';
            }
            if($sponsorhold[1]=='N'){
                $this->enterprise = 'N';
            }
        }
        $this->sponsor = $sponsor1;
        if($this->sponsor == '' && $this->enterprise =='N' ){
            $this->roomdiscovery = 'Y';
        }
        
        if($this->industry==''){
            $this->industry = 'personal';
        }
        
        if($this->enterprise=='N'){
            $this->msglifespan = 86400;
        } else {
            $this->msglifespan = 0;
        }
        
        $this->welcome = $welcome;
        
        $this->colorscheme = $colorscheme;
        if($this->colorscheme == ''){
            $this->colorscheme = 'std';
        }
        $this->gift = $gift;
        $this->wallpaper = $wallpaper;
        if($hardenter=='Y'){
            $hardenter = '';
        }
        $this->hardenter = $hardenter;
        $this->IsSponsoredEdit();
        
        if( !$this->ErrorCheck()){
            //echo $this->DisplayErrors();
            return false;
        }
        
        $this->SaveEditAccount();
        //$this->SendSignUpEmail();
        return true;
        
        
    }    
    function SaveEditAccount()
    {
        
        
        $result = pdo_query("1", 
          " update provider " .
          " set verified=?, providername= ?, ".
          " name2=?, companyname= ?, handle=?, ".
          " replyemail=?, alias = ?, positiontitle=?, " .
          " industry=?, msglifespan=?, ".
          " active=?, featureemail=?, ".
          " notifications=?, notificationflags=?, ".
          " inactivitytimeout=?, ".
          " sponsor=?, gift=?,  ".
          " publish=?, publishprofile=?, roomdiscovery=?,  ".      
          " streamingaccount=?, sponsorlist=?, hardenter=? ".
          " where providerid=?",
                array($this->verified,$this->providername,
                    $this->name2,,$this->companyname,,$this->handle,
                    $this->replyemail,$this->alias,$this->positiontitle,
                    $this->industry,$this->msglifespan,
                    $this->active,$this->enable_email,
                    $this->notifications,$this->notificationflags,
                    $this->inactivitytimeout,
                    $this->sponsor,$this->gift,
                    $this->publish,$this->publishprofile,$this->roomdiscovery,
                    $this->streamingaccount,$this->sponsorlist,$this->hardenter,
                    $this->providerid
                  )
          );
        
        //Create encrypted SMS
        pdo_query("1","
            delete from sms where providerid = $this->providerid
            ",array($this->providerid
                    ));

        if($this->replysms!=''){
            $sms_encrypted = EncryptText($this->replysms, $this->providerid);
            //$sms_decrypted = DecryptText($sms_encrypted, $_SESSION['responseencoding'], $providerid);
            pdo_query("1","
                insert into sms (providerid, sms, encoding ) values 
                (
                    ?, ?,'$_SESSION[responseencoding]'
                )
            ",array($this->providerid,$sms_encrypted));
        }
        
        pdo_query("1","delete from timeout where providerid = ? ",array($this->providerid));

        if($this->pin!=''){
            pdo_query("1","insert into timeout (providerid, pin, encoding ) values (?, ?, 'PLAINTEXT' ) ",
                    array($this->providerid,$this->pin));

        }
        
        $result = pdo_query("1",
            "
            update contacts set handle =?, targetproviderid=?  where handle=? and ? !=''
            ",array($this->handle,$this->providerid,$this->orig_handle,$this->orig_handle)
        );

        $result = pdo_query("1", 
            " update staff set staffname=? where loginid='admin' and providerid=? " 
          ,array($this->providername,$this->providerid));
        
        //Account Termination Cleanup
        if($this->active == 'N'){
            pdo_query("1","delete from invites where email=? and chatid is not null ",array($this->replyemail));
            pdo_query("1","delete from handle where providerid = ?  ",array($this->providerid));
             
            pdo_query("1","update iphash set icount = icount-1 where iphash = (select iphash2 from provider where providerid = ? ) ",array($this->providerid));
             
        }
         
        if($this->enterprise != ''){
            pdo_query("1","update provider set enterprise = ? where providerid = ? ",array($this->enterprise,$this->providerid));
        }
         
         //Check Email
         if($this->replyemail!=$this->newemail && $this->newemail!='' && $this->replyemail!=''){
            $this->SendChangedEmail();
             
            $result = pdo_query("1",
                "
                update provider set replyemail = ?, verified='N', verifiedemail='' where providerid = ? and active='Y'
                ",array($this->newemail,$this->providerid)
            );
            $result = pdo_query("1",
                "
                update staff set email = ? where providerid = ? and "
                    . "email=? and active='Y'
                ",array($this->newemail,$this->providerid,$this->replyemail)
            );
            $result = pdo_query("1",
                "
                update appidentity set replyemail = ? where  replyemail=?
                ",array($this->newemail,$this->replyemail)
            );
            $result = pdo_query("1",
                "
                update invites set email = ? where  email=?
                ",array($this->newemail,$this->replyemail)
            );
            $result = pdo_query("1",
                "
                update contacts set email = ?, targetproviderid=? where  email=?
                ",array($this->newemail,$this->providerid,$this->replyemail)
            );
            $result = pdo_query("1",
                "
                delete from verification where email=?
                ",array($this->replyemail)
            );
        }
        if( $this->enterprise == 'Y'){
            $result = pdo_query("1", "select * from msgplan where providerid = ? ",array($this->providerid));
            if(!$row = pdo_fetch($result)){

                $result = pdo_query("1", 
                  " 
                      insert into msgplan (providerid, planid, datestart, dateend, count, active, created )
                      values (?, 'STD',now(), now(), 1, 'N', now() )
                  ",array($this->providerid));

            } else {
                pdo_query("1","update msgplan set active='N' where active='F' and providerid = ? ",array($this->providerid));

            }


        }   
            
             
    }
        
    function IsSponsoredNew()
    {
        
        $industry = "";
        $partitioned = "";
        //If not enterprise - see if sponsor requires partitioning
        $result = pdo_query("1", 
          " select partitioned, industry from sponsor where sponsor = ? ",array($this->sponsor) 
          );
        if($row = pdo_fetch($result)){
            $partitioned = $row['partitioned'];
            $industry = $row['industry'];
            
        } 
        

        if($this->enterprise == 'Y'){
            
            $this->roomdiscovery = 'N';
            $this->publish = ''; //""
            $this->sponsorlist ='Y';
            $this->industry = "";
            $this->roomhandle = '';
            
            
        } else {
            //Non Enterprise- Default to Public
            $this->roomdiscovery = 'Y';
            $this->publish = 'Y'; //"Y"
            $this->industry = "";
            
        }
        
        
        //Commercial Accounts are not Partitioned
        if($this->enterprise == 'C'){
            return;
        }

        if($this->sponsor == ''){
            return;
        }
        
        $this->industry = $industry;
        
        if($this->enterprise!='Y'){
            if($partitioned=='Y'){

                $this->roomdiscovery = 'N';
                $this->publish = '';
            } else {
                $this->roomdiscovery = 'Y';
                $this->publish = 'Y';

            }
        }
        if($customsite){
            $this->roomdiscovery = 'N';
            $this->publish = 'N'; //"Y"
        }
        

        return;
        
        
    }    
    function IsSponsoredEdit()
    {
        if($_SESSION['superadmin']=='Y'){
            return;
        }
        if($this->sponsor ==''){
            return;
        }
        $industry = "";
        $partitioned = "";
        $roomdiscovery = $this->roomdiscovery;
        //If not enterprise - see if sponsor requires partitioning
        $result = pdo_query("1", 
          " select partitioned, industry from sponsor where sponsor = ? ",array($this->sponsor) 
          );
        if($row = pdo_fetch($result)){
            $partitioned = $row['partitioned'];
            $industry = $row['industry'];
            
            if($partitioned=='Y'){
                $roomdiscovery = 'N';
            }
            
        } 
        
        $this->industry = $industry;
        //$this->roomdiscovery = $roomdiscovery;
        

        return;
        
        
    }        
    function GetProviderid()
    {
        $providerid = 0;
        $result = pdo_query("1",
            "select max(val1)+1 as maxid from parms where parmkey='SUBSCRIBER' AND PARMCODE='ID' "
        );
        if( $row = pdo_fetch($result)){
            
            $providerid =$row['maxid'];
        }


        $result = pdo_query("1", "select max(providerid)+1 as providerid from provider ");
        if( $row = pdo_fetch($result)){
            
            $highid = $row['providerid'];
        }

        if( $providerid == 0 ){
            
            $result = pdo_query("1", "insert into parms (parmkey, parmcode, val1, val2 ) values ('SUBSCRIBER','ID', $highid, 0 )");
        }

        if( $highid > $providerid){
            
            $providerid = $highid;
        }

        $result = pdo_query("1", "update parms set val1 = ? where parmkey='SUBSCRIBER' and parmcode='ID' ",array($providerid));

        pdo_query("1","delete from handle where providerid=? ",array($providerid));
        pdo_query("1","insert into handle (handle, email, providerid) values (?, ?,?) ",array($this->handle,$this->replyemail,$this->providerid));
        
        
        return $providerid;

        
    }    
    function StoreError($error)
    {
        if($this->error1 == ''){
            $this->error1 = "$error";
        }
        $this->errormsg[] = $error;       
        $this->errorcount++;
        return false;
    }

    function ErrorCheck()
    {
        $error = true;
        if( $this->providername == "" ){
        
            $error = $this->StoreError("Missing Subscriber Name");
        }
        if( $this->password == "" ){
        
            $error = $this->StoreError("Missing Password  ");
        }
        
        /*
        if( $this->pin !== ""  && $this->providerid == $admintestaccount){
        
            $error = $this->StoreError("Pin Problem $this->pin");
        }
         * 
         */
        
        if( $this->superadmin!='Y' && stristr($this->providername,"brax")!==FALSE 
                && stristr($this->providername,"braxtv")===FALSE 
                && stristr($this->providername,"braxdemo")===FALSE 
                && stristr($this->providername,"braxuser")===FALSE 
                && stristr($this->providername,"braxman")===FALSE 
                && stristr($this->providername,"braxton")===FALSE ){
            $error = $this->StoreError("Restricted Name - Violation of Terms of Use");
        }
        if( $this->superadmin!='Y' && stristr($this->name2,"brax")!==FALSE 
                && stristr($this->providername,"braxtv")===FALSE 
                && stristr($this->providername,"braxdemo")===FALSE 
                && stristr($this->providername,"braxuser")===FALSE 
                && stristr($this->providername,"braxman")===FALSE 
                && stristr($this->providername,"braxton")===FALSE ){
            $error = $this->StoreError("Restricted Alt Name - Violation of Terms of Use");
        }
        if( $this->superadmin!='Y' && stristr($this->alias,"brax")!==FALSE 
                && stristr($this->providername,"braxtv")===FALSE 
                && stristr($this->providername,"braxdemo")===FALSE 
                && stristr($this->providername,"braxuser")===FALSE 
                && stristr($this->providername,"braxman")===FALSE 
                && stristr($this->providername,"braxton")===FALSE ){
            $error = $this->StoreError("Restricted Alias - Violation of Terms of Use");
        }
         
         
        /*
        if( stristr($this->providername,"brax")!==FALSE ){
            $error = $this->StoreError("Restricted Name - Violation of Terms of Use");
        }
        
        if( strstr($this->name2,"brax")!==FALSE ){
            $error = $this->StoreError("Restricted Name - Violation of Terms of Use");
        }
        if( strstr($this->alias,"brax")!==FALSE ){
            $error = $this->StoreError("Restricted Name - Violation of Terms of Use");
        }
        */
    
        if( $this->providerid == ""  || !is_numeric($this->providerid)){
        
            $error = $this->StoreError("Invalid Account");
        }
        if (filter_var($this->replyemail, FILTER_VALIDATE_EMAIL)===false){
        
            $error = $this->StoreError("Invalid Email [$this->replyemail]");
        }        
        if ($this->newemail !=='' && filter_var($this->newemail, FILTER_VALIDATE_EMAIL)===false){
        
            $error = $this->StoreError("Invalid New Email [$this->newemail]");
        }        
        if ($this->newemail != $this->replyemail && $this->newemail!='' &&
            !$this->EmailCheck() ){
            
            $error = $this->StoreError("Duplicate New Email [$this->newemail]");
        }
        if ($this->enterprise == 'Y' && $this->replyemail === "$this->providerid".".account@brax.me" ){
            
            $error = $this->StoreError("Email Required for Enterprise Accounts [$this->newemail]");
        }
        

        return $error;
    
    }
    function DisplayErrors()
    {
        $errortext = "";
        foreach( $this->errormsg as $erroritem){
        
            $errortext .= "$erroritem<br>";
        }
        return $errortext;
    }
    function StoreMessage($msg)
    {
        $this->displaymsg[] = $msg;       
        return true;
    }
    
    function DisplayMessages()
    {
        $displaytext = "";
        if(isset($this->displaymsg)){
        
            foreach( $this->displaymsg as $displayitem){

                $displaytext .= "$displayitem<br>";
            }
        }
        return $displaytext;
    }
    
    function GetErrorCount()
    {
        return $this->errorcount;
    }
    function GetNewProviderId()
    {
        return $this->providerid;
    }
    function ValidateHandle($providerid)
    {
        if($this->handle!='' && strstr($this->handle,'@')===false ){
            return false;
        }
        
        $this->handle = str_replace("@","",$this->handle);
        $this->handle = str_replace("'","",$this->handle);
        $this->handle = str_replace('"',"",$this->handle);
        $this->handle = str_replace('%',"",$this->handle);
        $this->handle = "@".strtolower(str_replace(" ","",$this->handle));
        if($this->handle == '@'){
            $this->handle = '';
            return true;
        }
        //$this->handle = preg_replace('/[^\da-z0-9]/i', '', $this->handle);        
        
        if($providerid != null && $providerid!= ''){
            $result = pdo_query("1","select handle from provider where handle=? and "
                    . "providerid!= ? and active='Y' ",array($this->handle,$providerid));
            if($row = pdo_fetch($result)){
                return false;
            }
            return true;
            
        }
        $banned = strstr($this->handle,"braxme");
        if($banned!== false){
            return false;
        }
        $banned = strstr($this->handle,"robmusic");
        if($banned!== false){
            return false;
        }
        $banned = strstr($this->handle,"whatthezuck");
        if($banned!== false){
            return false;
        }
        
        if($this->handle!=='@' && $this->handle!==''){

            /*
            $result = pdo_query("1","select handle from handle where handle='$this->handle' ");
            if($row = pdo_fetch($result)){

                $this->handle = $row['handle'];
            } else {
                $this->StoreError("Duplicate Handle Error");
                //error
                return false;

            }
             * 
             */
            $result = pdo_query("1","select handle from provider where handle=? and active = 'Y' ",array($this->handle));
            if($row = pdo_fetch($result)) {
                $this->StoreError("Duplicate Handle Error");
                return false;

                //error
            }
        }
        return true;
            
    }
    function CleanPhone( $phone )
    {
        $phone = str_replace( "(", "", $phone );
        $phone = str_replace( "/", "", $phone );
        $phone = str_replace( ")", "", $phone );
        $phone = str_replace( " ", "", $phone );
        $phone = str_replace( "-", "", $phone );
        $phone = str_replace( ".", "", $phone );
        
        if( $phone!='' && $phone[0]!='+'){
            $phone = "+1".$phone;
        }
        
        return $phone;
    }
    function FormatPhone( $phone )
    {
        $area = substr( $phone, 0, 3);
        $num1 = substr( $phone, 3, 3);
        $num2 = substr( $phone, 6, 4);
        
        if( $area == ''){
            return "";
        }

        
        return "(".$area.") ".$num1."-".$num2;
    }
    
    function DuplicateAccountCheck()
    {
        if($this->replyemail == ''){
            return false;
        }
        
        $result = pdo_query("1",
                "select providerid from provider where replyemail=? and active='Y' ",array($this->replyemail)
                );
        if( $row = pdo_fetch($result)){
            if($this->overwrite == 'Y'){
                $this->InactivateDuplicateAccount();
                return false;
            }
            $this->StoreError("Duplicate account");
            return true;
        }
        
        
        return false;
    }    
    function EmailCheck()
    {
        
        $result = pdo_query("1",
                "select providerid from provider where replyemail=? and active='Y' ",array($this->newemail)
                );
        if( $row = pdo_fetch($result)){
            return false;
        }
        
        
        return true;
    }   
    function InactivateDuplicateAccount()
    {
        
        
        $result = pdo_query("1",
                "update provider set active='N' where replyemail=? and active='Y' "
                ,array($this->replyemail));
        return true;
    }    
    
    function VerifyAccountCheck( ){
        
        
        $result = pdo_query("1",
                "select providerid from provider where providerid=? and active='Y' "
                ,array($this->providerid));
        if( $row = pdo_fetch($result)){
            
            return true;
        }
        
        return false;
    }    
    function InviteProcess($inviteid)
    {
        if($inviteid==''){
            return true;
        }
        $result = pdo_query("1",
                "update invites set email=? where inviteid=? "
                ,array($this->replyemail,$inviteid));
        
        return true;
    }    
    
    
    function SendSignUpEmail()
    {
        global $appname;
        global $rootserver;
        global $installfolder;
        global $prodserver;
        
        //Fake email so don't send signup message
        if(strstr($this->replyemail,".account@brax.me")!==false){
            return;
        }
        //OWASP ZAP Fake email so don't send signup message
        if(strstr($this->replyemail,"@example.com")!==false){
            return;
        }
        
        $signupverificationkey = uniqid("", true);
        pdo_query("1", 
                "insert into verification (type, providerid, verificationkey, loginid, email, createdate ) values (".
                " 'ACCOUNT',?, ?, 'admin', ?, now() ) "
                ,array($this->providerid,$signupverificationkey,$this->replyemail));
        
        $message = 
                "<html><body>".
                "<br><br><b>Thank You for signing up with $appname.</b><br><br>".
                "One final step: We need to verify your identity and your control of this email address.<br>".
                "If you skip this step, you will not be able to change your password and you may lose access to your account.<br>".
                "PLEASE CLICK LINK BELOW to verify this email address.<br><br>".
                "<a href='$rootserver/$installfolder/verify.php?i=$signupverificationkey'>".
                "$rootserver/$installfolder/verify.php?i=$signupverificationkey</a> <br><br>".
                "(Cut and paste link to browser if you cannot click it)<br><br>".
                "<a href='$prodserver'>".
                "<img src='$rootserver/img/logo-b1.png' style='height:30px; width: auto' height='30' width=auto>".
                "</a><br><br><br>".
                "</body></html>";
        
        $messagealt = 
                "Thank You for signing up with $appname.\r\n\r\n".
                "One final step: We need to verify your identity and your control of this email address.\r\n".
                "If you skip this step, you will not be able to change your password and you may lose access to your account.\r\n".
                "PLEASE cut and paste the link below to your browser (or click it if you are able)\r\n\r\n".
                "$rootserver/$installfolder/verify.php?i=$signupverificationkey \r\n".
                "(Cut and paste link to browser if you cannot click it)\r\n\r\n".
                "\r\n\r\n$prodserver\r\n\r\n";

        $braxemail = "rob@bytz.io";
        
        SendMail("0", "Verify your Email", "$message", "$messagealt", 
                "$this->providername", "$this->replyemail" );
        //SendMail( "0", "Signup", "$message", "$message",
        //        "$this->providername", "$braxemail" );
                
    }

    function SendChangedEmail()
    {
        global $appname;
        global $rootserver;
        global $installfolder;
        global $prodserver;
        
        //Fake email so don't send signup message
        if(strstr($this->replyemail,".account@brax.me")!==false){
            return;
        }
        if(strstr($this->replyemail,".accounts@brax.me")!==false){
            return;
        }
        if(strstr($this->replyemail,"@account.brax.me")!==false){
            return;
        }
        if(strstr($this->replyemail,"@accounts.brax.me")!==false){
            return;
        }
        //OWASP ZAP Fake email so don't send signup message
        if(strstr($this->replyemail,"@example.com")!==false){
            return;
        }
        $signupverificationkey = uniqid("", true);
        pdo_query("1", 
                "insert into verification (type, providerid, verificationkey, loginid, email, createdate ) values (".
                " 'ACCOUNT', ?, ?, 'admin', ?, now() ) "
                ,array($this->providerid,$signupverificationkey,$this->newemail));
        
        $message = 
                "<html><body>".
                "<br><br><b>Your email address on $appname has been changed.</b><br><br>".
                "If you did this, please verify your account by clicking on the link below.<br><br>".
                "If you did not do this, your account has been breached and you need to <br>".
                "contact us at<br>".
                "techsupport@brax.me.<br><br>".
                "<a href='$prodserver'>".
                "<img src='$rootserver/img/logo-b1.png' style='height:30px; width: auto' height='30' width=auto >".
                "</a><br><br><br>".
                "</body></html>";
        
        $messagealt = 
                "Your email address on $appname has been changed.\r\n\r\n".
                "If you did this, then you can ignore this message. If you did not do this\r\n".
                "your account has been breached and you need to contact us at\r\n".
                "techsupport@brax.me\r\n\r\n".
                "\r\n\r\n$prodserver\r\n\r\n";
        
        
        SendMail("0", "$appname Email Changed", "$message", "$messagealt", 
                "$this->providername", "$this->replyemail" );
        
        $message = 
                "<html><body>".
                "<br><br><b>You changed your email on $appname.</b><br><br>".
                "One final step: We need to verify your identity and your control of this email address.<br>".
                "If you skip this step, you will not be able to change your password and you may lose access to your account.<br>".
                "PLEASE CLICK LINK BELOW to verify this email address.<br><br>".
                "<a href='$rootserver/$installfolder/verify.php?i=$signupverificationkey'>".
                "$rootserver/$installfolder/verify.php?i=$signupverificationkey</a> <br><br>".
                "(Cut and paste link to browser if you cannot click it)<br><br>".
                "<a href='$prodserver'>".
                "<img src='$rootserver/img/logo-b1.png' style='height:30px; width: auto'  height='30' width=auto>".
                "</a><br><br><br>".
                "</body></html>";
        
        $messagealt = 
                "You changed your email on $appname.\r\n\r\n".
                "One final step: We need to verify your identity and your control of this email address.\r\n".
                "If you skip this step, you will not be able to change your password and you may lose access to your account.\r\n".
                "PLEASE cut and paste the link below to your browser (or click it if you are able)\r\n\r\n".
                "$rootserver/$installfolder/verify.php?i=$signupverificationkey \r\n".
                "(Cut and paste link to browser if you cannot click it)\r\n\r\n".
                "\r\n\r\n$prodserver\r\n\r\n";

        $braxemail = "rob@bytz.io";
        
        SendMail("0", "Verify your Email", "$message", "$messagealt", 
                "$this->providername", "$this->newemail" );
        //SendMail( "0", "Changed Email", "$message", "$message",
        //        "$this->providername", "$braxemail" );
                
    }
    
    
}



?>
