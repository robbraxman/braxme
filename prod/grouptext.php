<?php
session_start();
require_once("validsession.inc.php");
require_once("config-pdo.php");

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = tvalidator("ID",$_POST['roomid']);
    if( $mode == '2')
    {
        $fieldselect = "";
        $result = pdo_query("1","
                select credentialname from credentialrequest where providerid = $_SESSION[pid]
                and roomid = $roomid order by seq
                ");
        while($row = pdo_fetch($result))
        {
            if($fieldselect!='')
            {
                $fieldselect .= ",\r\n";
            }
            $fieldselect .= "$row[credentialname]=*";
        }
        echo "$fieldselect";
        exit();
    }

    $selectroom = "<select class='grouptextroom' id='grouptextroomid' name='textroomid'  style='width:250px'>";
    $result = pdo_query("1","
            select distinct room, roomid, 
            (select count(*) from statusroom s2 where s2.roomid = statusroom.roomid ) as count,
            (select count(*) from csvtemp where csvtemp.roomid = statusroom.roomid ) as countsms
            from statusroom where owner = $_SESSION[pid] order by room
            ");
    while($row = pdo_fetch($result))
    {
        $roomname = htmlentities($row['room']);
        $selectroom .= "<option value='$row[roomid]'>$roomname ($row[count]/$row[countsms])</option>";
    }
    $selectroom .= "</select>";
        

?>
<div class="" style="background-color:#E5E5E5;color:black;padding:20px">
    <div class='pagetitle2' style="color:black">
        <img src='../img/brax-sms-round-greatlake-128.png' style='position:relative;top:13px;height:35px;width:auto;padding-left:10px;padding-top:0;padding-right:2px;padding-bottom:5px;' />
        &nbsp;
        Group Message
    </div>
   <b><span id="providername"></span></b>&nbsp;&nbsp;&nbsp;&nbsp;    
   <table id="" class="" style="padding-left:10px">
        <tr>
        <td class="dataarea">
            <div class="divbutton divbutton_unsel grouptextsend" id="grouptextsend" data-test=''><b>Send Group Message</b></div>&nbsp;
            <div class="divbutton divbutton_unsel grouptextsend" id="grouptextsend" data-test='Y'><b>Test Group Message</b></div>
            <br>
        </tr>
        
        <tr>
        <td class="dataarea">
        <div class="mainfont" style="color:black">Select Room (members/sms only)</div>
        <?=$selectroom?>
        </td>
        </tr>

        <tr class='filterenablegroup' >
        <td class="dataarea">
        <div class="mainfont filterenable" style='color:skyblue;cursor:pointer;display:none'>Enable Advanced Filter</div>
        </td>
        </tr>
        
        <tr class='filtergroup' style='display:none'>
        <td class="dataarea">
            <div class="mainfont" style="color:black;">Filter by Info Request Values (Advanced)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='filterdisable' style='color:skyblue;cursor:pointer'>Hide</span></div>
            <textarea id="grouptextgroup" class='grouptextfilter dataentry' 
                      rows='5' cols='80'
                      NAME="textgroup"  placeholder='fieldname1=value1,fieldname2=value2,...'></textarea>
        </td>
        </tr>
        
        <tr>
        <td class="dataarea">
        <div class=mainfont style="color:black">SMS/Notification Message (130 Character Max)</div>    
        <textarea id="groupsmstext" class="smstext mobilewidth" NAME="smstext" cols="60" rows="3" maxlength="130" placeholder='Notification/SMS Message'></textarea>
        </td>
        </tr>

        <tr>
        <td class="dataarea">
        <div class=mainfont  style="color:black">Optional Post to Room</div>    
        <textarea id="grouptexttitle" class="texttitle mobilewidth" NAME="texttitle" cols="60" rows="1" placeholder='Title' ></textarea>
        <br>
        <textarea id="grouptext" class="text mobilewidth" NAME="text" cols="60" rows="10" placeholder='Room Post' ></textarea>
        <br>
        <textarea id="grouptextphoto" class="textphoto mobilewidth" NAME="textphoto" cols="60" rows="1" placeholder='Photo URL' ></textarea>
        <br>
              <div class='smalltext' style='display:inline-block;height:50px;width:50px;text-align:center;color:black'>
                  <div class='photoselect' 
                       id='photoselect_icon' data-target='#grouptextphoto' data-album='' 
                       data-src='' data-filename='' data-mode='X' data-caller='grouptext' >

                        <img class='buttonicon' src='../img/brax-photo-round-lawn-128.png' style='cursor:pointer;position:relative;display:inline;height:30px;width:auto;top:0px;' />
                  </div>
                  Share<br>Photo
                  <br>
              </div>
              <div class='smalltext' style='cursor:pointer;display:inline-block;height:50px;width:50px;text-align:center;color:black'>
                  <div class='fileselect' 
                       id='fileselect_icon' data-target='#grouptext' data-album='' 
                       data-src='' data-filename='' data-link=''  data-caller='grouptext' >

                        <img class='buttonicon' src='../img/brax-doc-round-lawn-128.png' style='position:relative;display:inline;height:30px;width:auto;top:0px;' />
                  </div>
                  Share<br>File
                  <br>
              </div>
        </td>
        </tr>

        <tr>
        <td class="dataarea">
        <div class=mainfont  style="color:black">Exclude SMS <input id='excludesms' class='excludesms' name='excludesms' type='checkbox' value='Y' style='position:relative;top:5px' />
        </div>    
            <span class='smalltext' style="color:black">Send to Mobile and Email only</span>
        </td>
        </tr>
        
        
   </table>
</div>    
<script>
    $('#grouptext').val( localStorage.grouptext ); 
    $('#grouptexttitle').val( localStorage.grouptexttitle );
    $('#groupsmstext').val( localStorage.groupsmstext );
    $('#grouptextphoto').val( localStorage.grouptextphoto );
$('#grouptextroomid').val( localStorage.grouptextroomid );
</script>
