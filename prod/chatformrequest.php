<?php
session_start();
require_once("config-pdo.php");
require_once("password.inc.php");
require_once("crypt-pdo.inc.php");

$providerid = @tvalidator("PURIFY",$_SESSION['pid']);
$mode = @tvalidator("PURIFY",$_POST['mode']);
$chatid = @tvalidator("PURIFY",$_POST['chatid']);
$roomid = @tvalidator("PURIFY",$_POST['roomid']);
$formid = @tvalidator("PURIFY",$_POST['formid']);
$passkey64 = @tvalidator("PURIFY",$_POST['passkey64']);

    $result = pdo_query("1","select keyhash from chatmaster where chatid = ? ",array($chatid));
    $keyhash = '';
    while($row = pdo_fetch($result)){
        $keyhash = $row['keyhash'];
    }


if( $mode == 'A'){
    
    if(intval($chatid)>0){
        $result = pdo_query("1","select providerid from chatmembers where chatid=? and providerid!=? ",array($chatid,$providerid));
    }
    if(intval($roomid)>0){
        $result = pdo_query("1","insert into roomforms (roomid, formid ) values (?, ?) ",array($roomid, $formid));
        
        
        $result = pdo_query("1","select providerid from statusroom where roomid=? and providerid!=? ",array($roomid, $formid));
    }
        
    $count = 0;
    
    
    while($row = pdo_fetch($result)){
        
        $count++;
        
        pdo_query("1"," 
        update credentialformrequest set status='Y' where providerid = $row[providerid] and formid=? and status='N' 
            ",array($formid));
        
        
        pdo_query("1"," 
        insert ignore into credentialformtrigger (providerid, formid, created, status ) values 
        ($row[providerid], ?, now(), 'N')
            ",array($formid));

        /*If form is re-requested, invalidate signature */
        pdo_query("1"," 
        update credentials set submitted = null where formid = ? and providerid = $row[providerid] 
            ",array($formid));
        
        
        pdo_query("1"," 
        insert ignore into credentialformrequest (providerid, formid, created, status, requestor ) values 
        ($row[providerid], ?, now(), 'N', ?)
            ",array($formid, $providerid));
        
        
    }
    if($count > 0){

        $result = pdo_query("1","select formname from credentialform where formid=? ",array($formid));
        $row = pdo_fetch($result);
        $formname = $row['formname'];
        
        
        if(intval($chatid)>0){
            $message = "Form '$formname' Requested from $count members";
            $messageshort = "Form Requested";
            $passkey = DecryptE2EPasskey($passkey64,$providerid);
            $encode = EncryptChat ($message,"$chatid","$passkey" );
            $encodeshort = EncryptChat ($messageshort,"$chatid","" );
            $result = pdo_query("1",
            "
                insert into chatmessage ( chatid, providerid, message, msgdate, encoding, status, loginid)
                values
                ( ?, ?, \"$encode\", now(), '$_SESSION[responseencoding]', 'Y','$_SESSION[loginid]' );
            ",array($chatid,$providerid));
        }
        echo "Form request sent to $count members";
    }

    
    
    exit();
}
?>

    <div class='gridnoborder' style='background-color:<?=$global_titlebar_color?>;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;' >
        <img class='icon15 setchatsession tapped' data-keyhash='<?=$keyhash?>' data-chatid='<?=$chatid?>' data-passkey64='<?=$passkey64?>' Title='Back to Home' src='../img/Arrow-Left-in-Circle-White_120px.png' 
            style='' />
        &nbsp;
        <span class='pagetitle2a' style='color:white'>Send a Form Request</span> 
    </div>
    <div class="showtable" style='margin:0;height:3000px;width:auto;background-color:<?=$global_background?>;color:<?=$global_textcolor?>'>
        
        <table style='margin-left:20px;margin-right:20px;background-color:<?=$global_background?>;color:<?=$global_textcolor?>' >

        <tr>
        <td class='dataarea'>
            <div class="pagetitle2" style='color:<?=$global_textcolor?>'>Form Selection</div>
            <br><br>
        </td>
        </tr>
            
<?php
    $result = pdo_query("1","
                select credentialform.formname, credentialform.formid 
                from credentialform
                where
                ( 
                  owner = ? or owner = 0 or 
                  credentialform.industry in (select industry from sponsorforms where sponsor= '$_SESSION[sponsor]') 
                )
                order by formname asc
               ",array($providerid));
    while($row = pdo_fetch($result)){
        echo "
        <tr>
        <td class='dataarea'>
            <input class='formrequestadd' type='checkbox' data-mode='S' data-passkey64='$passkey64' data-formid='$row[formid]' data-chatid='$chatid' style='float:left'/>
                
            <div style='float:left'>
            <img class='icon20' src='../img/formrequest2.png' style='' />
            $row[formname]
            </div>
        </td>
        </tr>
        ";
        
    }

?>
        
        <tr>
        <td class='dataarea'>
            <div class='setchatsession' style='float:left'  data-keyhash='<?=$keyhash?>'  data-chatid='<?=$chatid?>' data-passkey64='<?=$passkey64?>'>
            Return to Chat
            <img class='icon20' src='<?=$iconsource_braxarrowright_common?>' style='' />
            </div>
        </td>
        </tr>
        
        </table>
    </div>
        <br>
        <br>
        <br>
    <div id="status" class="status" ></div>
