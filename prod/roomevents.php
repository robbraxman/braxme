<?php
session_start();
require("validsession.inc.php");
require_once("config-pdo.php");

    //$replyflag = tvalidator("PURIFY",$_POST[replyflag]);
    $providerid = tvalidator("ID",$_POST['providerid']);

    $mode = @tvalidator("PURIFY",$_POST['mode']);
    $roomid = @tvalidator("ID",$_POST['roomid']);
    $eventname = @tvalidator("PURIFY",$_POST['eventname']);
    $eventdesc = @tvalidator("PURIFY",$_POST['eventdesc']);
    $eventdate = @tvalidator("PURIFY",$_POST['eventdate']);
    $eventtime = @tvalidator("PURIFY",$_POST['eventtime']);
    $eventid = @tvalidator("PURIFY",$_POST['eventid']);

    //Detect MM/DD/YYYY format from Firefox/IE
    $datehold = explode("/",$eventdate);
    if(strlen($datehold[0])==2)
    {
        $test = new DateTime($eventdate);
        $eventdate = date_format($test, 'Y-m-d'); // 2011-03-03 00:00:00
    }
    
    //Detect MM/DD/YYYY format from Firefox/IE
    $timehold = explode(":",$eventtime);
    if($eventtime!='' && strlen($timehold[1])>2)
    {
        $min = substr($timehold[1],2);
        $am = strtolower(ltrim(substr($timehold[1],2,3)));
        $houradd = 0;
        if($am=='pm'){
            $houradd = 12;
        }
        $hour = $timehold[0]+$houradd;
        $eventtime = "$hour:$min";
    }
    
    
    
    $dot = "<img class='unreadicon' src='../img/dot.png' style='height:10px;width:auto;padding-top:3;padding-right:2px;padding-bottom:3px;' />";
    $braxsocial = "<img class='icon20' src='../img/arrow-stem-circle-left-128.png' style='' />";
    $calendaricon= "<img class='icon20' src='../img/calendar-128.png' style='' />";
    //$braxsocial = "<img src='../img/braxroom-square.png' style='position:relative;top:3px;height:25px;width:auto;padding-top:0;padding-right:2px;padding-bottom:0px;' />";
    if( $mode == 'S')
    {
        if( $eventname !='' && $eventdate!='' )
        {
            if($eventid == '')
            {
                $result = pdo_query("1"," 
                insert into events (roomid, eventname, eventdesc, eventdate, eventtime, providerid, createdate, status, notificationstatus, timezone )
                values
                    ($roomid,'$eventname', '$eventdesc', 
                        date_add('$eventdate $eventtime',INTERVAL ($_SESSION[timezoneoffset])*(-1) HOUR), 
                        '$eventtime', $providerid, now(), 'Y','', $_SESSION[timezoneoffset] )
                       ");
            }
            else 
            {
                $result = pdo_query("1"," 
                update events set eventname = '$eventname', timezone = $_SESSION[timezoneoffset],
                    eventdate=date_add('$eventdate $eventtime',INTERVAL ($_SESSION[timezoneoffset])*(-1) HOUR), eventtime='$eventtime', eventdesc='$eventdesc' 
                    where eventid = $eventid and providerid = $providerid and roomid=$roomid
                       ");
            
            }
        $mode = '';
        }
    }
    if( $mode == 'D')
    {
        $result = pdo_query("1"," 
            delete from events where eventid = $eventid and providerid = $providerid and roomid=$roomid
                   ");
        $mode = '';
    }
    
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    if( $mode == 'A' || $mode == 'E')
    {
        if($mode == 'A'){
            $action = 'Save';
        }
        if($mode == 'E'){
            $action = 'Edit';
            $result = pdo_query("1","
                select date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%Y-%m-%d') as eventdate, 
                       date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%k:%i') as eventtime, 
                       eventname, eventdesc
                from events where roomid=$roomid and eventid=$eventid and providerid=$providerid
                    ");
            if($row=pdo_fetch($result)){
                $eventdate = "$row[eventdate]";
                $eventtime = "$row[eventtime]";
                $eventname = "$row[eventname]";
                $eventdesc = "$row[eventdesc]";
            }
        }
?>
    <br>
    <span class='pagetitle' style='color:black'>&nbsp;Room Events</span> 
    <br>
    &nbsp;&nbsp;
    <div class='mainfont roomevents'  style='color:black'
        data-roomid='<?=$roomid?>' data-mode=''>
            &nbsp;&nbsp;<?=$braxsocial?>
                Events List&nbsp;&nbsp;
    </div>

    <div style='margin:0;background-color:transparent;color:black'>
        <table class='gridnoborder mainfont' style='margin:auto;text-align:left;' >
            <tr style='padding:5px'>
                <td style='width:10%;text-align:right;padding-right:5px'></td>
                <td class='pagetitle2'  style='color:black'>
                    <?=$action?> Event
                </td>
            </tr>
            <tr style='padding:5px'>
                <td style='width:10%;text-align:right;padding-right:5px'>Event Date</td>
                <td style='text-align:left'>
                    <input class='eventdate mainfont' placeholder='MM/DD/YYYY' type='date' value='<?=$eventdate?>'/>
                </td>
            </tr>
            <tr style='padding:5px'>
                <td style='width:10%;text-align:right;padding-right:5px'>Event Time</td>
                <td style='text-align:left'>
                    <input class='eventtime mainfont' placeholder='00:00AM' type='time' size=8 value='<?=$eventtime?>'/>
                </td>
            </tr>
            <tr style='padding:5px'>
                <td style='width:10%;text-align:right;padding-right:5px'>Event Name</td>
                <td style='text-align:left;'>
                    <input class='eventname dataentry mainfont' placeholder='Event Name' type='text' size='30' maxlength='45' value='<?=$eventname?>' style=''/>
                </td>
            </tr>
            <tr style='padding:5px'>
                <td style='width:10%;text-align:right;padding-right:5px'>Event Description</td>
                <td >
                    <textarea class='mainfont eventdesc dataentry' 
                              placeholder='Event Description' cols='50' rows='3' size='30' 
                              style='' 
                    ><?=$eventdesc?></textarea>
                </td>
            </tr>
            <tr style='padding:5px'>
                <td  style='width:10%;text-align:right;padding-right:5px'></td>
                <td >
                    <br>
                    <div class='divbutton3 divbutton3_unsel roomevents' data-eventid='<?=$eventid?>' data-roomid='<?=$roomid?>' data-mode='S'>
                        <?=$action?> Event
                    </div>
                    <br><br><br><br>
                </td>
            </tr>
            
        </table>
    </div>

<?php
    exit();
    }
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    /*******************************************************/
    
    
    
    
?>    
    <br>
    
    <span class='pagetitle2' style='color:black'>&nbsp;<b>Room Events</b></span> 
    <br>
    &nbsp;&nbsp;<span class="smalltext">Automated Notifications are sent for each event.</span>
    <br>
    &nbsp;&nbsp;
    <div class='mainfont feed tapped'  style='color:black'
        id='feed' data-roomid='<?=$roomid?>' >
            &nbsp;&nbsp;<?=$braxsocial?>
                Room&nbsp;&nbsp;
    </div>
    &nbsp;&nbsp;
    <div class='mainfont roomevents tapped'  style='color:black'
         data-roomid='<?=$roomid?>' data-mode='A'>
            &nbsp;&nbsp;<img class='icon20' src='../img/add-circle-128.png' style='' />
            New Event
    </div>
                
<?php
$result = pdo_query("1","
        select date_format(date_add(eventdate,INTERVAL $_SESSION[timezoneoffset] HOUR),'%b %d, %y %a %h:%i%p') as eventdate, eventtime,
        eventname, eventdesc, eventid, events.providerid, events.timezone, provider.providername, provider.name2 from 
        events 
        left join provider on provider.providerid = events.providerid
        where roomid=$roomid
        and eventdate > now()
        order by eventdate asc 
        ");
while($row = pdo_fetch($result)){
    $delete = "";
    $edit = "";
    if($providerid == $row['providerid'])
    {
        $delete = 
                "
                <img class='roomevents tapped' data-mode='D' data-roomid='$roomid'
                    data-eventid='$row[eventid]'
                src='../img/delete-gray-128.png' 
                style='height:15px;float:right;cursor:pointer'
                />
                ";
        $edit = 
                "&nbsp;&nbsp;&nbsp;&nbsp;
                <div class='roomevents tapped' data-mode='E' data-roomid='$roomid'
                    data-eventid='$row[eventid]'
                style='display:inline;height:15px;cursor:pointer;color:#00A0E3'
                >
                Edit</div>
                ";
    }

    echo "
        <div class='mainfont gridstdborder'
            style='cursor:pointer;margin-top:5px;
            padding:10px;background-color:white;color:black'
            >
            <div class='pagetitle2a' style='margin:3px'>$calendaricon $row[eventname]</div>
            $row[eventdate]<br>
            $row[eventdesc]<br>
            $delete Created by: $row[providername] $edit
               
        </div>
        ";
}


?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
