<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");
require_once("crypt-pdo.inc.php");

    $braxchat2 = "<img src='../img/braxchat-square.png' style='position:relative;top:3px;height:15px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;margin:0' />";
    $braxchat = "<img src='../img/braxchat-128.png' style='height:30px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = @tvalidator("ID",$_POST['providerid']);
    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $chatid = @tvalidator("ID",$_POST['chatid']);
    $lifespan =  @tvalidator("PURIFY",$_POST['lifespan']);
    $passkey = @tvalidator("PURIFY",$_POST['passkey']);
    $passkey64 =  @tvalidator("PURIFY",$_POST['passkey64']);
    $title = base64_encode(@tvalidator("PURIFY",$_POST['title']));
    $titledecoded = stripslashes(@tvalidator("PURIFY",$_POST['title']));
    $find = rtrim(stripslashes(@tvalidator("PURIFY",$_POST['find'])));
    
    $email_available = false;
    $result = pdo_query("1","
        select replyemail from provider 
            where providerid=? and 
            replyemail not like '%.account@brax.me' and 
            active='Y' and verified='Y'
        ",array($providerid));
    if($row = pdo_fetch($result)){
        $email_available = true;
    }
    
    
    if($passkey64=='' && $passkey!=''){
        $passkey64 = EncryptE2EPasskey($passkey,$providerid);
    }
    
    $modeaction = 'chatinvite';
    $inputdisplaymode = 'display:none';
    $inputdisplaymode2 = 'display:inline';
    
    if( $mode == 'A'){
        $modeaction = 'addchatsession';
        $inputdisplaymode = 'display:none';
    }
    if( $mode == 'N'){
        $inputdisplaymode = '';
        $inputdisplaymode2 = 'display:none';
    }
    $lasttime = '';
    if(isset($_POST['lasttime'])){
        $lasttime = tvalidator("PURIFY",$_POST['lasttime']);
    }

    
    $order = "order by providername asc ";
    $find = ltrim($find);
    if(strlen($find)<3 && $find !=''){
        $find .= " ";
    }
    if($_SESSION['superadmin']=='Y' && rtrim($find) == ''){
        $find = "";
        $order = "order by provider.createdate desc ";
    }
    if($_SESSION['superadmin']!='Y' && rtrim($find) == ''){
        $find = "";
        $order = "order by provider.providername asc ";
    }
        
    $roomdiscovery = "";
    $result = pdo_query("1", "select roomdiscovery from provider where providerid = ? ",array($providerid));
    if($row = pdo_fetch($result)){
        $roomdiscovery = $row['roomdiscovery'];
    }
    if($_SESSION['sponsor']==''){
        $roomdiscovery = "Y";
    }
    
    if( $roomdiscovery == 'Y' || "$find"!='' ){
        $result = pdo_query("1",
           "
            select distinct 
                provider.providername as name, provider.replyemail, 
                provider.handle, provider.avatarurl, 
                provider.providerid, provider.companyname
            from provider where active='Y' and provider.termsofuse is not null
            and (
                  
                  ( (provider.providername like ? or provider.handle like ? ) 
                      
                      and 
                        (   publish='Y'
                        
                            or
                            
                            ( 
                                trim(?) != '' and providerid in 

                                (select providerid from groupmembers where groupid in

                                    (select groupid from groupmembers where providerid = ? )

                                    and groupmembers.providerid = provider.providerid
                                )
                            )
                            
                            or
                            
                            (
                                provider.sponsor = '$_SESSION[sponsor]' and '$_SESSION[sponsor]'!=''
                            )

                            or
                            ( providerid in 
                               (select targetproviderid from contacts 
                               where contacts.providerid = ?)
                            )

                        )
                  )
                   or
                  ( provider.handle = ? or provider.handle = ?  )
                )
            $order
            limit 200
            ",array($find."%","%".$find."%",$find,$providerid,$providerid,$find,"@".$find));
        
        /*
        $result = pdo_query("1",
           "
            select distinct 
                provider.providername as name, provider.replyemail, 
                provider.handle, provider.avatarurl, 
                provider.providerid, provider.companyname
            from provider where active='Y' and provider.termsofuse is not null
            and (provider.providername like '$find%' or provider.handle like '%$find%' )
            and
            ( 
                ( (provider.handle = '$find' or provider.handle = '@$find') and trim('$find')!='')

                or
                (provider.publish='Y'  ) 

                or
                (trim('$find') != '' and providerid in 

                (select providerid from groupmembers where groupid in

                 (select groupid from groupmembers where providerid = $providerid )

                    and groupmembers.providerid = provider.providerid
                    )
                )
                or
                ( trim('$find') != '' and providerid in 
                   (select targetproviderid from contacts 
                   where contacts.providerid = $providerid)
                )

            ) 
            $order
            limit 200
            "
        );
         * 
         */
    } else {
        $result = pdo_query("1",
           "
            select distinct 
                provider.providername as name, provider.replyemail, 
                provider.handle, provider.avatarurl, 
                provider.providerid, provider.companyname
            from provider where active='Y' and provider.termsofuse is not null
            and sponsor = '$_SESSION[sponsor]' and enterprise ='Y' and (sponsorlist='Y' or sponsorlist is null)
            $order
            limit 200
            ");
        
    }

    
        
    

    
    $count = 0;
    while($row = pdo_fetch($result)){
    
        
        $id = $row['replyemail'];
        if( $row['handle']!=''){
            
            $row['replyemail']='';
            $id = $row['handle'];
            
        }
        if( $row['companyname']!='' && $row['handle']==''){
            
            $id = $row['companyname'];
            
        }
        if($id == ''){
            
            continue;
            
        }
        if($row['avatarurl'] == "$rootserver/img/faceless.png"){
            
            $row['avatarurl'] = "$rootserver/img/newbie2.jpg";
            
        }
        
        if($count == 0){
        
            ShowChatInvite(1, $email_available, $chatid);
            
            echo "
                <div class='chatmembers' 
                    style='background-color:whitesmoke;border-width:0px;margin:auto;
                    text-align:center;overflow:hidden'>
                    <br>

                    <div class='formobile'></div>
                ";
            
        }
        $count++;
        
        $name = substr("$row[name]", 0, 20);

        echo "
                <div class='$modeaction chatselectbox pagetitle3  tapped2 gridstdborder rounded shadow' 
                    data-providerid='$row[providerid]' 
                    data-name='$row[name]' 
                    data-mode='S'               
                    data-chatid='$chatid'
                    data-handle='$row[handle]'
                    style='position:relative;display:inline-block;
                    ;overflow:hidden;
                    color:black;background-color:whitesmoke;
                    cursor:pointer;font-weight:300;margin-bottom:10px'
                >
                    <div style='background-color:#21313F;text-align:center;position:relative;left:0px;top:0px;width:100%'>
                    <img class='chatlistphoto1' src='$row[avatarurl]' style='position:relative;top:0px' />
                        </div>
                    <div class=smalltext2 style='text-align:center;width:100%;height:70px;width:95%;padding:5px;overflow:hidden'>
                        <b>$name</b><br>
                        <span class='smalltext2'>$id</span><br>

                    </div>
                </div>
                
            
               ";
        
            if(($count >= 20 && $find=='') || ($count >=49 && $find!='')  ){
                echo "<div style='background-color:whitesmoke;margin:auto;text-align:center;color:firebrick;max-width:300px'>"
                    . "<br><br><b>More contacts or public names available. Please search by name or @handle</b></div>";
                break;
            }
        
        
        
    }
    
    if($count == 0  ){
        ShowChatInvite(1, $email_available, $chatid);
        if($find=='' && $email_available){
            echo "<div style='background-color:whitesmoke;margin:auto;text-align:center;color:firebrick;max-width:300px'>"
                . "<br><br><b>You have no contacts. Invite a person to add to your contact list. You can invite people who are not on $appname.</b></div>";
        }
        echo "<br><br><br></div>";
    } else {
        echo "<br><br><br></div>";
        
    }

    
function ShowChatInvite($count, $email_available, $chatid){
    global $appname;
    global $find;
    global $chatid;
    global $mode;
    global $providerid;
    global $passkey64;
    global $title;
    global $lifespan;
    global $global_titlebar_color;
    global $icon_braxchat2;
    global $global_textcolor;
    global $global_background;
    global $iconsource_braxarrowleft_common;
    global $global_titlebar_color;
    
    $invitearea = "";
    $inputdisplaymode = 'display:none';
    $inputdisplaymode2 = 'display:block';
    if($count == 0 && $find==''){
        
        $inputdisplaymode = 'display:block';
        $inputdisplaymode2 = 'display:none';
        $invitearea = "<div style='background-color:whitesmoke'></div>";
        
    }
    if($mode == 'A'){
    
        $result = pdo_query("1","
            select keyhash from chatmaster where chatid = ?
            ",array($chatid));
        if($row = pdo_fetch($result)){
            $keyhash = $row['keyhash'];
        }
        
        
        echo "
                <div class='gridnoborder setchatsession' style='background-color:$global_titlebar_color;color:white;padding-left:20px;padding-right:20px;padding-bottom:3px;margin:0;'
                    data-chatid='$chatid' data-keyhash='$keyhash'>
                    <img class='icon15' src='$iconsource_braxarrowleft_common' style='padding-left:10px;margin-right:20px;' >
                    <span style='opacity:.5'>
                    $icon_braxchat2
                    </span>
                    <span class='pagetitle2a' style='color:white'>Add Party to Chat</span> 
                </div>
        ";
    }
    if($mode!='A' && ($count > 0 || $find != '')){
        
        $invitearea = 
        "
            <div class='pagetitle2a gridstdborder' style='background-color:$global_titlebar_color;padding-top:0px;padding-left:10px;padding-bottom:3px;text-align:left;color:white;margin:0'> 
            <img class='selectchatlist icon20' data-mode='CHAT' src='../img/Arrow-Left-in-Circle-White_120px.png' 
                style='padding-left:10px;max-height:35px' />            
                &nbsp;
            $icon_braxchat2
            Find a Chat Party
        </div>
        ";
        //$invitearea .= "";
        /*
        "
        <div class='inputfocuscontent pagetitle2' style='background-color:whitesmoke;display:block;padding-top:10px;color:black'>
            <div class='pagetitle3 selectchatlist tapped'  data-mode='CHAT'
                style='background-color:whitesmoke;color:black'>
                &nbsp;<img class='icon20' src='../img/Arrow-Left-in-Circle_120px.png' 
                style='padding-left:10px;max-height:35px' />
                Cancel Chat 
            </div>
        </div>
        ";
         * 
         */
    }
    
    $display = "";
    $action = "startchatbutton";
    if($mode == 'A'){
        $display = 'display:none';
        $action = "addchatbutton";
    }
    
    
    $invitearea .= 
        "
        <div class='pagetitle3' style='$inputdisplaymode2;margin:auto;text-align:center;background-color:whitesmoke'>
                <div class='formobile'><br></div>
                <div class='chatmembers inputfocuscontent'>
                    <br>
                    <center>
                    <div class='pagetitle'>Find Chat Party</div>
                    <input class='dataentry mainfont chatselectfind'  type='text' size=20 value='$find'              
                            placeholder='Name or Handle'
                            style='max-width:200px;background-color:ivory;padding-left:5px;'/>
                    <img class='icon25 $action' 
                        data-chatid='$chatid' 
                        data-providerid=$providerid 
                        data-mode='$mode' 
                        data-passkey64='$passkey64' 
                        data-titlebase64='$title'
                        data-lifespan='$lifespan'
                        src='../img/Arrow-Right-in-Circle_120px.png' 
                    style='top:8px' />
                    </center>
                </div>
                <br>
                <div class='showchatinvite divbutton6 mainfont gridstdborder' style='$display;background-color:whitesmoke;color:black'>
                    Send Email Invite to Unlisted Party 
                    <img class='icon15' src='../img/Arrow-Right_120px.png' style='top:3px' />
                </div>
                <br><br>
        </div>
        ";
    if($email_available == false){
        echo $invitearea;
        echo 
             "
            <table class='newchatinvite' style='padding-left:20px;padding-right:20px;background-color:white;$inputdisplaymode;width:100%;border-width:1px;border-color:black'>
            <tr style='text-align:left'>
                <td>
                <br><br><br><br>
                <div class='tipbubble gridstdborder pagetitle2a'>
                    In order to send an email invitation, your own email needs to be entered 
                    and validated. Please enter a valid email address in SETTINGS - MY IDENTITY and
                    respond to the verification email that will be sent to you.<br><br>
                    After your email is verified, you may freely use this feature!
                    
                </div>
                </td>
             </tr>
             </table>
             ";
        return;
    }
    //New Invite Input Area
    $invitearea .= 
        "
        <table class='newchatinvite' style='padding-left:20px;padding-right:20px;background-color:whitesmoke;$inputdisplaymode;width:100%;border-width:1px;border-color:black'>
        <tr style='text-align:left'>
            <td>
                <br><br>
                <span class=pagetitle2 style='color:black;'>
                <b>Send Email Invite to Unlisted Party</b>
                <br>
                </span>
            </td>
        </tr>
        <tr>
            <td>
            <table style='background-color:whitesmoke'>
            <tr>
                <td style='color:black;text-align:right'>
                </td>
                <td>
                <input class='inputfocus invitechatname' id='invitechatname' placeholder='Name of Party' type=text size=30>
                <br>
                </td>
            </tr>
            <tr>
                <td style='color:black;text-align:right'>
                </td>
            </tr>
            <tr>
                <td style='color:black;text-align:right'>
                </td>
                <td>
                <span class='pagetitle3'>
                <input class='inputfocus invitechatemail hidecontactbook' id='invitechatemail' placeholder='Email' type=text size=30>
                </td>
            </tr>
            <tr>
                <td style='color:black;text-align:right'>
                </td>
                <td style='color:black;'>
                    <div id='addressbookcontent' name='addressbookcontent' class='addressbookcontent' style='display:none'></div>
                </td>
            </tr>
            <tr>
                <td style='color:black;text-align:right'>
                </td>
                <td>
                <input class='inputfocus invitechatsms hidecontactbook' id='invitechatsms' placeholder='Text Phone' type=text size=30>
                <br>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                <img class='chatinvite tapped icon30' data-mode='' src='../img/Arrow-Right-in-Circle_120px.png' style='max-height:35px'>
                </td>
            </tr>
            </table>
            <br>
            </td>
        </tr>
        </table>
            
            
        ";        
    
    echo "$invitearea";
}    
    
?>